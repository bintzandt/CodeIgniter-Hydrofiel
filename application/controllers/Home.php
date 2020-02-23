<?php

class Home extends _SiteController
{
	public CI_Session $session;
	public Agenda_model $agenda_model;
	public Profile_model $profile_model;
	public Post_model $post_model;

    public function __construct()
    {
        parent::__construct();
        load_language_file('home');
    }

    /**
     * Generate the home page
     */
    public function index()
    {
        $data["engels"] = $this->session->engels;
        $data["events"] = $this->agenda_model->get_event(null, 3);
        $data["login"] = $this->session->userdata('logged_in');
        $data["verjaardagen"] = $this->profile_model->get_verjaardagen();
        $data["posts"] = $this->post_model->get_posts();
        $this->loadView('templates/home', $data);
    }

    /**
     * Function to change language of site
     */
    public function language()
    {
    	if ( is_english() ){
		    $this->session->engels = false;
	    } else {
		    $this->session->engels = true;
        }
	    redirect($this->agent->referrer());
    }
}
