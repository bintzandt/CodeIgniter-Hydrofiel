<?php

class IsFullError extends Error {
	public function __construct() {
		// Load the language file.
		load_language_file( 'error' );

		$message = lang( 'error_is_full' );
		parent::__construct( $message );
	}
}
