<?php

/**
 * Class Post_model
 * Model made in order to store posts that the bestuur has created.
 * Note: The current board never used this....
 */
class Post_model extends CI_Model {
	const POSTS_TABLE = 'posts';
	const POST_ID = 'post_id';

	/**
	 * Add a post to the database
	 *
	 * @param $data array The data from the post
	 *
	 * @return int The number of rows affected (hopefully 1)
	 */
	public function add_post( array $data ): int {
		$this->db->set( $data );
		$this->db->insert( $this::POSTS_TABLE );

		return $this->db->affected_rows();
	}

	/**
	 * Delete a post from the database
	 *
	 * @param $id int The id that is to be deleted
	 *
	 * @return int The number of rows affected (hopefully 1)
	 */
	public function delete_post( int $id ): int {
		$this->db->delete( $this::POSTS_TABLE, [ $this::POST_ID => $id ] );

		return $this->db->affected_rows();
	}

	/**
	 * Get all the posts from the database and show the newest post first
	 *
	 * @return stdClass All the posts currently in the database.
	 */
	public function get_posts() {
		$this->db->order_by( "post_timestamp", "desc" );
		$query = $this->db->get( $this::POSTS_TABLE );

		return $query->result();
	}
}
