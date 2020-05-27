<?php
class Migration_Add_training_to_soort_enum extends CI_Migration {
	public function up() {
		$field = [
			'soort' => [
				'type' => 'enum("algemeen", "toernooi", "nszk", "training")',
			],
		];

		$this->dbforge->modify_column( 'agenda', $field );
	}

	public function down() {
		$field = [
			'soort' => [
				'type' => 'enum("algemeen", "toernooi", "nszk" )',
			],
		];

		$this->dbforge->modify_column( 'agenda', $field );
	}
}
