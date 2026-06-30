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

class Invoice extends CI_Controller
{
	var $modul = "billing/invoice";

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Billing_Model', 'billing');
		$this->load->model('Crud_Model', 'crud_model');
		$this->billing = new Billing_Model();
		$this->crud_model = new Crud_Model();
		$this->apl = new Apl();
		$this->tombol = new Tombol();
		$this->pesan = new Pesan();
	}

	public function index()
	{
		$data['id_unit'] = (isset($_POST['id_unit'])) ? $_POST['id_unit'] : "";
		$data['kirim'] = (isset($_POST['kirim'])) ? $_POST['kirim'] : "";
		$data['tanggal_awal'] = isset($_POST['tanggal_awal']) ? $_POST['tanggal_awal']
			: date('Y-m-01');
		$data['tanggal_ahir'] = isset($_POST['tanggal_ahir']) ? $_POST['tanggal_ahir']
			: date('Y-m-d');
		$data['tombol_view'] = $this->apl->anchor('<a href="' . site_url('billing/invoice/add') . '" 
class="btn btn-neutral">
<i class="fa fa-plus"></i> Added</a>', 'tambah ' . $this->modul, $this->modul);

		$data['tombol_view'] .= $this->apl->anchor('<a href="' . site_url('billing/invoice/add_generate') . '" 
class="btn btn-neutral">
<i class="fa fa-plus"></i> Generate</a>', 'tambah ' . $this->modul, $this->modul);

		$data['tombol_view'] .= '<div class="dropdown">
  <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
   <i class="fa fa-file-excel"></i> Export
  </button>
  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">';
		$data['tombol_view'] .= '<a href="' . site_url('billing/invoice/export_csv?id_unit'
				. $data['id_unit']
				. '&kirim=' . $data['kirim']
				. '&tanggal_awal='
				. $data['tanggal_awal']
				. '&tanggal_ahir=' . $data['tanggal_ahir']) . '" 
class="dropdown-item" target="_blank">
<i class="fa fa-file-excel"></i> Export</a>';

		$data['tombol_view'] .= '<a href="' . site_url('billing/invoice/export_jurnal_csv?id_unit'
				. $data['id_unit']
				. '&kirim=' . $data['kirim']
				. '&tanggal_awal='
				. $data['tanggal_awal']
				. '&tanggal_ahir=' . $data['tanggal_ahir']) . '" 
class="dropdown-item" target="_blank">
<i class="fa fa-file-excel"></i> Export Jurnal</a>';

		$data['tombol_view'] .= '<a href="' . site_url('billing/invoice/export_tokopedia_csv?id_unit'
				. $data['id_unit']
				. '&kirim=' . $data['kirim']
				. '&tanggal_awal='
				. $data['tanggal_awal']
				. '&tanggal_ahir=' . $data['tanggal_ahir']) . '" 
class="dropdown-item" target="_blank">
<i class="fa fa-file-excel"></i> Export Tokopedia</a>';
		$data['tombol_view'] .= '</div>
</div>';


		$data['field'] = $this->billing->getDataBillingInvoice($data['id_unit']
			, $data['tanggal_awal'], $data['tanggal_ahir'], $data['kirim'])->get()->list_fields();

		$data['t'] = $this->billing->getDataBillingInvoiceTotal($data['id_unit']
			, $data['tanggal_awal'], $data['tanggal_ahir'], $data['kirim'])->get()->row();
		$data['page'] = 'billing/invoice/view'; //Halaman di tampilkan
		$data['judul'] = 'Publish Invoice';
		$this->load->view('home', $data);
	}


	public function export_csv()
	{

		$id_unit = (isset($_GET['id_unit'])) ? $_GET['id_unit'] : '';
		$kirim = (isset($_GET['kirim'])) ? $_GET['kirim'] : '';
		$tanggal_awal = (isset($_GET['tanggal_awal'])) ? $_GET['tanggal_awal'] : '';
		$tanggal_ahir = (isset($_GET['tanggal_ahir'])) ? $_GET['tanggal_ahir'] : '';
		$result = $this->billing->getDataBillingInvoiceExport($id_unit, $tanggal_awal, $tanggal_ahir, $kirim)->get();
		$this->apl->export_excell($result, " Invoice " . $_GET['tanggal_awal'] . ' s.d ' . $_GET['tanggal_ahir']);
	}

	public function export_jurnal_csv()
	{

		$id_unit = (isset($_GET['id_unit'])) ? $_GET['id_unit'] : '';
		$kirim = (isset($_GET['kirim'])) ? $_GET['kirim'] : '';
		$tanggal_awal = (isset($_GET['tanggal_awal'])) ? $_GET['tanggal_awal'] : '';
		$tanggal_ahir = (isset($_GET['tanggal_ahir'])) ? $_GET['tanggal_ahir'] : '';
		$result = $this->billing->getDataBillingInvoiceExportJurnal($id_unit, $tanggal_awal, $tanggal_ahir, $kirim)->get();
		$this->apl->export_excell($result, " Invoice " . $_GET['tanggal_awal'] . ' s.d ' . $_GET['tanggal_ahir']);
	}

	public function export_tokopedia_csv()
	{

		$id_unit = (isset($_GET['id_unit'])) ? $_GET['id_unit'] : '';
		$kirim = (isset($_GET['kirim'])) ? $_GET['kirim'] : '';
		$tanggal_awal = (isset($_GET['tanggal_awal'])) ? $_GET['tanggal_awal'] : '';
		$tanggal_ahir = (isset($_GET['tanggal_ahir'])) ? $_GET['tanggal_ahir'] : '';
		$query = $this->billing->getDataBillingInvoiceExportTokopedia($id_unit, $tanggal_awal, $tanggal_ahir, $kirim)->get();
		//$this->apl->export_excell($result, " Invoice " . $_GET['tanggal_awal'] . ' s.d ' . $_GET['tanggal_ahir']);

		if (!$query)
			return false;
		$this->load->library('PHPExcel');
		$this->load->library('PHPExcel/IOFactory');
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


		$filename = "UPLOAD TOKOPEDIA" . time() . ".xls";
		$objPHPExcel->setActiveSheetIndex(0);
		$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('export/' . $filename);
		$this->load->helper('download');
		$file_path = 'export/' . $filename;
		$file_content = file_get_contents($file_path); // Read the file's contents
		if (file_exists($file_path)) {
			// unlink($file_path);
		}
		force_download($filename, $file_content);

	}

	public function ajax_list()
	{
		$id_unit = (isset($_POST['id_unit'])) ? $_POST['id_unit'] : '';
		$kirim = (isset($_POST['kirim'])) ? $_POST['kirim'] : '';
		$tanggal_awal = $this->input->post('tanggal_awal');
		$tanggal_ahir = $this->input->post('tanggal_ahir');

		$column_search = array(
			'`db_unit`.`kode`',
			'`billing`.`invoice`',
			'`billing`.`tanggal_terbit`',
		);
		$column_order = array(
			null,
			'`db_unit`.`kode`',
			//'`billing`.`invoice`',
			'`billing`.`tanggal_terbit`',
			'PreviousBill',
			'Fine',
			'CurrentBill',
			'Bill',
			//'print',
			'kirim',
			null,
		);
		$list = $this->crud_model->get_data(
			$this->billing->getDataBillingInvoice($id_unit, $tanggal_awal, $tanggal_ahir, $kirim), $column_search, $column_order);
		$record_total = $this->crud_model->get_jumlah(
			$this->billing->getDataBillingInvoice($id_unit, $tanggal_awal, $tanggal_ahir, $kirim));
		$record_filter = $this->crud_model->get_jumlah_filter(
			$this->billing->getDataBillingInvoice($id_unit, $tanggal_awal, $tanggal_ahir, $kirim), $column_search, $column_order);


		$no = isset($_POST['start']) ? $_POST['start'] : '0';
		$jumlah = $list->num_fields();
		$data_array = array();

		$total_tagihan = 0;
		$total_denda = 0;
		$total_sekarang = 0;
		$total_piutang = 0;

		foreach ($list->result_array() as $data) {
			$no++;
			$r = array_values($data);
			$r[0] = $no;

			$total_piutang += $r[3];
			$total_denda += $r[4];
			$total_sekarang += $r[5];
			$total_tagihan += $r[6];

			//$r[3] = $this->apl->tgl_format($r[3], 1);
			//	$r[4] = $this->apl->tgl_format($r[4], 1);
			$r[3] = '<span class="float-right">' . $this->apl->number_format($r[3], 1) . '</span>';
			$r[4] = '<span class="float-right">' . $this->apl->number_format($r[4], 1) . '</span>';
			$r[5] = '<span class="float-right">' . $this->apl->number_format($r[5], 1) . '</span>';
			$r[6] = '<b class="float-right">' . $this->apl->number_format($r[6], 1) . '</b>';

			$mailto = ($r[$jumlah - 2] == 2) ? anchor('#modal_form',
				'<i class="fa fa-mail-forward"></i> Mail To',
				'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal" 
							class="mail_to dropdown-item"') : '';

			/*
			$r[$jumlah - 2] = ($r[$jumlah - 2] == 1) ? '<span class="btn btn-sm btn-info btn-block">COURIER</span>'
				: '<span class="btn btn-sm btn-success btn-block">EMAIL</span>';
			*/
			$aksi = '<div class="dropdown">
<button class="btn btn-wrapper" data-toggle="dropdown">
 <i class="fa fa-ellipsis-v"></i>
</button>
<div class="dropdown-menu dropdown-menu-right">'
				. $this->apl->anchor(anchor('#modal_form',
					'<i class="fa fa-edit"></i> Edit',
					'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
							class="edit_invoice_list dropdown-item"'), 'edit ' . $this->modul, $this->modul)
				. anchor('#modal_form',
					'<i class="fa fa-print"></i> Print',
					'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal" 
							class="print_data dropdown-item"')
				//. $mailto
				. $this->apl->anchor(anchor('#modal_form',
						'<i class="fa fa-trash"></i> Hapus',
						'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal" 
                                    class="hapus_inv dropdown-item"')
					, 'tombol hapus invoice', 'ar/billing');

			$aksi .= anchor('#modal_form',
				'<i class="fa fa-whatsapp"></i> Whatsapp',
				'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal" 
							class="whatsapp dropdown-item"');

			$aksi .= anchor('#modal_form',
				'<i class="fa fa-mail-bulk"></i> Email',
				'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal" 
							class="email dropdown-item"');

			$aksi .= '</div></div>';
			$r[$jumlah - 1] = $aksi;
			/*
			if ($r[9] == 0) {
				$r[9] = 'OPEN';
				if ($r[9] == 'OPEN') {
					$r[$jumlah - 1] = '<div class="btn-group btn-group-sm pull-right">'
						. anchor('#modal_form',
							'<i class="fa fa-edit"></i> Edit',
							'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
							class="edit_invoice_list btn btn-warning"')
						. anchor('#modal_form',
							'<i class="fa fa-print"></i> Print',
							'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
							class="print_data btn btn-success"')
						. $this->apl->anchor(anchor('#modal_form',
								'<i class="fa fa-trash"></i> Hapus',
								'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                                    class="hapus_inv btn btn-danger"')
							, 'tombol hapus invoice', 'ar/billing')
						. '</div>';

				} else {
					$r[$jumlah - 1] = '<div class="btn-group btn-sm pull-right">'
						. anchor('#modal_form',
							'<i class="fa fa-print"></i> Print',
							'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal" class="print_data btn btn-success"')
						. '</div>';
				}
			} else {
				$r[9] = 'TERPOSTING';
				$r[$jumlah - 1] = '<div class="btn-group btn-group-xs pull-right">'
					. anchor('#modal_form',
						'<i class="fa fa-print"></i> Print',
						'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal" class="print_data btn btn-success"')
					. '</div>';
			}
			*/


			//$r[7] = $this->apl->number_format($r[7], 1);
			$data_array[] = $r;

		}
		$output = array(
			"draw" => isset($_POST['draw']) ? $_POST['draw'] : '',
			"recordsTotal" => $record_total,
			"recordsFiltered" => $record_filter,
			"total_piutang" => $total_piutang,
			"total_tagihan" => $total_tagihan,
			"total_sekarang" => $total_sekarang,
			"total_denda" => $total_denda,

			"data" => $data_array,
		);
		header('Content-Type: application/json; charset=utf-8');
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET,POST');
		echo json_encode($output);//output to json format

	}

	public function form_edit_invoice_list($id)
	{
		$data['billing'] = $this->apl->getSelectedData("billing", array('id_billing' => $id))->row();
		$data['id_bast'] = $data['billing']->id_bast;
		$data['unit'] = $this->billing->getDataUnitBast($data['id_bast'])->row();

		/**
		 * Tagihan Awal PEriode Tanpa ADM
		 */

		$tanggal_p = $this->db->select('min(`billing_detail`.`tanggal`) as tanggal_awal')
			->select('max(`billing_detail`.`tanggal`) as tanggal_ahir')
			->from('billing_detail')
			->where(array(
				'id_billing' => $id,
				'status' => 1,
				'hapus' => 0,
			))->where('id_tag IN (' . $this->apl->tagihan(array('tagihan' => 1)) . ')')->get()->row();
		$data['tanggal_tagihan_awal'] = isset($tanggal_p->tanggal_awal) ? $tanggal_p->tanggal_awal
			: $this->apl->get_nilai_pilih("tagihan", "tanggal_ahir", array('id_bast' => $data['id_bast'], 'id_tag' => '1'));
		$data['tanggal_tagihan_ahir'] = isset($tanggal_p->tanggal_ahir) ? $tanggal_p->tanggal_ahir : date('Y-m-d');


		$data['no_invoice_baru'] = $this->apl->counter_view("INV-EPR") . "/" . "INV-EPR" . "/"
			. $this->apl->bulan_romawi() . "/" . date('Y', strtotime($data['billing']->tanggal_terbit));

		$data['list'] = $this->billing->getDataMasterTagihanUnitPeriode($data['id_bast'], $this->apl->tagihan("tagihan IN (1,3)"));
		$data['list_adm_invoice'] = $this->billing->getDataMasterTagihanLainnya($this->apl->tagihan(array('tagihan' => 5)));

		//print_r($data['list']->result());
		$data['list_utility'] = $this->billing->getDataMasterTagihanUnitUtilityInv($data['billing']->id_bast
			, $data['billing']->id_billing, $this->apl->tagihan(array('tagihan' => 2)));
		$data['jumlah_utility'] = $data['list_utility']->num_rows();


		$p = $this->billing->getDataPiutangTanggal($data['billing']->id_bast, $id);
		//$data['piutang'] = $this->billing->getDataPiutangJumlah($id);
		//$data['piutang'] = (isset($p)) ? $p->piutang : '0';

		$data['piutang'] = $this->apl->get_nilai_pilih("billing_detail", "SUM(jumlah)"
			, array('id_billing' => $id, 'id_tag' => '30', 'hapus' => 0));

		$data['p_awal'] = (isset($p)) ? $p->tanggal_awal : date('Y-m-d');
		$data['p_ahir'] = (isset($p)) ? $p->tanggal_ahir : date('Y-m-d');


		$data['denda'] = ($data['piutang'] == '0') ? '0' : (3 / 100) * $data['piutang'];


		$this->load->view('billing/invoice/form_edit_invoice_list', $data);
	}

	function ajax_edit_invoice_list()
	{
		$id_bast = isset($_POST['id_bast']) ? $_POST['id_bast'] : '';
		$id_billing = $this->input->post('id_billing');
		$tanggal_terbit = $this->apl->tgl_format($this->input->post('tanggal_terbit'), 0);
		$periode = "";
		$id_pemilik = $this->apl->get_nilai_pilih("bast", "id_pemilik", array('id_bast' => $id_bast));
		$nama = $this->apl->get_nilai_pilih("pemilik", "nama", array('id_pemilik' => $id_pemilik));
		$tlp = $this->apl->get_nilai_pilih("pemilik", "hp", array('id_pemilik' => $id_pemilik));
		$alamat = $this->apl->get_nilai("SELECT 
        CONCAT(alamat_surat) as alamat 
        FROM bast WHERE id_bast='$id_bast' ", "alamat");

		$data_update = array(
			'tanggal_terbit' => $this->apl->tgl_format($this->input->post('tanggal_terbit')),
			'tanggal_jt' => date('Y-m-d', strtotime('+' . $this->apl->tanggal_jt() . ' days',
				strtotime($this->input->post('tanggal_terbit')))),
			'id_admin' => $this->session->userdata('id_admin'),
			'hapus' => 0,
			//'nama' => $nama,
			//'alamat' => $alamat,
			//'tlp' => $tlp,
			//'tujuan' => 1,
			//'id_pemilik' => $id_pemilik,
		);
		$this->apl->updateData("billing", $data_update, array('id_billing' => $id_billing));

		/**
		 * Insert tabel ar_billing_detail Periode
		 */
		if (isset($_POST['update_tagihan_periode'])) {
			$tanggal_tagihan = isset($_POST['tanggal_tagihan']) ? $_POST['tanggal_tagihan'] : array();
			$this->apl->manualQuery("UPDATE billing_detail set hapus=1 
                WHERE id_billing='$id_billing' AND hapus=0
                AND id_tag IN (" . $this->apl->tagihan("tagihan IN (1,3)") . ")");
			$list = $this->billing->getDataMasterTagihanUnitPeriode($id_bast,
				$this->apl->tagihan("tagihan IN (1,3)"));
			for ($no = 1; $no <= $list->num_rows(); $no++) {
				$tanggal_awal_array = array();
				$tanggal_ahir_array = array();
				$periode_array = array();
				$tagihan_id = $this->input->post('tagihan_id');
				$nama_tagihan = $this->input->post('nama_tagihan');
				$total = $this->input->post('total');
				$ket = $this->input->post('ket');
				for ($i = 0; $i < count($tanggal_tagihan); $i++) {
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

						'bulan' => date('m', strtotime($tanggal_tagihan[$i])),
						'tahun' => date('Y', strtotime($tanggal_tagihan[$i])),


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
		/**
		 * Insert tabel ar_billing_detail Utility
		 */
		if (isset($_POST['update_tagihan_utility'])) {
			$this->apl->updateData('utility', array('post' => 0), array('id_billing' => $id_billing));
			$this->apl->manualQuery("UPDATE billing_detail set hapus=1 
                WHERE id_billing='$id_billing' AND hapus=0
                AND id_tag IN (" . $this->apl->tagihan(array('tagihan' => 2)) . ")");
			$utility = isset($_POST['utility']) ? $_POST['utility'] : array(); //TAGIHAN UTILITY
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

					'bulan' => date('m', strtotime($tanggal[$i])),
					'tahun' => date('Y', strtotime($tanggal[$i])),

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
		$data_billing = $this->apl->getSelectedData("billing", array('id_billing' => $id_billing))->row();

		/**
		 * TAGIHAN LAINNYA
		 *
		 */
		$this->apl->manualQuery("UPDATE billing_detail set hapus=1 
                WHERE id_billing='$id_billing' AND hapus=0
                AND id_tag IN (" . $this->apl->tagihan(array('tagihan' => 5)) . ")");
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
				'tanggal' => $this->apl->tgl_format($this->input->post('tanggal_terbit'), 0),
				'hapus' => 0,
				'id_admin' => $this->session->userdata('id_admin'),
				'jumlah' => $this->apl->number_format($total[$tag]),
				'periode' => $periode,
				'id_bast' => $id_bast,
				'status' => 1,

				'bulan' => date('m', strtotime($this->input->post('tanggal_terbit'))),
				'tahun' => date('Y', strtotime($this->input->post('tanggal_terbit'))),

			);
			$this->apl->insertData("billing_detail", $data_billing_detail);
		}
		if ($this->input->post('check_no_invoice')) {
			$edit_data_billing = array(
				'invoice' => $this->apl->counter("INV-EPR") . "/" . "INV-EPR" . "/" . $this->apl->bulan_romawi() . "/"
					. date('Y', strtotime($tanggal_terbit)),
				'no_ref_inv' => $data_billing->invoice,
			);
			$this->apl->log("Update Nomor Invoice",
				json_encode($this->apl->getSelectedData("billing", array('id_billing' => $id_billing))->row()),
				json_encode($edit_data_billing), "billing", $id_billing);
			$this->apl->updateData("billing", $edit_data_billing, array('id_billing' => $id_billing));
		}
		$this->apl->updateData("billing_detail", array('hapus' => 1), array('id_billing' => $id_billing, 'status' => 4));


		/**
		 * Denda dari piutang
		 */
		$this->apl->manualQuery("UPDATE billing_detail set hapus=1 
                WHERE id_billing='$id_billing' AND hapus=0
                AND id_tag ='11'");

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


		/**
		 * Piutang
		 */

		//$this->input_tagihan_piutang($id_billing, $id_bast);
		$piutang = $this->apl->number_format($this->input->post('piutang'));
		//if ($piutang > 0) {
		$ket_piutang = $this->input->post('ket_piutang');
		$tahun_dari = $this->input->post('tahun_dari');
		$bulan_dari = $this->input->post('bulan_dari');
		$tahun_ke = $this->input->post('tahun_ke');
		$bulan_ke = $this->input->post('bulan_ke');


		if ($ket_piutang == 'MANUAL') {
			$ket = $this->input->post('ket_piutang_note');
		} else {
			$ket = $ket_piutang . " " . $this->apl->bulan($bulan_dari, 2)
				. ' ' . $tahun_dari . " s.d " . $this->apl->bulan($bulan_ke, 2) . ' ' . $tahun_ke;
		}

		$this->apl->updateData("billing_detail",
			array('hapus' => 1), array('id_tag' => 30, 'id_billing' => $id_billing));

		$this->apl->insertData("billing_detail",
			$data_billing = array(
				'id_detail' => $this->apl->urut("billing_detail", "id_detail"),
				'id_tag' => "30",
				'id_billing' => $id_billing,
				'ket' => 'PIUTANG',
				//'note' => $bulan_dari . '-' . $tahun_dari . " s.d " . $bulan_ke . '-' . $tahun_ke,
				'note' => $ket,

				'tanggal' => $this->apl->tgl_format($this->input->post('tanggal_terbit')),
				'hapus' => 0,
				'id_admin' => $this->session->userdata('id_admin'),
				'jumlah' => $piutang,
				'id_bast' => $id_bast,
				'status' => 4,
				'bulan' => $bulan_dari,
				'tahun' => $tahun_dari,

				'bulan2' => $bulan_ke,
				'tahun2' => $tahun_ke,
			));
		//}

		$this->pesan->pesan_success("Update Data Invoice");
		echo json_encode(array("status" => TRUE));
	}

	public function print_priview_invoice($id)
	{
		$data['billing'] = $this->apl->getSelectedData("billing", array('id_billing' => $id))->row();
		$data['bast'] = $this->apl->getSelectedData("bast", array('id_bast' => $data['billing']->id_bast))->row();
		$data['p'] = $this->apl->getSelectedData("pemilik", array('id_pemilik' => $data['bast']->id_pemilik))->row();

		$data['unit'] = $this->billing->getDataUnitBast($data['billing']->id_bast)->row();
		$data['billing_detail'] = $this->billing->getDataBillingTagihanDetail($id, "1")->result();
		foreach ($data['billing_detail'] as $tagihan) {
			$tagihan_id[] = $tagihan->id_tag;
		}

		//$ipl = $this->apl->tagihan(array('tagihan !=' => 2, 'unit' => 1));
		$data['tagihan_sebelumnya'] = $this->billing->getDataBillingTagihanDetailPiutang($id, "30")->row();
		//$data['tagihan_sebelumnya_utility'] = $this->billing->getDataBillingTagihanDetailPiutang($id, $this->apl->tagihan(array('tagihan' => 2, 'unit' => 1)))->row();
		$data['id'] = $id;

		$data['denda'] = $this->apl->get_nilai_pilih("billing_detail", "SUM(jumlah)"
			, array(
				'id_billing' => $id,
				'id_tag' => 11,
				'status' => 1,
				'hapus' => 0,
			));

		//$data['over_payment'] = $this->billing->getDataOverPayment($data['billing']->id_bast, $data['billing']->tanggal_terbit);
		$data['over_payment'] = ($data['tagihan_sebelumnya']->tagih < -10000)
			? $data['tagihan_sebelumnya']->tagih : 0;
		/*
		$data['va'] = $this->apl->getSelectedData("db_bank", array(
			'inv' => 1,
			'hapus' => '0',
		))->result();
		*/
		$data['va'] = $this->db->select('*')
			->from('db_bank')
			->where(array('inv' => 1, 'hapus' => 0))
			->order_by('tipe DESC')->get()->result();


		$per = $this->db->select("MIN(`billing_detail`.`tanggal`) as tanggal_awal
                  , MAX(`billing_detail`.`tanggal`) as tanggal_ahir")
			->from('billing_detail')
			->join('db_tag', 'billing_detail.id_tag=db_tag.id_tag')
			->where(
				array(
					'billing_detail.id_billing' => $id,
					'billing_detail.hapus' => 0,
					'billing_detail.id_tag !=' => 11, //Denda
					'billing_detail.status' => 1
				))
			->group_by('`billing_detail`.`id_tag`,`billing_detail`.`ket`')
			->order_by('db_tag.tagihan,urut')->get()->row();

		$data['periode'] = ($this->apl->tgl_format($per->tanggal_awal, 4) == $this->apl->tgl_format($per->tanggal_ahir, 4))
			? $this->apl->tgl_format($per->tanggal_awal, 4) : $this->apl->tgl_format($per->tanggal_awal, 4) . " s.d "
			. $this->apl->tgl_format($per->tanggal_ahir, 4);

		$this->load->view('billing/invoice/print_priview_invoice', $data);

	}

	public function print_invoice()
	{
		/**
		 * Hitung Jumlah Print
		 */
		$id = $this->input->post('id');
		$b = $this->apl->getSelectedData("billing", array('id_billing' => $id));
		$bil = $b->row_array();

		$data = $b->row();
		$bast = $this->apl->getSelectedData("bast", array('id_bast' => $data->id_bast))->row_array();

		$print = $data->print;
		$no_invoice = $data->invoice;
		$data_detail = $this->apl->getSelectedData("billing_detail", array(
			'id_billing' => $id,
			'hapus' => 0,
		))->result();


		$this->load->library('ciqrcode');
		$params['data'] = $id;
		$params['level'] = 'H';
		$params['size'] = 10;
		$params['cachedir'] = 'upload/qr_code/';
		$params['savename'] = 'upload/qr_code/' . time() . "_" . $print . "_" . $id . '_inv.png';
		$qr_code = $this->ciqrcode->generate($params);
		$this->apl->log_print("PRINT INVOICE NO. " . $no_invoice, "billing", $id, ($print + 1),
			json_encode(array_merge($bil, $bast)), json_encode($data_detail), $qr_code);
		$this->apl->updateData("billing", array('print' => $print + 1), array('id_billing' => $id));
		$this->pesan->pesan_success("Print Data Invoice No. " . $no_invoice);
		echo json_encode(array("status" => TRUE));
	}

	public function invoice_print($id)
	{
		$data['billing'] = $this->apl->getSelectedData("billing", array('id_billing' => $id))->row();
		$data['bast'] = $this->apl->getSelectedData("bast", array('id_bast' => $data['billing']->id_bast))->row();
		$data['p'] = $this->apl->getSelectedData("pemilik", array('id_pemilik' => $data['bast']->id_pemilik))->row();
		$data['unit'] = $this->billing->getDataUnitBast($data['billing']->id_bast)->row();
		$data['billing_detail'] = $this->billing->getDataBillingTagihanDetail($id, "1")->result();
		foreach ($data['billing_detail'] as $tagihan) {
			$tagihan_id[] = $tagihan->id_tag;
		}

		$ipl = $this->apl->tagihan(array('tagihan !=' => 2, 'unit' => 1));
		$data['tagihan_sebelumnya'] = $this->billing->getDataBillingTagihanDetailPiutang($id, "30")->row();
		$data['id'] = $id;
		$data['denda'] = $this->apl->get_nilai_pilih("billing_detail", "SUM(jumlah)"
			, array(
				'id_billing' => $id,
				'id_tag' => 11, //Denda
				'status' => 1,
				'hapus' => 0,
			));
		//$data['over_payment'] = 0;
		//$data['over_payment'] = $this->billing->getDataOverPayment($data['billing']->id_bast, $data['billing']->tanggal_terbit);
		$data['over_payment'] = ($data['tagihan_sebelumnya']->tagih < -10000) ? $data['tagihan_sebelumnya']->tagih : 0;

		$data['qr_code'] = $this->apl->get_nilai_pilih("log_print", "qr_code", array(
			'nama_tabel' => 'billing',
			'id_tabel' => $id,
			'print' => $data['billing']->print,
		));

		/*
		$data['va'] = $this->apl->getSelectedData("db_bank", array(
			'inv' => 1,
			'hapus' => '0',
		))->result();
		*/
		$data['va'] = $this->db->select('*')
			->from('db_bank')
			->where(array('inv' => 1, 'hapus' => 0))
			->order_by('tipe DESC')->get()->result();

		$data['va2'] = $this->db->select('*')
			->from('billing_va')
			->where(array('id_billing' => $id, 'hapus' => 0))
			->get()->result();

		$data['redirect'] = isset($_GET['red']) ? $_GET['red'] : '';
		$this->load->view('billing/invoice/print_invoice', $data);
	}


	public function share($id)
	{
		$this->session->id_bms = "1";
		$data['billing'] = $this->apl->getSelectedData("billing", array('id_billing' => $id))->row();
		$data['bast'] = $this->apl->getSelectedData("bast", array('id_bast' => $data['billing']->id_bast))->row();
		$data['p'] = $this->apl->getSelectedData("pemilik", array('id_pemilik' => $data['bast']->id_pemilik))->row();
		$data['unit'] = $this->billing->getDataUnitBast($data['billing']->id_bast)->row();
		$data['billing_detail'] = $this->billing->getDataBillingTagihanDetail($id, "1")->result();
		foreach ($data['billing_detail'] as $tagihan) {
			$tagihan_id[] = $tagihan->id_tag;
		}

		$ipl = $this->apl->tagihan(array('tagihan !=' => 2, 'unit' => 1));
		$data['tagihan_sebelumnya'] = $this->billing->getDataBillingTagihanDetailPiutang($id, "30")->row();
		$data['id'] = $id;
		$data['denda'] = $this->apl->get_nilai_pilih("billing_detail", "SUM(jumlah)"
			, array(
				'id_billing' => $id,
				'id_tag' => 11, //Denda
				'status' => 1,
				'hapus' => 0,
			));
		//$data['over_payment'] = 0;
		//$data['over_payment'] = $this->billing->getDataOverPayment($data['billing']->id_bast, $data['billing']->tanggal_terbit);
		$data['over_payment'] = ($data['tagihan_sebelumnya']->tagih < -10000) ? $data['tagihan_sebelumnya']->tagih : 0;

		$data['qr_code'] = $this->apl->get_nilai_pilih("log_print", "qr_code", array(
			'nama_tabel' => 'billing',
			'id_tabel' => $id,
			'print' => $data['billing']->print,
		));

		$data['va'] = $this->apl->getSelectedData("db_bank", array(
			'inv' => 1,
			'hapus' => '0',
		))->result();
		$data['va2'] = $this->db->select('*')
			->from('billing_va')
			->where(array('id_billing' => $id, 'hapus' => 0))
			->get()->result();
		$data['redirect'] = isset($_GET['red']) ? $_GET['red'] : '';
		$this->load->view('billing/invoice/share', $data);
	}

	public function pdf($id)
	{
		$this->session->id_bms = "1";
		$data['billing'] = $this->apl->getSelectedData("billing", array('id_billing' => $id))->row();
		$data['bast'] = $this->apl->getSelectedData("bast", array('id_bast' => $data['billing']->id_bast))->row();
		$data['p'] = $this->apl->getSelectedData("pemilik", array('id_pemilik' => $data['bast']->id_pemilik))->row();
		$data['unit'] = $this->billing->getDataUnitBast($data['billing']->id_bast)->row();
		$data['billing_detail'] = $this->billing->getDataBillingTagihanDetail($id, "1")->result();
		foreach ($data['billing_detail'] as $tagihan) {
			$tagihan_id[] = $tagihan->id_tag;
		}

		$ipl = $this->apl->tagihan(array('tagihan !=' => 2, 'unit' => 1));
		$data['tagihan_sebelumnya'] = $this->billing->getDataBillingTagihanDetailPiutang($id, "30")->row();
		$data['id'] = $id;
		$data['denda'] = $this->apl->get_nilai_pilih("billing_detail", "SUM(jumlah)"
			, array(
				'id_billing' => $id,
				'id_tag' => 11, //Denda
				'status' => 1,
				'hapus' => 0,
			));
		//$data['over_payment'] = 0;
		//$data['over_payment'] = $this->billing->getDataOverPayment($data['billing']->id_bast, $data['billing']->tanggal_terbit);
		$data['over_payment'] = ($data['tagihan_sebelumnya']->tagih < -10000) ? $data['tagihan_sebelumnya']->tagih : 0;

		$data['qr_code'] = $this->apl->get_nilai_pilih("log_print", "qr_code", array(
			'nama_tabel' => 'billing',
			'id_tabel' => $id,
			'print' => $data['billing']->print,
		));

		$data['va'] = $this->apl->getSelectedData("db_bank", array(
			'inv' => 1,
			'hapus' => '0',
		))->result();
		$data['redirect'] = isset($_GET['red']) ? $_GET['red'] : '';
		//$this->load->view('billing/invoice/share', $data);
		//$this->load->view('billing/invoice/print_invoice', $data);
		$this->apl->generate_pdf("invoice.pdf", 'billing/invoice/pdf', $data);


	}


	public function add()
	{
		$id_bast = isset($_GET['id']) ? $_GET['id'] : '';
		$data['id_bast'] = isset($_POST['id_bast']) ? $_POST['id_bast'] : $id_bast;

		$data['tahun'] = isset($_POST['tahun']) ? $_POST['tahun'] : date('Y');
		if (!isset($_GET['tahun'])) {
			if (date("d") >= $this->apl->tanggal_periode() && date("m") == '12' && $data['tahun'] == date('Y')) {
				$data['tahun'] = $data['tahun'] + 1;
			}
		}
		$data['judul'] = 'Issued Bills';
		//getDataMasterTagihanUnitPeriode
		if ($data['id_bast'] != '') {
			$data['b'] = $this->apl->getSelectedData("bast", array('id_bast' => $data['id_bast']))->row();
			$data['list'] = $this->billing->getDataMasterTagihanUnitPeriode($data['id_bast'],
				$this->apl->tagihan("tagihan IN (1,3)"));

			$data['list_lainnya'] = $this->billing->getDataMasterTagihanUnitPeriode($data['id_bast'],
				$this->apl->tagihan(array('tagihan >=' => 5,
					//'tagihan <'=>10,
					)));
			$data['list_lainnya_invoice'] = $this->billing->getDataMasterTagihanLainnya($this->apl->tagihan(array('tagihan >=' => 5,
				//'tagihan <'=>10,
				)));

			$utility = $this->billing->getDataMasterTagihanUnitUtility($data['id_bast'],
				$this->apl->tagihan(array('tagihan' => 2)));
			$data['jumlah_utility'] = $utility->num_rows();
			$data['list_utility'] = $utility;

			$p = $this->billing->getDataPiutangTanggal($data['id_bast']);
			$data['piutang'] = (isset($p)) ? $p->piutang : '0';
			$data['p_awal'] = (isset($p)) ? $p->tanggal_awal : date('Y-m-d');
			$data['p_ahir'] = (isset($p)) ? $p->tanggal_ahir : date('Y-m-d');
			$data['denda'] = ($data['piutang'] == '0') ? '0' : (3 / 100) * $data['piutang'];

		}
		$data['page'] = 'billing/invoice/form_add';
		$this->load->view('home', $data);
	}

	public function add_generate()
	{
		$id_bast = isset($_GET['id']) ? $_GET['id'] : '';
		$data['id_bast'] = isset($_POST['id_bast']) ? $_POST['id_bast'] : $id_bast;
		$data['tahun'] = isset($_POST['tahun']) ? $_POST['tahun'] : date('Y');
		if (!isset($_GET['tahun'])) {
			if (date("d") >= $this->apl->tanggal_periode() && date("m") == '12' && $data['tahun'] == date('Y')) {
				$data['tahun'] = $data['tahun'] + 1;
			}
		}
		$data['judul'] = 'Issued Bills';
		$data['unit_kecuali'] = $this->apl->get_nilai_pilih("set_tagihan", "value", array('id' => 5));
		$data['page'] = 'billing/invoice/form_add_generate';
		$this->load->view('home', $data);
	}

	function add_act()
	{
		/**
		 * Insert tabel ar_billing
		 */
		$id_bast = isset($_POST['id_bast']) ? $_POST['id_bast'] : '';
		$id_billing = $this->apl->urut('billing', 'id_billing');
		$id_unit = $this->apl->get_nilai_pilih("bast", "id_unit", array('id_bast' => $id_bast));
		$id_pemilik = $this->apl->get_nilai_pilih("bast", "id_pemilik", array('id_bast' => $id_bast));

		$unit = $this->apl->getSelectedData("db_unit", array('id_unit' => $id_unit))->row();
		if ($unit->id_group == '1') {
			$tujuan = '1';
			$nama = $this->apl->get_nilai_pilih("pemilik", "nama", array('id_pemilik' => $id_pemilik));
			$tlp = $this->apl->get_nilai_pilih("pemilik", "hp", array('id_pemilik' => $id_pemilik));
			$id_penyewa = "";
			$alamat = $this->apl->get_nilai("SELECT 
        CONCAT(alamat_surat) as alamat 
        FROM bast WHERE id_bast='$id_bast' ", "alamat");
		} else {
			$tujuan = '2';
			$huni = $this->db->select('*')
				->from('bast_huni')
				->where(array(
					'hapus' => 0,
					'id_bast' => $id_bast,
					'status <' => 4,
				))
				->order_by('id_huni DESC')
				->limit('1')->get()->row();
			$id_penyewa = $huni->id_huni;
			$nama = $huni->nama;
			$tlp = $huni->hp;
			$alamat = $huni->alamat;
		}

		/**
		 * Insert data billing
		 */
		$data_billing = array(
			'id_billing' => $id_billing,
			'id_bast' => $id_bast,
			'invoice' => "INV/EPR-IPL" . "/" . $this->apl->counter("INV-EPR") . "/"
				. $this->apl->bulan(date('m', strtotime($this->input->post('tanggal_cetak'))), "4") . "/" . date('Y', strtotime($this->input->post('tanggal_cetak'))),

			'tanggal_terbit' => $this->apl->tgl_format($this->input->post('tanggal_cetak')),
			'tanggal_jt' => date('Y-m-d', strtotime('+' . $this->apl->tanggal_jt() . ' days',
				strtotime($this->input->post('tanggal_cetak')))),
			'id_admin' => $this->session->userdata('id_admin'),
			'hapus' => 0,
			'nama' => $nama,
			'alamat' => $alamat,
			'tlp' => $tlp,
			'tujuan' => $tujuan,
			'id_pemilik' => $id_pemilik,
			'id_penyewa' => $id_penyewa,
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
			$list = $this->billing->getDataMasterTagihanUnitPeriode($id_bast,
				$this->apl->tagihan("tagihan IN (1,3)"));
			//print_r($list->result());
			//echo "<br>";
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
						'bulan' => date('m', strtotime($tanggal_tagihan[$i])),
						'tahun' => date('Y', strtotime($tanggal_tagihan[$i])),
					);
					//print_r($data_billing_detail);
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
					'bulan' => date('m', strtotime($tanggal[$i])),
					'tahun' => date('Y', strtotime($tanggal[$i])),
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


				'bulan' => date('m', strtotime($this->input->post('tanggal_cetak'))),
				'tahun' => date('Y', strtotime($this->input->post('tanggal_cetak'))),
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


		/**
		 * Piutang
		 */

		$piutang = $this->apl->number_format($this->input->post('piutang'));
		//if ($piutang > 0) {
		$ket_piutang = $this->input->post('ket_piutang');
		$tahun_dari = $this->input->post('tahun_dari');
		$bulan_dari = $this->input->post('bulan_dari');
		$tahun_ke = $this->input->post('tahun_ke');
		$bulan_ke = $this->input->post('bulan_ke');


		if ($ket_piutang == 'MANUAL') {
			$ket = $this->input->post('ket_piutang_note');
		} else {
			$ket = $ket_piutang . " " . $this->apl->bulan($bulan_dari, 2)
				. ' ' . $tahun_dari . " s.d " . $this->apl->bulan($bulan_ke, 2) . ' ' . $tahun_ke;
		}
		$this->apl->insertData("billing_detail",
			$data_billing = array(
				'id_detail' => $this->apl->urut("billing_detail", "id_detail"),
				'id_tag' => "30",
				'id_billing' => $id_billing,
				'ket' => 'Piutang',
				'note' => $ket,
				'tanggal' => $this->apl->tgl_format($this->input->post('tanggal_cetak')),
				'hapus' => 0,
				'id_admin' => $this->session->userdata('id_admin'),
				'jumlah' => $piutang,
				'id_bast' => $id_bast,
				'status' => 4,
				'bulan' => $bulan_dari,
				'tahun' => $tahun_dari,
				'bulan2' => $bulan_ke,
				'tahun2' => $tahun_ke,
			));
		//}
		$this->pesan->pesan_success("successfully added billing data");
		if (isset($_POST['simpan'])) {
			redirect($_SERVER['HTTP_REFERER']);
		}

		if (isset($_POST['print'])) {
			redirect(site_url('billing/invoice/invoice_print/'
				. $id_billing . '?red=billing/invoice/add?id=' . $id_bast));
		}

	}

	function add_act_g()
	{
		$id_generate = $this->apl->urut("billing_generate", "id_generate");
		$id_bast_kecuali = implode(',', $this->input->post('id_bast_kecuali'));
		$tanggal_tagihan = implode(',', $this->input->post('tanggal_tagihan'));
		$tanggal_tagihan_utility = implode(',', $this->input->post('tanggal_tagihan_utility'));
		$tanggal_cetak = $this->input->post('tanggal_cetak');
		$bast = $this->apl->getAllData("bast")->num_rows();
		$this->apl->insertData("billing_generate", array(
			'id_generate' => $id_generate,
			'id_admin' => $this->session->id_admin,
		));

		redirect(site_url('billing/invoice/add_act_generate?id_bast=1&id_bast_kecuali=' . $id_bast_kecuali
			. '&tanggal_tagihan=' . $tanggal_tagihan
			. '&tanggal_tagihan_utility=' . $tanggal_tagihan_utility
			. '&tanggal_cetak=' . $tanggal_cetak
			. '&id_generate=' . $id_generate
			. '&bast=' . $bast
		));


	}

	function add_act_generate()
	{
		/**
		 * Insert tabel ar_billing
		 */
		echo '<div class="spinner-border" role="status">
  <span class="sr-only">Loading...</span>
</div>';
		$id_generate = $this->input->get('id_generate');
		$id_bast = $this->input->get('id_bast');
		$tanggal_cetak = $this->input->get('tanggal_cetak');
		$bast = $this->input->get('bast');

		$id_bast_kecuali = explode(',', $this->input->get('id_bast_kecuali'));
		$tanggal_tagihan = explode(',', $this->input->get('tanggal_tagihan'));
		$tanggal_tagihan_utility = explode(',', $this->input->get('tanggal_tagihan_utility'));

		if ($bast < $id_bast) {
			//if (10 < $id_bast) {
			//die();
			redirect(site_url('billing/invoice'), 'refresh');
		}
		//print_r($id_bast_kecuali);
		//die();
		//if (array_key_exists($id_bast, $id_bast_kecuali)) {
		if (in_array($id_bast, $id_bast_kecuali)) {
			//die("kecuali");
			redirect(site_url('billing/invoice/add_act_generate?id_bast=' . ($id_bast + 1)
				. '&id_bast_kecuali=' . implode(',', $id_bast_kecuali)
				. '&tanggal_tagihan=' . implode(',', $tanggal_tagihan)
				. '&tanggal_tagihan_utility=' . implode(',', $tanggal_tagihan_utility)
				. '&tanggal_cetak=' . $tanggal_cetak
				. '&id_generate=' . $id_generate
				. '&bast=' . $bast
			), "refresh");
		}
		//print_r($id_bast_kecuali);
		//die();

		/*
		if ($this->apl->getSelectedData("bast", array('id_bast' => $id_bast))->num_rows() == '1') {
			die("kecuali");


			redirect(site_url('billing/invoice/add_act_generate?id_bast=' . ($id_bast + 1)
				. '&id_bast_kecuali=' . implode(',',$id_bast_kecuali)
				. '&tanggal_tagihan=' . implode(',',$tanggal_tagihan)
				. '&tanggal_tagihan_utility=' . implode(',',$tanggal_tagihan_utility)
				. '&tanggal_cetak=' . $tanggal_cetak
				. '&id_generate=' . $id_generate
				. '&bast=' . $bast
			),"refresh");
		}
		*/


		$query = "select db_unit.id_unit,kode as pilih
 from `db_unit` inner join bast on bast.id_unit=db_unit.id_unit 
 where bast=1 and db_unit.id_group=1 
   and bast.hapus=0 and bast.id_bast=" . $id_bast;
		$cek = $this->apl->manualQuery($query)->num_rows();
		if ($cek == '1') {
			$id_billing = $this->apl->urut('billing', 'id_billing');
			$id_unit = $this->apl->get_nilai_pilih("bast", "id_unit", array('id_bast' => $id_bast));
			$id_pemilik = $this->apl->get_nilai_pilih("bast", "id_pemilik", array('id_bast' => $id_bast));

			$unit = $this->apl->getSelectedData("db_unit", array('id_unit' => $id_unit))->row();
			if ($unit->id_group == '1') {
				$tujuan = '1';
				$nama = $this->apl->get_nilai_pilih("pemilik", "nama", array('id_pemilik' => $id_pemilik));
				$tlp = $this->apl->get_nilai_pilih("pemilik", "hp", array('id_pemilik' => $id_pemilik));
				$id_penyewa = "";
				$alamat = $this->apl->get_nilai("SELECT 
        CONCAT(alamat_surat) as alamat 
        FROM bast WHERE id_bast='$id_bast' ", "alamat");
			} else {
				$tujuan = '2';
				$huni = $this->db->select('*')
					->from('bast_huni')
					->where(array(
						'hapus' => 0,
						'id_bast' => $id_bast,
						'status <' => 4,
					))
					->order_by('id_huni DESC')
					->limit('1')->get()->row();
				$id_penyewa = $huni->id_huni;
				$nama = $huni->nama;
				$tlp = $huni->hp;
				$alamat = $huni->alamat;
			}

			$virtual = $this->db->select('*')
				->from('db_bank')
				->where(array('inv' => 1))
				->order_by('tipe DESC')->get()->result();
			$cara_pembayaran = '';
			foreach ($virtual as $vir) {
				$nva = ($vir->tipe == '1') ? '{{va}}' : '{{va2}}';
				$cara_pembayaran .= '- VA ' . $vir->nama . '       : *' . $vir->va . ' - ' . $nva . '*
			';
			}
			/**
			 * Insert data billing
			 */
			$data_billing = array(
				'id_billing' => $id_billing,
				'id_bast' => $id_bast,
				'invoice' => "INV/EPR-IPL" . "/" . $this->apl->counter("INV-EPR", date('Y', strtotime($tanggal_cetak))) . "/"
					. $this->apl->bulan(date('m', strtotime($tanggal_cetak)), "4") . "/"
					. date('Y', strtotime($tanggal_cetak)),
				'tanggal_terbit' => $this->apl->tgl_format($tanggal_cetak),
				'tanggal_jt' => date('Y-m-d', strtotime('+' . $this->apl->tanggal_jt() . ' days',
					strtotime($tanggal_cetak))),
				'id_admin' => $this->session->userdata('id_admin'),
				'hapus' => 0,
				'nama' => $nama,
				'alamat' => $alamat,
				'tlp' => $tlp,
				'tujuan' => $tujuan,
				'id_pemilik' => $id_pemilik,
				'id_penyewa' => $id_penyewa,
				'id_generate' => $id_generate,
				'ket_bayar' => $cara_pembayaran,
			);
			$this->apl->insertData("billing", $data_billing);
			$this->apl->log("Tambah Data Invoice", "",
				json_encode($data_billing), "billing", $id_billing);
			$this->apl->log("Tambah Data Invoice", "",
				json_encode($data_billing), "db_unit", $id_unit);
			//$p = $this->billing->getDataPiutangTanggal($id_bast,$id_billing);
			$p = $this->billing->getDataPiutangTanggal($id_bast, '');
			$piutang = (isset($p)) ? $p->piutang : '0';
			$denda = ($piutang == '0') ? '0' : (3 / 100) * $piutang;

			/**
			 * Denda dari piutang
			 */
			//$denda = $this->apl->number_format($this->input->post('denda'));
			if ($denda > 0) {
				$id_detail = $this->apl->urut("billing_detail", "id_detail");
				$data_billing_detail_utility = array(
					'id_detail' => $id_detail,
					'id_tag' => '11',
					'id_billing' => $id_billing,
					'ket' => 'Denda Keterlambatan',
					'tanggal' => $this->apl->tgl_format($tanggal_cetak, 0),
					'hapus' => 0,
					'id_admin' => $this->session->userdata('id_admin'),
					'jumlah' => $denda,
					'id_bast' => $id_bast,
					'status' => 1,
				);
				$this->apl->insertData("billing_detail", $data_billing_detail_utility);
			}
			/**
			 * Piutang
			 */
			if ($piutang != 0) {
				$note = ($piutang > 0) ? 'Piutang' : 'Lebih bayar';
				$id_detail = $this->apl->urut("billing_detail", "id_detail");
				$this->apl->insertData("billing_detail",
					$data_billing = array(
						'id_detail' => $id_detail,
						'id_tag' => "30",
						'id_billing' => $id_billing,
						'ket' => 'Piutang',
						'note' => $note,
						'tanggal' => $this->apl->tgl_format($tanggal_cetak),
						'hapus' => 0,
						'id_admin' => $this->session->userdata('id_admin'),
						'jumlah' => $piutang,
						'id_bast' => $id_bast,
						'status' => 4,
					));

			}
			/**
			 * Insert tabel ar_billing_detail Periode
			 */

			if ($tanggal_tagihan != '') {
				$list = $this->billing->getDataMasterTagihanUnitPeriode($id_bast, $this->apl->tagihan("tagihan IN (1,3) AND unit=1"));
				//for ($no = 1; $no <= $list->num_rows(); $no++) {
				$no = 0;
				foreach ($list->result() as $t) {
					$tanggal_awal_array = array();
					$tanggal_ahir_array = array();
					$periode_array = array();
					$tagihan_id = $t->id_tag;
					$nama_tagihan = $t->nama;
					$total = $this->apl->total_pertagihan($t->tagihan, $t->jumlah, $t->luas, '1');
					//$ket = $this->input->post('ket');
					for ($i = 0; $i < count($tanggal_tagihan); $i++) {
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

						$cek = $this->apl->getSelectedData("billing_detail", array(
							'id_tag' => $tagihan_id,
							//'note' => "",
							'tanggal' => $this->apl->tgl_format($tanggal_tagihan[$i], 0),
							'hapus' => 0,
							'id_bast' => $id_bast,
							'status' => 1,
							'bulan' => date('m', strtotime($tanggal_tagihan[$i])),
							'tahun' => date('Y', strtotime($tanggal_tagihan[$i])),
						))->num_rows();
						if ($cek == 0) {
							$data_billing_detail = array(
								'id_detail' => $this->apl->urut("billing_detail", "id_detail"),
								'id_tag' => $tagihan_id,
								'id_billing' => $id_billing,
								'ket' => $nama_tagihan,
								'note' => "",
								'tanggal' => $this->apl->tgl_format($tanggal_tagihan[$i], 0),
								'hapus' => 0,
								'id_admin' => $this->session->userdata('id_admin'),
								'jumlah' => $this->apl->number_format($total),
								'periode' => $periode,
								'id_bast' => $id_bast,
								'status' => 1,
								'bulan' => date('m', strtotime($tanggal_tagihan[$i])),
								'tahun' => date('Y', strtotime($tanggal_tagihan[$i])),
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
									'id_tag' => $tagihan_id,
								));

							$this->apl->updateData("billing", array('periode' => $periode), array('id_billing' => $id_billing));
						}
					}
				}
			}

			if ($tanggal_tagihan_utility != '') {
				$list = $this->billing->getDataMasterTagihanUnitPeriode($id_bast, $this->apl->tagihan(array('tagihan' => 2)));
				foreach ($list->result() as $t) {
					$tagihan_id = $t->id_tag;
					$nama_tagihan = $t->nama;
					for ($i = 0; $i < count($tanggal_tagihan_utility); $i++) {
						$ut = $this->db
							->select('`utility_rekening`.`id_tag`,`db_tag`.`nama`,`utility`.`pakai`,`utility`.`id_meter`,
							`utility`.`tanggal`')
							->from('utility')
							->join('`utility_rekening`', '`utility`.`id_rekening` = `utility_rekening`.`id_rekening`')
							->join('`db_tag`', '`db_tag`.`id_tag` = `utility_rekening`.`id_tag`')
							->join('`bast`', '`bast`.`id_unit` = `utility_rekening`.`id_unit`')
							->where(array(
								'`utility_rekening`.`hapus`' => 0,
								'`bast`.`hapus`' => 0,
								'`bast`.`id_bast`' => $id_bast,
								'`utility`.`post`' => 0,
								'`utility`.`hapus`' => 0,
								'`utility`.`bulan`' => date('m', strtotime($tanggal_tagihan_utility[$i])),
								'`utility`.`tahun`' => date('Y', strtotime($tanggal_tagihan_utility[$i])),
								'`utility_rekening`.`id_tag`' => $tagihan_id,
							))->get();
						if ($ut->num_rows() == 1) {
							$u = $ut->row();
							$total = ($t->jumlah * $u->pakai);
							$id_detail = $this->apl->urut("billing_detail", "id_detail");
							$data_billing_detail_utility = array(
								'id_detail' => $id_detail,
								'id_tag' => $tagihan_id,
								'id_billing' => $id_billing,
								'ket' => $nama_tagihan,
								'tanggal' => $tanggal_tagihan_utility[$i],
								'hapus' => 0,
								'id_admin' => $this->session->userdata('id_admin'),
								'jumlah' => $this->apl->number_format($total),
								'id_bast' => $id_bast,
								'status' => 1,
								'`bulan`' => date('m', strtotime($tanggal_tagihan_utility[$i])),
								'`tahun`' => date('Y', strtotime($tanggal_tagihan_utility[$i])),
							);
							$this->apl->insertData("billing_detail", $data_billing_detail_utility);
							$this->apl->updateData("utility", array(
								'post' => 1,
								'user_post' => $this->session->userdata('id_admin'),
								'id_billing' => $id_billing,
								'id_billing_detail' => $id_detail,
							), array(
									'id_meter' => $u->id_meter,)
							);
						}

					}
				}
			}


			//TAGIHAN LAINNYA
			$list = $this->billing->getDataMasterTagihanUnitPeriode($id_bast, $this->apl->tagihan(array('tagihan' => 5)));
			foreach ($list->result() as $t) {
				$tagihan_id = $t->id_tag;
				$nama_tagihan = $t->nama;

				$cek = $this->apl->getSelectedData("billing_detail", array(
					'id_tag' => $tagihan_id,
					'note' => "",
					'tanggal' => $this->apl->tgl_format($tanggal_cetak, 0),
					'hapus' => 0,
					'id_bast' => $id_bast,
					'status' => 1,
					'bulan' => date('m', strtotime($tanggal_cetak)),
					'tahun' => date('Y', strtotime($tanggal_cetak)),
				))->num_rows();
				if ($cek == 0) {
					$data_billing_detail = array(
						'id_detail' => $this->apl->urut("billing_detail", "id_detail"),
						'id_tag' => $tagihan_id,
						'id_billing' => $id_billing,
						'ket' => $nama_tagihan,
						'note' => "",
						'tanggal' => $this->apl->tgl_format($tanggal_cetak, 0),
						'hapus' => 0,
						'id_admin' => $this->session->userdata('id_admin'),
						'jumlah' => $this->apl->number_format($t->jumlah),
						'periode' => $periode,
						'id_bast' => $id_bast,
						'status' => 1,
						'bulan' => date('m', strtotime($tanggal_cetak)),
						'tahun' => date('Y', strtotime($tanggal_cetak)),
					);
					$this->apl->insertData("billing_detail", $data_billing_detail);
				}
			}


		}

		redirect(site_url('billing/invoice/add_act_generate?id_bast=' . ($id_bast + 1)
			. '&id_bast_kecuali=' . implode(',', $id_bast_kecuali)
			. '&tanggal_tagihan=' . implode(',', $tanggal_tagihan)
			. '&tanggal_tagihan_utility=' . implode(',', $tanggal_tagihan_utility)
			. '&tanggal_cetak=' . $tanggal_cetak
			. '&id_generate=' . $id_generate
			. '&bast=' . $bast
		), "refresh");
	}


	private function extractMonthKeysFromDates($dateValues)
	{
		if (!is_array($dateValues)) {
			$dateValues = ($dateValues !== '' && $dateValues !== null)
				? explode(',', $dateValues)
				: array();
		}

		$monthKeys = array();
		foreach ($dateValues as $dateValue) {
			$dateValue = trim((string) $dateValue);
			if ($dateValue === '') {
				continue;
			}

			$timestamp = strtotime($dateValue);
			if ($timestamp === FALSE) {
				continue;
			}

			$monthKeys[date('Y-m', $timestamp)] = date('Y-m', $timestamp);
		}

		ksort($monthKeys, SORT_STRING);
		return array_values($monthKeys);
	}

	private function formatMonthKeyLabel($monthKey)
	{
		$timestamp = strtotime($monthKey . '-01');
		if ($timestamp === FALSE) {
			return '';
		}

		return $this->apl->bulan(date('m', $timestamp), 2) . ' ' . date('Y', $timestamp);
	}

	private function formatMonthRangeLabel($startMonthKey, $endMonthKey)
	{
		if ($startMonthKey === $endMonthKey) {
			return $this->formatMonthKeyLabel($startMonthKey);
		}

		$startTimestamp = strtotime($startMonthKey . '-01');
		$endTimestamp = strtotime($endMonthKey . '-01');

		if ($startTimestamp === FALSE || $endTimestamp === FALSE) {
			return '';
		}

		if (date('Y', $startTimestamp) === date('Y', $endTimestamp)) {
			return $this->apl->bulan(date('m', $startTimestamp), 2)
				. ' - '
				. $this->apl->bulan(date('m', $endTimestamp), 2)
				. ' '
				. date('Y', $startTimestamp);
		}

		return $this->formatMonthKeyLabel($startMonthKey) . ' - ' . $this->formatMonthKeyLabel($endMonthKey);
	}

	private function buildPeriodLabelFromDates($dateValues)
	{
		$monthKeys = $this->extractMonthKeysFromDates($dateValues);
		if (empty($monthKeys)) {
			return '';
		}

		$labels = array();
		$rangeStart = $monthKeys[0];
		$previous = $monthKeys[0];

		for ($i = 1; $i < count($monthKeys); $i++) {
			$current = $monthKeys[$i];
			$expected = date('Y-m', strtotime($previous . '-01 +1 month'));
			if ($current === $expected) {
				$previous = $current;
				continue;
			}

			$labels[] = $this->formatMonthRangeLabel($rangeStart, $previous);
			$rangeStart = $current;
			$previous = $current;
		}

		$labels[] = $this->formatMonthRangeLabel($rangeStart, $previous);
		return implode(', ', $labels);
	}

	private function enrichBillingDetailPeriods($billingDetail)
	{
		foreach ($billingDetail as $detail) {
			$detail->periode_label = $this->buildPeriodLabelFromDates($detail->tanggal_list);
		}

		return $billingDetail;
	}

	private function isCommercialAreaGroup($unitOrGroup)
	{
		if (is_object($unitOrGroup)) {
			$unitOrGroup = isset($unitOrGroup->id_group) ? $unitOrGroup->id_group : '';
		}

		return (string) $unitOrGroup === '2';
	}

	function ajax_hapus_invoice($id_billing)
	{
		$b = $this->apl->getSelectedData("billing", array('id_billing' => $id_billing))->row();
		$dt = $this->db->select('id_tag,id_detail, tanggal')
			->from('billing_detail')
			->where(array(
				'id_billing' => $id_billing,
				'hapus' => 0,
				'status' => 1,
			))->group_by('id_tag')->get()->result();
		foreach ($dt as $d) {
			/*
			$this->apl->updateData("tagihan", array(
				'tanggal_ahir' => date('Y-m-d', strtotime('-1 days'
					, strtotime(date('Y-m-01', strtotime($d->tanggal))))),
			),
				array(
					'id_tag' => $d->id_tag,
					'id_bast' => $b->id_bast)
			);
			*/
			$this->apl->updateData("utility", array('post' => 0)
				, array(
					'id_billing' => $id_billing,
					//'id_billing_detail'=>$d->id_detail,
					'post' => 1,
					'hapus' => 0,
				));
		}

		$this->apl->log("HAPUS INVOICE"
			, json_encode($this->apl->getSelectedData("billing", array('id_billing' => $id_billing))->row())
			, ""
			, "billing"
			, $id_billing
		);


		$this->apl->updateData("billing", array('hapus' => 1), array('id_billing' => $id_billing));
		$this->apl->updateData("billing_detail",
			array('hapus' => 1, 'hapus_invoice' => 1)
			, array(
				'id_billing' => $id_billing,
				'hapus' => 0,
			));

		$this->pesan->pesan_success("successfully deleted billing data No " . $b->invoice);
		echo json_encode(array("status" => TRUE));
	}

}

?>
