<?php
/**
 * Created by PhpStorm.
 * User: bintzandt
 * Date: 02/02/18
 * Time: 23:00
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

    protected function loadView($view, $data = NULL){
        $this->db->cache_on();
        $menu['hoofdmenus'] = $this->menu_model->hoofdmenu();
        $menu['engels'] = $this->session->userdata('engels');
        $menu['logged_in'] = $this->session->userdata('logged_in');
        $menu['superuser'] = $this->session->userdata('superuser');
        $this->db->cache_off();
//        echo '<pre>'; var_dump($this->session->userdata('superuser')); echo '</pre>';
        $this->load->view('templates/header');
        $this->load->view('templates/menu', $menu);
        ($data === NULL) ? $this->load->view($view) : $this->load->view($view, $data);
        $this->load->view('templates/footer');
    }

    protected function loadViewBeheer($view, $data){
        $this->load->view('templates/header');
        $this->load->view('templates/beheermenu');
        $this->load->view($view, $data);
        $this->load->view('templates/footer');
    }
}