<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Status Helper
 *
 * A simple helper that sets the status of a function in the flashdata of the session.
 */

if ( ! function_exists('error') ){
	function error( string $message ){
		$CI = get_instance();
		$CI->load->library( 'session' );

		$CI->session->set_flashdata( 'error', $message );
	}
}

if ( ! function_exists('success') ){
	function success( string $message ){
		$CI = get_instance();
		$CI->load->library( 'session' );

		$CI->session->set_flashdata( 'success', $message );
	}
}
