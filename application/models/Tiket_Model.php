<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Tiket_Model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
		$this->load->database();

	}

	public function getTiket($tipe, $post, $jatuh_tempo = "0", $id_dep = "")
	{
		$this->db->select(
			'0 as No,
			IFNULL(`db_unit`.`kode`,tiket.pelapor) as `Code Unit`,
			`tiket`.`no_form` as `No Form`,
			`tiket`.`ket` as `Note`,
			`tiket`.`tanggal` as `Date`');
		if ($post == 3 || $post == 6) {
			$this->db->select('approv as `Approv`');

		}

		$this->db->select('tanggal_awal as `Start Date`');
		$this->db->select('tanggal_ahir as `End Date`');

		$this->db->select('tiket.id_tiket as `Action`')
			->join('bast', 'bast.id_bast=tiket.id_bast')
			->join('db_unit', 'db_unit.id_unit=bast.id_unit')
			->join('pemilik', 'pemilik.id_pemilik=bast.id_pemilik')
			->where(array(
				'tiket.hapus' => 0,
				'tiket.tipe' => $tipe,
				'tiket.id_bast' => $this->session->id_bast,

			));
		$this->db->where(array(
			'tiket.post' => $post,
		));

		if ($jatuh_tempo == 1) {
			$this->db->where('tanggal_ahir', null);
		}
		if ($jatuh_tempo == 2) {
			$this->db->where('tanggal_ahir >=', date('Y-m-d'));
		}
		if ($jatuh_tempo == 3) {
			$this->db->where('tanggal_ahir <', date('Y-m-d'));
		}



		return $this->db->from('tiket');

	}



}
