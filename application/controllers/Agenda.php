<?php

/**
 * Controller to handle all Agenda related URLs
 * Class Agenda
 */
class Agenda extends _SiteController {
	/**
	 * Agenda constructor.
	 * Some functions require superuser access, these are determined here in the constructor.
	 */
	public function __construct() {
		parent::__construct();
		if( ! $this->session->logged_in ) {
			$this->session->set_flashdata( 'redirect', current_url() );
			redirect( '/inloggen' );
		}
		if( $this->session->engels ) {
			$this->lang->load( "agenda", "english" );
		}
		else {
			$this->lang->load( "agenda" );
		}
	}

	/**
	 * Function to visualize the upcoming event.
	 *
	 * @param null $event_id if event_id is specified, show the specified event, else show the index.
	 */
	public function id( $event_id = NULL ) {
		if( $event_id === NULL ) {
			$this->index();
		}
		else {
			$event = $this->agenda_model->get_event( $event_id );
			if( empty( $event ) ) {
				show_404();
			}

			if( $this->session->engels ) {
				$event->naam         = $event->en_naam;
				$event->omschrijving = $event->en_omschrijving;
			}
			else {
				$event->naam         = $event->nl_naam;
				$event->omschrijving = $event->nl_omschrijving;
			}

			$data['event']                = $event;
			$data['aangemeld']            = ( $this->agenda_model->get_aantal_aanmeldingen( $event_id, $this->session->id ) == 1 );
			$data['inschrijvingen']       = $this->agenda_model->get_inschrijvingen( $event_id, NULL );
			$data['aantal_aanmeldingen']  = sizeof( $data['inschrijvingen'] );
			$data['registration_details'] = $data['aangemeld'] && $event->soort === 'nszk';
			if( empty( $data['inschrijvingen'] ) )
				unset( $data['inschrijvingen'] );

			$this->loadView( 'agenda/id', $data );
		}
	}

	/**
	 * The index funtion shows a list of upcoming events to the user.
	 */
	public function index() {
		if( ( $data['events'] = $this->agenda_model->get_event() ) !== FALSE ) {
			foreach( $data['events'] as $event ) {
				$event->aanmeldingen = $this->agenda_model->get_aantal_aanmeldingen( $event->event_id );
				if( $this->session->engels ) {
					$event->naam = $event->en_naam;
				}
				else {
					$event->naam = $event->nl_naam;
				}
			}
			$this->loadView( 'agenda/index', $data );
		}
		else {
			$this->loadView( 'agenda/no_events' );
		}
	}

	/**
	 * Function that allows editing the details for a given event_id
	 *
	 * @param $event_id int The event of which the details will be edited
	 */
	public function edit_details( $event_id ) {
		$inschrijving = $this->agenda_model->get_inschrijvingen( $event_id, $this->session->id );
		$is_nszk      = $this->agenda_model->is_nszk( $event_id );
		if( empty( $inschrijving ) || ! $is_nszk )
			show_404();
		$data['details']   = $this->agenda_model->get_details( $event_id, $this->session->id );
		$data['edit_mode'] = ! empty( $data['details'] );
		$data['nszk_id']   = $event_id;
		$this->loadView( 'agenda/nszk_form', $data );
	}

	/**
	 * POST handler to update the NSZK inschrijving details
	 */
	public function update_details() {
		$data              = $this->input->post( NULL, TRUE );
		$data['member_id'] = $this->session->id;
		if( $this->agenda_model->update_nszk_inschrijving( $data ) ) {
			$this->session->set_flashdata( 'success', "Je aanmelding is bijgewerkt!" );
		}
		else {
			$this->session->set_flashdata( 'error', "Er is een fout opgetreden." );
		}
		redirect( '/agenda/id/' . $data['nszk_id'] );
	}

	/**
	 * Function to sign up for an NSZK.
	 */
	public function nszk() {
		$data              = $this->input->post( NULL, TRUE );
		$data['member_id'] = $this->session->id;

		$slagen = [];

		if( $data['opmerking'] === "" )
			unset( $data['opmerking'] );

		if( $data['event_soort'] === 'nszk' ) {
			for( $i = 0; $i < sizeof( $data['slag'] ); $i ++ ) {
				if( $data['tijd'][ $i ] !== "" ) {
					$slagen[ $data['slag'][ $i ] ] = $data['tijd'][ $i ];
				}
			}
			$slagen         = json_encode( $slagen );
			$data['slagen'] = $slagen;
			if( sizeof( $data['slagen'] ) === 0 ) {
				unset( $data['slagen'] );
			}
		}

		unset( $data['slag'] );
		unset( $data['tijd'] );
		unset( $data['event_soort'] );

		if( $this->agenda_model->aanmelden( $data ) ) {
			$this->loadView( 'agenda/nszk_form', [ 'nszk_id' => $data['event_id'] ] );
		}
		else {
			$this->session->set_flashdata( 'error', "Er is iets mis gegaan met je aanmelding." );
			redirect( '/agenda/id/' . $data['event_id'] );
		}
	}

	/**
	 * Function to save the additional information required for an NSZK.
	 */
	public function nszk_inschrijven() {
		$data              = $this->input->post( NULL, TRUE );
		$data['member_id'] = $this->session->id;
		if( $this->agenda_model->nszk_inschrijving( $data ) ) {
			$this->session->set_flashdata( 'success', "Je bent aangemeld voor dit NSZK!" );
		}
		else {
			$this->session->set_flashdata( 'error', "Er is iets mis gegaan met je aanmelding." );
		}
		redirect( '/agenda/id/' . $data['nszk_id'] );
	}

	/**
	 * Function for joining an event.
	 * For security purposes we get the id from the session, could be faked otherwise.
	 */
	public function aanmelden() {
		$data              = $this->input->post( NULL, TRUE );
		$data['member_id'] = $this->session->id;

		if( $data['opmerking'] === "" )
			unset( $data['opmerking'] );

		unset( $data['slag'] );
		unset( $data['tijd'] );
		unset( $data['event_soort'] );

		if( $this->agenda_model->aanmelden( $data ) ) {
			$this->session->set_flashdata( 'success', "Aanmelden gelukt!" );
		}
		else {
			$this->session->set_flashdata( 'error', "Het is niet gelukt om je aan te melden." );
		}
		redirect( '/agenda/id/' . $data['event_id'] );
	}

	/**
	 * Function to cancel joining an event.
	 *
	 * @param integer $event_id specifies for which event the user wants to cancel.
	 */
	public function afmelden( $event_id, $id = NULL ) {
		$event = $this->agenda_model->get_event( $event_id );
		if( $id === NULL ) {
			$id = $this->session->id;
			if( date( 'Y-m-d' ) > $event->afmelddeadline ) {
				$this->session->set_flashdata( 'error', "Je kunt je niet meer afmelden voor dit evenement." );
			}
			elseif( $this->agenda_model->afmelden( $id, $event_id ) ) {
				$this->session->set_flashdata( 'success', "Afmelden gelukt!" );
			}
			else {
				$this->session->set_flashdata( 'error', "Het is niet gelukt om je af te melden." );
			}
			redirect( '/agenda/id/' . $event_id );
		}
		else {
			if( $this->agenda_model->afmelden( $id, $event_id ) ) {
				$this->session->set_flashdata( 'success', "Afmelden gelukt!" );
			}
			else {
				$this->session->set_flashdata( 'error', "Het is niet gelukt de persoon af te melden." );
			}
			redirect( '/beheer/inschrijvingen/' . $event_id );
		}
	}
}
