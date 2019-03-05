<?php

/**
 * Class Profile
 * Handles all actions related to user profiles
 */
class Profile extends _SiteController {
	public function __construct() {
		parent::__construct();
		if( ! $this->session->logged_in ) {
			redirect( '/inloggen', 'refresh' );
		}
		if( $this->session->engels ) {
			$this->lang->load( "profile", "english" );
		}
		else {
			$this->lang->load( "profile" );
		}
	}

	/**
	 * See Profile/index
	 */
	public function id( $id = 0 ) {
		$this->index( $id );
	}

	/**
	 * Function to view a certain profile
	 *
	 * @param int $id ID of the user that will be viewed
	 *                If no ID is specified, the profile of the logged in user will be viewed
	 */
	public function index( $id = 0 ) {
		if( $id === 0 && isset( $this->session->id ) ) {
			$id = $this->session->id;
		}
		$data['profile'] = $this->profile_model->get_profile( $id );

		if( ! ( $this->session->superuser || $this->session->id === $id ) ) {
			$data['profile']->email        = $data['profile']->zichtbaar_email ? $data['profile']->email : lang( "profile_hidden" );
			$data['profile']->mobielnummer = $data['profile']->zichtbaar_telefoonnummer ? $data['profile']->mobielnummer : lang( "profile_hidden" );
			$data['profile']->adres        = $data['profile']->zichtbaar_adres ? $data['profile']->adres : lang( "profile_hidden" );
		}

		if( empty( $data['profile'] ) ) {
			show_404();
		}

		$data['profile']->lidmaatschap = $this->lidmaatschap( $data['profile']->lidmaatschap );
		$this->loadView( 'profile/view', $data );
	}

	/**
	 * Small helper function for lidmaatschap
	 *
	 * @param $soort string one of the select fields
	 *
	 * @return string A properly formatted string
	 */
	private function lidmaatschap( $soort ) {
		switch( $soort ) {
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

	/**
	 * Function to edit a profile
	 *
	 * @param int $id Which profile will be edited
	 */
	public function edit( $id = 0 ) {
		//Same check as in index, if no ID specified, ID will be pulled from session
		if( $id === 0 && isset( $this->session->id ) ) {
			$id = $this->session->id;
		}
		//Check to see if this user is allowed to edit the profile
		if( ! ( $this->session->superuser || $this->session->id === $id ) ) {
			show_error( "Je bent hiervoor niet bevoegd!" );
		}
		//Get profile data
		$data['profile'] = $this->profile_model->get_profile( $id );
		if( empty( $data['profile'] ) ) {
			show_404();
		}
		$data['profile']->lidmaatschap = $this->lidmaatschap( $data['profile']->lidmaatschap );
		$this->loadView( 'profile/edit', $data );
	}

	/**
	 * Function to save a profile
	 *
	 * @param $id int Which profile is this
	 *            TODO: Make this async for better performance
	 */
	public function save( $id ) {
		if( ! ( $this->session->superuser || $id === $this->session->id ) ) {
			show_error( "Je bent niet bevoegd om deze gebruiker te bewerken!" );
		}
		$data = $this->input->post( NULL, TRUE );
		//Check if the password has been changed and check if the same wachtwoord has been entered twice
		if( $data['wachtwoord1'] !== 'wachtwoord' && $data['wachtwoord1'] === $data['wachtwoord2'] ) {
			$data['wachtwoord'] = password_hash( $data['wachtwoord1'], PASSWORD_DEFAULT );
		}
		//Unset the outdated data
		unset( $data['wachtwoord1'] );
		unset( $data['wachtwoord2'] );

		if( ! isset( $data['zichtbaar_telefoonnummer'] ) ) {
			$data['zichtbaar_telefoonnummer'] = 0;
		}
		if( ! isset( $data['zichtbaar_email'] ) ) {
			$data['zichtbaar_email'] = 0;
		}
		if( ! isset( $data['zichtbaar_adres'] ) ) {
			$data['zichtbaar_adres'] = 0;
		}
		if( ! isset( $data['nieuwsbrief'] ) ) {
			$data['nieuwsbrief'] = 0;
		}
		if( ! isset( $data['engels'] ) ) {
			$data['engels'] = 0;
		}

		$profile = $this->profile_model->get_profile_array( $id );
		//We will notify the secretary of changes to these fields
		$important_changes = [ "email", "adres", "postcode", "plaats", "mobielnummer" ];
		$nr                = $this->profile_model->update( $id, $data );
		if( $nr > 0 ) {
			//Things have changed
			$profile_update = $this->profile_model->get_profile_array( $id );
			$diff           = array_diff_assoc( $profile_update, $profile );
			//Check if one of the important fields has been changed
			foreach( $important_changes as $key ) {
				if( array_key_exists( $key, $diff ) ) {
					$change[ $key ] = $diff[ $key ];
				}
			}
			if( $change !== NULL ) {
				//Mail to the secretary
				$this->send_user_update_mail( [
					"naam"   => $data["naam"],
					"change" => $change,
				] );
			}
			$this->session->set_flashdata( 'success', 'Gebruiker is opgeslagen.' );
		}
		else {
			$this->session->set_flashdata( 'error', 'Gebruiker is niet veranderd' );
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
		$this->email->subject( "Lid bewerkt" );
		$this->email->message( $this->load->view( 'mail/update', $data, TRUE ) );

		return $this->email->send();
	}
}