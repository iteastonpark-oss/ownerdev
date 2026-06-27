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

class Report extends CI_Controller
{
	var $modul = "Pembayaran";

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Bayar_Model', 'bayar');
		$this->load->model('Crud_Model', 'crud_model');
		$this->bayar = new Bayar_Model();
		$this->crud_model = new Crud_Model();
		$this->apl = new Apl();
		$this->tombol = new Tombol();
		$this->pesan = new Pesan();
	}

	public function off_name()
	{
		$data['tanggal_awal'] = isset($_POST['tanggal_awal']) ? $_POST['tanggal_awal'] : date('Y-m-01');
		$data['tanggal_ahir'] = isset($_POST['tanggal_ahir']) ? $_POST['tanggal_ahir'] : date('Y-m-d');
		$data['id_via'] = isset($_POST['id_via']) ? $_POST['id_via'] : '';
		$data['data'] = $this->bayar->getDataBayarHarian($data['tanggal_awal'],$data['tanggal_ahir'],$data['id_via'])->result();
		$data['pecahan'] = $this->apl->getAllData("db_uang")->result();
		$data['tombol_view']=anchor('bayar/report/kas_xls','<i class="fa fa-file-csv"></i> CSV'
		,'class="btn btn-default pull-right" target="_blank"');
		$data['page'] = 'bayar/report/kas'; //Halaman di tampilkan
		$this->load->view('home', $data);

	}
}

?>
