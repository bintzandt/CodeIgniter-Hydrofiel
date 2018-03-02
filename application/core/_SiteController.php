<?php
/**
 * Class _SiteController
 * General core controller, contains functions needed by several controllers
 */
class _SiteController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if(ENVIRONMENT === 'development') {
            $this->output->enable_profiler(TRUE);
        }
        $this->load->driver('cache', array('adapter' => 'file'));
    }

    /**
     * Loads the specified view
     * @param $view string Path to the view
     * @param null|array $data Contains the data the view needs
     */
    protected function loadView($view, $data = NULL){
        $this->db->cache_on();
        $menu['hoofdmenus'] = $this->menu_model->hoofdmenu();
        $menu['engels'] = $this->session->userdata('engels');
        $menu['logged_in'] = $this->session->userdata('logged_in');
        $menu['superuser'] = $this->session->userdata('superuser');
        $this->db->cache_off();
        $this->load->view('templates/header');
        $this->load->view('templates/menu', $menu);
        ($data === NULL) ? $this->load->view($view) : $this->load->view($view, $data);
        $this->load->view('templates/footer');
    }

    /**
     * Loads the specified beheer view
     * @param $view
     * @param $data
     * Due to some functions in 'normal' controllers doing beheer stuff this function needs to be here.
     * Once all controllers are correctly seperated this function can be removed
     * TODO: Remove this function once the controllers have been refractored
     */
    protected function loadViewBeheer($view, $data){
        $this->load->view('templates/header');
        $this->load->view('templates/beheermenu');
        $this->load->view($view, $data);
        $this->load->view('templates/footer');
    }
}