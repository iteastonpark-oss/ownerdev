<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 4/8/2016
 * Time: 2:42 PM
 */

defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Home_Model', 'home');
		$this->home = new Home_Model();

		$this->load->model('Dropdown_Model', 'dropdown_model');
		$this->dropdown_model = new Dropdown_Model();


		$this->apl = new Apl();
		$this->pesan = new Pesan();


	}

	public function index()
	{
		$data['judul'] = 'menu'; //Halaman di tampilkan
		$data['page'] = 'utility/home'; //Halaman di tampilkan
		$id_bast=$this->session->id_bast;
		if ($id_bast != '' && $this->session->tipe == 'owner') {
			$login=$this->session->login;
			$data['b'] = $this->apl->getSelectedData("bast", array('id_bast' => $id_bast))->row();
			$data['u'] = $this->apl->getSelectedData("db_unit", array('id_unit' => $data['b']->id_unit))->row();
			$data['p'] = $this->apl->getSelectedData("pemilik", array('id_pemilik' => $data['b']->id_pemilik))->row();

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
			//$data['gp'] = $this->bast->getHistoriGantiPemilik($id_bast);
		}
		$this->load->view('home', $data);

	}



}

?>
