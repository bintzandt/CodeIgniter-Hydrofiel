<?php

/**
 * Class _SiteController
 * General core controller, contains functions needed by several controllers
 */
class _SiteController extends CI_Controller {
	public function __construct() {
		parent::__construct();
		if( ENVIRONMENT === 'development' ) {
			$this->output->enable_profiler( TRUE );
		}
		else {
			$this->load->driver( 'cache', [ 'adapter' => 'file' ] );
		}
		if( $this->session->engels ) {
			$this->lang->load( "general", "english" );
		}
		else {
			$this->lang->load( "general" );
		}
	}

	/**
	 * Loads the specified view
	 *
	 * @param            $view string Path to the view
	 * @param null|array $data Contains the data the view needs
	 */
	protected function loadView( $view, $data = NULL ) {
		$this->db->cache_on();
		$menu['hoofdmenus'] = $this->menu_model->hoofdmenu();
		$menu['engels']     = $this->session->userdata( 'engels' );
		$menu['logged_in']  = $this->session->userdata( 'logged_in' );
		$menu['superuser']  = $this->session->userdata( 'superuser' );
		$this->db->cache_off();
		$this->load->view( 'templates/header' );
		$this->load->view( 'templates/menu', $menu );
		( $data === NULL ) ? $this->load->view( $view ) : $this->load->view( $view, $data );
		$this->load->view( 'templates/footer' );
	}
}