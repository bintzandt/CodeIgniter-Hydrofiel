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
            $config['upload_path'] = APPPATH . 'attachments/nederlands';
            $config['allowed_types'] = 'pdf|doc|docx|xlsx|xls|jpg|jpeg|png|gif';
            $config['max_size'] = 2048;

            //Upload dutch attachments
            $this->load->library('upload', $config);
            $this->upload->do_multi_upload('userfile_nl');

            //Upload english attachments
            $config['upload_path'] = APPPATH . 'attachments/engels';
            $this->upload->initialize($config);
            $this->upload->do_multi_upload('userfile_en');

            //Get all the form data
            $data = $this->input->post(NULL, TRUE);
            //Set as flashdata (bit hacky, but hey, it works... :D)
            $this->session->set_flashdata('data', $data);
            //Redirect to the actual mail send page
            redirect('beheer/mail/send');
        }
    }

    /**
     * Function to show a list of vrienden van Hydrofiel.
     */
    public function vrienden(){
        $vrienden = $this->mail_model->get_vrienden();
        if ($vrienden != NULL) {
            $vrienden = $vrienden->vrienden_van;
        } else {
            $vrienden = '';
        }
        $this->loadView('beheer/mail/vrienden', array("mailadressen" => $vrienden));
    }


    /**
     * Function to save the uploaded list of vrienden van Hydrofiel.
     */
    public function save_vrienden(){
        $data = $this->input->post(NULL, TRUE);
        $this->mail_model->set_vrienden($data['vrienden_van']);
        $this->session->set_flashdata('success', "De vrienden zijn opgeslagen");
        redirect('/beheer/mail');
    }

    /**
     * Function to send the mail
     */
    public function send(){
        //Make sure that flashdata is set, else this function will not work
        if (NULL !== $this->session->flashdata('data')){
            $data = $this->session->flashdata('data');
        }
        else {
            $this->session->set_flashdata("fail", "Deze functie kan zo niet gebruikt worden!");
            redirect('beheer/mail');
        }

        //Create an array to which the mail will be send.
        $bcc = array();
        if (!empty($data["email"])){
            $bcc = array_merge($this->get_emails($data["email"]), $bcc);
        }
        //Nederlands mailen.

        //Attach the attachments to the mail class.
        foreach(glob(APPPATH . 'attachments/nederlands/*.*') as $file) {
            $this->email->attach($file);
        }

        //Group
        $bcc = array_merge($this->set_group($data["aan"], FALSE), $bcc);
        //ID's
        if (!empty($data["los"])){
            $bcc = array_merge($this->set_ids($data["los"], FALSE), $bcc);
        }
        if ($data['layout'] === 'nieuwsbrief') {
            $text = $this->mail_model->get_vrienden();
            if ($text != NULL) {
                $email = $this->get_emails($text->vrienden_van);
                $bcc = array_merge($email, $bcc);
            }
        }

        $this->set_from($data["van"]);
        $hash = md5($data['content'] . $data['layout'] .time());
        $message = $this->get_message($data["layout"], $data["content"], $hash, FALSE);

        $this->email->message($message);
        $this->email->subject($data["onderwerp"]);
        $this->email->to("no-reply@hydrofiel.nl");
        $this->email->bcc($bcc);
        $succes1 = TRUE;
        if (!empty($bcc)) {
            if ($this->email->send()){
                $this->mail_model->save_mail(array(
                    "hash" => $hash,
                    "datum" => date('Y-m-d H:i:s'),
                    "van" => $data["van"],
                    "onderwerp" => $data["onderwerp"],
                    "bericht" => $message
                ));
            }
            else {
                $succes1 = FALSE;
            }
        }

        //Clear email variables
        $this->email->clear(TRUE);

        //Engels mailen
        //Attach the attachments to the mail class.
        foreach(glob(APPPATH . 'attachments/engels/*.*') as $file) {
            $this->email->attach($file);
        }

        $bcc = array();
        $bcc = array_merge($this->set_group($data["aan"], TRUE), $bcc);

        if (!empty($data["los"])){
            $bcc = array_merge($this->set_ids($data["los"], TRUE), $bcc);
        }

        $this->set_from($data["van"]);
        $hash = md5($data['en_content'] . $data['layout'] .time());
        $message = $this->get_message($data["layout"], $data["en_content"], $hash, TRUE);

        $this->email->message($message);
        $this->email->subject($data["en_onderwerp"]);
        $this->email->bcc($bcc);

        $succes2 = TRUE;
        if (!empty($bcc)) {
            if ($this->email->send()) {
                $this->mail_model->save_mail(array(
                    "hash" => $hash,
                    "datum" => date('Y-m-d H:i:s'),
                    "van" => $data["van"],
                    "onderwerp" => $data["en_onderwerp"],
                    "bericht" => $message
                ));
            } else {
                $succes2 = FALSE;
            }
        }

        //Afwikkeling
        foreach(glob(APPPATH . 'attachments/nederlands/*.*') as $file) {
            unlink($file);
        }
        foreach(glob(APPPATH . 'attachments/engels/*.*') as $file) {
            unlink($file);
        }

        if ($succes1 && $succes2) {
            $this->session->set_flashdata("success", "Mail is verstuurd.");
        }
        else {
            $this->session->set_flashdata("fail", "Er ging iets fout.");
        }

        redirect('beheer/mail');
    }

    /**
     * Delete a mail with certain hash
     * @param $hash string Which mail needs to be deleted
     */
    public function delete($hash){
        if ($this->mail_model->delete($hash)) {
            $this->session->set_flashdata('success', 'Mail verwijderd.');
        }
        else {
            $this->session->set_flashdata('fail', 'Er is iets fout gegaan.');
        }
        redirect('/beheer/mail/history');
    }

    /**
     * Function to show the history of all the mails.
     */
    public function history(){
        $data['success'] = $this->session->flashdata('success');
        $data['fail'] = $this->session->flashdata('fail');
        $data['email'] = $this->mail_model->get_mail(NULL, 1000);
        $this->loadView('mail/overview', $data);
    }

    /**
     * Show an overview of the mail history, or, when a hash is provided, show that mail
     * @param null $hash string Hash of the mail
     */
    public function view_mail($hash = NULL){
        //Show details of mail or show error
        $result = $this->mail_model->get_mail($hash);
        if (!empty($result)){
            $data=$result[0];
            $this->loadView('mail/view', $data);
        } else {
            show_error("Hash not found");
        }
    }

    /**
     * Turns a string of emails into an array
     * @param $emails A string contain email adresses
     * @return array An array containing all the valid email adresses provided in the string
     */
    private function get_emails($emails){
        $mail = array();
        // Regex to filter out all the spaces
        $emails = preg_replace('/\s+/', '', $emails);
        // Create an array of the string using ',' as delimiter
        $test_mail = explode(',', $emails);
        // Run over the array to check whether each mail adress is valid
        if (!empty($test_mail)) {
            foreach ($test_mail as $adress) {
                if (filter_var($adress, FILTER_VALIDATE_EMAIL)) array_push($mail, $adress);
            }
        }
        return $mail;
    }

    /**
     * Returns an array of mailadress based on which group has been selected
     * @param $group string Specifies which group needs to be mailed
     * @param $engels boolean Specifies whether this mail is English or Dutch
     * @return array An array containing all the emailadress belonging to the group
     */
    private function set_group($group, $engels){
        $mail = array();
        $emails = $this->mail_model->get_group($group, $engels);
        if (!empty($emails)){
            foreach ($emails as $email){
                array_push($mail, $email->email);
            }
        }
        return $mail;
    }

    /**
     * Returns an array of mailadresses based on which ids have been selected
     * @param $ids array An array of ids
     * @param $engels boolean Whether this mail is English or Dutch
     * @return array An array containing mailadresses for all the ids
     */
    private function set_ids($ids, $engels){
        $mail = array();
        $emails = $this->mail_model->get_emails($ids, $engels);
        if (!empty($emails)){
            foreach ($emails as $email){
                array_push($mail, $email->email);
            }
        }
        return $mail;
    }

    /**
     * Function to set the correct from mailadress
     * @param $from string From which mailadress the mail will be sent
     */
    private function set_from($from){
        switch ($from){
            case 'bestuur'          : $this->email->from('bestuur@hydrofiel.nl', 'Bestuur N.S.Z.&W.V. Hydrofiel'); break;
            case 'penningmeester'   : $this->email->from('penningmeester@hydrofiel.nl', 'Penningmeester N.S.Z.&W.V. Hydrofiel'); break;
            case 'zwemmen'          : $this->email->from('zwemcommissaris@hydrofiel.nl', 'Zwemcommissaris N.S.Z.&W.V. Hydrofiel'); break;
            case 'waterpolo'        : $this->email->from('waterpolocommissaris@hydrofiel.nl', 'Waterpolocommissaris N.S.Z.&W.V. Hydrofiel'); break;
            case 'algemeen'         : $this->email->from('commissarisalgemeen@hydrofiel.nl', 'Commissaris Algemeen N.S.Z.&W.V. Hydrofiel'); break;
            case 'secretaris'       : $this->email->from('secretaris@hydrofiel.nl', 'Secretaris N.S.Z.&W.V. Hydrofiel'); break;
            case 'voorzitter'       : $this->email->from('voorzitter@hydrofiel.nl', 'Voorzitter N.S.Z.&W.V. Hydrofiel'); break;
            case 'activiteiten'     : $this->email->from('activiteitencommissie@hydrofiel.nl', 'Activiteitencommissie N.S.Z.&W.V. Hydrofiel'); break;
            case 'webmaster'        : $this->email->from('webmaster@hydrofiel.nl', 'Webmaster'); break;
            default                 : $this->email->from('bestuur@hydrofiel.nl', 'Bestuur N.S.Z.&W.V. Hydrofiel'); break;
        }
    }

    /**
     * Function to create the actual email
     * @param $layout string Which layout need to be chosen
     * @param $content string The text which will be send
     * @param $hash string Hash of the mail
     * @param $engels boolean English or Dutch mail
     * @return string A HTML string representing the final mail
     */
    private function get_message($layout, $content, $hash, $engels){
        $data["content"] = $content;
        $data["hash"] = $hash;
        $data["engels"] = $engels;
        switch ($layout){
            case 'standaard'    : $message = $this->load->view('mail/default', $data, TRUE); break;
            case 'nieuwsbrief'  :
                $data["agenda"] = $this->agenda_model->get_event(NULL, 3);
                $message = $this->load->view('mail/nieuwsbrief', $data, TRUE);
                break;
            default             : $message = $content; break;
        }
        return $message;
    }
}