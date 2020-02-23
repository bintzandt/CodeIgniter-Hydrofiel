<?php

/**
 * Class Menu_model
 * Handles all database actions related to the menu
 */
class Menu_model extends CI_Model
{
    /**
     * Get a list of the hoofdmenu
     *
     * @param bool $include_submenu whether to include all submenus
     *
     * @return array
     */
    public function hoofdmenu($include_submenu = true)
    {
        $this->db->select('id, naam, engelse_naam, ingelogd');
        $this->db->from('pagina');
        $this->db->where('submenu', 'A');
        $this->db->where('bereikbaar', 'ja');
        $this->db->order_by('plaats');
        $query = $this->db->get();
        $result = [];
        foreach ($query->result_array() as $hoofdmenu) {
            if ($include_submenu) {
                $hoofdmenu['submenu'] = $this->submenu($hoofdmenu['id']);
            }
            $result[] = $hoofdmenu;
        }

        return $result;
    }

    /**
     * Get a submenu for a certain menu id
     *
     * @param $id
     *
     * @return null
     */
    public function submenu($id)
    {
        $this->db->select('id, naam, engelse_naam, ingelogd');
        $this->db->from('pagina');
        $this->db->where('submenu', $id);
        $this->db->where('bereikbaar', 'ja');
        $this->db->order_by('plaats');
        $query = $this->db->get();
        $submenu = $query->result_array();
        if (empty($submenu)) {
            return null;
        }

        return $submenu;
    }
}
