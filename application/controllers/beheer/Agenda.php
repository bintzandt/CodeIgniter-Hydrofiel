<?php

/**
 * Class Agenda
 * Used for handling all beheer functions related to the Agenda
 */
class Agenda extends _BeheerController {
	public Agenda_model $agenda_model;

	/**
	 * Load the index page.
	 */
	public function index() {
		$data['events']     = $this->agenda_model->get_events();
		$data['old_events'] = $this->agenda_model->get_old_events();
		$this->loadView( 'beheer/agenda/agenda', $data );
	}

	public function registration( $event_id, $member_id ){
		$inschrijving = $this->agenda_model->get_inschrijvingen( $event_id, $member_id );
		if ( ! empty( $inschrijving ) ) {
			$data['inschrijving'] = $inschrijving[0];
			$data['event_id']     = $event_id;
			if ( $this->agenda_model->is_nszk( $event_id ) ) {
				$data['nszk']    = true;
				$data['slagen']  = json_decode( $data['inschrijving']->slagen );
				$data['details'] = $this->agenda_model->get_details( $event_id, $member_id );
			}
			else {
				$data['nszk'] = false;
			}
			$this->loadView( 'beheer/agenda/inschrijving_detail', $data );
		}
	}

	/**
	 * Load the inschrijvingen pagina, possibly for a specific member.
	 *
	 * @var int $event_id  The ID of the event.
	 */
	public function inschrijvingen( int $event_id ) {
		$event = $this->agenda_model->get_event( $event_id );

		$data['inschrijvingen'] = $event->get_registrations();
		$data['event_id']       = $event_id;

		return $this->loadView( 'beheer/agenda/inschrijvingen', $data );
	}

	/**
	 * Creates a page to add a new event to the database.
	 */
	public function add() {
		$data['edit_mode'] = false;
		$this->loadView( 'beheer/agenda/edit_add', $data );
	}

	/**
	 * @param int $id Specifies which event is to be edited.
	 *                Creates a page on which the user can edit the event.
	 */
	public function edit( $id ) {
		$data['edit_mode'] = true;
		$data['event']     = $this->agenda_model->get_event( $id );
		if ( empty( $data['event'] ) ) {
			show_404();
		}
		$this->loadView( 'beheer/agenda/edit_add', $data );
	}

	/**
	 * Handles the actual request to save the event in the database.
	 * For editing only!
	 */
	public function save() {
		$data = $this->input->post( null, true );
		$data = $this->format_input_to_mysql_datetime( $data );

		if ( $data['soort'] === 'nszk' ) {
			$data['slagen'] = json_encode( $data['slagen'] );
		}
		else {
			unset( $data['slagen'] );
		}
		if ( $this->agenda_model->update_event( $data ) > 0 ) {
			success( 'Het evenement is succesvol bewerkt!' );
		}
		else {
			error( 'Het evenement is niet veranderd!' );
		}
		redirect( '/beheer/agenda' );
	}

	/**
	 * Handles the creates to save the event in the database.
	 * For a new event only!
	 */
	public function submit() {
		$data = $this->input->post( null, true );
		$data = $this->format_input_to_mysql_datetime( $data );

		if ( $data['soort'] === 'nszk' ) {
			$data['slagen'] = json_encode( $data['slagen'] );
		}
		else {
			unset( $data['slagen'] );
		}
		if ( $this->agenda_model->add_event( $data ) > 0 ) {
			success( 'Het evenement is succesvol toegevoegd!' );
		}
		else {
			error( 'Er is iets fout gegaan!' );
		}
		redirect( '/beheer/agenda' );
	}

	/**
	 * Deletes an event from the database.
	 *
	 * @param int $id the event_id which is to be deleted.
	 */
	public function delete( int $id ) {
		if ( $this->agenda_model->delete( $id ) > 0 ) {
			success( 'Het evenement is verwijderd.' );
		}
		else {
			error( 'Er is iets fout gegaan!' );
		}
		redirect( '/beheer/agenda' );
	}

	/**
	 * Cancels the registration for a user.
	 *
	 * @param int $event_id  The ID of the event.
	 * @param int $member_id The ID of the member.
	 */
	public function afmelden( int $event_id, int $member_id ) {
		$event = $this->agenda_model->get_event( $event_id );

		try {
			if ( $event->cancel( $member_id ) ) {
				success( 'Afmelden gelukt!' );
			}
			else {
				error( 'Het is niet gelukt de persoon af te melden.' );
			}
		} catch ( Error $e ) {
			error( $e->getMessage() );
		}

		redirect( '/beheer/inschrijvingen/' . $event_id );
	}

	/**
	 * Converts input from the fields to MySQL format.
	 *
	 * @param array $data Form data.
	 *
	 * @return array Form data with the correct values for saving.
	 */
	private function format_input_to_mysql_datetime( array $data ): array {
		if ( $data['inschrijfsysteem'] ) {
			$data['inschrijfdeadline'] = $this->format_item_to_mysql_datetime( $data['inschrijfdeadline'] );
			$data['afmelddeadline']    = $this->format_item_to_mysql_datetime( $data['afmelddeadline'] );
		}
		$data['van'] = $this->format_item_to_mysql_datetime( $data['van'] );
		$data['tot'] = $this->format_item_to_mysql_datetime( $data['tot'] );

		return $data;
	}

	/**
	 * Formats a string so that it can be saved in MySQL.
	 *
	 * @param string $item The string that will be formatted to a MySQL datetime.
	 *
	 * @return string A formatted string.
	 * @throws Error
	 */
	private function format_item_to_mysql_datetime( string $item ): string {
		$result = date_format( date_create( $item ), 'Y-m-d H:i:s' );

		if ( ! $result ) {
			throw new Error( 'Invalid date format' );
		}

		return $result;
	}
}
