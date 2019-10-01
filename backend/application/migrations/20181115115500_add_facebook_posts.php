<?php

class Migration_Add_facebook_posts extends CI_Migration {
	public function up() {
		$fields = [
			'id'      => [
				'type'       => 'VARCHAR',
				'constraint' => '175',
				'null'       => FALSE,
			],
			'text'    => [
				'type' => 'TEXT',
				'null' => FALSE,
			],
			'image'   => [
				'type'       => 'TEXT',
				'constraint' => '255',
				'null'       => FALSE,
			],
			'url'     => [
				'type'       => 'VARCHAR',
				'constraint' => '255',
				'null'       => FALSE,
			],
			'created' => [
				'type' => 'DATETIME',
				'null' => FALSE,
			],
		];
		$this->dbforge->add_field( $fields );
		$this->dbforge->add_key( 'id', TRUE );
		$this->dbforge->create_table( 'facebook_posts' );
	}

	public function down() {
		$this->dbforge->drop_table( 'facebook_posts' );
	}
}