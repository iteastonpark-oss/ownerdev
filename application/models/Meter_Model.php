<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Meter_Model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
		$this->load->database();

	}

	public function getRekening($id_tag)
	{
		$this->db->select('0 as No
        , `db_unit`.`kode` as `Unit`
        , `utility_rekening`.`rekening` as `Water Account`
        , `db_tag`.`nama` as `Uitlity Name`
        , MAX(utility.meter) as `Last Meter`
        , `utility_rekening`.`id_rekening` as `Action`')
			->join('(SELECT * FROM db_tag where tagihan=2) db_tag', '`utility_rekening`.`id_tag` = `db_tag`.`id_tag`')
			->join('db_unit', '`utility_rekening`.`id_unit` = `db_unit`.`id_unit`')
			->join('utility', '`utility`.`id_rekening` = `utility_rekening`.`id_rekening`')
			->where(array(
				'utility_rekening.hapus' => 0,
				'utility_rekening.id_tag' => $id_tag,
				'utility.hapus' => 0,

				'db_unit.hapus' => 0,
				//'db_unit.id_group' => '1',
			))->group_by('utility.id_rekening');
		return $this->db->from('utility_rekening');

	}

	public function getMeter($id_tag, $bulan, $tahun)
	{
		$id_unit = $this->db->select('id_unit')->from('bast')
			->where('id_bast', $this->session->id_bast)->get()->row()->id_unit;
		$this->db->select('
        `utility`.`bulan` as Month
     	, (`utility`.`meter`-`utility`.`pakai`) as `Start (M3)`
        , `utility`.`meter` as `End (M3)`
        , `utility`.`pakai` as `Usage (M3)`')
			//->join('bast', '`utility_rekening`.`id_bast` = `bast`.`id_bast`')
			->join('db_unit', '`utility_rekening`.`id_unit` = `db_unit`.`id_unit`')
			//	->join('pemilik', '`bast`.`id_pemilik` = `pemilik`.`id_pemilik`')
			->join('utility', '`utility`.`id_rekening` = `utility_rekening`.`id_rekening`')
			->where(array(
				'utility_rekening.hapus' => 0,
				'utility_rekening.id_tag' => $id_tag,
				'utility.hapus' => 0,

				'db_unit.hapus' => 0,
				'db_unit.id_unit' => $id_unit,
				//'db_unit.id_group' => '1',
			));

		if ($bulan != '') {
			$this->db->where(array('utility.bulan' => $bulan));
		}
		if ($tahun != '') {
			$this->db->where(array('utility.tahun' => $tahun));
		}
		return $this->db->from('utility_rekening');

	}
	public function getMeterRupiah($id_tag, $bulan, $tahun)
	{
		$id_unit = $this->db->select('id_unit')->from('bast')
			->where('id_bast', $this->session->id_bast)->get()->row()->id_unit;

		$this->db->select('
        `utility`.`bulan` as Month
     	, (`utility`.`meter`-`utility`.`pakai`) as `Start (M3)`
        , `utility`.`meter` as `End (M3)`
        , `utility`.`pakai` as `Usage (M3)`
			, IFNULL(`billing_detail`.`jumlah`,`db_tag`.`jumlah`) as `bill`')

			->join('db_unit', '`utility_rekening`.`id_unit` = `db_unit`.`id_unit`')
			//	->join('pemilik', '`bast`.`id_pemilik` = `pemilik`.`id_pemilik`')
			->join('utility', '`utility`.`id_rekening` = `utility_rekening`.`id_rekening`')
			->join('db_tag', '`utility_rekening`.`id_tag` = `db_tag`.`id_tag`')
			->join('billing_detail', '`billing_detail`.`id_detail` =`utility`.`id_billing_detail`','left')
			->where(array(
				'utility_rekening.hapus' => 0,
				'utility_rekening.id_tag' => $id_tag,
				'utility.hapus' => 0,

				'db_unit.hapus' => 0,
				'db_unit.id_unit' => $id_unit,
				//'db_unit.id_group' => '1',
			));

		if ($bulan != '') {
			$this->db->where(array('utility.bulan' => $bulan));
		}
		if ($tahun != '') {
			$this->db->where(array('utility.tahun' => $tahun));
		}
		return $this->db->from('utility_rekening');

	}

	public function getMeterImport($id_tag, $bulan, $tahun, $status)
	{
		$this->db->select('0 as No
           , `db_unit`.`kode`
        , `utility_rekening`.`rekening` as `Account`
     	, (`utility`.`meter`-`utility`.`pakai`) as `Start (M3)`
        , `utility`.`meter` as `End (M3)`
        , `utility`.`pakai` as `Usage(M3)`
        , `utility`.`bulan` as `Month`
        , `utility`.`tahun` as `Year`
        , 1 as status
        , `utility`.`id_meter` as `Action`')
			->join('db_unit', '`utility_rekening`.`id_unit` = `db_unit`.`id_unit`')
			->join('utility', '`utility`.`id_rekening` = `utility_rekening`.`id_rekening`')
			->where(array(
				'utility_rekening.hapus' => 0,
				'utility_rekening.id_tag' => $id_tag,
				'utility.hapus' => 0,
				'db_unit.hapus' => 0,
				'db_unit.id_group' => '1',
				'utility.import' => '1',
			));

		if ($bulan != '') {
			$this->db->where(array('utility.bulan' => $bulan));
		}
		if ($tahun != '') {
			$this->db->where(array('utility.tahun' => $tahun));
		}
		$q1 = $this->db->from('utility_rekening')->get_compiled_select();


		$this->db->select('0 as No
           , `db_unit`.`kode`
        , `utility_rekening`.`rekening` as `Account`
     	, (`utility`.`meter`-`utility`.`pakai`) as `Start(M3)`
        , `utility`.`meter` as `End (M3)`
        , `utility`.`pakai` as `Usage(M3)`
        , `utility`.`bulan` as `Month`
        , `utility`.`tahun` as `Year`
        , 2 as status
        , `utility`.`id_meter` as `Action`')
			->join('db_unit', '`utility_rekening`.`id_unit` = `db_unit`.`id_unit`')
			->join('utility_error utility', '`utility`.`id_rekening` = `utility_rekening`.`id_rekening`')
			->where(array(
				'utility_rekening.hapus' => 0,
				'utility_rekening.id_tag' => $id_tag,
				'db_unit.hapus' => 0,
				'utility.hapus' => 0,
				'db_unit.id_group' => '1',
				'utility.import' => '1',
			));

		if ($bulan != '') {
			$this->db->where(array('utility.bulan' => $bulan));
		}
		if ($tahun != '') {
			$this->db->where(array('utility.tahun' => $tahun));
		}
		$q2 = $this->db->from('utility_rekening')->get_compiled_select();

		$union = $q1 . " UNION " . $q2;
		$this->db->select('*');
		if ($status != "") {
			$this->db->where('status', $status);
			if ($status == '2') {
				$this->db->having('`Usage(M3)` < ', '0');
			}
		}
		return $this->db->from("(" . $union . ") utility");

	}

	public function getMeterImportExport($id_tag, $bulan, $tahun, $status)
	{
		$this->db->select('0 as No
           , `db_unit`.`kode`
        , `utility_rekening`.`rekening` as `Account`
     	, (`utility`.`meter`-`utility`.`pakai`) as `Start (M3)`
        , `utility`.`meter` as `End (M3)`
        , `utility`.`pakai` as `Usage(M3)`
        , `utility`.`bulan` as `Month`
        , `utility`.`tahun` as `Year`
        , 1 as status')
			->join('db_unit', '`utility_rekening`.`id_unit` = `db_unit`.`id_unit`')
			->join('utility', '`utility`.`id_rekening` = `utility_rekening`.`id_rekening`')
			->where(array(
				'utility_rekening.hapus' => 0,
				'utility_rekening.id_tag' => $id_tag,
				'utility.hapus' => 0,
				'db_unit.hapus' => 0,
				'db_unit.id_group' => '1',
				'utility.import' => '1',
			));

		if ($bulan != '') {
			$this->db->where(array('utility.bulan' => $bulan));
		}
		if ($tahun != '') {
			$this->db->where(array('utility.tahun' => $tahun));
		}
		$q1 = $this->db->from('utility_rekening')->get_compiled_select();


		$this->db->select('0 as No
           , `db_unit`.`kode`
        , `utility_rekening`.`rekening` as `Account`
     	, (`utility`.`meter`-`utility`.`pakai`) as `Start(M3)`
        , `utility`.`meter` as `End (M3)`
        , `utility`.`pakai` as `Usage(M3)`
        , `utility`.`bulan` as `Month`
        , `utility`.`tahun` as `Year`
        , 2 as status')
			->join('db_unit', '`utility_rekening`.`id_unit` = `db_unit`.`id_unit`')
			->join('utility_error utility', '`utility`.`id_rekening` = `utility_rekening`.`id_rekening`')
			->where(array(
				'utility_rekening.hapus' => 0,
				'utility_rekening.id_tag' => $id_tag,
				'db_unit.hapus' => 0,
				'utility.hapus' => 0,
				'db_unit.id_group' => '1',
				'utility.import' => '1',
			));

		if ($bulan != '') {
			$this->db->where(array('utility.bulan' => $bulan));
		}
		if ($tahun != '') {
			$this->db->where(array('utility.tahun' => $tahun));
		}
		$q2 = $this->db->from('utility_rekening')->get_compiled_select();

		$union = $q1 . " UNION " . $q2;
		$this->db->select('*');
		if ($status != "") {
			$this->db->where('status', $status);
			if ($status == '2') {
				$this->db->having('`Usage(M3)` < ', '0');
			}
		}
		return $this->db->from("(" . $union . ") utility")->get();

	}


	public function getMeterRekap($id_tag, $tahun)
	{
		$id_unit = $this->db->select('id_unit')->from('bast')
			->where('id_bast', $this->session->id_bast)->get()->row()->id_unit;
		$tahun_s = (int)$tahun - 1;
		$this->db->select('0 as No
           , `db_unit`.`kode` as `No Unit`
        , `utility_rekening`.`rekening` as `No Rekening`');

		$this->db->select("MAX(IF(tahun <'" . $tahun . "', utility.meter, 0)) as `" . $tahun_s . "` ");
		$b = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
		for ($i = 0; $i < count($b); $i++) {

			$this->db->select("MAX(IF(tahun = '" . $tahun . "' AND bulan='" . $b[$i] . "', utility.meter, 0)) as `" . $b[$i] . "` ");
			//$this->db->select("MAX(IF(tahun = '" . $tahun . "' AND bulan='" . $b[$i] . "', pakai, 0)) as `" . $b[$i] . "_` ");

			if ($b[$i] == '01') {
				$this->db->select("MAX(IF(tahun = '" . $tahun . "' AND bulan='" . $b[$i] . "', utility.meter, 0)) 
				- MAX(IF(tahun <'" . $tahun . "', utility.meter, 0)) as `" . $b[$i] . "_` ");
			} else {
				$this->db->select("MAX(IF(tahun = '" . $tahun . "' AND bulan='" . $b[$i] . "', utility.meter, 0)) 
				- MAX(IF(tahun = '" . $tahun . "' AND bulan='" . $b[($i - 1)] . "', utility.meter, 0)) as `" . $b[$i] . "_` ");

			}

		}
		$this->db->join('db_unit', '`utility_rekening`.`id_unit` = `db_unit`.`id_unit`')
			//->join('pemilik', '`bast`.`id_pemilik` = `pemilik`.`id_pemilik`')
			->join('utility', '`utility`.`id_rekening` = `utility_rekening`.`id_rekening`')
			->where(array(
				'utility_rekening.hapus' => 0,
				'utility.hapus' => 0,
				'utility_rekening.id_tag' => $id_tag,
				'db_unit.hapus' => 0,
				'db_unit.id_unit' => $id_unit,
				//'db_unit.id_group' => '1',
			));

		$this->db->group_by('utility_rekening.id_rekening');
		return $this->db->from('utility_rekening');

	}


	public function getDataUnitExportTemplate($id_tag)
	{
		$this->db->select('`db_unit`.`kode` as `No Unit`');
		$this->db->select('`utility`.`meter` as `Meter Terakhir`');
		$this->db->select("DATE_FORMAT(MAX(utility.tanggal), '%d-%m-%Y') as `Pencatatan Terakhir`");
		$this->db->from('utility_rekening')
			->join('db_unit', '`utility_rekening`.`id_unit` = `db_unit`.`id_unit`')
			->join('utility', '`utility`.`id_rekening` = `utility_rekening`.`id_rekening`')
			->where(array(
				'utility_rekening.hapus' => 0,
				'utility_rekening.id_tag' => $id_tag,
				'db_unit.hapus' => 0,
				'db_unit.id_group' => '1',
			));
		$this->db->group_by('`db_unit`.`id_unit`');
		return $this->db->get();
	}

}
