<?php

if ( ! function_exists('load_language_file') ){
	function load_language_file( $filename ){
		$CI = get_instance();
		$CI->load->library( 'session' );

		if ( $CI->session->engels ){
			$CI->lang->load( $filename, 'english' );
		} else {
			$CI->lang->load( $filename );
		}
	}
}
