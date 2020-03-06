<?php

/**
 * Controller to handle all Agenda related URLs
 * Class Agenda
 */
class Agenda extends _SiteController {
	public Agenda_model $agenda_model;

	/**
	 * Agenda constructor.
	 * Some functions require superuser access.
	 * These are determined here in the constructor.
	 */
	public function __construct() {
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
	public function id( $event_id = null ) {
		if ( $event_id === null ) {
			return $this->index();
		}

		/**
		 * @var $event Event
		 */
		$event = $this->agenda_model->get_event( $event_id );

		if ( empty( $event ) ) {
			show_404();
		}

		$data['event']                = $event;
		$data['aangemeld']            = $event->is_registered();
		$data['inschrijvingen']       = $event->registrations();
		$data['aantal_aanmeldingen']  = $event->nr_of_registrations();
		$data['registration_details'] = $data['aangemeld'] && $event->soort === 'nszk';
		$data['ical']                 = $event->generate_ical_link();

		if ( empty( $data['inschrijvingen'] ) ) {
			unset( $data['inschrijvingen'] );
		}

		$this->loadView( 'agenda/id', $data );
	}

	/**
	 * The index funtion shows a list of upcoming events to the user.
	 */
	public function index() {
		if ( ( $data['events'] = $this->agenda_model->get_event() ) === false ) {
			return $this->loadView( 'agenda/no_events' );
		}

		foreach ( $data['events'] as $event ) {
			$event->aanmeldingen = $this->agenda_model->get_aantal_aanmeldingen( $event->event_id );
			if ( $this->session->engels ) {
				$event->naam = $event->en_naam;
			}
			else {
				$event->naam = $event->nl_naam;
			}
		}

		return $this->loadView( 'agenda/index', $data );
	}

	/**
	 * Function that allows editing the details for a given event_id
	 *
	 * @param $event_id int The event of which the details will be edited
	 */
	public function edit_details( $event_id ) {
		$inschrijving = $this->agenda_model->get_inschrijvingen( $event_id, $this->session->id );
		$is_nszk      = $this->agenda_model->is_nszk( $event_id );
		if ( empty( $inschrijving ) || ! $is_nszk ) {
			show_404();
		}
		$data['details']   = $this->agenda_model->get_details( $event_id, $this->session->id );
		$data['edit_mode'] = ! empty( $data['details'] );
		$data['nszk_id']   = $event_id;
		$this->loadView( 'agenda/nszk_form', $data );
	}

	/**
	 * POST handler to update the NSZK inschrijving details
	 */
	public function update_details() {
		$data              = $this->input->post( null, true );
		$data['member_id'] = $this->session->id;

		if ( $this->agenda_model->update_nszk_inschrijving( $data ) ) {
			success( 'Je aanmelding is bijgewerkt!' );
		}
		else {
			error( 'Er is een fout opgetreden.' );
		}

		redirect( '/agenda/id/' . $data['nszk_id'] );
	}

	/**
	 * Function to save the additional information required for an NSZK.
	 */
	public function nszk_inschrijven() {
		$data              = $this->input->post( null, true );
		$data['member_id'] = $this->session->id;

		if ( $this->agenda_model->nszk_inschrijving( $data ) ) {
			success( 'Je bent aangemeld voor dit NSZK!' );
		}
		else {
			error( 'Er is iets mis gegaan met je aanmelding.' );
		}

		redirect( '/agenda/id/' . $data['nszk_id'] );
	}

	/**
	 * Function for joining an event.
	 * For security purposes we get the id from the session, could be faked otherwise.
	 */
	public function aanmelden() {
		$event_id  = $this->input->post( 'event_id', true );
		$opmerking = $this->input->post( 'opmerking', true );
		$slagen    = $this->input->post( 'slag', true );
		$tijden    = $this->input->post( 'tijd', true );

		$member_id = $this->session->id;

		/**
		 * @var $event Event
		 */
		$event = $this->agenda_model->get_event( $event_id );

		try {
			$event->register( $member_id, $opmerking, $slagen, $tijden );

			// Display a special form when it is a NSZK.
			if ( $event->is_nszk() ) {
				return $this->loadView( 'agenda/nszk_form', [ 'edit_mode' => false, 'nszk_id' => $event_id ] );
			}

			success( 'Aanmelden gelukt!' );
		} catch ( DeadlinePassedError $e ) {
			error( 'Je kunt je niet meer aanmelden voor dit evenement.' );
		} catch ( IsFullError $e ) {
			error( 'Het evenement is al vol.' );
		} catch ( AlreadyRegisteredError $e ) {
			error( 'Je bent al voor dit evenement aangemeld.' );
		}

		redirect( '/agenda/id/' . $event_id );
		exit;
	}

	/**
	 * Function to cancel joining an event.
	 *
	 * @param integer $event_id specifies for which event the user wants to cancel.
	 */
	public function afmelden( $event_id, $id = null ) {
		// If an ID is specified, we assume that this is someone from he board.
		if ( $id !== null ) {
			redirect( '/beheer/agenda/afmelden/' . $event_id . '/' . $id );
		}

		/**
		 * @var $event Event
		 */
		$event = $this->agenda_model->get_event( $event_id );
		$id    = $this->session->id;

		try {
			$event->cancel( $id );
			success( 'Afmelden gelukt!' );
		} catch ( DeadlinePassedError $e ) {
			error( 'De deadline om af te melden is al geweest.' );
		}

		redirect( '/agenda/id/' . $event_id );
	}
}
