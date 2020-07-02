<?php
class Training {
	public int $event_id;
	public string $nl_naam;
	public string $en_naam;
	public string $nl_omschrijving;
	public string $en_omschrijving;
	
	private string $van;
	private string $tot;

	public string $inschrijfdeadline;
	public string $afmelddeadline;

	private string $inschrijfsysteem;
	public string $maximum;
	
	private Agenda_model $agenda_model;

	public function __construct(){
		$CI =& get_instance();
		$this->agenda_model = $CI->agenda_model;
	}

	public function __get( string $name ){
		switch ( $name ) {
			case 'registrations': return $this->get_registrations();
			case 'naam': return is_english() ? $this->en_naam : $this->nl_naam;
			case 'omschrijving': return is_english() ? $this->en_omschrijving : $this->nl_omschrijving;
			case 'van': return date_format( date_create( $this->van ), 'd-m-Y H:i');
			case 'tot': return date_format( date_create( $this->tot ), 'd-m-Y H:i');
			case 'inschrijfsysteem': return true;
		}
		
		if ( $name === 'registrations' ){
			return $this->get_registrations();
		}
	}

	public function register( int $user_id ){
		return $this->agenda_model->aanmelden( [ 'event_id' => $this->event_id, 'member_id' => $user_id ] );
	}

	public function cancel( int $user_id ){
		return $this->agenda_model->afmelden( $user_id, $this->event_id );
	}

	public function nr_of_registrations(){
		return sizeof( $this->registrations );
	}

	public function get_registrations(){
		return $this->agenda_model->get_inschrijvingen( $this->event_id );
	}

	public function user_is_registered( $user_id ){
		return $this->agenda_model->get_inschrijvingen( $this->event_id, $user_id ) !== [];
	}
}
