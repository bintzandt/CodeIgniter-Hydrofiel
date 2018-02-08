<?php
/**
 * Created by PhpStorm.
 * User: bintzandt
 * Date: 29/11/17
 * Time: 13:19
 */

class Profile extends _SiteController
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->logged_in){
            redirect('/inloggen', 'refresh');
        }

        $protected = array('delete');
        if (in_array($this->router->method, $protected) && !$this->session->superuser){
            show_error("Je bent niet bevoegd!");
        }

        $this->load->model('profile_model');
        $this->load->helper('url_helper');
    }

    public function index($id = 0){
        if ($id === 0 && isset($this->session->id)){
            $id = $this->session->id;
        }
        $data['profile'] = $this->profile_model->get_profile($id);

        if (empty($data['profile'])){
            show_404();
        }

        $data['profile']->lidmaatschap = $this->lidmaatschap($data['profile']->lidmaatschap);
        parent::loadView('profile/view', $data);
    }

    public function id($id=0){
        $this->index($id);
    }

    public function edit($id = 0){
        if ($id === 0 && isset($this->session->id)){
            $id = $this->session->id;
        }
        if (!($this->session->superuser || $this->session->id === $id)) {
            show_error("Je bent hiervoor niet bevoegd!");
        }
        $data['profile'] = $this->profile_model->get_profile($id);
        if (empty($data['profile'])){
            show_404();
        }

        $data['profile']->lidmaatschap = $this->lidmaatschap($data['profile']->lidmaatschap);

        parent::loadView('profile/edit', $data);
    }

    public function save($id){
        if (!($this->session->superuser || $id === $this->session->id)){
            show_error("Je bent niet bevoegd om deze gebruiker te bewerken!");
        }
        $data = $this->input->post(NULL, TRUE);
        if ($data['wachtwoord1'] !== 'wachtwoord' && $data['wachtwoord1'] === $data['wachtwoord2']){
            $data['wachtwoord'] = password_hash($data['wachtwoord1'], PASSWORD_DEFAULT);
        }
        unset($data['wachtwoord1']);
        unset($data['wachtwoord2']);

        if (!isset($data['zichtbaar_telefoonnummer'])) $data['zichtbaar_telefoonnummer'] = 0;
        if (!isset($data['zichtbaar_email'])) $data['zichtbaar_email'] = 0;
        if (!isset($data['zichtbaar_adres'])) $data['zichtbaar_adres'] = 0;
        if (!isset($data['nieuwsbrief'])) $data['nieuwsbrief'] = 0;
        if (!isset($data['engels'])) $data['engels'] = 0;

        $profile = $this->profile_model->get_profile_array($id);
        $important_changes = array("email", "adres", "postcode", "plaats", "mobielnummer"
        );

        $nr = $this->profile_model->update($id, $data);
        if ($nr > 0){
            $profile_update = $this->profile_model->get_profile_array($id);
            $diff = array_diff_assoc($profile_update, $profile);
            foreach ($important_changes as $key){
                if (array_key_exists($key, $diff)) {
                    $change[$key] = $diff[$key];
                }
            }
            if ($change !== NULL){
                //Mail
                $this->send_user_update_mail(array(
                    "naam" => $data["naam"],
                    "change" => $change
                ));
            }
        }
        redirect('/profile/index/'.$id);
    }

    private function send_user_update_mail($data){
        $this->email->to('secretaris@hydrofiel.nl');
        $this->email->from('no-reply@hydrofiel.nl','Ledennotificatie');
        $this->email->subject("Lid bewerkt");
        $this->email->message($this->load->view('mail/update', $data, TRUE));
        return $this->email->send();
    }

    public function delete($id=NULL){
        if ($id !== NULL){
            if ($this->profile_model->delete($id)){
                $this->session->set_flashdata('success', 'Gebruiker verwijderd.');
            } else {
                $this->session->set_flashdata('fail', 'Het is niet gelukt om de gebruiker te verwijderen.');
            }
        }
        redirect('/beheer/leden');
    }

    private function lidmaatschap($soort){
        switch ($soort){
            case 'waterpolo_competitie' : return 'Waterpolo (competitie)';
            case 'waterpolo_recreatief' : return 'Waterpolo (recreatief)';
            case 'trainer'              : return 'Trainer';
            default                     : return 'Zwemmer';
        }
    }
}