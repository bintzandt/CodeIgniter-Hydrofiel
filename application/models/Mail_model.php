<?php

/**
 * Class Mail_model
 * Handles all database actions related to mailing
 */
class Mail_model extends CI_Model {
	/**
	 * Gets a list of user from a certain group
	 *
	 * @param $group
	 * @param $engels
	 *
	 * @return array
	 */
	public function get_group( $group, $engels ) {
		$this->db->select( 'email' );
		$this->db->from( 'gebruikers' );
		switch( $group ) {
			case "iedereen":
				break;
			case "waterpoloscompetitie":
				$this->db->where( 'lidmaatschap', 'waterpolo_competitie' );
				break;
			case "waterpolo":
				$this->db->where_in( 'lidmaatschap', [ 'waterpolo_competitie', 'waterpolo_recreatief' ] );
				break;
			case "waterpolosrecreatief":
				$this->db->where( 'lidmaatschap', 'waterpolo_recreatief' );
				break;
			case "nieuwsbrief":
				$this->db->where( 'nieuwsbrief', TRUE );
				break;
			case "zwemmers":
				$this->db->where( 'lidmaatschap', 'zwemmer' );
				break;
			case "trainers":
				$this->db->where( 'lidmaatschap', 'trainer' );
				break;
			case "select":
				$this->db->reset_query();

				return [];
			default:
				$this->db->where( 'rank', 2 );
		}
		$this->db->where( 'engels', $engels );
		$query = $this->db->get();

		return $query->result();
	}

	/**
	 * Get emails belonging to an array of ids
	 *
	 * @param $ids
	 * @param $engels
	 *
	 * @return mixed
	 */
	public function get_emails( $ids, $engels ) {
		$this->db->select( 'email' );
		$this->db->from( 'gebruikers' );
		$this->db->where_in( 'id', $ids );
		$this->db->where( 'engels', $engels );
		$query = $this->db->get();

		return $query->result();
	}

	/**
	 * Save a mail for 'cannot read'
	 *
	 * @param $data
	 *
	 * @return bool
	 */
	public function save_mail( $data ) {
		$this->db->set( $data );
		$this->db->insert( 'mail' );

		return ( $this->db->affected_rows() > 0 );
	}

	/**
	 * Get a mail to view
	 *
	 * @param null $hash
	 *
	 * @return mixed
	 */
	public function get_mail( $hash = NULL, $limit = 10 ) {
		if( $hash === NULL ) {
			$this->db->limit( $limit );
			$this->db->order_by( 'datum', 'desc' );
			$query = $this->db->get( 'mail' );

			return $query->result();
		}
		$query = $this->db->get_where( 'mail', [ 'hash' => $hash ] );

		return $query->result();
	}

	/**
	 * Delete a mail from the database
	 *
	 * @param $hash
	 *
	 * @return bool
	 */
	public function delete( $hash ) {
		$this->db->where( 'hash', $hash );
		$this->db->delete( 'mail' );

		return ( $this->db->affected_rows() > 0 );
	}

	/**
	 * Gets a list of vrienden_van_hydrofiel that automatically need to get the nieuwsbrief
	 * @return mixed
	 */
	public function get_vrienden() {
		$query = $this->db->get( 'vrienden_van' );

		return $query->row();
	}

	/**
	 * Set a list of vrienden_van_hydrofiel
	 *
	 * @param $vrienden
	 *
	 * @return bool
	 */
	public function set_vrienden( $vrienden ) {
		$this->db->truncate( 'vrienden_van' );
		$this->db->set( 'vrienden_van', $vrienden );
		$this->db->insert( 'vrienden_van' );

		return ( $this->db->affected_rows() > 0 );
	}
}