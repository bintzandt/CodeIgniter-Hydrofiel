<?php
/**
 * Created by PhpStorm.
 * User: bintzandt
 * Date: 02/02/18
 * Time: 22:51
 */

class Agenda extends _BeheerController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(){
        $data['success'] = $this->session->flashdata('success');
        $data['fail'] = $this->session->flashdata('fail');
        $data['events'] = $this->agenda_model->get_event();
        $this->loadView('beheer/agenda/agenda', $data);
    }

    public function inschrijvingen($event_id, $member_id = NULL){
        if ($member_id !== NULL){
            $inschrijving = $this->agenda_model->get_inschrijvingen($event_id, $member_id);
            if (!empty($inschrijving)){
                $data["inschrijving"] = $inschrijving[0];
                $data["event_id"] = $event_id;
                if ($this->agenda_model->is_nszk($event_id)) {
                    $data["nszk"] = TRUE;
                    $data["slagen"] = json_decode($data["inschrijving"]->slagen);
                    $data["details"] = $this->agenda_model->get_details($event_id, $member_id);
                }
                else {
                    $data["nszk"] = FALSE;
                }
                $this->loadView('beheer/agenda/inschrijving_detail', $data);
            }
        }
        else {
            $data["inschrijvingen"] = $this->agenda_model->get_inschrijvingen($event_id);
            $data["event_id"] = $event_id;
            if (empty($data["inschrijvingen"])){
                $data["error"] = TRUE;
            }
            $this->loadView('beheer/agenda/inschrijvingen', $data);
        }
    }
}