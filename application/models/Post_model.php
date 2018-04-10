<?php
/**
 * Created by PhpStorm.
 * User: bintzandt
 * Date: 09/04/18
 * Time: 14:35
 */

class Post_model extends CI_Model
{
    const POSTS_TABLE   = 'posts';
    const POST_ID       = 'post_id';

    public function add_post($data){
        $this->db->set($data);
        $this->db->insert($this::POSTS_TABLE);
        return $this->db->affected_rows();
    }

    public function delete_post($id){
        $this->db->delete($this::POSTS_TABLE, array($this::POST_ID => $id));
        return $this->db->affected_rows();
    }

    public function get_posts(){
        $this->db->order_by("post_timestamp", "desc");
        $query = $this->db->get($this::POSTS_TABLE);
        return $query->result();
    }
}