<?php
require_once( 'application/entities/Event.php' );

/**
 * Class Agenda_model
 * Model for accessing Agenda related database data
 */
class Agenda_model extends CI_Model {
	private const PRIMARY_KEY = 'event_id';
	private const TABLE = 'agenda';

	/**
	 * Update an event in the database
	 *
	 * @param $data array Data of the event
	 *
	 * @return int Number of affected database rows
	 */
	public function update_event( array $data ): int {
		$this->db->set( $data );
		$this->db->where( self::PRIMARY_KEY, $data['event_id'] );
		$this->db->update( self::TABLE );
		$this->db->flush_cache();

		return $this->db->affected_rows();
	}

	/**
	 * Add an event to the database
	 *
	 * @param $data array Event data
	 *
	 * @return int Number of affected database rows
	 */
	public function add_event( array $data ): int {
		$this->db->set( $data );
		$this->db->insert( self::TABLE );
		$this->db->flush_cache();

		return $this->db->affected_rows();
	}

	/**
	 * Delete an event in the database
	 *
	 * @param $id int ID of the event to be deleted
	 *
	 * @return int Number of affected database rows
	 */
	public function delete( int $id ): int {
		$this->db->delete( self::TABLE, [ self::PRIMARY_KEY => $id ] );
		$this->db->flush_cache();

		return $this->db->affected_rows();
	}

	/**
	 * Get the number of aanmelding for an event
	 *
	 * @param          $event_id int ID of the event
	 * @param null|int $id       ID of a certain user
	 *
	 * @return int
	 */
	public function get_aantal_aanmeldingen( int $event_id, int $id = null ): int {
		$this->db->select( 'count(event_id) as aantal' );
		$this->db->where( self::PRIMARY_KEY, $event_id );
		if ( $id !== null ) {
			$this->db->where( 'member_id', $id );
		}
		$query = $this->db->get( 'inschrijvingen' );
		if ( $query->num_rows() > 0 ) {
			$result = $query->result();

			return $result[0]->aantal;
		}

		return 0;
	}

	/**
	 * Meld an user aan for an event
	 *
	 * @param $data
	 *
	 * @return bool
	 */
	public function aanmelden( array $data ): bool {
		$this->db->insert( 'inschrijvingen', $data );

		return ( $this->db->affected_rows() > 0 );
	}

	/**
	 * Meld an user af for an event
	 *
	 * @param $id
	 * @param $event_id
	 *
	 * @return mixed
	 */
	public function afmelden( int $id, int $event_id ): int {
		$this->db->where( 'member_id', $id );
		$this->db->where( 'nszk_id', $event_id );
		$this->db->delete( 'nszk_inschrijfsysteem' );

		$this->db->where( 'member_id', $id );
		$this->db->where( 'event_id', $event_id );
		$this->db->delete( 'inschrijvingen' );

		return $this->db->affected_rows();
	}

	/**
	 * Add the data to the NSZK table
	 *
	 * @param $data
	 *
	 * @return bool
	 */
	public function nszk_inschrijving( array $data ): bool {
		$this->db->insert( 'nszk_inschrijfsysteem', $data );

		return ( $this->db->affected_rows() > 0 );
	}

	/**
	 * Function to update the nszk_inschrijving details
	 *
	 * @param $data array New data of the inschrijving
	 *
	 * @return bool bla
	 */
	public function update_nszk_inschrijving( array $data ): bool {
		$this->db->update(
			'nszk_inschrijfsysteem',
			$data,
			[
				'nszk_id'   => $data['nszk_id'],
				'member_id' => $data['member_id'],
			]
		);

		return $this->db->affected_rows() == 1;
	}

	/**
	 * Get a list of inschrijvingen
	 *
	 * @param          $event_id  int ID of event
	 * @param null|int $member_id Which member
	 * @param null|int $limit     Limits the number of aanmeldingen we get
	 *
	 * @return array An array of inschrijvingen
	 */
	public function get_inschrijvingen( int $event_id, int $member_id = null, int $limit = null ) {
		if ( $member_id === null && $limit === null ) {
			$this->db->order_by( 'datum desc' );
			$query = $this->db->get_where( 'inschrijvingen', [ 'event_id' => $event_id ] );
		}
		elseif ( $member_id === null ) {
			$this->db->limit( $limit );
			$this->db->order_by( 'datum desc' );
			$query = $this->db->get_where( 'inschrijvingen', [ 'event_id' => $event_id ] );
		}
		else {
			$query = $this->db->get_where( 'inschrijvingen', [ 'event_id' => $event_id, 'member_id' => $member_id ] );
		}
		if ( $query->num_rows() > 0 ) {
			foreach ( $query->result() as $inschrijving ) {
				$inschrijving->naam = $this->profile_model->get_profile( $inschrijving->member_id )->naam;
			}

			return $query->result();
		}

		return [];
	}

	/**
	 * Check if $event_id belongs to a NSZK
	 *
	 * @param $event_id
	 *
	 * @return bool Whether this event is a NSZK
	 */
	public function is_nszk( int $event_id ): bool {
		$result = $this->get_event( $event_id );

		return ( $result->soort === 'nszk' );
	}

	/**
	 * Function to get a list of events
	 *
	 * @param null|int $event_id If null an overview is loaded, when provided event with this ID is shown
	 * @param null|int $limit    Limits the number of event we get
	 *
	 * @return bool|array FALSE on failure, an array of events on success
	 */
	public function get_event( int $event_id = null, int $limit = null ) {
		if ( $event_id === null ) {
			//Get all events
			$this->db->where( 'van >=', date( 'Y-m-d H:i:s' ) );
			$this->db->order_by( 'van', 'ASC' );
			if ( $limit !== null ) {
				$this->db->limit( $limit );
			}
			$query = $this->db->get( self::TABLE );
			if ( $query->num_rows() > 0 ) {
				return $query->result();
			}

			return false;
		}
		else {
			$this->db->select( '*' );
			$this->db->from( self::TABLE );
			$this->db->where( self::PRIMARY_KEY, $event_id );
			$this->db->limit( 1 );
			$query = $this->db->get();

			return $query->first_row( 'Event' );
		}
	}

	public function get_old_events() {
		$this->db->where( 'van <', date( 'Y-m-d H:i:s' ) );
		$this->db->order_by( 'van', 'DESC' );
		$query = $this->db->get( self::TABLE );

		return $query->result();
	}

	/**
	 * Get the NSZK details for a certain event / member combi
	 *
	 * @param $event_id
	 * @param $member_id
	 *
	 * @return mixed
	 */
	public function get_details( int $event_id, int $member_id ) {
		$query = $this->db->get_where( 'nszk_inschrijfsysteem', [ 'nszk_id' => $event_id, 'member_id' => $member_id ] );

		return $query->row();
	}
}
