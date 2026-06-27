<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Job_Model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function getWo()
	{
		$this->db->select(
			'0 as No,
			`db_unit`.`kode` as `Kode Unit`,
			`tiket`.`no_form` as `No Form`,
			`tiket`.`ket` as `Keterangan,
			`tiket`.`tanggal` as `Tanggal`,
			`admin`.`nama_admin` as `Dibuat`
			');
		$this->db->select('tanggal_awal as `tanggal Awal`');
		$this->db->select('tanggal_ahir as `tanggal Akhir`');
		$this->db->select('id_tiket as Aksi');
		$this->db->join('admin', 'admin.id_admin=tiket.id_admin');
		$this->db->join('bast', 'bast.id_bast=tiket.id_bast')
			->join('db_unit', 'db_unit.id_unit=bast.id_unit')
			->join('pemilik', 'pemilik.id_pemilik=bast.id_pemilik')
			->where(array(
				'tiket.hapus' => 0,
				'tiket.tipe' => 5,
				'tiket.approv' => 1,
				'tiket.post' => 3,
			));
		return $this->db->from('tiket');
	}

	public function getJob($status)
	{
		$this->db->select(
			'0 as No,
			`db_unit`.`kode` as `Kode Unit`,
			`tiket`.`no_form` as `No Form`,
			`tiket`.`ket` as `Keterangan,
			`tiket`.`tanggal` as `Tanggal`,
			`admin`.`nama_admin` as `Dibuat`
			');
		$this->db->select('tanggal_awal as `tanggal Awal`');
		$this->db->select('tanggal_ahir as `tanggal Akhir`');
		$this->db->select('id_tiket as Aksi');
		$this->db->join('admin', 'admin.id_admin=tiket.id_admin');
		$this->db->join('bast', 'bast.id_bast=tiket.id_bast')
			->join('db_unit', 'db_unit.id_unit=bast.id_unit')
			->join('pemilik', 'pemilik.id_pemilik=bast.id_pemilik')
			->where(array(
				'tiket.hapus' => 0,
				'tiket.tipe' => $status,
				'tiket.approv' => 1,
				'tiket.post' => 3,

			));
		return $this->db->from('tiket');
	}

	public function getJadwalAsset($tanggal_awal,$tanggal_ahir,$nomor="")
	{
		$this->db->select(
			'0 as No');
		/*
			$this->db->select('CONCAT(`asset`.`nama`,"<br><small>",`asset`.`nomor`,"</small>") as `Name Equipment`,
  `jadwal`.`tanggal_awal` as `Date`,
  `jadwal`.`progress` as `Progress`
			');
		*/
		$this->db->select('asset.nomor as `Code Equipment`');
		$this->db->select('asset.nama as `Name Equipment`');
		$this->db->select('jadwal.tanggal_awal as Date');
		//$this->db->select('jadwal.progress as Progress');
		//$this->db->select('periksa.id_periksa as Progress');
		$this->db->select('IFNULL(periksa.id_periksa,progress) as Progress');
		$this->db->select('jadwal.id_jadwal as Action');
		$this->db->join('asset', '`jadwal`.`id_asset` = `asset`.`id_asset`');
		/*
		$this->db->join('(SELECT * FROM periksa WHERE hapus=0) periksa',
			'`jadwal`.`id_asset` = `periksa`.`id_asset` AND `jadwal`.`tanggal_awal` = `periksa`.`tanggal`','left')
		*/
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
		return $this->db->from('jadwal');
	}
	public function getJadwal($tahun)
	{
		$this->db->select(
			'0 as No,
			`event`.`nama` as `Name`,
  `event`.`ket` as `Ket`,
  `jadwal`.`tanggal_awal` as `Start`,
  `jadwal`.`tanggal_ahir` as `End`,
  `jadwal`.`progress` as `Progress`
			');
		$this->db->select('id_jadwal as Action');
		$this->db->join('jadwal', '`jadwal`.`id_event` = `event`.`id_event`')
			->where(array(
				'jadwal.hapus' => 0,
				'jadwal.tahun' => $tahun,
				'jadwal.tipe' => '1',
			));
		return $this->db->from('event');
	}

	public function getJadwalRencanaKerja($tanggal_awal, $tanggal_ahir, $id_dep)
	{
		$this->db->select('0 as No,
  `jadwal`.`judul` as `Title`,
  `db_dep`.`nama` as `Departement`,
  `jadwal`.`tanggal_awal` as `Start`,
  `jadwal`.`tanggal_ahir` as `End`,
  `jadwal`.`lokasi` as `Location`,
  `jadwal`.`pic` as `PIC`,
  `jadwal`.`progress` as `Progress`');
		$this->db->select('id_jadwal as Action');
		$this->db->join('db_dep', '`db_dep`.`id_dep` = `jadwal`.`id_dep`', 'left')
			->where(array(
				'jadwal.hapus' => 0,
				//'jadwal.tanggal_awal >=' => $tanggal_awal,
				//'jadwal.tanggal_ahir <=' => $tanggal_ahir,
				'jadwal.tipe' => '2',
			));
		if ($id_dep != '') {
			$this->db->where(array('jadwal.id_dep' => $id_dep));
		}
		return $this->db->from('jadwal');
	}

	public function getJadwalRencanaKerjaMingguan($id_dep)
	{
		$this->db->select('0 as No,
  `jadwal_group`.`nama` as `Title`,
 `db_dep`.`nama` as `Departement`');
		$this->db->select('jadwal_group.tanggal_awal as Start');
		$this->db->select('jadwal_group.tanggal_ahir as End');
		$this->db->select('id_group as Action');
		$this->db->join('db_dep', '`db_dep`.`id_dep` = `jadwal_group`.`id_dep`', 'left')
			->where(array(
				'jadwal_group.hapus' => 0,
				'jadwal_group.tipe' => '1',
			));
		if ($id_dep != '') {
			$this->db->where(array('jadwal_group.id_dep' => $id_dep));
		}
		$this->db->order_by('jadwal_group.tanggal_awal DESC');
		return $this->db->from('jadwal_group');
	}

	public function getJadwalRencanaKerjaByGroup($id_group)
	{
		$this->db->select('0 as No,
  `jadwal`.`judul` as `Title`,
  `jadwal`.`tanggal_awal` as `Start`,
  `jadwal`.`tanggal_ahir` as `End`,
  `jadwal`.`lokasi` as `Location`,
  `jadwal`.`pic` as `PIC/Category`,
  `jadwal`.`progress` as `Progress`');
		$this->db->select('id_jadwal as Action');
		$this->db->join('db_dep', '`db_dep`.`id_dep` = `jadwal`.`id_dep`', 'left')
			->where(array(
				'jadwal.hapus' => 0,
				//'jadwal.tanggal_awal >=' => $tanggal_awal,
				//'jadwal.tanggal_ahir <=' => $tanggal_ahir,
				'jadwal.tipe' => '2',
			));
		if ($id_group != '') {
			$this->db->where(array('jadwal.id_group' => $id_group));
		}
		return $this->db->from('jadwal');
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
			));
		if ($id_asset != '') {
			$this->db->where(array('id_asset' => $id_asset));
		}
		return $this->db->from('periksa');
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
	
	public function getJadwalPeriksaEdit($id_jadwal)
	{
		$this->db->select(
			'perintah_detail.id
			,perintah_detail.nama
			,perintah_detail.tipe
			,jadwal_periksa.value 
			'
		);
		$this->db
			->join('perintah_detail', 'jadwal_periksa.id_perintah_detail=perintah_detail.id')
			->where(array(
				'jadwal_periksa.hapus' => 0,
				'perintah_detail.hapus' => 0,
			));
		if ($id_jadwal != '') {
			$this->db->where(array('jadwal_periksa.id_jadwal' => $id_jadwal));
		}
		return $this->db->from('jadwal_periksa');
	}


	/**
	 * intruksi mperawatan
	 */


}
