<?php

use Spatie\CalendarLinks\Exceptions\InvalidLink;
use Spatie\CalendarLinks\Link;

class Event {
	public string $van;
	public string $tot;
	public string $locatie;
	public string $inschrijfdeadline;
	public int $maximum;

	protected string $event_id;
	protected string $nl_naam;
	protected string $nl_omschrijving;
	protected string $en_naam;
	protected string $en_omschrijving;

	private array $registrations;

	private Agenda_model $agenda_model;
	private CI_Session $session;

	public function __construct() {
		$CI =& get_instance();
		$this->agenda_model = $CI->agenda_model;
		$this->session = $CI->session;
	}

	public function __get( $name ) {
		switch ( $name ){
			case 'naam': return is_english() ? $this->en_naam : $this->nl_naam;
			case 'omschrijving': return is_english() ? $this->en_omschrijving : $this->nl_omschrijving;
		}
	}

	private function get_link_description(){
		$description = preg_replace('/<br ?\/?>/', "\n", $this->omschrijving);
		$description = preg_replace('/<\/?p ?>/', " ", $description);
		return strip_tags($description);
	}

	public function generate_ical_link( string $type = 'google' ): string {
		$from = date_create( $this->van );
		$to = date_create( $this->tot );
		try {
			$link = Link::create($this->naam, $from, $to)
			            ->description( $this->get_link_description() )
			            ->address($this->locatie);

			// Return a link based on the provided type.
			switch ( $type ){
				case 'google':
				default: return $link->google();
			}
		} catch ( InvalidLink $e ){
			return '';
		}
	}

	public function registrations(){
		if( ! isset( $this->registrations ) ){
			$this->registrations = $this->agenda_model->get_inschrijvingen($this->event_id, null);
		}
		return $this->registrations;
	}

	public function nr_of_registrations(): int {
		return sizeof( $this->registrations() );
	}

	public function is_registered( int $user = null ): bool {
		$user = $user ?? $this->session->id;
		return $this->agenda_model->get_aantal_aanmeldingen($this->event_id, $user) === 1;
	}
}
