<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bast extends CI_Controller
{


	var $modul = "bast";

	function __construct()
	{
		parent::__construct();
		$this->load->model('Crud_Model', 'crud_model');
		$this->crud_model = new Crud_Model();

		$this->load->model('Bast_Model', 'bast');
		$this->bast = new Bast_Model();
		$this->apl = new Apl();
		$this->tombol = new Tombol();
		$this->pesan = new Pesan();

	}


	public function index()
	{
		$data['judul'] = "Detail Informasi Unit";
		$data['tabs'] = "detail";
		$data['page'] = 'bast/detail'; //Halaman di tampilkan
		if ($this->session->login) {
			$id_bast = $this->session->id_bast;



			$data['b'] = $this->apl->getSelectedData("bast", array('id_bast' => $id_bast))->row();
			$data['u'] = $this->apl->getSelectedData("db_unit", array('id_unit' => $data['b']->id_unit))->row();
			$data['p'] = $this->apl->getSelectedData("pemilik", array('id_pemilik' => $data['b']->id_pemilik))->row();
			$data['cctv'] = $this->apl->getSelectedData("db_cctv", array('lantai' => $data['u']->lantai))->row();

			$data['tagihan'] = $this->db->select('(SUM(IF(status=1,jumlah,0)) - SUM(IF(status=3,jumlah,0)))
			-SUM(IF(status=2,jumlah,0)) as `piutang`')
				->from('billing_detail')
				->where(
					array(
						'id_bast' => $id_bast,
						'hapus' => 0
					)
				)
				->get()->row()->piutang;

			$data['air'] = $this->db->select('MAX(utility.meter) as `meter`')
				->join('(SELECT * FROM db_tag where tagihan=2 AND unit=1) db_tag', '`utility_rekening`.`id_tag` = `db_tag`.`id_tag`')
				->join('db_unit', '`utility_rekening`.`id_unit` = `db_unit`.`id_unit`')
				->join('utility', '`utility`.`id_rekening` = `utility_rekening`.`id_rekening`')
				->where(array(
					'utility_rekening.hapus' => 0,
					'utility_rekening.id_tag' => 4,
					'utility_rekening.id_unit' => $data['u']->id_unit,
					'utility.hapus' => 0,

					'db_unit.hapus' => 0,
					'db_unit.id_group' => '1',
				))->from('utility_rekening')->get()->row()->meter;
			$data['gp'] = $this->bast->getHistoriGantiPemilik($id_bast);
		}
		$this->load->view('home', $data);

	}

	public function huni()
	{
		$id_bast = $this->input->get('id');
		if ($id_bast == '') {
			redirect(site_url('unit/bast/view'));
		}

		$data['judul'] = "Residence Permit List";
		$data['page'] = 'unit/bast/huni'; //Halaman di tampilkan
		$data['b'] = $this->apl->getSelectedData("bast", array('id_bast' => $id_bast))->row();
		$data['u'] = $this->apl->getSelectedData("db_unit", array('id_unit' => $data['b']->id_unit))->row();

		$data['list'] = $this->bast->getHuniUnit($id_bast);
		$data['tabs'] = "huni";
		$this->load->view('home', $data);
	}

	public function surat()
	{
		$id_bast = $this->input->get('id');
		if ($id_bast == '') {
			redirect(site_url('unit/bast/view'));
		}
		$data['judul'] = "Alamat Surat";
		$data['page'] = 'unit/bast/surat'; //Halaman di tampilkan
		$data['b'] = $this->apl->getSelectedData("bast", array('id_bast' => $id_bast))->row();
		$data['u'] = $this->apl->getSelectedData("db_unit", array('id_unit' => $data['b']->id_unit))->row();

		$data['tabs'] = "surat";
		$this->load->view('home', $data);

	}

	public function surat_act()
	{
		$id_bast = $this->input->post('id_bast');
		$alamat_surat = $this->input->post('alamat_surat');
		$kota_surat = $this->input->post('kota_surat');
		$email_surat = $this->input->post('email_surat');
		$hp_surat = $this->input->post('hp_surat');
		$tlp_surat = $this->input->post('tlp_surat');
		$wa_surat = $this->input->post('wa_surat');
		$kirim = $this->input->post('kirim');

		$update = array(
			'alamat_surat' => $alamat_surat,
			'kota_surat' => $kota_surat,
			'email_surat' => $email_surat,
			'hp_surat' => $hp_surat,
			'wa_surat' => $wa_surat,
			'tlp_surat' => $tlp_surat,
			'kirim' => $kirim,
			'id_admin' => $this->session->id_admin);
		$s = $this->apl->getSelectedData("bast", array('id_bast' => $id_bast))->row();
		$this->apl->log("UPDATE ALAMAT SURAT"
			, json_encode($s)
			, json_encode($update)
			, "bast"
			, $id_bast
		);
		$this->apl->insertData("bast_his_surat", array(
			'id_bast' => $s->id_bast,
			'alamat_surat' => $s->alamat_surat,
			'kota_surat' => $s->kota_surat,
			'email_surat' => $s->email_surat,
			'hp_surat' => $s->hp_surat,
			'wa_surat' => $s->wa_surat,
			'tlp_surat' => $s->tlp_surat,
			'id_admin' => $this->session->id_admin
		));

		$this->apl->updateData("bast", $update,
			array('id_bast' => $id_bast));

		$this->pesan->pesan_success("Successfully Update Invoice Letter Address");
		redirect($_SERVER['HTTP_REFERER']);
	}


	public function ganti_pemilik()
	{
		$id_bast = $this->input->get('id');
		if ($id_bast == '') {
			redirect(site_url('unit/bast/view'));
		}

		$data['nik'] = isset($_POST['nik']) ? $_POST['nik'] : '';
		$data['judul'] = "Change Owner";
		$data['page'] = 'unit/bast/ganti_pemilik'; //Halaman di tampilkan
		$data['b'] = $this->apl->getSelectedData("bast", array('id_bast' => $id_bast))->row();
		$data['p'] = $this->apl->getSelectedData("pemilik", array('id_pemilik' => $data['b']->id_pemilik))->row();
		$data['pb'] = $this->apl->getSelectedData("pemilik", array('nik' => $data['nik']))->row();
		//$data['gp'] = $this->apl->getSelectedData("bast_his_pemilik", array('id_bast' => $id_bast));
		$data['gp'] = $this->bast->getHistoriGantiPemilik($id_bast);
		$data['u'] = $this->apl->getSelectedData("db_unit", array('id_unit' => $data['b']->id_unit))->row();


		$data['tabs'] = "pemilik";
		$this->load->view('home', $data);

	}


	public function ganti_pemilik_act()
	{
		$id_bast = $this->input->post('id_bast');
		$id_pemilik = $this->input->post('id_pemilik');
		$tanggal = $this->input->post('tanggal');
		$id_pemilik_lama = $this->input->post('id_pemilik_lama');
		$tangan = $this->input->post('tangan');

		$p = $this->apl->getSelectedData("pemilik", array('id_pemilik' => $id_pemilik))->row();

		$update = array(
			'id_pemilik' => $id_pemilik,
			'tangan' => $tangan,
			'alamat_surat' => $p->alamat,
			'kota_surat' => $p->kab,
			'email_surat' => $p->email,
			'hp_surat' => $p->hp,
			'wa_surat' => $p->wa,
			'tlp_surat' => $p->tlp,

		);
		$this->apl->log("GANTI KEPEMILIKAN"
			, json_encode($this->apl->getSelectedData("bast", array('id_bast' => $id_bast)))
			, json_encode($update)
			, "bast"
			, $id_bast
		);
		$this->apl->updateData("bast", $update,
			array('id_bast' => $id_bast));

		$this->apl->insertData("bast_his_pemilik", array(
			'id_bast' => $id_bast,
			'pemilik_lama' => $id_pemilik_lama,
			'pemilik_baru' => $id_pemilik,
			'tanggal' => $tanggal,
			'id_admin' => $this->session->id_admin,
		));

		$this->pesan->pesan_success("Successfully Changed Ownership");
		redirect($_SERVER['HTTP_REFERER']);
	}


	public function hapus()
	{
		$id_bast = $this->input->get('id');
		if ($id_bast == '') {
			redirect(site_url('unit/bast/view'));
		}

		$data['nik'] = isset($_POST['nik']) ? $_POST['nik'] : '';
		$data['judul'] = "Delete BAST";
		$data['page'] = 'unit/bast/hapus'; //Halaman di tampilkan
		$data['b'] = $this->apl->getSelectedData("bast", array('id_bast' => $id_bast))->row();
		$data['p'] = $this->apl->getSelectedData("pemilik", array('id_pemilik' => $data['b']->id_pemilik))->row();
		$data['pb'] = $this->apl->getSelectedData("pemilik", array('nik' => $data['nik']))->row();
		//$data['gp'] = $this->apl->getSelectedData("bast_his_pemilik", array('id_bast' => $id_bast));
		$data['gp'] = $this->bast->getHistoriGantiPemilik($id_bast);


		$data['tabs'] = "hapus";
		$this->load->view('home', $data);

	}


	public function hapus_act()
	{
		$id_bast = $this->input->post('id_bast');

		$data = array(
			'id_bast' => $id_bast,
			'alasan' => $this->input->post('alasan'),
			'id_admin' => $this->session->id_admin,

		);

		if (isset($_FILES["file"]) && !empty($_FILES["file"]["name"])) {
			{
				$tempName = $_FILES['file']['tmp_name'];
				$fileName = $_FILES['file']['name'];
				$fileName = $this->upload_model->fileName($fileName);
				$targetFile = $this->upload_model->targetFile($tempName, $fileName, "berkas");
				$this->upload_model->upload_resize($targetFile);
				$data = array_merge($data, array('file' => $fileName));
			}
		}
		$this->apl->log("HAPUS BAST"
			, json_encode($this->apl->getSelectedData("bast", array('id_bast' => $id_bast)))
			, json_encode($data)
			, "bast"
			, $id_bast
		);
		$this->apl->updateData("bast", array('hapus' => 1),
			array('id_bast' => $id_bast));
		$this->apl->insertData("bast_hapus", $data);


		$this->pesan->pesan_success("Successfully Delete BAST");
		redirect(site_url('unit/bast'));
	}

	/**
	 * Berkas
	 */


	public function berkas()
	{
		$id_bast = $this->input->get('id');
		if ($id_bast == '') {
			redirect(site_url('unit/bast/view'));
		}

		$data['judul'] = "File Unit";
		$data['page'] = 'unit/bast/berkas'; //Halaman di tampilkan
		$data['b'] = $this->apl->getSelectedData("bast", array('id_bast' => $id_bast))->row();
		$data['u'] = $this->apl->getSelectedData("db_unit", array('id_unit' => $data['b']->id_unit))->row();

		$data['field'] = $this->bast->getBerkas($id_bast)->get()->list_fields(); //Nama Coloum Tabel
		$data['tabs'] = "berkas";
		$data['tombol_view'] = $this->apl->anchor(
			$this->tombol->get_tambah_js("add_data()"),
			'tambah_berkas_' . $this->modul, $this->modul);

		$this->load->view('home', $data);

	}


	public function ajax_list_berkas()
	{
		$id = isset($_GET['id']) ? $_GET['id'] : '';

		$column_order = array(
			null,
			null,
			'`nama`',
			null
		);
		$column_search = array();


		$list = $this->crud_model->get_data(
			$this->bast->getBerkas($id), $column_search, $column_order);
		$record_total = $this->crud_model->get_jumlah(
			$this->bast->getBerkas($id));
		$record_filter = $this->crud_model->get_jumlah_filter(
			$this->bast->getBerkas($id), $column_search, $column_order);


		$data_array = array();
		$no = isset($_POST['start']) ? $_POST['start'] : '0';
		$jumlah = $list->num_fields();
		foreach ($list->result_array() as $data) {
			$no++;
			$r = array_values($data);
			$r[0] = $no;
			$r[1] = $this->upload_model->tampil_gambar_modal($r[1], "", "berkas", 'width="80px" height="80px"');

			$r[$jumlah - 1] = '<div class="btn-group btn-group-xs">'
				. $this->apl->anchor(
					$this->tombol->get_edit_js("edit_data('" . $r[$jumlah - 1] . "')"),
					'edit_berkas_' . $this->modul, $this->modul)
				. $this->apl->anchor(
					$this->tombol->get_hapus_js("delete_data('" . $r[$jumlah - 1] . "')"),
					'hapus_berkas_' . $this->modul, $this->modul)

				. '</div>';

			$data_array[] = $r;
		}
		$output = array(
			"draw" => isset($_POST['draw']) ? $_POST['draw'] : '',
			"recordsTotal" => $record_total,
			"recordsFiltered" => $record_filter,
			"data" => $data_array,
		);
		echo json_encode($output);//output to json format

	}

	public function ajax_add_berkas()
	{
		$urut = $this->apl->urut("bast_berkas", "id");
		$data = array(
			'id' => $urut,
			'id_upload' => $this->input->post('id_upload'),
			'nama' => $this->apl->get_nilai_pilih("db_upload", "nama", array('id_upload' => $this->input->post('id_upload'))),
			'nomor' => $this->input->post('nomor'),
			'id_bast' => $this->input->post('id_bast'),
			'id_admin' => $this->session->id_admin,
		);

		if (isset($_FILES["file"]) && !empty($_FILES["file"]["name"])) {
			{
				$tempName = $_FILES['file']['tmp_name'];
				$fileName = $_FILES['file']['name'];
				$fileName = $this->upload_model->fileName($fileName);
				$targetFile = $this->upload_model->targetFile($tempName, $fileName, "berkas");
				$this->upload_model->upload_resize($targetFile);
				$data = array_merge($data, array('file' => $fileName));
			}
		}
		$this->apl->log("Tambah",
			'',
			json_encode($data),
			"bast_berkas",
			$urut);
		$this->apl->insertData("bast_berkas", $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_edit_berkas($id)
	{
		$data = $this->apl->getSelectedData("bast_berkas", array('id' => $id))->row();
		echo json_encode($data);
	}

	public function ajax_update_berkas()
	{
		$data = array(
			'id_upload' => $this->input->post('id_upload'),
			'nama' => $this->apl->get_nilai_pilih("db_upload", "nama", array('id_upload' => $this->input->post('id_upload'))),
			'nomor' => $this->input->post('nomor'),
			'id_bast' => $this->input->post('id_bast'),
			'id_admin' => $this->session->id_admin,
		);

		if (isset($_FILES["file"]) && !empty($_FILES["file"]["name"])) {
			{
				$tempName = $_FILES['file']['tmp_name'];
				$fileName = $_FILES['file']['name'];
				$fileName = $this->upload_model->fileName($fileName);
				$targetFile = $this->upload_model->targetFile($tempName, $fileName, "berkas");
				$this->upload_model->upload_resize($targetFile);
				$data = array_merge($data, array('file' => $fileName));
			}
		}
		$this->apl->log("Edit",
			json_encode($this->apl->getSelectedData("bast_berkas",
				array("id" => $this->input->post('id')))->row()),
			json_encode($data),
			$this->table);
		$this->apl->updateData("bast_berkas", $data, array('id' => $this->input->post('id')));
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete_berkas($id)
	{
		$data = array(
			'hapus' => '1',

		);
		$this->apl->log("Hapus",
			json_encode($this->apl->getSelectedData("bast_berkas", array('id' => $id))->row()),
			json_encode($data),
			$this->table);
		$this->apl->updateData("bast_berkas", $data, array('id' => $id));
		echo json_encode(array("status" => TRUE));
	}

}
