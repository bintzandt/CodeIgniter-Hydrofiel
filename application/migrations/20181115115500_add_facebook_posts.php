<?php
class Migration_Add_facebook_posts extends CI_Migration {
	public function up(){
		$fields = array(
			'id' => array(
				'type' => 'VARCHAR',
				'constraint' => '175',
				'null' => false,
			),
			'text' => array(
				'type' => 'TEXT',
				'null' => false,
			),
			'image' => array(
				'type' => 'TEXT',
				'constraint' => '255',
				'null' => false,
			),
			'url' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
				'null' => false,
			),
			'created' => array(
				'type' => 'DATETIME',
				'null' => false,
			),
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('facebook_posts');
	}

	public function down(){
		$this->dbforge->drop_table('facebook_posts');
	}
}