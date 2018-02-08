<?php
/**
 * Created by PhpStorm.
 * User: bintzandt
 * Date: 29/11/17
 * Time: 14:53
 */

class Page_model extends CI_Model
{
    public function view($page_id){
        $query = $this->db->get_where('pagina', array('id' => $page_id));
        return $query->row_array();
    }

    public function get($id = NULL, $array = false){
        if ($id === NULL){
            $this->db->select('*');
            $this->db->from('pagina');
            $this->db->where('submenu', 'A');
            $this->db->order_by('plaats');
            $query = $this->db->get();
            $result = [];
            foreach ($query->result() as $hoofd){
                $hoofd->subpagina = $this->get_subpages($hoofd->id);
                $result[] = $hoofd;
            }
            return $result;
        }
        $query = $this->db->get_where('pagina', array('id' => $id));
        if ($array) return $query->row_array();
        return $query->result()[0];
    }

    public function get_subpages($id){
        $this->db->select('*');
        $this->db->from('pagina');
        $this->db->where('submenu', $id);
        $this->db->order_by('plaats');
        $query = $this->db->get();
        $submenu = $query->result();
        if (empty($submenu)){
            return NULL;
        }
        return $submenu;
    }

    public function get_max_plaats($submenu){
        $this->db->select('MAX(plaats) as max');
        $this->db->from('pagina');
        $this->db->where('submenu', $submenu);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result()[0]->max;
        }
        else return 0;
    }

    public function add($data){
        $this->db->insert('pagina', $data);
        return $this->db->affected_rows();
    }

    public function make_room($submenu, $na){
        $plaats = $this->get_plaats($na)->plaats + 1;
        $to_move = $this->get_id($submenu, $plaats);
        $this->db->where('id', $to_move);
        $this->db->update('pagina', array('plaats' => $this->get_max_plaats($to_move) + 1));
        return $plaats;
    }

    public function save($data){
        $this->db->set($data);
        $this->db->where('id', $data['id']);
        $this->db->update('pagina');
        return $this->db->affected_rows();
    }

    public function delete($page_id){
        $this->db->delete('pagina', array('id' => $page_id));
        $this->db->cache_delete('beheer', 'page');
        return $this->db->affected_rows();
    }

    public function get_plaats($id){
        $query = $this->db->get_where('pagina', array('id' => $id));
        if ($query->num_rows() > 0) {
            return $query->result()[0];
        }
        return FALSE;
    }

    public function get_id($submenu, $plaats){
        $query = $this->db->get_where('pagina', array('plaats' => $plaats, 'submenu' => $submenu));
        if ($query->num_rows() > 0) {
            return $query->result()[0]->id;
        }
        return FALSE;
    }
}