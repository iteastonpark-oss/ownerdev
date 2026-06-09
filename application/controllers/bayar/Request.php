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

class Request extends CI_Controller
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

	public function page($request)
	{
		$unit = $this->input->get('id_unit');
		$data['id_unit'] = (isset($_POST['id_unit'])) ? $_POST['id_unit'] : $unit;
		$data['tombol_view'] = ($data['id_unit'] == '') ? ''
			: '<div class="btn-group pull-right">'
			. anchor('#modal_form',
				'<i class="fa fa-plus"></i> Add Payment',
				'data-id="' . $data['id_unit'] . '" data-toggle="modal" 
				class="bayar btn btn-danger"')
			. '</div >';
		$data['tabs'] = 'bayar';
		$data['request'] = $request;
		$data['tipe'] = $this->apl->get_nilai_pilih("tiket_tipe", "id", array('link' => $request));
		$data['nama'] = $this->apl->get_nilai_pilih("tiket_tipe", "nama", array('link' => $request));
		$total = $this->bayar->getDataBillingTiketTotal($data['tipe'])->row();
		$data['total'] = (isset($total)) ? $total->total : 0;
		$data['field'] = $this->bayar->getDataBillingTiket($data['tipe'])->get()->list_fields();
		$data['page'] = 'bayar/tiket/view'; //Halaman di tampilkan
		$data['judul'] = 'Request ' . $data['nama'];

		$q = $this->bayar->getDataBillingTiket($data['tipe'])->get_compiled_select();
		$data['tot'] = $this->db->select('COUNT(bill.No) as total')
			->from('(' . $q . ') bill')->get()->row()->total;

		$this->load->view('home', $data);
	}

	public function ajax_list()
	{

		$tipe = $this->input->post('tipe');
		$column_search = array('db_unit.kode', 'no_form');
		$column_order = array(
			null,
			null,
			null,
			null,
			null,
			null,
		);

		$list = $this->crud_model->get_data(
			$this->bayar->getDataBillingTiket($tipe), $column_search, $column_order);
		$record_total = $this->crud_model->get_jumlah(
			$this->bayar->getDataBillingTiket($tipe));
		$record_filter = $this->crud_model->get_jumlah_filter(
			$this->bayar->getDataBillingTiket($tipe), $column_search, $column_order);


		$no = isset($_POST['start']) ? $_POST['start'] : '0';
		$jumlah = $list->num_fields();
		$data_array = array();
		foreach ($list->result_array() as $data) {
			$no++;
			$r = array_values($data);
			$r[0] = $no;
			$r[$jumlah - 1] = '<div class="btn-group btn-group-sm pull-right">'
				. $this->apl->anchor(anchor('#modal_form',
						'<i class="fa fa-bank"></i> Pay',
						'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal" class="bayar btn btn-danger"')
					, 'tambah ' . $this->modul, $this->modul)
				. anchor('#modal_form'
					, '<i class="fa fa-search"></i> Detail'
					, 'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="detail btn btn-info"  title="Detail"')

				. '</div>';

			$r[4] = $this->apl->tgl_format($r[4], 1);
			$r[5] = '<span class="pull-right">' . $this->apl->number_format($r[5], 1) . '</span>';

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

	public function report($request)
	{

		$data['tabs'] = 'report';
		$data['request'] = $request;
		$unit = $this->input->get('id_unit');
		$data['id_unit'] = (isset($_POST['id_unit'])) ? $_POST['id_unit'] : $unit;


		$data['tanggal_awal'] = isset($_POST['tanggal_awal']) ? $_POST['tanggal_awal']
			: date('Y-m-01');
		$data['tanggal_ahir'] = isset($_POST['tanggal_ahir']) ? $_POST['tanggal_ahir']
			: date('Y-m-d');

		$data['tombol_view'] = '<a href="' . site_url('bayar/request/export_csv/' . $request . '?id_unit=' . $data['id_unit']
				. '&tanggal_awal=' . $data['tanggal_awal'] . '&tanggal_ahir=' . $data['tanggal_ahir']) . '" 
class="btn btn-info">
<i class="fa fa-file-excel"></i> Export</a>';


		$data['tipe'] = $this->apl->get_nilai_pilih("tiket_tipe", "id", array('link' => $request));
		$data['nama'] = $this->apl->get_nilai_pilih("tiket_tipe", "nama", array('link' => $request));

		$data['field'] = $this->bayar->getDataBayarTiket($data['tipe'], $data['id_unit'], $data['tanggal_awal'], $data['tanggal_ahir'])->get()->list_fields();
		$data['jumlah'] = $this->bayar->getDataBayarTiketJumlah($data['tipe'], $data['id_unit'], $data['tanggal_awal'], $data['tanggal_ahir']);
		$data['page'] = 'bayar/tiket/report'; //Halaman di tampilkan
		$data['judul'] = 'Report ' . $data['nama'];
		$q = $this->bayar->getDataBillingTiket($data['tipe'])->get_compiled_select();
		$data['tot'] = $this->db->select('COUNT(bill.No) as total')
			->from('(' . $q . ') bill')->get()->row()->total;
		$this->load->view('home', $data);
	}

	public function export_csv($request)
	{
		$tipe = $this->apl->get_nilai_pilih("tiket_tipe", "id", array('link' => $request));
		$result = $this->bayar->getDataBayarTiket($tipe, $_GET['id_unit'], $_GET['tanggal_awal'], $_GET['tanggal_ahir'])->get();
		$this->apl->export_excell($result, " DataBayar" . $_GET['tanggal_awal'] . 's.d' . $_GET['tanggal_ahir']);
	}

	public function ajax_list_report()
	{

		$tipe = $this->input->post('tipe');
		$id_unit = (isset($_POST['id_unit'])) ? $_POST['id_unit'] : '';
		$tanggal_awal = $this->input->post('tanggal_awal');
		$tanggal_ahir = $this->input->post('tanggal_ahir');

		$column_search = array('kwt', 'db_unit.kode', 'bayar.tanggal', 'tiket_detail.jumlah', 'db_via.nama');
		$column_order = array(
			null,
			'kwt',
			'db_unit.kode',
			'bayar.tanggal',
			'tiket_detail.jumlah',
			'db_via.nama',
			null,
		);

		$list = $this->crud_model->get_data(
			$this->bayar->getDataBayarTiket($tipe, $id_unit, $tanggal_awal, $tanggal_ahir), $column_search, $column_order);
		$record_total = $this->crud_model->get_jumlah(
			$this->bayar->getDataBayarTiket($tipe, $id_unit, $tanggal_awal, $tanggal_ahir));
		$record_filter = $this->crud_model->get_jumlah_filter(
			$this->bayar->getDataBayarTiket($tipe, $id_unit, $tanggal_awal, $tanggal_ahir), $column_search, $column_order);


		$no = isset($_POST['start']) ? $_POST['start'] : '0';
		$jumlah = $list->num_fields();
		$data_array = array();

		$sisa = 0;
		foreach ($list->result_array() as $data) {
			$no++;
			$r = array_values($data);
			$r[0] = $no;
			$r[4] = '<span class="float-right">' . $this->apl->number_format($r[4], 1) . '</span>';

			$aksi = '<div class="dropdown">'
				. '<button class="btn btn-secondary" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
				. '<i class="fa fa-ellipsis-v"></i>'
				. '</button>'
				. '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">'

				. $this->apl->anchor(
					anchor('#modal_form'
						, '<i class="fa fa-edit"></i> Edit'
						, 'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="edit_bayar dropdown-item"  title="Tambah Pembayaran"'),
					'edit_' . $this->modul, $this->modul)
				. anchor(site_url('bayar/request/cetak?id=' . $r[$jumlah - 1])
					, '<i class="fa fa-print"></i> Print'
					, 'class="dropdown-item"  target="_blank" title="Print"')
				.anchor(
					'#modal_form',
					'<i class="fa fa-whatsapp"></i> Whatsapp',
					'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal" 
								class="whatsapp dropdown-item"'
				)

				. $this->tombol->get_hapus_js_dropdown('delete_data(' . $r[$jumlah - 1] . ')');
			$r[$jumlah - 1] = $aksi . '</div>';
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

	public function form_bayar($id)
	{
		$data['submit'] = 'tambah';
		$data['tiket'] = $this->apl->getSelectedData("tiket", array('id_tiket' => $id))->row();
		$tagihan = $this->apl->get_nilai_pilih("tiket_detail"
			, "(SUM(jumlah)+SUM(deposit)+SUM(material))", array('id_tiket' => $id, 'tipe' => 1, 'hapus' => 0));

		$bayar = $this->apl->get_nilai_pilih("tiket_detail"
			, "(SUM(jumlah)+SUM(deposit)+SUM(material))", array('id_tiket' => $id, 'tipe' => 2, 'hapus' => 0));

		$data['tagihan'] = $tagihan - $bayar;
		$data['detail'] = $this->apl->getSelectedData("tiket_detail",
			array('id_tiket' => $id, 'tipe' => 1, 'hapus' => 0))->row();
		$this->load->view('bayar/tiket/payment', $data);

	}

	function ajax_add_bayar()
	{
		$id_tiket = $this->input->post('id_tiket');
		$tipe = $this->apl->get_nilai_pilih("tiket", "tipe",
			"id_tiket=" . $this->input->post('id_tiket'));
		$nama_kwt = $this->apl->get_nilai_pilih("tiket_tipe", "kwt", array('id' => $tipe));

		$kwt = $this->apl->counter($nama_kwt) . "/" . $nama_kwt . "/" . $this->apl->bulan_romawi() . "/" . date('Y');
		$id_bayar = $this->apl->urut('bayar', 'id_bayar');
		$id_bast = $this->apl->get_nilai_pilih("tiket", "id_bast", "id_tiket=" . $id_tiket);
		$data_detail = array(
			'id_bayar' => $id_bayar,
			'id_bast' => $id_bast,
			'nok' => $kwt,
			'kwt' => $kwt,
			'jumlah' => $this->apl->number_format($this->input->post('bayar')),
			'tanggal' => $this->input->post('tanggal'),
			'id_via' => $this->input->post('id_via'),
			'id_admin' => $this->session->id_admin,
		);
		$this->apl->insertData("bayar", $data_detail);
		$this->apl->log("TAMBAH BAYAR"
			, ""
			, json_encode($data_detail)
			, "bayar"
			, $id_bayar
		);

		foreach ($this->apl->getSelectedData("tiket_detail", array(
			'id_tiket' => $id_tiket,
			'tipe' => 1,
			'hapus' => 0,
			'tahap_bayar' => 0,
		))->result() as $d) {

			$this->apl->insertData("tiket_detail", array(
				'ket' => $this->input->post('ket'),
				'id_tiket' => $id_tiket,
				'id_tag' => $d->id_tag,
				'tipe' => 2,
				'nama' => $d->nama,
				'jumlah' => $d->jumlah,
				'deposit' => $d->deposit,
				'harga_satuan' => $d->harga_satuan,
				'material' => $d->material,
				'id_bayar' => $id_bayar,
			));
		}
		//$this->apl->updateData("tiket", array('post' => 3), array('id_tiket' => $id_tiket));

		$uid = $this->apl->get_nilai_pilih("admin", "id_karyawan",
			array('id_admin' => $this->session->id_admin));
		$a = $this->apl->getSelectedData("karyawan", array('id_karyawan' => $uid))->row();
		$val = "Nama : " . $a->nama;
		$val .= " -- Jumlah Bayar =" . $this->input->post('bayar');
		$val .= " -- NO KWT =" . $kwt;
		$val .= " -- Tanggal =" . date('Y-m-d');
		$val .= " -- TM =" . date('Y-m-d H:i:s');
		$val .= " -- TTD =" . site_url('karyawan/signature?id=' . $uid);
		$this->load->library('ciqrcode');
		$params['data'] = $val;
		$params['level'] = 'H';
		$params['size'] = 50;
		$params['cachedir'] = 'upload/qr_tiket/';
		$params['savename'] = 'upload/qr_tiket/signature-' . $id_tiket . time() . ".png";
		$qr_code = $this->ciqrcode->generate($params);
		$data = array(
			'note' => 'PAYMENT REQUEST',
			'tanggal' => $this->input->post('tanggal'),
			'id_tiket' => $id_tiket,
			'id_admin' => $this->session->id_admin,
			'jab' => 0,
			'post' => 2,
			'qr_code' => $qr_code,
		);
		$this->apl->insertData('tiket_approv', $data);


		echo json_encode(array("status" => TRUE));


	}


	public function update_bayar($id)
	{
		$data['submit'] = 'edit';
		$data['bayar'] = $this->apl->getSelectedData("bayar", array('id_bayar' => $id))->row();
		$data['b'] = $this->apl->getSelectedData("tiket_detail",
			array('id_bayar' => $id, 'tipe' => 2, 'hapus' => 0))->row();
		$data['tiket'] = $this->apl->getSelectedData("tiket", array('id_tiket' => $data['b']->id_tiket))->row();
		$this->load->view('bayar/tiket/payment', $data);

	}

	function ajax_update_bayar()
	{
		$id_bayar = $this->input->post('id_bayar');
		$data_detail = array(
			'tanggal' => $this->input->post('tanggal'),
			'jumlah' => $this->apl->number_format($this->input->post('bayar')),
			'id_via' => $this->input->post('id_via'),
			'id_admin' => $this->session->id_admin,
		);
		$this->apl->log("UPDATE BAYAR"
			, json_encode($this->apl->getSelectedData("bayar", array('id_bayar' => $id_bayar))->row())
			, json_encode($data_detail)
			, "bayar"
			, $id_bayar
		);
		$this->apl->updateData("bayar", $data_detail, array('id_bayar' => $id_bayar));

		/*
		$this->apl->updateData("tiket_detail", array(
			'ket' => $this->input->post('ket'),
			'jumlah' => $this->apl->number_format($this->input->post('bayar')),
		), array('id_bayar' => $id_bayar, 'hapus' => 0));
		*/
		echo json_encode(array("status" => TRUE));


	}

	public function cetak()
	{
		$id = $this->input->get('id');

		$id_kasir = $this->apl->get_nilai_pilih("bayar", "id_admin", array('id_bayar' => $id));
		$kasir = $this->apl->get_nilai_pilih("admin", "id_karyawan",
			array('id_admin' => $id_kasir));
		$uid = $this->apl->get_nilai_pilih("admin", "id_karyawan",
			array('id_admin' => $this->session->id_admin));
		$a = $this->apl->getSelectedData("karyawan", array('id_karyawan' => $uid))->row();
		$ka = $this->apl->get_nilai_pilih("karyawan", "nama", array('id_karyawan' => $kasir));
		$data['ka'] = $ka;
		//echo $data['ka'];
		//die();
		$val = "Kasir : " . $ka;
		$val .= " -- Print : " . $a->nama;
		$val .= " -- TM Print=" . date('Y-m-d H:i:s');
		$this->load->library('ciqrcode');
		$params['data'] = $val;
		$params['level'] = 'H';
		$params['size'] = 50;
		$params['cachedir'] = 'upload/qr_bayar/';
		$params['savename'] = 'upload/qr_bayar/inv-tiket-' . $id . '-' . time() . ".png";
		$qr_code = $this->ciqrcode->generate($params);
		$this->apl->updateData('bayar', array('qr_code' => $qr_code,), array('id_bayar' => $id));


		$data['bayar'] = $this->apl->getSelectedData("bayar", array('id_bayar' => $id))->row();
		$data['b'] = $this->apl->getSelectedData("tiket_detail",
			array('id_bayar' => $id, 'tipe' => 2, 'hapus' => 0))->row();
		$data['tiket'] = $this->apl->getSelectedData("tiket", array('id_tiket' => $data['b']->id_tiket))->row();
		$this->load->view('bayar/tiket/cetak', $data);

	}


	public function ajax_delete($id)
	{
		$data = array(
			'hapus' => '1',
		);
		$this->apl->log("Hapus",
			json_encode($this->apl->getSelectedData("bayar", array('id_bayar' => $id))->row()),
			json_encode($data),
			'bayar', $id);
		$this->apl->updateData("bayar", $data, array('id_bayar' => $id));
		$this->apl->updateData("tiket_detail", $data, array('id_bayar' => $id, 'hapus' => 0));
		echo json_encode(array("status" => TRUE));
	}
}

?>
