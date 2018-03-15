<?php
/**
 * Created by PhpStorm.
 * User: bintzandt
 * Date: 06/12/17
 * Time: 22:14
 */

class Home extends _SiteController
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->engels) {
            $this->lang->load("home", "english");
        }
        else {
            $this->lang->load("home");
        }
    }

    /**
     * Generate the home page
     */
    public function index(){
        $data["engels"] = $this->session->engels;
        $data["events"] = $this->agenda_model->get_event(NULL, 3);
        $data['login'] = $this->session->userdata('logged_in');
        $data["verjaardagen"] = $this->profile_model->get_verjaardagen(3);
        $data["tekst"] = $this->page_model->get(1)->tekst;
        $this->loadView('templates/home', $data);
    }

    /**
     * Function to change language of site
     */
    public function language(){
        if (isset($this->session->engels) && $this->session->engels) {
            $this->session->engels = FALSE;
        } else {
            $this->session->engels = TRUE;
        }
        redirect($this->agent->referrer());
    }
}