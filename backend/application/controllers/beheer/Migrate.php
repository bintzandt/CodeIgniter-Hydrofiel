<?php

/**
 * Class Migrate
 * Used to execute the migrations on the site.
 * Note: enable migrations in application/config/config.php first!
 */
class Migrate extends _BeheerController {
	public function __construct() {
		parent::__construct();
	}

	public function index() {
		$this->load->library( 'migration' );
	}
}
