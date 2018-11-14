<?php
class Migration_Add_time_to_agenda_details extends CI_Migration {
	public function up(){
		// Change the date fields to datetime
		$fields = array(
			'van' => array(
				'type' => 'datetime'
			),
			'tot' => array(
				'type' => 'datetime'
			),
			'inschrijfdeadline' => array(
				'type' => 'datetime'
			),
			'afmelddeadline' => array(
				'type' => 'datetime'
			),
		);
		$this->dbforge->modify_column('agenda', $fields);
	}

	public function down(){
		// Change the datetime fields to date
		$fields = array(
			'van' => array(
				'type' => 'date'
			),
			'tot' => array(
				'type' => 'date'
			),
			'inschrijfdeadline' => array(
				'type' => 'date'
			),
			'afmelddeadline' => array(
				'type' => 'date'
			),
		);
		$this->dbforge->modify_column('agenda', $fields);
	}
}