<?php

class Page_model_test extends TestCase {
	private Page_model $obj;

	public function setUp(): void {
		$this->resetInstance();
		$this->CI->load->model( 'page_model' );
		$this->obj = $this->CI->page_model;
	}

	public static function setUpBeforeClass(): void {
		parent::setUpBeforeClass();

		$CI =& get_instance();
		$CI->load->library( 'Seeder' );
		$CI->seeder->call( 'PageSeeder' );
	}

	public function test_view(){
		$page1 = $this->obj->view( 1 );
		$this->assertInstanceOf( PageObject::class, $page1, 'should be an instance of PageObject' );
		$this->assertEquals( 'Page test 1 NL', $page1->naam );

		$non_existing = $this->obj->view( 3 );
		$this->assertNull( $non_existing, 'should return null on non-existing pages' );

		$this->expectException( TypeError::class );
		$this->obj->view( 'asd' );
	}
}
