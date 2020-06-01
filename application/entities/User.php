<?php

class User {
	public string $naam;
	public string $email;
	public int $rank;
	public ?string $webauthn;

	protected int $id;
	public ?string $recovery;
	public ?string $recovery_valid;
	protected bool $engels;
	protected bool $nieuwsbrief;
	protected bool $zichtbaar_email;
	private string $wachtwoord;
	protected string $lidmaatschap;
	protected ?string $geboortedatum;

	/**
	 * @param $name
	 * @param $value
	 *
	 * @throws Exception
	 */
	public function __set( $name, $value ) {
		switch ( $name ){
			case 'id': $this->id = intval( preg_replace( '/[^0-9]/', '', $value ) ); break;
			case 'wachtwoord': $this->wachtwoord = password_hash( $value, PASSWORD_DEFAULT ); break;
			case 'geboortedatum': $this->set_geboortedatum( $value ); break;
			case 'lidmaatschap': $this->lidmaatschap = $this->set_lidmaatschap( $value ); break;
			case 'nieuwsbrief':
			case 'engels':
			case 'zichtbaar_email': $this->$name = $value; break;
		}
	}

	public function __get( $name ) {
		if ( property_exists( $this, $name ) ) {
			switch ( $name ){
				case 'geboortedatum':
					return date_create( $this->geboortedatum )->format( 'd-m-Y' );
				case 'lidmaatschap': return $this->get_lidmaatschap();
				case 'email': return $this->get_email();
				case 'recovery': return $this->recovery;
				case 'id': return $this->id;

				// Automatically return values that can be used inside the checkbox.
				case 'engels': return $this->engels ? 'checked' : '';
				case 'nieuwsbrief': return $this->nieuwsbrief ? 'checked' : '';
				case 'zichtbaar_email': return $this->zichtbaar_email ? 'checked' : '';
			}
		}
	}

	public function get_object_vars() {
		return get_object_vars( $this );
	}

	public function verify_login( string $password ){
		return password_verify( $password, $this->wachtwoord );
	}

	public function needs_rehash(){
		return password_needs_rehash( $this->wachtwoord, PASSWORD_DEFAULT );
	}

	public function is_superuser(){
		return $this->rank <= 2;
	}

	public function get_engels(){
		return $this->engels;
	}

	public function get_formatted_date(){
		return $this->geboortedatum->format( 'd-m-Y' );
	}

	/**
	 * @param $value
	 *
	 * @throws Exception
	 */
	private function set_geboortedatum( $value ){
		$date = DateTime::createFromFormat( 'Y-m-d', $value );
		if ( ! $date ){
			$date = DateTime::createFromFormat( 'd-m-Y', $value );
		}

		$this->geboortedatum = $date ? $date->format( 'Y-m-d' ) : null;
	}

	/**
	 * Small helper function for lidmaatschap
	 *
	 * @param $soort string one of the select fields
	 *
	 * @return string A properly formatted string
	 */
	private function get_lidmaatschap() {
		switch ( $this->lidmaatschap ) {
			case 'waterpolo_competitie' :
				return 'Waterpolo (competitie)';
			case 'waterpolo_recreatief' :
				return 'Waterpolo (recreatief)';
			case 'trainer'              :
				return 'Trainer';
			default                     :
				return 'Zwemmer';
		}
	}

	private function set_lidmaatschap( string $value ): string {
		switch ( $value ){
			case 'Waterpolo - wedstrijd'    :
				return 'waterpolo_competitie';
			case 'Waterpolo - recreatief'   :
				return 'waterpolo_recreatief';
			case 'Trainers'                 :
				return 'trainer';
			case 'Overige'                  :
				return 'overig';
			default                         :
				return 'zwemmer';
		}
	}

	private function get_email(){
		if ( $this->zichtbaar_email ){
			return $this->email;
		}
		if ( is_admin_or_requested_user( $this->id ) ){
			return $this->email;
		}

		return lang( 'profile_hidden' );
	}

	public function set_name( string $first_name, string $middle_name, string $last_name ){
		$this->naam = $first_name;
		if ( $middle_name !== '' ){
			$this->naam .= ' ' . $middle_name;
		}
		$this->naam .= ' ' . $last_name;
	}
}
