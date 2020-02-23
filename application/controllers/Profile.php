<?php

/**
 * Class Profile
 * Handles all actions related to user profiles
 */
class Profile extends _SiteController {
	public CI_Session $session;
	public Profile_model $profile_model;
	public CI_Email $email;
	public CI_Input $input;

	/**
	 * Profile constructor.
	 * Ensures that a user is logged in and loads the correct language file.
	 */
	public function __construct() {
		parent::__construct();

		must_be_logged_in();
		load_language_file( 'profile' );
	}

	/**
	 * @param int $id Optional user_id.
	 *
	 * @see index.
	 */
	public function id( int $id = null ) {
		$this->index( $id );
	}

	/**
	 * Function to view a certain profile
	 *
	 * @param int $id ID of the user that will be viewed
	 *                If no ID is specified, the profile of the logged in user will be viewed
	 */
	public function index( int $id = null ) {
		// If no ID specified, ID will be pulled from session
		if ( ! $id ) {
			$id = $this->session->id;
		}
		$data['profile'] = $this->profile_model->get_profile( $id );

		if ( empty( $data['profile'] ) ) {
			show_404();
		}

		$this->loadView( 'profile/view', $data );
	}

	/**
	 * Function to edit a profile
	 *
	 * @param int $id Which profile will be edited
	 */
	public function edit( $id = 0 ) {
		// Same check as in index, if no ID specified, ID will be pulled from session
		if ( ! $id ) {
			$id = $this->session->id;
		}
		// Check to see if this user is allowed to edit the profile
		if ( ! is_admin_or_requested_user( $id ) ) {
			show_error( 'Je bent hiervoor niet bevoegd!' );
		}
		// Get profile data.
		$data['profile'] = $this->profile_model->get_profile( $id );

		if ( empty( $data['profile'] ) ) {
			show_404();
		}

		$this->loadView( 'profile/edit', $data );
	}

	/**
	 * Function to save a profile
	 *
	 * @param $id int Which profile is this
	 */
	public function save( $id ) {
		if ( ! is_admin_or_requested_user( $id ) ) {
			show_error( 'Je bent niet bevoegd om deze gebruiker te bewerken.' );
		}

		$data = $this->input->post( null, true );
		$user = $this->profile_model->get_profile( $id );

		//Check if the password has been changed and check if the same wachtwoord has been entered twice
		if ( ! empty( $data['wachtwoord1'] ) && $data['wachtwoord1'] === $data['wachtwoord2'] ) {
			$user->wachtwoord = $data['wachtwoord1'];
		}

		if ( ! isset( $data['zichtbaar_email'] ) ) {
			$user->zichtbaar_email = 0;
		} else {
			$user->zichtbaar_email = 1;
		}
		if ( ! isset( $data['nieuwsbrief'] ) ) {
			$user->nieuwsbrief = 0;
		} else {
			$user->nieuwsbrief = 1;
		}
		if ( ! isset( $data['engels'] ) ) {
			$user->engels = 0;
		} else {
			$user->engels = 1;
		}

		if ( $user->email !== $data['email'] ){
			$user->email = $data['email'];
			$this->send_user_update_mail(
				[
					'naam'   => $data['naam'],
					'change' => [
						'email' => $data['email'],
					],
				]
			);
		}

		$nr = $this->profile_model->update( $id, $user );
		if ( $nr > 0 ) {
			success( 'Gebruiker is opgeslagen.' );
		}
		else {
			error( 'Gebruiker is niet veranderd.' );
		}

		redirect( '/profile/index/' . $id );
	}

	/**
	 * Function to send a mail to the secretary
	 *
	 * @param $data array Which data has been edited
	 *
	 * @return boolean Has the mail been send succesful
	 */
	private function send_user_update_mail( $data ) {
		$this->email->to( 'secretaris@hydrofiel.nl' );
		$this->email->from( 'no-reply@hydrofiel.nl', 'Ledennotificatie' );
		$this->email->subject( 'Lid bewerkt' );
		$this->email->message( $this->load->view( 'mail/update', $data, true ) );

		return $this->email->send();
	}
}
