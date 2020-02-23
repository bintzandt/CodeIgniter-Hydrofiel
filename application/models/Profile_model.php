<?php
require_once 'application/entities/User.php';

/**
 * Class Profile_model
 * Handles all database action related to the profile
 */
class Profile_model extends CI_Model {
	private $fields;
	private const TABLE = 'gebruikers';

	// These constants reflect the order in the CSV which can be uploaded. Changes in the CSV should also be reflected here.
	// In addition: when new data is added to the CSV (international etc.) please add a constant here.
	// Note: The board has changed something but I cannot fix it since I did not get a new CSV file.
	const ID = 0;
	const VOOR = 1;
	const TUSSEN = 2;
	const ACHTER = 3;
	const GEBOORTEDATUM = 8;
	const EMAIL = 11;
	const ENGELS = 12;
	const LIDMAATSCHAP = 14;

	public function __construct() {
		parent::__construct();
		$this->fields = $this->db->list_fields( self::TABLE );
	}

	/**
	 * Get a profile in array form instead of std_object
	 *
	 * @param $id
	 *
	 * @return mixed
	 */
	public function get_profile_array( $id ) {
		$query = $this->db->get_where( self::TABLE, [ 'id' => $id ] );

		return $query->row_array();
	}

	public function get_user_by_email( string $email ): ?User {
		$this->db->limit( 1 );
		$query = $this->db->get_where( self::TABLE, [ 'email' => $email ] );

		return $query->first_row( 'User' );
	}

	/**
	 * Update a certain profile
	 *
	 * @param $id
	 * @param $data
	 *
	 * @return mixed
	 */
	public function update( int $id, $data ): int {
		$this->db->where( 'id', $id );
		$this->db->update( self::TABLE, $data );
		$this->db->cache_delete( 'profile' );

		return $this->db->affected_rows();
	}

	/**
	 * Function for adding/updating users to the database from a CSV
	 *
	 * @param $file_name
	 *
	 * @return int
	 */
	public function upload_users( $file_name ) {
		$file = fopen( $file_name, 'r' );

		//After the while loop, ids contains all ids of users that are still member.
		$ids = [];

		$nr = 0;

		//While not end of file
		while ( ! feof( $file ) ) {
			$data = fgetcsv( $file, 0, ';' );
			if ( $data === false ) {
				break;
			}

			$data = $this->clean_data( $data );

			//Create an User object with all relevant data
			$user = new User();
			$user->id = $data[ self::ID ];
			$user->set_name( $data[ self::VOOR ], $data[ self::TUSSEN ], $data[ self::ACHTER ] );
			$user->email = $data[ self::EMAIL ];
			$user->geboortedatum = $data[ self::GEBOORTEDATUM ];
			$user->engels = $data[ self::ENGELS ] !== 'Nee';
			$user->lidmaatschap = $data[ self::LIDMAATSCHAP ];

			//Push the id to the array ids
			array_push( $ids, $user->id );

			//Check if this is an existing user
			$profile = $this->get_profile( $user->id );
			if ( ! empty( $profile ) ) {
				$nr += $this->update( $user->id, $user );
			}
			else {
				try {
					$this->create_new_user( $user, $data[ self::VOOR ] );
				} catch ( Exception $e ){
					if ( ( $key = array_search( $user->id, $ids ) ) !== false ) {
						unset( $ids[ $key ] );
					}
				}
			}
		}
		fclose( $file );
		//Remove the file
		unlink( $file_name );

		//Remove all users not in ids as they are no longer member
		$this->db->where_not_in( 'id', $ids );
		$this->db->delete( self::TABLE );

		return $nr + $this->db->affected_rows();
	}

	/**
	 * Get the profile for a certain ID
	 * If no ID is specified all profiles will be returned
	 *
	 * @param int $id
	 *
	 * @return mixed
	 */
	public function get_profile( $id = 0 ) {
		if ( $id === 0 ) {
			$this->db->order_by( 'naam', 'asc' );
			/**
			 * @var CI_DB_result $query
			 */
			$query = $this->db->get( self::TABLE );

			return $query->result();
		}
		/**
		 * @var CI_DB_result $query
		 */
		$this->db->limit( 1 );
		$query = $this->db->get_where( self::TABLE, [ 'id' => $id ] );

		return $query->first_row( 'User' );
	}

	/**
	 * Function to send a welcome mail to new users
	 *
	 * @param $voornaam
	 * @param $email
	 * @param $recovery
	 *
	 * @return mixed
	 */
	private function send_welcome_mail( $voornaam, $email, $recovery, $engels ) {
		$this->load->model( 'agenda_model' );
		$data = [
			'recovery' => $recovery,
			'events'   => $this->agenda_model->get_event( null, 3 ),
			'voornaam' => $voornaam,
		];
		$this->email->clear( true );
		$this->email->to( $email );
		$this->email->from( 'bestuur@hydrofiel.nl', 'Bestuur N.S.Z.&W.V. Hydrofiel' );
		if ( $engels ) {
			$this->email->subject( "Welcome to Hydrofiel! 🏊🤽" );
			$this->email->message( $this->load->view( 'mail/welcome', $data, true ) );
			$this->email->attach( './application/views/mail/Welcomeletter_2019-2020_EN.pdf' );
		}
		else {
			$this->email->subject( "Welkom bij Hydrofiel! 🏊🤽‍" );
			$this->email->message( $this->load->view( 'mail/welkom', $data, true ) );
			$this->email->attach( './application/views/mail/Welkomstbrief_2019-2020_NL.pdf' );
		}

		return $this->email->send();
	}

	/**
	 * Delete a certain id from the databse
	 *
	 * @param $id
	 *
	 * @return bool
	 */
	public function delete( $id ) {
		$this->db->where( 'id', $id );
		$this->db->delete( self::TABLE );

		return $this->db->affected_rows() > 0;
	}

	/**
	 * Function to get upcoming birthdays.
	 *
	 * @param int $limit
	 *
	 * @return mixed
	 */
	public function get_verjaardagen( $limit = 3 ) {
		$query  = $this->db->query(
			"   SELECT id,naam, DATE_FORMAT(geboortedatum, '%d-%m-%Y') as geboortedatum, DATE_FORMAT(geboortedatum, '%Y') as geboortejaar
                FROM gebruikers 
                WHERE DATE_FORMAT(geboortedatum, '%m%d') >= DATE_FORMAT(now(), '%m%d')
                ORDER BY DATE_FORMAT(geboortedatum, '%m%d') ASC
                LIMIT $limit
            "
		);
		$result = $query->result();

		return $result;
	}

	/**
	 * @param User   $user
	 * @param string $first_name
	 *
	 * @return mixed
	 * @throws Exception
	 */
	private function create_new_user( User $user, string $first_name ){
		$this->load->model( 'login_model' );
		$this->db->insert( self::TABLE, $user );
		$result = $this->login_model->set_recovery( $user->email, true );
		//Send a welcome mail to new users :D
		if ( $result !== false ) {
			$this->send_welcome_mail( $first_name, $user->email, $result['recovery'], $user->engels );
			return $this->db->affected_rows();
		}

		throw new Exception();
	}

	/**
	 * Removes all additional ' from the data array.
	 *
	 * @param string[] $data An array of strings that will be cleaned.
	 *
	 * @return string[] An array with cleaned data.
	 */
	private function clean_data( Array $data ): Array {
		foreach ( $data as $key => $value ) {
			$val          = str_replace( '\'', '', $value );
			$data[ $key ] = $val;
		}
		return $data;
	}
}
