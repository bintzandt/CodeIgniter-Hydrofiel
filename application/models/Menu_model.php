<?php
/**
 * Created by PhpStorm.
 * User: bintzandt
 * Date: 29/11/17
 * Time: 14:53
 */

class Menu_model extends CI_Model
{
    public function hoofdmenu($include_submenu = true){
        $this->db->select('id, naam, engelse_naam, ingelogd');
        $this->db->from('pagina');
        $this->db->where('submenu', 'A');
        $this->db->where('bereikbaar', 'ja');
        $this->db->order_by('plaats');
        $query = $this->db->get();
        $result = [];
        foreach ($query->result_array() as $hoofdmenu){
            if ($include_submenu) $hoofdmenu['submenu'] = $this->submenu($hoofdmenu['id']);
            $result[] = $hoofdmenu;
        }
        return $result;
    }

    public function submenu($id){
        $this->db->select('id, naam, engelse_naam, ingelogd');
        $this->db->from('pagina');
        $this->db->where('submenu', $id);
        $this->db->where('bereikbaar', 'ja');
        $this->db->order_by('plaats');
        $query = $this->db->get();
        $submenu = $query->result_array();
        if (empty($submenu)){
            return NULL;
        }
        return $submenu;
    }
}