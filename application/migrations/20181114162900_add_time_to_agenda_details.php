<?php

class Migration_Add_time_to_agenda_details extends CI_Migration {
	public function up() {
		// Change the date fields to datetime
		$fields = [
			'van'               => [
				'type' => 'datetime',
			],
			'tot'               => [
				'type' => 'datetime',
			],
			'inschrijfdeadline' => [
				'type' => 'datetime',
			],
			'afmelddeadline'    => [
				'type' => 'datetime',
			],
		];
		$this->dbforge->modify_column( 'agenda', $fields );
	}

	public function down() {
		// Change the datetime fields to date
		$fields = [
			'van'               => [
				'type' => 'date',
			],
			'tot'               => [
				'type' => 'date',
			],
			'inschrijfdeadline' => [
				'type' => 'date',
			],
			'afmelddeadline'    => [
				'type' => 'date',
			],
		];
		$this->dbforge->modify_column( 'agenda', $fields );
	}
}