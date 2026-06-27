<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Corrective extends CI_Controller
{


	var $modul = "corrective";

	function __construct()
	{
		parent::__construct();
		$this->load->model('Crud_Model', 'crud_model');
		$this->crud_model = new Crud_Model();

		$this->load->model('Tiket_Model', 'tiket');
		$this->tiket = new Tiket_Model();

		$this->apl = new Apl();
		$this->tombol = new Tombol();
		$this->pesan = new Pesan();


	}

	public function view($tabs = 'open')
	{

		$data['judul'] = "Data Corrective Maintenance " . strtoupper($tabs);
		$data['tabs'] = $tabs;
		$data['jatuh_tempo'] = isset($_POST['jatuh_tempo']) ? $_POST['jatuh_tempo'] : 0;
		$data['tombol_view'] = "";
		if ($tabs == 'open') {
			$data['tombol_view'] .= $this->apl->anchor('<a href="' . site_url('tiket/corrective/add') . '" 
class="btn btn-neutral">
<i class="fa fa-plus"></i> Add </a>', 'tambah ' . $this->modul, $this->modul);

			$data['post'] = 1;
		}
		if ($tabs == 'proses') {
			$data['post'] = 3;
		}
		if ($tabs == 'done') {
			$data['post'] = 4;
		}
		if ($tabs == 'close') {
			$data['post'] = 5;
		}

		if ($tabs == 'reject') $data['post'] = 10;

		$data['tipe'] = '7';


		$data['tot'] = $this->db
			->select("SUM(IF(post=1,1,0)) as open")
			->select("SUM(IF(post=3,1,0)) as proses")
			->select("SUM(IF(post=4,1,0)) as done")
			->from('tiket')
			->where(
				array(
					'tiket.hapus' => 0,
					'tiket.tipe' => $data['tipe'],)
			)->get()->row();
		$data['tombol_view'] .= '<a href="' . site_url('tiket/ajax/export_csv/' . $data['tipe'] . '?post=' . $data['post']) . '" 
class="btn btn-info" target="_blank">
<i class="fa fa-file-excel"></i> Export</a>';

		$data['field'] = $this->tiket->getTiket($data['tipe'], $data['post'])->get()->list_fields();

		$data['page'] = 'tiket/view'; //Halaman di tampilkan
		$this->load->view('home', $data);

	}

	public function edit()
	{
		$id_tiket = $this->input->get('id');
		$data['judul'] = "Edit Corrective";
		$data['page'] = 'tiket/form_corrective'; //Halaman di tampilkan
		$data['tiket'] = $this->apl->getSelectedData("tiket", array('id_tiket' => $id_tiket))->row();
		$data['pekerjaan'] = $this->apl->getSelectedData("db_pekerjaan", array('hapus' => 0));
		$data['detail'] = $this->apl->getSelectedData("tiket_detail", array(
			'hapus' => 0,
			'id_tiket' => $id_tiket,
			'tipe' => 0,
		))->row();
		$data['asset'] = $this->apl->getSelectedData("asset", array(

			'id_asset' => $data['detail']->id_asset,
		))->row();
		$data['submit'] = 'edit'; //Halaman di tampilkan
		$this->load->view('home', $data);

	}

	public function add()
	{
		$data['judul'] = "Tambah Data General";
		$data['page'] = 'tiket/form_corrective'; //Halaman di tampilkan
		$data['submit'] = 'tambah'; //Halaman di tampilkan
		$data['no_form'] = $this->apl->counter_view("COR/EPR") . "/" . $this->apl->counter_code("COR/EPR");
		//$data['pekerjaan'] = $this->apl->getSelectedData("db_pekerjaan", array('hapus' => 0));
		$this->load->view('home', $data);

	}

	public function actions()
	{
		$submit = $this->input->post('submit');

		$dari_dep = $this->apl->get_nilai_pilih("karyawan_relasi", "id_departemen", array(
			'hapus' => 0,
			'id_karyawan' => $this->session->id_karyawan,
		));

		$data = array(
			'tanggal' => $this->input->post('tanggal'),
			'tipe' => 7,
			'no_form' => $this->input->post('no_form'),
			'pelapor' => $this->input->post('pelapor'),
			'id_dep' => $this->input->post('id_dep'),
			'ket' => $this->input->post('ket'),
			'dari_dep' => ($dari_dep) ? $dari_dep : '',
		);


		/**
		 * Jika Tambah Data
		 */
		if ($submit == 'tambah') {
			$id_tiket = $this->apl->urut("tiket", "id_tiket");
			$data = array_merge($data, array(
				'qr' => $this->apl->get_nilai_pilih("set_ttd", "qr", array('nama' => 'REQUEST')),
				'id_tiket' => $id_tiket,
				'id_admin' => $this->session->id_admin,
			));

			$this->apl->insertData("tiket_detail",
				array(
					'id_tiket' => $id_tiket,
					'tipe' => 0,
					'id_asset' => $this->input->post('id_asset'),
					'nama' => $this->input->post('ket'),
				)
			);

			$this->apl->counter('COR/EPR');
			$this->pesan->pesan_success("Successfully Added ");
			$this->apl->insertData("tiket", $data);
			$this->apl->log("TAMBAH", "", json_encode($data), "tiket", $id_tiket);
		}

		/**
		 * Jika Edit Data
		 */
		if ($submit == 'edit') {
			$id_tiket = $this->input->post('id_tiket');

			$this->apl->updateData("tiket_detail", array('hapus' => 1),
				array('id_tiket' => $id_tiket, 'tipe' => 0));

			$this->apl->insertData("tiket_detail",
				array(
					'id_tiket' => $id_tiket,
					'tipe' => 0,
					'id_asset' => $this->input->post('id_asset'),
					'nama' => $this->input->post('ket'),
				)
			);

			$this->apl->log("UPDATE", json_encode($this->apl->getSelectedData("tiket",
					array('id_tiket' => $id_tiket))->row())
				, json_encode($data), "tiket", $this->input->post('id_tiket'));
			$this->apl->updateData("tiket", $data, array('id_tiket' => $id_tiket));
			$this->pesan->pesan_success("Successfully Update");
		}

		if (isset($_POST['simpan'])) {
			$this->apl->updateData("tiket", array('post' => 1), array('id_tiket' => $id_tiket));
			redirect('tiket/corrective/view/open');
		}
		if (isset($_POST['proses'])) {
			$this->apl->updateData("tiket", array('post' => 3), array('id_tiket' => $id_tiket));
			redirect('tiket/corrective/view/proses');
		}
	}

	public function cetak()
	{
		$id_tiket = $this->input->get('id');

		$data['t'] = $this->apl->getSelectedData("tiket", "id_tiket=" . $id_tiket)->row();
		$data['d'] = $this->apl->getSelectedData("tiket_detail",
			array('id_tiket' => $id_tiket, 'tipe' => 1, 'hapus' => 0))->result();
		$data['p'] = $this->apl->getSelectedData("tiket_detail",
			array('id_tiket' => $id_tiket, 'tipe' => 0, 'hapus' => 0))->result();

		$data['judul'] = 'Form General';
		$bayar = $this->apl->getSelectedData("tiket_detail",
			array('id_tiket' => $id_tiket, 'tipe' => 2, 'hapus' => 0))->row();
		$data['b'] = (isset($bayar)) ? $this->apl->getSelectedData("bayar", "id_bayar=" . $bayar->id_bayar)->row()
			: array();
		$data['h'] = $this->apl->getSelectedData("tiket_histori", "id_tiket=" . $id_tiket)->result();
		$data['a'] = "";
		$data['berkas'] = "";
		$data['proses'] = "";

		$this->load->view('tiket/cetak', $data);

	}


}
