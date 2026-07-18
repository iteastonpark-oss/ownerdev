<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Acara (Owner Portal) — undangan acara/RUA untuk penghuni.
 * Penghuni melihat undangan, menyatakan kehadiran: hadir (online/offline), tidak,
 * dikuasakan (upload surat kuasa + KTP wakil + surat izin huni), atau diwakilkan
 * keluarga (upload KK + KTP wakil). 1 RSVP per unit (id_bast).
 * Ref rencana: docs/82_perencanaan_acara_voting.md (T2).
 */
class Acara extends CI_Controller
{
	/** Ekstensi berkas kuasa/KTP yang diizinkan. */
	private $file_ext = array('pdf', 'jpg', 'jpeg', 'png');
	const FILE_MAX_BYTES = 5242880; // 5 MB

	public function __construct()
	{
		parent::__construct();
		$this->apl   = new Apl();
		$this->pesan = new Pesan();

		if (!$this->session->login) {
			$this->session->redirect = 'acara';
			redirect('login');
		}
	}

	/**
	 * Daftar acara: yang aktif (publish) + riwayat, lengkap status RSVP unit ini.
	 */
	public function index()
	{
		$id_bast = $this->session->id_bast;

		$rows = $this->db
			->select('a.id_acara, a.kode, a.nama, a.deskripsi, a.lokasi, a.link_online,
				a.tgl_mulai, a.tgl_selesai, a.batas_rsvp, a.banner, a.status')
			->select('p.kehadiran, p.mode')
			->from('acara a')
			->join("(SELECT id_acara, kehadiran, mode FROM acara_peserta
				WHERE id_bast = " . (int) $id_bast . " AND hapus = 0) p", 'p.id_acara = a.id_acara', 'left')
			->where('a.hapus', 0)
			->where_in('a.status', array(1, 2))
			->order_by('a.tgl_mulai', 'DESC')
			->get()->result();

		$aktif = array();
		$riwayat = array();
		foreach ($rows as $r) {
			if ($r->status == 1) $aktif[] = $r;
			else $riwayat[] = $r;
		}

		$data['judul']   = 'Acara & Undangan';
		$data['page']    = 'acara/index';
		$data['aktif']   = $aktif;
		$data['riwayat'] = $riwayat;
		$this->load->view('home', $data);
	}

	/**
	 * Detail undangan + form RSVP untuk satu acara.
	 */
	public function detail($kode = '')
	{
		if ($kode === '') show_404();
		$id_bast = $this->session->id_bast;

		$acara = $this->db->from('acara')
			->where('kode', $kode)
			->where('hapus', 0)
			->where_in('status', array(1, 2))
			->get()->row();
		if (!$acara) show_404();

		$peserta = $this->db->from('acara_peserta')
			->where('id_acara', $acara->id_acara)
			->where('id_bast', $id_bast)
			->where('hapus', 0)
			->get()->row();

		$data['judul']   = $acara->nama;
		$data['page']    = 'acara/detail';
		$data['acara']   = $acara;
		$data['peserta'] = $peserta;
		$data['bisa_rsvp'] = $this->_bisa_rsvp($acara) && !$this->_sudah_checkin($peserta);
		$data['sudah_checkin'] = $this->_sudah_checkin($peserta);
		$data['ada_voting'] = $this->db->where('id_acara', $acara->id_acara)->where('hapus', 0)
			->where_in('status', array(1, 2))->count_all_results('acara_voting');
		$this->load->view('home', $data);
	}

	/**
	 * Stream gambar banner acara. bmsdev menyimpan banner di upload/berkas/
	 * (= BMS_UPLOAD_PATH.'berkas/' pada deployment produksi).
	 */
	public function banner($kode = '')
	{
		if ($kode === '') show_404();
		$acara = $this->db->select('banner')->from('acara')
			->where('kode', $kode)->where('hapus', 0)
			->where_in('status', array(1, 2))->get()->row();
		if (!$acara || empty($acara->banner)) show_404();

		$file = BMS_UPLOAD_PATH . 'berkas/' . basename($acara->banner);
		if (!is_file($file)) show_404();

		$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
		$mime = array('jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif', 'webp' => 'image/webp');
		if (!isset($mime[$ext])) show_404();

		while (ob_get_level()) ob_end_clean();
		header('Content-Type: ' . $mime[$ext]);
		header('Content-Length: ' . filesize($file));
		header('Cache-Control: private, max-age=3600');
		readfile($file);
		exit;
	}

	/**
	 * QR kehadiran: PNG berisi qr_token peserta (unit yang login).
	 * $dl='download' -> paksa unduh. Hanya untuk yg akan hadir & punya token.
	 */
	public function qr($kode = '', $dl = '')
	{
		if ($kode === '') show_404();
		$id_bast = $this->session->id_bast;

		$acara = $this->db->select('id_acara')->from('acara')
			->where('kode', $kode)->where('hapus', 0)
			->where_in('status', array(1, 2))->get()->row();
		if (!$acara) show_404();

		$peserta = $this->db->select('qr_token, kehadiran')->from('acara_peserta')
			->where('id_acara', $acara->id_acara)
			->where('id_bast', $id_bast)
			->where('hapus', 0)->get()->row();
		if (!$peserta || empty($peserta->qr_token) || $peserta->kehadiran === 'tidak') show_404();

		$this->load->library('ciqrcode');
		$tmp = rtrim(sys_get_temp_dir(), '/') . '/qr_acara_' . md5($peserta->qr_token) . '.png';
		$this->ciqrcode->generate(array(
			'data'     => $peserta->qr_token,
			'level'    => 'H',
			'size'     => 8,
			'savename' => $tmp,
		));
		if (!is_file($tmp)) show_404();

		$kode_unit = $this->db->select('du.kode')->from('bast b')
			->join('db_unit du', 'du.id_unit = b.id_unit')
			->where('b.id_bast', $id_bast)->get()->row();
		$kode_unit = $kode_unit ? $kode_unit->kode : $id_bast;

		while (ob_get_level()) ob_end_clean();
		header('Content-Type: image/png');
		if ($dl === 'download') {
			header('Content-Disposition: attachment; filename="QR-' . preg_replace('/[^A-Za-z0-9_-]/', '', $kode) . '-' . $kode_unit . '.png"');
		}
		header('Content-Length: ' . filesize($tmp));
		header('Cache-Control: private, max-age=0, must-revalidate');
		readfile($tmp);
		@unlink($tmp);
		exit;
	}

	/**
	 * Simpan RSVP (insert/update, 1 per unit). Tie ke session id_bast.
	 */
	public function rsvp()
	{
		$id_bast  = $this->session->id_bast;
		$id_acara = (int) $this->input->post('id_acara');

		$acara = $this->db->from('acara')
			->where('id_acara', $id_acara)
			->where('hapus', 0)
			->get()->row();
		if (!$acara) {
			$this->pesan->pesan_danger('Acara tidak ditemukan.');
			redirect('acara');
			return;
		}

		if (!$this->_bisa_rsvp($acara)) {
			$this->pesan->pesan_warning('Konfirmasi kehadiran sudah ditutup untuk acara ini.');
			redirect('acara/detail/' . $acara->kode);
			return;
		}

		$kehadiran = $this->input->post('kehadiran');
		if (!in_array($kehadiran, array('hadir', 'tidak', 'dikuasakan', 'diwakilkan'), true)) {
			$this->pesan->pesan_warning('Silakan pilih status kehadiran.');
			redirect('acara/detail/' . $acara->kode);
			return;
		}

		// RSVP existing (untuk update + reuse file/token lama)
		$peserta = $this->db->from('acara_peserta')
			->where('id_acara', $id_acara)
			->where('id_bast', $id_bast)
			->where('hapus', 0)
			->get()->row();

		// Sudah check-in -> terkunci. Ditegakkan di server, bukan cuma disembunyikan
		// di view, supaya tidak bisa dilewati dengan submit POST langsung.
		if ($this->_sudah_checkin($peserta)) {
			$this->pesan->pesan_warning('Kehadiran Anda sudah tercatat (check-in). Perubahan hanya dapat dilakukan oleh pengurus.');
			redirect('acara/detail/' . $acara->kode);
			return;
		}

		$data = array(
			'id_acara'   => $id_acara,
			'kode'       => $acara->kode,
			'id_bast'    => $id_bast,
			'kehadiran'  => $kehadiran,
			'nama_hadir' => trim((string) $this->input->post('nama_hadir')),
			'mode'       => null,
			'nama_wakil' => null,
		);

		if ($kehadiran === 'hadir') {
			$mode = $this->input->post('mode');
			if (!in_array($mode, array('online', 'offline'), true)) {
				$this->pesan->pesan_warning('Pilih mode kehadiran: Online atau Offline.');
				redirect('acara/detail/' . $acara->kode);
				return;
			}
			$data['mode'] = $mode;
		}

		if ($kehadiran === 'dikuasakan') {
			$nama_wakil = trim((string) $this->input->post('nama_wakil_kuasa'));
			if ($nama_wakil === '') {
				$this->pesan->pesan_warning('Nama penerima kuasa wajib diisi.');
				redirect('acara/detail/' . $acara->kode);
				return;
			}
			$data['nama_wakil'] = $nama_wakil;

			// Surat kuasa, KTP wakil & surat izin huni: wajib ada (pakai lama bila tidak upload baru).
			$sk = $this->_simpan_file('surat_kuasa', $acara->kode, 'SK', $id_bast,
				($peserta ? $peserta->surat_kuasa : null));
			$ktp = $this->_simpan_file('ktp_wakil', $acara->kode, 'KTP', $id_bast,
				($peserta ? $peserta->ktp_wakil : null));
			$sih = $this->_simpan_file('surat_izin_huni', $acara->kode, 'SIH', $id_bast,
				($peserta ? $peserta->surat_izin_huni : null));
			foreach (array($sk, $ktp, $sih) as $f) {
				if ($f['error']) {
					$this->pesan->pesan_danger($f['error']);
					redirect('acara/detail/' . $acara->kode);
					return;
				}
			}
			if (!$sk['path'] || !$ktp['path'] || !$sih['path']) {
				$this->pesan->pesan_warning('Surat kuasa, foto KTP wakil, dan surat izin huni wajib diunggah.');
				redirect('acara/detail/' . $acara->kode);
				return;
			}
			$data['surat_kuasa']      = $sk['path'];
			$data['ktp_wakil']        = $ktp['path'];
			$data['surat_izin_huni']  = $sih['path'];
			$data['kartu_keluarga']   = null;
		} elseif ($kehadiran === 'diwakilkan') {
			$nama_wakil = trim((string) $this->input->post('nama_wakil_keluarga'));
			if ($nama_wakil === '') {
				$this->pesan->pesan_warning('Nama anggota keluarga wajib diisi.');
				redirect('acara/detail/' . $acara->kode);
				return;
			}
			$data['nama_wakil'] = $nama_wakil;

			// KK & KTP wakil: wajib ada (pakai lama bila tidak upload baru).
			$kk = $this->_simpan_file('kartu_keluarga', $acara->kode, 'KK', $id_bast,
				($peserta ? $peserta->kartu_keluarga : null));
			$ktp = $this->_simpan_file('ktp_wakil_keluarga', $acara->kode, 'KTP', $id_bast,
				($peserta ? $peserta->ktp_wakil : null));
			foreach (array($kk, $ktp) as $f) {
				if ($f['error']) {
					$this->pesan->pesan_danger($f['error']);
					redirect('acara/detail/' . $acara->kode);
					return;
				}
			}
			if (!$kk['path'] || !$ktp['path']) {
				$this->pesan->pesan_warning('Kartu keluarga dan foto KTP anggota keluarga wajib diunggah.');
				redirect('acara/detail/' . $acara->kode);
				return;
			}
			$data['kartu_keluarga']  = $kk['path'];
			$data['ktp_wakil']       = $ktp['path'];
			$data['surat_kuasa']     = null;
			$data['surat_izin_huni'] = null;
		} else {
			// hadir/tidak -> kosongkan semua berkas wakil
			$data['surat_kuasa']     = null;
			$data['ktp_wakil']       = null;
			$data['surat_izin_huni'] = null;
			$data['kartu_keluarga']  = null;
		}

		// QR token: untuk yang akan hadir fisik (hadir/dikuasakan/diwakilkan). 'tidak' -> null.
		// checkin_status/checkin_time TIDAK PERNAH disentuh di sini — peserta yang
		// sudah check-in sudah ditolak di atas, dan data check-in adalah catatan
		// petugas, bukan milik unit.
		if ($kehadiran === 'tidak') {
			$data['qr_token'] = null;
		} else {
			$data['qr_token'] = ($peserta && !empty($peserta->qr_token))
				? $peserta->qr_token
				: bin2hex(random_bytes(16));
		}

		if ($peserta) {
			$this->db->where('id_peserta', $peserta->id_peserta)->update('acara_peserta', $data);
		} else {
			$this->db->insert('acara_peserta', $data);
		}

		$this->pesan->pesan_success('Terima kasih, konfirmasi kehadiran Anda tersimpan.');
		redirect('acara/detail/' . $acara->kode);
	}

	/* ===================== VOTING (penghuni) ===================== */

	/**
	 * Halaman voting acara: daftar voting (buka/tutup) + status pilihan unit.
	 */
	public function voting($kode = '')
	{
		if ($kode === '') show_404();
		$id_bast = $this->session->id_bast;

		$acara = $this->db->from('acara')->where('kode', $kode)->where('hapus', 0)
			->where_in('status', array(1, 2))->get()->row();
		if (!$acara) show_404();

		$votings = $this->db->from('acara_voting')->where('id_acara', $acara->id_acara)
			->where('hapus', 0)->where_in('status', array(1, 2))
			->order_by('id_voting', 'ASC')->get()->result();

		foreach ($votings as $v) {
			$v->opsi = $this->db->from('acara_voting_opsi')->where('id_voting', $v->id_voting)
				->where('hapus', 0)->order_by('urutan', 'ASC')->get()->result();
			$suara = $this->db->select('id_opsi')->from('acara_voting_suara')
				->where('id_voting', $v->id_voting)->where('id_bast', $id_bast)
				->where('hapus', 0)->get()->result();
			$v->pilihan = array();
			foreach ($suara as $s) $v->pilihan[] = $s->id_opsi;
			$v->sudah = count($v->pilihan) > 0;
			$v->buka  = $this->_voting_buka($v);
			list($v->label_tutup, $v->alasan_tutup) = $this->_voting_alasan_tutup($v);
		}

		$data['judul']   = 'Voting: ' . $acara->nama;
		$data['page']    = 'acara/voting';
		$data['acara']   = $acara;
		$data['votings'] = $votings;
		$data['sudah_rsvp'] = $this->_sudah_rsvp($acara->id_acara, $id_bast);
		$this->load->view('home', $data);
	}

	/**
	 * Unit wajib mengonfirmasi kehadiran (RSVP) dulu sebelum boleh memilih —
	 * pilihan apa pun (hadir/tidak/diwakilkan) dianggap sudah memutuskan.
	 */
	private function _sudah_rsvp($id_acara, $id_bast)
	{
		return $this->db->where('id_acara', $id_acara)->where('id_bast', $id_bast)
			->where('hapus', 0)->count_all_results('acara_peserta') > 0;
	}

	/**
	 * Simpan suara. 1 unit = 1 suara per voting. single: 1 opsi, multi: >=1 opsi.
	 */
	public function vote()
	{
		$id_bast = $this->session->id_bast;
		$id_voting = (int) $this->input->post('id_voting');
		$v = $this->db->from('acara_voting')->where('id_voting', $id_voting)->where('hapus', 0)->get()->row();
		if (!$v) {
			$this->pesan->pesan_danger('Voting tidak ditemukan.');
			redirect('acara');
			return;
		}
		// Gate RSVP juga di sisi server, bukan cuma menyembunyikan form di view.
		if (!$this->_sudah_rsvp($v->id_acara, $id_bast)) {
			$this->pesan->pesan_warning('Konfirmasi kehadiran Anda dulu sebelum memberikan suara.');
			redirect('acara/detail/' . $this->_acara_kode($v->id_acara));
			return;
		}
		if (!$this->_voting_buka($v)) {
			$this->pesan->pesan_warning('Voting sudah ditutup.');
			redirect('acara/voting/' . $this->_acara_kode($v->id_acara));
			return;
		}
		$sudah = $this->db->where(array('id_voting' => $id_voting, 'id_bast' => $id_bast, 'hapus' => 0))
			->count_all_results('acara_voting_suara');
		if ($sudah > 0) {
			$this->pesan->pesan_warning('Anda sudah memberikan suara pada voting ini.');
			redirect('acara/voting/' . $this->_acara_kode($v->id_acara));
			return;
		}

		$opsi = $this->input->post('opsi');
		if (!is_array($opsi)) $opsi = ($opsi !== null && $opsi !== '') ? array($opsi) : array();
		if ($v->tipe === 'single') $opsi = array_slice($opsi, 0, 1);
		$opsi = array_values(array_unique(array_filter(array_map('intval', $opsi))));
		if (empty($opsi)) {
			$this->pesan->pesan_warning('Silakan pilih jawaban terlebih dahulu.');
			redirect('acara/voting/' . $this->_acara_kode($v->id_acara));
			return;
		}

		$ip = $_SERVER['REMOTE_ADDR'];
		$browser = isset($_SERVER['HTTP_USER_AGENT']) ? substr($_SERVER['HTTP_USER_AGENT'], 0, 250) : '';
		$n = 0;
		foreach ($opsi as $oid) {
			$valid = $this->db->where(array('id_opsi' => $oid, 'id_voting' => $id_voting, 'hapus' => 0))
				->count_all_results('acara_voting_opsi');
			if ($valid > 0) {
				$this->db->insert('acara_voting_suara', array(
					'id_voting' => $id_voting, 'id_opsi' => $oid, 'id_bast' => $id_bast,
					'ip' => $ip, 'browser' => $browser, 'tm_pilih' => date('Y-m-d H:i:s'),
				));
				$n++;
			}
		}
		if ($n > 0) $this->pesan->pesan_success('Terima kasih, suara Anda tersimpan.');
		else $this->pesan->pesan_danger('Opsi tidak valid.');
		redirect('acara/voting/' . $this->_acara_kode($v->id_acara));
	}

	/**
	 * Hasil voting realtime (JSON) — hanya bila voting.tampil_hasil = 1.
	 */
	public function voting_hasil_ajax($id_voting)
	{
		$v = $this->db->select('tampil_hasil')->from('acara_voting')
			->where('id_voting', $id_voting)->where('hapus', 0)->get()->row();
		if (!$v || (int) $v->tampil_hasil !== 1) {
			$this->_json(array('status' => false, 'hidden' => true));
			return;
		}
		$rows = $this->db->select('o.teks_opsi')->select('IFNULL(c.jml,0) as jml', false)
			->from('acara_voting_opsi o')
			->join('(SELECT id_opsi, COUNT(1) jml FROM acara_voting_suara WHERE id_voting=' . (int) $id_voting . ' AND hapus=0 GROUP BY id_opsi) c', 'c.id_opsi = o.id_opsi', 'left')
			->where('o.id_voting', $id_voting)->where('o.hapus', 0)->order_by('o.urutan', 'ASC')->get()->result();
		$pemilih = $this->db->select('COUNT(DISTINCT id_bast) t', false)->from('acara_voting_suara')
			->where('id_voting', $id_voting)->where('hapus', 0)->get()->row();
		$this->_json(array('status' => true, 'pemilih' => (int) ($pemilih ? $pemilih->t : 0), 'opsi' => $rows));
	}

	private function _voting_buka($v)
	{
		if ((int) $v->status !== 1) return false;
		$now = time();
		if (!empty($v->waktu_buka) && $v->waktu_buka !== '0000-00-00 00:00:00' && strtotime($v->waktu_buka) > $now) return false;
		if (!empty($v->waktu_habis) && $v->waktu_habis !== '0000-00-00 00:00:00' && strtotime($v->waktu_habis) < $now) return false;
		return true;
	}

	/**
	 * Alasan voting tak bisa dipilih — supaya penghuni tidak cuma melihat "Ditutup"
	 * tanpa keterangan (mis. waktu buka masih di depan, atau waktu habis sudah lewat).
	 * Mengembalikan array(label singkat utk badge, keterangan lengkap).
	 */
	private function _voting_alasan_tutup($v)
	{
		$now = time();
		$ada = function ($t) { return !empty($t) && $t !== '0000-00-00 00:00:00'; };
		$fmt = function ($t) { return date('d M Y H:i', strtotime($t)); };

		if ((int) $v->status !== 1) {
			return array('Ditutup', 'Voting ini sudah ditutup oleh pengurus.');
		}
		if ($ada($v->waktu_buka) && strtotime($v->waktu_buka) > $now) {
			return array('Belum Dibuka', 'Voting dibuka pada ' . $fmt($v->waktu_buka) . '.');
		}
		if ($ada($v->waktu_habis) && strtotime($v->waktu_habis) < $now) {
			return array('Berakhir', 'Waktu voting berakhir pada ' . $fmt($v->waktu_habis) . '.');
		}
		return array('Ditutup', 'Voting ini sudah ditutup.');
	}

	private function _acara_kode($id_acara)
	{
		$a = $this->db->select('kode')->from('acara')->where('id_acara', $id_acara)->get()->row();
		return $a ? $a->kode : '';
	}

	private function _json($arr)
	{
		if (ob_get_length()) @ob_clean();
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($arr);
	}

	/**
	 * Boleh RSVP bila acara publish (status=1) & belum lewat batas_rsvp.
	 */
	/**
	 * Peserta yang SUDAH check-in tidak boleh lagi mengubah RSVP-nya sendiri —
	 * mengubah jadi "tidak" akan menghapus bukti kehadiran & QR token yang sudah
	 * dipakai petugas. Perubahan setelah check-in hanya lewat admin (bmsdev).
	 */
	private function _sudah_checkin($peserta)
	{
		return $peserta && (int) $peserta->checkin_status === 1;
	}

	private function _bisa_rsvp($acara)
	{
		if ((int) $acara->status !== 1) return false;
		if (!empty($acara->batas_rsvp) && $acara->batas_rsvp !== '0000-00-00 00:00:00') {
			if (strtotime($acara->batas_rsvp) < time()) return false;
		}
		return true;
	}

	/**
	 * Simpan berkas kuasa/KTP ke BMS_UPLOAD_PATH/acara_kuasa/{kode}/.
	 * Return ['path'=>rel|null, 'error'=>string|null]. Bila tak ada upload baru,
	 * kembalikan $lama (pertahankan file sebelumnya).
	 */
	private function _simpan_file($key, $kode, $prefix, $id_bast, $lama = null)
	{
		if (empty($_FILES[$key]['name']) || $_FILES[$key]['error'] === UPLOAD_ERR_NO_FILE) {
			return array('path' => $lama, 'error' => null);
		}
		$f = $_FILES[$key];
		if ($f['error'] !== UPLOAD_ERR_OK) {
			return array('path' => null, 'error' => 'Gagal mengunggah berkas. Coba lagi.');
		}
		if ($f['size'] > self::FILE_MAX_BYTES) {
			return array('path' => null, 'error' => 'Ukuran berkas melebihi 5 MB.');
		}
		$ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
		if (!in_array($ext, $this->file_ext, true)) {
			return array('path' => null, 'error' => 'Format berkas harus PDF, JPG, atau PNG.');
		}
		if (!$this->_cek_magic_bytes($f['tmp_name'], $ext)) {
			return array('path' => null, 'error' => 'Isi berkas tidak sesuai format PDF/JPG/PNG.');
		}

		$kode_aman = preg_replace('/[^A-Za-z0-9_-]/', '', $kode);
		$reldir = 'acara_kuasa/' . $kode_aman . '/';
		$absdir = BMS_UPLOAD_PATH . $reldir;
		if (!is_dir($absdir) && !@mkdir($absdir, 0775, true)) {
			return array('path' => null, 'error' => 'Gagal menyiapkan folder penyimpanan. Hubungi manajemen.');
		}
		$fname = $prefix . '-' . $kode_aman . '-' . (int) $id_bast . '-' . time() . '.' . $ext;
		if (!move_uploaded_file($f['tmp_name'], $absdir . $fname)) {
			return array('path' => null, 'error' => 'Gagal menyimpan berkas. Coba lagi.');
		}
		// hapus file lama bila diganti
		if (!empty($lama) && $lama !== $reldir . $fname) {
			$old = BMS_UPLOAD_PATH . $lama;
			if (is_file($old)) @unlink($old);
		}
		return array('path' => $reldir . $fname, 'error' => null);
	}

	/** Cek header byte asli file sesuai ekstensi (tanpa ext-fileinfo). */
	private function _cek_magic_bytes($tmp_path, $ext)
	{
		$fh = @fopen($tmp_path, 'rb');
		if (!$fh) return false;
		$head = fread($fh, 8);
		fclose($fh);
		if (substr($head, 0, 4) === '%PDF') return $ext === 'pdf';
		if (substr($head, 0, 3) === "\xFF\xD8\xFF") return in_array($ext, array('jpg', 'jpeg'), true);
		if (substr($head, 0, 8) === "\x89PNG\r\n\x1a\n") return $ext === 'png';
		return false;
	}
}
