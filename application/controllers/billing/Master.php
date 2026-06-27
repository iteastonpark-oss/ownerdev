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

class Master extends CI_Controller
{
	var $table = 'tagihan'; //Nama Database
	var $table_join = array(
		'db_tag' => array('db_tag.id_tag=tagihan.id_tag', 'inner')
	); //Inner Join

	var $primary = 'id'; //Primary Key ID
	var $order = array(
		'id' => 'asc',
	); //ORder Default
	var $column = array(
		'0 as No', 'kode as Bill Code', 'nama as Bill Name',
		'active as Active',
		'DATE_FORMAT(tanggal_ahir,"%Y-%m") as `Last Date`',
		'tagihan.jumlah as Default', 'id as Action'
	); // Nama Field yang akan di select  table
	var $column_order = array(
		null,
		'nama',
		null,
	);
	var $column_search = array('nama');
	var $where = array();
	var $group = '';
	var $modul = "master_tagihan_bast";

	/**
	 * Akhir
	 */


	public function __construct()
	{
		parent::__construct();
		$this->load->model('Crud_Model', 'crud_model');
		$this->crud_model = new Crud_Model();
		$this->apl = new Apl();
		$this->tombol = new Tombol();
		$this->where = array_merge(array('id_bast' => $this->input->get('id')), $this->where);
	}

	public function index()
	{
		$data['field'] = $this->crud_model->get_datatables()->list_fields(); //Nama Coloum Tabel

		$data['tombol_view'] = $this->apl->anchor(
			$this->tombol->get_tambah_js("add_data()"),
			'tambah_' . $this->modul,
			$this->modul
		);
		$data['judul'] = 'Bill Unit';
		$data['page'] = 'billing/tagihan/master'; //Halaman di tampilkan
		$this->load->view('home', $data);
	}


	public function ajax_list()
	{
		$list = $this->crud_model->get_datatables();
		$data_array = array();
		$no = isset($_POST['start']) ? $_POST['start'] : '0';
		$jumlah = count($this->column);
		foreach ($list->result_array() as $data) {
			$no++;
			$r = array_values($data);
			$r[0] = $no;
			$r[3] = '<span class="pull-right">' . $this->apl->number_format($r[3], 1) . '</span>';

			$r[$jumlah - 1] = '<div class="btn-group">'
				. $this->apl->anchor(
					$this->tombol->get_edit_js("edit_data('" . $r[$jumlah - 1] . "')"),
					'edit_' . $this->modul,
					$this->modul
				)
				/*
				. $this->apl->anchor(
					$this->tombol->get_hapus_js("delete_data('" . $r[$jumlah - 1] . "')"),
					'edit_' . $this->modul, $this->modul)
				*/
				. '</div>';

			$data_array[] = $r;
		}
		$output = array(
			"draw" => isset($_POST['draw']) ? $_POST['draw'] : '',
			"recordsTotal" => $this->crud_model->count_all(),
			"recordsFiltered" => $this->crud_model->count_filtered(),
			"data" => $data_array,
		);
		echo json_encode($output); //output to json format

	}


	public function ajax_add()
	{
		$urut = $this->apl->urut($this->table, $this->primary);
		$id_bast = $this->input->post('id_bast');
		$id_unit = $this->apl->get_nilai_pilih("bast", "id_unit", "id_bast=" . $id_bast);
		$periode = $this->apl->get_nilai_pilih(
			"db_tag",
			"periode",
			"id_tag=" . $this->input->post('id_tag')
		);

		$cek = $this->apl->getSelectedData("tagihan", array(
			'id_bast' => $id_bast,
			'id_tag' => $this->input->post('id_tag'),
		))->row();
		if (isset($cek)) {
			$data = array(
				'active' => $this->input->post('active'),
				'tanggal_awal' => $this->input->post('tanggal_ahir'),
				'tanggal_ahir' => $this->input->post('tanggal_ahir'),
				'jumlah' => $this->apl->number_format($this->input->post('jumlah')),
				'id_admin' => $this->session->id_admin,
			);
			$this->crud_model->update(array(
				'id_bast' => $id_bast,
				'id_tag' => $this->input->post('id_tag'),
			), $data);
		} else {
			$data = array(
				$this->primary => $urut,
				'id_bast' => $id_bast,
				'id_unit' => $id_unit,
				'id_tag' => $this->input->post('id_tag'),
				'active' => $this->input->post('active'),
				'periode' => $periode,
				'jumlah' => $this->apl->number_format($this->input->post('jumlah')),
				'tanggal_awal' => $this->input->post('tanggal_ahir'),
				'tanggal_ahir' => $this->input->post('tanggal_ahir'),
				'id_admin' => $this->session->id_admin,
			);
			$this->crud_model->save($data);
		}

		$this->apl->log(
			"Tambah",
			'',
			json_encode($data),
			$this->table,
			$urut
		);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_edit($id)
	{
		$data = $this->crud_model->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_update()
	{
		$data = array(
			'active' => $this->input->post('active'),
			'jumlah' => $this->apl->number_format($this->input->post('jumlah')),
			'tanggal_awal' => $this->input->post('tanggal_ahir'),
			'tanggal_ahir' => $this->input->post('tanggal_ahir'),
			'id_admin' => $this->session->id_admin,
		);
		$this->apl->log(
			"Edit",
			json_encode($this->apl->getSelectedData($this->table, array($this->primary => $this->input->post($this->primary)))->row()),
			json_encode($data),
			$this->table,
			$this->input->post($this->primary)
		);
		$this->crud_model->update(array($this->primary => $this->input->post($this->primary)), $data);
		echo json_encode(array("status" => TRUE));
	}
	public function set_tagihan()
	{

		$tanggal=$this->input->post('tanggal');
		$tanggal_ahir=date('Y-m-d', strtotime('-1 days', strtotime($tanggal.'-01')));
		$data = array(
			'tanggal_ahir' => $tanggal_ahir,
		);
		$this->apl->log(
			"Edit",
			json_encode($this->apl->getSelectedData($this->table, array($this->primary => $this->input->post($this->primary)))->row()),
			json_encode($data),
			$this->table,
			$this->input->post($this->primary)
		);
		$this->crud_model->update(array(
			//$this->primary => $this->input->post($this->primary)
			'id_bast' => $this->input->post('id_bast'),
		), $data);
		redirect('billing/master?id=' . $this->input->post('id_bast'));
	}

	public function ajax_delete($id)
	{
		$data = array(
			'hapus' => '1',
			'jumlah' => '0',

		);
		$this->apl->log(
			"Hapus",
			json_encode($this->apl->getSelectedData($this->table, array($this->primary => $id))->row()),
			json_encode($data),
			$this->table,
			$id
		);
		$this->crud_model->update(array($this->primary => $id), $data);
		echo json_encode(array("status" => TRUE));
	}
}

?>
