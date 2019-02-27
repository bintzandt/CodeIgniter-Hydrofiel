<?php

/**
 * Class Posts
 * Controller to handle all actions related to the posts that the board CAN create.
 * @property Post_model post_model
 */
class Posts extends _BeheerController {
	/**
	 * Posts constructor.
	 * We require the post_model (not autoloaded!)
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * The default view.
	 */
	public function index() {
		$data['success'] = $this->session->flashdata( 'success' );
		$data['fail']    = $this->session->flashdata( 'fail' );
		$data['posts']   = $this->post_model->get_posts();
		$this->loadView( 'beheer/posts/index', $data );
	}

	/**
	 * Function to add a post to the database.
	 */
	public function add_post() {
		$post = $this->input->post( NULL, TRUE );
		unset( $post['files'] );
		if( $this->post_model->add_post( $post ) > 0 ) {
			$this->session->set_flashdata( 'success', 'Post is toegevoegd.' );
		}
		else $this->session->set_flashdata( 'fail', 'De post kon niet worden toegevoegd.' );
		redirect( 'beheer/posts' );
	}

	/**
	 * Function to delete a post from the database
	 */
	public function delete_post( $id ) {
		if( $this->post_model->delete_post( $id ) > 0 ) {
			$this->session->set_flashdata( 'success', 'Post is verwijderd.' );
		}
		else $this->session->set_flashdata( 'fail', 'De post kon niet worden verwijderd.' );
		redirect( 'beheer/posts' );
	}

}