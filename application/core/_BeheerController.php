<?php

/**
 * Class _BeheerController
 * Handles loading the beheer view
 * This core controller can be extended with other functionality that needs to be accessed in beheer controllers
 */
class _BeheerController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        must_be_logged_in();
        must_be_admin();

        if (ENVIRONMENT === 'development') {
            $this->output->enable_profiler(true);
        }
    }

    /**
     * Function to load a certain view in a controller
     *
     * @param              $view string Specifies which view needs to be loaded
     * @param null | array $data Specifies which data needs to be passed to the view
     */
    protected function loadView($view, $data = null)
    {
        $this->load->view('templates/header');
        $this->load->view('templates/beheermenu');
        ($data === null) ? $this->load->view($view) : $this->load->view($view, $data);
        $this->load->view('templates/footer');
    }
}
