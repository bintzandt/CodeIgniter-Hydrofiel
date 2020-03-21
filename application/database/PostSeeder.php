<?php

class PostSeeder extends Seeder {
	private $table = 'posts';

	public function run() {
		$this->db->truncate( $this->table );

		$post1 = [
			'post_id'        => 1,
			'post_title_nl'  => 'Welkom bij Hydrofiel',
			'post_title_en'  => 'Welcome to Hydrofiel',
			'post_text_nl'   => 'Je bent geweldig!',
			'post_text_en'   => 'You are awesome!',
			'post_image'     => '',
			'post_timestamp' => '2020-03-01 10:00:00',
		];
		$post2 = [
			'post_id'        => 2,
			'post_title_nl'  => 'Welkom bij123 Hydrofiel',
			'post_title_en'  => 'Welcome to123 Hydrofiel',
			'post_text_nl'   => 'Je bent gewe123ldig!',
			'post_text_en'   => 'You are awesom123e!',
			'post_image'     => '',
			'post_timestamp' => '2020-03-01 10:00:00',
		];

		$this->db->insert( $this->table, $post1 );
		$this->db->insert( $this->table, $post2 );
	}
}
