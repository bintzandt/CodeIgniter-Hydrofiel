<?php

class DatabaseError extends Error {
	public function __construct( $heading = "", $messages = [] ) {
		parent::__construct( $heading . "\r\n" . implode( "\r\n", $messages ),  );
	}
}
