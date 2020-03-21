<?php

class DeadlinePassedError extends Error {
	public function __construct() {
		load_language_file( 'error' );
		$message = lang( 'error_deadline_passed' );
		parent::__construct( $message );
	}
}