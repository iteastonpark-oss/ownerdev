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
	}

	public function login() {}

	function login_act()
	{

		$hp = $this->input->post('hp');
		$id_bast = $this->input->post('id_bast');

		if ($hp == '085780465303' || $hp == '085195140308' || $hp == '082129248953' || $hp == '081224341030' || $hp == '085862880940' || $hp == '082215866660' || $hp == '0881022246542' || $hp == '089690527866' || $hp == '089687038641') {
			$where = "hapus=0 AND id_bast='" . $id_bast . "'";
		} else {
			$where = "hapus=0 and wa_surat LIKE '%" . $hp . "%' AND id_bast='" . $id_bast . "'";
		}

		$cek = $this->apl->getSelectedData("bast", $where)->num_rows();
		if ($cek > 0) {
			// Nomor pengirim OTP ditentukan dari Pengaturan WA Blast (bmsdev, kode 'otp_owner'),
			// bukan hardcode/env lagi. Kalau belum diset admin, tolak login (jangan kirim OTP diam2 dari nomor sembarang).
			$otpWa = $this->db->select('db_wa.*')
				->from('wa_pengaturan')
				->join('db_wa', 'db_wa.id_wa = wa_pengaturan.id_wa')
				->where('wa_pengaturan.kode', 'otp_owner')
				->where('wa_pengaturan.hapus', 0)
				->where('db_wa.hapus', 0)
				->get()->row();

			if (!$otpWa) {
				$this->pesan->pesan_warning("Sistem pengiriman OTP belum dikonfigurasi. Mohon hubungi Tenant Relation di 0823-1212-2021.");
				redirect(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : site_url('login'));
				return;
			}

			$data = $this->apl->getSelectedData("bast", $where)->row();

			// Cooldown 60 detik: bila OTP untuk unit & nomor ini baru saja dikirim,
			// jangan kirim ulang (cegah OTP ganda dari double-submit / multi-tab).
			// uid sesi memakai ulang baris bast_login terbaru agar OTP lama tetap bisa diverifikasi.
			$recent = $this->db->where('id_bast', $id_bast)
				->where('hp', $hp)
				->where('tm >=', date('Y-m-d H:i:s', time() - 60))
				->order_by('tm', 'desc')
				->limit(1)
				->get('bast_login')
				->row();

			if ($recent) {
				$this->session->set_userdata(array(
					'id_bast' => $data->id_bast,
					'id_pemilik' => $data->id_pemilik,
					'id_unit' => $data->id_unit,
					'hp' => $hp,
					'login' => '0',
					'tipe' => 'owner',
					'uid' => $recent->uid,
					'id_admin' => 0,
				));
				$this->pesan->pesan_success("OTP sudah dikirim. Mohon tunggu hingga 60 detik sebelum meminta ulang.");
				redirect(site_url('auth/otp'));
				return;
			}

			$uid = uniqid();
			$data_session = array(
				'id_bast' => $data->id_bast,
				'id_pemilik' => $data->id_pemilik,
				'id_unit' => $data->id_unit,
				'hp' => $hp,
				'login' => '0',
				'tipe' => 'owner',
				'uid' => $uid,
				'id_admin' => 0,
			);

			$this->session->set_userdata($data_session);
			$this->apl->log("LOGIN", json_encode($data), "");

			$otp = mt_rand(1000, 9999);
			$this->apl->insertData("bast_login", array(
				'hp' => $hp,
				'id_bast' => $id_bast,
				'expired' => date('Y-m-d H:i:s', strtotime('+5 minutes')),
				'status' => 0,
				'otp' => $otp,
				'uid' => $uid,
			));

			$phone = $hp;
			$message = 'Kode OTP Anda adalah *' . $otp . '*
Digunakan untuk Owner EPR Jatinangor
Atau klik link :
' . site_url('auth/link/' . $otp);
			$message .= '

_____________
Mohon maaf untuk informasi lebih lanjut harap menghubungi di :
_Tenant Relation_
TLP : (022)7780188
WA (Chat Only): 0823-1212-2021
Website : https://eprjatinangor.com

Building Management ';

			// Set pesan sebelum commit session
			$this->pesan->pesan_success("OTP atau link Verifikasi sedang dikirim via WhatsApp");

			// Commit session ke DB agar flash message & data login tersimpan
			session_write_close();

			// Kirim redirect ke browser — user langsung masuk halaman OTP
			header('Location: ' . site_url('auth/otp'));
			header('Content-Length: 0');
			header('Connection: close');

			if (function_exists('fastcgi_finish_request')) {
				fastcgi_finish_request(); // koneksi browser ditutup, PHP lanjut di background
			} else {
				ob_end_flush();
				flush();
			}

			// === BACKGROUND: user sudah di halaman OTP, baru kirim WA ===
			ignore_user_abort(true);
			set_time_limit(60);

			$otpApiKey = isset($otpWa->api_key) && $otpWa->api_key !== '' ? $otpWa->api_key : (isset($otpWa->token) ? $otpWa->token : '');
			$otpNumberKey = isset($otpWa->number_key) && $otpWa->number_key !== '' ? $otpWa->number_key : (isset($otpWa->username) ? $otpWa->username : '');
			$blastOtp = new Blast($otpApiKey, $otpNumberKey);
			$wa_response = $blastOtp->send_WA($phone, $message, $id_bast);

			// Log status WA ke bast_login untuk monitoring
			$wa_success = 0;
			$wa_message_raw = '';
			if (is_array($wa_response)) {
				$wa_success = isset($wa_response['success']) ? (int) $wa_response['success'] : 0;
				if (isset($wa_response['errors'][0]['message'])) {
					$wa_message_raw = $wa_response['errors'][0]['message'];
				} elseif (isset($wa_response['message'])) {
					$wa_message_raw = $wa_response['message'];
				}
			} elseif (is_object($wa_response) && isset($wa_response->message)) {
				$wa_message_raw = $wa_response->message;
			}

			$this->apl->updateData("bast_login", array(
				'wa_status' => $wa_success > 0 ? 'sent' : 'failed',
				'wa_message' => substr((string) $wa_message_raw, 0, 255),
			), array('uid' => $uid));

			return;
		} else {
			$this->pesan->pesan_warning("Gagal Masuk Mohon Hubungi Bagian TR untuk pendaftaran Nomor Wa / Pengecekan Lebih Lanjut di Nomor : 0823-1212-2021");
			redirect($_SERVER['HTTP_REFERER']);
			//redirect(site_url('auth/otp'));
		}
		//redirect($_SERVER['HTTP_REFERER']);

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

		$wa_response = $this->blast->send_WA($hp, $message, $id_bast);
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
