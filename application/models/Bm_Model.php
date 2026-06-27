<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 4/8/2016
 * Time: 2:42 PM
 */
?>
<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Bm_Model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}


	public function get()
	{
		$id_bms=(isset($this->session->id_bms)) ? $this->session->id_bms : '1';
		return $this->db->get_where("bms", array('id_bms' => $id_bms))->row();
	}

	public function tagihan($where)
	{
		$list = $this->db->from('db_tag')->where($where)->get();
		$data = array();
		foreach ($list->result() as $a) {
			$data[] = $a->id;
		}
		if ($list->num_rows() == 0) {
			return 0;
		} else {
			return implode(',', $data);

		}

	}

	public function tagihan_array($where)
	{

		$list = $this->db->from('db_tag')->where($where)->get();
		$data = array();
		foreach ($list as $a) {
			$data[$a->id] = $a->id;
		}
		return $data;
	}


	public function getDataBayar(){
		$this->db->select('
		`db_unit`.`kode`,
		`db_via`.`nama`,
		`bayar`.`kwt`,
		`bayar`.`jumlah`,
		`bayar`.`id_via`,
		`bayar`.`tanggal`,
		`bayar_lainnya`.`ket`,
		`bayar_lainnya`.`note`,
		1 as `status`
		')
				  ->from('`bayar`')
				  ->join('bast', '`bayar`.`id_bast` = `bast`.`id_bast`')
				  ->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`')
				  ->join('db_via', '`bayar`.`id_via` = `db_via`.`id_via`')
				  ->join('bayar_lainnya', '`bayar_lainnya`.`id_bayar` = `bayar`.`id_bayar`')
				  ->where(array(
					  'bayar.hapus' => 0,
				  ));
				  $q_lainnya = $this->db->get_compiled_select();



		$this->db->select('
		`db_unit`.`kode`,
		`db_via`.`nama`,
		`bayar`.`kwt`,
		`bayar`.`jumlah`,
		`bayar`.`id_via`,
		`bayar`.`tanggal`,
		`tiket_detail`.`nama` as ket,
		`tiket_detail`.`ket` as note,
		2 as `status`
	
		')
				  ->from('bayar')
				  ->join('bast', '`bayar`.`id_bast` = `bast`.`id_bast`')
				  ->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`')
				  ->join('db_via', '`bayar`.`id_via` = `db_via`.`id_via`')
				  ->join('tiket_detail', '`tiket_detail`.`id_bayar` = `bayar`.`id_bayar`')
				  ->where(array(
					  'bayar.hapus' => 0,
				  ));
				  $q_tiket = $this->db->get_compiled_select();
		$this->db->select('
		`db_unit`.`kode`,
		`db_via`.`nama`,
		`bayar`.`kwt`,
		`bayar`.`jumlah`,
		`bayar`.`id_via`,
		`bayar`.`tanggal`,
		`billing_detail`.`ket`,
		`billing_detail`.`note`,
		3 as `status`
	
		')
				  ->from('bayar')
				  ->join('bast', '`bayar`.`id_bast` = `bast`.`id_bast`')
				  ->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`')
				  ->join('db_via', '`bayar`.`id_via` = `db_via`.`id_via`')
				  ->join('billing_detail', '`billing_detail`.`id_bayar` = `bayar`.`id_bayar`')
				  ->where(array(
					  'bayar.hapus' => 0,
				  ));
				  $q_invoice = $this->db->get_compiled_select();


				  return $q_lainnya . " UNION " . $q_tiket
				  . " UNION " . $q_invoice;
	}

}

?>
