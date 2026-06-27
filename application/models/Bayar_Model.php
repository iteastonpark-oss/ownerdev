<?php

/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 4/8/2016
 * Time: 2:42 PM
 */
?>
<?php defined('BASEPATH') or exit('No direct script access allowed');

class Bayar_Model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/**
	 * @param $id_unit
	 * @return string
	 *
	 * DATA INVOICE
	 */
	public function getDataInvoiceBayar($id_unit)
	{

		$this->db->select('billing`.`invoice`');
		$this->db->select("`billing_detail`.`id_billing`");
		$this->db->select("`billing`.`tanggal_terbit` as `tanggal`");
		$this->db->select("`billing`.`tanggal_jt`");
		//$this->db->select('SUM(`billing_detail`.`jumlah`) as `tagihan`');
		$this->db->select('SUM(IF(billing_detail.id_tag != 11,`billing_detail`.`jumlah`,0)) as `tagihan`');
		$this->db->select('SUM(IF(billing_detail.id_tag = 11,`billing_detail`.`jumlah`,0)) as `denda`');

		$this->db->select('0 as `bayar`');
		$this->db->select("billing_detail.status as `status`");
		$this->db->select("billing.id_billing as `id`");

		$this->db->from('billing_detail');
		$this->db->join("bast", "`billing_detail`.`id_bast` = `bast`.`id_bast`", "inner");
		$this->db->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`', 'inner');
		$this->db->join(
			"(SELECT * FROM billing where hapus=0)`billing`",
			"`billing_detail`.`id_billing` = `billing`.`id_billing`",
			"left"
		);
		$this->db->where(array(
			'bast.hapus' => 0,
			'billing_detail.hapus' => 0,
			'billing_detail.status' => 1,
		));
		if ($id_unit != '') {
			$this->db->where("bast.id_unit", $id_unit);
		}
		$q1 = $this->db->group_by("`billing_detail`.`id_billing`")->get_compiled_select();


		$this->db->select('bayar`.`kwt` as `invoice`');
		$this->db->select("`billing_detail`.`id_billing`");
		$this->db->select("`bayar`.`tanggal` as `tanggal`");
		$this->db->select("'' as `tanggal_jt`");
		$this->db->select('0 as `tagihan`');
		$this->db->select('0 as `denda`');
		$this->db->select('`bayar`.`jumlah` as `bayar`');

		$this->db->select("billing_detail.status as `status`");
		$this->db->select("bayar.id_bayar as id");
		$this->db->from('billing_detail');
		$this->db->join("bayar", "`bayar`.`id_bayar` = `billing_detail`.`id_bayar`", "inner");
		$this->db->join("bast", "`billing_detail`.`id_bast` = `bast`.`id_bast`", "inner");
		$this->db->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`', 'inner');
		$this->db->where(array(
			'bast.hapus' => 0,
			'bayar.hapus' => 0,
			'billing_detail.hapus' => 0,
			'billing_detail.status' => 2,
		));
		if ($id_unit != '') {
			$this->db->where("bast.id_unit", $id_unit);
		}
		$q2 = $this->db->group_by("`billing_detail`.`id_bayar`")->get_compiled_select();


		$this->db->select('cn`.`form` as `invoice`');
		$this->db->select("`billing_detail`.`id_billing`");
		$this->db->select("`cn`.`tanggal` as `tanggal`");
		$this->db->select("'' as `tanggal_jt`");
		$this->db->select('0 as `tagihan`');
		$this->db->select('0 as `denda`');
		$this->db->select('`cn`.`jumlah` as `bayar`');

		$this->db->select("billing_detail.status as `status`");
		$this->db->select("cn.id_cn as `id`");
		$this->db->from('billing_detail');
		$this->db->join("cn", "`cn`.`id_cn` = `billing_detail`.`id_cn`", "inner");
		$this->db->join("bast", "`billing_detail`.`id_bast` = `bast`.`id_bast`", "inner");
		$this->db->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`', 'inner');
		$this->db->where(array(
			'bast.hapus' => 0,
			'cn.hapus' => 0,
			'billing_detail.hapus' => 0,
			'billing_detail.status' => 3,
		));
		if ($id_unit != '') {
			$this->db->where("bast.id_unit", $id_unit);
		}
		$q3 = $this->db->group_by("`billing_detail`.`id_cn`")->get_compiled_select();

		//return "(" . $q1 . " UNION " . $q2 . ") as tb";
		return "((" . $q1 . ") UNION (" . $q2 . ") UNION (" . $q3 . ") ) as tb";
	}

	public function getDataBillingInvoice($id_unit)
	{

		$from = $this->getDataInvoiceBayar($id_unit);
		return $this->db->select('0 as `No`,invoice as `Form`')
			//->select('DATE_FORMAT(`tanggal`, "%Y-%m") as `Date`')
			->select('`tanggal` as `Date`')
			->select('tanggal_jt  as `Due Date`')
			->select('SUM(tagihan) as `Invoice`')
			->select('SUM(denda) as `Fine`')
			->select('SUM(bayar) as `Payment/CN`,0 as `Balance`')
			->select('status')
			->select('id as Action')
			->from($from)
			//->group_by("DATE_FORMAT(`tanggal`, '%Y-%m')")
			->group_by("invoice")
			->order_by('tanggal,status');
	}

	public function getDataBillingTotal($id_unit)
	{
		$from = $this->getDataInvoiceBayar($id_unit);
		return $this->db->select('SUM(tagihan) as `tagihan`')
			->select('SUM(denda) as `denda`')
			->select('SUM(bayar) as `bayar`')
			->select('SUM(tagihan)+SUM(denda)-SUM(bayar) as `piutang`')
			->from($from)->get();
	}

	public function getDataBayar($id_unit, $tanggal_awal, $tanggal_ahir)
	{

		$this->db->select('0 as No');
		$this->db->select('db_unit`.`kode` as Unit');
		$this->db->select('bayar`.`kwt`');
		$this->db->select("`bayar`.`tanggal` as Date");
		$this->db->select('`bayar`.`jumlah` as `Amount Paid`');
		$this->db->select('db_via`.`nama` as `Payment With`');
		$this->db->select('SUM(IF(id_tag IS NULL || id_tag=0 || id_tag=12 || id_tag=20 || id_tag=30,billing_detail.jumlah,0)) as `Un Allocation`');
		$this->db->select('SUM(IF(id_tag=1,billing_detail.jumlah,0)) as `SF`');
		$this->db->select('SUM(IF(id_tag=2,billing_detail.jumlah,0)) as `SC`');
		$this->db->select('SUM(IF(id_tag=3,billing_detail.jumlah,0)) as `Adm Bank`');
		$this->db->select('SUM(IF(id_tag=4,billing_detail.jumlah,0)) as `Utility`');
		$this->db->select('SUM(IF(id_tag=10,billing_detail.jumlah,0)) as `Electricity`');
		$this->db->select('SUM(IF(id_tag=9,billing_detail.jumlah,0)) as `Shipping`');
		$this->db->select('SUM(IF(id_tag=11,billing_detail.jumlah,0)) as `Fine`');
		//$this->db->select('SUM(IF(id_tag IS NULL || id_tag=0 || id_tag=12 ,billing_detail.jumlah,0)) as `UnAllocation`');

		$this->db->select('bayar`.`id_bayar` as `Action`');
		$this->db->join("bast", "`billing_detail`.`id_bast` = `bast`.`id_bast`", "inner");
		$this->db->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`', 'inner');
		$this->db->join("`bayar`", "`billing_detail`.`id_bayar` = `bayar`.`id_bayar`");
		$this->db->join("`db_via`", "`db_via`.`id_via` = `bayar`.`id_via`", "left");
		$this->db->where(array(
			'bast.hapus' => 0,
			'billing_detail.hapus' => 0,
			'billing_detail.status' => 2,
			'bayar.tanggal >=' => $tanggal_awal,
			'bayar.tanggal <=' => $tanggal_ahir,
		))->group_by('bayar.id_bayar');
		//$this->db->order_by('bayar.tanggal');
		if ($id_unit != '') {
			$this->db->where("bast.id_unit", $id_unit);
		}
		return $this->db->from('billing_detail');
	}

	public function getDataBayarExport($id_unit, $tanggal_awal, $tanggal_ahir)
	{

		$this->db->select('0 as No');
		$this->db->select('db_unit`.`kode` as Unit');
		$this->db->select('db_unit`.`luas` as Luas');
		$this->db->select('pemilik`.`nama`');
		/*
		$this->db->select('bast`.`email_surat` as Email');
		$this->db->select('bast`.`hp_surat` as Hp');
		$this->db->select('bast`.`wa_surat` as Wa');
		$this->db->select('bast`.`alamat_surat` as Alamat');
		$this->db->select('CONCAT("4329 ",`db_unit`.`va`) as `VA_CimbNiaga`');
		$this->db->select('CONCAT("88176 ",`db_unit`.`va`) as `VA_Mandiri`');
		*/
		$this->db->select('bayar`.`kwt`');
		$this->db->select("`bayar`.`tanggal` as Date");
		$this->db->select('`bayar`.`jumlah` as `Amount Paid`');
		$this->db->select('db_via`.`nama` as `Payment With`');
		/*
		$this->db->select('SUM(IF(id_tag=20,billing_detail.jumlah,0)) as `Migration`');
		$this->db->select('SUM(IF(id_tag=1,billing_detail.jumlah,0)) as `SF`');
		$this->db->select('SUM(IF(id_tag=2,billing_detail.jumlah,0)) as `SC`');
		$this->db->select('SUM(IF(id_tag=3,billing_detail.jumlah,0)) as `Adm Bank`');
		$this->db->select('SUM(IF(id_tag=4,billing_detail.jumlah,0)) as `Utility`');
		$this->db->select('SUM(IF(id_tag=10,billing_detail.jumlah,0)) as `Electricity`');
		$this->db->select('SUM(IF(id_tag=9,billing_detail.jumlah,0)) as `Shipping`');
		$this->db->select('SUM(IF(id_tag=11,billing_detail.jumlah,0)) as `Fine`');
		$this->db->select('SUM(IF(id_tag IS NULL,billing_detail.jumlah,0)) as `UnAllocation`');

		$this->db->select('bayar`.`id_bayar` as `Action`');
		*/
		$this->db->join("bast", "`billing_detail`.`id_bast` = `bast`.`id_bast`", "inner");
		$this->db->join("pemilik", "`pemilik`.`id_pemilik` = `bast`.`id_pemilik`", "inner");
		$this->db->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`', 'inner');
		$this->db->join("`bayar`", "`billing_detail`.`id_bayar` = `bayar`.`id_bayar`");
		$this->db->join("`db_via`", "`db_via`.`id_via` = `bayar`.`id_via`");
		$this->db->where(array(
			'bast.hapus' => 0,
			'billing_detail.hapus' => 0,
			'billing_detail.status' => 2,
			'bayar.tanggal >=' => $tanggal_awal,
			'bayar.tanggal <=' => $tanggal_ahir,
		))->group_by('bayar.id_bayar');
		//->order_by('bayar.tanggal');
		if ($id_unit != '') {
			$this->db->where("bast.id_unit", $id_unit);
		}
		return $this->db->from('billing_detail');
	}
	public function getDataBayarGroupExport($id_unit, $tanggal_awal, $tanggal_ahir)
	{

		$this->db->select('0 as No');
		$this->db->select('db_unit`.`kode` as Unit');
		$this->db->select('db_unit`.`luas` as Luas');
		$this->db->select('pemilik`.`nama`');
		$this->db->select('`bayar`.`jumlah` as `total`');


		$this->db->join("bast", "`billing_detail`.`id_bast` = `bast`.`id_bast`", "inner");
		$this->db->join("pemilik", "`pemilik`.`id_pemilik` = `bast`.`id_pemilik`", "inner");
		$this->db->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`', 'inner');
		$this->db->join("`bayar`", "`billing_detail`.`id_bayar` = `bayar`.`id_bayar`");
		$this->db->join("`db_via`", "`db_via`.`id_via` = `bayar`.`id_via`");
		$this->db->where(array(
			'bast.hapus' => 0,
			//'db_unit.id_group' => 1,
			'billing_detail.hapus' => 0,
			'billing_detail.status' => 2,
			'bayar.tanggal >=' => $tanggal_awal,
			'bayar.tanggal <=' => $tanggal_ahir,
		))->group_by('bayar.id_bayar');
		//->order_by('bayar.tanggal');
		if ($id_unit != '') {
			$this->db->where("bast.id_unit", $id_unit);
		}
		$query = $this->db->from('billing_detail')->get_compiled_select();
		return $this->db->select('0 as No,Unit,Luas,nama,SUM(total) as `Amount Paid`')
			->from("(" . $query . ") as result")->group_by('Unit');
	}

	public function getDataBelumBayarExport($id_unit, $tanggal_awal, $tanggal_ahir)
	{

		$this->db->select('0 as No');
		$this->db->select('db_unit`.`kode` as Unit');
		$this->db->select('pemilik`.`nama`');
		$this->db->select('bast`.`email_surat` as Email');
		$this->db->select('bast`.`hp_surat` as Hp');
		$this->db->select('bast`.`wa_surat` as Wa');
		$this->db->select('bast`.`alamat_surat` as Alamat');
		$this->db->select('CONCAT("4329 ",`db_unit`.`va`) as `VA_CimbNiaga`');
		$this->db->select('CONCAT("88176 ",`db_unit`.`va`) as `VA_Mandiri`');
		$this->db->select("IFNULL(`bayar`.`jumlah`, 0) AS 'Amount'");
		$this->db->select('Outstanding');
		$this->db->join("(SELECT
      `id_bast`,id_bayar, (SUM(IF(`billing_detail`.`status` = 1, `billing_detail`.`jumlah`, 0)) - SUM(IF(`billing_detail`.`status` = 3, `billing_detail`.`jumlah`, 0))) - SUM(IF(`billing_detail`.`status` = 2, `billing_detail`.`jumlah`, 0)) AS 'Outstanding'
    FROM
      `billing_detail`
    WHERE
      `billing_detail`.`tanggal` >= '2014-01-01' AND `hapus` = 0
    GROUP BY
      `id_bast`) billing_detail", "`billing_detail`.`id_bast` = `bast`.`id_bast`", "left");

		$this->db->join("pemilik", "`pemilik`.`id_pemilik` = `bast`.`id_pemilik`", "inner");
		$this->db->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`', 'inner');
		$this->db->join("(SELECT   bayar.jumlah,bayar.id_bast
    	FROM `bayar` INNER JOIN billing_detail on billing_detail.id_bayar=bayar.id_bayar 
		WHERE `bayar`.`tanggal` >= '" . $tanggal_awal . "' 
		AND `bayar`.`tanggal` <= '" . $tanggal_ahir . "' 
		AND billing_detail.hapus=0
		AND billing_detail.status=2
		AND bayar.`hapus` = 0 GROUP BY bayar.id_bayar) bayar", "`bast`.`id_bast` = `bayar`.`id_bast`", "left");
		$this->db->where(array(
			'bast.hapus' => 0,
			'db_unit.id_group' => 1,
		));
		$this->db->group_by('bast.id_bast');
		$this->db->having('Amount', '0');
		if ($id_unit != '') {
			$this->db->where("bast.id_unit", $id_unit);
		}
		return $this->db->from('bast');
	}


	public function getDataBayarJumlah($id_unit, $tanggal_awal, $tanggal_ahir)
	{

		/*
		$this->db->select('SUM(`bayar`.`jumlah`) as `total`');
		$this->db->from('billing_detail');
		$this->db->join("bast", "`billing_detail`.`id_bast` = `bast`.`id_bast`", "inner");
		$this->db->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`', 'inner');
		$this->db->join("`bayar`", "`billing_detail`.`id_bayar` = `bayar`.`id_bayar`");
		$this->db->join("`db_via`", "`db_via`.`id_via` = `bayar`.`id_via`");
		$this->db->where(array(
			'bast.hapus' => 0,
			'billing_detail.hapus' => 0,
			'billing_detail.status' => 2,
			'bayar.tanggal >=' => $tanggal_awal,
			'bayar.tanggal <=' => $tanggal_ahir,
		));
		*/

		$this->db->select('SUM(`bayar`.`jumlah`) as `total`');
		$this->db->from('bayar');
		$this->db->join(
			"(SELECT DISTINCT id_bayar as id_bayar,id_bast FROM billing_detail WHERE `billing_detail`.`hapus` = 0 AND `billing_detail`.`status` = 2) billing_detail",
			"`billing_detail`.`id_bayar` = `bayar`.`id_bayar`",
			"inner"
		);
		$this->db->join("bast", "`billing_detail`.`id_bast` = `bast`.`id_bast`", "inner");
		$this->db->where(array(
			'bast.hapus' => 0,
			'bayar.tanggal >=' => $tanggal_awal,
			'bayar.tanggal <=' => $tanggal_ahir,
		));
		if ($id_unit != '') {
			$this->db->where("bast.id_unit", $id_unit);
		}

		$q = $this->db->get()->row();
		return (isset($q)) ? $q->total : '0';
	}

	public function getDataBillingInvoiceTotal($id)
	{
		//$from = $this->getDataInvoiceBayar($id_unit);
		return $this->db->select('SUM(IF(status=1,jumlah,0)) - SUM(IF(status=3,jumlah,0)) as `tagihan`')
			->select('SUM(IF(status=2,jumlah,0)) as `bayar`')
			->select('(SUM(IF(status=1,jumlah,0)) - SUM(IF(status=3,jumlah,0)))
			-SUM(IF(status=2,jumlah,0)) as `piutang`')
			->from('billing_detail')
			->where(
				array(
					'id_billing' => $id,
					'hapus' => 0
				)
			)
			->get();
	}

	public function getDataBillingInvoiceDetail($id)
	{
		//$from = $this->getDataInvoiceBayar($id_unit);
		return $this->db->select('`billing_detail`.`id_billing`,
		`billing_detail`.`id_detail`,
		`billing_detail`.`id_tag`,
		`billing_detail`.`ket`,
		SUM(IF(status=1,`billing_detail`.`jumlah`,0))-SUM(IF(status=2,`billing_detail`.`jumlah`,0)) as jumlah,
		`billing_detail`.`bulan`,
		`billing_detail`.`tahun`,
		`billing_detail`.`periode`,
		`billing_detail`.`hapus`,
		`billing_detail`.`hapus_invoice`,
		`billing_detail`.`note`')
			->from('billing_detail')
			->where(
				array(
					'id_billing' => $id,
					'id_tag !=' => 30,
					'hapus' => 0,
					'hapus_invoice' => 0
				)
			)->group_by('id_tag,bulan,tahun')
			->get();
	}

	public function getDataTagihanPiutang($id_bast)
	{
		$this->db->select('
		id_tag,(SUM(IF(`billing_detail`.`status`=1,`billing_detail`.`jumlah`,0))- SUM(IF(`billing_detail`.`status`=3,`billing_detail`.`jumlah`,0)))
		- SUM(IF(`billing_detail`.`status`=2,`billing_detail`.`jumlah`,0)) as `jumlah`
                        ');
		$this->db->from('`billing_detail`');
		$this->db->where(
			array(
				'billing_detail.hapus' => 0,
				'billing_detail.id_tag !=' => null,
				'billing_detail.id_bast' => $id_bast,
			)
		)->group_by('billing_detail.id_tag');
		return $this->db->get();
	}

	/**
	 * Konfirmasi Bayar
	 */
	public function getDataKonfirmasi()
	{
		$this->db->select('0 as No');
		$this->db->select('db_unit`.`kode` as Unit');
		$this->db->select("`bayar_konf`.`tanggal` as Date");
		$this->db->select('`bayar_konf`.`jumlah` as `Amount Paid`');
		//$this->db->select('db_via`.`nama` as `Payment With`');
		$this->db->select('`bayar_konf`.`nama` as `Name`');
		$this->db->select('`bayar_konf`.`ket` as `Note`');
		$this->db->select('bayar_konf`.`id` as `Action`');
		$this->db->join('db_unit', '`bayar_konf`.`id_unit` = `db_unit`.`id_unit`', 'inner');
		$this->db->join('bast', '`bast`.`id_unit` = `db_unit`.`id_unit`', 'inner');
		$this->db->join("`db_via`", "`db_via`.`id_via` = `bayar_konf`.`id_via`", "left");
		$this->db->where(array(
			'bayar_konf.hapus' => 0,
			'bayar_konf.status' => 0,
		));

		return $this->db->from('bayar_konf');
	}


	/**
	 * Credit Note Invoice
	 */
	public function getDataCreditNote($tanggal_awal, $tanggal_ahir)
	{

		$this->db->select('0 as No');
		$this->db->select('db_unit`.`kode` as unit');
		$this->db->select('cn`.`form`');
		$this->db->select("`cn`.`tanggal` as date");
		$this->db->select('`cn`.`jumlah` as `total`');
		//$this->db->select('db_via`.`nama` as `Cara Bayar`');

		$this->db->select('SUM(IF(id_tag IS NULL,billing_detail.jumlah,0)) as `UnAlokasi`');
		$this->db->select('SUM(IF(id_tag=20,billing_detail.jumlah,0)) as `MigrasiPiutang`');
		$this->db->select('SUM(IF(id_tag=1,billing_detail.jumlah,0)) as `SF`');
		$this->db->select('SUM(IF(id_tag=2,billing_detail.jumlah,0)) as `SC`');
		$this->db->select('SUM(IF(id_tag=3,billing_detail.jumlah,0)) as `AdmBank`');
		$this->db->select('SUM(IF(id_tag=4,billing_detail.jumlah,0)) as `Air`');
		$this->db->select('SUM(IF(id_tag=10,billing_detail.jumlah,0)) as `ListrikArea`');
		$this->db->select('SUM(IF(id_tag=9,billing_detail.jumlah,0)) as `Pengiriman`');
		$this->db->select('SUM(IF(id_tag=11,billing_detail.jumlah,0)) as `Denda`');

		$this->db->select('`cn`.`id_cn` as `action`');
		$this->db->join("bast", "`billing_detail`.`id_bast` = `bast`.`id_bast`", "inner");
		$this->db->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`', 'inner');
		$this->db->join("`cn`", "`billing_detail`.`id_cn` = `cn`.`id_cn`");
		$this->db->where(array(
			'bast.hapus' => 0,
			'billing_detail.hapus' => 0,
			'billing_detail.status' => 3,
			'cn.tanggal >=' => $tanggal_awal,
			'cn.tanggal <=' => $tanggal_ahir,
		))->group_by('cn.id_cn');
		return $this->db->from('billing_detail');
	}

	public function getDataCreditNoteJumlah($tanggal_awal, $tanggal_ahir)
	{

		$this->db->select('SUM(`billing_detail`.`jumlah`) as `total`');
		$this->db->from('billing_detail');
		$this->db->join("bast", "`billing_detail`.`id_bast` = `bast`.`id_bast`", "inner");
		$this->db->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`', 'inner');
		$this->db->join("`cn`", "`billing_detail`.`id_cn` = `cn`.`id_cn`");
		$this->db->where(array(
			'bast.hapus' => 0,
			'billing_detail.hapus' => 0,
			'billing_detail.status' => 3,
			'cn.tanggal >=' => $tanggal_awal,
			'cn.tanggal <=' => $tanggal_ahir,
		));
		$q = $this->db->get()->row();
		return (isset($q)) ? $q->total : '0';
	}

	/**
	 * @param $id_unit
	 * @return string
	 *
	 * DATA Pembayaran Work Order
	 */

	public function getDataBillingWorkOrder()
	{
		$this->db->select(
			'0 as No,
			`db_unit`.`kode` as `Kode Unit`,
			`tiket`.`no_form` as `No Form`,
			`tiket`.`ket` as `Keterangan,
			`tiket`.`tanggal` as `Tanggal`,
			(tiket_detail.jumlah + tiket_detail.material + tiket_detail.deposit ) as `Tagihan`
			'
		);

		$this->db->select('tiket.id_tiket as Aksi');
		$this->db->join('tiket_detail', 'tiket_detail.id_tiket=tiket.id_tiket')
			->join('bast', 'bast.id_bast=tiket.id_bast')
			->join('db_unit', 'db_unit.id_unit=bast.id_unit')
			->join('pemilik', 'pemilik.id_pemilik=bast.id_pemilik')
			->where(array(
				'tiket_detail.hapus' => 0,
				'tiket_detail.tipe' => 1,

				'tiket.hapus' => 0,
				'tiket.tipe' => 5,
				'tiket.post' => 2,

			))->group_by('tiket_detail.id_tiket');
		return $this->db->from('tiket');
	}

	public function getDataBillingWorkOrderTotal()
	{

		$this->db->select('SUM(`tiket_detail`.`jumlah`) + SUM(`tiket_detail`.`material`) + SUM(`tiket_detail`.`deposit`) as `total`');
		$this->db->from('tiket')->join('tiket_detail', 'tiket_detail.id_tiket=tiket.id_tiket')
			->join('bast', 'bast.id_bast=tiket.id_bast')
			->join('db_unit', 'db_unit.id_unit=bast.id_unit')
			->join('pemilik', 'pemilik.id_pemilik=bast.id_pemilik')
			->where(array(
				'tiket_detail.hapus' => 0,
				'tiket_detail.tipe' => 1,

				'tiket.hapus' => 0,
				'tiket.tipe' => 5,
				'tiket.post' => 2,

			));
		return $this->db->get();
	}

	public function getDataBayarWorkOrder($id_unit, $tanggal_awal, $tanggal_ahir)
	{

		$this->db->select('0 as No');
		$this->db->select('bayar`.`kwt`');
		$this->db->select("`bayar`.`tanggal`");
		$this->db->select('(`tiket_detail`.`jumlah` + `tiket_detail`.`deposit`  + `tiket_detail`.`material`)  as `bayar`');
		$this->db->select('db_via`.`nama` as `Cara Bayar`');
		$this->db->select('bayar`.`id_bayar` as `Aksi`');
		$this->db->join("tiket", "`tiket_detail`.`id_tiket` = `tiket`.`id_tiket`", "inner");
		$this->db->join("bast", "`tiket`.`id_bast` = `bast`.`id_bast`", "inner");
		$this->db->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`', 'inner');
		$this->db->join("`bayar`", "`tiket_detail`.`id_bayar` = `bayar`.`id_bayar`");
		$this->db->join("`db_via`", "`db_via`.`id_via` = `bayar`.`id_via`");
		$this->db->where(array(
			'bast.hapus' => 0,
			'tiket_detail.hapus' => 0,
			'tiket_detail.tipe' => 2,

			'tiket.tipe' => 5,
			'bayar.tanggal >=' => $tanggal_awal,
			'bayar.tanggal <=' => $tanggal_ahir,
		));
		if ($id_unit != '') {
			$this->db->where("bast.id_unit", $id_unit);
		}
		return $this->db->from('tiket_detail');
	}

	public function getDataBayarWorkOrderJumlah($id_unit, $tanggal_awal, $tanggal_ahir)
	{

		$this->db->select('(SUM(`tiket_detail`.`jumlah`)+SUM(`tiket_detail`.`material`)+SUM(`tiket_detail`.`deposit`)) as `total`');
		$this->db->from('tiket_detail');
		$this->db->join("tiket", "`tiket_detail`.`id_tiket` = `tiket`.`id_tiket`", "inner");
		$this->db->join("bast", "`tiket`.`id_bast` = `bast`.`id_bast`", "inner");
		$this->db->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`', 'inner');
		$this->db->join("`bayar`", "`tiket_detail`.`id_bayar` = `bayar`.`id_bayar`");
		$this->db->join("`db_via`", "`db_via`.`id_via` = `bayar`.`id_via`");
		$this->db->where(array(
			'bast.hapus' => 0,
			'tiket_detail.hapus' => 0,
			'tiket_detail.tipe' => 2,

			'tiket.tipe' => 5,
			'bayar.tanggal >=' => $tanggal_awal,
			'bayar.tanggal <=' => $tanggal_ahir,
		));
		if ($id_unit != '') {
			$this->db->where("bast.id_unit", $id_unit);
		}
		$q = $this->db->get()->row();
		return (isset($q)) ? $q->total : '0';
	}


	/**
	 * @param $id_unit
	 * @return string
	 *
	 * DATA Pembayaran AccessCard
	 */

	public function getDataBillingAccessCard()
	{
		$this->db->select(
			'0 as No,
			`db_unit`.`kode` as `Kode Unit`,
			`tiket`.`no_form` as `No Form`,
			`tiket`.`ket` as `Keterangan,
			`tiket`.`tanggal` as `Tanggal`,
			SUM(tiket_detail.jumlah) as `Tagihan`
			'
		);

		$this->db->select('tiket.id_tiket as Aksi');
		$this->db->join('tiket_detail', 'tiket_detail.id_tiket=tiket.id_tiket')
			->join('bast', 'bast.id_bast=tiket.id_bast')
			->join('db_unit', 'db_unit.id_unit=bast.id_unit')
			->join('pemilik', 'pemilik.id_pemilik=bast.id_pemilik')
			->where(array(
				'tiket_detail.hapus' => 0,
				'tiket_detail.tipe' => 1,

				'tiket.hapus' => 0,
				'tiket.tipe' => 6,
				'tiket.post' => 2,

			))->group_by('tiket_detail.id_tiket');
		return $this->db->from('tiket');
	}

	public function getDataBillingAccessCardTotal()
	{

		$this->db->select('SUM(`tiket_detail`.`jumlah`) as `total`');
		$this->db->from('tiket')->join('tiket_detail', 'tiket_detail.id_tiket=tiket.id_tiket')
			->join('bast', 'bast.id_bast=tiket.id_bast')
			->join('db_unit', 'db_unit.id_unit=bast.id_unit')
			->join('pemilik', 'pemilik.id_pemilik=bast.id_pemilik')
			->where(array(
				'tiket_detail.hapus' => 0,
				'tiket_detail.tipe' => 1,

				'tiket.hapus' => 0,
				'tiket.tipe' => 6,
				'tiket.post' => 2,

			));
		return $this->db->get();
	}

	public function getDataBayarAccessCard($id_unit, $tanggal_awal, $tanggal_ahir)
	{

		$this->db->select('0 as No');
		$this->db->select('bayar`.`kwt`');
		$this->db->select("`bayar`.`tanggal`");
		$this->db->select('`tiket_detail`.`jumlah` as `bayar`');
		$this->db->select('db_via`.`nama` as `Cara Bayar`');
		$this->db->select('bayar`.`id_bayar` as `Aksi`');
		$this->db->join("tiket", "`tiket_detail`.`id_tiket` = `tiket`.`id_tiket`", "inner");
		$this->db->join("bast", "`tiket`.`id_bast` = `bast`.`id_bast`", "inner");
		$this->db->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`', 'inner');
		$this->db->join("`bayar`", "`tiket_detail`.`id_bayar` = `bayar`.`id_bayar`");
		$this->db->join("`db_via`", "`db_via`.`id_via` = `bayar`.`id_via`");
		$this->db->where(array(
			'bast.hapus' => 0,
			'tiket.tipe' => 6,
			'tiket_detail.hapus' => 0,
			'tiket_detail.tipe' => 2,
			'bayar.tanggal >=' => $tanggal_awal,
			'bayar.tanggal <=' => $tanggal_ahir,
		));
		if ($id_unit != '') {
			$this->db->where("bast.id_unit", $id_unit);
		}
		return $this->db->from('tiket_detail');
	}

	public function getDataBayarAccessCardJumlah($id_unit, $tanggal_awal, $tanggal_ahir)
	{

		$this->db->select('SUM(`tiket_detail`.`jumlah`) as `total`');
		$this->db->from('tiket_detail');
		$this->db->join("tiket", "`tiket_detail`.`id_tiket` = `tiket`.`id_tiket`", "inner");
		$this->db->join("bast", "`tiket`.`id_bast` = `bast`.`id_bast`", "inner");
		$this->db->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`', 'inner');
		$this->db->join("`bayar`", "`tiket_detail`.`id_bayar` = `bayar`.`id_bayar`");
		$this->db->join("`db_via`", "`db_via`.`id_via` = `bayar`.`id_via`");
		$this->db->where(array(
			'bast.hapus' => 0,
			'tiket.tipe' => 6,
			'tiket_detail.hapus' => 0,
			'tiket_detail.tipe' => 2,
			'bayar.tanggal >=' => $tanggal_awal,
			'bayar.tanggal <=' => $tanggal_ahir,
		));
		if ($id_unit != '') {
			$this->db->where("bast.id_unit", $id_unit);
		}
		$q = $this->db->get()->row();
		return (isset($q)) ? $q->total : '0';
	}


	/**
	 *
	 * DATA Pembayaran Tiket
	 */

	public function getDataBillingTiket($tipe)
	{
		$this->db->select(
			'0 as No,
			`db_unit`.`kode` as `Unit`,
			`tiket`.`no_form` as `No Form`,
			`tiket`.`ket` as `Note,
			`tiket`.`tanggal` as `Date`,
			'
		);
		$this->db->select('(
		SUM(IF(tiket_detail.tipe=1
		,(`tiket_detail`.`jumlah` + `tiket_detail`.`material` + `tiket_detail`.`deposit`),0)) -
		SUM(IF(tiket_detail.tipe=2
		,(`tiket_detail`.`jumlah` + `tiket_detail`.`material` + `tiket_detail`.`deposit`),0)) 
		 ) as `Bill`');

		$this->db->select('tiket.id_tiket as Action');
		$this->db->join('tiket_detail', 'tiket_detail.id_tiket=tiket.id_tiket')
			->join('bast', 'bast.id_bast=tiket.id_bast')
			->join('db_unit', 'db_unit.id_unit=bast.id_unit')
			//->join('pemilik', 'pemilik.id_pemilik=bast.id_pemilik')
			->where(array(
				'tiket_detail.hapus' => 0,
				//'tiket_detail.tipe' => 1,
				'tiket.hapus' => 0,
				'tiket.tipe' => $tipe,
				//'tiket.post' => 2,

			))->group_by('tiket_detail.id_tiket')->having('Bill > 0');
		return $this->db->from('tiket');
	}

	public function getDataBillingTiketTotal($tipe)
	{

		$q = $this->getDataBillingTiket($tipe)->get_compiled_select();
		return $this->db->select('SUM(bill.Bill) as total')
			->from('(' . $q . ') bill')->get();

		/*
		echo $r->total;
		die();
		$this->db->select('(
		SUM(IF(tiket_detail.tipe=1
		,(`tiket_detail`.`jumlah` + `tiket_detail`.`material` + `tiket_detail`.`deposit`),0)) -
		SUM(IF(tiket_detail.tipe=2
		,(`tiket_detail`.`jumlah` + `tiket_detail`.`material` + `tiket_detail`.`deposit`),0))
		 ) as `total`');
		$this->db->from('tiket')
			->join('tiket_detail', 'tiket_detail.id_tiket=tiket.id_tiket')
			->join('bast', 'bast.id_bast=tiket.id_bast')
			//->join('db_unit', 'db_unit.id_unit=bast.id_unit')
			//->join('pemilik', 'pemilik.id_pemilik=bast.id_pemilik')
			->where(array(
				'tiket_detail.hapus' => 0,
				'tiket.hapus' => 0,
				//'bast.hapus' => 0,
				'tiket.tipe' => $tipe,
				'tiket.post <' => 4,

			));
		return $this->db->get();
		*/
	}

	public function getDataBayarTiket($tipe, $id_unit, $tanggal_awal, $tanggal_ahir)
	{

		$this->db->select('0 as No');
		$this->db->select('bayar`.`kwt`');
		$this->db->select('`db_unit`.`kode` as `Unit`');
		$this->db->select("`bayar`.`tanggal` as date");
		$this->db->select('(`tiket_detail`.`jumlah` + `tiket_detail`.`deposit`  + `tiket_detail`.`material`)  as `pay`');

		$this->db->select('db_via`.`nama` as `Payment With`');
		$this->db->select('tiket_detail`.`nama` as `Name`');
		$this->db->select('bayar`.`id_bayar` as `Action`');
		$this->db->join("tiket", "`tiket_detail`.`id_tiket` = `tiket`.`id_tiket`", "inner");
		$this->db->join("bast", "`tiket`.`id_bast` = `bast`.`id_bast`", "inner");
		$this->db->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`', 'inner');
		$this->db->join("`bayar`", "`tiket_detail`.`id_bayar` = `bayar`.`id_bayar`");
		$this->db->join("`db_via`", "`db_via`.`id_via` = `bayar`.`id_via`");
		$this->db->where(array(
			'bast.hapus' => 0,
			'tiket.tipe' => $tipe,
			'tiket_detail.hapus' => 0,
			'tiket_detail.tipe' => 2,
			'bayar.tanggal >=' => $tanggal_awal,
			'bayar.tanggal <=' => $tanggal_ahir,
		))->group_by('tiket_detail.id_bayar');
		if ($id_unit != '') {
			$this->db->where("bast.id_unit", $id_unit);
		}
		return $this->db->from('tiket_detail');
	}

	public function getDataBayarTiketJumlah($tipe, $id_unit, $tanggal_awal, $tanggal_ahir)
	{

		$this->db->select('(SUM(`tiket_detail`.`jumlah`)+SUM(`tiket_detail`.`material`)+SUM(`tiket_detail`.`deposit`)) as `total`');
		$this->db->from('tiket_detail');
		$this->db->join("tiket", "`tiket_detail`.`id_tiket` = `tiket`.`id_tiket`", "inner");
		$this->db->join("bast", "`tiket`.`id_bast` = `bast`.`id_bast`", "inner");
		$this->db->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`', 'inner');
		$this->db->join("`bayar`", "`tiket_detail`.`id_bayar` = `bayar`.`id_bayar`");
		$this->db->join("`db_via`", "`db_via`.`id_via` = `bayar`.`id_via`");
		$this->db->where(array(
			'bast.hapus' => 0,
			'tiket.tipe' => $tipe,
			'tiket_detail.hapus' => 0,
			'tiket_detail.tipe' => 2,
			'bayar.tanggal >=' => $tanggal_awal,
			'bayar.tanggal <=' => $tanggal_ahir,
		));
		if ($id_unit != '') {
			$this->db->where("bast.id_unit", $id_unit);
		}
		$q = $this->db->get()->row();
		return (isset($q)) ? $q->total : '0';
	}


	/**
	 * Get DAta Bayar Lainnya
	 */
	public function getDataBayarLainnya($id_unit, $id_tag, $tanggal_awal, $tanggal_ahir)
	{

		$this->db->select('0 as No');
		//$this->db->select('IF (bayar.tipe = 1, bast.kode, vendor.nama)  as `Name`');
		$this->db->select('(CASE WHEN bayar.tipe = 1 THEN bast.kode
		WHEN bayar.tipe = 2 THEN vendor.nama
		WHEN bayar.tipe  = 3 THEN bayar.nama
  END)   as `Name`');
		$this->db->select('bayar_lainnya`.`ket` as `Note`');
		$this->db->select('bayar`.`kwt`');
		$this->db->select("`bayar`.`tanggal` as `Date`");
		$this->db->select('`bayar`.`jumlah` as `Amount`');
		$this->db->select('db_via`.`nama` as `Via`');
		$this->db->select('bayar_lainnya`.`value` as `Value`');
		$this->db->select('bayar`.`id_bayar` as `Action`');
		$this->db->join("bayar", "`bayar`.`id_bayar` = `bayar_lainnya`.`id_bayar`", "inner");
		//$this->db->join("bast", "`bayar`.`id_bast` = `bast`.`id_bast`", "left");
		//$this->db->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`', 'left');
		$this->db->join('(SELECT db_unit.kode, bast.id_bast FROM bast 
		INNER JOIN db_unit ON db_unit.id_unit=bast.id_unit where bast.hapus=0) bast'
		, 'bayar.tipe=1 AND `bast`.`id_bast` = `bayar`.`id_bast`', 'left');
		
		$this->db->join('vendor'
		, 'bayar.tipe=2 AND `vendor`.`id_vendor` = `bayar`.`id_vendor`', 'left');
		
		$this->db->join("`db_via`", "`db_via`.`id_via` = `bayar`.`id_via`");
		$this->db->where(array(
			//'bast.hapus' => 0,
			'bayar.hapus' => 0,
			'bayar_lainnya.hapus' => 0,
			'bayar.tanggal >=' => $tanggal_awal,
			'bayar.tanggal <=' => $tanggal_ahir,
		));
		if ($id_unit != '') {
			$this->db->where("bast.id_unit", $id_unit);
		}
		if ($id_tag != '') {
			$this->db->where("bayar_lainnya.id_tag", $id_tag);
		}
		return $this->db->from('bayar_lainnya');
	}

	public function getDataBayarLainnyaJumlah($id_unit, $id_tag, $tanggal_awal, $tanggal_ahir)
	{

		$this->db->select('SUM(`bayar`.`jumlah`) as `total`');
		$this->db->from('bayar_lainnya');
		$this->db->join("`bayar`", "`bayar_lainnya`.`id_bayar` = `bayar`.`id_bayar`");
		/*
		$this->db->join("bast", "`bayar`.`id_bast` = `bast`.`id_bast`", "inner");
		$this->db->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`', 'inner');
		*/
		$this->db->join("`db_via`", "`db_via`.`id_via` = `bayar`.`id_via`");

		$this->db->join('(SELECT db_unit.kode, bast.id_bast , bast.id_unit FROM bast 
		INNER JOIN db_unit ON db_unit.id_unit=bast.id_unit where bast.hapus=0) bast'
			, 'bayar.tipe=1 AND `bast`.`id_bast` = `bayar`.`id_bast`', 'left');

		$this->db->where(array(
			//'bast.hapus' => 0,
			'bayar.hapus' => 0,
			'bayar_lainnya.hapus' => 0,

			'bayar.tanggal >=' => $tanggal_awal,
			'bayar.tanggal <=' => $tanggal_ahir,
		));
		if ($id_unit != '') {
			$this->db->where("bast.id_unit", $id_unit);
		}
		if ($id_tag != '') {
			$this->db->where("bayar_lainnya.id_tag", $id_tag);
		}
		$q = $this->db->get()->row();
		return (isset($q)) ? $q->total : '0';
	}

	/**
	 * Get DAta Bayar Lainnya
	 */
	public function getDataBayarListrik($id_unit, $id_tag, $tanggal_awal, $tanggal_ahir)
	{

		$this->db->select('0 as No');
		$this->db->select('bast`.`kode` as `Unit`');
		$this->db->select('bayar_lainnya`.`ket` as `Note`');
		$this->db->select('bayar`.`kwt`');
		$this->db->select("`bayar`.`tanggal` as `Date`");
		$this->db->select('`bayar`.`jumlah` as `Amount`');
		$this->db->select('db_via`.`nama` as `Via`');
		$this->db->select('bayar_lainnya`.`value` as `Value`');
		$this->db->select('bayar`.`id_bayar` as `Action`');
		$this->db->join("bayar", "`bayar`.`id_bayar` = `bayar_lainnya`.`id_bayar`", "inner");
		//$this->db->join("bast", "`bayar`.`id_bast` = `bast`.`id_bast`", "left");
		//$this->db->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`', 'left');
		$this->db->join('(SELECT db_unit.kode, bast.id_bast, bast.id_unit FROM bast 
		INNER JOIN db_unit ON db_unit.id_unit=bast.id_unit where bast.hapus=0) bast'
			, 'bayar.tipe=1 AND `bast`.`id_bast` = `bayar`.`id_bast`', 'left');


		$this->db->join("`db_via`", "`db_via`.`id_via` = `bayar`.`id_via`");
		$this->db->where(array(
			//'bast.hapus' => 0,
			'bayar.hapus' => 0,
			'bayar_lainnya.hapus' => 0,
			'bayar.tanggal >=' => $tanggal_awal,
			'bayar.tanggal <=' => $tanggal_ahir,
		));
		if ($id_unit != '') {
			$this->db->where("bast.id_unit", $id_unit);
		}
		if ($id_tag != '') {
			$this->db->where("bayar_lainnya.id_tag", $id_tag);
		}
		return $this->db->from('bayar_lainnya');
	}

	public function getDataBayarListrikJumlah($id_unit, $id_tag, $tanggal_awal, $tanggal_ahir)
	{

		$this->db->select('SUM(`bayar`.`jumlah`) as `total`');
		$this->db->from('bayar_lainnya');
		$this->db->join("`bayar`", "`bayar_lainnya`.`id_bayar` = `bayar`.`id_bayar`");
		$this->db->join("bast", "`bayar`.`id_bast` = `bast`.`id_bast`", "inner");
		$this->db->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`', 'inner');
		$this->db->join("`db_via`", "`db_via`.`id_via` = `bayar`.`id_via`");
		$this->db->where(array(
			'bast.hapus' => 0,
			'bayar.tanggal >=' => $tanggal_awal,
			'bayar.tanggal <=' => $tanggal_ahir,
		));
		if ($id_unit != '') {
			$this->db->where("bast.id_unit", $id_unit);
		}
		if ($id_tag != '') {
			$this->db->where("bayar_lainnya.id_tag", $id_tag);
		}

		$q = $this->db->get()->row();
		return (isset($q)) ? $q->total : '0';
	}




	/**
	 * Data Bayar Repor
	 */

	public function getDataBayarHarian($tanggal_awal, $tanggal_ahir, $id_via = '')
	{

		$this->db->select('
  `kode`,
  `nama`,
  CONCAT(`kwt`,"<br>",GROUP_CONCAT(DISTINCT ket SEPARATOR "<br>")) as kwt,
  	jumlah,
  `tanggal`')
			->from('view_bayar')
			->where(array(
				'tanggal >=' => $tanggal_awal,
				'tanggal <=' => $tanggal_ahir,
			));
		if ($id_via != '') {
			$this->db->where(array('id_via' => $id_via));
		}
		$this->db->order_by('tanggal');
		$this->db->group_by('id_bayar');
		return $this->db->get();
	}
	public function getDataBayarHarianDulu($tanggal_awal, $tanggal_ahir, $id_via = '')
	{
		$this->db->select('
  `ddb_unit`.`kode`,
  `db_via`.`nama`,
  `bayar`.`kwt`,
  `bayar`.`jumlah`,
  `bayar`.`tanggal`')
			->from('bayar')
			->join('bast', '`bayar`.`id_bast` = `bast`.`id_bast`')
			->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`')
			->join('db_via', '`bayar`.`id_via` = `db_via`.`id_via`')
			->where(array(
				'bayar.hapus' => 0,
				'bayar.tanggal >=' => $tanggal_awal,
				'bayar.tanggal <=' => $tanggal_ahir,
			));
		if ($id_via != '') {
			$this->db->where(array('bayar.id_via' => $id_via));
		}
		return $this->db->get();
	}
}

?>
