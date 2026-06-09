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

class Listrik extends CI_Controller
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

	public function index()
	{
		$unit = $this->input->get('id_unit');
		$data['id_unit'] = (isset($_POST['id_unit'])) ? $_POST['id_unit'] : $unit;
		$data['id_tag'] = (isset($_POST['id_tag'])) ? $_POST['id_tag'] : '6000';

		$data['tahun'] = isset($_POST['tahun']) ? $_POST['tahun']
			: date('Y');
		/*
		$data['tanggal_awal'] = date($data['bulan'] . '-01');
		$ta = date('Y-m-d', strtotime('+1 month', strtotime($data['tanggal_awal'])));
		$ta = date('Y-m-d', strtotime('-1 days', strtotime($ta)));
		$data['tanggal_ahir'] = $ta;
		*/
		$data['tanggal_awal']=date($data['tahun'].'-01-01');
		$data['tanggal_ahir']=date($data['tahun'].'-12-31');
		$data['tombol_view'] = '';

		$data['field'] = $this->bayar->getDataBayarListrik($data['id_unit'], $data['id_tag'], $data['tanggal_awal'], $data['tanggal_ahir'])->get()->list_fields();
		$data['jumlah'] = $this->bayar->getDataBayarListrikJumlah($data['id_unit'], $data['id_tag'], $data['tanggal_awal'], $data['tanggal_ahir']);
		$data['page'] = 'bayar/listrik/view'; //Halaman di tampilkan
		$data['judul'] = 'Payment Electricity';
		$this->load->view('home', $data);

	}
	

	public function export_csv($id_tag)
	{
		$result = $this->bayar->getDataBayarListrik($_GET['id_unit'], $id_tag, $_GET['tanggal_awal'], $_GET['tanggal_ahir'])->get();
		$this->apl->export_excell($result, " DataBayar" . $_GET['tanggal_awal'] . 's.d' . $_GET['tanggal_ahir']);
	}

	public function ajax_list()
	{

		$id_unit = (isset($_POST['id_unit'])) ? $_POST['id_unit'] : '';
		$id_tag = (isset($_POST['id_tag'])) ? $_POST['id_tag'] : '';
		$tanggal_awal = $this->input->post('tanggal_awal');
		$tanggal_ahir = $this->input->post('tanggal_ahir');

		$column_search = array('bast.kode', 'kwt', 'bayar.tanggal', 'bayar.jumlah', 'db_via.nama');
		$column_order = array(
			null,
			null,
			null,
			'bayar.nok',
			'bayar.tanggal',
			'bayar_lainnya.jumlah',
			'db_via.nama',
			'bayar_lainnya.value',
			null,
		);

		$list = $this->crud_model->get_data(
			$this->bayar->getDataBayarListrik($id_unit, $id_tag, $tanggal_awal, $tanggal_ahir), $column_search, $column_order);
		$record_total = $this->crud_model->get_jumlah(
			$this->bayar->getDataBayarListrik($id_unit, $id_tag, $tanggal_awal, $tanggal_ahir));
		$record_filter = $this->crud_model->get_jumlah_filter(
			$this->bayar->getDataBayarListrik($id_unit, $id_tag, $tanggal_awal, $tanggal_ahir), $column_search, $column_order);


		$no = isset($_POST['start']) ? $_POST['start'] : '0';
		$jumlah = $list->num_fields();
		$data_array = array();

		$sisa = 0;
		$td=array();
		foreach ($list->result_array() as $data) {
			$no++;
			$r = array_values($data);
			$r[0] = $no;
			$r[5] = '<span class="pull-right">' . $this->apl->number_format($r[5], 1) . '</span>';
			$aksi = '<div class="dropdown">'
				. '<button class="btn btn-secondary" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
				. '<i class="fa fa-ellipsis-v"></i>'
				. '</button>'
				. '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">'
					. anchor(URLADMIN . 'share/payment_listrik?id=' . urlencode(base64_encode($r[$jumlah - 1]))
					, '<i class="fa fa-print"></i> Print'
					, 'class="dropdown-item"  target="_blank" title="Print"');
			$r[$jumlah - 1] = $aksi . '</div>';
			$td[0]=$no;
			$td[1]=$r[3]."<br>".$r[4];
			$td[2]=$r[5];
			$td[3]=$r[$jumlah-1];
			$data_array[] = $td;

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
	public function ajax_list_lain()
	{

		$id_unit = (isset($_POST['id_unit'])) ? $_POST['id_unit'] : '';
		$id_tag = (isset($_POST['id_tag'])) ? $_POST['id_tag'] : '';
		$tanggal_awal = $this->input->post('tanggal_awal');
		$tanggal_ahir = $this->input->post('tanggal_ahir');

		$column_search = array('db_unit.kode', 'kwt', 'bayar.tanggal', 'bayar.jumlah', 'db_via.nama');
		$column_order = array(
			null,
			null,
			null,
			'bayar.nok',
			'bayar.tanggal',
			'bayar_lainnya.jumlah',
			'db_via.nama',
			null,
		);

		$list = $this->crud_model->get_data(
			$this->bayar->getDataBayarLain($id_unit, $id_tag, $tanggal_awal, $tanggal_ahir), $column_search, $column_order);
		$record_total = $this->crud_model->get_jumlah(
			$this->bayar->getDataBayarLain($id_unit, $id_tag, $tanggal_awal, $tanggal_ahir));
		$record_filter = $this->crud_model->get_jumlah_filter(
			$this->bayar->getDataBayarLain($id_unit, $id_tag, $tanggal_awal, $tanggal_ahir), $column_search, $column_order);


		$no = isset($_POST['start']) ? $_POST['start'] : '0';
		$jumlah = $list->num_fields();
		$data_array = array();

		$sisa = 0;
		foreach ($list->result_array() as $data) {
			$no++;
			$r = array_values($data);
			$r[0] = $no;
			$r[5] = '<span class="pull-right">' . $this->apl->number_format($r[5], 1) . '</span>';
			$aksi = '<div class="dropdown">'
				. '<button class="btn btn-secondary" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
				. '<i class="fa fa-ellipsis-v"></i>'
				. '</button>'
				. '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">'
				. $this->apl->anchor(
					anchor('#modal_form'
						, '<i class="fa fa-edit"></i> Edit'
						, 'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="edit_bayar btn dropdown-item"  title="Edit Pembayaran"'),
					'edit_' . $this->modul, $this->modul)
				. $this->apl->anchor(
					$this->tombol->get_hapus_js_dropdown("delete_data('" . $r[$jumlah - 1] . "')"),
					'hapus_' . $this->modul, $this->modul)
				. anchor(site_url('bayar/lainnya/cetak?id=' . $r[$jumlah - 1])
					, '<i class="fa fa-print"></i> Print'
					, 'class="dropdown-item"  target="_blank" title="Print"');
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

	public function form_bayar()
	{
		$data['submit'] = 'tambah';
		$this->load->view('bayar/listrik/payment', $data);

	}

	public function form_edit($id)
	{
		$data['submit'] = 'edit';
		$data['b'] = $this->db->select('bayar.*,bayar_lainnya.id_tag,value,admin,tax,harga_dasar')
			->from('bayar_lainnya')->join('bayar', 'bayar.id_bayar=bayar_lainnya.id_bayar')
			->where(
				array(
					'bayar.hapus' => 0,
					'bayar_lainnya.hapus' => 0,
					'bayar_lainnya.id_bayar' => $id,
				)

			)->get()->row();
		$this->load->view('bayar/listrik/payment', $data);

	}

	function ajax_add_bayar()
	{
		$id_tag = $this->input->post('id_tag');
		$id_bast = $this->input->post('id_bast');

		$t = $this->apl->getSelectedData("db_tag", array('id_tag' => $id_tag))->row();
		$kwt = $this->apl->counter("Kwt-" . $t->kode) . "/Kwt-" . $t->kode . "/"
			. $this->apl->bulan(date('m', strtotime($this->input->post('tanggal'))), 4)
			. "/" . date('Y', strtotime($this->input->post('tanggal')));


		$check = $this->apl->checkData("bayar", array(
			'id_bast' => $this->input->post('id_bast'),
			'jumlah' => $this->apl->number_format($this->input->post('bayar')),
			'tanggal' => $this->input->post('tanggal'),
			'id_via' => $this->input->post('id_via'),
			'ket' => $this->input->post('ket'),
			'hapus' => 0,
			'id_admin' => $this->session->id_admin,

		));

		if ($check == 0) {
			$id_bayar = $this->apl->urut('bayar', 'id_bayar');

			$data_detail = array(
				'id_bayar' => $id_bayar,
				'id_bast' => $id_bast,
				'nok' => $kwt,
				'kwt' => $kwt,
				'jumlah' => $this->apl->number_format($this->input->post('bayar')),
				'tanggal' => $this->input->post('tanggal'),
				'id_via' => $this->input->post('id_via'),
				'ket' => $this->input->post('ket'),
				'id_admin' => $this->session->id_admin,
			);
			$this->apl->insertData("bayar", $data_detail);
			$this->apl->log("TAMBAH BAYAR"
				, ""
				, json_encode($data_detail)
				, "bayar"
				, $id_bayar
			);

			$this->apl->insertData("bayar_lainnya", array(
				'id_lainnya' => $this->apl->urut('bayar_lainnya', 'id_lainnya'),
				'id_bast' => $id_bast,
				'value' => $this->input->post('value'),
				'tanggal' => $this->input->post('tanggal'),
				'id_bayar' => $id_bayar,
				'harga_dasar' => $t->jumlah,
				'id_tag' => $t->id_tag,
				'ket' => $t->nama,
				'note' => $this->input->post('ket'),
				'admin' => $this->apl->number_format($this->input->post('admin')),
				'tax' => $this->apl->number_format($this->input->post('tax')),

				'id_admin' => $this->session->id_admin,

			));
		}

		echo json_encode(array("status" => TRUE));


	}


	function ajax_update_bayar()
	{
		$id_tag = $this->input->post('id_tag');
		$id_bast = $this->input->post('id_bast');
		$t = $this->apl->getSelectedData("db_tag", array('id_tag' => $id_tag))->row();
		$id_bayar = $this->input->post('id_bayar');
		$data_detail = array(
			'tanggal' => $this->input->post('tanggal'),
			'jumlah' => $this->apl->number_format($this->input->post('bayar')),
			'id_via' => $this->input->post('id_via'),
			'ket' => $this->input->post('ket'),
			'id_bast' => $id_bast,
			'id_admin' => $this->session->id_admin,
		);
		$this->apl->log("UPDATE BAYAR"
			, json_encode($this->apl->getSelectedData("bayar", array('id_bayar' => $id_bayar))->row())
			, json_encode($data_detail)
			, "bayar"
			, $id_bayar
		);
		$this->apl->updateData("bayar", $data_detail, array('id_bayar' => $id_bayar));


		$t = $this->apl->getSelectedData("db_tag", array('id_tag' => $id_tag))->row();

		$this->apl->updateData("bayar_lainnya", array(
			'id_bast' => $id_bast,
			'value' => $this->input->post('value'),
			'tanggal' => $this->input->post('tanggal'),
			'harga_dasar' => $t->jumlah,
			'id_tag' => $t->id_tag,
			'admin' => $this->apl->number_format($this->input->post('admin')),
			'tax' => $this->apl->number_format($this->input->post('tax')),

			'ket' => $t->nama,
			'note' => $this->input->post('ket'),
		), array('id_bayar' => $id_bayar, 'hapus' => 0));

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
		$data['ka']=$ka;
		$val = "Kasir : " . $ka;
		$val .= " -- Print : " . $a->nama;
		$val .= " -- TM Print=" . date('Y-m-d H:i:s');
		$this->load->library('ciqrcode');
		$params['data'] = $val;
		$params['level'] = 'H';
		$params['size'] = 50;
		$params['cachedir'] = 'upload/qr_bayar/';
		$params['savename'] = 'upload/qr_bayar/inv-lainnya-' . $id . '-' . time() . ".png";
		$qr_code = $this->ciqrcode->generate($params);
		$this->apl->updateData('bayar', array('qr_code' => $qr_code,), array('id_bayar' => $id));


		$data['bayar'] = $this->apl->getSelectedData("bayar", array('id_bayar' => $id))->row();
		$data['l'] = $this->apl->getSelectedData("bayar_lainnya",
			array('id_bayar' => $id, 'hapus' => 0))->row();
		$id_unit = $this->apl->get_nilai_pilih("bast", "id_unit", "id_bast=" . $data['bayar']->id_bast);
		$data['unit'] = $this->apl->get_nilai_pilih("db_unit", "kode", "id_unit=" . $id_unit);

		$this->load->view('bayar/listrik/cetak', $data);

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
		$this->apl->updateData("bayar_lainnya", $data, array('id_bayar' => $id));
		echo json_encode(array("status" => TRUE));
	}
}

?>
