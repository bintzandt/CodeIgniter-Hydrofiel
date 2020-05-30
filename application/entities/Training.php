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
	
	private $related_ids = [ 70, 74, 82, 78, 71, 75, 83, 79 ];

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
			case 'inschrijfsysteem': return ( strtotime( 'now' ) > strtotime( '2020-05-30 10:00am' ) ) ? true : false;
		}
		
		if ( $name === 'registrations' ){
			return $this->get_registrations();
		}
	}

	public function register( int $user_id ){
		// Registration system should be turned on.
		if ( strtotime( 'now' ) < strtotime( '2020-05-30 10:00am' ) ){
			throw new Error( "Registratie nog niet open" );
		}

		// Registrations have been closed.
		if ( date('Y-m-d H:i:s') > $this->inschrijfdeadline ){
			throw new Error( "Registraties zijn gesloten" );
		}

		// Training is full.
		if ( $this->maximum > 0 && $this->nr_of_registrations() >= $this->maximum ){
			throw new Error( "Training is vol" );
		}

		// Check if user is already registered for another event.
		if ( $this->user_is_registered_for_related_training( $user_id ) ){
			throw new Error( "Je bent al aangemeld voor een training deze week" );
		}

		return $this->agenda_model->aanmelden( [ 'event_id' => $this->event_id, 'member_id' => $user_id ] );
	}

	public function cancel( int $user_id ){
		if ( date('Y-m-d H:i:s') > $this->afmelddeadline ){
			throw new Error( "Je kunt je niet meer afmelden voor deze training" );
		}

		return $this->agenda_model->afmelden( $user_id, $this->event_id );
	}

	public function nr_of_registrations(){
		return sizeof( $this->registrations );
	}

	public function get_registrations(){
		return $this->agenda_model->get_inschrijvingen( $this->event_id );
	}

	public function user_is_registered_for_related_training( $user_id ){
		foreach( $this->related_ids as $event_id ){
			if ( $this->agenda_model->get_inschrijvingen( $event_id, $user_id ) !== [] ){
				return true;
			}
		}

		return false;
	}

	public function user_is_registered( $user_id ){
		return $this->agenda_model->get_inschrijvingen( $this->event_id, $user_id ) !== [];
	}
}