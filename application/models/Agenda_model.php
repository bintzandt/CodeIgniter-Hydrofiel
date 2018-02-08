<?php
/**
 * Created by PhpStorm.
 * User: bintzandt
 * Date: 30/11/17
 * Time: 13:02
 */

class Agenda_model extends CI_Model
{
    public function get_event($event_id = NULL, $limit = NULL){
        if ($event_id === NULL){
            //Get all events
            $this->db->where('van >=', date('Y-m-d'));
            $this->db->order_by('van', 'ASC');
            if ($limit !== NULL){
                $this->db->limit($limit);
            }
            $query = $this->db->get('agenda');
            if ($query->num_rows() > 0) return $query->result();
            return FALSE;
        } else {
            $this->db->select('*');
            $this->db->from('agenda');
            $this->db->where('event_id', $event_id);
            $query = $this->db->get();
            return $query->row();
        }
    }

    public function update_event($data){
        $this->db->set($data);
        $this->db->where('event_id', $data['event_id']);
        $this->db->update('agenda');
        $this->db->flush_cache();
        return $this->db->affected_rows();
    }

    public function add_event($data){
        $this->db->set($data);
        $this->db->insert('agenda');
        $this->db->flush_cache();
        return $this->db->affected_rows();
    }

    public function delete($id){
        $this->db->delete('agenda', array('event_id' => $id));
        $this->db->flush_cache();
        return $this->db->affected_rows();
    }

    public function get_aantal_aanmeldingen($event_id, $id = NULL){
        $this->db->select('count(event_id) as aantal');
        $this->db->where('event_id', $event_id);
        if ($id !== NULL) $this->db->where('member_id', $id);
        $query = $this->db->get('inschrijvingen');
        if ($query->num_rows() > 0 ) return $query->result()[0]->aantal;
        return 0;
    }

    public function aanmelden($data){
        $this->db->insert('inschrijvingen', $data);
        return ($this->db->affected_rows() > 0);
    }

    public function afmelden($id, $event_id){
        $this->db->where('member_id', $id);
        $this->db->where('nszk_id', $event_id);
        $this->db->delete('nszk_inschrijfsysteem');

        $this->db->where('member_id', $id);
        $this->db->where('event_id', $event_id);
        $this->db->delete('inschrijvingen');
        return $this->db->affected_rows();
    }

    public function nszk_inschrijving($data){
        $this->db->insert('nszk_inschrijfsysteem', $data);
        return ($this->db->affected_rows() > 0);
    }

    public function get_inschrijvingen($event_id, $member_id = NULL, $limit=NULL){
        if ($member_id === NULL && $limit===NULL) {
            $query = $this->db->get_where('inschrijvingen', array('event_id' => $event_id));
        } elseif ($member_id === NULL){
            $this->db->limit($limit);
            $this->db->order_by('datum desc');
            $query = $this->db->get_where('inschrijvingen', array('event_id' => $event_id));
        }
        else $query = $this->db->get_where('inschrijvingen', array('event_id' => $event_id, 'member_id' => $member_id));
        if ($query->num_rows() > 0 ) {
            foreach ($query->result() as $inschrijving) {
                $inschrijving->naam = $this->profile_model->get_profile($inschrijving->member_id)->naam;
            }
            return $query->result();
        }
        return array();
    }

    public function is_nszk($event_id){
        $result = $this->get_event($event_id);
        return ($result->soort === 'nszk');
    }

    public function get_details($event_id, $member_id){
        $query = $this->db->get_where('nszk_inschrijfsysteem', array('nszk_id' => $event_id, 'member_id' => $member_id));
        return $query->row();
    }
}