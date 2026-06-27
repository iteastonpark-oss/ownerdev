<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 4/8/2016
 * Time: 2:42 PM
 */
?>
<?php defined('BASEPATH') or exit('No direct script access allowed');

class Apps_Model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	public function getAbsensi($id_karyawan, $tanggal_awal, $tanggal_ahir)
	{


		$this->db->select("CONCAT(`karyawan`.`nama`,'<br><small>',`karyawan`.`nik`,'</small>') as `Name`");

		//$this->db->select("CONCAT(absen.tanggal_masuk,' ',absen.jam_masuk) as `In`");
		//$this->db->select("CONCAT(absen.tanggal_pulang,' ',absen.jam_pulang) as `Out`");
		$this->db->select("CONCAT(
			
			CONCAT('<small>In :',absen.tanggal_masuk,' ',absen.jam_masuk,'</small>'),'<br>',
			
			IFNULL(CONCAT('<small>Out :',absen.tanggal_pulang,' ',absen.tanggal_pulang,'</small>'),'') 
			
			) as `Time`");

		$this->db->select('`absen`.`status` as Status');
		$this->db->select('`absen`.`id_absen` as Aksi');
		$this->db->from('absen');
		$this->db->join("karyawan", "`karyawan`.`id_karyawan` = `absen`.`id_karyawan`", "left");
		$this->db->where(array(
			'absen.hapus' => 0,
			//"STR_TO_DATE(waktu,'%d/%m/%Y') >=" => $tanggal_awal,
			//"STR_TO_DATE(waktu,'%d/%m/%Y') <=" => $tanggal_ahir,
			'absen.tanggal_masuk >=' => $tanggal_awal,
			'absen.tanggal_masuk <=' => $tanggal_ahir,

		));
		if ($id_karyawan != '') {
			$this->db->where('absen.id_karyawan', $id_karyawan);
		}
		return $this->db->order_by('absen.tanggal_masuk ASC');

	}

	public function getCeklis($tanggal_awal = '', $tanggal_ahir = '', $id_asset = '')
	{
		$this->db->select(
			'0 as No,
			`periksa`.`tanggal` as `date`,
			`asset`.`nomor` as `Number/Code`,
			`asset`.`nama` as `Name`,
			`periksa`.`ket` as `Note`,
			`admin`.`nama_admin` as `By`,
			');
		$this->db->select('id_periksa as Action')
			->join('asset', 'asset.id_asset=periksa.id_asset')
			->join('admin', 'admin.id_admin=periksa.id_admin')
			->where(array(
				'periksa.hapus' => 0,
				//'id_asset' => $id_asset,
				'periksa.tanggal >=' => $tanggal_awal,
				'periksa.tanggal <=' => $tanggal_ahir,
				'periksa.id_karyawan' => $this->session->id_karyawan,
			));
		if ($id_asset != '') {
			$this->db->where(array('id_asset' => $id_asset));
		}
		return $this->db->from('periksa');
	}
	public function getJadwalAsset($tanggal_awal,$tanggal_ahir,$nomor="")
	{
		$this->db->select(
			'0 as No');
		$this->db->select('asset.nomor as `Code Equipment`');
		$this->db->select('asset.nama as `Name Equipment`');
		$this->db->select('jadwal.tanggal_awal as Date');
		$this->db->select('IFNULL(periksa.id_periksa,progress) as Progress');
		$this->db->select('jadwal.id_jadwal as Action');
		$this->db->join('asset', '`jadwal`.`id_asset` = `asset`.`id_asset`');
		$this->db->join('(SELECT * FROM periksa WHERE hapus=0) periksa',
			'`jadwal`.`id_jadwal` = `periksa`.`id_jadwal`','left')
			->where(array(
				'jadwal.hapus' => 0,
				'jadwal.tanggal_awal >=' => $tanggal_awal,
				'jadwal.tanggal_ahir <=' => $tanggal_ahir,
				'jadwal.tipe' => '1',
			))
			->order_by('jadwal.tanggal_awal ASC')
		;
		if($nomor!=""){
			$this->db->where('asset.nomor',$nomor);
		}
		$this->db->having('Progress',0);

		return $this->db->from('jadwal');
	}
	public function getPerintahAssetTambah($id_perintah)
	{
		$this->db->select(
			'perintah_detail.id
			,perintah_detail.nama
			,perintah_detail.tipe
			,"" as value 
			'
		);
		$this->db
			->where(array(
				'perintah_detail.hapus' => 0,
			));
		if ($id_perintah != '') {
			$this->db->where(array('id_perintah' => $id_perintah));
		}
		return $this->db->from('perintah_detail');
	}

	public function getPerintahAssetEdit($id_periksa)
	{
		$this->db->select(
			'perintah_detail.id
			,perintah_detail.nama
			,perintah_detail.tipe
			,periksa_detail.value 
			,periksa_detail.file 
			'
		);
		$this->db
			->join('perintah_detail', 'periksa_detail.id_perintah_detail=perintah_detail.id')
			->where(array(
				'periksa_detail.hapus' => 0,
				'perintah_detail.hapus' => 0,
			));
		if ($id_periksa != '') {
			$this->db->where(array('periksa_detail.id_periksa' => $id_periksa));
		}
		return $this->db->from('periksa_detail');
	}


}

?>
