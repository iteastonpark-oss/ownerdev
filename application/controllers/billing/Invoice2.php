<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 4/8/2016
 * Time: 2:42 PM
 */
?>

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice extends CI_Controller
{
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
		$data['id_unit'] = (isset($_POST['id_unit'])) ? $_POST['id_unit'] : "";
		$data['kirim'] = (isset($_POST['kirim'])) ? $_POST['kirim'] : "";
		$data['tanggal_awal'] = isset($_POST['tanggal_awal']) ? $_POST['tanggal_awal']
			: date('Y-m-01');
		$data['tanggal_ahir'] = isset($_POST['tanggal_ahir']) ? $_POST['tanggal_ahir']
			: date('Y-m-d');

		$data['tombol_view'] = '<a href="' . site_url('billing/invoice/add') . '" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Invoice</a>';
		$data['field'] = $this->billing->getDataBillingInvoice($data['id_unit']
			, $data['tanggal_awal'], $data['tanggal_ahir'], $data['kirim'])->get()->list_fields();
		$data['page'] = 'billing/invoice/view'; //Halaman di tampilkan
		$data['judul'] = 'Invoice Terbit';
		$this->load->view('home', $data);
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
			'`billing`.`invoice`',
			'`billing`.`tanggal_terbit`',
			null,
			null,
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

		foreach ($list->result_array() as $data) {
			$no++;
			$r = array_values($data);
			$r[0] = $no;


			$r[3] = $this->apl->tgl_format($r[3], 1);
			$r[4] = $this->apl->tgl_format($r[4], 1);
			$r[5] = '<span class="pull-right">' . $this->apl->number_format($r[5], 1) . '</span>';
			$r[6] = '<span class="pull-right">' . $this->apl->number_format($r[6], 1) . '</span>';
			$r[7] = '<b class="pull-right">' . $this->apl->number_format($r[7], 1) . '</b>';

			$mailto = ($r[9] == 2) ? anchor('#modal_form',
				'<i class="fa fa-mail-forward"></i> Mail To',
				'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal" 
							class="mail_to dropdown-item"') : '';

			$r[9] = ($r[9] == 1) ? '<span class="btn btn-sm btn-info">KURIR</span>'
				: '<span class="btn btn-sm btn-success">EMAIL</span>';
			$r[$jumlah - 1] = '<div class="dropdown">
<button class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">Aksi</button>
<div class="dropdown-menu dropdown-menu-right">'
				. anchor('#modal_form',
					'<i class="fa fa-edit"></i> Edit',
					'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
							class="edit_invoice_list dropdown-item"')
				. anchor('#modal_form',
					'<i class="fa fa-print"></i> Print',
					'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal" 
							class="print_data dropdown-item"')
				//. $mailto
				. $this->apl->anchor(anchor('#modal_form',
						'<i class="fa fa-trash"></i> Hapus',
						'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal" 
                                    class="hapus_inv dropdown-item"')
					, 'tombol hapus invoice', 'ar/billing')

				. '</div></div>';


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


			$r[8] = $this->apl->number_format($r[8], 1);
			//$r[7] = $this->apl->number_format($r[7], 1);
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
			))->where('id_tag IN (' . $this->apl->tagihan(array('tagihan' => 1, 'unit' => 1)) . ')')->get()->row();
		$data['tanggal_tagihan_awal'] = isset($tanggal_p->tanggal_awal) ? $tanggal_p->tanggal_awal
			: $this->apl->get_nilai_pilih("tagihan", "tanggal_ahir", array('id_bast' => $data['id_bast'], 'id_tag' => '1'));
		$data['tanggal_tagihan_ahir'] = isset($tanggal_p->tanggal_ahir) ? $tanggal_p->tanggal_ahir : date('Y-m-d');

		/**
		 * Tagihan Awal ADMINISTRASI
		 */
		$tanggal_adm = $this->db->select('min(`billing_detail`.`tanggal`) as tanggal_awal')
			->select('max(`billing_detail`.`tanggal`) as tanggal_ahir')
			->from('billing_detail')
			->where(array(
				'id_billing' => $id,
				'status' => 1,
				'hapus' => 0,
			))->where('id_tag IN (' . $this->apl->tagihan(array('tagihan' => 3, 'unit' => 1)) . ')')->get()->row();


		//$data['tanggal_tagihan_awal_adm'] = $tanggal_adm->tanggal_awal;
		//$data['tanggal_tagihan_ahir_adm'] = $tanggal_adm->tanggal_ahir;
		//$data['tanggal_tagihan_awal_adm'] = isset($tanggal_p->tanggal_awal) ? $tanggal_p->tanggal_awal : date('Y-m-d');
		$data['tanggal_tagihan_awal_adm'] = isset($tanggal_p->tanggal_awal) ? $tanggal_p->tanggal_awal
			: $this->apl->get_nilai_pilih("tagihan", "tanggal_ahir", array('id_bast' => $data['id_bast'], 'id_tag' => '1'));

		$data['tanggal_tagihan_ahir_adm'] = isset($tanggal_p->tanggal_ahir) ? $tanggal_p->tanggal_ahir : date('Y-m-d');


		$data['no_invoice_baru'] = $this->apl->counter_view("INV-EPR") . "/" . "INV-EPR" . "/"
			. $this->apl->bulan_romawi() . "/" . date('Y', strtotime($data['billing']->tanggal_terbit));

		$data['list'] = $this->billing->getDataMasterTagihanUnitPeriode($data['id_bast'], $this->apl->tagihan(array('tagihan' => 1, 'unit' => 1)));
		$data['list_adm'] = $this->billing->getDataMasterTagihanUnitPeriode($data['id_bast'], $this->apl->tagihan(array('tagihan' => 3, 'unit' => 1)));
		$data['list_adm_invoice'] = $this->billing->getDataMasterTagihanLainnya($this->apl->tagihan(array('tagihan' => 5)));

		$data['list_utility'] = $this->billing->getDataMasterTagihanUnitUtilityInv($data['billing']->id_bast
			, $data['billing']->id_billing, $this->apl->tagihan(array('tagihan' => 2, 'unit' => 1)));
		$data['jumlah_utility'] = $data['list_utility']->num_rows();


		$p = $this->billing->getDataPiutangTanggal($data['billing']->id_bast, $id);
		$data['piutang'] = (isset($p)) ? $p->piutang : '0';
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
			'nama' => $nama,
			'alamat' => $alamat,
			'tlp' => $tlp,
			'tujuan' => 1,
			'id_pemilik' => $id_pemilik,
		);
		$this->apl->updateData("billing", $data_update, array('id_billing' => $id_billing));

		/**
		 * Insert tabel ar_billing_detail Periode
		 */
		if (isset($_POST['update_tagihan_periode'])) {
			$tanggal_tagihan = isset($_POST['tanggal_tagihan']) ? $_POST['tanggal_tagihan'] : array();
			$this->apl->manualQuery("UPDATE billing_detail set hapus=1 
                WHERE id_billing='$id_billing' AND hapus=0
                AND id_tag IN (" . $this->apl->tagihan(array('tagihan' => 1, 'unit' => 1)) . ")");
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

			/**
			 * Administarasi
			 */
			$this->apl->manualQuery("UPDATE billing_detail set hapus=1 
                WHERE id_billing='$id_billing' AND hapus=0
                AND id_tag IN (" . $this->apl->tagihan(array('tagihan' => 3, 'unit' => 1)) . ")");
			$tanggal_tagihan = isset($_POST['tanggal_tagihan_adm']) ? $_POST['tanggal_tagihan_adm'] : array(); //TAGIHAN ADMINISTARSSI
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
		/**
		 * Insert tabel ar_billing_detail Utility
		 */
		if (isset($_POST['update_tagihan_utility'])) {
			$this->apl->updateData('utility', array('post' => 0), array('id_billing' => $id_billing));
			$this->apl->manualQuery("UPDATE billing_detail set hapus=1 
                WHERE id_billing='$id_billing' AND hapus=0
                AND id_tag IN (" . $this->apl->tagihan(array('tagihan' => 2, 'unit' => 1)) . ")");
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


		//$this->input_tagihan_piutang($id_billing, $id_bast);
		$piutang = $this->apl->number_format($this->input->post('piutang'));
		if ($piutang > 0) {
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
		}

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

		$data['over_payment'] = $this->billing->getDataOverPayment($data['billing']->id_bast, $data['billing']->tanggal_terbit);

		$data['va'] = $this->apl->getSelectedData("db_bank", array(
			'inv' => 1,
			'hapus' => '0',
		))->result();

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
		$data['over_payment'] = $this->billing->getDataOverPayment($data['billing']->id_bast, $data['billing']->tanggal_terbit);

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
		$this->load->view('billing/invoice/print_invoice', $data);
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
		$data['judul'] = 'FORM TERBITKAN TAGIHAN';

		if ($data['id_bast'] != '') {

			$data['b'] = $this->apl->getSelectedData("bast", array('id_bast' => $data['id_bast']))->row();
			$data['list'] = $this->billing->getDataMasterTagihanUnitPeriode($data['id_bast'], $this->apl->tagihan(array('tagihan' => 1, 'unit' => 1)));
			$data['list_adm'] = $this->billing->getDataMasterTagihanUnitPeriode($data['id_bast'], $this->apl->tagihan(array('tagihan' => 3, 'unit' => 1)));
			$data['list_adm_invoice'] = $this->billing->getDataMasterTagihanLainnya($this->apl->tagihan(array('tagihan' => 5)));
			$utility = $this->billing->getDataMasterTagihanUnitUtility($data['id_bast'], $this->apl->tagihan(array('tagihan' => 2, 'unit' => 1)));
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

	function add_act()
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

			/*
			'invoice' => $this->apl->counter("INV-EPR") . "/" . "INV-EPR" . "/" . $this->apl->bulan_romawi() . "/"
				. date('Y', strtotime($this->input->post('tanggal_cetak'))),
			*/
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

						'bulan' => date('m', strtotime($tanggal_tagihan[$i])),
						'tahun' => date('Y', strtotime($tanggal_tagihan[$i])),

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


		$piutang = $this->apl->number_format($this->input->post('piutang'));
		if ($piutang > 0) {
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
		}
		$this->pesan->pesan_success("Menambah Data Invoice");
		if (isset($_POST['simpan'])) {
			redirect($_SERVER['HTTP_REFERER']);
		}

		if (isset($_POST['print'])) {
			redirect(site_url('billing/invoice/invoice_print/'
				. $id_billing . '?red=billing/invoice/add?id=' . $id_bast));
		}

	}


	function ajax_hapus_invoice($id_billing)
	{
		$b = $this->apl->getSelectedData("billing", array('id_billing' => $id_billing))->row();
		$dt = $this->db->select('id_tag, tanggal')
			->from('billing_detail')
			->where(array(
				'id_billing' => $id_billing,
				'hapus' => 0,
				'status' => 1,
			))->group_by('id_tag')->get()->result();
		foreach ($dt as $d) {
			$this->apl->updateData("tagihan", array(
				'tanggal_ahir' => date('Y-m-d', strtotime('-1 days'
					, strtotime(date('Y-m-01', strtotime($d->tanggal))))),
			),
				array(
					'id_tag' => $d->id_tag,
					'id_bast' => $b->id_bast)
			);
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

		$this->pesan->pesan_success("Hapus Data Invoice " . $b->invoice . " Berhasil");
		echo json_encode(array("status" => TRUE));
	}

}

?>
