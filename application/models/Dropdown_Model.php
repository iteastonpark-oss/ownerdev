<?php

/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 4/8/2016
 * Time: 2:42 PM
 */
?>
<?php defined('BASEPATH') or exit('No direct script access allowed');

class Dropdown_Model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/**
	 * @param $name
	 * @param $id
	 * @param string $atribut
	 * @return string
	 *
	 */

	public function getDropdownGender($name, $id, $atribut = '')
	{
		$options = array(
			'' => '--choose--',
			'laki-laki' => 'laki-laki',
			'perempuan' => 'perempuan',
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownBulan($name, $id, $atribut = '')
	{
		$options = array(
			//'' => '--Pilih Bulan--',
			'01' => 'Januari',
			'02' => 'Februari',
			'03' => 'Maret',
			'04' => 'April',
			'05' => 'Mei',
			'06' => 'Juni',
			'07' => 'Juli',
			'08' => 'Agustus',
			'09' => 'September',
			'10' => 'Oktober',
			'11' => 'November',
			'12' => 'Desember',
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownHari($name, $id, $atribut = '')
	{
		$options = array();
		for ($i = 0; $i <= 31; $i++) {
			$options[$i] = $i;
		}
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownNamaHari($name, $id, $atribut = '')
	{
		$options = array(
			'SENIN' => 'SENIN',
			'SELASA' => 'SELASA',
			'RABU' => 'RABU',
			'KAMIS' => 'KAMIS',
			'JUMAT' => 'JUMAT',
			'SABTU' => 'SABTU',
			'MINGGU' => 'MINGGU',
		);

		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownNoHari($name, $id, $atribut = '')
	{
		$options = array(
			'1' => 'SENIN',
			'2' => 'SELASA',
			'3' => 'RABU',
			'4' => 'KAMIS',
			'5' => 'JUMAT',
			'6' => 'SABTU',
			'7' => 'MINGGU',
		);

		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownTahun($name, $id, $atribut = '')
	{
		$options = array();
		for ($i = '2010'; $i <= (date('Y') + 2); $i++) {
			$options[$i] = $i;
		}
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownLevel($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id,level as pilih
 from `admin_level` where id <= " . $this->session->level,
			""
		);

		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownAgama($name, $id, $atribut = '')
	{
		$options = array(
			'' => '--choose--',
			'islam' => 'Islam',
			'kristen' => 'Kristen',
			'Katolik' => 'Katholik',
			'hindu' => 'Hindu',
			'budha' => 'Budha',
			'Konghucu' => 'Konghucu',
			'Lainnya' => 'Lainnya',
		);

		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownStatusPerkawinan($name, $id, $atribut = '')
	{
		$options = array(
			'' => '-- Choose --',
			'lajang' => 'Lajang',
			'menikah' => 'menikah',
			'duda' => 'Duda',
			'janda' => 'Janda',
		);

		return form_dropdown($name, $options, $id, $atribut);
	}


	public function getDropdownPendidikan($name, $id, $atribut = '')
	{
		$options = array(
			'' => '-- Choose --',
			'SD' => 'SD / SEDERAJAT',
			'SMP' => 'SMP / SEDERAJAT',
			'SMA' => 'SMA / SEDERAJAT',
			'D1' => 'D1',
			'D2' => 'D2',
			'D3' => 'D3',
			'S1' => 'S1',
			'S2' => 'S2',
			'S3' => 'S3',
		);
		return form_dropdown($name, $options, $id, $atribut);
	}


	public function getHubunganKeluarga($name, $id, $atribut = '')
	{
		$options = array(
			'' => '-- Choose --',
			'Ayah' => 'Ayah',
			'Ibu' => 'Ibu',
			'Istri' => 'Istri',
			'Suami' => 'Suami',
			'Anak' => 'Anak',
		);

		return form_dropdown($name, $options, $id, $atribut);
	}

	/**
	 * @param $name
	 * @param $id
	 * @param string $atribut
	 * @return string
	 *
	 *
	 * DROPDOWN LOKASI
	 */
	public function getDropdownDesa($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_table("lok_desa", "id_desa", "nama", "-- Choose --");
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownKec($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_table("lok_kec", "id_kec", "nama", "-- Choose --");
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownKota($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_table("lok_kab", "id_kab", "nama", "-- Choose --");
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownProv($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_table("lok_prov", "id_prov", "nama", "-- Choose --");
		return form_dropdown($name, $options, $id, $atribut);
	}

	function getKabupatenByProv($name, $id, $provId = "", $atribut = "")
	{
		$options = $this->apl->get_dropdown_list(
			"select id_kab,nama as pilih
 from `lok_kab` where id_prov='" . $provId . "'",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	function getKecamatanByKota($name, $id, $id_kab = "", $atribut = "")
	{
		$options = $this->apl->get_dropdown_list(
			"select id_kec,nama as pilih
 from `lok_kec` where id_kab='" . $id_kab . "'",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	function getDesaByKec($name, $id, $id_kec = "", $atribut = "")
	{
		$options = $this->apl->get_dropdown_list(
			"select id_desa,nama as pilih
 from `lok_desa` where id_kec='" . $id_kec . "'",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownKotaNama($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select nama,nama as pilih
 from `lok_kab`",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	/**
	 *
	 *
	 *
	 *
	 */


	/**
	 * Dropdown Aplikasi
	 */


	public function getDropdownBilling($name, $id, $id_bast, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id_billing,invoice as pilih
 from `billing` where  hapus=0 and id_bast=" . $id_bast,
			"--Choose--"
		);

		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownTagihan($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id_tag,nama as pilih
 from `db_tag` where  1",
			""
		);

		return form_dropdown($name, $options, $id, $atribut);
	}


	public function getDropdownRumusTagihan($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id,rumus as pilih
 from `db_tagihan` where  1",
			""
		);

		return form_dropdown($name, $options, $id, $atribut);
	}


	public function getDropdownTipeUnit($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select nama,nama as pilih
 from `db_tipe` where  hapus=0",
			""
		);

		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownTowerUnit($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select nama,nama as pilih
 from `db_tower` where  hapus=0",
			""
		);

		return form_dropdown($name, $options, $id, $atribut);
	}


	public function getDropdownLantaiUnit($name, $id, $atribut = '')
	{
		$options = array();
		for ($i = 0; $i <= 31; $i++) {
			$options[$i] = $i;
		}
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownGroupUnit($name, $id, $atribut = '')
	{
		$options = array(
			'1' => 'Unit',
			'2' => 'Commercial Area',
			'3' => 'Other',
		);
		return form_dropdown($name, $options, $id, $atribut);
	}


	public function getDropdownPemilikTambahBast($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select nik,nama as pilih
 from `pemilik`",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownUnitTambahBast($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select db_unit.id_unit,kode as pilih
 from `db_unit` left join (select * from bast where hapus=0) bast ON bast.id_unit=db_unit.id_unit 
 where bast.id_bast is null",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}


	public function getDropdownStatusBast($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id,status as pilih from db_status",
			""
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownViaTiket($name, $id, $atribut = '')
	{
		$options = array(
			'bicara langsung' => 'Bicara Langsung',
			'telepon' => 'Telepon',
			'email' => 'Email',
		);
		return form_dropdown($name, $options, $id, 'class="form-control " id="' . $name . '" ' . $atribut);
	}


	public function getDropdownUnit($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select db_unit.id_unit,kode as pilih
 from `db_unit` inner join bast on bast.id_unit=db_unit.id_unit where bast=1",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownUnitKode($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select db_unit.kode,kode as pilih
 from `db_unit` inner join bast on bast.id_unit=db_unit.id_unit where bast=1 and `id_group`=1 order by kode",
			""
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownUnitBast($name, $id, $atribut = '', $choose = '-- Choose --')
	{
		$options = $this->apl->get_dropdown_list(
			"select id_bast,kode as pilih
 from `db_unit` inner join bast on bast.id_unit=db_unit.id_unit where bast=1",
			$choose
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownUnitBastIzinHuni($name, $id, $atribut = '', $id_bast = '')
	{
		;
		$options = $this->apl->get_dropdown_list(
			"select id_bast,kode as pilih
 from `db_unit` inner join bast on bast.id_unit=db_unit.id_unit where `id_group`=1 AND bast.hapus=0
AND bast.id_bast NOT IN(select id_bast from bast_huni where hapus=0 and status < 4 and id_bast != '" . $id_bast . "')",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownUnitBastIzinCaTenant($name, $id, $atribut = '', $id_bast = '')
	{
		;
		$options = $this->apl->get_dropdown_list(
			"select id_bast,kode as pilih
 from `db_unit` inner join bast on bast.id_unit=db_unit.id_unit where `id_group`=2 AND bast.hapus=0
AND bast.id_bast NOT IN(select id_bast from bast_huni where hapus=0 and status < 4 and id_bast != '" . $id_bast . "')",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownUnitBastWhatsapp($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id_bast,CONCAT(kode,' - ',pemilik.nama) as pilih
 from `db_unit` inner join bast on bast.id_unit=db_unit.id_unit inner join pemilik on bast.id_pemilik=pemilik.id_pemilik  where bast=1 AND bast.wa_surat !=''",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownAgent($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select agent.id_agent,nama as pilih
 from `agent` where hapus=0 and tipe=0",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownUnitAgent($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select db_unit.id_unit,kode as pilih
 from `db_unit` inner join bast on bast.id_unit=db_unit.id_unit 
 where bast=1 AND db_unit.id_unit NOT IN(select id_unit from agent_unit where hapus=0 AND id_unit!= '" . $id . "')",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownTipeVendor($name, $id, $atribut = '')
	{
		$options = array(
			'' => '-- Choose --',
			'1' => 'KONTRAKTOR',
			'2' => 'SUPPLIER',
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownVendor($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id_vendor,CONCAT(kode,' - ',nama) as pilih
 from `vendor` where hapus=0",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownVendorKontraktor($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id_vendor,CONCAT(kode,' - ',nama) as pilih
 from `vendor` where tipe=1 and hapus=0",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownVendorSupplier($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id_vendor,CONCAT(kode,' - ',nama) as pilih
 from `vendor` where tipe=2 and hapus=0",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownTipeKontak($name, $id, $atribut = '')
	{
		$options = array(
			'' => '-- Choose --',
			'1' => 'UNIT BAST',
			'2' => 'VENDOR',
			'3' => 'OTHER',
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownTipeHuni($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id,nama as pilih
 from `db_huni`",
			""
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownPeriodeHuni($name, $id, $atribut = '')
	{
		$options = array(
			'1' => 'HARIAN',
			'2' => 'BULANAN',
			'3' => 'TAHUNAN',
		);
		return form_dropdown($name, $options, $id, $atribut);
	}


	public function getDropdownUnitRekening($name, $id, $id_tag = "", $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select db_unit.id_unit,db_unit.kode as pilih,IFNULL(`rek`.`id_rekening`,0) as id_rek
 from `db_unit` 
 left join (select * from utility_rekening where hapus=0 and id_tag='" . $id_tag . "') rek on rek.id_unit=db_unit.id_unit having id_rek=0",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownRekening($name, $id, $id_tag = "", $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select utility_rekening.id_rekening,CONCAT(db_unit.kode,' - ' ,utility_rekening.rekening) as pilih from `utility_rekening` 
 inner join db_unit on utility_rekening.id_unit=db_unit.id_unit 
 where utility_rekening.hapus=0 AND utility_rekening.id_tag='" . $id_tag . "'",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}


	public function getDropdownViaBayar($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id_via, nama from db_via where hapus=0",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownSumberDana($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id_via, nama from kas_via where hapus=0",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownViaBayarPO($name, $id, $atribut = '')
	{
		$options = array(
			'' => "-- Choose --",
			'1' => "CASH",
			'2' => "BANK",
			'3' => "TERMS",
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownKaryawan($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id_karyawan, nama from karyawan where status=0",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownAdminKaryawan($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id_admin, nama_admin from admin where id_admin !=1 AND hapus=0",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownTagihanInvoiceUnit($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id_tag,nama as pilih
 from `db_tag` where  hapus=0 and ( unit=1 || ca=1)",
			""
		);

		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownTagihanInvoice($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id_tag,nama as pilih
 from `db_tag` where  hapus=0 and tipe=1",
			""
		);

		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownTagihanInvoiceDanDeposit($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id_tag,nama as pilih
 from `db_tag` where  hapus=0 and (tipe=1 || (tipe=2 && id_tag=2000))",
			""
		);

		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownPekerjaanWorkOrder($name, $id, $atribut = '')
	{
		$tag = $this->db->query('select * from db_tag where hapus=0 and (tipe=3 || (tipe=2 && id_tag=2001)) 
order by urut,tipe')->result();
		$select = '<select name="' . $name . '" ' . $atribut . '>';
		$select .= '<option value="">-- Choose --</option>';

		foreach ($tag as $t) {
			if ($t->id_tag == $id) {
				$select .= '<option value="' . $t->id_tag . '" selected="selected"
			data-kunci_harga="' . $t->kunci_harga . '" 
			data-material="' . $t->material . '" 
			data-deposit="' . $t->deposit . '" 
			data-harga="' . $t->jumlah . '">' . $t->nama . '</option>';
			} else {
				$select .= '<option value="' . $t->id_tag . '" 
			data-kunci_harga="' . $t->kunci_harga . '" 
			data-material="' . $t->material . '" 
			data-deposit="' . $t->deposit . '" 
			data-harga="' . $t->jumlah . '">' . $t->nama . '</option>';
			}
		}
		$select .= '</select>';
		return $select;
	}

	public function getDropdownHargaFo($name, $id, $atribut = '')
	{
		$tag = $this->db->query('select * from db_tag where hapus=0 and tipe=5  
order by urut,tipe')->result();
		$select = '<select name="' . $name . '" ' . $atribut . '>';
		$select .= '<option value="">-- Choose --</option>';

		foreach ($tag as $t) {
			if ($t->id_tag == $id) {
				$select .= '<option value="' . $t->id_tag . '" selected="selected"
			data-kunci_harga="' . $t->kunci_harga . '" 
			data-material="' . $t->material . '" 
			data-deposit="' . $t->deposit . '" 
			data-harga="' . $t->jumlah . '">' . $t->nama . '</option>';
			} else {
				$select .= '<option value="' . $t->id_tag . '" 
			data-kunci_harga="' . $t->kunci_harga . '" 
			data-material="' . $t->material . '" 
			data-deposit="' . $t->deposit . '" 
			data-harga="' . $t->jumlah . '">' . $t->nama . '</option>';
			}
		}
		$select .= '</select>';
		return $select;
	}

	public function getDropdownKartu($name, $id, $atribut = '')
	{
		$tag = $this->db->query('select * from db_tag where hapus=0 and tipe=4')->result();
		$select = '<select name="' . $name . '" ' . $atribut . '>';
		$select .= '<option value="">-- Choose --</option>';

		foreach ($tag as $t) {
			if ($t->id_tag == $id) {
				$select .= '<option value="' . $t->id_tag . '" selected="selected"
			data-kunci_harga="' . $t->kunci_harga . '" 
			data-harga="' . $t->jumlah . '">' . $t->nama . '</option>';
			} else {
				$select .= '<option value="' . $t->id_tag . '" 
			data-kunci_harga="' . $t->kunci_harga . '" 
			data-harga="' . $t->jumlah . '">' . $t->nama . '</option>';
			}
		}
		$select .= '</select>';
		return $select;
	}


	public function getDropdownTagihanLainnya($name, $id, $atribut = '')
	{

		$options = $this->apl->get_dropdown_list(
			"select id_tag,nama as pilih
 from `db_tag` where  hapus=0 and tipe=6 ",
			""
		);

		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownTagihanLain($name, $id, $atribut = '')
	{

		/*
		$options = $this->apl->get_dropdown_list(
			"select id_tag,nama as pilih
 from `db_tag` where  hapus=0 and tagihan=10 ",
			""
		);
		*/
		$options = $this->apl->get_dropdown_list(
			"select id_tag,nama as pilih
 from `db_tag` where  hapus=0 and tipe=7 ",
			""
		);

		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownTagLainnya($name, $id, $atribut = '')
	{
		$tag = $this->db->query('select * from db_tag where hapus=0 and tipe=6')->result();
		$select = '<select name="' . $name . '" ' . $atribut . '>';
		$select .= '<option value="">-- Choose --</option>';

		foreach ($tag as $t) {
			if ($t->id_tag == $id) {
				$select .= '<option value="' . $t->id_tag . '" selected="selected"
			data-kunci_harga="' . $t->kunci_harga . '" 
			data-admin="' . $t->admin . '" 
			data-tax="' . $t->tax . '" 
			data-harga="' . $t->jumlah . '">' . $t->nama . '</option>';
			} else {
				$select .= '<option value="' . $t->id_tag . '" 
			data-kunci_harga="' . $t->kunci_harga . '" 
			data-admin="' . $t->admin . '" 
			data-tax="' . $t->tax . '" 
			
			data-harga="' . $t->jumlah . '">' . $t->nama . '</option>';
			}
		}
		$select .= '</select>';
		return $select;
	}

	public function getDropdownTagLain($name, $id, $atribut = '')
	{
		$tag = $this->db->query('select * from db_tag where hapus=0 and tipe=7')->result();
		$select = '<select name="' . $name . '" ' . $atribut . '>';
		$select .= '<option value="">-- Choose --</option>';

		foreach ($tag as $t) {
			if ($t->id_tag == $id) {
				$select .= '<option value="' . $t->id_tag . '" selected="selected"
			data-kunci_harga="' . $t->kunci_harga . '" 
			data-admin="' . $t->admin . '" 
			data-tax="' . $t->tax . '" 
			data-harga="' . $t->jumlah . '">' . $t->nama . '</option>';
			} else {
				$select .= '<option value="' . $t->id_tag . '" 
			data-kunci_harga="' . $t->kunci_harga . '" 
			data-admin="' . $t->admin . '" 
			data-tax="' . $t->tax . '" 
			
			data-harga="' . $t->jumlah . '">' . $t->nama . '</option>';
			}
		}
		$select .= '</select>';
		return $select;
	}

	/**
	 * HRM
	 */


	public function getDropdownDepartemen($name, $id, $atribut = '')
	{
		/*
		$options = $this->apl->get_dropdown_list(
			"select id_dep,nama as pilih
 from `db_dep`",
			"-- Choose --");

		return form_dropdown($name, $options, $id, $atribut);
		*/
		$query = $this->db->query('select * from db_dep where 1')->result();
		$select = '<select name="' . $name . '" ' . $atribut . '>';
		$select .= '<option value="">-- Choose --</option>';

		foreach ($query as $q) {
			if ($q->id_dep == $id) {
				$select .= '<option value="' . $q->id_dep . '" selected="selected"
			data-kode="' . $q->kode . '" >' . $q->nama . '</option>';
			} else {
				$select .= '<option value="' . $q->id_dep . '" 
			data-kode="' . $q->kode . '" >' . $q->nama . '</option>';
			}
		}

		$select .= '</select>';
		return $select;
	}

	public function getDropdownJabatan($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id_jabatan,nama as pilih
 from `jabatan`",
			"-- Choose --"
		);

		return form_dropdown($name, $options, $id, $atribut);
	}


	public function getDropdownFingerKaryawan($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id_finger, CONCAT(id_finger,' ',nama) as pilih from karyawan where id_finger != ''",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}


	public function getDropdownStatusPemohon($name, $id, $atribut = '')
	{
		$options = array(
			'PEMILIK' => 'PEMILIK',
			'KUASA PEMILIK' => 'KUASA PEMILIK',
			'AGENT' => 'AGENT',
			'PENYEWA' => 'PENYEWA',
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownKetPengajuan($name, $id, $atribut = '')
	{
		$options = array(
			'' => '-- Choose --',
			//'BARU' => 'BARU',
			'HILANG' => 'HILANG',
			'BARU' => 'BARU',
		);

		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownTipeDokument($name, $id, $atribut = '')
	{
		$options = array(
			'1' => 'FILE',
			'2' => 'FOLDER',
		);

		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownTipeGroup($name, $id, $atribut = '')
	{
		$options = array(
			'0' => 'Maintenance Asset',
			'1' => 'GROUP',
		);

		return form_dropdown($name, $options, $id, $atribut);
	}


	/**
	 *
	 * Akuntansi
	 */

	public function getDropdownAkunCashBank($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id_rek,nama_rek as pilih
 from `acc_rekening` WHERE cb !=0 ",
			"-- Choose --"
		);

		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownAkunCash($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id_rek,nama_rek as pilih
 from `acc_rekening` WHERE cb=1 ",
			"-- Choose --"
		);

		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownAkunBank($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id,nama_rek as pilih
 from `acc_rekening` WHERE cb=2 AND id_koperasi=" . $this->session->id_koperasi,
			"-- Choose --"
		);

		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownCoa($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id_rek,CONCAT(no_rek, ' - ',nama_rek) as pilih
 from `acc_rekening` ",
			"-- Choose --"
		);

		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownCoaByGroup($name, $id, $atribut = '', $group = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id_rek,CONCAT(no_rek, ' - ',nama_rek) as pilih
 from `acc_rekening` where `group`='" . $group . "'",
			"-- Choose --"
		);

		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownCoaPendapatan($name, $id, $atribut = '', $group = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id_rek,CONCAT(no_rek, ' - ',nama_rek) as pilih
 from `acc_rekening` where `group` IN (4)",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownCoaBiaya($name, $id, $atribut = '', $group = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id_rek,CONCAT(no_rek, ' - ',nama_rek) as pilih
 from `acc_rekening` where `group` IN (5,6)",
			"-- Choose --"
		);

		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownCoaNeraca($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id_rek,nama_rek as pilih
 from `acc_rekening` WHERE `group` <= 3",
			"-- Choose --"
		);

		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownCoaLabaRugi($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id_rek,nama_rek as pilih
 from `acc_rekening` WHERE `group` > 3 ",
			"-- Choose --"
		);

		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownTipe($name, $id, $atribut = '')
	{
		$options = array(
			'1' => 'DEBET',
			'2' => 'KREDIT',
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownTipeJurnal($name, $id, $atribut = '')
	{
		$options = array(
			'0' => 'JURNAL UMUM',
			'1' => 'KAS MASUK',
			'2' => 'KAS KELUAR',
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownTipeMasukKeluar($name, $id, $atribut = '')
	{
		$options = array(
			'1' => 'KAS MASUK',
			'2' => 'KAS KELUAR',
		);
		return form_dropdown($name, $options, $id, $atribut);
	}


	public function getDropdownGroupAkun($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id,nama as acc_group from acc_group ORDER by id",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownPosSaldo($name, $id, $atribut = '')
	{
		$options = array(
			'' => '-- Choose --',
			'DEBET' => 'DEBET',
			'KREDIT' => 'KREDIT',
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownPosBook($name, $id, $atribut = '')
	{
		$options = array(
			'MASUK' => 'MASUK',
			'KELUAR' => 'KELUAR',
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownPosLaporan($name, $id, $atribut = '')
	{
		$options = array(
			'' => '-- Choose --',
			'NERACA' => 'NERACA',
			'LABA RUGI' => 'LABA RUGI',
		);
		return form_dropdown($name, $options, $id, $atribut);
	}


	public function getDropdownBudgeting($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id_budget,nama from budget",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownBudgetingDepartement($name, $id, $atribut = '', $id_dep = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id_budget,nama from budget where id_dep='" . $id_dep . "'",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownRekeningDefault($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id_default,nama as pilih from acc_default ORDER by id_default",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownRekeningCashBank($name, $id, $atribut = '')
	{

		$options = array(
			'0' => 'TIDAK',
			'1' => 'CASH',
			'2' => 'BANK',
		);
		return form_dropdown($name, $options, $id, $atribut);
	}


	public function getDropdownUploadByNama($name, $id, $atribut = '', $nama = '')
	{
		$query = "SELECT
  `db_upload`.`id_upload`,
  `db_upload`.`nama`
FROM
  `db_upload`
  INNER JOIN `set_upload` ON `set_upload`.`id_upload` = `db_upload`.`id_upload`
WHERE 1 ";
		if ($nama != '') {
			$query .= " AND `set_upload`.`nama` = '" . $nama . "'";
		}

		$options = $this->apl->get_dropdown_list(
			$query,
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}


	/**
	 * Purchase
	 */


	public function getDropdownPriority($name, $id, $atribut = '')
	{

		$options = array(
			'URGENT' => 'URGENT',
			'PRIORITY' => 'PRIORITY',
			'REGULER' => 'REGULER',
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownKategoriPr($name, $id, $atribut = '')
	{

		$options = $this->apl->get_dropdown_list(
			"select nama,nama as pilih from p_kat ",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownKategoriKas($name, $id, $atribut = '')
	{

		$options = $this->apl->get_dropdown_list(
			"select id,nama as pilih from kas_kat ",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownUnitBarang($name, $id, $atribut = '')
	{

		$options = $this->apl->get_dropdown_list(
			//"select unit,unit as pilih from barang group by unit order by unit",
			"select unit,unit as pilih from barang_unit group by unit order by unit",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownUnitAsset($name, $id, $atribut = '')
	{

		$options = $this->apl->get_dropdown_list(
			"select unit,unit as pilih from asset group by unit order by unit",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownKategoriBarang($name, $id, $atribut = '')
	{

		$options = $this->apl->get_dropdown_list(
			"select id_kategori,nama as pilih from barang_kategori ",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}


	public function getDropdownBarang($name, $id, $atribut = '')
	{
		$tag = $this->db->query('select * from barang')->result();
		$select = '<select name="' . $name . '" ' . $atribut . '>';
		$select .= '<option value="" selected="selected">-- Choose --</option>';

		foreach ($tag as $t) {
			$select .= '<option value="' . $t->id_barang . '" 
			data-harga="' . $t->harga . '" 
			data-merk="' . $t->merk . '" 
			data-unit="' . $t->unit . '" 
			>' . $t->kode . " - " . $t->nama . " - " . $t->merk . '</option>';
		}
		$select .= '</select>';
		return $select;
	}


	public function getDropdownPr($name, $id, $atribut = '')
	{
		$query = "SELECT p_req.* FROM p_req LEFT JOIN (select * FROM p_po where hapus=0 AND post > 0) p_po 
ON p_po.id_req=p_req.id_req WHERE p_req.hapus=0 AND p_req.post = '4' AND p_po.id_po IS NULL";

		if ($id != '') {
			$query .= ' UNION SELECT p_req.* FROM p_req INNER JOIN p_po 
ON p_po.id_req=p_req.id_req WHERE p_po.hapus=0 AND p_po.id_req=' . $id;
		}
		$query = $this->db->query($query)->result();
		$select = '<select name="' . $name . '" ' . $atribut . '>';
		$select .= '<option value="" data-ket="">-- Choose --</option>';

		foreach ($query as $q) {
			if ($q->id_req == $id) {
				$select .= '<option value="' . $q->id_req . '" selected="selected"
			data-ket="' . $q->ket . '" 
			data-no_form="' . $q->no_form . '" >' . $q->no_form . '</option>';
			} else {
				$select .= '<option value="' . $q->id_req . '" 
			data-ket="' . $q->ket . '" 
			data-no_form="' . $q->no_form . '" >' . $q->no_form . '</option>';
			}
		}

		$select .= '</select>';
		return $select;
	}


	public function getDropdownBidangVendor($name, $id, $tipe, $atribut = '')
	{

		$options = $this->apl->get_dropdown_list(
			"select id_bidang,nama as pilih from vendor_bidang where hapus=0 and tipe=" . $tipe,
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	/**
	 * Tipe Pembayaran
	 */

	public function getDropdownTipePembayaran($name, $id, $atribut = '')
	{
		$options = array(
			'' => '-- Choose --',
			'1' => 'Other',
			'2' => 'Request',
			'3' => 'IPL',
			'4' => 'Cash/Bank In',
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownTipeBiaya($name, $id, $atribut = '')
	{
		$options = array(
			'' => '--: Choose :--',
			'1' => 'PPD',
			'2' => 'Purchase Order',
			'3' => 'Refund',
			'4' => 'Cash/Bank Out',
		);
		return form_dropdown($name, $options, $id, $atribut);
	}


	/**
	 * PPD & KAS
	 */
	public function getDropdownKasUntuk($name, $id, $atribut = '')
	{
		$options = array(
			'1' => 'PPD',
			'2' => 'Purchase Order',
		);
		return form_dropdown($name, $options, $id, $atribut);
	}


	/**
	 * Tipe Lembur
	 */
	public function getDropdownWaktuLembur($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id,nama as pilih from lembur_tipe ",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	/**
	 * Kategori Perizinan
	 */
	public function getDropdownKategoriPerizinan($name, $id, $atribut = '')
	{

		$options = $this->apl->get_dropdown_list(
			"select id_kategori,nama as pilih from kontrak_kategori ",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownStatusPerizinan($name, $id, $atribut = '')
	{
		$options = array(

			'1' => 'Berjalan',
			'2' => 'Kontrak Berakhir',
			'3' => 'OnProgress',
		);

		return form_dropdown($name, $options, $id, $atribut);
	}

	/**
	 * Kategori Histori collection
	 */
	public function getDropdownCollKategori($name, $id, $atribut = '')
	{

		$options = $this->apl->get_dropdown_list(
			"select id,nama as pilih from coll_kategori ",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownPengiriman($name, $id, $atribut = '')
	{

		$options = $this->apl->get_dropdown_list(
			"select id_kirim,nama as pilih
 from `db_kirim` ",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownEvent($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id_event,nama as pilih
 from `event` where hapus=0",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownKategoriItem($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id_kategori,nama as pilih from item_kategori ",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}


	public function getDropdownItem($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id_barang,CONCAT(kode,' - ',nama) as pilih
 from `barang` where hapus=0 and storage=1",
			"-- Choose --"
		);

		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownAsset($name, $id, $atribut = '')
	{
		/*
		$options = $this->apl->get_dropdown_list(
			"select id_asset,CONCAT(nomor,' - ',nama) as pilih
 from `asset` where hapus=0 ",
			"-- Choose --"
		);

		return form_dropdown($name, $options, $id, $atribut);
		*/

		$data = $this->db->query('select * from asset where hapus=0')->result();
		$select = '<select name="' . $name . '" ' . $atribut . '>';
		$select .= '<option value="" selected="selected">-- Choose --</option>';

		foreach ($data as $d) {
			$select .= '<option value="' . $d->id_asset . '" 
			data-id_perintah="' . $d->id_perintah . '"
			>' . $d->nomor . " - " . $d->nama . '</option>';
		}
		$select .= '</select>';
		return $select;
	}

	public function getDropdownKategoriAlat($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id_kategori,nama as pilih
	 from `asset_kategori` where hapus=0 and id_kategori !=1",
			"-- Choose --"
		);

		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownPerintah($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id_perintah,nama as pilih
	 from `perintah` where hapus=0",
			"-- Choose --"
		);

		return form_dropdown($name, $options, $id, $atribut);
	}


	/**
	 * Tipe ceklis ya tidak
	 */
	public function getDropdownTipeCeklis($name, $id, $atribut = '')
	{
		$options = array(
			'' => "-- Choose --",
			'1' => "Yes",
			'0' => "No",
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	/**
	 * Tipe ceklis ya tidak
	 */
	public function getDropdownTipeIsianCeklis($name, $id, $atribut = '')
	{
		$options = array(
			'' => "-- Choose --",
			'1' => "Yes/No",
			'2' => "Input",
			'3' => "Choice",
		);
		return form_dropdown($name, $options, $id, $atribut);
	}


	/**
	 * RM
	 */

	public function getDropdownUnitBastRM($name, $id, $atribut = '', $id_rm = '')
	{
		;
		$options = $this->apl->get_dropdown_list(
			"select id_rm,kode as pilih
 from `db_unit` inner join rm on rm.id_unit=db_unit.id_unit where bast=1 
AND rm.id_rm NOT IN(select id_rm from rm_huni where hapus=0 and status < 4 and id_rm != '" . $id_rm . "')",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownViaBayarRM($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id_via, nama from rm_via where hapus=0",
			"-- Choose --"
		);
		return form_dropdown($name, $options, $id, $atribut);
	}


	/**
	 * Dropdown WA Blast
	 */
	public function getDropdownWaBlast($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id_wa,CONCAT(nomor,' -- ',nama) as pilih
	 from `db_wa` where hapus=0",
			"-- Choose --"
		);

		return form_dropdown($name, $options, $id, $atribut);
	}


	/**
	 * RAB
	 */

	public function getDropdownMaterialAtauJasa($name, $id, $atribut = '')
	{
		$options = array(
			'' => '-- Choose --',
			'1' => 'Service',
			'2' => 'Material',
		);

		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownRab($name, $id, $atribut = '')
	{
		$options = $this->apl->get_dropdown_list(
			"select id_rab,nama as pilih
	 from `rab` where hapus=0",
			"-- Choose --"
		);

		return form_dropdown($name, $options, $id, $atribut);
	}

	/**
	 *
	 * Absensi
	 */


	public function getDropdownJamKerja($name, $id, $atribut = '')
	{

		$options1 = $this->apl->get_dropdown_list(
			"select kode,kode as pilih
	 from `fs_jam_kerja` where hapus=0",
			"-- Choose --"
		);

		$options2 = array('C' => 'C', 'L' => 'L');
		$options = $options1 + $options2;
		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownJadwalKerja($name, $id, $atribut = '')
	{

		$options = $this->apl->get_dropdown_list(
			"select id_jadwal_kerja,nama as pilih
	 from `fs_jadwal_kerja` where hapus=0",
			"-- Choose --"
		);

		return form_dropdown($name, $options, $id, $atribut);
	}

	public function getDropdownPinAbsensi($name, $id, $atribut = '')
	{

		$options = $this->apl->get_dropdown_list(
			"select pin,nama as pilih
	 from `fs_user` where 1",
			"-- Choose --"
		);

		return form_dropdown($name, $options, $id, $atribut);
	}
}

?>
