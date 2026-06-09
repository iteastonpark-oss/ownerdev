<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 4/8/2016
 * Time: 2:42 PM
 */
?>

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tagihan extends CI_Controller
{
	var $modul = 'billing/tagihan';

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Billing_Model', 'billing_model');
		$this->load->model('Crud_Model', 'crud_model');
		$this->billing = new Billing_Model();
		$this->crud_model = new Crud_Model();
		$this->apl = new Apl();
		$this->tombol = new Tombol();
		$this->pesan = new Pesan();
	}


	public function index()
	{

		$data['tombol_view'] = '';
		$data['field'] = $this->billing->getDataBillingTagihan()->get()->list_fields();
		$data['page'] = 'billing/tagihan/view'; //Halaman di tampilkan
		$data['judul'] = 'Informasi Tagihan';
		$this->load->view('home', $data);
	}


	public function ajax_list()
	{
		$column_search = array('db_unit.kode');
		$column_order = array(
			null,
			'NoUnit',
			'luas',
			'terahir_terbit',
			null,
			null,
			'piutang',
			null,
			null,
			null,
		);

		$list = $this->crud_model->get_data(
			$this->billing->getDataBillingTagihan(), $column_search, $column_order);
		$record_total = $this->crud_model->get_jumlah(
			$this->billing->getDataBillingTagihan());
		$record_filter = $this->crud_model->get_jumlah_filter(
			$this->billing->getDataBillingTagihan(), $column_search, $column_order);


		$no = isset($_POST['start']) ? $_POST['start'] : '0';
		$jumlah = $list->num_fields();
		$data_array = array();

		foreach ($list->result_array() as $data) {
			$no++;
			$r = array_values($data);
			$r[0] = $no;
			$r[0] = $no;
			$r[3] = $this->apl->tgl_format($r[3], 1);
			$tanggal = explode(',', $r[4]);
			$r[4] = '';
			for ($j = 0; $j < count($tanggal); $j++) {
				if ($tanggal[$j] != '') {
					$r[4] .= $this->apl->tgl_format(date('Y-m-d', strtotime('+1 days',
							strtotime($tanggal[$j]))), 1) . '</br>';
				}
			}
			$nama_tag = explode(',', $r[5]);
			$r[5] = '';
			for ($j = 0; $j < count($nama_tag); $j++) {
				if ($nama_tag[$j] != '') {
					$r[5] .= $nama_tag[$j] . '</br>';
				}
			}
			$piutang = $r[6];
			if ($r[6] == 0) {
				$r[6] = '<div class="btn-group btn-group-xs pull-right">'
					. anchor('#modal_form',
						$this->apl->number_format($r[6], 1),
						'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal" class="posting_data_awal"')
					. '</div>';
			} else {
				$r[6] = '<p class="pull-right">' . $this->apl->number_format($r[6], 1) . '</p>';
			}


			if ($r[7] != '') {
				$ipl = explode(',', $r[7]);
				$rincina = '';
				$total_ipl = 0;
				for ($k = 0; $k < count($ipl); $k++) {
					if ($ipl[$k] != '') {
						$tag = $this->apl->getSelectedData("db_tag", array('id_tag' => $ipl[$k]))->row();
						$sub_total_ipl = $this->apl->total_pertagihan($tag->tagihan, $tag->jumlah, $r[2], $this->apl->rentang_periode());
						//$rincina .= $tag->periode . " x " . $tag->jumlah . '   = ' . $this->apl->number_format($sub_total_ipl, 1) . '<br>';
						$rincina .= $this->apl->rentang_periode() . " x " . $this->apl->number_format($tag->jumlah, 1)
							. '   = ' . $this->apl->number_format($sub_total_ipl, 1) . '<br>';
						$total_ipl += $sub_total_ipl;
					}
				}
				$r[7] = $rincina . '<span class="pull-right">' . $this->apl->number_format($total_ipl, 1) . '<p>';
			} else {
				$r[7] = '<p class="pull-right">0</p>';
			}


			$r[$jumlah - 2] = '<span class="h6 pull-right">Rp. ' . $this->apl->number_format(($piutang + $total_ipl + $r[8]), 1) . '</span>';
			$r[8] = '<span class="pull-right">' . $this->apl->number_format($r[8], 1) . '</span>';


			$r[$jumlah - 1] = $this->apl->anchor('<div class="dropdown">
<button class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown">Action</button>
<div class="dropdown-menu dropdown-menu-right">'
				. anchor(site_url('billing/master?id=' . $r[$jumlah - 1]),
					'<i class="fa fa-edit"></i> Master Bill', 'class="dropdown-item"')
				. anchor('#modal_form',
					'<i class="fa fa-info"></i> Detail BAST',
					'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal" class="detail dropdown-item"')
				/*
				. anchor(site_url('unit/bast/surat?id=' . $r[$jumlah - 1]),
					'<i class="fa fa-send"></i> send', 'class="dropdown-item"')
				*/
				. anchor(site_url('billing/invoice/add?id=' . $r[$jumlah - 1]),
					'<i class="fa fa-book"></i> Invoice',
					'class="dropdown-item"')

				. '</div></div>', 'aksi ' . $this->modul, $this->modul);

			/*
			$r[$jumlah - 1] = '<div class="btn-group btn-sm">'
				. anchor(site_url('billing/master?id=' . $r[$jumlah - 1]),
					'<i class="fa fa-edit"></i> Master', 'class="btn btn-info"')

				. anchor('#modal_form',
					'<i class="fa fa-send"></i> Invoice',
					'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal" class="posting_data_list btn btn-warning"')
				. anchor(site_url('billing/invoice/add?id=' . $r[$jumlah - 1]),
					'<i class="fa fa-send"></i> Invoice',
					'class="btn btn-warning"')
				. '</div>';
		*/
			$data_array[] = $r;

		}
		$output = array(
			"draw" => isset($_POST['draw']) ? $_POST['draw'] : '',
			"recordsTotal" => $record_total,
			"recordsFiltered" => $record_filter,
			"data" => $data_array,
		);
		header('Content-Type: application/json; charset=utf-8');
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET,POST');
		echo json_encode($output);//output to json format

	}

	public function form_posting_billing_list($id)
	{
		$data['tahun'] = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
		if (!isset($_GET['tahun'])) {
			if (date("d") >= $this->apl->tanggal_periode() && date("m") == '12' && $data['tahun'] == date('Y')) {
				$data['tahun'] = $data['tahun'] + 1;
			}
		}
		$data['judul'] = 'FORM TERBITKAN TAGIHAN';
		$data['list'] = $this->billing->getDataMasterTagihanUnitPeriode($id, $this->apl->tagihan(array('tagihan' => 1, 'unit' => 1)));
		$data['list_adm'] = $this->billing->getDataMasterTagihanUnitPeriode($id, $this->apl->tagihan(array('tagihan' => 3, 'unit' => 1)));
		$data['list_adm_invoice'] = $this->billing->getDataMasterTagihanLainnya($this->apl->tagihan(array('tagihan' => 5)));
		$utility = $this->billing->getDataMasterTagihanUnitUtility($id, $this->apl->tagihan(array('tagihan' => 2, 'unit' => 1)));
		$data['jumlah_utility'] = $utility->num_rows();
		$data['list_utility'] = $utility;
		$data['id_bast'] = $id;

		$p = $this->billing->getDataPiutang($id);
		$data['piutang'] = (isset($p)) ? $p->piutang : '0';
		$data['denda'] = ($data['piutang'] == '0') ? '0' : (3 / 100) * $data['piutang'];
		$this->load->view('billing/tagihan/form_posting_billing_list', $data);
	}

	function ajax_tambah_tagihan_list()
	{
		/**
		 * Insert tabel ar_billing
		 */
		$id_bast = isset($_POST['id_bast']) ? $_POST['id_bast'] : '';
		$id_billing = $this->apl->urut('billing', 'id_billing');
		$id_unit = $this->apl->get_nilai_pilih("bast", "id_unit", array('id_bast' => $id_bast));
		$id_pemilik = $this->apl->get_nilai_pilih("bast", "id_pemilik", array('id_bast' => $id_bast));
		$nama = $this->apl->get_nilai_pilih("pemilik", "nama", array('id_pemilik' => $id_pemilik));
		$tlp = $this->apl->get_nilai_pilih("pemilik", "hp", array('id_pemilik' => $id_pemilik));
		$alamat = $this->apl->get_nilai("SELECT 
        CONCAT(alamat_surat) as alamat 
        FROM bast WHERE id_bast='$id_bast' ", "alamat");

		/**
		 * Insert data billing
		 */
		$data_billing = array(
			'id_billing' => $id_billing,
			'id_bast' => $id_bast,
			'invoice' => $this->apl->counter("INV-EPR") . "/" . "INV-EPR" . "/" . $this->apl->bulan_romawi() . "/"
				. date('Y', strtotime($this->input->post('tanggal_cetak'))),
			'tanggal_terbit' => $this->apl->tgl_format($this->input->post('tanggal_cetak')),
			'tanggal_jt' => date('Y-m-d', strtotime('+' . $this->apl->tanggal_jt() . ' days',
				strtotime($this->input->post('tanggal_cetak')))),
			'id_admin' => $this->session->userdata('id_admin'),
			'hapus' => 0,
			'nama' => $nama,
			'alamat' => $alamat,
			'tlp' => $tlp,
			'tujuan' => 1,
			'id_pemilik' => $id_pemilik,
		);
		$this->apl->insertData("billing", $data_billing);
		$this->apl->log("Tambah Data Invoice", "",
			json_encode($data_billing), "billing", $id_billing);
		$this->apl->log("Tambah Data Invoice", "",
			json_encode($data_billing), "db_unit", $id_unit);

		/**
		 * Insert tabel ar_billing_detail Periode
		 */

		$tanggal_tagihan = isset($_POST['tanggal_tagihan']) ? $_POST['tanggal_tagihan'] : '';
		if ($tanggal_tagihan != '') {
			$list = $this->billing->getDataMasterTagihanUnitPeriode($id_bast, $this->apl->tagihan(array('tagihan' => 1, 'unit' => 1)));
			for ($no = 1; $no <= $list->num_rows(); $no++) {
				$tanggal_awal_array = array();
				$tanggal_ahir_array = array();
				$periode_array = array();
				$tagihan_id = $this->input->post('tagihan_id');
				$nama_tagihan = $this->input->post('nama_tagihan');
				$total = $this->input->post('total');
				$ket = $this->input->post('ket');
				for ($i = 0; $i < count($tanggal_tagihan); $i++) {
					//$tag = $tanggal_tagihan[$i];
					$tag = $i;
					$periode = $this->apl->periode_tagihan(date('m',
						strtotime('+1 month',
							strtotime($tanggal_tagihan[$i]))
					));
					$tanggal_awal = date('Y-m-d', strtotime($tanggal_tagihan[0]));
					$tanggal_ahir = date('Y-m-01',
						strtotime('+' . count($tanggal_tagihan) . ' month',
							strtotime($tanggal_awal)));
					$tanggal_ahir = date('Y-m-d',
						strtotime('-1 days',
							strtotime($tanggal_ahir)));
					$periode_array[] = $periode;
					$tanggal_awal_array[] = $tanggal_awal;
					$tanggal_ahir_array[] = $tanggal_ahir;
					$data_billing_detail = array(
						'id_detail' => $this->apl->urut("billing_detail", "id_detail"),
						'id_tag' => $tagihan_id[$tag][$no],
						'id_billing' => $id_billing,
						'ket' => $nama_tagihan[$tag][$no],
						'note' => $ket[$i][$no],
						'tanggal' => $this->apl->tgl_format($tanggal_tagihan[$i], 0),
						'hapus' => 0,
						'id_admin' => $this->session->userdata('id_admin'),
						'jumlah' => $this->apl->number_format($total[$tag][$no]),
						'periode' => $periode,
						'id_bast' => $id_bast,
						'status' => 1,
					);
					$this->apl->insertData("billing_detail", $data_billing_detail);

					/**
					 * Update Periode Terakhir ar_tagihan
					 */
					$this->apl->updateData("tagihan",
						array(
							'tanggal_awal' => $tanggal_awal,
							'tanggal_ahir' => $tanggal_ahir,
							'periode' => $periode,
						),
						array(
							'id_bast' => $id_bast,
							'id_tag' => $tagihan_id[$tag][$no],
						));

					$this->apl->updateData("billing", array('periode' => $periode), array('id_billing' => $id_billing));
				}
			}
		}

		$tanggal_tagihan = isset($_POST['tanggal_tagihan_adm']) ? $_POST['tanggal_tagihan_adm'] : ''; //TAGIHAN ADMINISTARSSI
		if ($tanggal_tagihan != '') {
			$list = $this->billing->getDataMasterTagihanUnitPeriode($id_bast, $this->apl->tagihan(array('tagihan' => 3, 'unit' => 1)));

			$tanggal_awal_array = array();
			$tanggal_ahir_array = array();
			$periode_array = array();
			for ($no = 1; $no <= $list->num_rows(); $no++) {
				$tagihan_id = $this->input->post('tagihan_id_adm');
				$nama_tagihan = $this->input->post('nama_tagihan_adm');
				$total = $this->input->post('total_adm');
				$ket = $this->input->post('ket_adm');

				for ($i = 0; $i < count($tanggal_tagihan); $i++) {
					//$tag = $tanggal_tagihan[$i];
					$tag = $i;
					$periode = $this->apl->periode_tagihan(date('m',
						strtotime('+1 month',
							strtotime($tanggal_tagihan[$i]))
					));
					$tanggal_awal = date('Y-m-d', strtotime($tanggal_tagihan[0]));
					$tanggal_ahir = date('Y-m-01',
						strtotime('+' . count($tanggal_tagihan) . ' month',
							strtotime($tanggal_awal)));
					$tanggal_ahir = date('Y-m-d',
						strtotime('-1 days',
							strtotime($tanggal_ahir)));
					$periode_array[] = $periode;
					$tanggal_awal_array[] = $tanggal_awal;
					$tanggal_ahir_array[] = $tanggal_ahir;
					$data_billing_detail = array(
						'id_detail' => $this->apl->urut("billing_detail", "id_detail"),
						'id_tag' => $tagihan_id[$tag][$no],
						'id_billing' => $id_billing,
						'ket' => $nama_tagihan[$tag][$no],
						'note' => $ket[$i][$no],
						'tanggal' => $this->apl->tgl_format($tanggal_tagihan[$i], 0),
						'hapus' => 0,
						'id_admin' => $this->session->userdata('id_admin'),
						'jumlah' => $this->apl->number_format($total[$tag][$no]),
						'periode' => $periode,
						'id_bast' => $id_bast,
						'status' => 1,
					);
					$this->apl->insertData("billing_detail", $data_billing_detail);

					$this->apl->updateData("tagihan",
						array(
							'tanggal_awal' => $tanggal_awal,
							'tanggal_ahir' => $tanggal_ahir,
							'periode' => $periode,
						),
						array(
							'id_bast' => $id_bast,
							'id_tag' => $tagihan_id[$tag][$no],
						));
				}
			}

		}
		$utility = isset($_POST['utility']) ? $_POST['utility'] : ''; //TAGIHAN UTILITY
		if ($utility != '') {
			$total_utility = isset($_POST['total_utility']) ? $_POST['total_utility'] : '';
			for ($i = 0; $i < count($utility); $i++) {

				$ut = $this->db->select(' `utility_rekening`.`id_tag`,`db_tag`.`nama`,`utility`.`tanggal`')->from('utility')
					->join('`utility_rekening`', '`utility`.`id_rekening` = `utility_rekening`.`id_rekening`')
					->join('`db_tag`', '`db_tag`.`id_tag` = `utility_rekening`.`id_tag`')
					->where('utility.id_meter', $utility[$i])->get()->row();

				$tag_id[$i] = (isset($ut)) ? $ut->id_tag : '';
				$tag_nama[$i] = (isset($ut)) ? $ut->nama : '';
				$tanggal[$i] = (isset($ut)) ? $ut->tanggal : '';


				$id_detail = $this->apl->urut("billing_detail", "id_detail");
				$data_billing_detail_utility = array(
					'id_detail' => $id_detail,
					'id_tag' => $tag_id[$i],
					'id_billing' => $id_billing,
					'ket' => $tag_nama[$i],
					'tanggal' => $tanggal[$i],
					'hapus' => 0,
					'id_admin' => $this->session->userdata('id_admin'),
					'jumlah' => $this->apl->number_format($total_utility[$utility[$i]]),
					'id_bast' => $id_bast,
					'status' => 1,
				);
				$this->apl->insertData("billing_detail", $data_billing_detail_utility);
				$this->apl->updateData("utility", array(
					'post' => 1,
					'user_post' => $this->session->userdata('id_admin'),
					'id_billing' => $id_billing,
					'id_billing_detail' => $id_detail,
				), array(
						'id_meter' => $utility[$i],)
				);
			}
		}
		//TAGIHAN LAINNYA
		$tagihan_id = $this->input->post('tagihan_id_invoice');
		$nama_tagihan = $this->input->post('nama_tagihan_invoice');
		$total = $this->input->post('total_invoice');
		$ket = '';
		for ($i = 0; $i < count($tagihan_id); $i++) {
			$tag = $tagihan_id[$i];
			$data_billing_detail = array(
				'id_detail' => $this->apl->urut("billing_detail", "id_detail"),
				'id_tag' => $tagihan_id[$i],
				'id_billing' => $id_billing,
				'ket' => $nama_tagihan[$tag],
				'note' => $ket,
				'tanggal' => $this->apl->tgl_format($this->input->post('tanggal_cetak'), 0),
				'hapus' => 0,
				'id_admin' => $this->session->userdata('id_admin'),
				'jumlah' => $this->apl->number_format($total[$tag]),
				'periode' => $periode,
				'id_bast' => $id_bast,
				'status' => 1,
			);
			$this->apl->insertData("billing_detail", $data_billing_detail);
		}

		/**
		 * Denda dari piutang
		 */


		$denda = $this->apl->number_format($this->input->post('denda'));
		if ($denda > 0) {
			$id_detail = $this->apl->urut("billing_detail", "id_detail");
			$data_billing_detail_utility = array(
				'id_detail' => $id_detail,
				'id_tag' => '11',
				'id_billing' => $id_billing,
				'ket' => 'Denda Keterlambatan',
				'tanggal' => $this->apl->tgl_format($this->input->post('tanggal_cetak'), 0),
				'hapus' => 0,
				'id_admin' => $this->session->userdata('id_admin'),
				'jumlah' => $denda,
				'id_bast' => $id_bast,
				'status' => 1,
			);
			$this->apl->insertData("billing_detail", $data_billing_detail_utility);
		}
		$this->input_tagihan_piutang($id_billing, $id_bast);
		$this->pesan->pesan_success("Successfully added billing data");
		echo json_encode(array("status" => TRUE));
	}

	function input_tagihan_piutang2($id_billing, $id_bast)
	{
		$hutang = $this->db->select("billing_detail.id_tag")
			->select("billing_detail.ket")
			->select("(
				SUM(IF(status=1,billing_detail.jumlah,0))
			-( SUM(IF(status=2,billing_detail.jumlah,0)) + SUM(IF(status=3,billing_detail.jumlah,0)) )
			) as hutang")
			->select("MIN(billing_detail.tanggal) as tanggal_awal")
			->select("MAX(billing_detail.tanggal) as tanggal_ahir")
			->from('billing')
			->join('billing_detail', 'billing_detail.id_billing=billing.id_billing')
			->where(
				array(
					'billing_detail.id_billing <' => $id_billing,
					'billing_detail.hapus' => 0,
					'billing_detail.id_bast' => $id_bast,
				))
			->where(
				array(
					'billing.id_billing <' => $id_billing,
					'billing.hapus' => 0,
					'billing.id_bast' => $id_bast,
				))
			->group_by('billing_detail.id_tag')
			->get();

		if ($hutang->num_rows() != 0) {
			$jml_hutang = 0;
			foreach ($hutang->result() as $tag) {
				$jml_hutang += $tag->hutang;
				$this->apl->insertData("billing_detail",

					$data_billing = array(

						'id_detail' => $this->apl->urut("billing_detail", "id_detail"),
						'id_tag' => $tag->id_tag,
						'id_billing' => $id_billing,
						'ket' => $tag->ket,
						'note' => $tag->tanggal_awal . " s.d " . $tag->tanggal_ahir,
						'tanggal' => $tag->tanggal_ahir,
						'hapus' => 0,
						'id_admin' => $this->session->userdata('id_admin'),
						'jumlah' => $tag->hutang,
						'id_bast' => $id_bast,
						'status' => 4,
					));
			}
		}

	}

	function input_tagihan_piutang($id_billing, $id_bast)
	{
		$hutang = $this->db
			//->select("billing_detail.id_tag")
			//->select("billing_detail.ket")
			->select("(
				SUM(IF(status=1,billing_detail.jumlah,0))
			-( SUM(IF(status=2,billing_detail.jumlah,0)) + SUM(IF(status=3,billing_detail.jumlah,0)) )
			) as hutang")
			->select("MIN(billing_detail.tanggal) as tanggal_awal")
			->select("MAX(billing_detail.tanggal) as tanggal_ahir")
			->from('billing')
			->join('billing_detail', 'billing_detail.id_billing=billing.id_billing')
			->where(
				array(
					'billing_detail.id_billing <' => $id_billing,
					'billing_detail.hapus' => 0,
					'billing_detail.id_bast' => $id_bast,
				))
			->where(
				array(
					'billing.id_billing <' => $id_billing,
					'billing.hapus' => 0,
					'billing.id_bast' => $id_bast,
				))
			//->group_by('billing_detail.id_tag')
			->get()->row();

		/*
		if ($hutang->num_rows() != 0) {
			$jml_hutang = 0;
			foreach ($hutang->result() as $tag) {
				$jml_hutang += $tag->hutang;
				$this->apl->insertData("billing_detail",
					$data_billing = array(
						'id_detail' => $this->apl->urut("billing_detail", "id_detail"),
						'id_tag' => $tag->id_tag,
						'id_billing' => $id_billing,
						'ket' => $tag->ket,
						'note' => $tag->tanggal_awal . " s.d " . $tag->tanggal_ahir,
						'tanggal' => $tag->tanggal_ahir,
						'hapus' => 0,
						'id_admin' => $this->session->userdata('id_admin'),
						'jumlah' => $tag->hutang,
						'id_bast' => $id_bast,
						'status' => 4,
					));
			}
		}
		*/

		if (isset($hutang)) {
			$this->apl->insertData("billing_detail",
				$data_billing = array(
					'id_detail' => $this->apl->urut("billing_detail", "id_detail"),
					'id_tag' => "30",
					'id_billing' => $id_billing,
					'ket' => "Piutang",
					'note' => $hutang->tanggal_awal . " s.d " . $hutang->tanggal_ahir,
					'tanggal' => $hutang->tanggal_ahir,
					'hapus' => 0,
					'id_admin' => $this->session->userdata('id_admin'),
					'jumlah' => $hutang->hutang,
					'id_bast' => $id_bast,
					'status' => 4,
				));

		}
	}


	public function detail_bast($id)
	{

		$id_bast = $id;

		$data['judul'] = "Detail Informasi Unit";
		$data['tabs'] = "detail";
		$data['page'] = 'unit/bast/detail'; //Halaman di tampilkan

		$data['b'] = $this->apl->getSelectedData("bast", array('id_bast' => $id_bast))->row();
		$data['u'] = $this->apl->getSelectedData("db_unit", array('id_unit' => $data['b']->id_unit))->row();
		$data['p'] = $this->apl->getSelectedData("pemilik", array('id_pemilik' => $data['b']->id_pemilik))->row();

		$data['tagihan'] = $this->db->select('(SUM(IF(status=1,jumlah,0)) - SUM(IF(status=3,jumlah,0)))
			-SUM(IF(status=2,jumlah,0)) as `piutang`')
			->from('billing_detail')
			->where(
				array(
					'id_bast' => $id_bast,
					'hapus' => 0
				)
			)
			->get()->row()->piutang;

		$data['air'] = $this->db->select('MAX(utility.meter) as `meter`')
			->join('(SELECT * FROM db_tag where tagihan=2 AND unit=1) db_tag', '`utility_rekening`.`id_tag` = `db_tag`.`id_tag`')
			->join('db_unit', '`utility_rekening`.`id_unit` = `db_unit`.`id_unit`')
			->join('utility', '`utility`.`id_rekening` = `utility_rekening`.`id_rekening`')
			->where(array(
				'utility_rekening.hapus' => 0,
				'utility_rekening.id_tag' => 4,
				'utility_rekening.id_unit' => $data['u']->id_unit,
				'utility.hapus' => 0,

				'db_unit.hapus' => 0,
				'db_unit.id_group' => '1',
			))->from('utility_rekening')->get()->row()->meter;

		$this->load->view('billing/tagihan/detail_bast', $data);
	}
}

?>
