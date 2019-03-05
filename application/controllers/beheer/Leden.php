<?php

/**
 * Class Leden
 * Handles all beheer functionality related to Leden
 */
class Leden extends _BeheerController {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Load Leden overzichts page
	 */
	public function index() {
		$data['leden']   = $this->profile_model->get_profile();
		$this->loadView( 'beheer/leden/leden', $data );
	}

	/**
	 * Load a form where a leden.csv file can be uploaded
	 */
	public function importeren() {
		$this->load->helper( 'form' );
		$this->loadView( 'beheer/leden/importeren' );
	}

	/**
	 * Delete a certain profile
	 *
	 * @param int|null $id
	 */
	public function delete( $id = NULL ) {
		if( $id !== NULL ) {
			if( $this->profile_model->delete( $id ) ) {
				$this->session->set_flashdata( 'success', 'Gebruiker verwijderd.' );
			}
			else {
				$this->session->set_flashdata( 'error', 'Het is niet gelukt om de gebruiker te verwijderen.' );
			}
		}
		redirect( '/beheer/leden' );
	}


}