<?php

class Migration_Add_cancel_deadline extends CI_Migration {
	public function up() {
		$fields = [
			'afmelddeadline' => [
				'type'  => 'DATE',
				'after' => 'inschrijfdeadline',
				'null'  => TRUE,
			],
		];
		$this->dbforge->add_column( 'agenda', $fields );
	}

	public function down() {
		$this->dbforge->drop_column( 'agenda', 'afmelddeadline' );
	}
}