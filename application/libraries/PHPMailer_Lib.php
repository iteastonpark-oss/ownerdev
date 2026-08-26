<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PHPMailer_Lib
{
	// Zoho (bukan Niagahoster) -- port SMTP Niagahoster (25/465/587) diblokir
	// provider hosting, sudah dikonfirmasi tim teknis & dibuka utk Zoho saja
	// (2026-08-26). Kredensial sama seperti yang sudah dipakai live di
	// bmsdev/application/controllers/pesan/Email.php sejak April 2026.
	var $user_email = 'billing@eprjatinangor.com';
	var $pass = 'Mailepr2022@billing';

	public function __construct()
	{
		log_message('Debug', 'PHPMailer class is loaded.');
	}

	public function load()
	{
		// Include PHPMailer library files
		require_once APPPATH . 'third_party/PHPMailer/src/Exception.php';
		require_once APPPATH . 'third_party/PHPMailer/src/PHPMailer.php';
		require_once APPPATH . 'third_party/PHPMailer/src/SMTP.php';

		$mail = new PHPMailer;
		return $mail;
	}

	public function email()
	{
		return $this->user_email;
	}

	public function password()
	{
		return $this->pass;
	}
}
