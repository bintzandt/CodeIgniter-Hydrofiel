<?php
/**
 * Handles all page related stuff
 * Class Page
 */
class Page extends _SiteController
{
    public function __construct()
    {
        parent::__construct();
        $protected = array('toevoegen', 'edit', 'delete');
        if (in_array($this->router->method, $protected) && !$this->session->userdata('superuser')){
            show_error("Je bent niet bevoegd!");
        }
    }

    /**
     * Index function, refers to id
     * @param string $page Which page needs to be shown
     */
    public function index($page = '1'){
        $this->id($page);
    }

    /**
     * Actual function to get page contents
     * @param string $page
     */
    public function id($page = '1'){
        $this->db->cache_on();
        //Get the page from the model
        $data['pagina'] = $this->page_model->view($page);

        //Check if this is an actual page
        if (empty($data['pagina'])){
            show_404();
        }
        //Check if we need to be logged in to visit this page
        if ($data['pagina']['ingelogd'] && !$this->session->logged_in){
            redirect('/inloggen');
        }
        //If this is not a CMS page we redirect to the correct URL
        //TODO: Is this still used?
        if ($data['pagina']['cmspagina'] === 'nee'){
            redirect($data['pagina']['navigatie']);
        }
        //Check if we are in an English setting
        if ($this->session->engels) {
            $data['tekst'] = $data['pagina']['engels'];
        }
        else {
            $data['tekst'] = $data['pagina']['tekst'];
        }
        $this->db->cache_off();
        $this->loadView('templates/page', $data);
    }

    /**
     * Function to add a new page
     * TODO: Move this to the beheer/page controller
     */
    public function toevoegen(){
        $data = $this->input->post(NULL, TRUE);
        if ($data['hoofdmenu']) {
            $data['submenu'] = 'A';
            $data['plaats'] = $this->page_model->make_room($data['submenu'], $data['na']);
        }
        else {
            $data['submenu'] = $data['na'];
            $data['plaats'] = $this->page_model->get_max_plaats($data['submenu']);
        }
        unset($data['hoofdmenu'], $data['na']);
        if (($result = $this->page_model->add($data)) === 1) {
            $this->session->set_flashdata('success', 'De pagina is succesvol toegevoegd!');
            redirect('/beheer');
        }
        else {
            $this->session->set_flashdata('fail', 'Er is iets fout gegaan. Probeer het later opnieuw');
            redirect('/beheer');
        }
    }

    /**
     * Function to edit a page
     * TODO: Move this function to the beheer/page controller
     */
    public function edit(){
        $data = $this->input->post(NULL, TRUE);
        $page = $this->page_model->view($data['id']);
        if ($data['hoofdmenu']) {
            $data['submenu'] = 'A';
        }
        else {
            $data['submenu'] = $data['na'];
        }
        unset($data['hoofdmenu'], $data['na']);
        $diff = array_diff_assoc($data, $page);
        $diff['id'] = $data['id'];
        if (isset($diff['submenu'])){
            show_error("Je kunt dit alleen in de database aanpassen.");
        }
        if (($result = $this->page_model->save($diff)) === 1) {
            $this->session->set_flashdata('success', 'De pagina is succesvol opgeslagen!');
            redirect('/beheer');
        }
        else {
            $this->session->set_flashdata('fail', 'Er is iets fout gegaan of je hebt niets gewijzigd. Probeer het later opnieuw');
            redirect('/beheer');
        }
    }

    /**
     * Function to delete a page
     * @param $id int ID of the page to be deleted
     * TODO: Move this function to the beheer/page controller
     */
    public function delete($id){
        if ($this->page_model->delete($id) > 0) {
            $this->session->set_flashdata('success', 'De pagina is verwijderd!');
        }
        else {
            $this->session->set_flashdata('fail', 'Er is iets fout gegaan.');
        }
        redirect('/beheer');
    }
}