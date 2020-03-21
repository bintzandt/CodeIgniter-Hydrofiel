<?php

class NotAuthorizedError extends Error {
	public function __construct() {
		load_language_file( 'error' );
		$message = lang( 'error_not_authorized' );
		parent::__construct( $message );
	}
}
