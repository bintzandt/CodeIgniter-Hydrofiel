<?php

class Profile_model_test extends TestCase {
	const NEW_USER = './new_user.csv';
	const UPDATE_USER = './update_user.csv';
	const INVALID_USER = './invalid_user.csv';
	
	private Profile_model $obj;

	public function setUp(): void {
		$this->resetInstance();
		$this->CI->load->model( 'profile_model' );
		$this->obj = $this->CI->profile_model;
	}

	public static function setUpBeforeClass(): void {
		parent::setUpBeforeClass();

		$CI =& get_instance();
		$CI->load->library( 'Seeder' );
		$CI->seeder->call( 'ProfileSeeder' );
	}

	public static function tearDownAfterClass(): void {
		parent::tearDownAfterClass();

		unlink( Profile_model_test::INVALID_USER );
	}

	public function test_get_profile_array() {
		$expected = [
			'id'    => '1',
			'naam'  => 'Admin user',
			'email' => 'admin@hydrofiel.nl',
			'rank'  => '1',
		];
		$result   = $this->obj->get_profile_array( 1 );

		// We should be able to fetch the correct data from the DB.
		foreach ( $expected as $k => $v ) {
			$this->assertEquals( $expected[ $k ], $result[ $k ], $k . ' should match the data in the seed.' );
		}

		// The function returns null for non-existing users.
		$result = $this->obj->get_profile_array( - 1 );
		$this->assertNull( $result );
	}

	public function test_get_user_by_email() {
		// Should return null for non-existing values.
		$this->assertNull( $this->obj->get_user_by_email( - 1 ) );
		$this->assertNull( $this->obj->get_user_by_email( true ) );
		$this->assertNull( $this->obj->get_user_by_email( 'random' ) );

		// Should not match on % in the string.
		$this->assertNull( $this->obj->get_user_by_email( 'admin%' ) );

		// Should fetch the user associated with an email-address.
		$expected = [
			'id'    => '1',
			'naam'  => 'Admin user',
			'email' => 'admin@hydrofiel.nl',
			'rank'  => '1',
		];
		$result   = $this->obj->get_user_by_email( 'admin@hydrofiel.nl' );
		$this->assertInstanceOf( 'User', $result );
		$this->assertNotEmpty( $result );
		$this->assertEquals( $expected['id'], $result->id );
	}

	public function test_delete() {
		$this->assertFalse( $this->obj->delete( - 1 ), 'should return false on non-existing users' );
		$this->assertFalse( $this->obj->delete(  '-34' ), 'should return false on non-existing users' );

		$this->assertTrue( $this->obj->delete( 3 ), 'should delete existing user');
		$this->assertFalse( $this->obj->delete( 3 ), 'user should be deleted');
	}

	public function test_update() {
		// Should update nothing for non-existing users.
		$this->assertEquals( 0, $this->obj->update( - 1, [ 'naam' => 'henk' ] ) );
		$this->assertEquals( 0, $this->obj->update( 12345678, [ 'naam' => 'henk' ] ) );

		// Should update entries for existing columns.
		$this->assertEquals( 1, $this->obj->update( 1, [ 'naam' => 'Super Admin User' ] ), 'Should update entries for existing columns' );
		$this->assertEquals( 1, $this->obj->update( 1, [
			'naam' => 'Super Adje User',
			'rank' => 2,
		] ), 'Should update entries for existing columns' );
	}

	/**
	 * This function should be run at the end since it will remove all users from the DB.
	 */
	public function test_upload_users() {
		/**
		 * Create two files for testing:
		 *      new_user.csv    -> A file that contains a single new user.
		 *      update_user.csv -> A file that contains an update to an existing user.
		 *      invalid.csv     -> A file that contains invalid data.
		 * These files are provided to the upload_users() function to check the results.
		 */
		$new_user_file = fopen( Profile_model_test::NEW_USER, 'wb');
		fwrite( $new_user_file, '7;Test;;Gebruiker;;;;;01-01-1999;;;webmaster@hydrofiel.nl;Nee;;zwemmer' );
		fclose( $new_user_file );

		$update_user_file = fopen( Profile_model_test::UPDATE_USER, 'wb');
		fwrite( $update_user_file, '7;Test;;Blabla;;;;;2000-01-01;;;webmaster@hydrofiel.nl;Nee;;zwemmer' );
		fclose( $update_user_file );

		$invalid_file = fopen( Profile_model_test::INVALID_USER, 'wb');
		fwrite( $invalid_file, '-1;;dfs;Blabla;;asdad;;01-01-2000;;;webmaster@hydrofiel.nl;Nee;;zwemmer' );
		fclose( $invalid_file );

		$this->assertEquals( 2, $this->obj->upload_users( 'new_user.csv' ) );
		/**
		 * @var User $new_user
		 */
		$new_user = $this->obj->get_profile( 7 );

		$this->assertEquals( 'Test Gebruiker', $new_user->naam );
		$this->assertEquals( 6, $new_user->rank );
		$this->assertEquals( '01-01-1999', $new_user->geboortedatum );
		$this->assertNotNull( $new_user->recovery );

		$this->assertEquals( 1, $this->obj->upload_users( 'update_user.csv' ) );
		$updated_user = $this->obj->get_profile( 7 );
		$this->assertEquals( 'Test Blabla', $updated_user->naam );
		$this->assertEquals( '01-01-2000', $updated_user->geboortedatum );
	}
}
