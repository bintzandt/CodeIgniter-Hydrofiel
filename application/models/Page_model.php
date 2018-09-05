<?php
/**
 * Class Page_model
 * Handles all page related database actions
 */
class Page_model extends CI_Model
{
    /**
     * Queries the database for a certain page_id
     * @param $page_id
     * @return mixed
     */
    public function view($page_id){
        $query = $this->db->get_where('pagina', array('id' => $page_id));
        return $query->row_array();
    }

    public function get_all(){
        $query = $this->db->get('pagina');
        return $query->result();
    }

    /**
     * Get a page from the database
     * @param null $id
     * @param bool $array
     * @return array
     */
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

    /**
     * Get all subpages for a certain ID
     * @param $id
     * @return null
     */
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

    /**
     * Get the maximum position for a certain submenu
     * TODO: Move this to the menu model
     * @param $submenu
     * @return int
     */
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

    /**
     * Add a page to the databse
     * @param $data
     * @return mixed
     */
    public function add($data){
        $this->db->insert('pagina', $data);
        return $this->db->affected_rows();
    }

    /**
     * Make room in a submenu after a certain position
     * @param $submenu
     * @param $na
     * @return int
     */
    public function make_room($submenu, $na){
        $plaats = $this->get_plaats($na)->plaats + 1;
        $to_move = $this->get_id($submenu, $plaats);
        $this->db->where('id', $to_move);
        $this->db->update('pagina', array('plaats' => $this->get_max_plaats($to_move) + 1));
        return $plaats;
    }

    /**
     * Save edits to a page to the database
     * @param $data
     * @return mixed
     */
    public function save($data){
        $this->db->set($data);
        $this->db->where('id', $data['id']);
        $this->db->update('pagina');
        return $this->db->affected_rows();
    }

    /**
     * Delete a certain page from the database
     * @param $page_id
     * @return mixed
     */
    public function delete($page_id){
        $this->db->delete('pagina', array('id' => $page_id));
        $this->db->cache_delete('beheer', 'page');
        return $this->db->affected_rows();
    }

    /**
     * Get the plaats in the menu for a certain id
     * @param $id
     * @return bool
     */
    public function get_plaats($id){
        $query = $this->db->get_where('pagina', array('id' => $id));
        if ($query->num_rows() > 0) {
            return $query->result()[0];
        }
        return FALSE;
    }

    /**
     * Translate a submenu and a position into an page_id
     * @param $submenu
     * @param $plaats
     * @return bool
     */
    public function get_id($submenu, $plaats){
        $query = $this->db->get_where('pagina', array('plaats' => $plaats, 'submenu' => $submenu));
        if ($query->num_rows() > 0) {
            return $query->result()[0]->id;
        }
        return FALSE;
    }

    /**
     * Function to get a list of images
     * @return array
     */
    public function get_image_list(){
        $files = array();
        foreach(glob('./fotos/*.*') as $file) {
            if ($file === './fotos/index.php') continue;
            $image = new stdClass();
            $naam = explode(' ', basename($file));
            if (sizeof($naam) > 2){
                unset($naam[0]);
                unset($naam[1]);
            }
            $naam = implode(" ", $naam);
            $image->naam = $naam;
            $image->url = site_url('/fotos/' . basename($file));
            $image->thumb = site_url('/fotos/thumb/' . basename($file));
            $image->deleteUrl = site_url('/beheer/upload/delete/fotos/' . basename($file));
            array_push($files, $image);
        }
        return $files;
    }

    /**
     * Function to get a list of files.
     */
    public function get_file_list(){
        $files = array();
        foreach(glob('./files/*.*') as $file) {
            if ($file === './files/index.php') continue;
            $document = new stdClass();
            $document->naam = basename($file);
            $document->url = site_url('/files/' . basename($file));
            $document->deleteUrl = site_url('/beheer/upload/delete/files/' . basename($file));
            array_push($files, $document);
        }
        return $files;
    }
}