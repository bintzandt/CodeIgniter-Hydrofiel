<?php

/**
 * Class Inloggen
 * Handles shit related to logging in on the website.
 */
class Inloggen extends _SiteController {
	public function __construct() {
		parent::__construct();
		$this->load->model( 'login_model' );
		if( $this->session->engels ) {
			$this->lang->load( "inloggen", "english" );
		}
		else {
			$this->lang->load( "inloggen" );
		}
	}

	/**
	 * Show the default login page
	 */
	public function index() {
		$this->load->helper( [ 'form', 'url' ] );
		$this->load->library( 'form_validation' );
		$this->form_validation->set_rules( 'email', 'email', 'required|valid_email',
			[
				'required'    => 'Je moet een %s opgeven!',
				'valid_email' => 'Email en/of wachtwoord onjuist.',
			] );
		$this->form_validation->set_rules( 'wachtwoord', 'wachtwoord', 'required',
			[ 'required' => 'Je moet een %s opgeven.' ] );

		if( $this->session->logged_in ) {
			$this->session->sess_destroy();
			$this->session->logged_in = FALSE;
		}

		$post = $this->input->post( NULL, TRUE );

		//Check if we have a meaningfull referrer and that it does not refer to the inloggen page (or any subpage)
		if( $this->agent->referrer() !== "" && strpos( $this->agent->referrer(), 'inloggen' ) === FALSE ) {
			$data['redirect'] = $this->agent->referrer();
		}
		else {
			$data['redirect'] = isset( $post['redirect'] ) ? $post['redirect'] : '/';
		}

		if( $this->form_validation->run() == FALSE ) {
			$this->loadView( 'inloggen/index', $data );
		}
		else {
			$this->verify_login( $post );
		}
	}

	/**
	 * Verify the login
	 */
	public function verify_login( $data ) {
		$email      = strtolower( $data['email'] );
		$wachtwoord = $data['wachtwoord'];

		$login = $this->login_model->get_hash( $email );

		if( $login !== FALSE && ( $hash = $login->wachtwoord ) !== FALSE && password_verify( $wachtwoord, $hash ) ) {
			if( password_needs_rehash( $hash, PASSWORD_DEFAULT ) ) {
				$hash = password_hash( $wachtwoord, PASSWORD_DEFAULT );
				$this->login_model->set_hash( $email, $hash );
			}
			$userdata = [
				'id'        => $login->id,
				'superuser' => ( $login->rank <= 2 ),
				'logged_in' => TRUE,
				'engels'    => $login->engels,
			];
			$this->session->set_userdata( $userdata );
			$this->login_model->unset_recovery( $login->id );
			redirect( $data['redirect'] );
		}
		else {
			$this->session->set_flashdata( 'error', 'Email en/of wachtwoord onjuist.' );
			redirect( '/inloggen' );
		}
	}

	/**
	 * Show password forgotten page
	 */
	public function forgot_password() {
		$this->load->helper( [ 'form', 'url' ] );
		$this->load->library( 'form_validation' );
		$this->form_validation->set_rules( 'email', 'emailadres', 'valid_email|required',
			[
				'required'    => 'Je moet een %s invullen.',
				'valid_email' => 'Het ingevulde %s is niet geldig.',
			] );
		if( $this->form_validation->run() == FALSE ) {
			$this->loadView( 'inloggen/wachtwoord_vergeten' );
		}
		else {
			$this->reset();
		}
	}

	/**
	 * Function to reset the password or to send the mail
	 *
	 * @param null $recovery string the recovery ccode provided in the mail
	 */
	public function reset( $recovery = NULL ) {
		//If no recovery has been provided we will generate one and send an email.
		if( $recovery === NULL ) {
			$data = $this->input->post( NULL, TRUE );
			if( empty( $data ) )
				redirect( '/inloggen' );
			if( ( $result = $this->login_model->set_recovery( $data['email'] ) ) !== FALSE ) {
				if( $this->send_password_recovery_mail( $result['email'], $result['recovery'], $result['recovery_valid'] ) ) {
					$this->session->set_flashdata( 'success', 'Er is een mail met een resetcode naar je gestuurd!' );
				}
				else {
					$this->session->set_flashdata( 'error', 'Het is niet gelukt om de mail te sturen. Neem contact op met <a href="mailto:webmaster@hydrofiel.nl">de webmaster</a>.' );
				}
			}
			else {
				$this->session->set_flashdata( 'error', 'Dit mailadres is niet bij ons bekend.' );
			}
			redirect( '/inloggen' );
		}
		//Check if we can reset this password
		else {
			$result = $this->login_model->get_id_and_mail( $recovery );
			if( $result !== FALSE ) {
				$this->load->helper( [ 'form', 'url' ] );
				$this->load->library( 'form_validation' );
				$this->form_validation->set_rules( 'wachtwoord1', 'wachtwoord', 'required',
					[ 'required' => 'Je moet een %s invullen.' ] );
				$this->form_validation->set_rules( 'wachtwoord2', 'wachtwoord', 'required|matches[wachtwoord1]',
					[
						'required' => 'Je moet het %s bevestigen.',
						'matches'  => 'De wachtwoorden komen niet overeen.',
					] );
				if( $this->form_validation->run() == FALSE ) {
					//Show the new password form
					$data['recovery'] = $recovery;
					$this->loadView( 'inloggen/nieuw_wachtwoord', $data );
				}
				else {
					$this->set_new_pass();
				}
			}
			else {
				$this->session->set_flashdata( 'fail', 'Deze recovery is onbekend of niet meer geldig.' );
				redirect( '/inloggen' );
			}
		}
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
		$this->email->message( $this->load->view( 'mail/wachtwoord', $data, TRUE ) );

		return $this->email->send();
	}

	/**
	 * Function to actually set a new password
	 */
	public function set_new_pass() {
		$data = $this->input->post( NULL, TRUE );
		// Check if we still have a valid recovery...
		$result = $this->login_model->get_id_and_mail( $data['recovery'] );
		if( $result === FALSE ) {
			$this->session->set_flashdata( 'error', 'Deze recovery is onbekend.' );
		}
		else {
			$update = [
				'wachtwoord'     => password_hash( $data['wachtwoord1'], PASSWORD_DEFAULT ),
				'recovery_valid' => NULL,
				'recovery'       => NULL,
			];
			if( $this->profile_model->update( $result->id, $update ) > 0 ) {
				$this->session->set_flashdata( 'success', 'Wachtwoord succesvol opgeslagen' );
			}
			else {
				$this->session->set_flashdata( 'error', 'Er is iets mis gegaan bij het opslaan van je wachtwoord. Probeer het later opnieuw.' );
			}
			redirect( '/inloggen' );
		}
		redirect( 'inloggen/reset/' . $data['recovery'] );
	}

}