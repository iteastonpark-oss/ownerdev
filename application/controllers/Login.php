<?php

/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 4/15/2016
 * Time: 9:13 AM
 */
class Login extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Login_Model', 'login_model');
		$this->login_model = new Login_Model();
		$this->apl = new Apl();

		// Dibutuhkan oleh views/login/forgot_password.php (dropdown pilih unit).
		$this->load->model('Dropdown_Model', 'dropdown_model');
		$this->dropdown_model = new Dropdown_Model();
	}

	public function login() {}

	// Login berbasis password (sementara menggantikan OTP WA, lihat docs/90 —
	// gateway WA kena banned/filtering Meta, lihat memory wa-gateway-production-status).
	// Kode OTP di Auth.php (otp/link) & resend_otp() di bawah SENGAJA dibiarkan utuh
	// (dead code) supaya bisa cepat di-revert kalau OTP perlu diaktifkan lagi.
	const MAX_LOGIN_ATTEMPTS = 5;
	const LOCKOUT_MINUTES = 15;

	function login_act()
	{
		$hp = trim((string) $this->input->post('hp'));
		$id_bast = $this->input->post('id_bast');
		$password = (string) $this->input->post('password');

		// Kalau gagal, isi ulang cuma Password — hp+unit tetap ke-prefill di form
		// (dibaca sekali & dihapus oleh views/login/login.php, lihat old_hp/old_id_bast).
		$this->session->old_hp = $hp;
		$this->session->old_id_bast = $id_bast;

		if ($hp === '' || $id_bast === '' || $password === '') {
			$this->pesan->pesan_warning("Nomor WhatsApp, Unit, dan Password wajib diisi.");
			redirect(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : site_url(''));
			return;
		}

		if ($hp == '085780465303' || $hp == '085195140308' || $hp == '082129248953' || $hp == '081224341030' || $hp == '085862880940' || $hp == '082215866660' || $hp == '0881022246542' || $hp == '089690527866' || $hp == '089687038641') {
			$where = "hapus=0 AND id_bast='" . $id_bast . "'";
		} else {
			$where = "hapus=0 and wa_surat LIKE '%" . $hp . "%' AND id_bast='" . $id_bast . "'";
		}

		$cek = $this->apl->getSelectedData("bast", $where)->num_rows();
		if ($cek == 0) {
			$this->pesan->pesan_warning("Gagal Masuk Mohon Hubungi Bagian TR untuk pendaftaran Nomor Wa / Pengecekan Lebih Lanjut di Nomor : 0823-1212-2021");
			redirect($_SERVER['HTTP_REFERER']);
			return;
		}

		$data = $this->apl->getSelectedData("bast", $where)->row();
		$pemilik = $this->apl->getSelectedData("pemilik", array('id_pemilik' => $data->id_pemilik, 'hapus' => 0))->row();

		if (!$pemilik) {
			$this->pesan->pesan_warning("Gagal Masuk Mohon Hubungi Bagian TR untuk pendaftaran Nomor Wa / Pengecekan Lebih Lanjut di Nomor : 0823-1212-2021");
			redirect($_SERVER['HTTP_REFERER']);
			return;
		}

		if (!empty($pemilik->locked_until) && strtotime($pemilik->locked_until) > time()) {
			$this->pesan->pesan_warning("Akun sementara terkunci karena terlalu banyak percobaan login gagal. Coba lagi setelah " . date('H:i', strtotime($pemilik->locked_until)) . " atau hubungi Tenant Relation 0823-1212-2021.");
			redirect($_SERVER['HTTP_REFERER']);
			return;
		}

		if (empty($pemilik->password)) {
			$this->pesan->pesan_warning("Password belum diaktifkan untuk akun Anda. Mohon hubungi Tenant Relation di 0823-1212-2021 untuk aktivasi password.");
			redirect($_SERVER['HTTP_REFERER']);
			return;
		}

		if (!password_verify($password, $pemilik->password)) {
			$attempts = (int) $pemilik->failed_attempts + 1;
			$update = array('failed_attempts' => $attempts);
			if ($attempts >= self::MAX_LOGIN_ATTEMPTS) {
				$update['locked_until'] = date('Y-m-d H:i:s', strtotime('+' . self::LOCKOUT_MINUTES . ' minutes'));
			}
			$this->apl->updateData("pemilik", $update, array('id_pemilik' => $pemilik->id_pemilik));
			$this->apl->log("LOGIN_FAILED", json_encode(array('id_pemilik' => $pemilik->id_pemilik, 'hp' => $hp, 'attempt' => $attempts)), "");

			if (isset($update['locked_until'])) {
				$this->pesan->pesan_warning("Password salah. Akun dikunci sementara " . self::LOCKOUT_MINUTES . " menit karena terlalu banyak percobaan gagal.");
			} else {
				$this->pesan->pesan_warning("Nomor WhatsApp, Unit, atau Password salah.");
			}
			redirect($_SERVER['HTTP_REFERER']);
			return;
		}

		// Sukses login — hp/unit tidak perlu di-prefill lagi.
		$this->session->unset_userdata(array('old_hp', 'old_id_bast'));

		// Sukses — reset counter, rotate session id (cegah session fixation), set sesi login.
		$this->apl->updateData("pemilik", array('failed_attempts' => 0, 'locked_until' => null), array('id_pemilik' => $pemilik->id_pemilik));
		$this->session->sess_regenerate(FALSE);

		$data_session = array(
			'id_bast' => $data->id_bast,
			'id_pemilik' => $data->id_pemilik,
			'id_unit' => $data->id_unit,
			'hp' => $hp,
			'login' => '1',
			'tipe' => 'owner',
			'uid' => uniqid(),
			'id_admin' => 0,
			'username' => $pemilik->nama,
			'must_change_password' => (int) $pemilik->must_change_password,
		);
		$this->session->set_userdata($data_session);
		$this->apl->log("LOGIN", json_encode(array('id_pemilik' => $pemilik->id_pemilik, 'id_bast' => $data->id_bast)), "");

		if ((int) $pemilik->must_change_password === 1) {
			$this->pesan->pesan_success("Login berhasil. Mohon buat password baru terlebih dahulu.");
			redirect(site_url('login/ganti_password?force=1'));
			return;
		}

		$this->pesan->pesan_success("Login berhasil");
		$red = (isset($_SESSION['redirect'])) ? $_SESSION['redirect'] : '';
		redirect(site_url($red));
	}

	// Menu "Ubah Password" (self-service) sekaligus dipakai untuk paksa ganti
	// password default saat must_change_password=1 (lihat login_act()).
	function ganti_password()
	{
		if (!$this->session->login) {
			redirect(site_url(''));
			return;
		}

		$data['judul'] = 'Ubah Password';
		$data['page'] = 'login/ganti_password';
		$data['force'] = ((int) $this->session->must_change_password === 1);
		$this->load->view('home', $data);
	}

	function ganti_password_act()
	{
		if (!$this->session->login) {
			redirect(site_url(''));
			return;
		}

		$id_pemilik = $this->session->id_pemilik;
		$current_password = (string) $this->input->post('current_password');
		$new_password = (string) $this->input->post('new_password');
		$confirm_password = (string) $this->input->post('confirm_password');

		$pemilik = $this->apl->getSelectedData("pemilik", array('id_pemilik' => $id_pemilik))->row();
		$must_change = $pemilik && (int) $pemilik->must_change_password === 1;

		// Kalau bukan alur wajib-ganti (mis. dipanggil dari menu Ubah Password biasa),
		// password lama wajib diverifikasi dulu.
		if (!$must_change) {
			if ($current_password === '' || !$pemilik || !password_verify($current_password, (string) $pemilik->password)) {
				$this->pesan->pesan_warning("Password saat ini salah.");
				redirect(site_url('login/ganti_password'));
				return;
			}
		}

		if (strlen($new_password) < 8) {
			$this->pesan->pesan_warning("Password baru minimal 8 karakter.");
			redirect(site_url('login/ganti_password'));
			return;
		}
		if ($new_password !== $confirm_password) {
			$this->pesan->pesan_warning("Konfirmasi password baru tidak sama.");
			redirect(site_url('login/ganti_password'));
			return;
		}
		if ($current_password !== '' && $new_password === $current_password) {
			$this->pesan->pesan_warning("Password baru tidak boleh sama dengan password lama.");
			redirect(site_url('login/ganti_password'));
			return;
		}

		$this->apl->updateData("pemilik", array(
			'password' => password_hash($new_password, PASSWORD_BCRYPT),
			'must_change_password' => 0,
			'password_updated_at' => date('Y-m-d H:i:s'),
			'failed_attempts' => 0,
			'locked_until' => null,
		), array('id_pemilik' => $id_pemilik));

		$this->session->must_change_password = 0;
		$this->apl->log("GANTI_PASSWORD_OWNER", json_encode(array('id_pemilik' => $id_pemilik)), "");
		$this->pesan->pesan_success("Password berhasil diubah.");
		redirect(site_url(''));
	}

	// ==========================================================================
	// Lupa Password — via EMAIL (pemilik.email atau bast.email_surat, mana yang
	// terisi). Owner diidentifikasi pakai hp+unit (sama seperti login), supaya
	// tidak perlu owner "ingat" email apa yang didaftarkan. Kalau kedua kolom
	// email kosong, arahkan hubungi admin/TR (sesuai keputusan user 2026-08-26,
	// lihat docs/90 — cakupan email cuma ~51% unit vs WA ~98%).
	// ==========================================================================
	const RESET_TOKEN_MINUTES = 30;

	function forgot_password()
	{
		$data['judul'] = 'Lupa Password';
		$data['auth_page'] = 'login/forgot_password';
		$this->load->view('home', $data);
	}

	function forgot_password_act()
	{
		$hp = trim((string) $this->input->post('hp'));
		$id_bast = $this->input->post('id_bast');

		if ($hp === '' || $id_bast === '') {
			$this->pesan->pesan_warning("Nomor WhatsApp dan Unit wajib diisi.");
			redirect(site_url('login/forgot_password'));
			return;
		}

		if ($hp == '085780465303' || $hp == '085195140308' || $hp == '082129248953' || $hp == '081224341030' || $hp == '085862880940' || $hp == '082215866660' || $hp == '0881022246542' || $hp == '089690527866' || $hp == '089687038641') {
			$where = "hapus=0 AND id_bast='" . $id_bast . "'";
		} else {
			$where = "hapus=0 and wa_surat LIKE '%" . $hp . "%' AND id_bast='" . $id_bast . "'";
		}

		$bast = $this->apl->getSelectedData("bast", $where)->row();
		$genericMsg = "Kalau data yang Anda masukkan sesuai, link reset password sudah kami kirim ke email yang terdaftar.";

		if (!$bast) {
			// Pesan digeneralisir (tidak bocorkan apakah hp/unit valid) — beda dgn login_act
			// yg memang perlu spesifik krn user harus tahu kombinasi hp/unit-nya salah.
			$this->pesan->pesan_success($genericMsg);
			redirect(site_url(''));
			return;
		}

		$pemilik = $this->apl->getSelectedData("pemilik", array('id_pemilik' => $bast->id_pemilik, 'hapus' => 0))->row();
		if (!$pemilik) {
			$this->pesan->pesan_success($genericMsg);
			redirect(site_url(''));
			return;
		}

		$email = $this->resolve_owner_email($pemilik, $bast);
		if (!$email) {
			$this->pesan->pesan_warning("Email tidak ditemukan untuk akun ini. Silakan hubungi Admin BMS / Tenant Relation di 0823-1212-2021 atau info@eprjatinangor.com untuk reset password manual.");
			redirect(site_url('login/forgot_password'));
			return;
		}

		$token = bin2hex(random_bytes(32));
		$this->apl->updateData("pemilik", array(
			'reset_token' => $token,
			'reset_token_expires_at' => date('Y-m-d H:i:s', strtotime('+' . self::RESET_TOKEN_MINUTES . ' minutes')),
		), array('id_pemilik' => $pemilik->id_pemilik));

		$link = site_url('login/reset_password/' . $token);
		$sent = $this->send_reset_email($email, $pemilik->nama, $link);

		$this->apl->log("FORGOT_PASSWORD_OWNER", json_encode(array('id_pemilik' => $pemilik->id_pemilik, 'email_sent' => $sent)), "");

		if ($sent) {
			$this->pesan->pesan_success("Link reset password sudah dikirim ke email " . $this->mask_email($email) . ". Cek juga folder Spam/Junk.");
		} else {
			$this->pesan->pesan_warning("Gagal mengirim email reset password. Silakan coba lagi beberapa saat lagi atau hubungi Admin BMS.");
		}
		redirect(site_url(''));
	}

	function reset_password($token = '')
	{
		$pemilik = $token ? $this->apl->getSelectedData("pemilik", array(
			'reset_token' => $token,
			'hapus' => 0,
		))->row() : null;

		$valid = $pemilik && !empty($pemilik->reset_token_expires_at) && strtotime($pemilik->reset_token_expires_at) > time();

		if (!$valid) {
			$this->pesan->pesan_warning("Link reset password tidak valid atau sudah kadaluarsa. Silakan minta link baru.");
			redirect(site_url('login/forgot_password'));
			return;
		}

		$data['judul'] = 'Reset Password';
		$data['auth_page'] = 'login/reset_password';
		$data['token'] = $token;
		$this->load->view('home', $data);
	}

	function reset_password_act()
	{
		$token = (string) $this->input->post('token');
		$new_password = (string) $this->input->post('new_password');
		$confirm_password = (string) $this->input->post('confirm_password');

		$pemilik = $token ? $this->apl->getSelectedData("pemilik", array(
			'reset_token' => $token,
			'hapus' => 0,
		))->row() : null;
		$valid = $pemilik && !empty($pemilik->reset_token_expires_at) && strtotime($pemilik->reset_token_expires_at) > time();

		if (!$valid) {
			$this->pesan->pesan_warning("Link reset password tidak valid atau sudah kadaluarsa. Silakan minta link baru.");
			redirect(site_url('login/forgot_password'));
			return;
		}

		if (strlen($new_password) < 8) {
			$this->pesan->pesan_warning("Password baru minimal 8 karakter.");
			redirect(site_url('login/reset_password/' . $token));
			return;
		}
		if ($new_password !== $confirm_password) {
			$this->pesan->pesan_warning("Konfirmasi password baru tidak sama.");
			redirect(site_url('login/reset_password/' . $token));
			return;
		}

		$this->apl->updateData("pemilik", array(
			'password' => password_hash($new_password, PASSWORD_BCRYPT),
			'must_change_password' => 0,
			'password_updated_at' => date('Y-m-d H:i:s'),
			'failed_attempts' => 0,
			'locked_until' => null,
			'reset_token' => null,
			'reset_token_expires_at' => null,
		), array('id_pemilik' => $pemilik->id_pemilik));

		$this->apl->log("RESET_PASSWORD_OWNER_VIA_EMAIL", json_encode(array('id_pemilik' => $pemilik->id_pemilik)), "");
		$this->pesan->pesan_success("Password berhasil direset. Silakan login dengan password baru Anda.");
		redirect(site_url(''));
	}

	// pemilik.email diutamakan, fallback ke bast.email_surat (alamat surat unit) —
	// gabungan keduanya menaikkan cakupan owner yang bisa pakai reset via email
	// dari ~34% (pemilik.email saja) ke ~51% unit (lihat docs/90 #40).
	private function resolve_owner_email($pemilik, $bast)
	{
		if (!empty($pemilik->email) && filter_var($pemilik->email, FILTER_VALIDATE_EMAIL)) {
			return $pemilik->email;
		}
		if (!empty($bast->email_surat) && filter_var($bast->email_surat, FILTER_VALIDATE_EMAIL)) {
			return $bast->email_surat;
		}
		return null;
	}

	private function mask_email($email)
	{
		$parts = explode('@', $email);
		if (count($parts) !== 2) {
			return $email;
		}
		$name = $parts[0];
		$masked = strlen($name) <= 2 ? $name[0] . '*' : substr($name, 0, 2) . str_repeat('*', max(1, strlen($name) - 2));
		return $masked . '@' . $parts[1];
	}

	// SMTP sama seperti bmsdev/Send_email.php (akun billing@eprjatinangor.com via Niagahoster).
	private function send_reset_email($to, $nama, $link)
	{
		try {
			$this->load->library('PHPMailer_Lib');
			$mailer = new PHPMailer_Lib();
			$mail = $mailer->load();

			$mail->isSMTP();
			$mail->Host = 'srv125.niagahoster.com';
			$mail->SMTPAuth = true;
			$mail->Username = $mailer->email();
			$mail->Password = $mailer->password();
			$mail->SMTPSecure = 'tls';
			$mail->Port = 587;
			// Batasi timeout koneksi SMTP — kalau server mail lambat/unreachable,
			// jangan sampai nge-hang request PHP-FPM/worker berlama-lama (ditemukan
			// saat uji lokal: default PHPMailer bisa nunggu lama tanpa batas jelas).
			$mail->Timeout = 10;
			$mail->SMTPKeepAlive = false;

			$mail->setFrom($mailer->email(), 'Easton Park Residence');
			$mail->addAddress($to);
			$mail->isHTML(true);
			$mail->Subject = 'Reset Password Portal Owner - Easton Park Residence';
			$mail->Body = '<p>Yth. ' . htmlspecialchars($nama) . ',</p>'
				. '<p>Kami menerima permintaan reset password untuk akun Portal Owner Easton Park Residence Anda.</p>'
				. '<p><a href="' . $link . '" style="background:#715d00;color:#fff;padding:10px 20px;border-radius:6px;text-decoration:none;">Reset Password</a></p>'
				. '<p>Atau salin link berikut ke browser Anda:<br>' . $link . '</p>'
				. '<p>Link ini berlaku selama ' . self::RESET_TOKEN_MINUTES . ' menit. Kalau Anda tidak meminta reset password, abaikan email ini.</p>'
				. '<p>Building Management<br>Easton Park Residence Jatinangor</p>';

			return (bool) $mail->send();
		} catch (Exception $e) {
			log_message('error', 'send_reset_email failed: ' . $e->getMessage());
			return false;
		}
	}

	function login_otp()
	{
		$otp = $this->input->post('otp');
		$otp = $otp[0] . $otp[1] . $otp[2] . $otp[3];
		$login = $this->db->select('*')
			->from('bast_login')
			->where(array('otp' => $otp, 'uid' => $this->session->uid))
			->get()->row();

		if (!$login) {
			$this->pesan->pesan_warning("OTP Salah/Tidak Sesuai");
			redirect($_SERVER['HTTP_REFERER']);
			return;
		}

		if (strtotime($login->expired) < time()) {
			$this->pesan->pesan_warning("OTP sudah kadaluarsa. Silakan kirim ulang OTP.");
			redirect(site_url('auth/otp'));
			return;
		}

		$this->apl->updateData("bast_login", array('status' => 1),
			array('otp' => $otp, 'uid' => $this->session->uid)
		);
		$this->session->login = '1';
		$this->pesan->pesan_success("Verifikasi berhasil");
		$red = (isset($_SESSION['redirect'])) ? $_SESSION['redirect'] : '';
		redirect(site_url($red));
	}

	function resend_otp()
	{
		header('Content-Type: application/json');

		$uid = $this->session->uid;
		$id_bast = $this->session->id_bast;
		$hp = $this->session->hp;

		if (!$uid || !$id_bast || !$hp) {
			echo json_encode(array('success' => false, 'message' => 'Sesi tidak valid, silakan login ulang.'));
			return;
		}

		// Nomor pengirim OTP dari Pengaturan WA Blast (bmsdev, kode 'otp_owner') — lihat login_act().
		$otpWa = $this->db->select('db_wa.*')
			->from('wa_pengaturan')
			->join('db_wa', 'db_wa.id_wa = wa_pengaturan.id_wa')
			->where('wa_pengaturan.kode', 'otp_owner')
			->where('wa_pengaturan.hapus', 0)
			->where('db_wa.hapus', 0)
			->get()->row();

		if (!$otpWa) {
			echo json_encode(array('success' => false, 'message' => 'Sistem pengiriman OTP belum dikonfigurasi. Mohon hubungi Tenant Relation di 0823-1212-2021.'));
			return;
		}

		$new_otp = mt_rand(1000, 9999);
		$new_expired = date('Y-m-d H:i:s', strtotime('+5 minutes'));
		$new_uid = uniqid();

		// Invalidate OTP lama, insert OTP baru
		$this->apl->updateData("bast_login", array('status' => 2), array('uid' => $uid));
		$this->apl->insertData("bast_login", array(
			'hp' => $hp,
			'id_bast' => $id_bast,
			'expired' => $new_expired,
			'status' => 0,
			'otp' => $new_otp,
			'uid' => $new_uid,
		));

		// Update uid di session ke OTP baru
		$this->session->uid = $new_uid;

		$expired_ts = strtotime($new_expired);

		$message = 'Kode OTP Anda adalah *' . $new_otp . '*
Digunakan untuk Owner EPR Jatinangor
Atau klik link :
' . site_url('auth/link/' . $new_otp) . '

_____________
Mohon maaf untuk informasi lebih lanjut harap menghubungi di :
_Tenant Relation_
TLP : (022)7780188
WA (Chat Only): 0823-1212-2021
Website : https://eprjatinangor.com

Building Management ';

		// Commit session sebelum async
		session_write_close();

		// Kirim response JSON ke browser
		echo json_encode(array(
			'success' => true,
			'expired_at' => $expired_ts,
			'message' => 'OTP baru sudah dikirim via WhatsApp.',
		));

		if (function_exists('fastcgi_finish_request')) {
			fastcgi_finish_request();
		} else {
			ob_end_flush();
			flush();
		}

		// Kirim WA di background
		ignore_user_abort(true);
		set_time_limit(60);

		$otpApiKey = isset($otpWa->api_key) && $otpWa->api_key !== '' ? $otpWa->api_key : (isset($otpWa->token) ? $otpWa->token : '');
		$otpNumberKey = isset($otpWa->number_key) && $otpWa->number_key !== '' ? $otpWa->number_key : (isset($otpWa->username) ? $otpWa->username : '');
		$blastOtp = new Blast($otpApiKey, $otpNumberKey);
		$wa_response = $blastOtp->send_WA($hp, $message, $id_bast);
		$wa_success = (is_array($wa_response) && isset($wa_response['success'])) ? (int)$wa_response['success'] : 0;
		$wa_message_raw = '';
		if (is_array($wa_response) && isset($wa_response['errors'][0]['message'])) {
			$wa_message_raw = $wa_response['errors'][0]['message'];
		} elseif (is_array($wa_response) && isset($wa_response['message'])) {
			$wa_message_raw = $wa_response['message'];
		}

		$this->apl->updateData("bast_login", array(
			'wa_status' => $wa_success > 0 ? 'sent' : 'failed',
			'wa_message' => substr((string)$wa_message_raw, 0, 255),
		), array('uid' => $new_uid));
	}


	function logout()
	{
		$data_session = array(
			'id_bast',
			'id_pemilik',
			'id_unit',
			'hp',
			'login',
			'tipe',
			'uid',
			'redirect',
		);

		$this->apl->log("User " . $this->session->userdata('email') . " Berhasil Keluar", "", json_encode($data_session), "");
		$this->session->unset_userdata($data_session);
		$this->session->unset_userdata('menu');
		$this->session->unset_userdata('tombol');
		$this->load->library('session');
		$this->pesan->pesan_info('Successfully logged out

');
		redirect('');
	}
}
