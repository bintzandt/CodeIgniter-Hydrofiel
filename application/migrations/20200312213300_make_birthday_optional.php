<?php
class Migration_Make_birthday_optional extends CI_Migration {
	public function up() {
		$field = [
			'geboortedatum' => [
				'type' => 'DATETIME',
				'null' => true,
			],
		];

		$this->dbforge->modify_column( 'gebruikers', $field );
	}

	public function down() {
		$field = [
			'geboortedatum' => [
				'type' => 'DATETIME',
				'null' => false,
			],
		];

		$this->dbforge->modify_column( 'gebruikers', $field );
	}
}
