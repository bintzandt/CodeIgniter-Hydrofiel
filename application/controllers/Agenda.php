<?php

use Spatie\CalendarLinks\Link;

/**
 * Controller to handle all Agenda related URLs
 * Class Agenda
 */
class Agenda extends _SiteController
{
    /**
     * Agenda constructor.
     * Some functions require superuser access.
     * These are determined here in the constructor.
     */
    public function __construct()
    {
        parent::__construct();
        must_be_logged_in();
		load_language_file( 'agenda' );
    }

    /**
     * Function to visualize the upcoming event.
     *
     * @param null $event_id if event_id is specified, show the specified event.
     *                       Otherwise show the index.
     *
     * @return void
     */
    public function id($event_id = null)
    {
        if ($event_id === null) {
            return $this->index();
        }

        $event = $this->agenda_model->get_event($event_id);

        if (empty($event)) {
            show_404();
        }

        if ($this->session->engels) {
            $event->naam = $event->en_naam;
            $event->omschrijving = $event->en_omschrijving;
        } else {
            $event->naam = $event->nl_naam;
            $event->omschrijving = $event->nl_omschrijving;
        }

        $from = date_create($event->van);
        $to = date_create($event->tot);
        $description = preg_replace('/<br ?\/?>/', "\n", $event->omschrijving);
        $description = preg_replace('/<\/?p ?>/', " ", $description);
        $description = strip_tags($description);

        $ical = Link::create($event->naam, $from, $to)
            ->description($description)
            ->address($event->locatie);

        $data['event'] = $event;
        $data['aangemeld'] = ($this->agenda_model->get_aantal_aanmeldingen($event_id, $this->session->id) == 1);
        $data['inschrijvingen'] = $this->agenda_model->get_inschrijvingen($event_id, null);
        $data['aantal_aanmeldingen'] = sizeof($data['inschrijvingen']);
        $data['registration_details'] = $data['aangemeld'] && $event->soort === 'nszk';
        $data['ical'] = $ical->google();

        if (empty($data['inschrijvingen'])) {
            unset($data['inschrijvingen']);
        }

        $this->loadView('agenda/id', $data);
    }

    /**
     * The index funtion shows a list of upcoming events to the user.
     */
    public function index()
    {
        if (($data['events'] = $this->agenda_model->get_event()) === false) {
	        return $this->loadView('agenda/no_events');
        }

        foreach ($data['events'] as $event) {
            $event->aanmeldingen = $this->agenda_model->get_aantal_aanmeldingen($event->event_id);
            if ($this->session->engels) {
                $event->naam = $event->en_naam;
            } else {
                $event->naam = $event->nl_naam;
            }
        }

        return $this->loadView('agenda/index', $data);
    }

    /**
     * Function that allows editing the details for a given event_id
     *
     * @param $event_id int The event of which the details will be edited
     */
    public function edit_details($event_id)
    {
        $inschrijving = $this->agenda_model->get_inschrijvingen($event_id, $this->session->id);
        $is_nszk = $this->agenda_model->is_nszk($event_id);
        if (empty($inschrijving) || !$is_nszk) {
            show_404();
        }
        $data['details'] = $this->agenda_model->get_details($event_id, $this->session->id);
        $data['edit_mode'] = !empty($data['details']);
        $data['nszk_id'] = $event_id;
        $this->loadView('agenda/nszk_form', $data);
    }

    /**
     * POST handler to update the NSZK inschrijving details
     */
    public function update_details()
    {
        $data = $this->input->post(null, true);
        $data['member_id'] = $this->session->id;

        if ($this->agenda_model->update_nszk_inschrijving($data)) {
            success('Je aanmelding is bijgewerkt!');
        } else {
            error('Er is een fout opgetreden.');
        }

        redirect('/agenda/id/' . $data['nszk_id']);
    }

    /**
     * Function to sign up for an NSZK.
     */
    public function nszk()
    {
        $data = $this->input->post(null, true);
        $data['member_id'] = $this->session->id;

        $slagen = [];

        if ($data['opmerking'] === "") {
            unset($data['opmerking']);
        }

        if ($data['event_soort'] === 'nszk') {
            for ($i = 0; $i < sizeof($data['slag']); $i++) {
                if ($data['tijd'][$i] !== "") {
                    $slagen[$data['slag'][$i]] = $data['tijd'][$i];
                }
            }
            $slagen = json_encode($slagen);
            $data['slagen'] = $slagen;
            if ($data['slagen']==='[]') {
                unset($data['slagen']);
            }
        }

        unset($data['slag']);
        unset($data['tijd']);
        unset($data['event_soort']);

        if ( ! $this->agenda_model->aanmelden($data)) {
	        error('Er is iets mis gegaan met je aanmelding.');
	        redirect('/agenda/id/' . $data['event_id']);
        }

	    return $this->loadView('agenda/nszk_form', ['nszk_id' => $data['event_id'], 'edit_mode' => false]);
    }

    /**
     * Function to save the additional information required for an NSZK.
     */
    public function nszk_inschrijven()
    {
        $data = $this->input->post(null, true);
        $data['member_id'] = $this->session->id;

        if ($this->agenda_model->nszk_inschrijving($data)) {
            success('Je bent aangemeld voor dit NSZK!');
        } else {
            error('Er is iets mis gegaan met je aanmelding.');
        }

        redirect('/agenda/id/' . $data['nszk_id']);
    }

    /**
     * Function for joining an event.
     * For security purposes we get the id from the session, could be faked otherwise.
     */
    public function aanmelden()
    {
        $data = $this->input->post(null, true);
        $data['member_id'] = $this->session->id;

        if ($data['opmerking'] === "") {
            unset($data['opmerking']);
        }

        unset($data['slag']);
        unset($data['tijd']);
        unset($data['event_soort']);

        if ($this->agenda_model->aanmelden($data)) {
            success('Aanmelden gelukt!');
        } else {
            error('Het is niet gelukt om je aan te melden.');
        }

        redirect('/agenda/id/' . $data['event_id']);
    }

    /**
     * Function to cancel joining an event.
     *
     * @param integer $event_id specifies for which event the user wants to cancel.
     */
    public function afmelden($event_id, $id = null)
    {
        // If an ID is specified, we assume that this is someone from he board.
	    if ($id !== null) {
		    redirect('/beheer/agenda/afmelden/' . $event_id . '/' . $id );
	    }

	    $event = $this->agenda_model->get_event($event_id);
	    $id = $this->session->id;

        if ( date( 'Y-m-d' ) > $event->afmelddeadline ) {
		    error('Je kunt je niet meer afmelden voor dit evenement.');
	    }
	    elseif ( $this->agenda_model->afmelden( $id, $event_id ) ) {
		    success('Afmelden gelukt!');
	    }
	    else {
		    error('Het is niet gelukt om je af te melden.');
	    }
	    redirect( '/agenda/id/' . $event_id );
    }

    /**
     * Route for displaying the training data.
     */
    public function training(){
        // Get training data.
        $waterpolo = $this->agenda_model->get_training( true );
        $swimming  = $this->agenda_model->get_training();

        // Show the view.
        return $this->loadView( 'agenda/training', [ 'waterpolo' => $waterpolo, 'swimming' => $swimming ] );
    }

    public function view_training( $id ){
        /**
         * @var Training
         */
        $training = $this->agenda_model->view_training( $id );
        $user_id  = $this->session->id;

        $data['event'] = (object) $training;
        $data['aangemeld'] = $training->user_is_registered( $user_id );
        $data['inschrijvingen'] = $training->registrations;
        $data['aantal_aanmeldingen'] = sizeof($data['inschrijvingen']);
        $data['registration_details'] = false;
        
        return $this->loadView( 'agenda/id', $data );
    }

    public function register_training(){
        $user_id = $this->session->id;
        $training_id = $this->input->post( 'event_id', true );

        /**
         * @var Training
         */
        $training = $this->agenda_model->view_training( $training_id );

        try {
            $training->register( $user_id );
            success( "Je bent succesvol aangemeld voor deze training." );
        } catch ( Error $e ){
            error( $e->getMessage() );
        }

        redirect( '/agenda/view_training/' . $training_id );
    }

    public function cancel_training( $training_id ){
        $user_id = $this->session->id;
        /**
         * @var Training
         */
        $training = $this->agenda_model->view_training( $training_id );
        try {
            $training->cancel( $user_id );
            success( "Je bent succesvol afgemeld" );
        } catch ( Error $e ){
            error( $e->getMessage() );
        }

        redirect( '/agenda/view_training/' . $training_id );
    }
}
