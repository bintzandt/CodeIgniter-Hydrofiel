<?php

class PageObject {
	public string $naam;
	public string $engelse_naam;
	public string $engels;
	public bool $ingelogd;
	protected string $tekst;

	public function __get( $name ) {
		switch ( $name ){
			case 'name': return is_english() ? $this->engelse_naam : $this->naam;
			case 'tekst': return is_english() ? $this->engels : $this->tekst;
		}
	}

	public function __set( $name, $value ) {
		if ( property_exists( $this, $name ) ){
			$this->$name = $value;
		}
	}

	public function check_login(){
		if ( ! $this->ingelogd ){
			return;
		}

		must_be_logged_in();
	}
}
