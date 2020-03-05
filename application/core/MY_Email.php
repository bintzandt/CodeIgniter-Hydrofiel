<?php

/**
 * Class MY_Email
 *
 * Overwrites the send class so it does not send any emails when testing.
 */
class MY_Email extends CI_Email {
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Overwrite the default send method, to prevent sending e-mails from the testing server.
	 *
	 * @param bool $auto_clear Whether to clear all variables after sending.
	 *
	 * @return bool true on success, false on failure.
	 */
	public function send( $auto_clear = true ) {
		var_dump( ENVIRONMENT );
		if ( ENVIRONMENT !== 'testing' ){
			return parent::send( $auto_clear );
		}

		return true;
	}
}
