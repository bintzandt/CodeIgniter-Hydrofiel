<?php
class Leden extends _BeheerController {
    public function __construct()
    {
        parent::__construct();
    }

    public function index(){
        $data['success'] = $this->session->flashdata('success');
        $data['fail'] = $this->session->flashdata('fail');
        $data['leden'] = $this->profile_model->get_profile();
        $this->loadView('beheer/leden/leden', $data);
    }

    public function importeren(){
        $this->load->helper('form');
        $this->loadView('beheer/leden/importeren');
    }


}