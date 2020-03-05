<?php

/**
 * Class Menu_model
 * Handles all database actions related to the menu
 */
class Menu_model extends CI_Model {
	/**
	 * Get a list of the hoofdmenu
	 *
	 * @param bool $include_submenu whether to include all submenus
	 *
	 * @return array
	 */
	public function hoofdmenu( bool $include_submenu = true ): array {
		$this->db->select( 'id, naam, engelse_naam, ingelogd' );
		$this->db->from( 'pagina' );
		$this->db->where( 'submenu', 'A' );
		$this->db->where( 'bereikbaar', 'ja' );
		$this->db->order_by( 'plaats' );
		$query = $this->db->get();

		$hoofdmenu = $query->result_array();

		if ( $include_submenu ) {
			foreach ( $hoofdmenu as &$menu_item ) {
				$menu_item['submenu'] = $this->submenu( $menu_item['id'] );
			}
			unset( $menu_item );
		}

		return $hoofdmenu;
	}

	/**
	 * Get a submenu for a certain menu id
	 *
	 * @param $id
	 *
	 * @return null
	 */
	public function submenu( int $id ): array {
		$this->db->select( 'id, naam, engelse_naam, ingelogd' );
		$this->db->from( 'pagina' );
		$this->db->where( 'submenu', $id );
		$this->db->where( 'bereikbaar', 'ja' );
		$this->db->order_by( 'plaats' );
		$query   = $this->db->get();
		$submenu = $query->result_array();

		return $submenu;
	}
}
