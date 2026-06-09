<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 4/8/2016
 * Time: 2:42 PM
 */
?>
<?php defined('BASEPATH') or exit('No direct script access allowed');

class Billing_Model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function getDataBillingTagihan()
	{
		$this->db->select('0 as `No`,`db_unit`.`kode` as `NoUnit`,luas');
		$this->db->select('IFNULL(terbit,"") as LastPublished');
		$this->db->select("GROUP_CONCAT(DISTINCT(if(db_tag.tagihan != 2,`tagihan`.`tanggal_ahir`,'')) ORDER BY db_tag.id_tag ASC) AS `Bill Date`");
		$this->db->select("GROUP_CONCAT(DISTINCT(if(db_tag.tagihan != 2,`db_tag`.`nama`,'')) ORDER BY db_tag.id_tag ASC) AS `Bill Name`");
		$this->db->select('IFNULL(`billing_detail`.`tagihan`, 0) AS `piutang`,');
		$this->db->select("GROUP_CONCAT(DISTINCT(if(db_tag.tagihan != 2,`tagihan`.`id_tag`,'')) ORDER BY db_tag.id_tag ASC) AS `Period Bills`");
		$this->db->select('IFNULL(`utility`.`tag_utility`, 0) AS `Utility Bills`');
		$this->db->select('bast.id_bast as `Total Tagihan`, bast.id_bast as `Action`');
		$this->db->from('tagihan');
		$this->db->join('bast', '`tagihan`.`id_bast` = `bast`.`id_bast`', 'inner');
		$this->db->join('db_unit', '`tagihan`.`id_unit` = `db_unit`.`id_unit`', 'inner');
		$this->db->join('db_tag', '`db_tag`.`id_tag` = `tagihan`.`id_tag`', 'inner');
		$this->db->join('(SELECT MAX(`tanggal_terbit`) AS `terbit`, `id_bast` FROM `billing` WHERE
      `hapus` = 0 AND `periode` != 0 GROUP BY id_bast) billing', '`tagihan`.`id_bast` = `billing`.`id_bast`', 'left');

		$this->db->join('(SELECT (SUM(IF(`status` = 1, `jumlah`, 0)) - (SUM(IF(`status` = 2, `jumlah`, 0)) 
		+ SUM(IF(`status` = 3, `jumlah`, 0)))) AS `tagihan`, `id_bast`  
		FROM `billing_detail` where  hapus=0 GROUP BY id_bast) billing_detail'
			, '`tagihan`.`id_bast` = `billing_detail`.`id_bast`', 'left');

		$this->db->join('(SELECT
  `utility_rekening`.`id_unit`, (SUM(`utility`.`pakai`) * `db_tag`.`jumlah`) AS `tag_utility`
FROM
  `utility_rekening`
    INNER JOIN `utility` ON `utility`.`id_rekening` = `utility_rekening`.`id_rekening`
    INNER JOIN `db_tag` ON `db_tag`.`id_tag` = `utility_rekening`.`id_tag`
WHERE
  `utility`.`post` = 0 AND `utility`.`hapus` = 0 AND `utility_rekening`.hapus=0
GROUP BY
  `utility_rekening`.`id_unit`) utility', '`tagihan`.`id_unit` = `utility`.`id_unit`', 'left');

		$where = array(
			'bast.hapus' => 0,
			'tagihan.active' => 1,
		);
		$this->db->where($where);
		$this->db->order_by('tagihan.tanggal_ahir', 'ASC');
		return $this->db->group_by('`tagihan`.`id_bast`');

	}

	public function getDataMasterTagihanUnitPeriode($bast_id, $tagihan = '')
	{

		$this->db->select('                  
					`db_tag`.`nama`,
                  IF(tagihan.jumlah=0,`db_tag`.`jumlah`,tagihan.jumlah) as jumlah,
                  `tagihan`.`id_unit`,
                  `tagihan`.`periode`,
                  `tagihan`.`tanggal_awal`,
                  `tagihan`.`tanggal_ahir`,
                  `tagihan`.`id_tag`,
                  `db_unit`.`luas`,
                  `db_tag`.`tagihan`,
                  `tagihan`.`id`
        ');
		$this->db->from('tagihan');
		$this->db->join('db_tag', '`db_tag`.`id_tag` = `tagihan`.`id_tag`', 'inner');
		$this->db->join('db_unit', '`tagihan`.`id_unit` = `db_unit`.`id_unit`', 'inner');
		$this->db->where(
			array(
				'tagihan.id_bast' => $bast_id,
				'tagihan.active' => 1
			)
		);
		if ($tagihan != '') {
			$this->db->where('`db_tag`.`id_tag` IN (' . $tagihan . ')');
		}
		return $this->db->get();
	}

	public function getDataMasterTagihanUnitUtility($bast_id, $tagihan)
	{
		$query = "SELECT
              `db_tag`.`nama`,
               IF(tagihan.jumlah=0,`db_tag`.`jumlah`,tagihan.jumlah) as jumlah,
              `tagihan`.`id_unit`,
              `tagihan`.`id_tag`,
              `tagihan`.`tanggal_awal`,
              `tagihan`.`tanggal_ahir`,
              `db_tag`.`tagihan`,
              `utility`.`meter`,
              `utility`.`pakai`,
              `db_tag`.`jumlah` * `utility`.`pakai` AS `total`,
              `utility`.`bulan`,
              `utility`.`tahun`,
              `tagihan`.`id` as `tagihan_id`,
              `utility`.`id_meter` AS `id`
            FROM
              `tagihan`
              INNER JOIN `db_tag` ON `db_tag`.`id_tag` = `tagihan`.`id_tag`
              INNER JOIN `db_unit` ON `tagihan`.`id_unit` = `db_unit`.`id_unit`
              
              INNER JOIN utility_rekening ON utility_rekening.id_unit=tagihan.id_unit && tagihan.id_tag=utility_rekening.id_tag
              INNER JOIN `utility` ON `utility_rekening`.`id_rekening` = `utility`.`id_rekening`
            WHERE
              `db_tag`.`id_tag` IN ($tagihan) AND
              `utility`.`post` = '0' AND
              `utility`.`hapus` = '0' AND
              `tagihan`.`active` = '1' AND
              `utility_rekening`.`hapus` = '0' AND
              `tagihan`.`id_bast` = " . $bast_id . " ORDER BY db_tag.id_tag,utility.bulan,utility.tahun";
		return $this->db->query($query);
	}

	public function getDataMasterTagihanLainnya($tagihan)
	{

		$this->db->select('`db_tag`.`nama`,
                  `db_tag`.`jumlah`,
                  `db_tag`.`tagihan`,
                  `db_tag`.`jumlah`,
                  `db_tag`.`id_tag`,
                  `db_tag`.`periode`
        ');
		$this->db->from('db_tag');
		$this->db->where('hapus', 0);

		$this->db->where('`db_tag`.`id_tag` IN (' . $tagihan . ')');

		return $this->db->order_by('tagihan')->get();
	}

	public function getDataBillingInvoice($id_unit, $tanggal_awal, $tanggal_ahir, $kirim, $print = "")
	{

		$this->db->select('0 as `No`,`db_unit`.`kode` as `No Unit`');
		//$this->db->select('`billing`.`invoice` as `No Invoice`');
		$this->db->select('CONCAT(`billing`.`invoice`,"<br><small>Publish : ",DATE_FORMAT(`billing`.`tanggal_terbit`,"%d-%m-%Y")
,"</small><br><small>Due Date : ",DATE_FORMAT(`billing`.`tanggal_jt`,"%d-%m-%Y"),"</small>") as `Invoice`');
		//$this->db->select("`billing`.`tanggal_jt` as `Due Date`");
		$this->db->select('SUM(IF(billing_detail.status=4,`billing_detail`.`jumlah`,0)) as `PreviousBill`');
		$this->db->select('SUM(IF(billing_detail.status=1 AND billing_detail.id_tag = 11,`billing_detail`.`jumlah`,0)) as `Fine`');

		$this->db->select('SUM(IF(billing_detail.status=1 AND billing_detail.id_tag != 11,`billing_detail`.`jumlah`,0)) as `CurrentBill`');

		$this->db->select('SUM(IF(billing_detail.status=1,`billing_detail`.`jumlah`,0)) 
			+ SUM(IF(billing_detail.status=4,`billing_detail`.`jumlah`,0))  as `Bill`');

		//$this->db->select('`billing`.`print` as `Print`');
		//$this->db->select('`bast`.`kirim` as `Shipped`');
		//$this->db->select('`billing_detail`.`post` as `Status Posting`');
		$this->db->select('`billing_kirim`.`kirim` as `Shipped`');
		$this->db->select('`billing`.`id_billing` as Action');
		$this->db->from('billing');
		$this->db->join("bast", "`billing`.`id_bast` = `bast`.`id_bast`", "inner");
		$this->db->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`', 'inner');
		$this->db->join("billing_detail", "`billing_detail`.`id_billing` = `billing`.`id_billing`", "inner");
		$query_kirim = "SELECT
  GROUP_CONCAT(DISTINCT `db_kirim`.`nama`) as kirim,
  `billing_kirim`.`id_billing`,
  `billing_kirim`.`id_kirim`,
  `billing_kirim`.`hapus`
FROM
  `billing_kirim`
  INNER JOIN `db_kirim` ON `db_kirim`.`id_kirim` = `billing_kirim`.`id_kirim`
WHERE
  `billing_kirim`.`hapus` = 0 ";
		$inner = "left";

		if ($kirim != '') {
			$inner = "inner";
			$query_kirim .= " AND billing_kirim.id_kirim ='" . $kirim . "'";
		}

		$query_kirim .= " group by id_billing";
		$this->db->join("(" . $query_kirim . ") billing_kirim",
			"`billing_kirim`.`id_billing` = `billing`.`id_billing`", $inner);
		//$this->db->join("(".$query_kirim.") billing_kirim","`billing_kirim`.`id_billing` = `billing`.`id_billing`", "left");
		$this->db->where(array(
			'bast.hapus' => 0,
			'billing.hapus' => 0,
			'billing_detail.hapus' => 0,
			'billing.tanggal_terbit >=' => $tanggal_awal,
			'billing.tanggal_terbit <=' => $tanggal_ahir,

		));
		if ($id_unit != '') {
			$this->db->where("bast.id_unit", $id_unit);

		}
		/*
		if ($kirim != '') {
			if ($kirim == '1' || $kirim == '2') {
				$this->db->where("bast.kirim", $kirim);
			} else {
				if ($kirim == '3') {
				$this->db->where("bast.wa_surat != '' AND bast.wa_surat != '0'");
			}
				if ($kirim == '4') {
					$this->db->where("(bast.wa_surat != '' AND bast.wa_surat != '0') AND bast.kirim='1'");
				}
				if ($kirim == '5') {
					$this->db->where("(bast.wa_surat != '' AND bast.wa_surat != '0') AND bast.kirim='2'");
				}
				if ($kirim == '6') {
					$this->db->where("(bast.wa_surat != '' || bast.wa_surat != '0') 
					AND (bast.email_surat != '' || bast.email_surat != '-') 
					AND bast.kirim='2'");
				}
			}

		}
		*/
		if ($kirim != '') {
			//$this->db->where(array("billing_kirim.id_kirim"=> $kirim));
		}
		if ($print != '') {
			if ($print == 0) {
				$this->db->where('billing.print', 0);
			}
			if ($print == 1) {
				$this->db->where('billing.print !=', 0);

			}
		}
		$this->db->group_by('billing.id_billing');
		return $this->db->order_by('billing.print ASC');

	}

	public function getDataBillingInvoiceTotal($id_unit, $tanggal_awal, $tanggal_ahir, $kirim, $print = "")
	{


		$this->db->select('SUM(IF(billing_detail.status=4,`billing_detail`.`jumlah`,0)) as `piutang`');
		$this->db->select('SUM(IF(billing_detail.status=1 AND billing_detail.id_tag = 11,`billing_detail`.`jumlah`,0)) as `denda`');
		$this->db->select('SUM(IF(billing_detail.status=1 AND billing_detail.id_tag != 11,`billing_detail`.`jumlah`,0)) as `sekarang`');
		$this->db->select('SUM(IF(billing_detail.status=1,`billing_detail`.`jumlah`,0)) 
			+ SUM(IF(billing_detail.status=4,`billing_detail`.`jumlah`,0))  as `tagihan`');
		$this->db->from('billing');
		$this->db->join("bast", "`billing`.`id_bast` = `bast`.`id_bast`", "inner");
		$this->db->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`', 'inner');
		$this->db->join("billing_detail", "`billing_detail`.`id_billing` = `billing`.`id_billing`", "inner");
		$query_kirim = "SELECT
  GROUP_CONCAT(DISTINCT `db_kirim`.`nama`) as kirim,
  `billing_kirim`.`id_billing`,
  `billing_kirim`.`id_kirim`,
  `billing_kirim`.`hapus`
FROM
  `billing_kirim`
  INNER JOIN `db_kirim` ON `db_kirim`.`id_kirim` = `billing_kirim`.`id_kirim`
WHERE
  `billing_kirim`.`hapus` = 0 ";
		$inner = "left";

		if ($kirim != '') {
			$inner = "inner";
			$query_kirim .= " AND billing_kirim.id_kirim ='" . $kirim . "'";
		}

		$query_kirim .= " group by id_billing";
		$this->db->join("(" . $query_kirim . ") billing_kirim",
			"`billing_kirim`.`id_billing` = `billing`.`id_billing`", $inner);
		//$this->db->join("(".$query_kirim.") billing_kirim","`billing_kirim`.`id_billing` = `billing`.`id_billing`", "left");
		$this->db->where(array(
			'bast.hapus' => 0,
			'billing.hapus' => 0,
			'billing_detail.hapus' => 0,
			'billing.tanggal_terbit >=' => $tanggal_awal,
			'billing.tanggal_terbit <=' => $tanggal_ahir,

		));
		if ($id_unit != '') {
			$this->db->where("bast.id_unit", $id_unit);

		}
		if ($kirim != '') {
			//$this->db->where(array("billing_kirim.id_kirim"=> $kirim));
		}
		if ($print != '') {
			if ($print == 0) {
				$this->db->where('billing.print', 0);
			}
			if ($print == 1) {
				$this->db->where('billing.print !=', 0);

			}
		}
		//$this->db->group_by('billing.id_billing');
		return $this->db->order_by('billing.print ASC');

	}


	public function getDataBillingInvoiceExport($id_unit, $tanggal_awal, $tanggal_ahir, $kirim, $print = "")
	{

		$this->db->select('0 as `No`,`db_unit`.`kode` as `No Unit`');
		$this->db->select('`billing`.`invoice` as `No Invoice`');
		$this->db->select("`billing`.`tanggal_terbit` as `Date`");
		$this->db->select("`billing`.`tanggal_jt` as `Due Date`");
		$this->db->select('SUM(IF(billing_detail.status=4,`billing_detail`.`jumlah`,0)) as `PreviousBill`');
		$this->db->select('SUM(IF(billing_detail.status=1 AND billing_detail.id_tag = 11,`billing_detail`.`jumlah`,0)) as `Fine`');

		$this->db->select('SUM(IF(billing_detail.status=1 AND billing_detail.id_tag != 11,`billing_detail`.`jumlah`,0)) as `CurrentBill`');

		$this->db->select('SUM(IF(billing_detail.status=1,`billing_detail`.`jumlah`,0)) 
			+ SUM(IF(billing_detail.status=4,`billing_detail`.`jumlah`,0))  as `Bill`');

		//$this->db->select('`billing`.`print` as `Print`');
		$this->db->select('IF(`bast`.`kirim`="1","COURIER","EMAIL") as `defaul`');
		$this->db->select('`bast`.`alamat_surat` as `Addres`');
		$this->db->select('`bast`.`wa_surat` as `WA`');
		$this->db->select('`bast`.`email_surat` as `Email`');
		//$this->db->select('`billing_detail`.`post` as `Status Posting`');
		$this->db->select('`billing_kirim`.`kirim` as `Shipped`');
		$query_kirim = "SELECT
  GROUP_CONCAT(DISTINCT `db_kirim`.`nama`) as kirim,
  `billing_kirim`.`id_billing`,
  `billing_kirim`.`id_kirim`,
  `billing_kirim`.`hapus`
FROM
  `billing_kirim`
  INNER JOIN `db_kirim` ON `db_kirim`.`id_kirim` = `billing_kirim`.`id_kirim`
WHERE
  `billing_kirim`.`hapus` = 0 ";
		$inner = "left";

		if ($kirim != '') {
			$inner = "inner";
			$query_kirim .= " AND billing_kirim.id_kirim ='" . $kirim . "'";
		}

		$query_kirim .= " group by id_billing";
		$this->db->join("(" . $query_kirim . ") billing_kirim",
			"`billing_kirim`.`id_billing` = `billing`.`id_billing`", $inner);

		$this->db->from('billing');
		$this->db->join("bast", "`billing`.`id_bast` = `bast`.`id_bast`", "inner");
		$this->db->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`', 'inner');
		$this->db->join("billing_detail", "`billing_detail`.`id_billing` = `billing`.`id_billing`", "inner");
		$this->db->where(array(
			'bast.hapus' => 0,
			'billing.hapus' => 0,
			'billing_detail.hapus' => 0,
			'billing.tanggal_terbit >=' => $tanggal_awal,
			'billing.tanggal_terbit <=' => $tanggal_ahir,

		));
		if ($id_unit != '') {
			$this->db->where("bast.id_unit", $id_unit);

		}
		if ($kirim != '') {
			if ($kirim == '1' || $kirim == '2') {
				$this->db->where("bast.kirim", $kirim);
			} else {
				if ($kirim == '3') {
					$this->db->where("bast.wa_surat != '' AND bast.wa_surat != '0'");
				}
				if ($kirim == '4') {
					$this->db->where("(bast.wa_surat != '' AND bast.wa_surat != '0') AND bast.kirim='1'");
				}
				if ($kirim == '5') {
					$this->db->where("(bast.wa_surat != '' AND bast.wa_surat != '0') AND bast.kirim='2'");
				}
				if ($kirim == '6') {
					$this->db->where("(bast.wa_surat != '' || bast.wa_surat != '0') 
					AND (bast.email_surat != '' || bast.email_surat != '-') 
					AND bast.kirim='2'");
				}
			}

		}
		if ($print != '') {
			if ($print == 0) {
				$this->db->where('billing.print', 0);
			}
			if ($print == 1) {
				$this->db->where('billing.print !=', 0);

			}
		}
		$this->db->group_by('billing.id_billing');
		return $this->db->order_by('billing.print ASC');

	}

	public function getDataBillingInvoiceExportJurnal($id_unit, $tanggal_awal, $tanggal_ahir, $kirim, $print = "")
	{

		$this->db->select('`db_unit`.`kode` as `*Customer`');
		$this->db->select('`bast`.`email_surat` as `Email`');
		$this->db->select('`bast`.`alamat_surat` as `BillingAddress`');
		$this->db->select('"" as `ShippingAddress`');
		$this->db->select('`billing`.`tanggal_terbit` as `*InvoiceDate`');
		$this->db->select('`billing`.`tanggal_jt` as `*DueDate`');
		$this->db->select('"" as `ShippingDate`');
		$this->db->select('"" as `ShipVia`');
		$this->db->select('"" as `TrackingNo`');
		$this->db->select('"" as `CustomerRefNo`');
		$this->db->select('`billing`.`invoice` as `*InvoiceNumber`');
		$this->db->select('"" as `Message`');
		$this->db->select('"" as `Memo`');
		$this->db->select('db_tag.nama as `*ProductName`');
		$this->db->select('"" as `Description`');
		$this->db->select('"" as `*Quantity`');
		//$this->db->select('COUNT(db_tag.id_tag) as `Unit`');
		$this->db->select('"1" as `Unit`');
		//$this->db->select('`billing_detail`.`jumlah` as `*UnitPrice`');
		$this->db->select('SUM(`billing_detail`.`jumlah`) as `*UnitPrice`');
		$this->db->select('"" as `ProductDiscountRate(%)`');
		$this->db->select('"" as `InvoiceDiscountRate(%)`');
		$this->db->select('"" as `TaxName`');
		$this->db->select('"" as `TaxRate(%)`');
		$this->db->select('"" as `ShippingFee`');
		$this->db->select('"" as `WitholdingAccountCode`');
		$this->db->select('"" as `WitholdingAmount(value or %)`');
		$this->db->select('"" as `#paid?(yes/no)`');
		$this->db->select('"" as `#PaymentMethod`');
		$this->db->select('"" as `#PaidToAccountCode`');
		$this->db->select('"" as `Tags (use ; to separate tags)`');
		$this->db->select('"" as `WarehouseName`');
		$this->db->select('"" as `#currency code(example: IDR`');
		$this->db->select('"" as `USD`');
		$this->db->select('"" as `CAD)`');
		//$this->db->select('`billing_detail`.`post` as `Status Posting`');
		$this->db->from('billing');
		$this->db->join("bast", "`billing`.`id_bast` = `bast`.`id_bast`", "inner");
		$this->db->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`', 'inner');
		$this->db->join("billing_detail", "`billing_detail`.`id_billing` = `billing`.`id_billing`", "inner");
		$this->db->join("db_tag", "`db_tag`.`id_tag` = `billing_detail`.`id_tag`", "inner");
		$this->db->where(array(
			'bast.hapus' => 0,
			'billing.hapus' => 0,
			'billing_detail.hapus' => 0,
			'billing_detail.status' => 1,
			'billing.tanggal_terbit >=' => $tanggal_awal,
			'billing.tanggal_terbit <=' => $tanggal_ahir,

		));
		if ($id_unit != '') {
			$this->db->where("bast.id_unit", $id_unit);

		}
		if ($kirim != '') {
			if ($kirim == '1' || $kirim == '2') {
				$this->db->where("bast.kirim", $kirim);
			} else {
				if ($kirim == '3') {
					$this->db->where("bast.wa_surat != '' AND bast.wa_surat != '0'");
				}
				if ($kirim == '4') {
					$this->db->where("(bast.wa_surat != '' AND bast.wa_surat != '0') AND bast.kirim='1'");
				}
				if ($kirim == '5') {
					$this->db->where("(bast.wa_surat != '' AND bast.wa_surat != '0') AND bast.kirim='2'");
				}
				if ($kirim == '6') {
					$this->db->where("(bast.wa_surat != '' || bast.wa_surat != '0') 
					AND (bast.email_surat != '' || bast.email_surat != '-') 
					AND bast.kirim='2'");
				}
			}

		}
		if ($print != '') {
			if ($print == 0) {
				$this->db->where('billing.print', 0);
			}
			if ($print == 1) {
				$this->db->where('billing.print !=', 0);

			}
		}
		$this->db->group_by('billing.id_billing');
		$this->db->group_by('billing_detail.id_tag');
		return $this->db->order_by('billing.id_billing,billing_detail.id_tag');

	}

	public function getDataBillingInvoiceExportTokopedia($id_unit, $tanggal_awal, $tanggal_ahir, $kirim, $print = "")
	{

		$list = $this->db->select("(
				SUM(IF(billing_detail.status=1 
				AND (`billing_detail`.`id_tag` IS NULL || `billing_detail`.`id_tag` != 30 )  
				,billing_detail.jumlah,0))
			-(SUM(IF(billing_detail.status=2 ,billing_detail.jumlah,0)) 
					+ SUM(IF(billing_detail.status=3,billing_detail.jumlah,0)) 
					)
			) as piutang,bast.id_bast")
			->from('billing_detail')
			->join("bast", "`billing_detail`.`id_bast` = `bast`.`id_bast`", "inner")
			->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`', 'inner')
			->where(array(
				'bast.hapus' => 0,
				'billing_detail.hapus' => 0,
			))->group_by('bast.id_bast')->having('piutang', 0)->get();
		$bast = array();
		foreach ($list->result() as $a) {
			$bast[] = $a->id_bast;
		}
		if ($list->num_rows() == 0) {
			$bast = 0;
		} else {
			$bast = implode(',', $bast);

		}


		$this->db->select('CONCAT("08501121221", `db_unit`.`va2`) as `bill_number`');
		$this->db->select('SUM(IF(billing_detail.status=1,`billing_detail`.`jumlah`,0)) 
			+ SUM(IF(billing_detail.status=4,`billing_detail`.`jumlah`,0))  as `bill_amount`');
		$this->db->select('SUM(IF(billing_detail.status=4,`billing_detail`.`jumlah`,0))  as `piutang`');
		$this->db->select("`billing`.`tanggal_terbit` as `start_date`");
		$this->db->select("`billing`.`tanggal_jt` as `end_date`");
		//$this->db->select("`pemilik`.`nama` as `Nama`");
		$this->db->select("CONCAT(LEFT(`pemilik`.`nama`,2),'***', RIGHT(`pemilik`.`nama`,2)) as `Nama`");
		$this->db->select("'IPL' as `Jenis Tagihan`");
		$this->db->select("DATE_FORMAT(`billing`.`tanggal_terbit`, '%M%Y') as `Periode`");
		$this->db->select("`db_unit`.`kode` as `Unit`");
		//$this->db->select('`bast`.`email_surat` as `customer_email`');
		$this->db->select("SUBSTRING_INDEX(SUBSTRING_INDEX(`bast`.`email_surat`, ',', 1), ',', -1) as `customer_email`");
		//$this->db->select('SUM(IF(billing_detail.status=4,`billing_detail`.`jumlah`,0)) as piutang');
		$this->db->from('billing');
		$this->db->join("bast", "`billing`.`id_bast` = `bast`.`id_bast`", "inner");
		$this->db->join("pemilik", "`bast`.`id_pemilik` = `pemilik`.`id_pemilik`", "inner");
		$this->db->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`', 'inner');
		$this->db->join("billing_detail", "`billing_detail`.`id_billing` = `billing`.`id_billing`", "inner");
		$this->db->where(array(
			'bast.hapus' => 0,
			'billing.hapus' => 0,
			'billing_detail.hapus' => 0,
			'billing.tanggal_terbit >=' => $tanggal_awal,
			'billing.tanggal_terbit <=' => $tanggal_ahir,

		))->where('bast.id_bast NOT IN (' . $bast . ')');
		//$this->db->having('(SUM(IF(billing_detail.status=4,`billing_detail`.`jumlah`,0)))= 0');
		$this->db->having('(SUM(IF(billing_detail.status=4,`billing_detail`.`jumlah`,0)))< 1000000');
		$this->db->having('bill_amount > 0');
		//$this->db->having('(SUM(IF(billing_detail.status=4,`billing_detail`.`jumlah`,0)))= 0');
		if ($id_unit != '') {
			$this->db->where("bast.id_unit", $id_unit);
		}
		return $this->db->group_by('billing.id_billing');
		//->having('piutang',0);
	}


	function getDataUnitBast($bast)
	{
		$query = "SELECT `db_unit`.* FROM `bast`
                INNER JOIN `db_unit` ON `db_unit`.`id_unit` = `bast`.`id_unit` WHERE
                `bast`.`id_bast` = '$bast'";
		return $this->db->query($query);
	}

	public function getDataMasterTagihanUnitUtilityInv($bast_id, $id_billing, $tagihan)
	{

		$query = "SELECT
  `db_tag`.`nama`,
 	`db_tag`.`jumlah`,
  `db_tag`.`id_tag`,
  `db_tag`.`tagihan`,
  `utility`.`meter`,
  `utility`.`pakai`,
  `db_tag`.`jumlah` * `utility`.`pakai` AS 'total',
  `utility`.`bulan`,
  `utility`.`tahun`,
  `utility`.`post`,
  `utility`.`id_meter` AS 'id'
FROM
  `utility_rekening`
      INNER JOIN (SELECT
  `db_tag`.`id_tag`,
  IF(tagihan.jumlah=0,`db_tag`.`jumlah`,tagihan.jumlah) as jumlah,
  `db_tag`.`nama`,
  `db_tag`.`tagihan`
FROM
  `db_tag`
  INNER JOIN `tagihan` ON `db_tag`.`id_tag` = `tagihan`.`id_tag`
WHERE `tagihan`.`id_bast` = " . $bast_id . "
) `db_tag` ON `db_tag`.`id_tag` = `utility_rekening`.`id_tag`
    INNER JOIN `utility` ON `utility_rekening`.`id_rekening` = `utility`.`id_rekening`
    INNER JOIN `billing` ON `utility`.`id_billing` = `billing`.`id_billing`
WHERE
  `utility`.`post` = 1 AND `utility_rekening`.`hapus` = '0' AND `utility`.`id_billing`=" . $id_billing . "
  
UNION
SELECT
  `db_tag`.`nama`,
    IF(tagihan.jumlah=0,`db_tag`.`jumlah`,tagihan.jumlah) as jumlah,
  `db_tag`.`id_tag`,
  `db_tag`.`tagihan`,
  `utility`.`meter`,
  `utility`.`pakai`,
  IF(tagihan.jumlah=0,`db_tag`.`jumlah`,tagihan.jumlah) * `utility`.`pakai` AS 'total',
  `utility`.`bulan`,
  `utility`.`tahun`,
  `utility`.`post`,
  `utility`.`id_meter` AS 'id'
FROM
   `tagihan`
              INNER JOIN `db_tag` ON `db_tag`.`id_tag` = `tagihan`.`id_tag`
              INNER JOIN `db_unit` ON `tagihan`.`id_unit` = `db_unit`.`id_unit`
              
              INNER JOIN utility_rekening ON utility_rekening.id_unit=tagihan.id_unit && tagihan.id_tag=utility_rekening.id_tag
              INNER JOIN `utility` ON `utility_rekening`.`id_rekening` = `utility`.`id_rekening`
            
WHERE
  `utility_rekening`.`id_tag` IN ($tagihan)
    AND `utility`.`post` = '0'
    AND `tagihan`.`active` = '1' 
	AND `utility_rekening`.`hapus` = '0'
    AND `tagihan`.`id_bast` = " . $bast_id . "
    
    ORDER BY tahun,bulan";

		return $this->db->query($query);
	}

	function getDataBillingTagihanDetail($id_billing, $status = "")
	{
		return $this->db->select("SUM(`billing_detail`.`jumlah`) as tagih,db_tag.tagihan as jenis_tagihan,
                  `billing_detail`.`ket`,
                  `billing_detail`.`id_tag`
                  ,`billing_detail`.`periode`
                  ,`billing_detail`.`id_billing`
                  ,`billing_detail`.`id_detail`
                  , MIN(`billing_detail`.`tanggal`) as tanggal_awal
                  , MAX(`billing_detail`.`tanggal`) as tanggal_ahir
                ")
			->from('billing_detail')
			->join('db_tag', 'billing_detail.id_tag=db_tag.id_tag')
			->where(
				array(
					'billing_detail.id_billing' => $id_billing,
					'billing_detail.hapus' => 0,
					'billing_detail.id_tag !=' => 11, //Denda
					'billing_detail.status' => $status
				))
			->group_by('`billing_detail`.`id_tag`,`billing_detail`.`ket`')
			->order_by('db_tag.tagihan,urut')->get();
	}


	function getDataBillingTagihanDetailPiutang($id_billing, $tag_id = "")
	{

		if ($tag_id != '') {
			//  $this->db->where('db_tag.id', $tag_id);
			$where = "id_tag IN (" . $tag_id . ")";
			$this->db->where($where);

		}
		return $this->db->select("Sum(`billing_detail`.`jumlah`) as tagih,note,bulan,tahun,bulan2,tahun2
                  , MIN(`billing_detail`.`tanggal`) as tanggal_awal
                  , MAX(`billing_detail`.`tanggal`) as tanggal_ahir")
			->from('billing_detail')
			//->join('db_tag', 'billing_detail.id_tag=db_tag.id_tag')
			->where(
				array(
					'billing_detail.id_billing' => $id_billing,
					'billing_detail.hapus' => 0,
					'billing_detail.status' => 4
				))
			//->group_by('`billing_detail`.`id_tag`,`billing_detail`.`ket`')
			//->order_by('db_tag.tagihan,urut')
			->get();
	}


	public function getDataPiutang($id_bast, $id_billing = '')
	{

		$this->db->select('SUM(IF(billing_detail.status=1,`billing_detail`.`jumlah`,0)) 
			- SUM(IF(billing_detail.status=2,`billing_detail`.`jumlah`,0))  as `piutang`');
		$this->db->from('billing_detail');
		$this->db->join("bast", "`billing_detail`.`id_bast` = `bast`.`id_bast`", "inner");
		$this->db->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`', 'inner');
		$this->db->where(array(
			'bast.hapus' => 0,
			'billing_detail.hapus' => 0,
		));
		$this->db->where("bast.id_bast", $id_bast);

		if ($id_billing != '') {
			$this->db->where("billing_detail.id_billing < ", $id_billing);
		}
		return $this->db->get()->row();

	}

	public function getDataOverPayment($id_bast, $tanggal)
	{

		$billing = $this->db->select('SUM(`billing_detail`.`jumlah`) as jumlah')
			->from('billing_detail')
			->join("billing", "`billing_detail`.`id_billing` = `billing`.`id_billing`", "inner")
			->where(array(
				'billing_detail.hapus' => 0,
				'billing_detail.status' => 1,
				'billing.id_bast' => $id_bast,
				'billing.tanggal_terbit <' => $tanggal,
			))->get()->row()->jumlah;

		$bayar = $this->db->select('SUM(`billing_detail`.`jumlah`) as jumlah')
			->from('billing_detail')
			->join("bayar", "`billing_detail`.`id_bayar` = `bayar`.`id_bayar`", "inner")
			->where(array(
				'billing_detail.hapus' => 0,
				'billing_detail.status' => 2,
				'bayar.id_bast' => $id_bast,
				'bayar.tanggal <' => $tanggal,
			))->get()->row()->jumlah;

		$ov = ($billing - $bayar);
		$lb = $ov * -1;
		//$lb= ($ov < 0) ? ($ov*-1) : 0;
		$lb = ($ov < 0) ? $lb : 0;

		//return 0;
		//return ($ov < 0) ? $ov : 0;
		//return ($lb > 9999) ? 0 : $lb;
		$lb = ($lb < 9999) ? 0 : $lb;
		return $lb;

	}

	public function getDataPiutangTanggal($id_bast, $id_billing = '')
	{

		$this->db->select("(
				SUM(IF(billing_detail.status=1 AND id_billing !='" . $id_billing . "' 
				AND (`billing_detail`.`id_tag` IS NULL || `billing_detail`.`id_tag` != 30 )  
				,billing_detail.jumlah,0))
			-(SUM(IF(billing_detail.status=2 ,billing_detail.jumlah,0)) 
					+ SUM(IF(billing_detail.status=3,billing_detail.jumlah,0)) 
					)
			) as piutang")
			->select("MIN(billing_detail.tanggal) as tanggal_awal")
			->select("MAX(billing_detail.tanggal) as tanggal_ahir")
			->from('billing_detail')
			->join("bast", "`billing_detail`.`id_bast` = `bast`.`id_bast`", "inner")
			->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`', 'inner');
		$this->db->where(array(
			'bast.hapus' => 0,
			'billing_detail.hapus' => 0,
		));
		//$this->db->where("(`billing_detail`.`id_tag` IS NULL || `billing_detail`.`id_tag` != 30 )");
		$this->db->where("bast.id_bast", $id_bast);
		if ($id_billing != '') {
			//$this->db->where("billing_detail.id_billing < ", $id_billing);
		}
		return $this->db->get()->row();
	}

	public function getDataPiutangJumlah($id_billing = '')
	{

		$this->db->select("SUM(jumlah) as piutang")
			->from('billing_detail');
		$this->db->where(array(
			'bast.hapus' => 0,
			'billing_detail.hapus' => 0,
			'billing_detail.id_tag' => '30',
		));
		if ($id_billing != '') {
			$this->db->where("billing_detail.id_billing", $id_billing);
		}
		return $this->db->get()->row()->piutang;
	}

	public function getDataPiutangManual($id_bast, $id_billing = '')
	{
		$this->db->select("jumlah as piutang,note")
			->from('billing_detail')
			->join("bast", "`billing_detail`.`id_bast` = `bast`.`id_bast`", "inner")
			->join('db_unit', '`bast`.`id_unit` = `db_unit`.`id_unit`', 'inner');
		$this->db->where(array(
			'bast.hapus' => 0,
			'billing_detail.hapus' => 0,
		));
		$this->db->where("bast.id_bast", $id_bast);

		if ($id_billing != '') {
			$this->db->where("billing_detail.id_billing", $id_billing);
		}
		return $this->db->get()->row();
	}


	/**
	 *
	 * Billing unit Developer
	 */

	public function getDataBillingInvoiceDev($id_unit, $tanggal_awal, $tanggal_ahir, $kirim, $print = "")
	{

		$this->db->select('0 as `No`,`db_unit`.`kode` as `No Unit`');
		//$this->db->select('`billing`.`invoice` as `No Invoice`');
		$this->db->select('CONCAT(`dev_billing`.`invoice`,"<br><small>Publish : ",DATE_FORMAT(`dev_billing`.`tanggal_terbit`,"%d-%m-%Y")
,"</small><br><small>Due Date : ",DATE_FORMAT(`dev_billing`.`tanggal_jt`,"%d-%m-%Y"),"</small>") as `Invoice`');
		//$this->db->select("`billing`.`tanggal_jt` as `Due Date`");
		$this->db->select('SUM(IF(dev_billing_detail.status=4,`dev_billing_detail`.`jumlah`,0)) as `PreviousBill`');
		$this->db->select('SUM(IF(dev_billing_detail.status=1 AND dev_billing_detail.id_tag = 11,`dev_billing_detail`.`jumlah`,0)) as `Fine`');

		$this->db->select('SUM(IF(dev_billing_detail.status=1 AND dev_billing_detail.id_tag != 11,`dev_billing_detail`.`jumlah`,0)) as `CurrentBill`');

		$this->db->select('SUM(IF(dev_billing_detail.status=1,`dev_billing_detail`.`jumlah`,0)) 
			+ SUM(IF(dev_billing_detail.status=4,`dev_billing_detail`.`jumlah`,0))  as `Bill`');

		//$this->db->select('`billing`.`print` as `Print`');
		//$this->db->select('`bast`.`kirim` as `Shipped`');
		//$this->db->select('`billing_detail`.`post` as `Status Posting`');
		$this->db->select('`dev_billing`.`id_billing` as Action');
		$this->db->from('dev_billing');
		$this->db->join('db_unit', '`dev_billing`.`id_unit` = `db_unit`.`id_unit`', 'inner');
		$this->db->join("dev_billing_detail", "`dev_billing_detail`.`id_billing` = `dev_billing`.`id_billing`", "inner");
		$this->db->where(array(
			'dev_billing.hapus' => 0,
			'dev_billing_detail.hapus' => 0,
			'dev_billing.tanggal_terbit >=' => $tanggal_awal,
			'dev_billing.tanggal_terbit <=' => $tanggal_ahir,

		));
		$this->db->group_by('dev_billing.id_billing');
		return $this->db->order_by('dev_billing.id_billing ASC');

	}

	public function getDataBillingInvoiceExportDev($id_unit, $tanggal_awal, $tanggal_ahir, $kirim, $print = "")
	{

		$this->db->select('0 as `No`,`db_unit`.`kode` as `No Unit`');
		$this->db->select('`dev_billing`.`invoice` as `No Invoice`');
		$this->db->select("`dev_billing`.`tanggal_terbit` as `Date`");
		$this->db->select("`dev_billing`.`tanggal_jt` as `Due Date`");
		$this->db->select('SUM(IF(dev_billing_detail.status=4,`dev_billing_detail`.`jumlah`,0)) as `PreviousBill`');
		$this->db->select('SUM(IF(dev_billing_detail.status=1 AND dev_billing_detail.id_tag = 11,`dev_billing_detail`.`jumlah`,0)) as `Fine`');

		$this->db->select('SUM(IF(dev_billing_detail.status=1 AND dev_billing_detail.id_tag != 11,`dev_billing_detail`.`jumlah`,0)) as `CurrentBill`');

		$this->db->select('SUM(IF(dev_billing_detail.status=1,`dev_billing_detail`.`jumlah`,0)) 
			+ SUM(IF(dev_billing_detail.status=4,`dev_billing_detail`.`jumlah`,0))  as `Bill`');

		$this->db->from('dev_billing');
		$this->db->join('db_unit', '`dev_billing`.`id_unit` = `db_unit`.`id_unit`', 'inner');
		$this->db->join("dev_billing_detail", "`dev_billing_detail`.`id_billing` = `dev_billing`.`id_billing`", "inner");
		$this->db->where(array(
			'dev_billing.hapus' => 0,
			'dev_billing_detail.hapus' => 0,
			'dev_billing.tanggal_terbit >=' => $tanggal_awal,
			'dev_billing.tanggal_terbit <=' => $tanggal_ahir,

		));
		$this->db->group_by('dev_billing.id_billing');
		return $this->db->order_by('dev_billing.id_billing ASC');

	}

	public function getDataBillingInvoiceExportJurnalDev($id_unit, $tanggal_awal, $tanggal_ahir, $kirim, $print = "")
	{

		$this->db->select('`db_unit`.`kode` as `*Customer`');
		$this->db->select('"" as `Email`');
		$this->db->select('"" as `BillingAddress`');
		$this->db->select('"" as `ShippingAddress`');
		$this->db->select('`dev_billing`.`tanggal_terbit` as `*InvoiceDate`');
		$this->db->select('`dev_billing`.`tanggal_jt` as `*DueDate`');
		$this->db->select('"" as `ShippingDate`');
		$this->db->select('"" as `ShipVia`');
		$this->db->select('"" as `TrackingNo`');
		$this->db->select('"" as `CustomerRefNo`');
		$this->db->select('`dev_billing`.`invoice` as `*InvoiceNumber`');
		$this->db->select('"" as `Message`');
		$this->db->select('"" as `Memo`');
		$this->db->select('db_tag.nama as `*ProductName`');
		$this->db->select('"" as `Description`');
		$this->db->select('"" as `*Quantity`');
		//$this->db->select('COUNT(db_tag.id_tag) as `Unit`');
		$this->db->select('"1" as `Unit`');
		//$this->db->select('`billing_detail`.`jumlah` as `*UnitPrice`');
		$this->db->select('SUM(`dev_billing_detail`.`jumlah`) as `*UnitPrice`');
		$this->db->select('"" as `ProductDiscountRate(%)`');
		$this->db->select('"" as `InvoiceDiscountRate(%)`');
		$this->db->select('"" as `TaxName`');
		$this->db->select('"" as `TaxRate(%)`');
		$this->db->select('"" as `ShippingFee`');
		$this->db->select('"" as `WitholdingAccountCode`');
		$this->db->select('"" as `WitholdingAmount(value or %)`');
		$this->db->select('"" as `#paid?(yes/no)`');
		$this->db->select('"" as `#PaymentMethod`');
		$this->db->select('"" as `#PaidToAccountCode`');
		$this->db->select('"" as `Tags (use ; to separate tags)`');
		$this->db->select('"" as `WarehouseName`');
		$this->db->select('"" as `#currency code(example: IDR`');
		$this->db->select('"" as `USD`');
		$this->db->select('"" as `CAD)`');
		//$this->db->select('`billing_detail`.`post` as `Status Posting`');
		$this->db->from('dev_billing');
		$this->db->join('db_unit', '`dev_billing`.`id_unit` = `db_unit`.`id_unit`', 'inner');
		$this->db->join("dev_billing_detail", "`dev_billing_detail`.`id_billing` = `dev_billing`.`id_billing`", "inner");
		$this->db->join("db_tag", "`db_tag`.`id_tag` = `dev_billing_detail`.`id_tag`", "inner");
		$this->db->where(array(
			'dev_billing.hapus' => 0,
			'dev_billing_detail.hapus' => 0,
			'dev_billing_detail.status' => 1,
			'dev_billing.tanggal_terbit >=' => $tanggal_awal,
			'dev_billing.tanggal_terbit <=' => $tanggal_ahir,

		));
		$this->db->group_by('dev_billing.id_billing');
		$this->db->group_by('dev_billing_detail.id_tag');
		return $this->db->order_by('dev_billing.id_billing,dev_billing_detail.id_tag');

	}


}

?>
