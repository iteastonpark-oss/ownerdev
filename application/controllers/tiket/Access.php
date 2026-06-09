<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Access extends CI_Controller
{


	var $modul = "access";

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
		$data['judul'] = "Data Access Card " . strtoupper($tabs);
		$data['tabs'] = $tabs;
		$data['jatuh_tempo'] = isset($_POST['jatuh_tempo']) ? $_POST['jatuh_tempo'] : 0;
		$data['tombol_view'] = "";
		if ($tabs == 'open') {
			$data['tombol_view'] .= $this->apl->anchor('<a href="' . site_url('tiket/access/add') . '" 
class="btn btn-neutral">
<i class="fa fa-plus"></i> Add </a>', 'tambah ' . $this->modul, $this->modul);
			$data['post'] = 1;
		}
		if ($tabs == 'payment') $data['post'] = 2;
		if ($tabs == 'proses') $data['post'] = 3;
		if ($tabs == 'done') $data['post'] = 4;
		if ($tabs == 'close') $data['post'] = 5;

		$data['tipe'] = '6';


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
		$data['judul'] = "Edit Access Card";
		$data['page'] = 'tiket/form_access'; //Halaman di tampilkan
		$data['tiket'] = $this->apl->getSelectedData("tiket", array('id_tiket' => $id_tiket))->row();

		$data['card'] = $this->apl->getSelectedData("tiket_detail", array(
			'hapus' => 0,
			'id_tiket' => $id_tiket,
			'tipe' => 1
		))->row();
		$data['submit'] = 'edit'; //Halaman di tampilkan
		$this->load->view('home', $data);

	}

	public function form_berkas($id = '')
	{
		$data['ket'] = $this->input->post('ket');
		$data['status_pemohon'] = $this->input->post('status_pemohon');
		$data['submit'] = $this->input->post('submit');

		//if ($data['status_pemohon'] != 'PEMILIK' || $data['ket'] == 'HILANG') {

			$kartu = array();
			$k = ($data['submit'] == 'tambah')
				? $this->tiket->getBerkasTiketTambah("KTP")
				: $this->tiket->getBerkasTiketEdit("KTP", $id);
			$kartu = array_merge($kartu, $k->result());

			if ($data['status_pemohon'] != 'PEMILIK') {
				if ($data['status_pemohon'] == 'KUASA PEMILIK') {
					$result = ($data['submit'] == 'tambah')
						? $this->tiket->getBerkasTiketTambah("KARTU_KUASA")
						: $this->tiket->getBerkasTiketEdit("KARTU_KUASA", $id);
				} else {
					$result = ($data['submit'] == 'tambah')
						? $this->tiket->getBerkasTiketTambah("KARTU")
						: $this->tiket->getBerkasTiketEdit("KARTU", $id);
				}
				$kartu = array_merge($kartu, $result->result());

			}
			if ($data['ket'] == 'HILANG') {
				$result = ($data['submit'] == 'tambah')
					? $this->tiket->getBerkasTiketTambah("KARTU HILANG")
					: $this->tiket->getBerkasTiketEdit("KARTU HILANG", $id);

				$kartu = array_merge($kartu, $result->result());

			}
			//$data['berkas'] = array_merge($result, $kartu->result());
			$data['berkas'] = $kartu;

			$this->load->view('tiket/form_berkas', $data);


		//}

	}

	public function add()
	{
		$data['judul'] = "Add Access Card";
		$data['page'] = 'tiket/form_access'; //Halaman di tampilkan
		$data['submit'] = 'tambah'; //Halaman di tampilkan
		$data['no_form'] = $this->apl->counter_view("Acces-Card/EPR-TR") . "/" . $this->apl->counter_code("Acces-Card/EPR-TR");
		/*
		$data['pekerjaan'] = $this->apl->getSelectedData("acc_jurnal_umum",
			array('id_jurnal' => '', 'hapus' => 0))->result();
		*/
		$data['card'] = array();
		$this->load->view('home', $data);

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
			'tipe' => 6, //Access Card
			'no_form' => $this->input->post('no_form'),
			'pelapor' => $this->input->post('pelapor'),
			'kontak' => $this->input->post('kontak'),
			'via' => $this->input->post('via'),
			'ket' => $this->input->post('ket'),
			'email' => $this->input->post('email'),
			'kartu_hilang' => $this->input->post('kartu_hilang'),
			'status_pemohon' => $this->input->post('status_pemohon'),
			'lainnya' => $this->input->post('lainnya'),
			'id_dep' => '1',

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

			$this->apl->counter("Acces-Card/EPR-TR");

			$id_tag = $this->input->post('id_tag');
			$jumlah = $this->apl->number_format($this->input->post('jumlah'));
			$qty = $this->input->post('qty');

			$this->apl->insertData("tiket_detail",
				array(
					'id_tiket' => $id_tiket,
					'tipe' => 1,
					'nama' => $this->apl->get_nilai_pilih("db_tag",
						"nama", array('id_tag' => $id_tag)),
					'id_tag' => $id_tag,
					'jumlah' => $jumlah * $qty,
					'harga_satuan' => $jumlah,
					'qty' => $qty,
				)
			);

			$this->pesan->pesan_success("Successfully Added Access Card data UNIT " . $kode_unit);
			$this->apl->insertData("tiket", $data);
			$this->apl->log("TAMBAH", "", json_encode($data), "tiket", $id_tiket);
		}

		/**
		 * Jika Edit Data
		 */
		if ($submit == 'edit') {
			$id_tiket = $this->input->post('id_tiket');
			$this->apl->updateData("tiket_detail", array('hapus' => 1),
				array('id_tiket' => $id_tiket, 'tipe' => 1));


			$id_tag = $this->input->post('id_tag');
			$jumlah = $this->apl->number_format($this->input->post('jumlah'));
			$qty = $this->input->post('qty');


			$this->apl->insertData("tiket_detail",
				array(
					'id_tiket' => $id_tiket,
					'tipe' => 1,
					'nama' => $this->apl->get_nilai_pilih("db_tag",
						"nama", array('id_tag' => $id_tag)),
					'id_tag' => $id_tag,
					'jumlah' => $jumlah * $qty,
					'harga_satuan' => $jumlah,
					'qty' => $qty,
				)
			);

			$this->apl->log("UPDATE", json_encode($this->apl->getSelectedData("tiket",
					array('id_tiket' => $id_tiket))->row())
				, json_encode($data), "tiket", $this->input->post('id_tiket'));
			$this->apl->updateData("tiket", $data, array('id_tiket' => $id_tiket));
			$this->pesan->pesan_success("Successfully Changed Access Card data UNIT  " . $kode_unit);
		}


		$id_upload = $this->input->post('id_upload');
		$nama_file = $this->input->post('nama_file');
		$folder = $this->input->post('folder');

		for ($i = 0; $i < count($id_upload); $i++) {
			if (!empty($_FILES['foto']["name"][$i])) {
				$tempName = $_FILES['foto']["tmp_name"][$i];
				$fileName = $_FILES['foto']["name"][$i];
				$fileName = $this->upload_model->fileName($fileName);
				$targetFile = $this->upload_model->targetFile($tempName, $fileName, $folder[$i]);
				//$this->upload_model->upload_resize($targetFile);

				$this->apl->updateData("tiket_berkas", array('hapus' => 1),
					array('id_tiket' => $id_tiket,
						'id_upload' => $id_upload[$i],
					));

				$this->apl->insertData("tiket_berkas", array(
					'file' => $fileName,
					'nama' => $nama_file[$i],
					'id_upload' => $id_upload[$i],
					'id_tiket' => $id_tiket,
					'id_admin' => $this->session->id_admin,
				));

			}
		}


		if (isset($_POST['simpan'])) {
			$this->apl->updateData("tiket", array('post' => 1), array('id_tiket' => $id_tiket));
			redirect('tiket/access/view/open');
		}
		if (isset($_POST['payment'])) {
			$this->apl->updateData("tiket", array('post' => 2), array('id_tiket' => $id_tiket));
			redirect('tiket/access/view/payment');
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
		$data['a'] = $this->apl->getSelectedData("tiket_approv",
			array('id_tiket' => $id_tiket, 'note !=' => ''))->result();
		$data['proses'] = '';
		$data['berkas'] = '';
		$this->load->view('tiket/cetak', $data);

	}


}
