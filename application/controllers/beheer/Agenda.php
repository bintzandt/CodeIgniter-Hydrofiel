<?php

/**
 * Class Agenda
 * Used for handling all beheer functions related to the Agenda
 */
class Agenda extends _BeheerController {
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Load the index page
	 */
	public function index() {
		$data['events']  = $this->agenda_model->get_event();
		$this->loadView( 'beheer/agenda/agenda', $data );
	}

	/**
	 * Load the inschrijvingen pagina, possibly for a specific member
	 */
	public function inschrijvingen( $event_id, $member_id = NULL ) {
		if( $member_id !== NULL ) {
			$inschrijving = $this->agenda_model->get_inschrijvingen( $event_id, $member_id );
			if( ! empty( $inschrijving ) ) {
				$data["inschrijving"] = $inschrijving[0];
				$data["event_id"]     = $event_id;
				if( $this->agenda_model->is_nszk( $event_id ) ) {
					$data["nszk"]    = TRUE;
					$data["slagen"]  = json_decode( $data["inschrijving"]->slagen );
					$data["details"] = $this->agenda_model->get_details( $event_id, $member_id );
				}
				else {
					$data["nszk"] = FALSE;
				}
				$this->loadView( 'beheer/agenda/inschrijving_detail', $data );
			}
		}
		else {
			$data["inschrijvingen"] = $this->agenda_model->get_inschrijvingen( $event_id );
			$data["event_id"]       = $event_id;
			if( empty( $data["inschrijvingen"] ) ) {
				$data["error"] = TRUE;
			}
			$this->loadView( 'beheer/agenda/inschrijvingen', $data );
		}
	}

	/**
	 * Creates a page to add a new event to the database.
	 */
	public function add() {
		$data['edit_mode'] = FALSE;
		$this->loadView( 'beheer/agenda/edit_add', $data );
	}

	/**
	 * @param int $id Specifies which event is to be edited.
	 *                Creates a page on which the user can edit the event.
	 */
	public function edit( $id ) {
		$data['edit_mode'] = TRUE;
		$data['event']     = $this->agenda_model->get_event( $id );
		if( empty( $data['event'] ) )
			show_404();
		$this->loadView( 'beheer/agenda/edit_add', $data );
	}

	/**
	 * Handles the actual request to save the event in the database.
	 * For editing only!
	 */
	public function save() {
		$data = $this->input->post( NULL, TRUE );
		$data = $this->format_input_to_mysql_datetime( $data );

		if( $data['soort'] === 'nszk' ) {
			$data['slagen'] = json_encode( $data['slagen'] );
		}
		else {
			unset( $data['slagen'] );
		}
		if( $this->agenda_model->update_event( $data ) > 0 ) {
			$this->session->set_flashdata( 'success', "Het evenement is succesvol bewerkt!" );
		}
		else {
			$this->session->set_flashdata( 'error', "Het evenement is niet veranderd!" );
		}
		redirect( '/beheer/agenda' );
	}

	private function format_input_to_mysql_datetime( $data ) {
		if( $data['inschrijfsysteem'] ) {
			$data['inschrijfdeadline'] = $this->format_item_to_mysql_datetime( $data['inschrijfdeadline'] );
			$data['afmelddeadline']    = $this->format_item_to_mysql_datetime( $data['afmelddeadline'] );
		}
		$data['van'] = $this->format_item_to_mysql_datetime( $data['van'] );
		$data['tot'] = $this->format_item_to_mysql_datetime( $data['tot'] );

		return $data;
	}

	private function format_item_to_mysql_datetime( $data ) {
		return date_format( date_create( $data ), 'Y-m-d H:i:s' );
	}

	/**
	 * Handles the creates to save the event in the database.
	 * For a new event only!
	 */
	public function submit() {
		$data = $this->input->post( NULL, TRUE );
		$data = $this->format_input_to_mysql_datetime( $data );

		if( $data['soort'] === 'nszk' ) {
			$data['slagen'] = json_encode( $data['slagen'] );
		}
		else {
			unset( $data['slagen'] );
		}
		if( $this->agenda_model->add_event( $data ) > 0 ) {
			$this->session->set_flashdata( 'success', "Het evenement is succesvol toegevoegd!" );
		}
		else {
			$this->session->set_flashdata( 'error', "Er is iets fout gegaan!" );
		}
		redirect( '/beheer/agenda' );
	}

	/**
	 * Deletes an event from the database.
	 *
	 * @param integer $id the event_id which is to be deleted.
	 */
	public function delete( $id ) {
		if( $this->agenda_model->delete( $id ) > 0 ) {
			$this->session->set_flashdata( 'success', "Het evenement is verwijderd!" );
		}
		else {
			$this->session->set_flashdata( 'error', "Er is iets fout gegaan!" );
		}
		redirect( '/beheer/agenda' );
	}
}