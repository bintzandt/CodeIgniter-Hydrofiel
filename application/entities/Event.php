<?php

use Spatie\CalendarLinks\Exceptions\InvalidLink;
use Spatie\CalendarLinks\Link;

const DATE_FORMAT = 'd-m-Y H:i';

/**
 * Class Event
 * Handles the conversion between our database and views.
 * Contains additional functions for registering and cancelling for events.
 */
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

	// An array to keep track of registrations. This ensures that we do not have to make a DB call every time.
	private array $registrations;

	// CI instances required in this class.
	private Agenda_model $agenda_model;
	private CI_Session $session;

	/**
	 * Event constructor.
	 * Sets CI variables on the local object.
	 */
	public function __construct() {
		$CI                 =& get_instance();
		$this->agenda_model = $CI->agenda_model;
		$this->session      = $CI->session;
	}

	/**
	 * Magic __get function for some class variables.
	 * This is used for retrieving the name and description in the correct language.
	 *
	 * @param string $name The name of the variable to retrieve.
	 *
	 * @return string Translated name and description.
	 */
	public function __get( $name ) {
		switch ( $name ) {
			case 'naam':
				return is_english() ? $this->en_naam : $this->nl_naam;
			case 'omschrijving':
				return is_english() ? $this->en_omschrijving : $this->nl_omschrijving;
		}
	}

	/**
	 * Function to register for an event.
	 *
	 * @param int        $member_id The user to register.
	 * @param string     $opmerking The remark that is added to the registration.
	 * @param array|null $slagen    Optional: an array of strokes for which the user registered.
	 * @param array|null $tijden    Optional: an array of records that belong to the array of strokes.
	 *
	 * @throws DeadlinePassedError
	 * @throws IsFullError
	 * @throws AlreadyRegisteredError
	 * @throws NotAuthorizedError
	 */
	public function register( int $member_id, string $opmerking = '', ?array $slagen = null, ?array $tijden = null ) {
		if ( ! is_admin_or_requested_user( $member_id ) ) {
			throw new NotAuthorizedError();
		}

		if ( ! $this->can_register() ) {
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
		if ( $this->is_nszk() && ! is_null( $slagen ) && ! is_null( $tijden ) ) {
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

	/**
	 * Cancels the registration for a user.
	 *
	 * @param int $member_id The user for which the registration is cancelled.
	 *
	 * @return bool Indicates whether operation was successful.
	 *
	 * @throws DeadlinePassedError
	 */
	public function cancel( int $member_id ): bool {
		if ( ! is_admin_or_requested_user( $member_id ) ) {
			throw new NotAuthorizedError();
		}

		// An admin can always cancel a registration.
		if ( ! is_admin() && ! $this->can_cancel() ) {
			throw new DeadlinePassedError();
		}

		return $this->agenda_model->afmelden( $member_id, $this->event_id ) === 1;
	}

	/**
	 * Generate an ICAL link for this event.
	 *
	 * @param string $type For which calendar type the link is generated.
	 *
	 * @return string The generated link.
	 */
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
	 * Formats a date field.
	 *
	 * @param string $property Which field will be formatted.
	 *
	 * @return string The formatted date or an empty string in the case of an invalid date.
	 * @throws Error Throw an error if the property is not a valid date field.
	 */
	public function get_formatted_date_string( string $property ) {
		if ( ! in_array( $property, self::date_properties, true ) ) {
			throw new Error( $property . ' is not a valid Date property in Event.' );
		}

		$result = date_format( date_create( $this->$property ), DATE_FORMAT );

		return $result ? $result : '';
	}

	/**
	 * Function to check whether there is a maximum for this event.
	 *
	 * @return bool
	 */
	public function has_maximum() {
		return $this->maximum > 0;
	}

	/**
	 * Returns a list of registrations for the current event.
	 *
	 * @return array A list of users registered for this event.
	 */
	public function get_registrations() {
		if ( ! isset( $this->registrations ) ) {
			$this->registrations = $this->agenda_model->get_inschrijvingen( $this->event_id, null );
		}

		return $this->registrations;
	}

	/**
	 * Get the number of registered users for this event.
	 *
	 * @return int The number of registered users for this event.
	 */
	public function nr_of_registrations(): int {
		return sizeof( $this->get_registrations() );
	}

	/**
	 * Function to check whether a user is registered for this event.
	 *
	 * @param int|null $user An userId or null for the current user.
	 *
	 * @return bool true if the user is registered, false otherwise.
	 */
	public function is_registered( int $user = null ): bool {
		$user = $user ?? $this->session->id;

		return $this->agenda_model->get_aantal_aanmeldingen( $this->event_id, $user ) === 1;
	}

	/**
	 * Checks whether the registration can still be canceled.
	 *
	 * @return bool
	 */
	public function can_cancel(): bool {
		return date( 'Y-m-d H:i:s' ) <= $this->afmelddeadline;
	}

	/**
	 * Checks whether the registrations are open.
	 *
	 * @return bool
	 */
	public function can_register(): bool {
		return date( 'Y-m-d H:i:s' ) <= $this->inschrijfdeadline;
	}

	/**
	 * Checks whether the current event is of type nszk.
	 *
	 * @return bool
	 */
	public function is_nszk(): bool {
		return $this->soort === 'nszk';
	}

	/**
	 * Checks whether the event is full.
	 *
	 * @return bool
	 */
	public function is_full(): bool {
		return $this->maximum > 0 && $this->maximum === $this->nr_of_registrations();
	}

	/**
	 * Generates a string based on the registrations.
	 *
	 * @return string A string containing the current number of registrations and an optional maximum.
	 */
	public function nr_of_registrations_string(): string {
		$result = $this->nr_of_registrations();
		if ( $this->has_maximum() ) {
			$result .= '/' . $this->maximum;
		}

		return $result;
	}

	/**
	 * Generates the description used for the link.
	 * This replaces all HTML breaks with newlines.
	 *
	 * @return string A string that can be used for the ICAL link.
	 */
	private function get_link_description() {
		$description = preg_replace( '/<br ?\/?>/', "\n", $this->omschrijving );
		$description = preg_replace( '/<\/?p ?>/', " ", $description );

		return strip_tags( $description );
	}
}
