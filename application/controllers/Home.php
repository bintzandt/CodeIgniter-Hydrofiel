<?php

class Home extends _SiteController {
	// Private variables needed for accessing the Facebook posts
	private $token = "?access_token=1787347374631170|MVXgVmEVfr8FjeOrCV_M-fP68Ys";
	private $graph_url = "https://graph.facebook.com/";
	private $facebook_id = "273619436014416";

	public function __construct() {
		parent::__construct();
		if( $this->session->engels ) {
			$this->lang->load( "home", "english" );
		}
		else {
			$this->lang->load( "home" );
		}
	}

	/**
	 * Generate the home page
	 */
	public function index() {
		$data["engels"]         = $this->session->engels;
		$data["events"]         = $this->agenda_model->get_event( NULL, 3 );
		$data["login"]          = $this->session->userdata( 'logged_in' );
		$data["verjaardagen"]   = $this->profile_model->get_verjaardagen( 3 );
		$data["posts"]          = $this->post_model->get_posts();
		$data["facebook_posts"] = $this->post_model->get_facebook_posts();
		$this->loadView( 'templates/home', $data );
	}

	/**
	 * Function to change language of site
	 */
	public function language() {
		if( isset( $this->session->engels ) && $this->session->engels ) {
			$this->session->engels = FALSE;
		}
		else {
			$this->session->engels = TRUE;
		}
		redirect( $this->agent->referrer() );
	}

	/**
	 * Function to check if Hydrofiel posted new posts
	 */
	public function check_facebook_posts() {
		$options  = "&fields=posts.limit(5)";
		$post_url = $this->graph_url . $this->facebook_id . $this->token . $options;

		// Get a list of posts
		$posts = json_decode( file_get_contents( $post_url ) );
		$data  = $posts->posts->data;

		// Loop over all the recent posts
		foreach( $data as $post ) {
			// Make sure that the post has a message
			if( ! property_exists( $post, 'message' ) ) {
				continue;
			}

			// Several variables we need to save the post
			$created = $post->created_time;
			$message = $post->message;
			$id      = $post->id;

			// Try to get the image and make sure we have one
			$array = $this->get_image_from_id( $id );
			if( $array === FALSE ) {
				continue;
			}

			// Add the post to our database
			$post = [
				'id'      => $id,
				'text'    => $message,
				'url'     => $array[0],
				'image'   => $array[1],
				'created' => $created,
			];
			$this->post_model->insert_facebook_post( $post );
		}
	}

	/**
	 * Get an image belonging to a post id
	 *
	 * @param $id int The post id for which we want the image
	 *
	 * @return array|bool On succes an array of post-url and image-url is returned
	 *                    On failure FALSE is returned
	 */
	private function get_image_from_id( $id ) {
		$options        = "/attachments";
		$attachment_url = $this->graph_url . $id . $options . $this->token;

		// Get the list of attachments for this post
		$attachments = json_decode( file_get_contents( $attachment_url ) );

		// We only want posts with an image
		if( sizeof( $attachments->data ) === 0 ) {
			return FALSE;
		}
		if( ! property_exists( $attachments->data[0], 'subattachments' ) ) {
			return FALSE;
		}

		// Set the post-url
		$post_url = $attachments->data[0]->target->url;
		$images   = $attachments->data[0]->subattachments->data;

		// Loop over all the images until we find one with type photo
		foreach( $images as $image ) {
			if( $image->type === 'photo' ) {
				return [ $post_url, $image->media->image->src ];
			}
		}
	}
}
