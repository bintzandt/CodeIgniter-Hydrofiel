<?php
/**
 * Created by PhpStorm.
 * User: bintzandt
 * Date: 02/02/18
 * Time: 23:34
 */

class _BeheerController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->logged_in){
            redirect('/inloggen', '');
        }

        if (!$this->session->superuser){
            show_error("Je hebt geen toegang tot het beheer gedeelte!");
        }
        $this->load->model('profile_model');
        $this->load->model('agenda_model');
        if(ENVIRONMENT === 'development') {
            $this->output->enable_profiler(TRUE);
        }
    }

    protected function loadView($view, $data = NULL){
        $this->load->view('templates/header');
        $this->load->view('templates/beheermenu');
        ($data===NULL) ? $this->load->view($view) : $this->load->view($view, $data);
        $this->load->view('templates/footer');
    }
}