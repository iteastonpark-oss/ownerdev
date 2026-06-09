<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 4/12/2016
 * Time: 4:50 PM
 */
?>
<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Tagihan
{
	var $db = NULL;

	function __construct()
	{
		$CI =& get_instance();
		$this->db = $CI->load->database('default', TRUE);
	}


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
			if ($bulan >= $result1 AND $bulan <= $result2) {
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
		return date('Y-m-d', strtotime('-' . ($rentang_periode - 1) . ' month',
			strtotime(date('Y-' . $this->bulan($periode * $rentang_periode) . '-' . $tanggal_periode))));
	}

	public function periode_tagihan_ahir($periode)
	{
		$tahun = date('Y');
		if (date("d") >= $this->tanggal_periode() && date("m") == '12' && $tahun == date('Y')) {
			$tahun = $tahun + 1;
		}
		$tanggal_periode = $this->tanggal_periode();
		$rentang_periode = $this->rentang_periode();
		$ahir = date('Y-m-d',
			strtotime(date($tahun . '-' . $this->bulan($periode * $rentang_periode) . '-' . $tanggal_periode)));

		$ahir = date('Y-m-d', strtotime('+1 month',
			strtotime($ahir)));
		return date('Y-m-d', strtotime('-1 days',
			strtotime($ahir)));


	}

	function total_pertagihan($tagihan, $jumlah, $luas, $rentang_bulan)
	{
		$total = 0;
		if ($tagihan == '1') {
			$total = $jumlah * $luas * $rentang_bulan;
		}
		if ($tagihan == '2') {
			$total = 0;
		}
		if ($tagihan == '3') {
			$total = $jumlah * $rentang_bulan;
		}
		if ($tagihan == '4') {
			$total = $jumlah;
		}
		return $total;
	}
}

?>

