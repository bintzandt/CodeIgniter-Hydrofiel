<?php

if ( ! function_exists('must_be_logged_in') ){
	function must_be_logged_in(){
		$CI = get_instance();
		$CI->load->library( 'session' );

		if ( ! is_logged_in() ){
			$CI->session->set_userdata('redirect', uri_string());
			redirect('/inloggen');
		}
	}
}

if ( ! function_exists('must_be_admin') ){
	function must_be_admin(){
		$CI = get_instance();
		$CI->load->library( 'session' );

		if ( ! $CI->session->superuser ){
			show_error("Je hebt geen toegang tot het beheer gedeelte!");
		}
	}
}

if ( ! function_exists('is_logged_in') ){
	function is_logged_in(){
		$CI = get_instance();
		$CI->load->library( 'session' );
		return $CI->session->logged_in;
	}
}

if ( ! function_exists('is_english') ){
	function is_english(){
		$CI = get_instance();
		$CI->load->library( 'session' );
		return $CI->session->engels;
	}
}

if ( ! function_exists('is_admin') ){
	function is_admin(){
		$CI = get_instance();
		$CI->load->library( 'session' );
		return $CI->session->superuser;
	}
}

if ( ! function_exists('is_requested_user') ){
	function is_requested_user( int $requested_id ){
		$CI = get_instance();
		$CI->load->library( 'session' );
		return $CI->session->id === $requested_id;
	}
}

if ( ! function_exists('is_admin_or_requested_user') ){
	function is_admin_or_requested_user( int $requested_id ){
		return is_admin() || is_requested_user( $requested_id );
	}
}
