<?php
/**
 * Created by PhpStorm.
 * User: bintzandt
 * Date: 01/12/17
 * Time: 16:41
 */

class Mail extends _SiteController
{
    public function __construct()
    {
        parent::__construct();

        $protected = array('send', 'delete');
        if (in_array($this->router->method, $protected) && !$this->session->superuser){
            show_error("Je bent niet bevoegd!");
        }
        $this->load->model('mail_model');
        $this->load->model('agenda_model');
    }

    public function send(){
        if (NULL !== $this->session->flashdata('data')){
            $data = $this->session->flashdata('data');
        }
        else {
            $this->session->set_flashdata("fail", "Deze functie kan zo niet gebruikt worden!");
            redirect('beheer/mail');
        }

        //Attach the attachments to the mail class.
        foreach(glob('/home/bintza1q/attachments/*.*') as $file) {
            $this->email->attach($file);
        }
        //Create an array to which the mail will be send.
        $bcc = array();
        if (!empty($data["email"])){
            $bcc = array_merge($this->set_emails($data["email"]), $bcc);
        }
        //Nederlands mailen.
        //Group
        $bcc = array_merge($this->set_group($data["aan"], FALSE), $bcc);
        //ID's
        if (!empty($data["los"])){
            $bcc = array_merge($this->set_ids($data["los"], FALSE), $bcc);
        }

        $this->set_from($data["van"]);
        $hash = md5($data['content'] . $data['layout'] .time());
        $message = $this->get_message($data["layout"], $data["content"], $hash, FALSE);

        $this->email->message($message);
        $this->email->subject($data["onderwerp"]);
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
        $this->email->clear();

        //Engels mailen
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
        foreach(glob('/home/bintza1q/attachments/*.*') as $file) {
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

    public function history($hash = NULL){
        if ($hash === NULL && $this->session->superuser) {
            $data['success'] = $this->session->flashdata('success');
            $data['fail'] = $this->session->flashdata('fail');
            $data['email'] = $this->mail_model->get_mail();

            parent::loadViewBeheer('mail/overview', $data);
        }
        else {
            //Show details of mail or show error
            if (!empty($result = $this->mail_model->get_mail($hash))){
                $data=$result[0];
                parent::loadView('mail/view', $data);
            } else {
                show_error("Hash not found");
            }

        }
    }

    public function delete($hash){
        if ($this->mail_model->delete($hash)) {
            $this->session->set_flashdata('success', 'Mail verwijderd.');
        }
        else {
            $this->session->set_flashdata('fail', 'Er is iets fout gegaan.');
        }
        redirect('/mail/history');
    }

    private function set_emails($emails){
        $mail = array();
        $emails = preg_replace('/\s+/', '', $emails);
        $test_mail = explode(',', $emails);
        if (!empty($test_mail)) {
            foreach ($test_mail as $adress) {
                if (filter_var($adress, FILTER_VALIDATE_EMAIL)) array_push($mail, $adress);
            }
        }
        return $mail;
    }

    private function set_group($group, $engels){
        $mail = [];
        $emails = $this->mail_model->get_group($group, $engels);
        if (!empty($emails)){
            foreach ($emails as $email){
                array_push($mail, $email->email);
            }
        }
//        var_dump($mail); exit();
        return $mail;
    }

    private function set_ids($ids, $engels){
        $mail = [];
        $emails = $this->mail_model->get_emails($ids, $engels);
        if (!empty($emails)){
            foreach ($emails as $email){
                array_push($mail, $email->email);
            }
        }
        return $mail;
    }

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
            default                 : $this->email->from('bestuur@hydrofiel.nl', 'Bestuur N.S.Z.&W.V. Hydrofiel'); break;
        }
    }

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