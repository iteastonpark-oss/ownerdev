<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 4/8/2016
 * Time: 2:42 PM
 */
?>
<?php defined('BASEPATH') or exit('No direct script access allowed');

class Report_Model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function getDataBayar($kode,$tanggal_awal, $tanggal_ahir,$status='')
	{

		$this->db->select('0 as No,
  `kode` as Unit,
  `nama` as With,
  CONCAT(`kwt`,"<br><small>",GROUP_CONCAT(DISTINCT ket SEPARATOR "</small><br>")) as kwt,
  	jumlah as Total,
  `tanggal` as Date')
			->from('view_uang_masuk')
			->where(array(
				'tanggal >=' => $tanggal_awal,
				'tanggal <=' => $tanggal_ahir,
				'kode' => $kode,
			));
		if ($status != '') {
			$this->db->where(array('status' => $status));
		}
		$this->db->order_by('tanggal');
		return  $this->db->group_by('id_bayar');
	}
	public function getJumlahBayar($kode,$tanggal_awal, $tanggal_ahir,$status)
	{

		$this->db->select('SUM(jumlah) as total')
			->from('view_uang_masuk')
			->where(array(
				'tanggal >=' => $tanggal_awal,
				'tanggal <=' => $tanggal_ahir,
				'kode' => $kode,
			));
		if ($status != '') {
			$this->db->where(array('status' => $status));
		}
		$this->db->order_by('tanggal');
		return  $this->db->get();
	}
	public function getDataBiaya($tanggal_awal, $tanggal_ahir, $id_via='',$status='')
	{

		$this->db->select('0 as No,
  `to` as To,
  `via` as With,
 no_form as Nomor, 
  jumlah as Total,
  `tanggal` as Date')
			->from('view_uang_keluar')
			->where(array(
				'tanggal >=' => $tanggal_awal,
				'tanggal <=' => $tanggal_ahir,
			));
		if ($id_via != '') {
			$this->db->where(array('id_via' => $id_via));
		}
		if ($status != '') {
			$this->db->where(array('status' => $status));
		}
		return $this->db->order_by('tanggal');
		//return  $this->db->group_by('id_kas');
	}
	public function getJumlahBiaya($tanggal_awal, $tanggal_ahir, $id_via='',$status='')
	{

		$this->db->select('SUM(jumlah) as total')
			->from('view_uang_keluar')
			->where(array(
				'tanggal >=' => $tanggal_awal,
				'tanggal <=' => $tanggal_ahir,
			));
		if ($id_via != '') {
			$this->db->where(array('id_via' => $id_via));
		}
		if ($status != '') {
			$this->db->where(array('status' => $status));
		}
		$this->db->order_by('tanggal');
		return  $this->db->get();
	}

}

?>
