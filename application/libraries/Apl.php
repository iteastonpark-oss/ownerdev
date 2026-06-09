<?php

/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 4/12/2016
 * Time: 4:50 PM
 */
?>
<?php defined('BASEPATH') or exit('No direct script access allowed');

class Apl
{
	var $db = NULL;

	function __construct()
	{
		$CI = &get_instance();
		$this->db = $CI->load->database('default', TRUE);
		$CI->load->library("session");
	}

	public function getAllData($table)
	{
		return $this->db->get($table);
	}

	public function getAllDataLimited($table, $limit, $offset)
	{
		return $this->db->get($table, $limit, $offset);
	}

	public function getSelectedDataLimited($table, $data, $limit, $offset)
	{
		return $this->db->get_where($table, $data, $limit, $offset);
	}

	public function getSelectedData($table, $data, $order = '')
	{
		if ($order != '') {
			$this->db->order_by($order . ' ASC ');
		}
		return $this->db->get_where($table, $data);
	}

	function updateData($table, $data, $field_key)
	{
		$this->db->update($table, $data, $field_key);
	}

	function deleteData($table, $data)
	{
		$this->db->delete($table, $data);
	}

	function insertData($table, $data)
	{
		$this->db->insert($table, $data);
	}

	function manualQuery($q)
	{
		return $this->db->query($q);
	}


	function checkData($table, $data)
	{
		return $this->db->get_where($table, $data)->num_rows();
	}


	public function get_dropdown_list($query, $title = '')
	{
		$result = $this->db->query($query);
		$dd = array();
		if ($title != '') {
			$dd[''] = $title;
		}

		if ($result->num_rows() > 0) {
			foreach ($result->result_array() as $row) {
				// tentukan value (sebelah kiri) dan labelnya (sebelah kanan)
				$r = array_values($row);
				for ($i = 0; $i < 2; $i++) {
					$dd[$r[0]] = $r[1];
				}
			}
		}

		return $dd;
	}

	public function get_dropdown_table($table, $selected, $value, $title = '')
	{
		$result = $this->db->get($table);
		$dd = array();
		if ($title != '') {
			$dd[''] = $title;
		}

		if ($result->num_rows() > 0) {
			foreach ($result->result_array() as $row) {
				// tentukan value (sebelah kiri) dan labelnya (sebelah kanan)
				$dd[$row[$selected]] = $row[$value];
			}
		}

		return $dd;
	}

	public function get_nilai($query, $cari)
	{
		$query = $this->db->query($query);
		$row = $query->row_array();
		if (isset($row)) {
			return $row[$cari];
		} else {
			return "";
		}
	}

	public function get_nilai_pilih($table, $field, $where)
	{
		$this->db->select($field);
		$this->db->from($table);
		$this->db->where($where);
		$query = $this->db->get();
		$row = $query->row_array();
		if (isset($row)) {
			$r = array_values($row);
			return $r[0];
		} else {
			return "0";
		}
	}

	public function get_nilai_pilih_number($table, $field, $where)
	{
		$this->db->select($field);
		$this->db->from($table);
		$this->db->where($where);
		$query = $this->db->get();
		$row = $query->row_array();
		if (isset($row)) {
			return $row[$field];
		} else {
			return "0";
		}
	}

	public function update_tambah($tabel, $field, $tambah, $where)
	{

		$masuk = $this->get_nilai_pilih($tabel, $field, $where);
		$this->updateData(
			$tabel,
			array($field => ($masuk + $tambah)),
			$where
		);
	}

	public function urut($table, $field)
	{

		$result = $this->db->query("SELECT $field FROM $table ORDER BY $field DESC LIMIT 1");
		$row1 = $result->row_array();

		$result2 = $this->db->query("SELECT COUNT($field) FROM $table ");
		$nrow = $result2->num_rows();
		if ($nrow == 0) {
			$id = 1;
		} else {
			$id = $row1[$field] + 1;
		}

		return $id;
	}

	public function password($pass)
	{
		return sha1($pass);
	}

	public function urut_where($table, $field, $where = '')
	{
		$this->db->select($field)
			->from($table)
			->order_by($field . ' DESC')
			->limit(1);
		if ($where != '')
			$this->db->where($where);

		$row1 = $this->db->get()->row_array();

		$this->db->select('COUNT(' . $field . ')')
			->from($table);
		if ($where != '')
			$this->db->where($where);


		$nrow = $this->db->get()->num_rows();
		if ($nrow == 0) {
			$id = 1;
		} else {
			$id = $row1[$field] + 1;
		}

		return $id;
	}

	function number_format($number, $sts = 0)
	{
		$number = ($number == '') ? '0' : $number;
		if ($number != '') {
			if ($sts == '0')
				return str_replace(",", "", $number);

			else
				return number_format($number);
		}
	}

	function tgl_format($tgl, $sts = 0)
	{
		if ($tgl != '') {
			if ($sts == '0')
				return date('Y-m-d', strtotime($tgl));
			if ($sts == '2') {
				$bulan = $this->bulan(date('m', strtotime($tgl)), 2);
				return $bulan . ' ' . date('Y', strtotime($tgl));
			}
			if ($sts == '3') {
				$bulan = $this->bulan(date('m', strtotime($tgl)), 2);
				return date('j') . ' ' . $bulan . ' ' . date('Y', strtotime($tgl));
			}
			if ($sts == '4')
				return date('F Y', strtotime($tgl));

			if ($sts == '5') {
				$bulan = $this->bulan(date('m', strtotime($tgl)), 2);
				return date('d', strtotime($tgl)) . ' ' . $bulan . ' ' . date('Y', strtotime($tgl));
			}
			if ($sts == '6')
				return date('j M Y', strtotime($tgl));

			else
				return date('d-m-Y', strtotime($tgl));
		} else {
			return '';
		}
	}

	public function log($ket, $data_lama, $data_baru, $table = '', $id = '')
	{
		$CI = &get_instance();
		$CI->load->library("session");
		$data = array(
			'id' => $this->urut("admin_log", "id"),
			'ket' => $ket,
			'username' => $CI->session->userdata('username'),
			'id_admin' => $CI->session->userdata('id_admin'),
			'data_lama' => $data_lama,
			'data_baru' => $data_baru,
			'nama_tabel' => $table,
			'id_tabel' => $id,
		);
		$this->db->insert("admin_log", $data);
		return $this->db->insert_id();
	}

	public function log_print($ket, $tabel, $id_tabel, $print, $data, $data_detail, $qr_code)
	{
		$CI = &get_instance();
		$CI->load->library("session");

		$data = array(
			'id' => $this->urut("log_print", "id"),
			'ket' => $ket,
			'username' => $CI->session->userdata('email'),
			'user_id' => $CI->session->userdata('id_user'),
			'bms_id' => $CI->session->userdata('bms_id'),
			'nama_tabel' => $tabel,
			'id_tabel' => $id_tabel,
			'print' => $print,
			'qr_code' => $qr_code,
			'data' => $data,
			'data_detail' => $data_detail,
		);
		$this->db->insert("log_print", $data);
		//return $this->db->insert_id();
	}

	/**
	 * Penomoran
	 */


	function counter($code, $tahun = "")
	{
		if ($tahun == "") {
			$tahun = date('Y');
		}
		$query = "SELECT * FROM db_counter WHERE code='$code' AND tahun='$tahun'";
		$result = $this->db->query($query);
		if ($result->num_rows() == '0') {
			$id = 1;
			$query_tambah = "INSERT INTO db_counter (code,nomor,tahun) VALUES ('$code','1','$tahun')";
			$this->db->query($query_tambah);
		} else {
			$row = $result->row();
			$id = $row->nomor + 1;

			$query_update = " UPDATE db_counter SET nomor='$id'
            WHERE code='$code' AND tahun='$tahun'";
			$this->db->query($query_update);
		}
		return $id;
	}

	function counter_view($code)
	{
		$tahun = date('Y');
		$query = "SELECT * FROM db_counter WHERE code='$code' AND tahun='$tahun'";
		$result = $this->db->query($query);
		if ($result->num_rows() == '0') {
			$id = 1;
			$query_tambah = "INSERT INTO db_counter (code,nomor,tahun) VALUES ('$code','0','$tahun')";
			$this->db->query($query_tambah);
		} else {
			$row = $result->row();
			$id = $row->nomor + 1;
		}
		return $id;
	}

	function counter_view_purchase($table)
	{
		$tahun = date('Y');
		$query = "SELECT MAX(CAST(no_form as int)) as nomor FROM " . $table . " 
		WHERE  post > '0' AND DATE_FORMAT(tanggal,'%Y') ='$tahun'";
		$result = $this->db->query($query);
		if ($result->num_rows() == '0') {
			$id = 1;
		} else {
			$row = $result->row();
			$id = $row->nomor + 1;
		}
		return $id;
	}

	function counter_view_tabel($table)
	{
		$tahun = date('Y');
		$query = "SELECT MAX(CAST(no_form as int)) as nomor FROM " . $table . " 
		WHERE  post > '0' AND DATE_FORMAT(tanggal,'%Y') ='$tahun'";
		$result = $this->db->query($query);
		if ($result->num_rows() == '0') {
			$id = 1;
		} else {
			$row = $result->row();
			$id = $row->nomor + 1;
		}
		return $id;
	}

	function counter_code($code)
	{
		$bulan = $this->bulan_romawi();
		$tahun = date('Y');
		$id = $code . '/' . $bulan . '/' . $tahun;
		return $id;
	}


	public function penomoran($nomor)
	{
		$format = sprintf("%06d", $nomor);
		return $format;
	}


	function hari($num, $sts = 1)
	{

		if ($sts == 1) {
			$hari = array(
				'1' => "Senin",
				'2' => "Selasa",
				'3' => "Rabu",
				'4' => "Kamis",
				'5' => "Jum'at",
				'6' => "Sabtu",
				'7' => "Minggu",
			);
		}
		if ($sts == 2) {
			$hari = array(

				'1' => "S",
				'2' => "S",
				'3' => "R",
				'4' => "K",
				'5' => "J",
				'6' => "S",
				'7' => "M",
			);
		}
		return $hari[$num];
	}

	function bulan_romawi($tanggal = "")
	{
		$tanggal = ($tanggal == "") ? date('m') : $tanggal;
		$BulanR = array("I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII");
		$bulan = substr($tanggal, 0, 2);

		$result = $BulanR[(int)$bulan - 1];
		return $result;
	}


	function bulan($bulan, $sts = 1)
	{
		if ($sts == 1) {
			$BulanR = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
		}
		if ($sts == 2) {
			$BulanR = array(
				"Januari",
				"Februari",
				"Maret",
				"April",
				"Mei",
				"Juni",
				"Juli",
				"Agustus",
				"September",
				"Oktober",
				"November",
				"Desember"
			);
		}
		if ($sts == 3) {
			$BulanR = array(
				"Jan",
				"Feb",
				"Mar",
				"Apr",
				"Mei",
				"Jun",
				"Jul",
				"Ags",
				"Sep",
				"Okt",
				"Nov",
				"Des"
			);
		}
		if ($sts == 4) {
			$BulanR = array("I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII");
		}
		if ($sts == 5) {
			$BulanR = array(
				"January",
				"February",
				"March",
				"April",
				"May",
				"June",
				"July",
				"August",
				"September",
				"October",
				"November",
				"December"
			);
		}

		$result = $BulanR[(int)$bulan - 1];
		return $result;
	}

	function selisih_bulan($tgl1, $tgl2)
	{
		$numBulan = ((int)date("Y", strtotime($tgl2)) - (int)date("Y", strtotime($tgl1))) * 12;
		$numBulan += (int)date("m", strtotime($tgl2)) - (int)date("m", strtotime($tgl1));
		return $numBulan;
	}

	function datediff($tgl1, $tgl2)
	{
		$tgl1 = strtotime($tgl1);
		$tgl2 = strtotime($tgl2);
		$diff_secs = abs($tgl1 - $tgl2);
		$base_year = min(date("Y", $tgl1), date("Y", $tgl2));
		$diff = mktime(0, 0, $diff_secs, 1, 1, $base_year);
		return array(
			"years" => date("Y", $diff) - $base_year,
			"months_total" => (date("Y", $diff) - $base_year) * 12 + date("n", $diff) - 1,
			//"months_total" => (date("Y", $diff) - $base_year) * 12 + date("n", $diff),
			"months" => date("n", $diff) - 1,
			//"months" => date("n", $diff),
			"days_total" => floor($diff_secs / (3600 * 24)),
			"days" => date("j", $diff) - 1,
			"hours_total" => floor($diff_secs / 3600),
			"hours" => date("G", $diff),
			"minutes_total" => floor($diff_secs / 60),
			"minutes" => (int)date("i", $diff),
			"seconds_total" => $diff_secs,
			"seconds" => (int)date("s", $diff)
		);
	}

	function tambah_bulan($dt, $opr = '')
	{
		$check_date = date('d', strtotime($dt));
		$check_bulan = date('m', strtotime($dt));
		if ($opr == '-') {
			if ($check_date >= '29') {
				if ($check_bulan == '03') {
					$dt = date("Y-m-d", strtotime(date("Y-03-01", strtotime($dt)) . " -1 day"));
				} else {
					$dt = date("Y-m-$check_date", strtotime(date("Y-m-d", strtotime($dt)) . " -1 month"));
				}
			} else {
				$dt = date("Y-m-d", strtotime(date("Y-m-d", strtotime($dt)) . " -1 month"));
			}
		} else {
			if ($check_date >= '29') {
				if ($check_bulan == '01') {
					$dt = date("Y-m-d", strtotime(date("Y-03-01", strtotime($dt)) . " -1 day"));
				} else {
					$dt = date("Y-m-$check_date", strtotime(date("Y-m-d", strtotime($dt)) . " +1 month"));
				}
			} else {
				$dt = date("Y-m-d", strtotime(date("Y-m-d", strtotime($dt)) . " +1 month"));
			}
		}
		return $dt;
	}

	function terbilang($satuan)
	{

		$huruf = array(
			"",
			"Satu",
			"Dua",
			"Tiga",
			"Empat",
			"Lima",
			"Enam",
			"Tujuh",
			"Delapan",
			"Sembilan",
			"Sepuluh",
			"Sebelas"
		);

		if ($satuan < 12)
			return " " . $huruf[$satuan];
		elseif ($satuan < 20)
			return $this->terbilang($satuan - 10) . " Belas";
		elseif ($satuan < 100)
			return $this->terbilang($satuan / 10) . " Puluh" .

				$this->terbilang($satuan % 10);
		elseif ($satuan < 200)
			return "Seratus" . $this->terbilang($satuan - 100);
		elseif ($satuan < 1000)
			return $this->terbilang($satuan / 100) . " Ratus" .

				$this->terbilang($satuan % 100);
		elseif ($satuan < 2000)
			return "Seribu " . $this->terbilang($satuan - 1000);
		elseif ($satuan < 1000000)
			return $this->terbilang($satuan / 1000) . " Ribu " .

				$this->terbilang($satuan % 1000);
		elseif ($satuan < 1000000000)
			return $this->terbilang($satuan / 1000000) . " Juta" .
				$this->terbilang($satuan % 1000000);
		elseif ($satuan >= 1000000000)
			echo "Angka terlalu Besar";
	}

	function Terbilang2($satuan)
	{
		$satuan = str_replace(".", ",", $satuan);
		$satuan = explode(',', $satuan);
		//echo $satuan[1];
		return (isset($satuan[1])) ? $this->Terbilang($satuan[0]) . " Rupiah " . $this->Terbilang($satuan[1]) . " sen "
			: $this->Terbilang($satuan[0]) . " Rupiah";
	}

	public function anchor($tombol, $kode, $modul = '')
	{


		$cek_tombol = $this->getSelectedData("admin_tombol", array(
			'modul' => $modul,
			'kode' => $kode
		));
		if ($cek_tombol->num_rows() == '0') {
			$this->insertData("admin_tombol", array(
				'id' => $this->urut("admin_tombol", "id"),
				'modul' => $modul,
				'kode' => $kode,
			));
		}

		$CI = &get_instance();
		$CI->load->library("session");


		if ($this->super_user()) {
			return $tombol;
		} else {
			if ($CI->session->tombol != '') {
				if (element($cek_tombol->row()->id, $CI->session->tombol)) {
					return $tombol;
				} else {
					//  return '<a href="#" class="btn btn-default btn-sm"><i class="fa fa-remove"></i></a>';
				}
			} else {
				return false;
			}
		}
	}

	function super_user()
	{
		$CI = &get_instance();
		$CI->load->library("session");

		if ($CI->session->userdata('level') == 100) {
			return true;
		}
	}

	function tr()
	{
		$CI = &get_instance();
		$CI->load->library("session");

		if ($CI->session->userdata('id_dep') == 4) {
			return true;
		}
	}

	function bm()
	{
		$CI = &get_instance();
		$CI->load->library("session");

		if ($CI->session->userdata('id_dep') == 9) {
			return true;
		}
	}

	function fin()
	{
		$CI = &get_instance();
		$CI->load->library("session");

		if ($CI->session->userdata('id_dep') == 5) {
			return true;
		}
	}

	function hrd()
	{
		$CI = &get_instance();
		$CI->load->library("session");

		if ($CI->session->userdata('id_dep') == 8) {
			return true;
		}
	}

	function administrator()
	{
		$CI = &get_instance();
		$CI->load->library("session");
		if ($CI->session->userdata('level') == 100) {
			return true;
		}
	}

	function penulisan_kode($str)
	{
		return strtoupper(str_replace(' ', '', $str));
	}

	function tampil_segment()
	{
		$CI = &get_instance();
		$jumlah_segment = $CI->uri->total_segments();
		$modul = 'home';
		for ($i = 1; $i <= $jumlah_segment; $i++) {
			if ($i == '1') {
				$modul = $CI->uri->segment($i);
			} else {
				$modul .= "/" . $CI->uri->segment($i);
			}
		}
		return $modul;
	}

	function random_code($kode_awal)
	{
		return $kode_awal . "T" . strtoupper(
			substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 3)
		);
	}

	function generate_username($string_name, $kode_awal, $rand_no = 99)
	{
		$string_name = str_replace(".", "", $string_name);
		$username_parts = array_filter(explode(" ", strtolower($string_name))); //explode and lowercase name
		$username_parts = array_slice($username_parts, 0, 2); //return only first two arry part
		$part1 = (!empty($username_parts[0])) ? substr($username_parts[0], 0, 5) : ""; //cut first name to 8 letters
		$part2 = (!empty($username_parts[1])) ? substr($username_parts[1], 0, 2) : ""; //cut second name to 5 letters
		// $part3 = ($rand_no) ? rand(0, $rand_no) : "";

		//$username = $part1 . str_shuffle($part2) . $kode_awal . $part3; //str_shuffle to randomly shuffle all characters
		$username = $part1 . str_shuffle($part2) . $kode_awal; //str_shuffle to randomly shuffle all characters
		return $username;
	}

	function strShorten($str, $maxlen = 10, $insert = '/.../')
	{
		if ($str && !is_array($str)) { // valid string
			if ($maxlen && is_numeric($maxlen) && $maxlen < strlen($str)) { // string needs shortening
				if ($insert && ($ilen = strlen($insert))) { // insert string and length
					if ($ilen >= $maxlen) { // insert string too long so use default insert
						$insert = '**'; // short default so works even when a very small $maxlen
						$ilen = 2;
					}
				}
				$chars = $maxlen - $ilen; // number of $str chars to keep
				$start = ceil($chars / 2); // position to start cutting
				$end = floor($chars / 2); // position from end to stop cutting
				return substr_replace($str, $insert, $start, -$end); // first.insert.last
			} else { // string already short enough
				return $str; // return original string
			}
		}
	}

	public function export_excell($query, $nama = "")
	{
		$CI = &get_instance();
		if (!$query)
			return false;
		$CI->load->library('PHPExcel');
		$CI->load->library('PHPExcel/IOFactory');
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
		$objPHPExcel->setActiveSheetIndex(0);
		// Field names in the first row
		$fields = $query->list_fields();
		$col = 0;
		foreach ($fields as $field) {
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
			$col++;
		}
		// Fetching the table data
		$row = 2;
		$no = 0;
		foreach ($query->result() as $data) {
			$no++;
			$data->No = $no;
			$col = 0;
			foreach ($fields as $field) {
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data->$field);
				$col++;
			}
			$row++;
		}


		$filename = $nama . time() . ".xls";
		$objPHPExcel->setActiveSheetIndex(0);
		$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('export/' . $filename);
		$CI->load->helper('download');
		$file_path = 'export/' . $filename;
		$file_content = file_get_contents($file_path); // Read the file's contents
		if (file_exists($file_path)) {
			// unlink($file_path);
		}
		force_download($filename, $file_content);
	}

	public function export_pdf($query)
	{
		$CI = &get_instance();
		if (!$query)
			return false;
		$CI->load->library('PHPExcel');
		$CI->load->library('PHPExcel/IOFactory');
		$rendererName = PHPExcel_Settings::PDF_RENDERER_DOMPDF;
		$rendererLibrary = 'dompdf';
		$rendererLibraryPath = dirname(_FILE_) . '/' . $rendererLibrary;
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
		$objPHPExcel->setActiveSheetIndex(0);
		$fields = $query->list_fields();
		$col = 0;
		foreach ($fields as $field) {
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 5, $field);
			$col++;
		}
		$row = 6;
		$no = 0;
		foreach ($query->result() as $data) {
			$no++;
			$data->No = $no;
			$col = 0;
			foreach ($fields as $field) {
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data->$field);
				$col++;
			}
			$row++;
		}

		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Simple');
		$objPHPExcel->getActiveSheet()->setShowGridLines(false);
		$objPHPExcel->setActiveSheetIndex(0);
		if (!PHPExcel_Settings::setPdfRenderer(
			$rendererName,
			$rendererLibraryPath
		)) {
			die('NOTICE: Please set the $rendererName and $rendererLibraryPath values' .
				'<br />' .
				'at the top of this script as appropriate for your directory structure');
		}

		$filename = time() . "_print.pdf";
		$objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
		$objWriter->save('export/' . $filename);
		$CI->load->helper('download');
		$file_path = 'export/' . $filename;
		$file_content = file_get_contents($file_path); // Read the file's contents
		if (file_exists($file_path)) {
			unlink($file_path);
		}
		force_download($filename, $file_content);
	}

	public function export_page_pdf($query)
	{

		$CI = &get_instance();
		$CI->load->library('Pdf');
		if (!$query)
			return false;
		$data['result'] = $query;
		$nama_file = "lap.pdf";
		$CI->pdf->load_view('layout/generate_pdf', $data, $nama_file);
	}


	public function generate_pdf($name, $link, $data)
	{

		$CI = &get_instance();
		$CI->load->view($link, $data);
		// Get output html
		$html = $CI->output->get_output();
		// add external css library
		//$html .= '<link type="text/css" href="'.base_url('assets/argon/css/argon.css?v=1.0.0').'" rel="stylesheet">';
		$html .= '<link type="text/css" href="' . base_url('assets/argon/css/argon.css?v=1.0.0') . '" rel="stylesheet">';
		// Load pdf library


		$CI->load->library('Pdf');
		$CI->dompdf->load_html(ob_get_clean());
		$CI->dompdf->loadHtml($html);
		//$CI->dompdf->set_base_path(base_url("assets"));
		$CI->dompdf->set_option('isRemoteEnabled', TRUE);
		$CI->dompdf->set_option('isHtml5ParserEnabled', TRUE);
		// setup size
		$CI->dompdf->setPaper('A4', 'portrait');
		// Render the HTML as PDF
		$CI->dompdf->render();

		// Output  PDF (1 = download and 0 = preview)
		$CI->dompdf->stream($name, array("Attachment" => 1));
	}


	public function no_hp($hp)
	{
		if (substr($hp, 0, 2) == "62") {
			$hp = substr_replace($hp, '0', 0, 2);
		} elseif (substr($hp, 0, 3) == "+62") {
			$hp = substr_replace($hp, '0', 0, 3);
		} else {
			$hp = $hp;
		}
		return $hp;
	}

	function umur($birthday)
	{
		$biday = new DateTime($birthday);
		$today = new DateTime();

		$diff = $today->diff($biday);


		return $diff->y . " Tahun";
	}


	/**
	 *
	 *
	 * Funggsi fungis aplikasi
	 *
	 *
	 */
	public function master_periode()
	{
		return $this->get_nilai_pilih("set_tagihan", "value", array('id' => '1'));
	}

	public function tanggal_periode()
	{
		return $this->get_nilai_pilih("set_tagihan", "value", array('id' => '3'));
	}

	public function tanggal_bast()
	{
		return $this->get_nilai_pilih("set_tagihan", "value", array('id' => '4'));
	}

	public function tanggal_jt()
	{
		return $this->get_nilai_pilih("set_tagihan", "value", array('id' => '2'));
	}

	public function rentang_periode()
	{
		$master_periode = $this->master_periode();
		$rentang_periode = 12 / $master_periode;
		return $rentang_periode;
	}

	public function periode_tagihan($bulan = '')
	{
		if ($bulan == '') {
			$bulan = date('m');
		}
		$master_periode = $this->master_periode();
		$periode = $this->rentang_periode();

		$bulan1 = 1;
		for ($i = 1; $i <= $master_periode; $i++) {
			$result1 = $this->bulan($bulan1);
			$result2 = $this->bulan($i * $periode);
			if ($bulan >= $result1 and $bulan <= $result2) {
				return $i;
			}
			$bulan1 += $periode;
		}
	}

	public function warna_periode($bulan = '')
	{
		$periode = $this->periode_tagihan(date('m', strtotime($bulan)));

		$warna_tr = '';
		if ($periode == 1) {
			$warna_tr = 'success';
		}
		if ($periode == 2) {
			$warna_tr = 'info';
		}
		if ($periode == 3) {
			$warna_tr = 'success';
		}
		if ($periode == 4) {
			$warna_tr = 'info';
		}
		return $warna_tr;
	}

	public function list_periode_tagihan_ahir($bulan = '')
	{
		if ($bulan == '') {
			$bulan = date('m');
		}
		$master_periode = $this->master_periode();
		$periode = $this->rentang_periode();
		$bulan1 = 1;
		$result2 = array();
		for ($i = 1; $i <= $master_periode; $i++) {
			$result1 = $this->bulan($bulan1);
			$result2[$this->bulan($i * $periode)] = $this->bulan($i * $periode);
			$bulan1 += $periode;
		}
		return $result2;
	}


	public function periode_tagihan_awal($periode)
	{

		$tanggal_periode = $this->tanggal_periode();
		$rentang_periode = $this->rentang_periode();
		return date('Y-m-d', strtotime(
			'-' . ($rentang_periode - 1) . ' month',
			strtotime(date('Y-' . $this->bulan($periode * $rentang_periode) . '-' . $tanggal_periode))
		));
	}

	public function periode_tagihan_ahir($periode)
	{
		$tahun = date('Y');
		if (date("d") >= $this->tanggal_periode() && date("m") == '12' && $tahun == date('Y')) {
			$tahun = $tahun + 1;
		}
		$tanggal_periode = $this->tanggal_periode();
		$rentang_periode = $this->rentang_periode();
		$ahir = date(
			'Y-m-d',
			strtotime(date($tahun . '-' . $this->bulan($periode * $rentang_periode) . '-' . $tanggal_periode))
		);

		$ahir = date('Y-m-d', strtotime(
			'+1 month',
			strtotime($ahir)
		));
		return date('Y-m-d', strtotime(
			'-1 days',
			strtotime($ahir)
		));
	}

	function total_pertagihan($tagihan, $jumlah, $luas, $pengali)
	{
		$total = 0;
		if ($tagihan == '1') {
			$total = $jumlah * $luas * $pengali;
		}
		if ($tagihan == '2') {
			$total = $jumlah * $pengali;
		}
		if ($tagihan == '3') {
			$total = $jumlah * $pengali;
		}
		if ($tagihan == '4') {
			$total = $jumlah;
		}
		if ($tagihan == '5') {
			$total = $jumlah;
		}
		if ($tagihan == '6') {
			$total = ($pengali > 0) ? ($jumlah / 100) * $pengali : '0';
		}
		if ($tagihan == '10') {
			$total = ($pengali > 0) ? ($jumlah / 100) * $pengali : '0';
		}
		return $total;
	}


	public function bms()
	{
		return $this->getSelectedData("bms", array('id_bms' => $this->session->id_bms))->row();
	}

	public function tagihan($where)
	{
		$list = $this->db->from('db_tag')->where($where)->get();
		$data = array();
		foreach ($list->result() as $a) {
			$data[] = $a->id_tag;
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
			$data[$a->id_tag] = $a->id_tag;
		}
		return $data;
	}

	public function karyawan($id_karyawan)
	{
		return $this->manualQuery("SELECT
  `jabatan`.`nama` AS `jabatan`,
  `karyawan`.*
FROM
  `karyawan_relasi`
  INNER JOIN `jabatan` ON `karyawan_relasi`.`id_jabatan` =
    `jabatan`.`id_jabatan`
  INNER JOIN `karyawan` ON `karyawan`.`id_karyawan` =
    `karyawan_relasi`.`id_karyawan`
WHERE
  `karyawan_relasi`.`id_karyawan` = '" . $id_karyawan . "'")->row();
	}


	public function hide_nama($nama = "YUSTIRA PURNAMA", $start = "2", $end = "2")
	{
		$nama = explode(" ", $nama);
		$tampil = array();
		for ($i = 0; $i < count($nama); $i++) {
			$len[$i] = strlen($nama[$i]);
			for ($j = $start; $j < $len[$i] - $end; $j++) {
				$nama[$i] = $this->replace_char($nama[$i], $j, "X");
			}
			$tampil[$i] = $nama[$i];
		}
		return implode(" ", $tampil);
	}

	function replace_char($string, $position, $newchar)
	{
		if (strlen($string) <= $position) {
			return $string;
		}
		$string[$position] = $newchar;
		return $string;
	}

	function distance($lat1, $lon1, $lat2, $lon2, $unit)
	{

		$theta = $lon1 - $lon2;
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		$miles = $dist * 60 * 1.1515;
		$unit = strtoupper($unit);

		if ($unit == "K") {
			return ($miles * 1.609344);
		} else if ($unit == "N") {
			return ($miles * 0.8684);
		} else {
			return $miles;
		}
	}


	function ceiling($number, $significance = 1)
	{
		if ($significance == '100') {
			return (is_numeric($number) && is_numeric($significance)) ? (ceil($number / $significance) * $significance) : false;
		}
		if ($significance == '1000') {
			$sisa = (is_numeric($number) && is_numeric($significance))
				? (floor($number / $significance) * $significance) : false;
			$sisa = $number - $sisa;
			if ($sisa < '500') {
				return (is_numeric($number) && is_numeric($significance))
					? (floor($number / $significance) * $significance) : false;
			}
			if ($sisa == '500') {
				$significance = '500';
				return (is_numeric($number) && is_numeric($significance))
					? (floor($number / $significance) * $significance) : false;
			}
			if ($sisa > '500') {
				return (is_numeric($number) && is_numeric($significance))
					? (ceil($number / $significance) * $significance) : false;
			}
			//return $sisa;
		}
	}
}
?>

