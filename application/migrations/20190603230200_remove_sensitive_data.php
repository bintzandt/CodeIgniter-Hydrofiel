<?php

class Migration_Remove_sensitive_data extends CI_Migration {
	public function up() {
		$fields = [
			'adres',
			'postcode',
			'plaats',
			'mobielnummer',
			'geslacht',
			'zichtbaar_adres',
			'zichtbaar_telefoonnummer',
		];

		foreach( $fields as $field ){
			$this->dbforge->drop_column( 'gebruikers', $field );
		}
	}

	public function down() {
		$fields = [
			'adres'                    => [
				'type'       => 'VARCHAR',
				'constraint' => 80,
				'null'       => FALSE,
			],
			'postcode'                 => [
				'type'       => 'VARCHAR',
				'constraint' => 6,
				'null'       => FALSE,
			],
			'plaats'                   => [
				'type'       => 'VARCHAR',
				'constraint' => 80,
				'null'       => FALSE,
			],
			'mobielnummer'             => [
				'type'       => 'VARCHAR',
				'constraint' => 15,
				'null'       => FALSE,
			],
			'geslacht'                 => [
				'type' => 'enum("man", "vrouw")',
				'null' => FALSE,
			],
			'zichtbaar_adres'          => [
				'type'       => 'TINYINT',
				'constraint' => 1,
				'null'       => FALSE,
				'default'    => 0,
			],
			'zichtbaar_telefoonnummer' => [
				'type'       => 'TINYINT',
				'constraint' => 1,
				'null'       => FALSE,
				'default'    => 0,
			],
		];

		$this->dbforge->add_column( 'gebruikers', $fields );
	}
}