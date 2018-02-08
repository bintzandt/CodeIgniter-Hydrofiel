<?php
/**
 * Created by PhpStorm.
 * User: bintzandt
 * Date: 02/12/17
 * Time: 13:10
 */

class Mail_model extends CI_Model
{
    public function get_group($group, $engels){
        $this->db->select('email');
        $this->db->from('gebruikers');
        switch($group){
            case "iedereen":
                break;
            case "waterpoloscompetitie":
                $this->db->where('lidmaatschap','waterpolo_competitie');
                break;
            case "waterpolo":
                $this->db->where_in('lidmaatschap', array('waterpolo_competitie', 'waterpolo_recreatief'));
                break;
            case "waterpolosrecreatief":
                $this->db->where('lidmaatschap','waterpolo_recreatief');
                break;
            case "nieuwsbrief":
                $this->db->where('nieuwsbrief', TRUE);
                break;
            case "zwemmers":
                $this->db->where('lidmaatschap','zwemmer');
                break;
            case "trainers":
                $this->db->where('lidmaatschap','trainer');
                break;
            case "select":
                $this->db->reset_query();
                return array();
            default:
                $this->db->where('rank', 2);
        }
        $this->db->where('engels', $engels);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_emails($ids, $engels){
        $this->db->select('email');
        $this->db->from('gebruikers');
        $this->db->where_in('id',$ids);
        $this->db->where('engels', $engels);
        $query = $this->db->get();
        return $query->result();
    }

    public function save_mail($data){
        $this->db->set($data);
        $this->db->insert('mail');
        return ($this->db->affected_rows() > 0);
    }

    public function get_mail($hash = NULL){
        if ($hash === NULL){
            $this->db->limit(10);
            $query = $this->db->get('mail');
            return $query->result();
        }
        $query = $this->db->get_where('mail', array('hash' => $hash));
        return $query->result();
    }

    public function delete($hash){
        $this->db->where('hash', $hash);
        $this->db->delete('mail');
        return ($this->db->affected_rows() > 0);
    }
}