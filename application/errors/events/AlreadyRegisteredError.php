<?php

class AlreadyRegisteredError extends Error {
	public function __construct() {
		load_language_file( 'error' );
		$message = lang( 'error_already_registered' );
		parent::__construct( $message );
	}
}
