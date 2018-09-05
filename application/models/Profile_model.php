<?php
/**
 * Class Profile_model
 * Handles all database action related to the profile
 */
class Profile_model extends CI_Model
{
    // These constants reflect the order in the CSV which can be uploaded. Changes in the CSV should also be reflected here.
    // In addition: when new data is added to the CSV (international etc.) please add a constant here.
    // Note: The board has changed something but I cannot fix it since I did not get a new CSV file.
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

    /**
     * Get the profile for a certain ID
     * If no ID is specified all profiles will be returned
     * @param int $id
     * @return mixed
     */
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

    /**
     * Get a profile in array form instead of std_object
     * @param $id
     * @return mixed
     */
    public function get_profile_array($id){
        $query = $this->db->get_where('gebruikers', array('id' => $id));
        return $query->row_array();
    }

    /**
     * Update a certain profile
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('gebruikers', $data);
        $this->db->cache_delete('profile');
        return $this->db->affected_rows();
    }

    /**
     * Function for adding/updating users to the database from a CSV
     * @param $file_name
     * @return int
     */
    public function upload_users($file_name)
    {
        $file = fopen($file_name, 'r');

        //After the while loop, ids contains all ids of users that are still member
        $ids = array();

        $nr = 0;

        //While not end of file
        while(!feof($file)){
            $data = fgetcsv($file, 0, ';');
            if ($data === FALSE) break;
            //Remove all additional '
            foreach ($data as $key => $value) {
                $val = str_replace("'", "", $value);
                $data[$key] = $val;
            }

            //Push the id to the array ids
            array_push($ids, intval($data[self::ID]));

            //Create a user ID with all relevant data
            //Note: new datafields will have to be added here
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
            //Translate the lidmaatschap field into database ready data
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
                //Send a welcome mail to new users :D
                if ($result!==FALSE){
                    if ($this->send_welcome_mail($data[self::VOOR], $user['email'], $result['recovery'])){
                        $nr += $this->db->affected_rows();
                    }
                    else {
                        if (($key = array_search($user['id'], $ids)) !== FALSE){
                            unset($ids[$key]);
                        }
                    }
                }
            }
        }
        fclose($file);
        //Remove the file
        unlink($file_name);

        //Remove all users not in ids as they are no longer member
        $this->db->where_not_in('id', $ids);
        $this->db->delete('gebruikers');
        return $nr + $this->db->affected_rows();
    }

    /**
     * Delete a certain id from the databse
     * @param $id
     * @return bool
     */
    public function delete($id){
        $this->db->where('id', $id);
        $this->db->delete('gebruikers');
        return $this->db->affected_rows() > 0;
    }

    /**
     * Function to get upcoming birthdays.
     * @param int $limit
     * @return mixed
     */
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

    /**
     * Function to send a welcome mail to new users
     * @param $voornaam
     * @param $email
     * @param $recovery
     * @return mixed
     */
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
        $this->email->attach('./application/views/mail/Welcomeletter_2017-2018_EN.pdf');
        $this->email->attach('./application/views/mail/Welkomstbrief_2017-2018_NL.pdf');
        return $this->email->send();
    }
}