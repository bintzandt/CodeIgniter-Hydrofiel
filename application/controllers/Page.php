<?php

/**
 * Handles all page related stuff
 * Class Page
 */
class Page extends _SiteController {
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Index function, refers to id
	 *
	 * @param string $page Which page needs to be shown
	 */
	public function index( $page = '1' ) {
		$this->id( $page );
	}

	/**
	 * Actual function to get page contents
	 *
	 * @param string $page
	 */
	public function id( $page = '1' ) {
		$this->db->cache_on();
		//Get the page from the model
		$data['pagina'] = $this->page_model->view( $page );

		//Check if this is an actual page
		if( empty( $data['pagina'] ) ) {
			show_404();
		}
		//Check if we need to be logged in to visit this page
		if( $data['pagina']['ingelogd'] && ! $this->session->logged_in ) {
			redirect( '/inloggen' );
		}

		if( $data['pagina']['naam'] == 'Wedstrijden' ) {
			$this->loadView( 'templates/wedstrijden' );
		}

		//Check if we are in an English setting
		if( $this->session->engels ) {
			$data['tekst'] = $data['pagina']['engels'];
		}
		else {
			$data['tekst'] = $data['pagina']['tekst'];
		}
		$this->db->cache_off();
		$this->loadView( 'templates/page', $data );
	}

	/**
	 * Overwrite the default error_404 handler.
	 */
	public function page_missing() {
		$this->loadView( 'errors/html/error_404' );
	}

	/**
	 * Function to save routes from the database and add them to the routes file.
	 * This allows for easier navigation to several pages on the website.
	 */
	public function save_routes() {
		$pages = $this->page_model->get_all();
		$data  = [];
		if( ! empty( $pages ) ) {
			$data[] = '<?php if ( ! defined(\'BASEPATH\')) exit(\'No direct script access allowed\');';

			foreach( $pages as $page ) {
				$data[] = '$route[\'' . strtolower( $page->naam ) . '\'] = \'page/id/' . $page->id . '\';';
				$data[] = '$route[\'' . strtolower( $page->engelse_naam ) . '\'] = \'page/id/' . $page->id . '\';';
			}
			$output = implode( "\n", $data );

			write_file( APPPATH . 'cache/routes.php', $output );
		}
	}
}