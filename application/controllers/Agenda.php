<?php
/**
 * Created by PhpStorm.
 * User: bintzandt
 * Date: 30/11/17
 * Time: 12:47
 */

/**
 * Controller to handle all Agenda related URLs
 * Class Agenda
 */
class Agenda extends _SiteController
{
    /**
     * Agenda constructor.
     * Some functions require superuser access, these are determined here in the constructor.
     */
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->logged_in){
            redirect('/inloggen');
        }
        $protected = array('add', 'edit', 'save', 'submit', 'delete');
        if (in_array($this->router->method, $protected) && !$this->session->superuser){
            show_error("Je bent niet bevoegd!");
        }
        $this->load->model('agenda_model');
    }

    /**
     * The index funtion shows a list of upcoming events to the user.
     */
    public function index(){
        if (($data['events'] = $this->agenda_model->get_event()) !== FALSE){
            foreach ($data['events'] as $event){
                $event->aanmeldingen = $this->agenda_model->get_aantal_aanmeldingen($event->event_id);
            }
            $this->loadView('agenda/index', $data);
        } else {
            echo "NO EVENTS";
        }
    }

    /**
     * Function to visualize the upcoming event.
     * @param null $event_id if event_id is specified, show the specified event, else show the index.
     */
    public function id($event_id = NULL){
        if ($event_id === NULL){
            $this->index();
        }
        else {
            $event = $this->agenda_model->get_event($event_id);
            if (empty($event)){
                show_404();
            }
            $data['event'] = $event;
            $data['success'] = $this->session->flashdata('success');
            $data['fail'] = $this->session->flashdata('fail');
            $data['aangemeld'] = ($this->agenda_model->get_aantal_aanmeldingen($event_id, $this->session->id) == 1);
            $data['inschrijvingen'] = $this->agenda_model->get_inschrijvingen($event_id, NULL, 5);
            if (empty($data['inschrijvingen'])) unset($data['inschrijvingen']);

            $this->loadView('agenda/id', $data);
        }
    }

    /**
     * Requires superuser access.
     * Creates a page to add a new event to the database.
     * todo: Use CodeIgniter's FormHelper class to generate the form.
     */
    public function add(){
        $data['edit_mode'] = false;
        $this->loadViewBeheer('beheer/agenda/edit_add', $data);
    }

    /**
     * Requires superuser access.
     * @param int $id Specifies which event is to be edited.
     * Creates a page on which the user can edit the event.
     */
    public function edit($id){
        $data['edit_mode'] = true;
        $data['event'] = $this->agenda_model->get_event($id);
        if (empty($data['event'])) show_404();
        $this->loadViewBeheer('beheer/agenda/edit_add', $data);
    }

    /**
     * Requires superuser access.
     * Handles the actual request to save the event in the database.
     * For editing only!
     */
    public function save(){
        $data = $this->input->post(NULL, TRUE);
        $data['van'] = date_format(date_create($data['van']), 'Ymd');
        $data['tot'] = date_format(date_create($data['tot']), 'Ymd');
        $data['inschrijfdeadline'] = date_format(date_create($data['inschrijfdeadline']), 'Ymd');

        if ($data['soort'] === 'nszk') {
            $data['slagen'] = json_encode($data['slagen']);
        } else {
            unset($data['slagen']);
        }
        if ($this->agenda_model->update_event($data) > 0){
            $this->session->set_flashdata('success', "Het evenement is succesvol bewerkt!");
        } else {
            $this->session->set_flashdata('fail', "Het evenement is niet veranderd!");
        }
        redirect('/beheer/agenda');
    }

    /**
     * Requires superuser access.
     * Handles the creates to save the event in the database.
     * For a new event only!
     */
    public function submit(){
        $data = $this->input->post(NULL, TRUE);

        $data['van'] = date_format(date_create($data['van']), 'Ymd');
        $data['tot'] = date_format(date_create($data['tot']), 'Ymd');
        $data['inschrijfdeadline'] = date_format(date_create($data['inschrijfdeadline']), 'Ymd');
        if ($data['soort'] === 'nszk') {
            //we moeten de slagen verwerken naar json
        } else {
            unset($data['slagen']);
        }
        if ($this->agenda_model->add_event($data) > 0){
            $this->session->set_flashdata('success', "Het evenement is succesvol toegevoegd!");
        } else {
            $this->session->set_flashdata('fail', "Er is iets fout gegaan!");
        }
        redirect('/beheer/agenda');
    }

    /**
     * Deletes an event from the database.
     * @param integer $id the event_id which is to be deleted.
     */
    public function delete($id){
        if ($this->agenda_model->delete($id) > 0 ){
            $this->session->set_flashdata('success', "Het evenement is verwijderd!");
        } else {
            $this->session->set_flashdata('fail', "Er is iets fout gegaan!");
        }
        redirect('/beheer/agenda');
    }

    public function nszk(){
        $data = $this->input->post(NULL, TRUE);
        $data['member_id'] = $this->session->id;

        $slagen = array();

        if ($data['opmerking'] === "") unset($data['opmerking']);

        if ($data['event_soort'] === 'nszk'){
            for ($i = 0; $i < sizeof($data['slag']); $i++){
                if ($data['tijd'][$i] !== "") {
                    $slagen[$data['slag'][$i]] = $data['tijd'][$i];
                }
            }
            $slagen = json_encode($slagen);
            $data['slagen'] = $slagen;
            if (sizeof($data['slagen']) === 0) {
                unset($data['slagen']);
            }
        }

        unset($data['slag']);
        unset($data['tijd']);
        unset($data['event_soort']);

        if ($this->agenda_model->aanmelden($data)) {
            $this->loadView('agenda/nszk_form', array('nszk_id' => $data['event_id']));
        }
        else {
            $this->session->set_flashdata('fail', "Er is iets mis gegaan met je aanmelding.");
            redirect('/agenda/id/' . $data['event_id']);
        }
    }

    public function nszk_inschrijven(){
        $data = $this->input->post(NULL, TRUE);
        $data['member_id'] = $this->session->id;
        if ($this->agenda_model->nszk_inschrijving($data)) {
            $this->session->set_flashdata('success', "Je bent aangemeld voor dit NSZK!");
        }
        else {
            $this->session->set_flashdata('fail', "Er is iets mis gegaan met je aanmelding.");
        }
        redirect('/agenda/id/' . $data['nszk_id']);
    }

    /**
     * Function for joining an event.
     * For security purposes we get the id from the session, could be faked otherwise.
     */
    public function aanmelden(){
        $data = $this->input->post(NULL, TRUE);
        $data['member_id'] = $this->session->id;

        if ($data['opmerking'] === "") unset($data['opmerking']);

        unset($data['slag']);
        unset($data['tijd']);
        unset($data['event_soort']);

        if ($this->agenda_model->aanmelden($data)) {
            $this->session->set_flashdata('success', "Aanmelden gelukt!");
        } else {
            $this->session->set_flashdata('fail', "Het is niet gelukt om je aan te melden.");
        }
        redirect('/agenda/id/' . $data['event_id']);
    }

    /**
     * Function to cancel joining an event.
     * @param integer $event_id specifies for which event the user wants to cancel.
     */
    public function afmelden($event_id, $id = NULL){
        if ($id === NULL ) {
            $id = $this->session->id;
            if ($this->agenda_model->afmelden($id, $event_id)) {
                $this->session->set_flashdata('success', "Afmelden gelukt!");
            } else {
                $this->session->set_flashdata('fail', "Het is niet gelukt om je af te melden.");
            }
            redirect('/agenda/id/' . $event_id);
        }
        else {
            if ($this->agenda_model->afmelden($id, $event_id)) {
                $this->session->set_flashdata('success', "Afmelden gelukt!");
            } else {
                $this->session->set_flashdata('fail', "Het is niet gelukt de persoon af te melden.");
            }
            redirect('/beheer/inschrijvingen/' . $event_id);
        }
    }
}