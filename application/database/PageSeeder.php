<?php

class PageSeeder extends Seeder {
	private $table = 'pagina';

	public function run(){
		$this->db->truncate( $this->table );

		$page1 = [
			'id' => 1,
			'naam' => 'Page test 1 NL',
			'engelse_naam' => 'Page test 1 EN',
			'tekst' => 'Mooie tekst',
			'engels' => 'Beautiful text',
			'plaats' => 1,
			'submenu' => 'A',
			'zichtbaar' => 'ja',
			'bereikbaar' => 'ja',
			'cmspagina' => 'ja',
			'ingelogd' => 0,
		];

		$page2 = [
			'id' => 2,
			'naam' => 'Page test 2 NL',
			'engelse_naam' => 'Page test 2 EN',
			'tekst' => 'Mooie tekst',
			'engels' => 'Beautiful text',
			'plaats' => 1,
			'submenu' => 'A',
			'zichtbaar' => 'nee',
			'bereikbaar' => 'nee',
			'cmspagina' => 'nee',
			'ingelogd' => 1,
		];

		$this->db->insert_batch( $this->table, [ $page1, $page2 ] );
	}
}
