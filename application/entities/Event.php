<?php

use Spatie\CalendarLinks\Exceptions\InvalidLink;
use Spatie\CalendarLinks\Link;

const DATE_FORMAT = 'd-m-Y H:i';

class Event {
	const date_properties = [ 'van', 'tot', 'inschrijfdeadline', 'afmelddeadline' ];

	public string $van;
	public string $tot;
	public string $locatie;
	public string $inschrijfdeadline;
	public string $afmelddeadline;
	public int $event_id;
	public int $maximum;

	// These values can be used directly, however, it is easier to get the naam and omschrijving via the __get() since
	// that one takes is_english() in account.
	public string $nl_naam;
	public string $nl_omschrijving;
	public string $en_naam;
	public string $en_omschrijving;

	private array $registrations;

	private Agenda_model $agenda_model;
	private CI_Session $session;

	public function __construct() {
		$CI                 =& get_instance();
		$this->agenda_model = $CI->agenda_model;
		$this->session      = $CI->session;
	}

	public function __get( $name ) {
		switch ( $name ) {
			case 'naam':
				return is_english() ? $this->en_naam : $this->nl_naam;
			case 'omschrijving':
				return is_english() ? $this->en_omschrijving : $this->nl_omschrijving;
		}
	}

	private function get_link_description() {
		$description = preg_replace( '/<br ?\/?>/', "\n", $this->omschrijving );
		$description = preg_replace( '/<\/?p ?>/', " ", $description );

		return strip_tags( $description );
	}

	public function generate_ical_link( string $type = 'google' ): string {
		$from = date_create( $this->van );
		$to   = date_create( $this->tot );
		try {
			$link = Link::create( $this->naam, $from, $to )
			            ->description( $this->get_link_description() )
			            ->address( $this->locatie );

			// Return a link based on the provided type.
			switch ( $type ) {
				case 'google':
				default:
					return $link->google();
			}
		} catch ( InvalidLink $e ) {
			return '';
		}
	}

	/**
	 * @param string $property
	 *
	 * @return false|string
	 * @throws Error
	 */
	public function get_formatted_date_string( string $property ) {
		if ( ! in_array( $property, self::date_properties, true ) ) {
			throw new Error( $property . ' is not a valid Date property in Event.' );
		}

		return date_format( date_create( $this->$property ), DATE_FORMAT );
	}

	public function get_until() {
		return date_format( date_create( $this->tot ), DATE_FORMAT );
	}

	public function has_maximum() {
		return $this->maximum > 0;
	}

	public function registrations() {
		if ( ! isset( $this->registrations ) ) {
			$this->registrations = $this->agenda_model->get_inschrijvingen( $this->event_id, null );
		}

		return $this->registrations;
	}

	public function nr_of_registrations(): int {
		return sizeof( $this->registrations() );
	}

	public function is_registered( int $user = null ): bool {
		$user = $user ?? $this->session->id;

		return $this->agenda_model->get_aantal_aanmeldingen( $this->event_id, $user ) === 1;
	}

	public function register( int $member_id, string $opmerking = '', ?array $slagen = null, ?array $tijden = null ) {
		if ( ! $this->registrations_open() ) {
			throw new DeadlinePassedError();
		}

		if ( $this->is_full() ) {
			throw new IsFullError();
		}

		if ( $this->is_registered( $member_id ) ) {
			throw new AlreadyRegisteredError();
		}

		$data = [
			'event_id'  => $this->event_id,
			'member_id' => $member_id,
			'opmerking' => $opmerking,
		];

		// Check if we need to add the slagen to this registration.
		if ( $this->soort === 'nszk' && ! is_null( $slagen ) && ! is_null( $tijden ) ) {
			$result = [];
			foreach ( $slagen as $index => $slag ) {
				if ( $tijden[ $index ] !== '' ) {
					$result[ $slag ] = $tijden[ $index ];
				}
			}
			$slagen = json_encode( $result );
			if ( $slagen !== '[]' ) {
				$data['slagen'] = $slagen;
			}
		}

		$this->agenda_model->aanmelden( $data );
	}

	public function can_cancel(): bool {
		return date( 'Y-m-d H:i:s' ) <= $this->afmelddeadline;
	}

	public function is_nszk(): bool{
		return $this->soort === 'nszk';
	}

	public function cancel( int $member_id ): void {
		if ( ! $this->can_cancel() ) {
			throw new DeadlinePassedError();
		}

		$this->agenda_model->afmelden( $member_id, $this->event_id );
	}

	public function registrations_open(): bool {
		return date( 'Y-m-d H:i:s' ) <= $this->inschrijfdeadline;
	}

	public function is_full(): bool {
		return $this->maximum > 0 && $this->maximum === $this->nr_of_registrations();
	}

	public function nr_of_registrations_string(): string {
		$result = $this->nr_of_registrations();
		if ( $this->has_maximum() ) {
			$result .= '/' . $this->maximum;
		}

		return $result;
	}
}
