<?php

class Post_model_test extends TestCase {
	private Post_model $obj;

	public function setUp(): void {
		$this->resetInstance();
		$this->CI->load->model( 'post_model' );
		$this->obj = $this->CI->post_model;
	}

	public static function setUpBeforeClass(): void {
		parent::setUpBeforeClass();

		$CI =& get_instance();
		$CI->load->library( 'Seeder' );
		$CI->seeder->call( 'PostSeeder' );
	}

	public function test_get_posts() {
		$expected = [
			(object) [
				'post_id'        => '2',
				'post_title_nl'  => 'Welkom bij123 Hydrofiel',
				'post_title_en'  => 'Welcome to123 Hydrofiel',
				'post_text_nl'   => 'Je bent gewe123ldig!',
				'post_text_en'   => 'You are awesom123e!',
				'post_image'     => '',
				'post_timestamp' => '2020-03-01 10:00:00',
			],
			(object) [
				'post_id'        => '1',
				'post_title_nl'  => 'Welkom bij Hydrofiel',
				'post_title_en'  => 'Welcome to Hydrofiel',
				'post_text_nl'   => 'Je bent geweldig!',
				'post_text_en'   => 'You are awesome!',
				'post_image'     => '',
				'post_timestamp' => '2020-03-01 10:00:00',
			],
		];
		$result   = $this->obj->get_posts();
		$this->assertEquals( $expected, $result );
	}

	public function test_add_existing_post(){
		$existing_post = [
			'post_id'        => '1',
			'post_title_nl'  => 'Huh this changed',
			'post_title_en'  => 'Wow',
			'post_text_nl'   => 'Awesome',
			'post_text_en'   => 'xd',
			'post_image'     => '',
			'post_timestamp' => '2020-03-01 11:00:00',
		];
		$this->expectException( DatabaseError::class );
		$this->obj->add_post( $existing_post );
	}

	public function test_add_invalid_post(){
		$invalid_post = [
			'post_id'        => '3',
			'post_title_nl'  => 7,
			'post_title_en'  => 'Wow',
			'post_text_nl'   => 'Awesome',
			'post_text_en'   => 'xd',
			'post_image'     => null,
			'post_timestamp' => '2020-03-01 11:00:00',
		];
		$this->expectException( DatabaseError::class );
		$this->obj->add_post( $invalid_post );
	}

	public function test_add_new_post(){
		$new_post = [
			'post_id'        => '3',
			'post_title_nl'  => 'New',
			'post_title_en'  => 'Wow',
			'post_text_nl'   => 'Awesome',
			'post_text_en'   => 'xd',
			'post_image'     => '',
			'post_timestamp' => '2020-03-01 11:00:00',
		];

		$this->assertEquals( 1, $this->obj->add_post( $new_post ), 'should add a new post' );
	}

	public function test_delete_existing_post(){
		$this->assertTrue( $this->obj->delete_post( 1 ) === 1 );
	}

	public function test_deleting_non_existing_post(){
		$this->assertFalse( $this->obj->delete_post( 37 ) === 1 );
	}
}
