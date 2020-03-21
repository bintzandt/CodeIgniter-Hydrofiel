<?php

class ProfileSeeder extends Seeder {
	private $table = 'gebruikers';

	public function run(){
		$this->db->truncate( $this->table );

		$admin_user = new User();
		$admin_user->naam = 'Admin user';
		$admin_user->id = 1;
		$admin_user->wachtwoord = 'admin';
		$admin_user->email = 'admin@hydrofiel.nl';
		$admin_user->rank = 1;
		$admin_user->geboortedatum = '1999-01-01';

		$board_user = new User();
		$board_user->naam = 'Board user';
		$board_user->id = 2;
		$board_user->wachtwoord = 'board';
		$board_user->email = 'board@hydrofiel.nl';
		$board_user->rank = 2;
		$board_user->geboortedatum = '1999-01-01';

		$normal_user = new User();
		$normal_user->naam = 'Normal user';
		$normal_user->id = 3;
		$normal_user->wachtwoord = 'normal';
		$normal_user->email = 'normal@hydrofiel.nl';
		$normal_user->rank = 6;
		$normal_user->geboortedatum = '1999-01-01';

		$this->db->insert( $this->table, $admin_user );
		$this->db->insert( $this->table, $board_user );
		$this->db->insert( $this->table, $normal_user );
	}
}
