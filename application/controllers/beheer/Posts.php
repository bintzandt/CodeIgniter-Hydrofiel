<?php
/**
 * Created by PhpStorm.
 * User: bintzandt
 * Date: 09/04/18
 * Time: 14:23
 */

class Posts extends _BeheerController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('post_model');
    }

    public function index(){
        $data['success'] = $this->session->flashdata('success');
        $data['fail'] = $this->session->flashdata('fail');
        $data['posts'] = $this->post_model->get_posts();
        $this->loadView('beheer/posts/index', $data);
    }

    public function add_post(){
        $post = $this->input->post(NULL, TRUE);
        unset($post['files']);
        if ($this->post_model->add_post($post) > 0 ) $this->session->set_flashdata('success', 'Post is toegevoegd.');
        else $this->session->set_flashdata('fail', 'De post kon niet worden toegevoegd.');
        redirect('beheer/posts');
    }

    public function delete_post($id){
        if ($this->post_model->delete_post($id) > 0) $this->session->set_flashdata('success', 'Post is verwijderd.');
        else $this->session->set_flashdata('fail', 'De post kon niet worden verwijderd.');
        redirect('beheer/posts');
    }

}