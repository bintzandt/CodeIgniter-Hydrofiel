<?php
/**
 * Created by PhpStorm.
 * User: bintzandt
 * Date: 29/11/17
 * Time: 13:15
 */

class Profile_model extends CI_Model
{
    const ID = 0;
    const VOOR = 1;
    const TUSSEN = 2;
    const ACHTER = 3;
    const ADRES = 4;
    const POSTCODE = 5;
    const NR = 6;
    const PLAATS = 7;
    const GEBOORTEDATUM = 8;
    const VASTTELEFOON = 9;
    const MOBIEL = 10;
    const EMAIL = 11;
    const GESLACHT = 12;
    const LIDMAATSCHAP = 13;

    public function get_profile($id = 0)
    {
        if ($id === 0) {
            $this->db->order_by('naam', 'asc');
            $query = $this->db->get('gebruikers');
            return $query->result();
        }
        $query = $this->db->get_where('gebruikers', array('id' => $id));
        return $query->row();
    }

    public function get_profile_array($id){
        $query = $this->db->get_where('gebruikers', array('id' => $id));
        return $query->row_array();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('gebruikers', $data);
        $this->db->cache_delete('profile');
        return $this->db->affected_rows();
    }

    public function upload_users($file_name)
    {
        $file = fopen($file_name, 'r');
        $ids = array();
        $nr = 0;

        while(!feof($file)){
            $data = fgetcsv($file, 0, ';');
            if ($data === FALSE) break;
            foreach ($data as $key => $value) {
                $val = str_replace("'", "", $value);
                $data[$key] = $val;
            }
            array_push($ids, intval($data[self::ID]));

            $user = array(
                "id" => $data[self::ID],
                "naam" => $data[self::VOOR] . " " . ($data[self::TUSSEN] === "" ? "" : $data[self::TUSSEN] . " ") . $data[self::ACHTER],
                "email" => $data[self::EMAIL],
                "adres" => $data[self::ADRES] . " " . $data[self::NR],
                "postcode" => str_replace(" ", "", $data[self::POSTCODE]),
                "plaats" => $data[self::PLAATS],
                "geboortedatum" => date_format(date_create($data[self::GEBOORTEDATUM]), "Y-m-d"),
                "mobielnummer" => ($data[self::VASTTELEFOON] === '' ? $data[self::MOBIEL] : $data[self::VASTTELEFOON]),
                "geslacht" => ($data[self::GESLACHT] === 'm' ? 'man' : 'vrouw'),
            );
            switch ($data[self::LIDMAATSCHAP]) {
                case 'Waterpolo - wedstrijd'    :
                    $user["lidmaatschap"] = 'waterpolo_competitie';
                    break;
                case 'Zwemmers'                 :
                    $user["lidmaatschap"] = 'zwemmer';
                    break;
                case 'Waterpolo - recreatief'   :
                    $user["lidmaatschap"] = 'waterpolo_recreatief';
                    break;
                case 'Trainers'                 :
                    $user["lidmaatschap"] = 'trainer';
                    break;
                case 'Overige'                  :
                    $user["lidmaatschap"] = 'overig';
                    break;
                default                         :
                    $user["lidmaatschap"] = 'zwemmer';
                    break;
            }

            //Check if this is an existing user
            $profile = $this->get_profile($user["id"]);
            if (!empty($profile)) {
                $this->db->set($user);
                $this->db->where('id', $user["id"]);
                $this->db->update('gebruikers');
                $nr += $this->db->affected_rows();

            } else {
                $this->load->model('login_model');
                $this->db->insert('gebruikers', $user);
                $result = $this->login_model->set_recovery($user['email'], TRUE);
                /*if ($result!==FALSE){
                    if ($this->send_welcome_mail($data[self::VOOR], $user['email'], $result['recovery'])){
                        $nr += $this->db->affected_rows();
                    }
                    else {
                        if (($key = array_search($user['id'], $ids)) !== FALSE){
                            unset($ids[$key]);
                        }
                    }
                }*/
            }
        }

        fclose($file);
        unlink($file_name);
        $this->db->where_not_in('id', $ids);
        $this->db->delete('gebruikers');
        return $nr + $this->db->affected_rows();
    }

    public function delete($id){
        $this->db->where('id', $id);
        $this->db->delete('gebruikers');
        return $this->db->affected_rows() > 0;
    }

    public function get_verjaardagen($limit = 3){
        $query = $this->db->query(
            "   SELECT id,naam, DATE_FORMAT(geboortedatum, '%d-%m-%Y') as geboortedatum, DATE_FORMAT(geboortedatum, '%Y') as geboortejaar
                FROM gebruikers 
                WHERE DATE_FORMAT(geboortedatum, '%m%d') >= DATE_FORMAT(now(), '%m%d')
                ORDER BY DATE_FORMAT(geboortedatum, '%m%d') ASC
                LIMIT $limit
            ");
        $result = $query->result();
        return $result;
    }

    private function send_welcome_mail($voornaam, $email, $recovery){
        $this->load->model('agenda_model');
        $data = array(
            'recovery' => $recovery,
            'events' => $this->agenda_model->get_event(NULL, 3),
            'voornaam' => $voornaam
        );
        $this->email->to($email);
        $this->email->from('bestuur@hydrofiel.nl','Bestuur N.S.Z.&W.V. Hydrofiel');
        $this->email->subject("Welkom bij Hydrofiel! 🏊🤽‍");
        $this->email->message($this->load->view('mail/welkom', $data, TRUE));
        $this->email->attach('/home/bintza1q/test.bintzandt.nl/application/views/mail/Welcomeletter_2017-2018_EN.pdf');
        $this->email->attach('/home/bintza1q/test.bintzandt.nl/application/views/mail/Welkomstbrief_2017-2018_NL.pdf');
        return $this->email->send();
    }
}