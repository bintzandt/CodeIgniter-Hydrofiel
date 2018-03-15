<?php
/**
 * Class Mail
 * Displays all Mail related activities in the beheer panel
 */
class Mail extends _BeheerController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('mail_model');
    }

    /**
     * Show the default mail page
     */
    public function index(){
        $this->load->helper('form', 'url');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('onderwerp', 'onderwerp', 'required');
        // Check if the submitted form is valid according to the rules above
        if ($this->form_validation->run() == FALSE) {
            // Form is not valid
            $data['success'] = $this->session->flashdata('success');
            $data['fail'] = $this->session->flashdata('fail');
            $data['leden'] = $this->profile_model->get_profile();
            $this->loadView('beheer/mail/mail', $data);
        }
        else {
            // Form is valid
            $config['upload_path'] = '/home/bintza1q/attachments/nederlands';
            $config['allowed_types'] = 'pdf|doc|docx|xlsx|xls|jpg|jpeg|png|gif';
            $config['max_size'] = 2048;

            //Upload dutch attachments
            $this->load->library('upload', $config);
            $this->upload->do_multi_upload('userfile_nl');

            //Upload english attachments
            $config['upload_path'] = '/home/bintza1q/attachments/engels';
            $this->upload->initialize($config);
            $this->upload->do_multi_upload('userfile_en');

            //Get all the form data
            $data = $this->input->post(NULL, TRUE);
            //Set as flashdata (bit hacky, but if it works... :D)
            $this->session->set_flashdata('data', $data);
            //Redirect to the actual mail send page
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