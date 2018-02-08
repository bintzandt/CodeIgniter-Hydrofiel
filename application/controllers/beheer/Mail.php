<?php
/**
 * Created by PhpStorm.
 * User: bintzandt
 * Date: 02/02/18
 * Time: 22:50
 */

class Mail extends _BeheerController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(){
        $this->load->helper('form', 'url');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('onderwerp', 'onderwerp', 'required');
        if ($this->form_validation->run() == FALSE) {
            $data['success'] = $this->session->flashdata('success');
            $data['fail'] = $this->session->flashdata('fail');
            $data['leden'] = $this->profile_model->get_profile();
            $this->loadView('beheer/mail', $data);
        }
        else {
            $config['upload_path'] = '/home/bintza1q/attachments/';
            $config['allowed_types'] = 'pdf|doc|docx|xlsx|xls|jpg|jpeg|png|gif';
            $config['max_size'] = 2048;

            $this->load->library('upload', $config);

            $this->upload->do_multi_upload();

            $data = $this->input->post(NULL, TRUE);
            $this->session->set_flashdata('data', $data);
            redirect('mail/send');
        }
    }
}