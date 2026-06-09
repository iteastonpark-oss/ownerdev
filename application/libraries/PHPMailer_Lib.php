<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PHPMailer_Lib
{
	var $user_email = 'billing@eprjatinangor.com';
	var $pass = 'eprj@2021';

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
