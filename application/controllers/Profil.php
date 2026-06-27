<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 4/8/2016
 * Time: 2:42 PM
 */
?>

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profil extends CI_Controller
{
	/**
	 * @var string
	 * Isis Atribut Tabel, Query, Field yang di tampilkan
	 */
	var $table = 'admin'; //Nama Database
	var $primary = 'id_admin'; //Primary Key ID
	var $modul = 'profil'; //Primary Key ID

	/**
	 * Akhir
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Crud_Model', 'crud_model');
		$this->crud_model = new Crud_Model();
		$this->load->model('Karyawan_Model', 'karyawan');
		$this->karyawan = new Karyawan_Model();
		$this->apl = new Apl();
		$this->pesan = new Pesan();

	}


	public function index()
	{
		$data['judul'] = 'My Profil';
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
		} else {
			$id = $this->session->id_admin;

		}
		$text = "SELECT * FROM admin WHERE id_admin='$id'";
		$data2 = $this->apl->manualQuery($text)->row();
		$data['id_admin'] = $data2->id_admin;
		$data['nama_admin'] = $data2->nama_admin;
		$data['username'] = $data2->username;
		$data['email'] = $data2->email;
		$data['password'] = $data2->password;
		$data['level'] = $data2->level;

		$data['karyawan'] = $this->karyawan->get_data_karyawan_by_id($data2->id_karyawan)->get()->row();


		$data['form'] = 'form_profil'; //Halaman di tampilkan
		$data['page'] = 'profil/form_profil'; //Halaman di tampilkan

		$this->load->view('home', $data);
	}

	public function ajax_get_profil($id)
	{
		$text = "SELECT * FROM admin WHERE id_admin='$id'";
		$data2 = $this->apl->manualQuery($text)->row_array();
		$output['data']['profil'] = $data2;

		echo json_encode($output);
	}

	public function edit_profil()
	{
		$id = $this->input->get('id');
		$db = $this->apl->manualQuery("SELECT * FROM admin WHERE id_admin=" . $id)->row();
		$data['id_admin'] = $db->id_admin;
		$data['nama_admin'] = $db->nama_admin;
		$data['username'] = $db->username;
		$data['password'] = $db->password;
		$this->load->view('profil/edit_profil', $data);
	}

	public function password_profil()
	{
		$id = $this->input->get('id');
		$db = $this->apl->manualQuery("SELECT * FROM admin WHERE id_admin=" . $id)->row();
		$data['id_admin'] = $db->id_admin;
		$data['password'] = $db->password;
		$this->load->view('profil/password_profil', $data);
	}

	public function signature_profil()
	{
		$id = $this->input->get('id');

		$db = $this->apl->getSelectedData("admin", array('id_admin' => $id))->row();
		$data['k'] = $this->apl->getSelectedData("karyawan", array('id_karyawan' => $db->id_karyawan))->row();
		$this->load->view('profil/signature_profil', $data);
	}

	public function signature()
	{
		$id = $this->input->get('id');

		$db = $this->apl->getSelectedData("admin", array('id_admin' => $id))->row();
		$data['k'] = $this->apl->getSelectedData("karyawan", array('id_karyawan' => $db->id_karyawan))->row();
		$data['judul'] = 'Add Signature'; //Halaman di tampilkan
		//$data['page'] = 'profil/signature'; //Halaman di tampilkan
		$data['page'] = 'profil/signature_pad'; //Halaman di tampilkan
		$this->load->view('home', $data);
	}

	public function signature_pad()
	{
		$id = $this->input->get('id');

		$db = $this->apl->getSelectedData("admin", array('id_admin' => $id))->row();
		$data['k'] = $this->apl->getSelectedData("karyawan", array('id_karyawan' => $db->id_karyawan))->row();
		$data['judul'] = 'Add Signature'; //Halaman di tampilkan
		//$data['page'] = 'profil/signature_pad'; //Halaman di tampilkan
		$this->load->view('profil/signature_pad', $data);
	}

	public function ajax_edit_profil()
	{
		$id = $this->input->post($this->primary);
		$data = array(
			'username' => $this->input->post('username'),
		);
		$this->apl->log("Update Username Profil",
			json_encode($this->apl->getSelectedData($this->table,
				array($this->primary => $this->input->post($this->primary)))->row()),
			json_encode($data),
			$this->table,
			$id);
		$this->crud_model->update(array($this->primary => $id), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_password_profil()
	{
		$id = $this->input->post($this->primary);

		if ($this->input->post('password1') != $this->input->post('password2')) {
			$this->pesan->pesan_warning("Passwords are not the same");
		} else {

			$data = array(
				'password' => $this->apl->password($this->input->post('password1')),
			);
			$this->apl->log("Update Password Profil",
				json_encode($this->apl->getSelectedData($this->table,
					array($this->primary => $this->input->post($this->primary)))->row()),
				json_encode($data),
				$this->table,
				$id);
			$this->crud_model->update(array($this->primary => $id), $data);
		}
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_add_signature()
	{


		$nama_gambar = time() . "-signature-" . $this->input->post('id_karyawan') . ".png";
		$image_parts = explode(";base64,", $_POST['signed']);
		$image_type_aux = explode("image/", $image_parts[0]);
		$image_base64 = base64_decode($image_parts[1]);
		file_put_contents('upload/signature/' . $nama_gambar, $image_base64);

		$this->apl->updateData("karyawan", array(
			'signature' => $nama_gambar,
			'encode_signature' => $image_parts[1],
		), array('id_karyawan' => $this->input->post('id_karyawan')));

		$this->pesan->pesan_success("Successfully Add Signature");
		echo json_encode(array("status" => TRUE));
	}

	public function signature_act()
	{
		$nama_gambar = time() . "-signature-" . $this->input->post('id_karyawan') . ".png";
		$image_parts = explode(";base64,", $this->input->post('signed'));

		$image_type_aux = explode("image/", $image_parts[0]);
		$image_base64 = base64_decode($image_parts[1]);
		file_put_contents('upload/signature/' . $nama_gambar, $image_base64);


		$this->load->library('ciqrcode');
		$params['data'] = $nama_gambar;
		$params['level'] = 'H';
		$params['size'] = 50;
		$params['cachedir'] = 'upload/qr_code/';
		$params['savename'] = 'upload/qr_code/signature-' . $this->input->post('id_karyawan') . ".png";
		$qr_code = $this->ciqrcode->generate($params);

		$this->apl->updateData("karyawan", array(
			'signature' => $nama_gambar,
			'encode_signature' => $image_parts[1],
			'qr_signature' => $qr_code,
		), array('id_karyawan' => $this->input->post('id_karyawan')));

		$this->pesan->pesan_success("Successfully Add Signature");
		redirect(site_url('profil'));
	}


}

?>
