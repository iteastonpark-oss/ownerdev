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
	var $modul = "Pembayaran";

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Bayar_Model', 'bayar');
		$this->load->model('Crud_Model', 'crud_model');
		$this->bayar = new Bayar_Model();
		$this->crud_model = new Crud_Model();
		$this->apl = new Apl();
		$this->tombol = new Tombol();
		$this->pesan = new Pesan();
	}

	public function bayar()
	{
		$unit = $this->input->get('id_unit');
		$data['id_unit'] = (isset($_POST['id_unit'])) ? $_POST['id_unit'] : $unit;
		$data['tombol_view'] = '';
		$data['tabs'] = 'bayar';
		$total = $this->bayar->getDataBillingTotal($data['id_unit'])->row();
		$data['total_tagihan'] = (isset($total)) ? $total->tagihan : 0;
		$data['total_denda'] = (isset($total)) ? $total->denda : 0;
		$data['total_bayar'] = (isset($total)) ? $total->bayar : 0;
		$data['total_piutang'] = (isset($total)) ? $total->piutang : 0;
		$data['field'] = $this->bayar->getDataBillingInvoice($data['id_unit'])->get()->list_fields();
		$data['page'] = 'bayar/invoice/view'; //Halaman di tampilkan
		$data['judul'] = 'Publish Invoice';
		$this->load->view('home', $data);
	}

	public function ajax_list()
	{
		$id_unit = (isset($_POST['id_unit'])) ? $_POST['id_unit'] : '';
		$column_search = array();
		$column_order = array(
			null,
			null,
			null,
			null,
			null,
			null,
		);

		$list = $this->crud_model->get_data(
			$this->bayar->getDataBillingInvoice($id_unit),
			$column_search,
			$column_order
		);
		$record_total = $this->crud_model->get_jumlah(
			$this->bayar->getDataBillingInvoice($id_unit)
		);
		$record_filter = $this->crud_model->get_jumlah_filter(
			$this->bayar->getDataBillingInvoice($id_unit),
			$column_search,
			$column_order
		);


		$no = isset($_POST['start']) ? $_POST['start'] : '0';
		$jumlah = $list->num_fields();
		$data_array = array();

		$sisa = 0;
		$total_tagihan = 0;
		$total_denda = 0;
		$total_bayar = 0;
		$total_piutang = 0;
		$td = array();
		foreach ($list->result_array() as $data) {
			$no++;
			$r = array_values($data);
			$r[0] = $no;

			$r[1] = '<b>' . $r[1] . '</b>';
			$aksi = '<div class="dropdown">
<button class="btn btn-wrapper" data-toggle="dropdown">
 <i class="fa fa-ellipsis-v"></i>
</button>
<div class="dropdown-menu dropdown-menu-right">';


			$sisa = $sisa + $r[4] + $r[5] - $r[6];
			$r[2] = $this->apl->tgl_format($r[2], 1);
			$r[3] = $this->apl->tgl_format($r[3], 1);

			$total_tagihan += $r[4];
			$total_denda += $r[5];
			$total_bayar += $r[6];

			if ($r[$jumlah - 2] == 1) {
				$td[2] = $r[4] + $r[5];
				$r[$jumlah - 2] = '<label class="btn btn-sm btn-info btn-block">Invoice</label>';
				$r[$jumlah - 1] = $aksi
					. anchor(
						'#modal_form',
						'<i class="fa fa-print"></i> Print',
						'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal" 
								class="print_data dropdown-item"'
					)
					. '</div>';
			}
			if ($r[$jumlah - 2] == 2) {
				$td[2] = $r[6];

				$r[$jumlah - 2] = '<label class="btn btn-sm btn-success btn-block">Payment</label>';
				$r[$jumlah - 1] = $aksi
					. anchor(
						URLADMIN.'share/ipl?id=' . urlencode(base64_encode($r[$jumlah-1])),
						'<i class="fa fa-print"></i> Print',
						'class="dropdown-item"  target="_blank" title="Print"'
					) . '</div>';
			}
			if ($r[$jumlah - 2] == 3) {
				$r[$jumlah - 2] = '<label class="btn btn-sm btn-warning btn-block">Credit Note</label>';
				$r[$jumlah - 1] = '';
				$td[2] = $r[6];

			}
			//$td[0] = '0';
			$td[0] = "<small>".$r[1] . "</small><br>" . $r[2] . '<br>' . $r[$jumlah - 2];
			$td[1] = '<span class="float-right">' . $this->apl->number_format($td[2], 1) . '</span>';
			$td[2] = $r[$jumlah - 1];
			$data_array[] = $td;
		}
		$output = array(
			"draw" => isset($_POST['draw']) ? $_POST['draw'] : '',
			"recordsTotal" => $record_total,
			"recordsFiltered" => $record_filter,
			"total_piutang" => $sisa,
			"total_tagihan" => $total_tagihan,
			"total_bayar" => $total_bayar,
			"total_denda" => $total_denda,
			"data" => $data_array,
		);
		header('Content-Type: application/json; charset=utf-8');
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET,POST');
		echo json_encode($output); //output to json format

	}



	public function cetak()
	{
		error_reporting(E_NOTICE);
		$id = $this->input->get('id');

		//echo "ID ADMIN : " . $this->session->id_admin . "<br>-------------";
		$id_kasir = $this->apl->get_nilai_pilih("bayar", "id_admin", array('id_bayar' => $id));
		$kasir = $this->apl->get_nilai_pilih(
			"admin",
			"id_karyawan",
			array('id_admin' => $id_kasir)
		);
		$uid = $this->apl->get_nilai_pilih(
			"admin",
			"id_karyawan",
			array('id_admin' => $this->session->id_admin)
		);
		$a = $this->apl->getSelectedData("karyawan", array('id_karyawan' => $uid))->row();
		if (isset($a)) {
			$ka = $this->apl->get_nilai_pilih("karyawan", "nama", array('id_karyawan' => $kasir));
			$data['ka'] = $ka;
			$val = "Kasir : " . $ka;
			$val .= " -- Print : " . $a->nama;
		} else {
			$data['ka'] = "Aplikasi Owner";
			$val = "Kasir : " . $kasir;
			$val .= " -- Print : " . "Aplikasi Owner";

		}
		$val .= " -- TM Print=" . date('Y-m-d H:i:s');
		$this->load->library('ciqrcode');
		$params['data'] = $val;
		$params['level'] = 'H';
		$params['size'] = 50;
		$params['cachedir'] = 'upload/qr_bayar/';
		$params['savename'] = 'upload/qr_bayar/inv-billing-' . $id . '-' . time() . ".png";
		$qr_code = $this->ciqrcode->generate($params);
		$this->apl->updateData('bayar', array('qr_code' => $qr_code,), array('id_bayar' => $id));

		$data['bayar'] = $this->apl->getSelectedData("bayar", array('id_bayar' => $id))->row();
		$data['b'] = $this->apl->getSelectedData(
			"billing_detail",
			array('id_bayar' => $id, 'status' => 2, 'hapus' => 0)
		)->row();
		$data['bast'] = $this->apl->getSelectedData("bast", array('id_bast' => $data['b']->id_bast, 'hapus' => 0))->row();
		$data['bast_sewa'] = $this->apl->getSelectedData(
			"bast_huni",
			array('id_bast' => $data['b']->id_bast, 'hapus' => 0, 'tipe' => 10, 'status' => 1)
		)->row();
		$data['unit'] = $this->apl->getSelectedData("db_unit", array('id_unit' => $data['bast']->id_unit))->row();
		$data['d'] = $this->bayar->getDataBillingTotal($data['bast']->id_unit)->row();
		$this->load->view('bayar/invoice/cetak', $data);
	}
}

?>
