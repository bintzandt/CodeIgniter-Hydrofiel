<?php
/**
 * Class Pagina
 * Handles all page related stuff in the beheer panel
 */
class Pagina extends _BeheerController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Show index
     */
    public function index(){
        $this->db->cache_delete('menu', 'hoofdmenu');
        $this->db->cache_delete('page', 'id');
        $data['hoofdpagina'] = $this->page_model->get();
        $data['success'] = $this->session->flashdata('success');
        $data['fail'] = $this->session->flashdata('fail');
        $this->loadView('beheer/pages/pages', $data);
    }

    /**
     * Add a page
     */
    public function toevoegen(){
        //Note: Edit mode is false because we use the same view for editing and adding pages
        //      Only different controllers handle the request.
        $data['edit_mode'] = false;
        $data['hoofdmenu'] = $this->menu_model->hoofdmenu(false);
        $this->loadView('beheer/pages/edit_add', $data);
    }

    /**
     * Edit a page
     * @param null $id int which page needs to be edited
     */
    public function edit($id = NULL){
        //If no id has been provided show a 404
        if ($id===NULL) show_404();
        $data['edit_mode'] = true;
        $data['hoofdmenu'] = $this->menu_model->hoofdmenu(false);
        $data['page'] = $this->page_model->get($id);
        $this->loadView('beheer/pages/edit_add', $data);
    }

    /**
     * Move a page up in the menu
     * @param null $id int which page needs to move up
     * TODO: Make this async using javascript
     */
    public function up($id=NULL){
        $this->db->cache_delete('menu', 'hoofdmenu');
        $this->db->cache_delete('page', 'id');
        if ($id===NULL) redirect('/beheer');
        $result = $this->page_model->get_plaats($id);
        if ($result === FALSE) redirect('/beheer');
        $plaats = $result->plaats;
        if (intval($plaats)===0) {
            $this->session->set_flashdata('fail', 'Deze pagina staat al bovenaan.');
        }
        else {
            $nieuwe_plaats = $plaats - 1;
            $to_move = $this->page_model->get_id($result->submenu, $nieuwe_plaats);
            if ($to_move !== FALSE) {
                $this->page_model->save(array('id'=>$to_move, 'plaats'=>$plaats));
            }
            if ($this->page_model->save(array('id'=>$id, 'plaats'=>$nieuwe_plaats)) === 1){
                $this->session->set_flashdata('success', 'De pagina is succesvol verplaatst.');
            }

        }
        redirect('/beheer');

    }

    /**
     * Move a page down in the menu
     * @param null $id int which page needs to move down
     * TODO: Make this async using javascript
     */
    public function down($id=NULL){
        $this->db->cache_delete('menu', 'hoofdmenu');
        $this->db->cache_delete('page', 'id');
        if ($id===NULL) redirect('/beheer');
        $result = $this->page_model->get_plaats($id);
        if ($result === FALSE) redirect('/beheer');
        $plaats = $result->plaats;
        if ($plaats===$this->page_model->get_max_plaats($result->submenu)) {
            $this->session->set_flashdata('fail', 'Deze pagina staat al onderaan.');
        }
        else {
            $nieuwe_plaats = $plaats + 1;
            $to_move = $this->page_model->get_id($result->submenu, $nieuwe_plaats);
            if ($to_move !== FALSE) {
                $this->page_model->save(array('id'=>$to_move, 'plaats'=>$plaats));
            }
            if ($this->page_model->save(array('id'=>$id, 'plaats'=>$nieuwe_plaats)) === 1){
                $this->session->set_flashdata('success', 'De pagina is succesvol verplaatst.');
            }
        }
        redirect('/beheer');
    }
}