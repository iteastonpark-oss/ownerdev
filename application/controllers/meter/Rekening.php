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

class Rekening extends CI_Controller
{
	var $table = 'utility_rekening'; //Nama Database
	var $primary = 'id_rekening'; //Primary Key ID
	var $modul = "utility_rekening";

	/**
	 * Akhir
	 */


	public function __construct()
	{
		parent::__construct();
		$this->load->model('Crud_Model', 'crud_model');
		$this->crud_model = new Crud_Model();

		$this->load->model('Meter_Model', 'meter');
		$this->meter = new Meter_Model();

		$this->apl = new Apl();
		$this->tombol = new Tombol();
		$this->pesan = new Pesan();
	}

	public function view($tabs = 'air')
	{
		if ($tabs == 'air') {
			$data['id_tag'] = 4;
		}
		if ($tabs == 'listrik') {
			$data['id_tag'] = 6;
		}
		$data['tabs'] = $tabs;
		$data['tombol_view'] = $this->apl->anchor(
			$this->tombol->get_tambah_js("add_data()"),
			'tambah_' . $this->modul, $this->modul);
		$data['field'] = $this->meter->getRekening($data['id_tag'])->get()->list_fields(); //Nama Coloum Tabel


		$data['judul'] = 'Rekening';
		$data['page'] = 'utility/rekening/view'; //Halaman di tampilkan
		$this->load->view('home', $data);
	}


	public function ajax_list()
	{
		$id_tag = (isset($_POST['id_tag'])) ? $_POST['id_tag'] : '';

		$column_search = array(
			'`db_unit`.`kode`',
			'`utility_rekening`.`rekening`',
		);
		$column_order = array(
			null,
			'`db_unit`.`kode`',
			'`utility_rekening`.`rekening`',
			null,
			null,
		);

		$list = $this->crud_model->get_data(
			$this->meter->getRekening($id_tag), $column_search, $column_order);
		$record_total = $this->crud_model->get_jumlah(
			$this->meter->getRekening($id_tag));
		$record_filter = $this->crud_model->get_jumlah_filter(
			$this->meter->getRekening($id_tag), $column_search, $column_order);


		$no = isset($_POST['start']) ? $_POST['start'] : '0';
		$jumlah = $list->num_fields();
		$data_array = array();

		foreach ($list->result_array() as $data) {
			$no++;
			$r = array_values($data);
			$r[0] = $no;
			$r[$jumlah - 1] = '<div class="btn-group btn-group-xs">'

				. $this->apl->anchor(
					$this->tombol->get_edit_js("edit_data('" . $r[$jumlah - 1] . "')"),
					'edit_' . $this->modul, $this->modul)
				. $this->apl->anchor(
					$this->tombol->get_hapus_js("delete_data('" . $r[$jumlah - 1] . "')"),
					'edit_' . $this->modul, $this->modul)

				. '</div>';

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


	public function ajax_add()
	{
		$no_rekening = $this->input->post('no_rekening');
		if ($this->apl->getSelectedData($this->table, array(
			'rekening' => $no_rekening,
			'hapus' => 0,))->row()) {

			$this->pesan->pesan_warning("Failed to add water account "
				. $no_rekening
				. "<br> already in use");
		} else {
			$id_rekening = $this->apl->urut($this->table, $this->primary);
			$data = array(
				$this->primary => $id_rekening,
				'id_unit' => $this->input->post('id_unit'),
				'id_tag' => $this->input->post('id_tag'),
				'rekening' => $no_rekening,
				'id_admin' => $this->session->id_admin,
			);

			$this->apl->log("Tambah",
				'',
				json_encode($data),
				$this->table,
				$id_rekening);
			$this->crud_model->save($data);

			$id_meter = $this->apl->urut("utility", "id_meter");
			$data_meter = array(
				'id_meter' => $id_meter,
				'id_rekening' => $id_rekening,
				'bulan' => $this->input->post('bulan'),
				'tahun' => $this->input->post('tahun'),
				'meter' => $this->input->post('meter_awal'),
				'tanggal' => $this->input->post('tahun') . '-' . $this->input->post('bulan') . '-25',
				'pakai' => 0,
				'id_admin' => $this->session->id_admin,
				'awal' => 1,
				'post' => 1,
				'ket' => 'Aktivasi'
			);
			$this->apl->insertData("utility", $data_meter);
			$this->apl->log("TAMBAH METER AWAL", "", json_encode($data_meter), "meter", $id_meter);

		}
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_edit($id)
	{
		$data = $this->crud_model->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_update()
	{
		if ($this->apl->getSelectedData($this->table, array(
			'rekening' => $this->input->post('rekening'),
			'hapus' => 0,
			'id_rekening !=' => $this->input->post('id_rekening'),
		))->row()) {

			$this->pesan->pesan_warning("Failed to change the water account " . $this->input->post('rekening')
				. "<br> already in use");
		} else {

			$data = array(
				'rekening' => $this->input->post('rekening'),
				'id_admin' => $this->session->id_admin,
			);
			$this->apl->log("Edit",
				json_encode($this->apl->getSelectedData($this->table, array($this->primary => $this->input->post($this->primary)))->row()),
				json_encode($data),
				$this->table,
				$this->input->post($this->primary));
			$this->crud_model->update(array($this->primary => $this->input->post($this->primary)), $data);
		}
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$data = array(
			'hapus' => '1',

		);
		$this->apl->log("Hapus",
			json_encode($this->apl->getSelectedData($this->table, array($this->primary => $id))->row()),
			json_encode($data),
			$this->table, $id);
		$this->crud_model->update(array($this->primary => $id), $data);
		echo json_encode(array("status" => TRUE));
	}

}

?>
