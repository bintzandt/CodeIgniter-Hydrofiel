<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$config['useragent'] = 'CodeIgniter';

$config['protocol'] = 'smtp';
$config['smtp_host'] = 'mail';
$config['smtp_port'] = '1025';
$config['smtp_timeout'] = 5;

$config['wordwrap'] = true;
$config['wrapchars'] = 76;
$config['mailtype'] = 'html';
$config['charset'] = 'utf-8';
$config['validate'] = true;
$config['priority'] = 3;
$config['crlf'] = "\r\n";
$config['newline'] = "\r\n";
$config['bcc_batch_mode'] = true;
$config['bcc_batch_size'] = 100;
