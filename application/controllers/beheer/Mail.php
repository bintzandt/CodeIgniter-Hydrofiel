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
        $this->load->model('mail_model');
    }

    public function index(){
        $this->load->helper('form', 'url');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('onderwerp', 'onderwerp', 'required');
        if ($this->form_validation->run() == FALSE) {
            $data['success'] = $this->session->flashdata('success');
            $data['fail'] = $this->session->flashdata('fail');
            $data['leden'] = $this->profile_model->get_profile();
            $this->loadView('beheer/mail/mail', $data);
        }
        else {
            $config['upload_path'] = '/home/bintza1q/attachments/nederlands';
            $config['allowed_types'] = 'pdf|doc|docx|xlsx|xls|jpg|jpeg|png|gif';
            $config['max_size'] = 2048;

            $this->load->library('upload', $config);
            $this->upload->do_multi_upload('userfile_nl');

            $config['upload_path'] = '/home/bintza1q/attachments/engels';
            $this->upload->initialize($config);


            $this->upload->do_multi_upload('userfile_en');

            $data = $this->input->post(NULL, TRUE);
            $this->session->set_flashdata('data', $data);
            redirect('mail/send');
        }
    }

    public function vrienden(){
        $vrienden = $this->mail_model->get_vrienden();
        if ($vrienden != NULL) {
            $vrienden = $vrienden->vrienden_van;
        } else {
            $vrienden = '';
        }
        $this->loadView('beheer/mail/vrienden', array("mailadressen" => $vrienden));
    }

    public function save_vrienden(){
        $data = $this->input->post(NULL, TRUE);
        $this->mail_model->set_vrienden($data['vrienden_van']);
        $this->session->set_flashdata('success', "De vrienden zijn opgeslagen");
        redirect('/beheer/mail');
    }
}