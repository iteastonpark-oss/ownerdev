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

class Utility extends CI_Controller
{
	var $table = 'utility'; //Nama Database
	var $primary = 'id_meter'; //Primary Key ID
	var $modul = "utility";

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
		$data['bulan'] = isset($_POST['bulan']) ? $_POST['bulan'] : '';
		$data['tahun'] = isset($_POST['tahun']) ? $_POST['tahun'] : date('Y');

		$data['tabs'] = $tabs;
		$data['tombol_view'] = '<div class="btn-group">'
			. $this->apl->anchor(
				$this->tombol->get_tambah_js("add_data()"),
				'tambah_' . $this->modul, $this->modul)
			. $this->apl->anchor(anchor(site_url('meter/utility/import/' . $data['id_tag']), '<i class="fa fa-upload"></i> Import'
				, 'class="btn btn-info"'), 'tombol import ' . $this->modul, $this->modul)
			. '</div>';
		$data['field'] = $this->meter->getMeterRupiah($data['id_tag'], $data['bulan'], $data['tahun'])->get()->list_fields(); //Nama Coloum Tabel
		$data['judul'] = 'History Utility';
		$data['page'] = 'utility/meter/view'; //Halaman di tampilkan
		$this->load->view('home', $data);
	}

	public function rekap($tabs = 'air')
	{
		if ($tabs == 'air') {
			$data['id_tag'] = 4;
		}
		if ($tabs == 'listrik') {
			$data['id_tag'] = 6;
		}

		$data['tabs'] = $tabs;
		$data['tombol_view'] = '';
		$data['tahun'] = isset($_POST['tahun']) ? $_POST['tahun'] : date('Y');
		$data['field'] = $this->meter->getMeterRekap($data['id_tag'], $data['tahun'])->get()->list_fields();
		$data['judul'] = "Data Rekap";
		$data['page'] = 'utility/meter/rekap';
		$this->load->view('home', $data);
	}

	public function ajax_list()
	{
		$id_tag = (isset($_POST['id_tag'])) ? $_POST['id_tag'] : '';
		$bulan = isset($_POST['bulan']) ? $_POST['bulan'] : date('m');
		$tahun = isset($_POST['tahun']) ? $_POST['tahun'] : date('Y');

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
			$this->meter->getMeterRupiah($id_tag, $bulan, $tahun), $column_search, $column_order);
		$record_total = $this->crud_model->get_jumlah(
			$this->meter->getMeterRupiah($id_tag, $bulan, $tahun));
		$record_filter = $this->crud_model->get_jumlah_filter(
			$this->meter->getMeterRupiah($id_tag, $bulan, $tahun), $column_search, $column_order);


		$no = isset($_POST['start']) ? $_POST['start'] : '0';
		$jumlah = $list->num_fields();
		$data_array = array();

		foreach ($list->result_array() as $data) {
			$no++;
			$r = array_values($data);
			$r[0] = $no;
			/*
			$awal = $this->apl->get_nilai_pilih("utility", "awal", "id_meter=" . $r[$jumlah - 1]);

			$aksi = '<div class="dropdown">'
				. '<button class="btn btn-secondary" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
				. '<i class="fa fa-ellipsis-v"></i>'
				. '</button>'
				. '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">';
			$aksi .= anchor('#modal_form'
				, '<i class="fa fa-search"></i> Detail'
				, 'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="detail btn dropdown-item"  title="Detail"');

			$r[$jumlah - 1] = $aksi . '</div>';
			*/
			$r[4]="<span class='float-right'>".$this->apl->number_format($r[4],1).'</span>';

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

	public function ajax_list_rekap()
	{
		$tahun = isset($_POST['tahun']) ? $_POST['tahun'] : '2020';
		$id_tag = isset($_POST['id_tag']) ? $_POST['id_tag'] : '4';

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
		error_reporting('E_NOTICE');

		$list = $this->crud_model->get_data(
			$this->meter->getMeterRekap($id_tag, $tahun), $column_search, $column_order);
		$record_total = $this->crud_model->get_jumlah(
			$this->meter->getMeterRekap($id_tag, $tahun));
		$record_filter = $this->crud_model->get_jumlah_filter(
			$this->meter->getMeterRekap($id_tag, $tahun), $column_search, $column_order);

		$no = isset($_POST['start']) ? $_POST['start'] : '0';
		$jumlah = $list->num_fields();
		$total_kons = array();

		$data_array = array();
		foreach ($list->result_array() as $data) {
			$no++;
			$r = array_values($data);
			$r[0] = $no;
			$r[1] = '<b>' . $r[1] . '</b>';
			$r[2] = '<b>' . $r[2] . '</b>';

			for ($i = 1; $i <= 24; $i++) {
				$total_kons[$i] += $r[$i + 3];
				if ($r[$i + 3] == '') {
					$r[$i + 3] = "-";
				}
				if ($i % 2 == 0) {
					if ($r[$i + 3] > 5)
						$r[$i + 3] = '<b class="text-danger">' . $r[$i + 3] . '</b>';
					else
						$r[$i + 3] = '<b class="text-info">' . $r[$i + 3] . '</b>';

				}

			}

			$data_array[] = $r;
		}


		for ($i = 1; $i <= 24; $i++) {
			$total_kons[$i] = number_format($total_kons[$i]) . " M3";
		}
		$output = array(
			"draw" => isset($_POST['draw']) ? $_POST['draw'] : '',
			"total_kons" => $total_kons,
			"recordsTotal" => $record_total,
			"recordsFiltered" => $record_filter,
			"data" => $data_array,
		);
		header('Content-Type: application/json; charset=utf-8');
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET,POST');
		echo json_encode($output);//output to json format

	}

	public function ajax_add_meter()
	{
		$data['submit'] = 'tambah';
		$data['id_tag'] = $this->input->post('id_tag');
		$data['bulan'] = $this->input->post('bulan');
		$data['tahun'] = $this->input->post('tahun');
		$data['link_file'] = '';
		$this->load->view('utility/meter/form', $data);

	}

	public function ajax_update_meter($id)
	{
		$data['submit'] = 'edit';
		$data['id_tag'] = $this->input->post('id_tag');
		$data['m'] = $this->crud_model->get_by_id($id);
		$data['link_file'] = $this->upload_model->link($data['m']->file, "meter");
		$this->load->view('utility/meter/form', $data);
	}

	public function ajax_add()
	{
		$id_rekening = $this->input->post('id_rekening');
		$no_rekening = $this->apl->get_nilai_pilih("utility_rekening", "rekening", "id_rekening=" . $id_rekening);
		$urut = $this->apl->urut($this->table, $this->primary);
		$meter_ahir = $this->apl->get_nilai_pilih($this->table, "MAX(meter)"
			, array('id_rekening' => $id_rekening, 'hapus' => 0));

		$cek_bulan = $this->apl->getSelectedData($this->table
			, array(
				"DATE_FORMAT(STR_TO_DATE(CONCAT(tahun,'-',bulan),'%Y-%m'),'%Y-%m') >= " => $this->input->post('tahun') . '-' . $this->input->post('bulan'),
				'id_rekening' => $id_rekening,
				'hapus' => 0,
			))->row();
		$pakai = $this->input->post('meter') - $meter_ahir;
		$data = array(
			$this->primary => $urut,
			'id_rekening' => $id_rekening,
			'bulan' => $this->input->post('bulan'),
			'tahun' => $this->input->post('tahun'),
			'meter' => $this->input->post('meter'),
			'pakai' => $pakai,
			'id_admin' => $this->session->id_admin,
		);

		if (isset($cek_bulan)) {
			$this->pesan->pesan_warning("Saving failed : Account water : " . $no_rekening . " Until this month, it has been inputted");

		} else {
			if ($this->input->post('meter') < $meter_ahir) {
				$this->pesan->pesan_warning("Saving failed : End Meter Larger than input");
			} else {
				$this->apl->log("Tambah",
					'',
					json_encode($data),
					$this->table,
					$urut);

				if (isset($_FILES["file"]) && !empty($_FILES["file"]["name"])) {
					$nama_gambar = time() . "-histori-" . $id_rekening . ".png";
					$image_parts = explode(";base64,", $_POST['file_base64']);
					$image_type_aux = explode("image/", $image_parts[0]);
					$image_base64 = base64_decode($image_parts[1]);
					file_put_contents('upload/meter/' . $nama_gambar, $image_base64);
					$data = array_merge($data, array('file' => $nama_gambar,));
				}

				$this->apl->log(
					"TAMBAH HISTORI PENGERJAAN",
					"",
					json_encode($data),
					'tiket_histori',
					$this->input->post('id_tiket')
				);


				$this->crud_model->save($data);

				$this->pesan->pesan_success("Successfully added meter to air number account " . $no_rekening);
			}
		}
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_edit($id)
	{
		$data = $this->crud_model->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_detail($id)
	{
		$data['m'] = $this->crud_model->get_by_id($id);
		$this->load->view('utility/meter/detail', $data);
	}


	public function ajax_update()
	{

		$id_rekening = $this->input->post('id_rekening');
		$no_rekening = $this->apl->get_nilai_pilih("utility_rekening", "rekening", "id_rekening=" . $id_rekening);

		/*
		$meter_ahir = $this->apl->get_nilai_pilih($this->table, "MAX(meter)"
			, array('id_rekening' => $id_rekening, 'hapus' => 0,
				'id_meter !=' => $this->input->post('id_meter')));
		*/
		$meter_ahir = $this->apl->get_nilai("select * from utility 
         where id_rekening=" . $id_rekening . " and hapus=0 and id_meter !='" . $this->input->post('id_meter') . "' 
         order by id_meter desc limit 1", "meter");

		$pakai = $this->input->post('meter') - $meter_ahir;

		$data = array(
			'id_rekening' => $id_rekening,
			'bulan' => $this->input->post('bulan'),
			'tahun' => $this->input->post('tahun'),
			'meter' => $this->input->post('meter'),
			'pakai' => $pakai,
			'id_admin' => $this->session->id_admin,
		);

		if ($this->input->post('meter') < $meter_ahir) {
			$this->pesan->pesan_warning("Saving failed : End Meter Larger than input");
		} else {
			$this->apl->log("Edit",
				json_encode($this->apl->getSelectedData($this->table, array($this->primary => $this->input->post($this->primary)))->row()),
				json_encode($data),
				$this->table,
				$this->input->post($this->primary));
			if (isset($_FILES["file"]) && !empty($_FILES["file"]["name"])) {
				$nama_gambar = time() . "-histori-" . $id_rekening . ".png";
				$image_parts = explode(";base64,", $_POST['file_base64']);
				$image_type_aux = explode("image/", $image_parts[0]);
				$image_base64 = base64_decode($image_parts[1]);
				file_put_contents('upload/meter/' . $nama_gambar, $image_base64);
				$data = array_merge($data, array('file' => $nama_gambar,));
			}
			$this->crud_model->update(array($this->primary => $this->input->post($this->primary)), $data);
			$this->pesan->pesan_success("Successfully updated Meter water account " . $no_rekening);
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


	public function import($id_tag = 4)
	{
		$data['id_tag'] = $id_tag;
		$b = isset($_GET['b']) ? $_GET['b'] : date('m');
		$t = isset($_GET['t']) ? $_GET['t'] : date('Y');
		$data['bulan'] = isset($_POST['bulan']) ? $_POST['bulan'] : $b;
		$data['tahun'] = isset($_POST['tahun']) ? $_POST['tahun'] : $t;
		$data['status'] = isset($_POST['status']) ? $_POST['status'] : '';

		$data['tombol_view'] = '<a href="' . site_url('meter/utility/template_csv/' . $data['id_tag']) . '"
				   class="btn btn-info">
					<i class="fa fa-download fa-file-excel-o"></i> Download Template</a>';


		$data['tombol_view'] .= '<a href="' . site_url('meter/utility/export_import_csv/' . $data['id_tag'])
			. '?bulan=' . $data['bulan']
			. '&tahun=' . $data['tahun']
			. '&status=' . $data['status']
			. '"
				   class="btn btn-info" target="_blank">
					<i class="fa fa-download fa-file-excel-o"></i> Export</a>';


		$data['field'] = $this->meter->getMeterImport($data['id_tag'], $data['bulan'], $data['tahun'], $data['status'])->get()->list_fields(); //Nama Coloum Tabel
		$data['judul'] = 'Import Meteran Terakhir';
		$data['page'] = 'utility/meter/import'; //Halaman di tampilkan
		$this->load->view('home', $data);

	}

	public function export_import_csv($id_tag)
	{
		$bulan = $this->input->get('bulan');
		$tahun = $this->input->get('tahun');
		$status = $this->input->get('status');

		$result = $this->meter->getMeterImportExport($id_tag, $bulan, $tahun, $status);
		$this->apl->export_excell($result, "export_hasil_import");

	}

	public function template_csv($id_tag)
	{
		$result = $this->meter->getDataUnitExportTemplate($id_tag);
		$this->apl->export_excell($result, "template_import_utility");

	}

	function actions_import()
	{
		$this->load->library(array('PHPExcel', 'PHPExcel/IOFactory'));
		$id_tag = $this->input->post('id_tag');
		$fileName = time() . "-" . $_FILES['file']['name'];
		$fileName = str_replace(" ", "_", $fileName);
		$config['upload_path'] = 'upload/meter'; //buat folder dengan nama assets di root folder
		$config['file_name'] = $fileName;
		$config['allowed_types'] = 'xls|xlsx|csv';
		$config['max_size'] = 10000;

		$this->load->library('upload');
		$this->upload->initialize($config);

		if (!$this->upload->do_upload('file'))
			$this->upload->display_errors();

		$media = $this->upload->data('file');
		$inputFileName = './upload/meter/' . $fileName;
		try {
			$inputFileType = IOFactory::identify($inputFileName);
			$objReader = IOFactory::createReader($inputFileType);
			$objReader->setReadDataOnly(true);
			$objPHPExcel = $objReader->load($inputFileName);
		} catch (Exception $e) {
			die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
		}

		$sheet = $objPHPExcel->getSheet(0);
		$highestRow = $sheet->getHighestRow();
		$highestColumn = $sheet->getHighestColumn();

		for ($row = 2; $row <= $highestRow; $row++) {                  //  Read a row of data into an array
			$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
				NULL,
				TRUE,
				FALSE);

			//$meter_awal = $this->meter->getMeterAwal($rowData[0][0])->total;
			$id_unit = $this->apl->get_nilai_pilih("db_unit",
				"id_unit",
				array('kode' => $rowData[0][0]));
			/*
			$id_bast = $this->apl->get_nilai_pilih("bast", "id_bast",
				array('id_unit' => $id_unit, 'hapus' => 0));
			*/

			$id_rekening = $this->apl->get_nilai_pilih("utility_rekening", "id_rekening",
				array(
					'id_unit' => $id_unit,
					'id_tag' => $id_tag,
					'hapus' => 0));

			$meter_awal = $this->apl->get_nilai_pilih($this->table, "MAX(meter)"
				, array('id_rekening' => $id_rekening, 'hapus' => 0));

			if ($rowData[0][1] == '') {
				$rowData[0][1] = 0;
			}
			$no_rekening = $this->apl->get_nilai_pilih("utility_rekening", "rekening", "id_rekening=" . $id_rekening);
			$cek_bulan = $this->apl->getSelectedData("utility"
				, array(
					"DATE_FORMAT(STR_TO_DATE(CONCAT(tahun,'-',bulan),'%Y-%m'),'%Y-%m') >= " => $this->input->post('tahun') . '-' . $this->input->post('bulan'),
					'id_rekening' => $id_rekening,
					'hapus' => 0,
				))->row();
			$pakai = $rowData[0][1] - $meter_awal;
			$urut = $this->apl->urut($this->table, $this->primary);
			$data = array(
				'id_rekening' => $id_rekening,
				'bulan' => $this->input->post('bulan'),
				'tahun' => $this->input->post('tahun'),
				'meter' => $rowData[0][1],
				'pakai' => $pakai,
				'tanggal' => date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($rowData[0][2])),
				'id_admin' => $this->session->id_admin,
				'import' => 1,
			);

			if (isset($cek_bulan)) {
				$data = array_merge($data, array(
					'id_meter' => $this->apl->urut("utility_error", "id_meter"),
					'ket' => "Gagal Simpan : No Rekening : " . $no_rekening . " Sampai Bulan Ini Sudah di input",
				));
				$this->apl->insertData("utility_error", $data);
				$this->pesan->pesan_warning("Failed to save : Water Account : " . $no_rekening . " Until this month, it has been inputted");

			} else {
				if ($meter_awal > $rowData[0][1]) {
					$data = array_merge($data, array(
						'id_meter' => $this->apl->urut("utility_error", "id_meter"),
						'ket' => "Failed to save : End Meter Larger than input",
					));
					$this->apl->insertData("utility_error", $data);
					$this->pesan->pesan_warning("Failed to save : End Meter Larger than input");
				} else {
					$this->apl->log("Tambah",
						'',
						json_encode($data),
						$this->table,
						$urut);
					$data = array_merge($data, array(
						$this->primary => $urut,
						'ket' => "Import Meteran Air",

					));
					$this->crud_model->save($data);
					$this->pesan->pesan_success("Successfully Added a Water Account Meter " . $no_rekening);
				}
			}
		}

		$data_import = array(
			'id	' => $this->apl->urut("utility_import", "id"),
			'nama_file' => $fileName,
			'id_admin' => $this->session->userdata('id_admin'),
			'id_tag' => $id_tag,
		);
		$this->apl->insertData("utility_import", $data_import);
		$this->pesan->pesan_success("Successfully imported water meter");
		redirect(site_url('meter/utility/import') . "?b=" . $this->input->post('bulan')
			. "&t=" . $this->input->post('tahun'));
	}

	public function ajax_list_import()
	{
		$id_tag = (isset($_POST['id_tag'])) ? $_POST['id_tag'] : '';
		$bulan = isset($_POST['bulan']) ? $_POST['bulan'] : date('m');
		$tahun = isset($_POST['tahun']) ? $_POST['tahun'] : date('Y');

		$status = isset($_POST['status']) ? $_POST['status'] : '';
		$column_search = array(
			'`kode`',
			'`Account`',
		);
		$column_order = array(
			null,
			'`kode`',
			'`Account`',
			'(`utility`.`meter`-`utility`.`pakai`)',
			'`utility`.`meter`',
			'`Usage(M3)`',
			null,
			null,
			null,
			null,
			null,
		);

		$list = $this->crud_model->get_data(
			$this->meter->getMeterImport($id_tag, $bulan, $tahun, $status), $column_search, $column_order);
		$record_total = $this->crud_model->get_jumlah(
			$this->meter->getMeterImport($id_tag, $bulan, $tahun, $status));
		$record_filter = $this->crud_model->get_jumlah_filter(
			$this->meter->getMeterImport($id_tag, $bulan, $tahun, $status), $column_search, $column_order);


		$no = isset($_POST['start']) ? $_POST['start'] : '0';
		$jumlah = $list->num_fields();
		$data_array = array();

		foreach ($list->result_array() as $data) {
			$no++;
			$r = array_values($data);
			$r[0] = $no;

			if ($r[$jumlah - 2] == 1) {
				$r[$jumlah - 2] = '<button class="btn btn-sm btn-info">Success </button>';
				$r[$jumlah - 1] = '';

			} else {
				$r[$jumlah - 2] = '<button class="btn btn-sm btn-warning"><i class="fa fa-trash"></i> Failed </button>';
				$r[$jumlah - 1] = '<div class="btn-group btn-group-xs">'
					. $this->apl->anchor(
						$this->tombol->get_edit_js("edit_data('" . $r[$jumlah - 1] . "')"),
						'edit_' . $this->modul, $this->modul)
					. $this->apl->anchor(
						$this->tombol->get_hapus_js("delete_data('" . $r[$jumlah - 1] . "')"),
						'edit_' . $this->modul, $this->modul)

					. '</div>';

			}

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


	public function ajax_edit_error($id)
	{
		$data = $this->apl->getSelectedData("utility_error", array('id_meter' => $id))->row();
		echo json_encode($data);
	}

	public function ajax_update_error()
	{
		$id_rekening = $this->input->post('id_rekening');
		$no_rekening = $this->apl->get_nilai_pilih("utility_rekening", "rekening", "id_rekening=" . $id_rekening);
		$urut = $this->apl->urut($this->table, $this->primary);
		/*
		$meter_ahir = $this->apl->get_nilai_pilih($this->table, "MAX(meter)"
			, array('id_rekening' => $id_rekening, 'hapus' => 0));
		*/
		$meter_ahir = $this->apl->get_nilai("select * from utility 
         where id_rekening=" . $id_rekening . " and hapus=0 and id_meter !='" . $this->input->post('id_meter') . "' 
         order by id_meter desc limit 1", "meter");


		$cek_bulan = $this->apl->getSelectedData($this->table
			, array(
				"DATE_FORMAT(STR_TO_DATE(CONCAT(tahun,'-',bulan),'%Y-%m'),'%Y-%m') >= " => $this->input->post('tahun') . '-' . $this->input->post('bulan'),
				'id_rekening' => $id_rekening,
				'hapus' => 0,
			))->row();
		$pakai = $this->input->post('meter') - $meter_ahir;
		$data = array(
			$this->primary => $urut,
			'id_rekening' => $id_rekening,
			'bulan' => $this->input->post('bulan'),
			'tahun' => $this->input->post('tahun'),
			'meter' => $this->input->post('meter'),
			'pakai' => $pakai,
			'import' => 1,
			'id_admin' => $this->session->id_admin,
		);

		if (isset($cek_bulan)) {
			$this->pesan->pesan_warning("Saving failed : Water Account : " . $no_rekening . " Until this month, it has been inputted");

		} else {
			if ($this->input->post('meter') < $meter_ahir) {
				$this->pesan->pesan_warning("Saving failed : End Meter Larger than input");
			} else {
				$this->apl->log("Tambah",
					'',
					json_encode($data),
					$this->table,
					$urut);
				$this->crud_model->save($data);
				$this->pesan->pesan_success("Successfully added to the Water Account Meter " . $no_rekening);
				$this->apl->updateData("utility_error", array('hapus' => 1), array('id_meter' => $this->input->post('id_meter')));
			}
		}
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete_error($id)
	{
		$data = array(
			'hapus' => '1',
		);
		$this->apl->log("Hapus",
			json_encode($this->apl->getSelectedData("utility_error", array($this->primary => $id))->row()),
			json_encode($data),
			$this->table, $id);

		$this->apl->updateData("utility_error", array('hapus' => 1), array('id_meter' => $id));
		echo json_encode(array("status" => TRUE));
	}


}

?>
