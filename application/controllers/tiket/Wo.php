<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Wo extends CI_Controller
{


	var $modul = "wo";

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

		$data['judul'] = "Data Work Order " . strtoupper($tabs);
		$data['tabs'] = $tabs;
		$data['jatuh_tempo'] = isset($_POST['jatuh_tempo']) ? $_POST['jatuh_tempo'] : 0;

		$data['tombol_view'] = "";
		if ($tabs == 'open') {
			/*
			$data['tombol_view'] .= '<a href="' . site_url('tiket/wo/add') . '"
class="btn btn-neutral">
<i class="fa fa-plus"></i> Add </a>';
			*/
			$data['post'] = 1;
		}
		if ($tabs == 'payment') $data['post'] = 2;
		if ($tabs == 'proses') $data['post'] = 3;
		if ($tabs == 'done') $data['post'] = 4;
		if ($tabs == 'close') $data['post'] = 5;

		$data['tipe'] = '5';


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


		$data['field'] = $this->tiket->getTiket($data['tipe'], $data['post'])->get()->list_fields();
		$data['page'] = 'tiket/view'; //Halaman di tampilkan
		$this->load->view('home', $data);

	}

	public function edit()
	{
		$id_tiket = $this->input->get('id');
		$data['judul'] = "Edit Work Order";
		$data['page'] = 'tiket/form_wo'; //Halaman di tampilkan
		$data['tiket'] = $this->apl->getSelectedData("tiket", array('id_tiket' => $id_tiket))->row();

		$data['pekerjaan'] = $this->apl->getSelectedData("tiket_detail", array(
			'hapus' => 0,
			'id_tiket' => $id_tiket,
			'tipe' => 1
		))->row();
		$data['submit'] = 'edit'; //Halaman di tampilkan
		$this->load->view('home', $data);

	}

	public function add()
	{
		$data['judul'] = "Add Work Order";
		$data['page'] = 'tiket/form_wo'; //Halaman di tampilkan
		$data['submit'] = 'tambah'; //Halaman di tampilkan
		$data['no_form'] =
			$this->apl->counter_view("WO/EPR-TR") . "/"
			. $this->apl->counter_code("WO/EPR-TR");
		/*
		$data['pekerjaan'] = $this->apl->getSelectedData("acc_jurnal_umum",
			array('id_jurnal' => '', 'hapus' => 0))->result();
		*/
		$data['pekerjaan'] = array();
		$this->load->view('home', $data);

	}

	public function actions()
	{
		$submit = $this->input->post('submit');
		$id_bast = $this->input->post('id_bast');
		$id_pemilik = $this->apl->get_nilai_pilih("bast", "id_pemilik", "id_bast=" . $id_bast);
		$id_unit = $this->apl->get_nilai_pilih("bast", "id_unit", "id_bast=" . $id_bast);
		$kode_unit = $this->apl->get_nilai_pilih("db_unit", "kode", "id_unit=" . $id_unit);
		$rab = $this->input->post('rab');

		$data = array(
			'id_bast' => $id_bast,
			'id_pemilik' => $id_pemilik,
			'tanggal' => $this->input->post('tanggal'),
			'tipe' => 5,
			'no_form' => $this->input->post('no_form'),
			'pelapor' => $this->input->post('pelapor'),
			'kontak' => $this->input->post('kontak'),
			'via' => $this->input->post('via'),
			'ket' => $this->input->post('ket'),
			'email' => $this->input->post('email'),
			'id_dep' => $this->input->post('id_dep'),
			'rab' => $this->input->post('rab'),

			//'rab' => $this->input->post('rab'),
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

			$this->pesan->pesan_success("Successfully Added Work Order UNIT " . $kode_unit);
			$this->apl->insertData("tiket", $data);
			$this->apl->counter("WO/EPR-TR");

			$id_tag = $this->input->post('id_tag');


			$jumlah = $this->apl->number_format($this->input->post('jumlah'));
			$qty = $this->apl->number_format($this->input->post('qty'));
			$material = $this->apl->number_format($this->input->post('material'));
			$deposit = $this->apl->number_format($this->input->post('deposit'));
			$harga_satuan = $this->apl->number_format($this->input->post('harga_satuan'));
			if ($rab == 0) {
				$this->apl->insertData("tiket_detail",
					array(
						'id_tiket' => $id_tiket,
						'tipe' => 1,
						'nama' => $this->apl->get_nilai_pilih("db_tag", "nama", array('id_tag' => $id_tag)),
						'id_tag' => $id_tag,
						'jumlah' => $jumlah,
						'deposit' => $deposit,
						'material' => $material,
						'qty' => $qty,
						'harga_satuan' => $jumlah / $qty,
					)
				);
			}
			/*
			for ($i = 0; $i < count($id_tag); $i++) {
				$this->apl->insertData("tiket_detail",
					array(
						'id_tiket' => $id_tiket,
						'tipe' => 1,
						'nama' => $this->apl->get_nilai_pilih("db_tag", "nama", array('id_tag' => $id_tag[$i])),
						'id_tag' => $id_tag[$i],
						'jumlah' => $jumlah[$i],
						'harga_satuan' => $harga_satuan[$i],
					)
				);
			}
			*/

			$this->apl->log("TAMBAH", "", json_encode($data), "tiket", $id_tiket);
		}

		/**
		 * Jika Edit Data
		 */
		if ($submit == 'edit') {
			$id_tiket = $this->input->post('id_tiket');
			$this->apl->updateData("tiket_detail", array('hapus' => 1),
				array('id_tiket' => $id_tiket, 'tipe' => 1));
			//$id_tag = $this->input->post('id_tag');
			//$jumlah = $this->input->post('jumlah');
			//$harga_satuan = $this->input->post('harga_satuan');


			$id_tag = $this->input->post('id_tag');
			$jumlah = $this->apl->number_format($this->input->post('jumlah'));
			$material = $this->apl->number_format($this->input->post('material'));
			$deposit = $this->apl->number_format($this->input->post('deposit'));
			$qty = $this->apl->number_format($this->input->post('qty'));
			if ($rab == 0) {

				$this->apl->insertData("tiket_detail",
					array(
						'id_tiket' => $id_tiket,
						'tipe' => 1,
						'nama' => $this->apl->get_nilai_pilih("db_tag", "nama", array('id_tag' => $id_tag)),
						'id_tag' => $id_tag,
						'jumlah' => $jumlah,
						'deposit' => $deposit,
						'material' => $material,
						'qty' => $qty,
						'harga_satuan' => $jumlah / $qty,

					)
				);
			}
			/*
			for ($i = 0; $i < count($id_tag); $i++) {
				$this->apl->insertData("tiket_detail",
					array(
						'id_tiket' => $id_tiket,
						'tipe' => 1,
						'id_tag' => $id_tag[$i],
						'nama' => $this->apl->get_nilai_pilih("db_tag",
							"nama", array('id_tag' => $id_tag[$i])),
						'jumlah' => $jumlah[$i],
						'harga_satuan' => $harga_satuan[$i],

					)
				);
			}
			*/

			$this->apl->log("UPDATE", json_encode($this->apl->getSelectedData("tiket",
					array('id_tiket' => $id_tiket))->row())
				, json_encode($data), "tiket", $this->input->post('id_tiket'));
			$this->apl->updateData("tiket", $data, array('id_tiket' => $id_tiket));
			$this->pesan->pesan_success("Successfully Added Work Order UNIT  " . $kode_unit);
		}

		if (isset($_POST['simpan'])) {
			$this->apl->updateData("tiket", array('post' => 1), array('id_tiket' => $id_tiket));
			redirect('tiket/wo/view/open');
		}
		if (isset($_POST['payment'])) {
			$this->apl->updateData("tiket", array('post' => 2), array('id_tiket' => $id_tiket));
			redirect('tiket/wo/view/payment');
		}
	}

	public function cetak()
	{
		$id_tiket = $this->input->get('id');

		$data['t'] = $this->apl->getSelectedData("tiket", "id_tiket=" . $id_tiket)->row();
		$data['d'] = $this->apl->getSelectedData("tiket_detail",
			array('id_tiket' => $id_tiket, 'tipe' => 1, 'hapus' => 0))->result();

		$data['judul'] = 'FORM REQUEST Work ORder';
		$bayar = $this->apl->getSelectedData("tiket_detail",
			array('id_tiket' => $id_tiket, 'tipe' => 2, 'hapus' => 0))->row();
		$data['b'] = (isset($bayar)) ? $this->apl->getSelectedData("bayar", "id_bayar=" . $bayar->id_bayar)->row()
			: array();
		$data['h'] = "";
		$data['a'] = "";
		$data['berkas'] = "";
		$data['proses'] = "";
		$this->load->view('tiket/cetak_wo', $data);

	}

}
