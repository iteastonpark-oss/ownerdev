<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pbb extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->apl   = new Apl();
		$this->pesan = new Pesan();

		if (!$this->session->login) {
			$this->session->redirect = 'pbb';
			redirect('login');
		}
	}

	public function index()
	{
		$id_unit = $this->session->id_unit;

		$pbb = $this->db->select('pbb.id_pbb, pbb.nop')
			->from('pbb')
			->where('pbb.id_unit', $id_unit)
			->where('pbb.hapus', 0)
			->get()->row();

		$detail = array();
		if ($pbb) {
			$detail = $this->db
				->select('id_detail, tahun, tagihan, file_pdf, tgl_upload, bukti_bayar, bukti_tgl_upload')
				->from('pbb_detail')
				->where('id_pbb', $pbb->id_pbb)
				->order_by('tahun', 'DESC')
				->get()->result();
		}

		$data['judul']   = 'Pajak Bumi dan Bangunan (PBB)';
		$data['page']    = 'pbb/index';
		$data['pbb']     = $pbb;
		$data['detail']  = $detail;
		$this->load->view('home', $data);
	}

	/**
	 * Serve file PDF langsung via readfile() — tidak ada endpoint publik.
	 * Validasi kepemilikan dilakukan sebelum file dibaca.
	 */
	public function download($id_detail = '')
	{
		$id_detail = (int) $id_detail;
		if (!$id_detail) show_404();

		$id_unit = $this->session->id_unit;

		// Validasi: id_detail harus milik unit owner yang sedang login
		$detail = $this->db
			->select('pd.id_detail, pd.file_pdf, pd.tahun')
			->from('pbb_detail pd')
			->join('pbb', 'pbb.id_pbb = pd.id_pbb')
			->where('pd.id_detail', $id_detail)
			->where('pbb.id_unit', $id_unit)   // kunci keamanan
			->where('pbb.hapus', 0)
			->get()->row();

		if (!$detail || empty($detail->file_pdf)) {
			show_404();
			return;
		}

		// Deteksi format: path baru mengandung '/'
		if (strpos($detail->file_pdf, '/') !== false) {
			$file_path = BMS_UPLOAD_PATH . $detail->file_pdf;
		} else {
			$file_path = BMS_UPLOAD_PATH . 'pbb_pdf/' . $detail->file_pdf;
		}

		if (!file_exists($file_path)) {
			$this->pesan->pesan_danger('File PDF tidak ditemukan. Hubungi manajemen.');
			redirect('pbb');
			return;
		}

		$filename = 'PBB_' . $this->session->username . '_' . $detail->tahun . '.pdf';

		// Bersihkan output buffer CI3 sebelum stream PDF
		while (ob_get_level()) ob_end_clean();

		header('Content-Type: application/pdf');
		header('Content-Disposition: inline; filename="' . $filename . '"');
		header('Content-Length: ' . filesize($file_path));
		header('Cache-Control: private, max-age=0, must-revalidate');
		header('Pragma: public');
		readfile($file_path);
		exit;
	}

	/** Ekstensi bukti bayar yang diizinkan → content-type. */
	private $bukti_mime = array(
		'pdf'  => 'application/pdf',
		'jpg'  => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'png'  => 'image/png',
	);
	const BUKTI_MAX_BYTES = 5242880; // 5 MB

	/** Cek header byte asli file sesuai ekstensi yang diklaim (tanpa ext-fileinfo). */
	private function _cek_magic_bytes($tmp_path, $ext)
	{
		$fh = @fopen($tmp_path, 'rb');
		if (!$fh) return FALSE;
		$head = fread($fh, 8);
		fclose($fh);

		if (substr($head, 0, 4) === '%PDF') {
			return $ext === 'pdf';
		}
		if (substr($head, 0, 3) === "\xFF\xD8\xFF") {
			return in_array($ext, array('jpg', 'jpeg'), true);
		}
		if (substr($head, 0, 8) === "\x89PNG\r\n\x1a\n") {
			return $ext === 'png';
		}
		return FALSE;
	}

	/**
	 * Owner unggah bukti bayar PBB untuk satu baris tahun (id_detail miliknya).
	 * File disimpan di BMS_UPLOAD_PATH/pbb_bukti/{tahun}/ dan tercatat di pbb_detail
	 * → terlihat di admin bms untuk rekap.
	 */
	public function upload_bukti()
	{
		$id_detail = (int) $this->input->post('id_detail');
		$id_unit   = $this->session->id_unit;

		// Validasi kepemilikan: id_detail harus milik unit owner yang login.
		$detail = $this->db->select('pd.id_detail, pd.tahun, pd.bukti_bayar')
			->from('pbb_detail pd')
			->join('pbb', 'pbb.id_pbb = pd.id_pbb')
			->where('pd.id_detail', $id_detail)
			->where('pbb.id_unit', $id_unit)
			->where('pbb.hapus', 0)
			->get()->row();

		if (!$detail) {
			$this->pesan->pesan_danger('Data PBB tidak valid.');
			redirect('pbb');
			return;
		}
		if (empty($_FILES['bukti']['name']) || $_FILES['bukti']['error'] === UPLOAD_ERR_NO_FILE) {
			$this->pesan->pesan_warning('Silakan pilih file bukti bayar terlebih dahulu.');
			redirect('pbb');
			return;
		}

		$f = $_FILES['bukti'];
		if ($f['error'] !== UPLOAD_ERR_OK) {
			$this->pesan->pesan_danger('Gagal mengunggah file. Coba lagi.');
			redirect('pbb');
			return;
		}
		if ($f['size'] > self::BUKTI_MAX_BYTES) {
			$this->pesan->pesan_danger('Ukuran file melebihi 5 MB.');
			redirect('pbb');
			return;
		}
		$ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
		if (!isset($this->bukti_mime[$ext])) {
			$this->pesan->pesan_danger('Format tidak didukung. Gunakan PDF, JPG, atau PNG.');
			redirect('pbb');
			return;
		}
		// Verifikasi tipe asli via magic bytes (bukan sekadar ekstensi).
		// Tidak pakai ext-fileinfo: tidak tersedia di build PHP production.
		if (!$this->_cek_magic_bytes($f['tmp_name'], $ext)) {
			$this->pesan->pesan_danger('Isi file tidak sesuai format PDF/JPG/PNG.');
			redirect('pbb');
			return;
		}

		$kode = $this->db->select('kode')->where('id_unit', $id_unit)->get('db_unit')->row();
		$kode = $kode ? $kode->kode : ('U' . $id_unit);

		$reldir = 'pbb_bukti/' . $detail->tahun . '/';
		$absdir = BMS_UPLOAD_PATH . $reldir;
		if (!is_dir($absdir) && !@mkdir($absdir, 0775, true)) {
			$this->pesan->pesan_danger('Gagal menyiapkan folder penyimpanan. Hubungi manajemen.');
			redirect('pbb');
			return;
		}

		$fname   = 'BUKTI-PBB-' . $kode . '-' . $detail->tahun . '-' . time() . '.' . $ext;
		$relpath = $reldir . $fname;
		if (!move_uploaded_file($f['tmp_name'], $absdir . $fname)) {
			$this->pesan->pesan_danger('Gagal menyimpan file. Coba lagi.');
			redirect('pbb');
			return;
		}

		// Hapus bukti lama bila ada (ganti dengan yang baru).
		if (!empty($detail->bukti_bayar) && $detail->bukti_bayar !== $relpath) {
			$old = BMS_UPLOAD_PATH . $detail->bukti_bayar;
			if (is_file($old)) @unlink($old);
		}

		$this->db->where('id_detail', $id_detail)->update('pbb_detail', array(
			'bukti_bayar'      => $relpath,
			'bukti_tgl_upload' => date('Y-m-d H:i:s'),
		));

		$this->pesan->pesan_success('Bukti bayar PBB tahun ' . $detail->tahun . ' berhasil diunggah.');
		redirect('pbb');
	}

	/** Owner hapus bukti bayar PBB miliknya sendiri. */
	public function hapus_bukti()
	{
		$id_detail = (int) $this->input->post('id_detail');
		$id_unit   = $this->session->id_unit;

		$detail = $this->db->select('pd.id_detail, pd.tahun, pd.bukti_bayar')
			->from('pbb_detail pd')
			->join('pbb', 'pbb.id_pbb = pd.id_pbb')
			->where('pd.id_detail', $id_detail)
			->where('pbb.id_unit', $id_unit)
			->where('pbb.hapus', 0)
			->get()->row();

		if (!$detail || empty($detail->bukti_bayar)) {
			$this->pesan->pesan_danger('Data bukti bayar tidak ditemukan.');
			redirect('pbb');
			return;
		}

		$file_path = BMS_UPLOAD_PATH . $detail->bukti_bayar;
		if (is_file($file_path)) @unlink($file_path);

		$this->db->where('id_detail', $id_detail)->update('pbb_detail', array(
			'bukti_bayar'      => NULL,
			'bukti_tgl_upload' => NULL,
		));

		$this->pesan->pesan_success('Bukti bayar PBB tahun ' . $detail->tahun . ' berhasil dihapus.');
		redirect('pbb');
	}

	/** Serve file bukti bayar milik owner yang login (inline, dgn validasi kepemilikan). */
	public function bukti($id_detail = '')
	{
		$id_detail = (int) $id_detail;
		if (!$id_detail) show_404();

		$detail = $this->db->select('pd.bukti_bayar, pd.tahun')
			->from('pbb_detail pd')
			->join('pbb', 'pbb.id_pbb = pd.id_pbb')
			->where('pd.id_detail', $id_detail)
			->where('pbb.id_unit', $this->session->id_unit)
			->where('pbb.hapus', 0)
			->get()->row();

		if (!$detail || empty($detail->bukti_bayar)) show_404();

		$file_path = BMS_UPLOAD_PATH . $detail->bukti_bayar;
		if (!file_exists($file_path)) {
			$this->pesan->pesan_danger('File bukti tidak ditemukan. Hubungi manajemen.');
			redirect('pbb');
			return;
		}

		$ext  = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
		$mime = isset($this->bukti_mime[$ext]) ? $this->bukti_mime[$ext] : 'application/octet-stream';

		while (ob_get_level()) ob_end_clean();
		header('Content-Type: ' . $mime);
		header('Content-Disposition: inline; filename="BuktiPBB_' . $this->session->username . '_' . $detail->tahun . '.' . $ext . '"');
		header('Content-Length: ' . filesize($file_path));
		header('Cache-Control: private, max-age=0, must-revalidate');
		readfile($file_path);
		exit;
	}
}
