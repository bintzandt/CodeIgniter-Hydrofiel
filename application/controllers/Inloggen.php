<?php

/**
 * Class Inloggen
 * Handles shit related to logging in on the website.
 */
class Inloggen extends _SiteController {
	public Login_model $login_model;
	public Profile_model $profile_model;
	public CI_Session $session;
	public CI_Form_validation $form_validation;
	public CI_Input $input;

	public function __construct() {
		parent::__construct();
		$this->load->model( 'login_model' );
		load_language_file( 'inloggen' );
		load_language_file( 'error' );
	}

	/**
	 * Shows the login page.
	 */
	public function index(): void {
		// If the user is logged in, log out the user.
		if ( $this->session->logged_in ) {
			$this->session->sess_destroy();
			$this->session->logged_in = false;
		}

		// Check whether there is valid data in the form.
		if ( $this->form_validation->run() === false ) {
			$this->loadView( 'inloggen/index' );
			exit;
		}

		$post = $this->input->post( null, true );
		$this->verify_login( $post );
	}

	/**
	 * Verify the login attempt.
	 */
	public function verify_login( $data ) {
		$email      = strtolower( $data['email'] );
		$wachtwoord = $data['wachtwoord'];

		$user = $this->profile_model->get_user_by_email( $email );

		if ( ! $user || ! $user->verify_login( $wachtwoord ) ) {
			error( lang( 'error_email_password_incorrect' ) );
			redirect( '/inloggen' );
		}

		if ( $user->needs_rehash() ) {
			$user->wachtwoord = $wachtwoord;
			$this->profile_model->update( $user->id, $user );
		}

		$userdata = [
			'id'        => $user->id,
			'superuser' => $user->is_superuser(),
			'logged_in' => true,
			'engels'    => $user->get_engels(),
		];
		$this->session->set_userdata( $userdata );
		$this->login_model->unset_recovery( $user->id );

		$redirect = $this->session->userdata( 'redirect' ) ?? '/';
		redirect( $redirect );
	}

	/**
	 * Show password forgotten page
	 */
	public function forgot_password(): void {
		if ( $this->form_validation->run() == false ) {
			$this->loadView( 'inloggen/wachtwoord_vergeten' );
			exit;
		}

		$this->generate_reset();
	}

	public function generate_reset() {
		$data = $this->input->post( null, true );

		if ( empty( $data ) ) {
			redirect( '/inloggen' );
		}

		$result = $this->login_model->set_recovery( $data['email'] );

		// We do not want to provide any additional information about whether accounts exist in our database.
		if ( $result !== false ) {
			if ( ! $this->send_password_recovery_mail(
				$result['email'],
				$result['recovery'],
				$result['recovery_valid']
			) ) {
				error( 'Het is niet gelukt om de mail te sturen. Neem contact op met <a href="mailto:webmaster@hydrofiel.nl">de webmaster</a>.' );
			}
		}

		success( 'Je ontvangt een e-mail als het adres bij ons bekend is.' );
		redirect( '/inloggen' );
	}

	/**
	 * Function to reset the password or to send the mail
	 *
	 * @param null $recovery string the recovery code provided in the mail
	 */
	public function reset( $recovery = null ) {
		//If no recovery has been provided err.
		if ( $recovery === null ) {
			error( 'Geen recovery meegegeven.' );
			redirect( '/inloggen/forgot_password' );
		}

		//Check if we can reset this password
		$result = $this->login_model->get_id_and_mail( $recovery );
		if ( $result === false ) {
			error( 'Deze recovery is onbekend of niet meer geldig.' );
			redirect( '/inloggen/forgot_password' );
		}

		if ( $this->form_validation->run() !== false ) {
			$this->set_new_pass();
			success( 'Het wachtwoord is veranderd.' );
			redirect( '/inloggen' );
		}

		//Show the new password form
		$data['recovery'] = $recovery;
		$this->loadView( 'inloggen/nieuw_wachtwoord', $data );
	}

	/**
	 * Private function to send recovery email
	 *
	 * @param $email    string Receiving emailadress
	 * @param $recovery string The recovery that the user can use
	 * @param $valid    string until which time is the recovery valid
	 *
	 * @return true | false
	 */
	private function send_password_recovery_mail( $email, $recovery, $valid ) {
		$data = [
			'recovery' => $recovery,
			'valid'    => $valid,
		];
		$this->email->to( $email );
		$this->email->from( 'bestuur@hydrofiel.nl', 'Hydrofiel wachtwoordherstel' );
		$this->email->subject( "Wachtwoord vergeten 🐰" );
		$this->email->message( $this->load->view( 'mail/wachtwoord', $data, true ) );

		return $this->email->send();
	}

	/**
	 * Function to actually set a new password
	 */
	public function set_new_pass() {
		$data = $this->input->post( null, true );

		// Check if we have a valid recovery.
		$result = $this->login_model->get_id_and_mail( $data['recovery'] );

		if ( $result === false ) {
			error( 'Deze recovery is onbekend.' );
			redirect( 'inloggen/reset/' . $data['recovery'] );
		}

		$update = [
			'wachtwoord'     => password_hash( $data['wachtwoord1'], PASSWORD_DEFAULT ),
			'recovery_valid' => null,
			'recovery'       => null,
		];

		if ( $this->profile_model->update( $result->id, $update ) > 0 ) {
			success( 'Wachtwoord succesvol opgeslagen' );
		}
		else {
			error( 'Er is iets mis gegaan bij het opslaan van je wachtwoord. Probeer het later opnieuw.' );
		}

		redirect( '/inloggen' );
	}

}
