<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Fo extends CI_Controller
{


	var $modul = "fo";

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

		$data['judul'] = "FittOut " . $tabs;
		$data['tabs'] = $tabs;
		$data['jatuh_tempo'] = isset($_POST['jatuh_tempo']) ? $_POST['jatuh_tempo'] : 0;
		$data['tombol_view'] = "";
		if ($tabs == 'open') {
			$data['tombol_view'] .= $this->apl->anchor('<a href="' . site_url('tiket/fo/add') . '" 
class="btn btn-neutral">
<i class="fa fa-plus"></i> Added FitOut</a>', 'tambah ' . $this->modul, $this->modul);
			$data['post'] = 1;
		}
		if ($tabs == 'payment') {
			$data['post'] = 2;
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


		$data['tipe'] = '3';


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

		$data['tombol_view'] = "";
		$data['page'] = 'tiket/view'; //Halaman di tampilkan
		$this->load->view('home', $data);

	}

	public function edit()
	{
		$id_tiket = $this->input->get('id');
		$data['judul'] = "Edit Fitt Out";
		$data['page'] = 'tiket/form_fo'; //Halaman di tampilkan
		$data['tiket'] = $this->apl->getSelectedData("tiket", array('id_tiket' => $id_tiket))->row();
		$data['pekerjaan'] = $this->apl->getSelectedData("db_pekerjaan", array('hapus' => 0));
		$data['detail'] = $this->apl->getSelectedData("tiket_detail", array(
			'hapus' => 0,
			'id_tiket' => $id_tiket,
			'tipe' => 0,
		))->result();
		$data['biaya'] = $this->apl->getSelectedData("tiket_detail", array(
			'hapus' => 0,
			'id_tiket' => $id_tiket,
			'tipe' => 1
		))->row();

		$data['submit'] = 'edit'; //Halaman di tampilkan
		$this->load->view('home', $data);

	}

	public function add()
	{
		$data['judul'] = "Add Data Fitt Out";
		$data['page'] = 'tiket/form_fo'; //Halaman di tampilkan
		$data['submit'] = 'tambah'; //Halaman di tampilkan
		$data['no_form'] = $this->apl->counter_view("FITOut/EPR-TR") . "/" . $this->apl->counter_code("FITOut/EPR-TR");
		$data['pekerjaan'] = $this->apl->getSelectedData("db_pekerjaan", array('hapus' => 0));
		$data['biaya'] = array();
		$this->load->view('home', $data);

	}

	public function form_berkas()
	{
		$data['id_vendor'] = $this->input->post('id_vendor');
		$data['berkas'] = $this->tiket->getBerkasVendor($data['id_vendor'])->result();
		$this->load->view('tiket/view_berkas', $data);
	}

	public function actions()
	{
		$submit = $this->input->post('submit');
		$id_bast = $this->input->post('id_bast');
		$id_pemilik = $this->apl->get_nilai_pilih("bast", "id_pemilik", "id_bast=" . $id_bast);
		$id_unit = $this->apl->get_nilai_pilih("bast", "id_unit", "id_bast=" . $id_bast);
		$kode_unit = $this->apl->get_nilai_pilih("db_unit", "kode", "id_unit=" . $id_unit);

		$data = array(
			'id_bast' => $id_bast,
			'id_pemilik' => $id_pemilik,
			'tanggal' => $this->input->post('tanggal'),
			'tipe' => 3,
			'no_form' => $this->input->post('no_form'),
			'pelapor' => $this->input->post('pelapor'),
			'kontak' => $this->input->post('kontak'),
			'via' => $this->input->post('via'),
			'ket' => $this->input->post('ket'),
			'id_vendor' => $this->input->post('id_vendor'),
			'email' => $this->input->post('email'),

		);


		if (isset($_FILES["lainnya"]) && !empty($_FILES["lainnya"]["name"])) {
			{
				$tempName = $_FILES['lainnya']['tmp_name'];
				$fileName = $_FILES['lainnya']['name'];
				$fileName = $this->upload_model->fileName($fileName);
				$targetFile = $this->upload_model->targetFile($tempName, $fileName,
					$this->apl->get_nilai_pilih("db_upload", "folder", "id_upload=5"));
				$this->upload_model->upload_resize($targetFile);
				$data = array_merge($data, array(
					'kebijakan' => 1,
					'lainnya' => $fileName,
				));
				//$data = array_merge($data, array('file' => $fileName));
			}
		}

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

			$id_pekerjaan = $this->input->post('id_pekerjaan');
			$nama = $this->input->post('nama');
			for ($i = 0; $i < count($id_pekerjaan); $i++) {
				$this->apl->insertData("tiket_detail",
					array(
						'id_tiket' => $id_tiket,
						'tipe' => 0,
						'id_pekerjaan' => $id_pekerjaan[$i],
						'nama' => $nama[$id_pekerjaan[$i]],
					)
				);
			}

			$id_tag = $this->input->post('id_tag');
			$jumlah = $this->apl->number_format($this->input->post('jumlah'));
			$deposit = $this->apl->number_format($this->input->post('deposit'));

			$this->apl->insertData("tiket_detail",
				array(
					'id_tiket' => $id_tiket,
					'tipe' => 1,
					'nama' => $this->apl->get_nilai_pilih("db_tag", "nama", array('id_tag' => $id_tag)),
					'id_tag' => $id_tag,
					'jumlah' => $jumlah,
					'deposit' => $deposit,
					'material' => '0',
					'qty' => '1',
					'harga_satuan' => $jumlah,
				)
			);

			$this->apl->counter("FITOut/EPR-TR");

			$this->pesan->pesan_success("Successfully Added FO UNIT " . $kode_unit);
			$this->apl->insertData("tiket", $data);
			$this->apl->log("TAMBAH", "", json_encode($data), "tiket", $id_tiket);
		}

		/**
		 * Jika Edit Data
		 */
		if ($submit == 'edit') {
			$id_tiket = $this->input->post('id_tiket');


			$this->apl->updateData("tiket_detail", array('hapus' => 1), array(
				'id_tiket' => $id_tiket, 'tipe' => 0));
			$id_pekerjaan = $this->input->post('id_pekerjaan');
			$nama = $this->input->post('nama');
			for ($i = 0; $i < count($id_pekerjaan); $i++) {
				$this->apl->insertData("tiket_detail",
					array(
						'id_tiket' => $id_tiket,
						'tipe' => 0,
						'id_pekerjaan' => $id_pekerjaan[$i],
						'nama' => $nama[$id_pekerjaan[$i]],
					)
				);
			}

			$this->apl->updateData("tiket_detail", array('hapus' => 1),
				array('id_tiket' => $id_tiket, 'tipe' => 1));
			$id_tag = $this->input->post('id_tag');
			$jumlah = $this->apl->number_format($this->input->post('jumlah'));
			$deposit = $this->apl->number_format($this->input->post('deposit'));

			$this->apl->insertData("tiket_detail",
				array(
					'id_tiket' => $id_tiket,
					'tipe' => 1,
					'nama' => $this->apl->get_nilai_pilih("db_tag", "nama", array('id_tag' => $id_tag)),
					'id_tag' => $id_tag,
					'jumlah' => $jumlah,
					'deposit' => $deposit,
					'material' => '0',
					'qty' => '1',
					'harga_satuan' => $jumlah,
				)
			);

			$this->apl->log("UPDATE", json_encode($this->apl->getSelectedData("tiket",
					array('id_tiket' => $id_tiket))->row())
				, json_encode($data), "tiket", $this->input->post('id_tiket'));
			$this->apl->updateData("tiket", $data, array('id_tiket' => $id_tiket));
			$this->pesan->pesan_success("Successfully Added DEFECT UNIT  " . $kode_unit);
		}


		$this->apl->updateData("tiket_berkas", array('hapus' => 1),
			array('id_tiket' => $id_tiket,
				//'id_upload' => $id_upload[$i],
			));


		foreach ($this->apl->getSelectedData('vendor_berkas', array('id_vendor' => $this->input->post('id_vendor')))->result() as $v) {
			$this->apl->insertData("tiket_berkas", array(
				'file' => $v->file,
				'nama' => $v->nama,
				'id_upload' => $v->id_upload,
				'nomor' => $v->nomor,
				'id_tiket' => $id_tiket,
				'id_admin' => $this->session->id_admin,
			));
		}


		if (isset($_POST['simpan'])) {
			$this->apl->updateData("tiket", array('post' => 1), array('id_tiket' => $id_tiket));
			redirect('tiket/fo/view/open');
		}

		if (isset($_POST['payment'])) {
			$this->apl->updateData("tiket", array('post' => 2), array('id_tiket' => $id_tiket));
			redirect('tiket/fo/view/payment');
		}
	}

	public function cetak()
	{
		$id_tiket = $this->input->get('id');

		$data['t'] = $this->apl->getSelectedData("tiket", "id_tiket=" . $id_tiket)->row();
		$data['d'] = $this->apl->getSelectedData("tiket_detail",
			array('id_tiket' => $id_tiket, 'tipe' => 1, 'hapus' => 0))->result();

		$bayar = $this->apl->getSelectedData("tiket_detail",
			array('id_tiket' => $id_tiket, 'tipe' => 2, 'hapus' => 0))->row();
		$data['b'] = (isset($bayar)) ? $this->apl->getSelectedData("bayar", "id_bayar=" . $bayar->id_bayar)->row()
			: array();
		$data['h'] = $this->apl->getSelectedData("tiket_histori", "id_tiket=" . $id_tiket)->result();

		$data['proses']='';
		$this->load->view('tiket/cetak', $data);

	}

}
