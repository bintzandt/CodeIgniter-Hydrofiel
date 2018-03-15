<?php
/**
 * Class Inloggen
 * Handles shit related to logging in on the website.
 */
class Inloggen extends _SiteController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('login_model');
        $this->load->model('profile_model');
        if ($this->session->engels) {
            $this->lang->load("inloggen", "english");
        }
        else {
            $this->lang->load("inloggen");
        }
    }

    /**
     * Show the default login page
     */
    public function index(){
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'email', 'required|valid_email',
            array('required' => 'Je moet een %s opgeven!',
                  'valid_email' => 'Email en/of wachtwoord onjuist.'));
        $this->form_validation->set_rules('wachtwoord', 'wachtwoord', 'required',
            array('required' => 'Je moet een %s opgeven.'));

        if ($this->session->logged_in){
            $this->session->sess_destroy();
            $this->session->logged_in = false;
        }

        $data['success'] = $this->session->flashdata('success');
        $data['fail'] = $this->session->flashdata('fail');

        if ($this->form_validation->run() == FALSE) {
            parent::loadView('inloggen/index', $data);
        } else {
            $this->verify_login();
        }
    }

    /**
     * Verify the login
     */
    public function verify_login(){
        $data = $this->input->post(NULL, TRUE);
        $email = strtolower($data['email']);
        $wachtwoord = $data['wachtwoord'];

        $login = $this->login_model->get_hash($email);
        $hash = $login->wachtwoord;

        if (password_verify($wachtwoord, $hash)){
            if (password_needs_rehash($hash, PASSWORD_DEFAULT)){
                $hash = password_hash($wachtwoord, PASSWORD_DEFAULT);
                $this->login_model->set_hash($email, $hash);
            }
            $userdata = array(
                'id' => $login->id,
                'superuser' => ($login->rank <= 2),
                'logged_in' => true,
                'engels' => $login->engels
            );
            $this->session->set_userdata($userdata);
            $this->login_model->unset_recovery($login->id);
            if (!empty(explode('/inloggen', $data['referer'], -1))) {
                redirect('');
            }
            redirect($data['referer']);
        }
        else {
             $this->session->set_flashdata('fail', 'Email en/of wachtwoord onjuist.');
             redirect('/inloggen');
        }
    }

    /**
     * Show password forgotten page
     */
    public function forgot_password(){
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'emailadres', 'valid_email|required',
            array('required' => 'Je moet een %s invullen.',
                  'valid_email' => 'Het ingevulde %s is niet geldig.'));
        if ($this->form_validation->run() == FALSE) {
            parent::loadView('inloggen/wachtwoord_vergeten');
        }
        else {
            $this->reset();
        }
    }

    /**
     * Private function to send recovery email
     * @param $email string Receiving emailadress
     * @param $recovery string The recovery that the user can use
     * @param $valid string until which time is the recovery valid
     * @return true | false
     */
    private function send_password_recovery_mail($email, $recovery, $valid){
        $data = array(
            'recovery' => $recovery,
            'valid' => $valid
        );
        $this->email->to($email);
        $this->email->from('bestuur@hydrofiel.nl','Hydrofiel wachtwoordherstel');
        $this->email->subject("Wachtwoord vergeten 🐰");
        $this->email->message($this->load->view('mail/wachtwoord', $data, TRUE));
        return $this->email->send();
    }

    /**
     * Function to reset the password or to send the mail
     * @param null $recovery string the recovery ccode provided in the mail
     */
    public function reset($recovery = NULL){
        //If no recovery has been provided we will generate one and send an email.
        if ($recovery === NULL){
            $data = $this->input->post(NULL, TRUE);
            if (empty($data)) redirect('/inloggen');
            if (($result = $this->login_model->set_recovery($data['email'])) !== FALSE){
                if ($this->send_password_recovery_mail($result['email'], $result['recovery'], $result['recovery_valid'])) {
                    $this->session->set_flashdata('success', 'Er is een mail met een resetcode naar je gestuurd!');
                }
                else {
                    $this->session->set_flashdata('fail','Het is niet gelukt om de mail te sturen. Neem contact op met <a href="mailto:webmaster@hydrofiel.nl">de webmaster</a>.');
                }
            } else {
                $this->session->set_flashdata('fail','Dit mailadres is niet bij ons bekend.');
            }
            redirect('/inloggen');
        }
        //Check if we can reset this password
        else {
            $result = $this->login_model->get_id_and_mail($recovery);
            if ($result !== FALSE) {
                $this->load->helper(array('form', 'url'));
                $this->load->library('form_validation');
                $this->form_validation->set_rules('wachtwoord1', 'wachtwoord', 'required',
                    array('required' => 'Je moet een %s invullen.'));
                $this->form_validation->set_rules('wachtwoord2', 'wachtwoord', 'required|matches[wachtwoord1]',
                    array('required' => 'Je moet het %s bevestigen.',
                          'matches' => 'De wachtwoorden komen niet overeen.'));
                if ($this->form_validation->run() == FALSE) {
                    //Show the new password form
                    $data['recovery'] = $recovery;
                    $data['success'] = $this->session->flashdata('success');
                    $data['fail'] = $this->session->flashdata('fail');
                    parent::loadView('inloggen/nieuw_wachtwoord', $data);
                }
                else {
                    $this->set_new_pass();
                }
            } else {
                $this->session->set_flashdata('fail', 'Deze recovery is onbekend of niet meer geldig.');
                redirect('/inloggen');
            }
        }
    }

    /**
     * Function to actually set a new password
     */
    public function set_new_pass(){
        $data = $this->input->post(NULL, TRUE);
        // Check if we still have a valid recovery...
        $result = $this->login_model->get_id_and_mail($data['recovery']);
        if ($result === FALSE) {
            $this->session->set_flashdata('fail','Deze recovery is onbekend.');
        }
        else {
            $update = array(
                'wachtwoord' => password_hash($data['wachtwoord1'], PASSWORD_DEFAULT),
                'recovery_valid' => NULL,
                'recovery' => NULL
            );
            if ($this->profile_model->update($result->id, $update) > 0) {
                $this->session->set_flashdata('success','Wachtwoord succesvol opgeslagen');
            }
            else {
                $this->session->set_flashdata('fail','Er is iets mis gegaan bij het opslaan van je wachtwoord. Probeer het later opnieuw.');
            }
            redirect('/inloggen');
        }
        redirect('inloggen/reset/' . $data['recovery']);
    }

}