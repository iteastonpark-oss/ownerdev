<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pemilik extends CI_Controller
{


	var $modul = "pemilik";

	function __construct()
	{
		parent::__construct();
		$this->load->model('Crud_Model', 'crud_model');
		$this->crud_model = new Crud_Model();


		$this->apl = new Apl();
		$this->tombol = new Tombol();
		$this->pesan = new Pesan();


	}


	public function edit()
	{
		if ($this->session->login) {
			$id_bast = $this->session->id_bast;
			$data['b'] = $this->apl->getSelectedData("bast", array('id_bast' => $id_bast))->row();
			$id_pemilik = $this->input->get('id');

			$data['pemilik'] = $this->apl->getSelectedData("pemilik", array('id_pemilik' => $data['b']->id_pemilik))->row();
			$data['ktp'] = $this->apl->getSelectedData("bast_berkas", array('id_bast' => $id_bast, 'hapus' => 0, 'nama' => 'KTP'))->row();
			$data['kk'] = $this->apl->getSelectedData("bast_berkas", array('id_bast' => $id_bast, 'hapus' => 0, 'nama' => 'Kartu Keluarga'))->row();

			$data['ktp'] = $this->db->where(array('id_bast' => $id_bast, 'hapus' => 0, 'nama' => 'KTP'))
				->order_by('id', 'DESC')->get('bast_berkas', 1)->row();
			$data['kk'] = $this->db->where(array('id_bast' => $id_bast, 'hapus' => 0, 'nama' => 'Kartu Keluarga'))
				->order_by('id', 'DESC')->get('bast_berkas', 1)->row();
			$data['submit'] = 'edit'; //Halaman di tampilkan
		}
		$this->session->redirect = "pemilik/edit";
		$data['judul'] = "Edit Owner";
		$data['page'] = 'pemilik/form'; //Halaman di tampilkan
		$this->load->view('home', $data);

	}




	public function actions()
	{
		// Ambil semua POST data
		$postData = $this->input->post();

		// Siapkan data untuk dikirim ke BMS
		$curlData = [];
		foreach ($postData as $key => $value) {
			$curlData[$key] = $value;
		}


		if (!empty($_FILES['foto']['name'])) {
			$curlData['foto'] = new CURLFile(
				$_FILES['foto']['tmp_name'],
				$_FILES['foto']['type'],
				$_FILES['foto']['name']
			);
		}

		if (!empty($_FILES['foto_ktp']['name'])) {
			$curlData['foto_ktp'] = new CURLFile(
				$_FILES['foto_ktp']['tmp_name'],
				$_FILES['foto_ktp']['type'],
				$_FILES['foto_ktp']['name']
			);
		}

		if (!empty($_FILES['file_kk']['name'])) {
			$curlData['file_kk'] = new CURLFile(
				$_FILES['file_kk']['tmp_name'],
				$_FILES['file_kk']['type'],
				$_FILES['file_kk']['name']
			);
		}


		$ch = curl_init("https://bms.eprjatinangor.com/pemilik/actions_iframe");
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $curlData);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

		$result = curl_exec($ch);
		$error = curl_error($ch);
		curl_close($ch);

		if ($error) {
			echo "Gagal kirim ke BMS: " . $error;
		} else {
			echo $result; // Bisa redirect atau tampilkan pesan sukses
			//redirect('bast');
		}
		redirect('bast');
	}

	public function actions2()
	{
		$submit = $this->input->post('submit');
		$data = array(
			//'nama' => $this->input->post('nama'),
			'nik' => $this->input->post('nik'),
			'jenis_kelamin' => $this->input->post('jenis_kelamin'),
			'tempat_lahir' => $this->input->post('tempat_lahir'),
			'tanggal_lahir' => $this->input->post('tanggal_lahir'),
			'status_perkawinan' => $this->input->post('status_perkawinan'),
			'agama' => $this->input->post('agama'),
			'alamat' => $this->input->post('alamat'),
			'pos' => $this->input->post('pos'),
			'id_desa' => $this->input->post('id_desa'),
			'desa' => $this->apl->get_nilai_pilih("lok_desa", "nama", "id_desa="
				. $this->input->post('id_desa')),
			'id_kec' => $this->input->post('id_kec'),
			'kec' => $this->apl->get_nilai_pilih("lok_kec", "nama", "id_kec="
				. $this->input->post('id_kec')),
			'id_kab' => $this->input->post('id_kab'),
			'kab' => $this->apl->get_nilai_pilih("lok_kab", "nama", "id_kab="
				. $this->input->post('id_kab')),
			'id_prov' => $this->input->post('id_prov'),
			'prov' => $this->apl->get_nilai_pilih("lok_prov", "nama", "id_prov="
				. $this->input->post('id_prov')),

			'kantor' => $this->input->post('kantor'),
			'alamat_kantor' => $this->input->post('alamat_kantor'),
			'kota_kantor' => $this->input->post('kota_kantor'),

			'hp' => $this->input->post('hp'),
			'tlp' => $this->input->post('tlp'),
			'email' => $this->input->post('email'),
			'wa' => $this->input->post('wa'),


		);
		$id_pemilik = $this->input->post('id_pemilik');

		if (!empty($_FILES['foto']["name"])) {
			$nama_gambar = time() . "-Profil-" . $id_pemilik . ".png";
			$image_parts = explode(";base64,", $_POST['file_base64']);
			$image_type_aux = explode("image/", $image_parts[0]);
			$image_base64 = base64_decode($image_parts[1]);
			file_put_contents('upload/bukti_bayar/' . $nama_gambar, $image_base64);
			$data = array_merge($data, array('foto' => $nama_gambar));
		}


		$this->apl->log("UPDATE", json_encode($this->apl->getSelectedData("pemilik",
				array('id_pemilik' => $id_pemilik))->row())
			, json_encode($data), "pemilik", $id_pemilik);
		$this->apl->updateData("pemilik", $data, array('id_pemilik' => $this->input->post('id_pemilik')));
		$this->pesan->pesan_success("Successfully changed owner With ID Card " . $this->input->post('nik'));


		if (!empty($_FILES['foto_ktp']["name"])) {
			$nama_gambar = time() . "-Profil-" . $id_pemilik . ".png";
			$image_parts = explode(";base64,", $_POST['file_base64_ktp']);
			$image_type_aux = explode("image/", $image_parts[0]);
			$image_base64 = base64_decode($image_parts[1]);
			file_put_contents('upload/bukti_bayar/' . $nama_gambar, $image_base64);
			//$data = array_merge($data,array('foto' => $nama_gambar));
			$this->apl->insertData("bast_berkas",
				array(
					'id_bast' => $this->session->id_bast,
					'nama' => 'KTP',
					'file' => $nama_gambar,
					'id_upload' => '1',
				));
		}
		if (!empty($_FILES['file_kk']["name"])) {
			$tempName = $_FILES['file_kk']['tmp_name'];
			$fileName = $_FILES['file_kk']['name'];
			$fileName = $this->upload_model->fileName($fileName);
			$targetFile = $this->upload_model->targetFile($tempName, $fileName, "berkas");
			$this->apl->insertData("bast_berkas",
				array(
					'id_bast' => $this->session->id_bast,
					'nama' => 'Kartu Keluarga',
					'file' => $fileName,
					'id_upload' => '9',
				));
		}
		redirect('bast');
	}

	function ajax_cek_nik()
	{


		$nik = $this->input->post('nik');
		$id_pemilik = $this->input->post('id_pemilik');
		$cek = $this->apl->getSelectedData("pemilik",
			array(
				'nik' => $nik,
				'id_pemilik !=' => $id_pemilik,
			))->num_rows();


		if (!$cek) {
			$output['data'] = array('pesan' => 1);
		} else {

			$output['data'] = array('pesan' => 0);
		}
		header('Content-Type: application/json; charset=utf-8');
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET,POST');
		echo json_encode($output);

	}

}
