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
				->select('id_detail, tahun, tagihan, file_pdf, tgl_upload')
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

		$file_path = BMS_UPLOAD_PATH . 'pbb_pdf/' . $detail->file_pdf;

		if (!file_exists($file_path)) {
			$this->pesan->pesan_danger('File PDF tidak ditemukan. Hubungi manajemen.');
			redirect('pbb');
			return;
		}

		$filename = 'PBB_' . $this->session->username . '_' . $detail->tahun . '.pdf';

		header('Content-Type: application/pdf');
		header('Content-Disposition: inline; filename="' . $filename . '"');
		header('Content-Length: ' . filesize($file_path));
		header('Cache-Control: private, max-age=0, must-revalidate');
		readfile($file_path);
		exit;
	}
}
