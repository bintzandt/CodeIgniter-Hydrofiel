<?php

/**
 * This object contains all the configuration for the form validation.
 *
 * We are using the "magic" method. Which means that the array indices will automatically map to the function where they are used.
 *
 * See https://codeigniter.com/userguide3/libraries/form_validation.html#associating-a-controller-method-with-a-rule-group for more details.
 */
$config = [
	// Custom error delimiters.
	'error_prefix' => '<div class="alert alert-danger"><strong>',
	'error_suffix' => '</strong></div>',

	'inloggen/index' => [
		[
			'field' => 'email',
			'label' => 'E-mailadres',
			'rules' => 'required|valid_email',
		],
		[
			'field' => 'wachtwoord',
			'label' => 'Wachtwoord',
			'rules' => 'required',
		],
	],
	'inloggen/forgot_password' => [
		[
			'field' => 'email',
			'label' => 'E-mailadres',
			'rules' => 'valid_email|required',
		],
	],
	'inloggen/reset' => [
		[
			'field' => 'wachtwoord1',
			'label' => 'Wachtwoord',
			'rules' => 'required',
		],
		[
			'field' => 'wachtwoord2',
			'label' => 'WachtwoordBevestiging',
			'rules' => 'required|matches[wachtwoord1]',
		],
	],
];
