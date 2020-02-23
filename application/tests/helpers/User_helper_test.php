<?php

class User_helper_test extends TestCase {
	public function setUp(): void {
		$this->resetInstance();
		$this->CI->load->helper( 'user_helper' );
	}

	public function test_is_admin(){
		$this->assertEquals( false, is_admin(), 'is_admin() should return false by default' );

		$this->CI->session->superuser = true;

		$this->assertEquals( true, is_admin(), 'is_admin() should return true when the user is admin' );
	}

	public function test_must_be_logged_in(){
		try {
			must_be_logged_in();
		} catch ( CIPHPUnitTestRedirectException $e ){
			$this->assertEquals( 'Redirect to http://hydrofiel.test/inloggen', $e->getMessage() );
		}

		$this->CI->session->logged_in = true;
		$this->assertEmpty( must_be_logged_in() );
	}

	public function test_is_requested_user(){
		$this->CI->session->id = 3;
		$this->assertEquals( false, is_requested_user( 5 ) );
		$this->assertEquals( false, is_requested_user( -1 ) );
		$this->assertEquals( true, is_requested_user( 3 ) );
	}

	public function test_is_admin_or_requested_user(){
		$this->CI->session->id = 5;
		$this->CI->session->superuser = false;
		$this->assertEquals( false, is_admin_or_requested_user( 3 ) );
		$this->assertEquals( true, is_admin_or_requested_user( 5 ) );

		$this->CI->session->superuser = true;
		$this->assertEquals( true, is_admin_or_requested_user( 3 ) );
		$this->assertEquals( true, is_admin_or_requested_user( 5 ) );
	}
}
