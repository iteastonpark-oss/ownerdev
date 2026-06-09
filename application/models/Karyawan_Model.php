<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Karyawan_Model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
		$this->load->database();

	}

	public function get_data($id = '')
	{
		return $this->db->select('0 as No
        , 
  `karyawan`.`foto` as `Picture`,
  `karyawan`.`nik` as `Nik`,
  `karyawan`.`nama` as `Name`,
  `karyawan`.`alamat` as `Address`,
  `karyawan`.`hp` as `Hp/tel`,
  `jabatan`.`nama` AS `Position`,
  `karyawan`.`id_karyawan` as `Action`')
			->from('karyawan')
			->join('karyawan_relasi', '`karyawan_relasi`.`id_karyawan` = `karyawan`.`id_karyawan`', 'left')
			->join('jabatan', '`karyawan_relasi`.`id_jabatan` = `jabatan`.`id_jabatan`', 'left')
			->where(
				array(
					'karyawan.status' => 0,
				));

	}

	public function get_data_karyawan_by_id($id = '')
	{
		return $this->db->select('karyawan.*
        ,karyawan_relasi.id_jabatan,karyawan_relasi.id_proyek,karyawan_relasi.id_departemen,karyawan_relasi.fungsi_kerja
        ,jabatan.nama as jabatan')
			->from('karyawan')
			->join('karyawan_relasi', '`karyawan_relasi`.`id_karyawan` = `karyawan`.`id_karyawan`', 'left')
			->join('jabatan', '`karyawan_relasi`.`id_jabatan` = `jabatan`.`id_jabatan`', 'left')
			->where(
				array(
					//'karyawan.status' => 0,
					'karyawan.id_karyawan' => $id,
				));
	}

	public function get_salary($id)
	{

		return $this->db->select('
 `gaji`.`id_gaji`,
 `gaji`.`nama`,
 `gaji`.`keterangan`')
			->select("IFNULL(`karyawan_gaji`.`nominal`,'0') as nominal")
			->from('`gaji`')
			->join('(Select `id_karyawan`,`nominal`,`id_gaji` from `karyawan_gaji` 
            where id_karyawan=' . $id . ' AND hapus=0) karyawan_gaji', '`karyawan_gaji`.`id_gaji` = `gaji`.`id_gaji`', 'left')
			->order_by('gaji.id_gaji ASC')
			->get();

	}

	public function get_kontrak($id = '')
	{
		return $this->db->select('0 as No,
         `nomor` as `Nomor Kontrak`,
         `kontrak_awal` as `Kontrak Awal`,
  `kontrak_ahir` as `Kontrak Ahir`,
  `nilai` As `Nilai Kontrak`,
  `id` as `Aksi`')
			->from('karyawan_kontrak')
			->where(
				array(
					'id_karyawan' => $id,
					'hapus' => 0,
				));

	}

	public function get_berkas($id = '')
	{
		return $this->db->select('0 as No,
         `file` as `File`,
  `nama` as `File Name`,
  `id` as `Action`')
			->from('karyawan_berkas')
			->where(
				array(
					'id_karyawan' => $id,
					'hapus' => 0,
				));

	}

	public function get_keluarga($id = '')
	{
		return $this->db->select('0 as No,
         `nama` as `Name`,
         `hp` as `Contact`,
         `hubungan` as `Relationship`,
         `alamat` as `Address`,
  `id` as `Action`')
			->from('karyawan_keluarga')
			->where(
				array(
					'id_karyawan' => $id,
					'hapus' => 0,
				));

	}

	public function get_pengalaman($id = '')
	{
		return $this->db->select('0 as No,
         `perusahaan` as `Company Name`,
         `tahun` as `Year`,
         `jabatan` as `Position`,
  `id` as `Action`')
			->from('karyawan_pengalaman')
			->where(
				array(
					'id_karyawan' => $id,
					'hapus' => 0,
				));

	}

	public function get_salary_jumlah($id = '')
	{
		$this->db->select("(SUM(IF(`gaji`.`tipe` = '+', `nominal`, 0))) - (SUM(IF(`gaji`.`tipe` = '-', `nominal`, 0))) AS 'nilai'")
			->from('karyawan_gaji')
			->join('gaji', '`gaji`.`id_gaji` = `karyawan_gaji`.`id_gaji`')
			->where(
				array(
					'id_karyawan' => $id,
					'karyawan_gaji.hapus' => 0,
				));

		$nilai = $this->db->get()->row();
		if ($nilai) {
			$nilai = $nilai->nilai;
		} else {
			$nilai = "0";
		}
		return $nilai;
	}

	public function get_karyawan($id = '')
	{
		return $this->db->select('0 as No,
         `file` as `File`,
  `nama` as `Nama File`,
  `id` as `Aksi`')
			->from('karyawan_berkas')
			->where(
				array(
					'id_karyawan' => $id,
					'hapus' => 0,
				));

	}


	/**
	 * Absensi
	 */

	public function getAbsensiDulu($id_finger, $tanggal_awal, $tanggal_ahir)
	{

		$this->db->select('0 as `No`,`absen`.`id_finger` as `id`,`karyawan`.`nik` as `Nik`');
		$this->db->select("`karyawan`.`nama` as `Nama`");
		$this->db->select("`absen`.`waktu` as `Waktu Masuk`");
		$this->db->select("STR_TO_DATE(waktu,'%d/%m/%Y') as `tanggal`");
		$this->db->select('`absen`.`status` as Status');
		$this->db->select('`absen`.`id_absen` as Aksi');
		$this->db->from('absen');
		$this->db->join("karyawan", "`karyawan`.`id_finger` = `absen`.`id_finger`", "left");
		$this->db->where(array(
			'absen.hapus' => 0,
			//"STR_TO_DATE(waktu,'%d/%m/%Y') >=" => $tanggal_awal,
			//"STR_TO_DATE(waktu,'%d/%m/%Y') <=" => $tanggal_ahir,
			'absen.tanggal >=' => $tanggal_awal,
			'absen.tanggal <=' => $tanggal_ahir,

		));
		if ($id_finger != '') {
			$this->db->where('absen.id_finger', $id_finger);
		}
		return $this->db->order_by('tanggal ASC');

	}
public function getAbsensi($id_karyawan, $tanggal_awal, $tanggal_ahir)
	{

		$this->db->select('0 as `No`,`karyawan`.`nik` as `Nik`');
		$this->db->select("`karyawan`.`nama` as `Nama`");

		$this->db->select("CONCAT(absen.tanggal_masuk,' ',absen.jam_masuk) as `In`");
		$this->db->select("CONCAT(absen.tanggal_pulang,' ',absen.jam_pulang) as `Out`");
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

	public function getAbsensiExportTemplate()
	{

		$this->db->select('0 as `No`,`absen`.`id_finger` as `id`,`karyawan`.`nik` as `Nik`');
		$this->db->select("`karyawan`.`nama` as `Nama`");
		$this->db->select("CONCAT(`absen`.`tanggal`,' ',`absen`.`jam`) as `Waktu`");
		$this->db->select('`absen`.`status` as Status');
		$this->db->select('`absen`.`id_absen` as Aksi');
		$this->db->from('absen');
		$this->db->join("karyawan", "`karyawan`.`id_finger` = `absen`.`id_finger`", "left");
		$this->db->where(array(
			'absen.hapus' => 0,

		))->limit(1);
		return $this->db->order_by('tanggal ASC');

	}

	public function getAbsensiRekap($id_finger, $tanggal_awal, $tanggal_ahir)
	{

		$query = "(SELECT absen.id_finger,
  `tanggal`, `status`,IF(`status` = 'C/Masuk', `jam`,'') AS 'masuk', IF(`status` = 'C/Keluar', `jam`,'') AS 'keluar'
FROM
  `absen`
WHERE
  `id_finger` = " . $id_finger . " AND absen.tanggal >= '" . $tanggal_awal . "' AND absen.tanggal <= '" . $tanggal_ahir . "'
GROUP BY
  `status`,`tanggal` ) as r";

		return $this->db->select('tanggal as `Tanggal`')
			->select('`karyawan`.`nik` as `Nik`')
			->select("`karyawan`.`nama` as `Nama`")
			->select('max(masuk) as `ScanMasuk`,max(keluar) as `ScanKeluar`')
			->from($query)
			->join("karyawan", "`karyawan`.`id_finger` = `r`.`id_finger`", "left")
			->group_by('r.tanggal');
	}

	/**
	 * Lembur
	 */

	public function getLembur($post, $tanggal_awal = "", $tanggal_ahir = "")
	{
		$this->db->select(
			'0 as No');
		//$this->db->select('karyawan.nama as `Employee`');
		$this->db->select('tanggal as `Request Date`');
		$this->db->select('db_dep.nama as `Departement`');
		$this->db->select('`admin`.`nama_admin` as `Created By`
			');
		if ($post > 1) {
			$this->db->select('periksa.nama_admin as `HRD`')
				->join('admin periksa', 'periksa.id_admin=lembur.id_hrd', 'left');

		}

		//	$this->db->select('lembur.upah as `Wages`');

		if ($post == 4) {

			$this->db->where(array(

				'lembur.tanggal >=' => $tanggal_awal,
				'lembur.tanggal <=' => $tanggal_ahir,
			));
		}

		$this->db->select('lembur.id_lembur as `Action`')
			->join('db_dep', 'db_dep.id_dep=lembur.id_dep', 'left')
			->join('admin', '`admin`.`id_admin` = `lembur`.`id_admin`', 'left')
			//->join('karyawan', '`karyawan`.`id_karyawan` = `lembur`.`id_karyawan`')
			->where(array(
				'lembur.hapus' => 0,

			));

		$this->db->where(
			array('lembur.post' => $post)
		);


		if (!$this->apl->super_user() and !$this->apl->bm() and !$this->apl->hrd()) {
			//$this->db->where('p_req.id_admin', $this->session->id_admin);
			if ($this->session->id_dep == '4') {
				$this->db->where('lembur.id_dep IN (2,4)');
			} else {
				$this->db->where('lembur.id_dep', $this->session->id_dep);
			}
		}

		return $this->db->from('lembur');

	}

	public function getLemburDetail($id_lembur)
	{
		$this->db->select(
			'0 as No');
		$this->db->select('karyawan.nama as `Employee`');
		$this->db->select('lembur_detail.jam_mulai as `start`');
		$this->db->select('lembur_detail.jam_selesai as `end`');
		$this->db->select('lembur_detail.upah as `wage`');

		$this->db->select('lembur_detail.id as `Action`')
			->join('lembur', 'lembur.id_lembur=lembur_detail.id_lembur')
			->join('db_dep', 'db_dep.id_dep=lembur.id_dep', 'left')
			->join('karyawan', '`karyawan`.`id_karyawan` = `lembur_detail`.`id_karyawan`')
			->where(array(
				'lembur_detail.hapus' => 0,
				'lembur_detail.id_lembur' => $id_lembur,

			));

		return $this->db->from('lembur_detail');

	}


}
