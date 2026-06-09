<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Bast_Model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
		$this->load->database();

	}

	public function getBastUnit($group = '1')
	{

		$this->db->select('0 as No
        , `db_unit`.`kode` as `No Unit`
        , `db_unit`.`luas` as `Larga`
        , `bast`.`tanggal` as `Bast Date`
        , `pemilik`.`nama` as `Name Of Owner`
        , `pemilik`.`alamat` as `Address`
        , `pemilik`.`kab` as `City`
        , `id_bast` as `Action`')
			->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`')
			->join('pemilik', '`bast`.`id_pemilik` = `pemilik`.`id_pemilik`')
			->where(array(
				'bast.hapus' => 0,
				'db_unit.hapus' => 0,
				'db_unit.id_group' => $group,
			));
		return $this->db->from('bast');

	}

	public function getBastUnitExport()
	{

		$this->db->select('0 as No
        , `db_unit`.`kode` as `No Unit`
        , `db_unit`.`luas` as `Luas`
        , `db_unit`.`tipe` as `Tipe`
        , `db_unit`.`tower` as `Tower`
        , `bast`.`tanggal` as `Tanggal Bast`
        , `pemilik`.`nama` as `Nama Pemilik`
        , `pemilik`.`alamat` as `Alamat`
        , `pemilik`.`kab` as `Kota/Kab`
        , `bast`.`tangan` as `KePemilikan`
        , `pemilik`.`hp` as `hp`
        , `pemilik`.`tlp` as `tlp`
        , `pemilik`.`email` as `emailPemilik`
        
        , `bast`.`alamat_surat` as `AlamatSurat`
        , `bast`.`kota_surat` as `KotaSurat`
        , `bast`.`email_surat` as `EmailSurat`
        , `bast`.`hp_surat` as `HpSurat`
        , `bast`.`tlp_surat` as `TlpSurat`
        , `bast`.`wa_surat` as `WhatsappSurat`
        , CONCAT("VA",`db_unit`.`va`) as `VA`
        
        ')
		
		->select('pemilik.kantor')
		->select('pemilik.jenis_kelamin')

			->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`')
			->join('pemilik', '`bast`.`id_pemilik` = `pemilik`.`id_pemilik`')
			->where(array(
				'bast.hapus' => 0,
				'db_unit.hapus' => 0,
				'db_unit.id_group' => '1',
			));
		return $this->db->from('bast');
	}
	public function getBelumBastUnitExport()
	{

		$this->db->select('0 as No
        , `db_unit`.`kode` as `No Unit`
        , `db_unit`.`luas` as `Luas`
        , `db_unit`.`tipe` as `Tipe`
        , IFNULL(id_bast,0) as `status`
        ')
			->join('(select id_bast,id_unit from bast where hapus=0) bast', '`bast`.`id_unit` = `db_unit`.`id_unit`','left')
			->where(array(
				'db_unit.hapus' => 0,
				'db_unit.id_group' => '1',
			))
			->having('status',0);
		return $this->db->from('db_unit');
	}

	public function getIzinHuni($status)
	{

		$this->db->select('0 as No
        , `db_unit`.`kode` as `No Unit`
        , `pemilik`.`nama` as `Name Of Owner`
        , `bast_huni`.`no_form` as `No Form`
        , `bast_huni`.`nama` as `Resident`
        , `db_huni`.`nama` as `Status Resident`
        , `bast_huni`.`tanggal_masuk` as `Date Of Entry`
        , `bast_huni`.`tanggal_awal` as `Start`
        , `bast_huni`.`tanggal_ahir` as `End`
        , id_tr as `Approv TR`
        , id_acc as `Approv BM`
        , `id_huni` as `Action`')
			->join('bast', '`bast_huni`.`id_bast` = `bast`.`id_bast`')
			->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`')
			->join('pemilik', '`bast`.`id_pemilik` = `pemilik`.`id_pemilik`')
			->join('db_huni', '`db_huni`.`id` = `bast_huni`.`tipe`')
			->where(array(
				'bast.hapus' => 0,
				'bast_huni.hapus' => 0,
				'db_unit.hapus' => 0,
				'db_unit.id_group' => '1',
			));
		if ($status == '5') {
			$this->db->where(array(
				'bast_huni.status' => 3,
				'bast_huni.tanggal_ahir <' => date('Y-m-d'),
			));
		} else {
			$this->db->where('bast_huni.status', $status);
		}
		/*
		if ($id_acc == 0) {
			$this->db->where('id_acc', 0);
		} else {
			$this->db->where('id_acc !=', 0);

		}
		*/
		return $this->db->from('bast_huni');

	}

	public function getIzinHuniExport($status)
	{

		$this->db->select('0 as No
        , `db_unit`.`kode` as `No Unit`
        , `pemilik`.`nama` as `Nama Pemilik`
        , `bast_huni`.`no_form` as `No Form`
        , `bast_huni`.`id_tipe` as `Tipe ID`
        , CONCAT(" ",`bast_huni`.`id_kartu`) as `ID`
        , `bast_huni`.`nama` as `Penghuni`
        , `db_huni`.`nama` as `Status Penghuni`
        , `bast_huni`.`tanggal_masuk` as `Tanggal Masuk`
        , `bast_huni`.`tanggal_awal` as `Start`
        , `bast_huni`.`tanggal_ahir` as `End`
        , `bast_huni`.`tanggal_keluar` as `Exit`
        ')
			->join('bast', '`bast_huni`.`id_bast` = `bast`.`id_bast`')
			->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`')
			->join('pemilik', '`bast`.`id_pemilik` = `pemilik`.`id_pemilik`')
			->join('db_huni', '`db_huni`.`id` = `bast_huni`.`tipe`')
			->where(array(
				'bast.hapus' => 0,
				'bast_huni.hapus' => 0,
				'db_unit.hapus' => 0,
				'db_unit.id_group' => '1',
				'bast_huni.status' => $status,
			));
		return $this->db->from('bast_huni');

	}

	public function getHuniUnit($id_bast)
	{

		$this->db->select('0 as No
        , `db_unit`.`kode` as `Unit`
        , `pemilik`.`nama` as `Owner`
        , `bast_huni`.`no_form` as `No Form`
        , `bast_huni`.`nama` as `Occupant`
        , `db_huni`.`nama` as `Status Penghuni`
        , `bast_huni`.`tanggal_masuk` as `Date Of Entry`
        , id_acc as `Acc`
        , `id_huni` as `Action`')
			->join('bast', '`bast_huni`.`id_bast` = `bast`.`id_bast`')
			->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`')
			->join('pemilik', '`bast`.`id_pemilik` = `pemilik`.`id_pemilik`')
			->join('db_huni', '`db_huni`.`id` = `bast_huni`.`tipe`')
			->where(array(
				'bast.id_bast' => $id_bast,
				'bast.hapus' => 0,
				'bast_huni.hapus' => 0,
				'db_unit.hapus' => 0,
				'db_unit.id_group' => '1',
//				'bast_huni.status' => $status,
			));
		return $this->db->from('bast_huni')->get();

	}


	public function getBerkas($id = '')
	{
		return $this->db->select('0 as No,
         `file` as `File`,
  `nama` as `File Name`,
  `id` as `Action`')
			->from('bast_berkas')
			->where(
				array(
					'id_bast' => $id,
					'hapus' => 0,
				));

	}


	public function getBerkasHuniTambah($nama)
	{
		return $this->db->select('
    `db_upload`.`nama`,
  	`db_upload`.`id_upload`,
  	`db_upload`.`folder`')
			->from('`set_upload`')
			->join('db_upload', '`set_upload`.`id_upload` = `db_upload`.`id_upload`', 'inner')
			->where('set_upload.nama', $nama)
			->where('set_upload.hapus', 0)
			->order_by('db_upload.id_upload')
			->get();
	}

	public function getBerkasHuniEdit($nama, $id_huni)
	{
		return $this->db->select('
    `bast_huni_berkas`.`file`,
    IFNULL(`bast_huni_berkas`.`nama`,`db_upload`.`nama`) as nama,
  	IFNULL(`bast_huni_berkas`.`id_upload`,db_upload.id_upload) as id_upload,
  	`db_upload`.`folder`')
			->from('`set_upload`')
			->join('db_upload', '`set_upload`.`id_upload` = `db_upload`.`id_upload`', 'inner')
			->join('(SELECT * FROM bast_huni_berkas WHERE bast_huni_berkas.id_huni="' . $id_huni . ' AND hapus=0") bast_huni_berkas',
				'`bast_huni_berkas`.`id_upload` = `db_upload`.`id_upload`', 'left')
			->where('set_upload.nama', $nama)
			//->where('bast_huni_berkas.id_huni', $id_huni)
			//->where('bast_huni_berkas.hapus', 0)
			->order_by('db_upload.id_upload')
			->get();


	}

	/*
	public function getBerkasHuniEdit($nama, $id_huni)
	{
		return $this->db->select('
    `bast_huni_berkas`.`file`,
    `bast_huni_berkas`.`nama`,
  	`bast_huni_berkas`.`id_upload`,
  	`db_upload`.`folder`')
			->from('`set_upload`')
			->join('db_upload', '`set_upload`.`id_upload` = `db_upload`.`id_upload`', 'inner')
			->join('bast_huni_berkas', '`bast_huni_berkas`.`id_upload` = `db_upload`.`id_upload`', 'inner')
			->where('set_upload.nama', $nama)
			->where('bast_huni_berkas.id_huni', $id_huni)
			->where('bast_huni_berkas.hapus', 0)
			->order_by('db_upload.id_upload')
			->get();


	}
	*/

	public function getHistoriGantiPemilik($id_bast = '')
	{
		$this->db->select('0 as No')
			->select('`db_unit`.`kode` as Unit')
			->select('`bast_his_pemilik`.`tm`')
			->select('`bast_his_pemilik`.`tanggal` as Date')
			->select('`pemilik`.`nama` AS `Old Owner`')
			->select('`p`.`nama` AS `New Owner`')
			->join('`bast`', '`bast_his_pemilik`.`id_bast` = `bast`.`id_bast`')
			->join('`db_unit`', '`db_unit`.`id_unit` = `bast`.`id_unit`')
			->join('`pemilik`', '`bast_his_pemilik`.`pemilik_lama` =
			`pemilik`.`id_pemilik`')
			->join('`pemilik` `p`', '`bast_his_pemilik`.`pemilik_baru` =
			`p`.`id_pemilik`');
		if ($id_bast != '') {
			$this->db->where('bast.id_bast', $id_bast);
		}
		return $this->db->from('`bast_his_pemilik`')->get();

	}

	public function getBukuTamu($id_unit, $tanggal_awal, $tanggal_ahir)
	{
		$this->db->select('0 as No');
		$this->db->select('`tamu`.`ktp` as `ID Card`');
		$this->db->select('`db_unit`.`kode` as `Code`');
		$this->db->select('`tamu`.`tanggal` as `Date`');
		$this->db->select('`tamu`.`nama` as `Name`');
		$this->db->select('`tamu`.`kota` as `City`');
		$this->db->select('`tamu`.`status_huni` as `Status`');
		$this->db->select('`tamu`.`lama_tinggal` as `Length Of Stay`');
		$this->db->select('`tamu`.`id_tamu` as `Action`');
		//$this->db->from('tamu')
		$this->db->join('`db_unit`', '`db_unit`.`id_unit` = `tamu`.`id_unit`')
			->join('(SELECT `agent`.`nama`,
    `agent_unit`.`id_unit`
  FROM `agent`
    INNER JOIN `agent_unit` ON `agent_unit`.`id_agent` = `agent`.`id_agent`
  WHERE `agent_unit`.`hapus` = 0) `agent`', '`agent`.`id_unit` =
			`tamu`.`id_unit`', 'left');
		$this->db->where(array(
			'tamu.hapus' => 0,
			'tamu.tanggal >=' => $tanggal_awal,
			'tamu.tanggal <=' => $tanggal_ahir,
		));

		if ($id_unit != '') {
			$this->db->where("tamu.id_unit", $id_unit);
		}
		return $this->db->from('tamu');

	}

	public function getBukuTamuExport($id_unit, $tanggal_awal, $tanggal_ahir)
	{
		$this->db->select('0 as No');
		$this->db->select('`tamu`.`ktp`');
		$this->db->select('`db_unit`.`kode`');
		$this->db->select('`tamu`.`tanggal`');
		$this->db->select('`tamu`.`nama`');
		$this->db->select('`tamu`.`kota`');
		$this->db->select('`tamu`.`status_huni`');
		$this->db->select('`tamu`.`lama_tinggal`');
		//$this->db->from('tamu')
		$this->db->join('`db_unit`', '`db_unit`.`id_unit` = `tamu`.`id_unit`')
			->join('(SELECT `agent`.`nama`,
    `agent_unit`.`id_unit`
  FROM `agent`
    INNER JOIN `agent_unit` ON `agent_unit`.`id_agent` = `agent`.`id_agent`
  WHERE `agent_unit`.`hapus` = 0) `agent`', '`agent`.`id_unit` =
			`tamu`.`id_unit`', 'left');
		$this->db->where(array(
			'tamu.hapus' => 0,
			'tamu.tanggal >=' => $tanggal_awal,
			'tamu.tanggal <=' => $tanggal_ahir,
		));

		if ($id_unit != '') {
			$this->db->where("tamu.id_unit", $id_unit);
		}
		return $this->db->from('tamu');

	}

	/**
	 * @param $co
	 * @return mixed
	 * Unit Cut Off
	 */


	public function getBastUnitCO($co)
	{

		$this->db->select('0 as No')
			->select('CONCAT(`db_unit`.`kode`
," (",`db_unit`.`luas`,")"
,"<br><small>"
,`pemilik`.`nama`
,"</small>"
,"<br><small>BAST : "
,date_format(`bast`.`tanggal`,"%d-%m-%Y")
,"</small><br>"
,"") as `No Unit`')
			//	->select('CONCAT(`pemilik`.`alamat`," - ",`pemilik`.`kab`) as `Address`')
			->select('`bast.co` as `Utilites Cutt Off`
        , `id_bast` as `Action`')
			->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`')
			->join('pemilik', '`bast`.`id_pemilik` = `pemilik`.`id_pemilik`')
			->where(array(
				'bast.hapus' => 0,
				'db_unit.hapus' => 0,
				'db_unit.id_group' => '1',
			));
		if ($co != '') {
			$this->db->where('co', $co);
		}
		return $this->db->from('bast');

	}

	public function getBastUnitApprovCO()
	{
		$this->db->select('0 as No')
			->select('CONCAT(`db_unit`.`kode`," (",`db_unit`.`luas`,")"
,"<br><small>",`pemilik`.`nama`,"</small>"
,"<br><small>BAST : ",date_format(`bast`.`tanggal`,"%d-%m-%Y")
,"</small><br>","") as `No Unit`')
			->select('`co.ket` as `Note`,`co.piutang` as `Bill`, `id_co` as `Action`')
			->join('bast', '`bast`.`id_bast` = `co`.`id_bast`')
			->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`')
			->join('pemilik', '`bast`.`id_pemilik` = `pemilik`.`id_pemilik`')
			->where(array(
				'bast.hapus' => 0,
				'co.hapus' => 0,
				'co.approv' => 0,
				'co.status' => 0,
				'db_unit.hapus' => 0,
				'db_unit.id_group' => '1',
			));

		return $this->db->from('co');

	}

	/**
	 *
	 *
	 * PBB
	 */

	public function getDataPBB($id_unit)
	{
		$this->db->select('0 as No');
		$this->db->select('`db_unit`.`kode` as unit');
		$this->db->select('`pbb`.`nop`');
		//$this->db->select('`bast`.`nama`');
		for ($i = 2016; $i <= date('Y'); $i++) {
			$this->db->select('SUM(IF(`pbb_detail`.`tahun` = "' . $i . '", `tagihan`, 0)) AS `' . $i . '`');
		}
		$this->db->select('`pbb`.`id_pbb` as Action');


		$this->db->join('`db_unit`', '`db_unit`.`id_unit` = `pbb`.`id_unit`')
			/*
			->join('(SELECT `id_bast`, `pemilik`.`nama`, `id_unit` FROM `bast`
        INNER JOIN `pemilik` ON `pemilik`.`id_pemilik` = `bast`.`id_pemilik` WHERE `bast`.`hapus` = 0) bast',
				'`db_unit`.`id_unit` = `bast`.`id_unit`', 'left')
			*/
			->join('`pbb_detail`', '`pbb_detail`.`id_pbb` = `pbb`.`id_pbb`')
			->group_by('`pbb_detail`.`id_pbb`');
		if ($id_unit != '') {
			$this->db->where(array('db_unit.id_unit' => $id_unit));
		}
		return $this->db->from('pbb');

	}


}
