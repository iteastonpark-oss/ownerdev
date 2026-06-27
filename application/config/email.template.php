<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	'protocol' => 'smtp', // 'mail', 'sendmail', or 'smtp'
	'smtp_host' => 'smtp.example.com',
	'smtp_port' => 465,
	'smtp_user' => 'dev2kangs@gmail.com',
	'smtp_pass' => 'developer2kangs',
	'smtp_crypto' => 'ssl', //can be 'ssl' or 'tls' for example
	'mailtype' => 'html', //plaintext 'text' mails or 'html'
	'smtp_timeout' => '4', //in seconds
	'charset' => 'iso-8859-1',
	'wordwrap' => TRUE
);
