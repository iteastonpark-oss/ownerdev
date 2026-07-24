<?php defined('BASEPATH') or exit('No direct script access allowed');

class Blast
{
	var $db = NULL;
	var $apl = NULL;
	var $api_key;
	var $number_key;
	// Legacy aliases (for backward compatibility)
	var $token_wa;
	var $username_wa;
	var $base_url = 'https://api.watzap.id/v1';
	var $credential_checked = false;

	function __construct($apiKey = 'HLNW0LHSBP6JSBS3', $numberKey = 'zw0ekX6cyRQA3ghf')
	{
		$CI = &get_instance();
		$this->db = $CI->load->database('default', TRUE);
		$this->apl = $CI->load->library('apl', TRUE);

		$this->api_key = (string) $apiKey;
		$this->number_key = (string) $numberKey;
		$this->syncLegacyAliases();
	}

	private function syncLegacyAliases()
	{
		$this->token_wa = $this->api_key;
		$this->username_wa = $this->number_key;
	}

	public function no_hp($hp)
	{
		$phone = trim((string) $hp);
		$phone = preg_replace('/[^0-9\+]/', '', $phone);

		if (substr($phone, 0, 1) === '+') {
			$phone = substr($phone, 1);
		}

		if (substr($phone, 0, 2) === '62') {
			return $phone;
		}

		if (substr($phone, 0, 1) === '0') {
			return '62' . substr($phone, 1);
		}

		if (substr($phone, 0, 1) === '8') {
			return '62' . $phone;
		}

		if (substr($phone, 0, 3) === '965') {
			return $phone;
		}

		return '';
	}

	public function cek_token()
	{
		echo $this->api_key;
	}

	function insertData($table, $data)
	{
		$this->db->insert($table, $data);
	}

	private function request($endpoint, $payload = array())
	{
		$url = rtrim($this->base_url, '/') . '/' . ltrim($endpoint, '/');

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
			CURLOPT_POSTFIELDS => json_encode($payload),
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			// wait_until_send dimatikan (0) di semua payload send_message — versi
			// wait=1 pernah hang/timeout tanpa batas jelas untuk sebagian nomor meski
			// device sehat & pesan tetap terkirim. 30s tetap dipertahankan sbg margin aman.
			CURLOPT_TIMEOUT => 30,
		));

		$response = curl_exec($curl);
		$error = curl_error($curl);
		$httpCode = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);

		$decoded = json_decode($response, true);
		if (!is_array($decoded)) {
			$decoded = array(
				'status' => '1006',
				'message' => $error !== '' ? $error : 'Invalid response',
				'ack' => 'failed',
			);
		}

		$decoded['_raw'] = $response;
		$decoded['_http_code'] = $httpCode;
		return $decoded;
	}

	private function logFailure($context, $phone, $response)
	{
		if (function_exists('log_message')) {
			$status = isset($response['status']) ? $response['status'] : 'unknown';
			$message = isset($response['message']) ? $response['message'] : 'no message';
			log_message('error', 'Blast WatZap failed [' . $context . '] phone=' . $phone . ' status=' . $status . ' message=' . $message);
		}
	}

	private function isSuccess($response)
	{
		$status = isset($response['status']) ? (string) $response['status'] : '';
		$ack = isset($response['ack']) ? strtolower((string) $response['ack']) : '';

		return $status === '200' || $status === '1' || $status === 'true' || $ack === 'success' || $ack === 'sent';
	}

	private function makeErrorEntry($phone, $response, $fallback = '')
	{
		$message = isset($response['message']) ? (string) $response['message'] : $fallback;
		if ($message === '') {
			$message = 'Unknown error';
		}

		return array(
			'phone' => $phone,
			'status' => isset($response['status']) ? (string) $response['status'] : 'unknown',
			'message' => $message,
		);
	}

	private function makeAuthPayload($data = array())
	{
		$this->resolveCredentialMapping();

		return array_merge(array(
			'api_key' => $this->api_key,
			'number_key' => $this->number_key,
		), $data);
	}

	private function resolveCredentialMapping()
	{
		if ($this->credential_checked) {
			return;
		}
		$this->credential_checked = true;

		$primary = $this->request('checking_key', array(
			'api_key' => $this->api_key,
			'api-key' => $this->api_key,
		));
		if ($this->isSuccess($primary)) {
			$this->syncLegacyAliases();
			return;
		}

		$message = isset($primary['message']) ? strtolower((string) $primary['message']) : '';
		$status = isset($primary['status']) ? (string) $primary['status'] : '';
		$isInvalidApiKey = $status === '1002' || strpos($message, 'invalid key') !== false || strpos($message, 'invalid api key') !== false;

		if (!$isInvalidApiKey || trim((string) $this->number_key) === '') {
			return;
		}

		$secondary = $this->request('checking_key', array(
			'api_key' => $this->number_key,
			'api-key' => $this->number_key,
		));
		if ($this->isSuccess($secondary)) {
			// Fallback otomatis: sebagian data lama menyimpan api_key di kolom username.
			$apiKey = $this->number_key;
			$numberKey = $this->api_key;
			$this->api_key = $apiKey;
			$this->number_key = $numberKey;
			$this->syncLegacyAliases();
		}
	}

	private function parsePhones($to)
	{
		$to = (string) $to;
		$parts = preg_split('/[\s,;]+/', $to, -1, PREG_SPLIT_NO_EMPTY);
		if (!is_array($parts)) {
			return array();
		}

		return array_values(array_unique($parts));
	}

	public function device()
	{
		$this->resolveCredentialMapping();

		// Docs: Check API Status only requires api_key
		return $this->request('checking_key', array(
			'api_key' => $this->api_key,
			// Compatibility with legacy examples using "api-key"
			'api-key' => $this->api_key,
		));
	}

	public function send_WA($to, $text, $id_bast = '')
	{
		$CI = &get_instance();
		$CI->load->library('session');
		$id_admin = $CI->session->userdata('id_admin');

		$phones = $this->parsePhones($to);
		$summary = array(
			'success' => 0,
			'failed' => 0,
			'errors' => array(),
		);

		foreach ($phones as $rawPhone) {
			$rawPhone = trim($rawPhone);
			if ($rawPhone === '') {
				continue;
			}

			$phone = $this->no_hp($rawPhone);
			if ($phone === '') {
				$this->logFailure('send_WA-invalid-phone', $rawPhone, array('message' => 'Invalid phone format'));
				$summary['failed']++;
				$summary['errors'][] = $this->makeErrorEntry($rawPhone, array('status' => '1001', 'message' => 'Invalid phone format'));
				continue;
			}

			$payload = $this->makeAuthPayload(array(
				'phone_no' => $phone,
				'message' => $text,
				'wait_until_send' => '0',
			));

			$response = $this->request('send_message', $payload);

			if ($this->isSuccess($response)) {
				$this->insertData('call', array(
					'message' => $text,
					'phone' => $phone,
					'status' => 1,
					'keluar_masuk' => 0,
					'id_admin' => $id_admin,
					'id_bast' => $id_bast,
					'tanggal' => date('Y-m-d'),
				));
				$summary['success']++;
			} else {
				$this->logFailure('send_WA', $phone, $response);
				$errMsg = isset($response['message']) ? (string) $response['message'] : 'unknown error';
				$this->insertData('call', array(
					'message' => $text,
					'phone' => $phone,
					'status' => 0,
					'keluar_masuk' => 0,
					'id_admin' => $id_admin,
					'id_bast' => $id_bast,
					'tanggal' => date('Y-m-d'),
					'note' => substr($errMsg, 0, 50),
				));
				$summary['failed']++;
				$summary['errors'][] = $this->makeErrorEntry($phone, $response);
			}
		}

		return $summary;
	}

	public function send_WA_multiple($data, $id_broadcast = '')
	{
		if (!isset($data['data']) || !is_array($data['data'])) {
			return array(
				'success' => 0,
				'failed' => 1,
				'errors' => array(
					array(
						'phone' => '',
						'status' => '1002',
						'message' => 'Payload data tidak valid',
					),
				),
			);
		}

		$call = array();
		$summary = array(
			'success' => 0,
			'failed' => 0,
			'errors' => array(),
		);

		foreach ($data['data'] as $row) {
			if (!is_array($row)) {
				continue;
			}

			$phone = isset($row['phone']) ? $this->no_hp($row['phone']) : '';
			$message = isset($row['message']) ? $row['message'] : '';
			$id_bast = isset($row['id_bast']) ? $row['id_bast'] : '';

			if ($phone === '' || $message === '') {
				if ($phone === '') {
					$this->logFailure('send_WA_multiple-invalid-phone', isset($row['phone']) ? $row['phone'] : '', array('message' => 'Invalid phone format'));
					$summary['errors'][] = $this->makeErrorEntry(isset($row['phone']) ? $row['phone'] : '', array('status' => '1001', 'message' => 'Invalid phone format'));
				} else {
					$summary['errors'][] = $this->makeErrorEntry($phone, array('status' => '1003', 'message' => 'Message kosong'));
				}
				$summary['failed']++;
				continue;
			}

			$payload = $this->makeAuthPayload(array(
				'phone_no' => $phone,
				'message' => $message,
				'wait_until_send' => '0',
			));

			$response = $this->request('send_message', $payload);

			if ($this->isSuccess($response)) {
				$call[] = array(
					'message' => $message,
					'phone' => $phone,
					'status' => 1,
					'keluar_masuk' => 0,
					'id_bast' => $id_bast,
					'id_broadcast' => $id_broadcast,
					'tanggal' => date('Y-m-d'),
				);
				$summary['success']++;
			} else {
				$this->logFailure('send_WA_multiple', $phone, $response);
				$summary['failed']++;
				$summary['errors'][] = $this->makeErrorEntry($phone, $response);
			}
		}

		if (!empty($call)) {
			$this->db->insert_batch('call', $call);
		}

		return $summary;
	}

	public function send_WA_document($to, $filename, $url_gambar, $id_bast = '', $caption = '')
	{
		$CI = &get_instance();
		$CI->load->library('session');
		$id_admin = $CI->session->userdata('id_admin');

		$phones = $this->parsePhones($to);
		$summary = array(
			'success' => 0,
			'failed' => 0,
			'errors' => array(),
		);

		$explode = explode('.', (string) $filename);
		$extensi = strtolower($explode[count($explode) - 1]);
		$isImage = in_array($extensi, array('png', 'jpg', 'jpeg', 'webp', 'gif'));

		foreach ($phones as $rawPhone) {
			$rawPhone = trim($rawPhone);
			if ($rawPhone === '') {
				continue;
			}

			$phone = $this->no_hp($rawPhone);
			if ($phone === '') {
				$this->logFailure('send_WA_document-invalid-phone', $rawPhone, array('message' => 'Invalid phone format'));
				$summary['failed']++;
				$summary['errors'][] = $this->makeErrorEntry($rawPhone, array('status' => '1001', 'message' => 'Invalid phone format'));
				continue;
			}

			if ($isImage) {
				$payload = $this->makeAuthPayload(array(
					'phone_no' => $phone,
					'url' => $url_gambar,
					'message' => $caption,
					'separate_caption' => '0',
					'wait_until_send' => '0',
				));
				$response = $this->request('send_image_url', $payload);
			} else {
				$payload = $this->makeAuthPayload(array(
					'phone_no' => $phone,
					'url' => $url_gambar,
				));
				$response = $this->request('send_file_url', $payload);

				// WatZap file endpoint tidak menyediakan caption, kirim caption sebagai pesan lanjutan.
				if ($this->isSuccess($response) && trim($caption) !== '') {
					$this->request('send_message', $this->makeAuthPayload(array(
						'phone_no' => $phone,
						'message' => $caption,
						'wait_until_send' => '0',
					)));
				}
			}

			if ($this->isSuccess($response)) {
				$this->insertData('call', array(
					'message' => $caption,
					'phone' => $phone,
					'status' => 1,
					'keluar_masuk' => 0,
					'id_admin' => $id_admin,
					'id_bast' => $id_bast,
					'tanggal' => date('Y-m-d'),
				));
				$summary['success']++;
			} else {
				$this->logFailure('send_WA_document', $phone, $response);
				$errMsg = isset($response['message']) ? (string) $response['message'] : 'unknown error';
				$this->insertData('call', array(
					'message' => $caption,
					'phone' => $phone,
					'status' => 0,
					'keluar_masuk' => 0,
					'id_admin' => $id_admin,
					'id_bast' => $id_bast,
					'tanggal' => date('Y-m-d'),
					'note' => substr($errMsg, 0, 50),
				));
				$summary['failed']++;
				$summary['errors'][] = $this->makeErrorEntry($phone, $response);
			}
		}

		return $summary;
	}

	public function check_WA($phone)
	{
		$phone = $this->no_hp($phone);
		if ($phone === '') {
			return '-1';
		}

		$response = $this->request('validate_number', $this->makeAuthPayload(array(
			'phone_no' => $phone,
		)));

		return $this->isSuccess($response) ? '1' : '0';
	}

	public function info_WA()
	{
		return $this->device();
	}

	public function batre_WA()
	{
		return $this->device();
	}

	public function delete_session()
	{
		return array(
			'status' => '1006',
			'ack' => 'failed',
			'message' => 'Endpoint delete session tidak tersedia di API WatZap.',
		);
	}

	// Compatibility layer untuk controller lama.
	public function restart_tr()
	{
		return $this->delete_session();
	}

	public function restart_fin()
	{
		return $this->delete_session();
	}

	public function scan_QR()
	{
		return $this->device();
	}

	public function scan_QR_tr()
	{
		return $this->device();
	}

	public function scan_QR_fin()
	{
		return $this->device();
	}

	public function balas_WA_FIN($to, $text, $id_bast = '')
	{
		return $this->send_WA($to, $text, $id_bast);
	}

	public function balas_WA_TR($to, $text, $id_bast = '')
	{
		return $this->send_WA($to, $text, $id_bast);
	}
}
