<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ajax extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('Crud_Model', 'crud_model');
		$this->crud_model = new Crud_Model();

		$this->load->model('Tiket_Model', 'tiket');
		$this->tiket = new Tiket_Model();

		$this->apl = new Apl();
		$this->tombol = new Tombol();
		$this->upload_model = new Upload_Model();
		$this->pesan = new Pesan();
	}

	public function export_csv($tipe)
	{

		$post = (isset($_GET['post'])) ? $_GET['post'] : '';
		$result = $this->tiket->getTiketExport($tipe, $post)->get();
		$this->apl->export_excell($result, " Request");
	}

	public function ajax_list_tombol_gird()
	{
		$tipe = (isset($_POST['tipe'])) ? $_POST['tipe'] : 0;
		$post = (isset($_POST['post'])) ? $_POST['post'] : 0;
		$jatuh_tempo = (isset($_POST['jatuh_tempo'])) ? $_POST['jatuh_tempo'] : 0;
		$unit = $this->apl->get_nilai_pilih("bast", "id_unit", array('id_bast' => $this->session->id_bast));
		$column_search = array(
			'`db_unit`.`kode`',
			'`tiket`.`no_form`',
			'`tiket`.`ket`',
			'`tiket`.`pelapor`',
			'`tiket`.`tanggal`',
		);
		$column_order = array(
			null,
			'`db_unit`.`kode`',
			'`tiket`.`no_form`',
			'`tiket`.`ket`',
			'tiket.tanggal',

		);
		if ($post == 3 || $post == 6) {
			$column_order = array_merge($column_order, array('approv'));
		}
		$column_order = array_merge($column_order, array('tanggal_awal', 'tanggal_ahir', null));
		$list = $this->crud_model->get_data(
			$this->tiket->getTiket($tipe, $post, $jatuh_tempo),
			$column_search,
			$column_order
		);
		$record_total = $this->crud_model->get_jumlah(
			$this->tiket->getTiket($tipe, $post, $jatuh_tempo)
		);
		$record_filter = $this->crud_model->get_jumlah_filter(
			$this->tiket->getTiket($tipe, $post, $jatuh_tempo),
			$column_search,
			$column_order
		);

		$no = isset($_POST['start']) ? $_POST['start'] : '0';
		$jumlah = $list->num_fields();

		$data_array = array();
		foreach ($list->result_array() as $data) {
			$no++;
			$r = array_values($data);
			$r[0] = $no;
			$r[4] = $this->apl->tgl_format($r[4], 1);
			$tipe = $this->apl->get_nilai_pilih(
				"tiket",
				"tipe",
				"id_tiket=" . $r[$jumlah - 1]
			);
			$link = $this->apl->get_nilai_pilih("tiket_tipe", "link", array('id' => $tipe));

			$aksi = '<div class=""> ';

			if ($post == 1) {
				$aksi .= '<a href="' . site_url('tiket/' . $link . '/edit/?id=' . $r[$jumlah - 1]) . '" 
                            class="btn btn-warning" title="edit"><i class="fa fa-edit"></i></a>';
				$aksi .= $this->apl->anchor(
					anchor(
						'#modal_form',
						'<i class="fa fa-trash"></i>',
						'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="hapus btn btn-danger"  title="Hapus"'
					),
					'hapus pengerjaan ' . $link,
					$link
				);

				$aksi .= $this->apl->anchor(
					anchor(
						'#modal_form',
						'<i class="fa fa-check"></i>',
						'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="approv_data btn btn-success"  title="ACC ' . strtoupper($link) . '"'
					),
					'tombol approv dept Head TR ' . $link,
					$link
				);
			}
			if ($post == 2) {
				$aksi .= $this->apl->anchor(
					anchor(
						'#modal_form',
						'<i class="fa fa-trash"></i>',
						'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="hapus btn btn-danger"  title="Hapus"'
					),
					'hapus pengerjaan ' . $link,
					$link
				);
			}
			$aksi .= anchor(
				'#modal_form',
				'<i class="fa fa-search"></i>',
				'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="detail btn btn-info"  title="Detail"'
			);


			$aksi .= anchor(
				site_url('tiket/' . $link . '/cetak?id=' . $r[$jumlah - 1]),
				'<i class="fa fa-print"></i>',
				'class="btn btn-success"  target="_blank" title="Print"'
			);
			if ($post == 3) {
				if ($r[5] == 0) {
					/*
					$aksi .= $this->apl->anchor(anchor('#modal_form'
						, '<i class="fa fa-check"></i>'
						, 'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="approval btn btn-warning"  title="Approval"'),
						'tombol approval ' . $link, $link);
					*/
				}
				if ($r[5] != 0) {
					if ($r[6] != '') {
						$r[6] = $this->apl->tgl_format($r[6], 1);
						$r[7] = $this->apl->tgl_format($r[7], 1);
						if ($tipe == '3') { //Jadwal utnuk fitti out
							$aksi .= $this->apl->anchor(
								anchor(
									'#modal_form',
									'<i class="fa fa-angle-right"></i>',
									'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="perpanjang_jadwal btn btn-primary"  
                           title="Perpanjang Jadwal Pengerjaan"'
								),
								'perpanjang ' . $link,
								$link
							);
						} else {
							$aksi .= $this->apl->anchor(
								anchor(
									'#modal_form',
									'<i class="fa fa-calendar"></i>',
									'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="jadwal_pengerjaan btn btn-primary"  title="Jadwal Pengerjaan"'
								),
								'tombol jadwal pengerjaan '
								. $link,
								$link
							);
						}


						if ($tipe == '6') { // penyelesaian untuk akses card

							$aksi .= $this->apl->anchor(
								anchor(
									'#modal_form',
									'<i class="fa fa-send"></i>',
									'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="selesai_pengerjaan_card btn btn-success"  title="Selesai Pengerjaan"'
								),
								'tombol selesai pengerjaan '
								. $link,
								$link
							);
						} else {

							$aksi .= $this->apl->anchor(
								anchor(
									'#modal_form',
									'<i class="fa fa-plus"></i>',
									'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="tambah_histori btn btn-info"  title="Tambah Rincian Pengerjaan"'
								),
								'tombol tambah histori pengerjaan '
								. $link,
								$link
							);


							$aksi .= $this->apl->anchor(
								anchor(
									'#modal_form',
									'<i class="fa fa-archive"></i>',
									'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="hold_pengerjaan btn btn-success"  title="hold/postphone"'
								),
								'tombol hold/postphone pengerjaan '
								. $link,
								$link
							);


							$aksi .= $this->apl->anchor(
								anchor(
									'#modal_form',
									'<i class="fa fa-send"></i>',
									'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="selesai_pengerjaan btn btn-success"  title="Selesai Pengerjaan"'
								),
								'tombol selesai pengerjaan '
								. $link,
								$link
							);
						}
					} else {
						$aksi .= $this->apl->anchor(
							anchor(
								'#modal_form',
								'<i class="fa fa-calendar"></i>',
								'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="jadwal_pengerjaan btn btn-primary"  title="Jadwal Pengerjaan"'
							),
							'tombol jadwal pengerjaan '
							. $link,
							$link
						);
					}
				}
				$r[5] = ($r[5] == 0) ? '<button class="btn btn-warning btn-sm">UnApproval</button>'
					: '<button class="btn btn-info btn-sm">Approval</button>';
			}
			if ($post == 4) {
				$aksi .= $this->apl->anchor(
					anchor(
						'#modal_form',
						'<i class="fa fa-legal"></i>',
						'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="tutup_pengerjaan btn btn-primary"  title="Selesai Pengerjaan"'
					),
					'tombol tutup pengerjaan '
					. $link,
					$link
				);
			}
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
		echo json_encode($output); //output to json format

	}

	public function ajax_list()
	{
		$tipe = (isset($_POST['tipe'])) ? $_POST['tipe'] : 0;
		$post = (isset($_POST['post'])) ? $_POST['post'] : 0;
		$jatuh_tempo = (isset($_POST['jatuh_tempo'])) ? $_POST['jatuh_tempo'] : 0;
		$post = ($jatuh_tempo == '4') ? '6' : $post;

		$column_search = array(
			'`db_unit`.`kode`',
			'`tiket`.`no_form`',
			'`tiket`.`ket`',
			'`tiket`.`tanggal`',
		);
		$column_order = array(
			null,
			'`db_unit`.`kode`',
			'`tiket`.`no_form`',
			'`tiket`.`ket`',
			'`tiket`.`tanggal`',
			null,
		);

		$list = $this->crud_model->get_data(
			$this->tiket->getTiket($tipe, $post, $jatuh_tempo),
			$column_search,
			$column_order
		);
		$record_total = $this->crud_model->get_jumlah(
			$this->tiket->getTiket($tipe, $post, $jatuh_tempo)
		);
		$record_filter = $this->crud_model->get_jumlah_filter(
			$this->tiket->getTiket($tipe, $post, $jatuh_tempo),
			$column_search,
			$column_order
		);

		$no = isset($_POST['start']) ? $_POST['start'] : '0';
		$jumlah = $list->num_fields();

		$data_array = array();
		foreach ($list->result_array() as $data) {
			$no++;
			$r = array_values($data);
			$r[0] = $no;
			$r[4] = $this->apl->tgl_format($r[4], 1);

			$note_approv = $this->apl->getSelectedData(
				"tiket_approv",
				array('id_tiket' => $r[$jumlah - 1], 'status' => 0)
			)->result();
			$status_reject = '';
			if ($note_approv) {
				$status_reject = '<br><small class="text-warning">';
				foreach ($note_approv as $not) {
					$status_reject .= '<br>' . $not->note;
				}
				$status_reject .= '</small>';
			}

			$r[3] = $r[3] . $status_reject;
			$t = $this->apl->getSelectedData("tiket", array('id_tiket' => $r[$jumlah - 1]))->row();
			//$tipe = $this->apl->get_nilai_pilih("tiket", "tipe","id_tiket=" . $r[$jumlah - 1]);
			$tipe = $t->tipe;
			$rab = $t->rab;

			$link = $this->apl->get_nilai_pilih("tiket_tipe", "link", array('id' => $tipe));
			$kwt = $this->apl->get_nilai_pilih("tiket_tipe", "kwt", array('id' => $tipe));

			$aksi = '<div class="dropdown">'
				. '<button class="btn btn-secondary" type="button" id="dropdownMenuButton" 
data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
				. '<i class="fa fa-ellipsis-v"></i>'
				. '</button>'
				. '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">';

			$aksi .= anchor(
				'#modal_form',
				'<i class="fa fa-search"></i> Detail',
				'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="detail dropdown-item"  title="Detail"'
			);




			if ($post < 3) {
				/*
				$aksi .= anchor('#modal_form'
					, '<i class="fa fa-pencil-alt"></i> Signature'
					, 'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="signature dropdown-item"  title="Detail"');
				*/
				$aksi .= anchor(
					site_url('tiket/ajax/signature?id=' . $r[$jumlah - 1]),
					'<i class="fa fa-signature"></i> Signature',
					'class="btn dropdown-item"  target="_blank" title="Signature"'
				);

			}

			$aksi .= anchor(
				URLADMIN.'tiket/' . $link . '/cetak?id=' . $r[$jumlah - 1],
				'<i class="fa fa-print"></i> Print',
				'class="btn dropdown-item"  target="_blank" title="Print"'
			);

			if ($post == 1) {

				$aksi .= anchor(
					'#modal_form',
					'<i class="fa fa-upload"></i> Attachment',
					'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
					   class="tambah_attachment dropdown-item"  title="Tambah Rincian Pengerjaan"'
				);

				$aksi .= '<a href="' . site_url('tiket/' . $link . '/edit/?id=' . $r[$jumlah - 1]) . '" 
                            class="dropdown-item" title="edit"><i class="fa fa-edit"></i> Edit</a>';
				$aksi .= anchor(
						'#modal_form',
						'<i class="fa fa-trash"></i> Delete',
						'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="hapus dropdown-item"  title="Hapus"'
					);
				$aksi .= anchor(
					'#modal_form',
					'<i class="fa fa-book"></i> Add Note',
					'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="note dropdown-item"  title="Detail"'
				);
				$total = $this->apl->getSelectedData("tiket_detail", array('id_tiket' => $r[$jumlah - 1], 'tipe' => '1', 'hapus' => 0))->num_rows();

			}

			if ($post == 3) {

				$r[5] = ($r[5] == 0) ? '<button class="btn btn-warning btn-sm">UnApproval</button>'
					: '<button class="btn btn-info btn-sm">Approval</button>';

			}
			if ($post == 4) {
				$aksi .= anchor(
						'#modal_form',
						'<i class="fa fa-legal"></i> Close Job',
						'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="tutup_pengerjaan dropdown-item"  title="Selesai Pengerjaan"'
					);
			}
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
		echo json_encode($output); //output to json format

	}

	public function ajax_list_job()
	{
		$tipe = (isset($_POST['tipe'])) ? $_POST['tipe'] : 0;
		$post = (isset($_POST['post'])) ? $_POST['post'] : 0;
		$jatuh_tempo = (isset($_POST['jatuh_tempo'])) ? $_POST['jatuh_tempo'] : 0;
		$post = ($jatuh_tempo == '4') ? '6' : $post;

		$column_search = array(
			'`db_unit`.`kode`',
			'`tiket`.`no_form`',
			'`tiket`.`ket`',
			'`tiket`.`tanggal`',
		);
		$column_order = array(
			null,
			'`db_unit`.`kode`',
			'`tiket`.`no_form`',
			'`tiket`.`ket`',
			'`tiket`.`tanggal`',
			null,
		);

		$list = $this->crud_model->get_data(
			$this->tiket->getTiketJob($tipe, $post, $jatuh_tempo),
			$column_search,
			$column_order
		);
		$record_total = $this->crud_model->get_jumlah(
			$this->tiket->getTiketJob($tipe, $post, $jatuh_tempo)
		);
		$record_filter = $this->crud_model->get_jumlah_filter(
			$this->tiket->getTiketJob($tipe, $post, $jatuh_tempo),
			$column_search,
			$column_order
		);

		$no = isset($_POST['start']) ? $_POST['start'] : '0';
		$jumlah = $list->num_fields();

		$data_array = array();
		foreach ($list->result_array() as $data) {
			$no++;
			$r = array_values($data);
			$r[0] = $no;
			$r[4] = $this->apl->tgl_format($r[4], 1);

			$note_approv = $this->apl->getSelectedData(
				"tiket_approv",
				array('id_tiket' => $r[$jumlah - 1], 'status' => 0)
			)->result();
			$status_reject = '';
			if ($note_approv) {
				$status_reject = '<br><small class="text-warning">';
				foreach ($note_approv as $not) {
					$status_reject .= '<br>' . $not->note;
				}
				$status_reject .= '</small>';
			}

			$r[3] = $r[3] . $status_reject;
			$t = $this->apl->getSelectedData("tiket", array('id_tiket' => $r[$jumlah - 1]))->row();
			//$tipe = $this->apl->get_nilai_pilih("tiket", "tipe","id_tiket=" . $r[$jumlah - 1]);
			$tipe = $t->tipe;
			$rab = $t->rab;

			$link = $this->apl->get_nilai_pilih("tiket_tipe", "link", array('id' => $tipe));
			$kwt = $this->apl->get_nilai_pilih("tiket_tipe", "kwt", array('id' => $tipe));

			$aksi = '<div class="dropdown">'
				. '<button class="btn btn-secondary" type="button" id="dropdownMenuButton" 
data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
				. '<i class="fa fa-ellipsis-v"></i>'
				. '</button>'
				. '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">';

			$aksi .= anchor(
				'#modal_form',
				'<i class="fa fa-search"></i> Detail',
				'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="detail dropdown-item"  title="Detail"'
			);

			$aksi .= anchor(
				'#modal_form',
				'<i class="fa fa-book"></i> Add Note',
				'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="note dropdown-item"  title="Detail"'
			);

			if ($tipe == '6' and $post < 4) {
				$aksi .= $this->apl->anchor(
					anchor(
						'#modal_form',
						'<i class="fa fa-id-card"></i> Add No Card',
						'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="tambah_card dropdown-item"  title="Tambah No Kartu"'
					),
					'tombol tambah nomor kartu '
					. $link,
					$link
				);
			}
			/*
			if ($tipe == '6') {
				$aksi .= $this->apl->anchor(anchor('#modal_form'
					, '<i class="fa fa-id-card"></i> Add No Card'
					, 'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="tambah_card dropdown-item"  title="Tambah No Kartu"'),
					'tombol tambah nomor kartu '
					. $link, $link);
				$chek_kartu = $this->apl->getSelectedData("card", array(
					'id_tiket' => $r[$jumlah - 1],
					'hapus' => 0
				))->num_rows();
				if ($chek_kartu > 0) {

					$aksi .= $this->apl->anchor(anchor('#modal_form'
						, '<i class="fa fa-send"></i> Done Job'
						, 'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="selesai_pengerjaan dropdown-item"  title="Selesai Pengerjaan"'),
						'tombol selesai pengerjaan ' . $link, $link);

				}
			} else {
				$aksi .= $this->apl->anchor(anchor('#modal_form'
					, '<i class="fa fa-send"></i> Done Job'
					, 'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="selesai_pengerjaan dropdown-item"  title="Selesai Pengerjaan"'),
					'tombol selesai pengerjaan ' . $link, $link);
			}
			*/


			if ($post < 3) {
				/*
				$aksi .= anchor('#modal_form'
					, '<i class="fa fa-pencil-alt"></i> Signature'
					, 'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="signature dropdown-item"  title="Detail"');
				*/
				$aksi .= anchor(
					site_url('tiket/ajax/signature?id=' . $r[$jumlah - 1]),
					'<i class="fa fa-signature"></i> Signature',
					'class="btn dropdown-item"  target="_blank" title="Signature"'
				);
			}

			$aksi .= anchor(
				site_url('tiket/' . $link . '/cetak?id=' . $r[$jumlah - 1]),
				'<i class="fa fa-print"></i> Print',
				'class="btn dropdown-item"  target="_blank" title="Print"'
			);


			$tombol_jadwal = $this->apl->anchor(
				anchor(
					'#modal_form',
					'<i class="fa fa-calendar"></i> Schedule',
					'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="jadwal_pengerjaan dropdown-item"  title="Jadwal Pengerjaan"'
				),
				'tombol jadwal pengerjaan '
				. $link,
				$link
			);


			if ($post == 1) {

				$aksi .= anchor(
					'#modal_form',
					'<i class="fa fa-upload"></i> Attachment',
					'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
					   class="tambah_attachment dropdown-item"  title="Tambah Rincian Pengerjaan"'
				);
				/*
				$aksi .= $this->apl->anchor(
					anchor(
						'#modal_form',
						'<i class="fa fa-upload"></i> Attachment',
						'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
					   class="tambah_attachment dropdown-item"  title="Tambah Rincian Pengerjaan"'
					),
					'tombol tambah histori pengerjaan '
						. $link,
					$link
				);
				*/
				$aksi .= '<a href="' . site_url('tiket/' . $link . '/edit/?id=' . $r[$jumlah - 1]) . '" 
                            class="dropdown-item" title="edit"><i class="fa fa-edit"></i> Edit</a>';
				$aksi .= $this->apl->anchor(
					anchor(
						'#modal_form',
						'<i class="fa fa-trash"></i> Delete',
						'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="hapus dropdown-item"  title="Hapus"'
					),
					'hapus pengerjaan ' . $link,
					$link
				);

				$total = $this->apl->getSelectedData("tiket_detail", array('id_tiket' => $r[$jumlah - 1], 'tipe' => '1', 'hapus' => 0))->num_rows();
				if ($tipe == 5) {
					if ($total > 0) {
						if ($rab == 1) {
							$rab_approv = $this->apl->get_nilai_pilih("rab", "id_acc", array('id_rab' => $t->id_rab));
							if ($rab_approv > 0) {
								$aksi .= $this->apl->anchor(
									anchor(
										'#modal_form',
										'<i class="fa fa-check"></i> Approval Dept Head',
										'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="approv_data dropdown-item"  title="ACC ' . strtoupper($link) . '"'
									),
									'tombol approv dept Head TR ' . $link,
									$link
								);
							}
						} else {
							$aksi .= $this->apl->anchor(
								anchor(
									'#modal_form',
									'<i class="fa fa-check"></i> Approval Dept Head',
									'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="approv_data dropdown-item"  title="ACC ' . strtoupper($link) . '"'
								),
								'tombol approv dept Head TR ' . $link,
								$link
							);
						}
					}
				} else {
					$aksi .= $this->apl->anchor(
						anchor(
							'#modal_form',
							'<i class="fa fa-check"></i> Approval Dept Head',
							'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="approv_data dropdown-item"  title="ACC ' . strtoupper($link) . '"'
						),
						'tombol approv dept Head TR ' . $link,
						$link
					);
				}
			}
			if ($post == 2) {
				$aksi .= $this->apl->anchor(
					anchor(
						'#modal_form',
						'<i class="fa fa-trash"></i> Delete',
						'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="hapus dropdown-item"  title="Hapus"'
					),
					'hapus pengerjaan ' . $link,
					$link
				);
			}
			if ($post == 3) {

				$aksi .= '<a href="' . site_url('tiket/' . $link . '/edit/?id=' . $r[$jumlah - 1]) . '"
                            class="dropdown-item" title="edit"><i class="fa fa-edit"></i> Edit</a>';
				if ($r[5] == 0) {
					/*
					$aksi .= $this->apl->anchor(anchor('#modal_form'
						, '<i class="fa fa-check"></i>'
						, 'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="approval btn btn-warning"  title="Approval"'),
						'tombol approval ' . $link, $link);
					*/
				}
				if ($r[5] != 0) {

					if ($r[6] != '') {
						$r[6] = $this->apl->tgl_format($r[6], 1);
						$r[7] = $this->apl->tgl_format($r[7], 1);

						if ($tipe == '3') { //Jadwal utnuk fitti out
							$aksi .= $this->apl->anchor(
								anchor(
									'#modal_form',
									'<i class="fa fa-angle-right"></i> Extend Schedule',
									'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="perpanjang_jadwal dropdown-item"  
                           title="Perpanjang Jadwal Pengerjaan"'
								),
								'perpanjang ' . $link,
								$link
							);


						} else {
							$aksi .= $tombol_jadwal;
						}


						$aksi .= $this->apl->anchor(
							anchor(
								'#modal_form',
								'<i class="fa fa-history"></i> History Job',
								'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="tambah_histori dropdown-item"  title="Tambah Rincian Pengerjaan"'
							),
							'tombol tambah histori pengerjaan '
							. $link,
							$link
						);

						//if ($tipe == '5') {
						$aksi .= $this->apl->anchor(
							anchor(
								'#modal_form',
								'<i class="fa fa-archive"></i> Hold/Postphone',
								'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="hold_pengerjaan dropdown-item"  title="hold/postphone"'
							),
							'tombol hold/postphone pengerjaan ' . $link,
							$link
						);
						//}
						$aksi .= $this->apl->anchor(
							anchor(
								'#modal_form',
								'<i class="fa fa-send"></i> Done Job',
								'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="selesai_pengerjaan dropdown-item"  title="Selesai Pengerjaan"'
							),
							'tombol selesai pengerjaan ' . $link,
							$link
						);
					} else {
						$aksi .= $tombol_jadwal;
					}
				}
				$r[5] = ($r[5] == 0) ? '<button class="btn btn-warning btn-sm">UnApproval</button>'
					: '<button class="btn btn-info btn-sm">Approval</button>';


				//if ($kwt == null || $kwt == '') {

				$aksi .= $this->apl->anchor(anchor('#modal_form'
					, '<i class="fa fa-remove"></i> Cancel Request'
					, 'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="reject dropdown-item"  title="Reject"'),
					'tombol reject ' . $link, $link);
				//}

			}
			if ($post == 4) {
				$aksi .= $this->apl->anchor(
					anchor(
						'#modal_form',
						'<i class="fa fa-legal"></i> Close Job',
						'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="tutup_pengerjaan dropdown-item"  title="Selesai Pengerjaan"'
					),
					'tombol tutup pengerjaan '
					. $link,
					$link
				);
			}
			if ($post == 6) {
				$aksi .= $this->apl->anchor(
					anchor(
						'#modal_form',
						'<i class="fa fa-folder-open"></i> Un Hold/Postphone',
						'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="unhold_pengerjaan dropdown-item"  title="un hold/postphone"'
					),
					'tombol Un hold/postphone pengerjaan '
					. $link,
					$link
				);
			}
			if ($post == 5) {
				$ver_tutup = $this->apl->get_nilai_pilih("tiket", "ver_tutup", array('id_tiket' => $r[$jumlah - 1]));
				if ($ver_tutup == 0) {
					$r[$jumlah - 1] = $aksi . '</div>';
				} else {
					$r[$jumlah - 1] = '<a href="#" class="btn btn-warning btn-block btn-sm"><i class="fa fa-spinner fa-spin"></i> Waiting Acc ...</a>';
				}
			} else {
				$r[$jumlah - 1] = $aksi . '</div>';
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
		echo json_encode($output); //output to json format

	}

	public function add_jadwal($id_tiket)
	{
		$data['j'] = $this->apl->getSelectedData("tiket", "id_tiket=" . $id_tiket)->row();
		$this->load->view('tiket/form_jadwal', $data);
	}

	public function ajax_add_jadwal()
	{
		$data = array(
			'tanggal_awal' => $this->input->post('tanggal_awal'),
			'tanggal_ahir' => $this->input->post('tanggal_ahir'),
			'pic' => $this->input->post('pic'),
			'pic_nama' => $this->apl->get_nilai_pilih("karyawan", "nama", "id_karyawan=" . $this->input->post('pic')),
		);
		$this->apl->log(
			"TAMBAH JADWAL PENGERJAAN DEFECCT",
			json_encode($this->apl->getSelectedData(
				"tiket",
				array('id_tiket' => $this->input->post('id_tiket'))
			)->row()),
			json_encode($data),
			'tiket',
			$this->input->post('id_tiket')
		);
		$this->apl->updateData('tiket', $data, array('id_tiket' => $this->input->post('id_tiket')));
		$this->pesan->pesan_success("Berhasil Menambah Jadwal Pengerjaan");
		echo json_encode(array("status" => TRUE));
	}

	public function add_perpanjangan($id_tiket)
	{
		$data['j'] = $this->apl->getSelectedData("tiket", "id_tiket=" . $id_tiket)->row();
		$data['d'] = $this->apl->getSelectedData(
			"tiket_detail",
			array(
				'id_tiket' => $id_tiket,
				'hapus' => 0,
				'tipe' => 1,
			)
		)->row();
		$this->load->view('tiket/form_jadwal_perpanjangan', $data);
	}

	public function ajax_add_perpanjangan()
	{
		$id_tiket = $this->input->post('id_tiket');
		$t = $this->apl->getSelectedData("tiket", "id_tiket=" . $id_tiket)->row();
		$this->apl->insertData("tiket_p", array(
			'id_tiket' => $id_tiket,
			'tanggal' => date('Y-m-d'),
			'tanggal_awal' => $t->tanggal_awal,
			'tanggal_ahir' => $t->tanggal_ahir,
			'id_admin' => $this->session->id_admin,
		));

		$data = array(

			'tanggal_awal' => $this->input->post('tanggal_awal'),
			'tanggal_ahir' => $this->input->post('tanggal_ahir'),
			'post' => 2,
		);

		$this->apl->updateData("tiket_detail", array(
			'tahap_bayar' => 1
		), array('id_tiket' => $id_tiket, 'hapus' => 0, 'tipe' => 1));
		$id_tag = $this->input->post('id_tag');
		$jumlah = $this->apl->number_format($this->input->post('jumlah'));
		$deposit = $this->apl->number_format($this->input->post('deposit'));
		$this->apl->insertData(
			"tiket_detail",
			array(
				'id_tiket' => $id_tiket,
				'tipe' => 1,
				'nama' => $this->apl->get_nilai_pilih("db_tag", "nama", array('id_tag' => $id_tag)),
				'id_tag' => $id_tag,
				'jumlah' => $jumlah,
				'deposit' => $deposit,
				'material' => '0',
				'qty' => '1',
				'harga_satuan' => $jumlah,
			)
		);

		$this->apl->log(
			"TAMBAH JADWAL PERPANJANGAN FITT OUT",
			json_encode($this->apl->getSelectedData(
				"tiket",
				array('id_tiket' => $this->input->post('id_tiket'))
			)->row()),
			json_encode($data),
			'tiket',
			$this->input->post('id_tiket')
		);
		$this->apl->updateData('tiket', $data, array('id_tiket' => $this->input->post('id_tiket')));
		$this->pesan->pesan_success("Successfully Increased Fitt Out Extension Schedule");
		echo json_encode(array("status" => TRUE));
	}

	public function add_histori($id_tiket)
	{
		$data['t'] = $this->apl->getSelectedData("tiket", "id_tiket=" . $id_tiket)->row();
		$data['h'] = $this->apl->getSelectedData("tiket_histori", array("id_tiket" => $id_tiket, 'hapus' => 0))->result();
		$this->load->view('tiket/form_histori', $data);
	}

	public function add_attachment($id_tiket)
	{
		$data['t'] = $this->apl->getSelectedData("tiket", "id_tiket=" . $id_tiket)->row();
		$data['h'] = $this->apl->getSelectedData("tiket_histori", array("id_tiket" => $id_tiket, 'hapus' => 0))->result();
		$this->load->view('tiket/form_attachment', $data);
	}

	public function ajax_histori_jadwal()
	{
		$data = array(

			'ket' => $this->input->post('ket'),
			'tanggal' => $this->input->post('tanggal'),
			'id_tiket' => $this->input->post('id_tiket'),
			'id_admin' => $this->session->id_admin,
		);

		if (isset($_FILES["file"]) && !empty($_FILES["file"]["name"])) {


			$tempName = $_FILES['file']['tmp_name'];
			$fileName = $_FILES['file']['name'];
			$fileName = $this->upload_model->fileName($fileName);
			$targetFile = $this->upload_model->targetFile($tempName, $fileName, "histori");
			$this->upload_model->upload_resize($targetFile);
			$data = array_merge($data, array('file' => $fileName,));


			/*
			$nama_gambar = time() . "-histori-" . $this->input->post('id_tiket') . ".png";
			$image_parts = explode(";base64,", $_POST['file_base64']);
			$image_type_aux = explode("image/", $image_parts[0]);
			$image_base64 = base64_decode($image_parts[1]);
			file_put_contents('upload/histori/' . $nama_gambar, $image_base64);
			$data = array_merge($data, array('file' => $nama_gambar,));
			*/
		}

		$this->apl->log(
			"TAMBAH HISTORI PENGERJAAN",
			"",
			json_encode($data),
			'tiket_histori',
			$this->input->post('id_tiket')
		);
		$this->apl->insertData('tiket_histori', $data);
		$this->pesan->pesan_success("Successfully Increased Work History");
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_attachment()
	{

		$id_upload = $this->input->post('id_upload');
		$u = $this->apl->getSelectedData("db_upload", array('id_upload' => $id_upload))->row();

		$data = array(

			'nama' => $u->nama,
			'id_upload' => $id_upload,
			'nomor' => $this->input->post('nomor'),
			'id_tiket' => $this->input->post('id_tiket'),
			'id_admin' => $this->session->id_admin,
		);

		if (isset($_FILES["file"]) && !empty($_FILES["file"]["name"])) {

			$tempName = $_FILES['file']['tmp_name'];
			$fileName = $_FILES['file']['name'];
			$fileName = $this->upload_model->fileName($fileName);
			$targetFile = $this->upload_model->targetFile($tempName, $fileName, $u->folder);
			$this->upload_model->upload_resize($targetFile);
			$data = array_merge($data, array('file' => $fileName,));
		}

		$this->apl->log(
			"TAMBAH ATTACHMENT",
			"",
			json_encode($data),
			'tiket_berkas',
			$this->input->post('id_tiket')
		);
		$this->apl->insertData('tiket_berkas', $data);
		$this->pesan->pesan_success("Successfully Increased Work History");
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_detail($id_tiket)
	{
		$data['t'] = $this->apl->getSelectedData("tiket", "id_tiket=" . $id_tiket)->row();
		$data['d'] = $this->apl->getSelectedData(
			"tiket_detail",
			array('id_tiket' => $id_tiket, 'tipe' => 1, 'hapus' => 0)
		)->result();
		$data['h'] = $this->apl->getSelectedData("tiket_histori", array("id_tiket" => $id_tiket, 'hapus' => 0))->result();
		$data['berkas'] = $this->apl->getSelectedData(
			"tiket_berkas",
			array('id_tiket' => $id_tiket, 'hapus' => 0)
		)->result();
		$data['p'] = $this->apl->getSelectedData(
			"tiket_detail",
			array('id_tiket' => $id_tiket, 'tipe' => 0, 'hapus' => 0)
		)->result();

		$data['a'] = $this->apl->getSelectedData(
			"tiket_approv",
			array('id_tiket' => $id_tiket, 'note !=' => '')
		)->result();

		$bayar = $this->apl->getSelectedData(
			"tiket_detail",
			array('id_tiket' => $id_tiket, 'tipe' => 2, 'hapus' => 0)
		)->row();
		$data['b'] = (isset($bayar)) ? $this->apl->getSelectedData("bayar", "id_bayar=" . $bayar->id_bayar)->row() : array();
		$data['proses'] = $this->input->get('proses');
		$this->load->view('tiket/detail', $data);
	}

	public function ajax_signature($id_tiket)
	{
		$data['t'] = $this->apl->getSelectedData("tiket", "id_tiket=" . $id_tiket)->row();
		$data['d'] = $this->apl->getSelectedData(
			"tiket_detail",
			array('id_tiket' => $id_tiket, 'tipe' => 1, 'hapus' => 0)
		)->result();
		$data['h'] = $this->apl->getSelectedData("tiket_histori", array("id_tiket" => $id_tiket, 'hapus' => 0))->result();
		$data['berkas'] = $this->apl->getSelectedData(
			"tiket_berkas",
			array('id_tiket' => $id_tiket, 'hapus' => 0)
		)->result();
		$data['p'] = $this->apl->getSelectedData(
			"tiket_detail",
			array('id_tiket' => $id_tiket, 'tipe' => 0, 'hapus' => 0)
		)->result();

		$data['a'] = $this->apl->getSelectedData(
			"tiket_approv",
			array('id_tiket' => $id_tiket, 'note !=' => '')
		)->result();

		$bayar = $this->apl->getSelectedData(
			"tiket_detail",
			array('id_tiket' => $id_tiket, 'tipe' => 2, 'hapus' => 0)
		)->row();
		$data['b'] = (isset($bayar)) ? $this->apl->getSelectedData("bayar", "id_bayar=" . $bayar->id_bayar)->row() : array();
		$data['proses'] = $this->input->get('proses');
		$this->load->view('tiket/signature', $data);
	}

	public function signature()
	{
		$id_tiket = $this->input->get('id');
		$data['t'] = $this->apl->getSelectedData("tiket", "id_tiket=" . $id_tiket)->row();
		$data['d'] = $this->apl->getSelectedData(
			"tiket_detail",
			array('id_tiket' => $id_tiket, 'tipe' => 1, 'hapus' => 0)
		)->result();
		$data['h'] = $this->apl->getSelectedData("tiket_histori", array("id_tiket" => $id_tiket, 'hapus' => 0))->result();
		$data['berkas'] = $this->apl->getSelectedData(
			"tiket_berkas",
			array('id_tiket' => $id_tiket, 'hapus' => 0)
		)->result();
		$data['p'] = $this->apl->getSelectedData(
			"tiket_detail",
			array('id_tiket' => $id_tiket, 'tipe' => 0, 'hapus' => 0)
		)->result();

		$data['a'] = $this->apl->getSelectedData(
			"tiket_approv",
			array('id_tiket' => $id_tiket, 'note !=' => '')
		)->result();

		$bayar = $this->apl->getSelectedData(
			"tiket_detail",
			array('id_tiket' => $id_tiket, 'tipe' => 2, 'hapus' => 0)
		)->row();
		$data['b'] = (isset($bayar)) ? $this->apl->getSelectedData("bayar", "id_bayar=" . $bayar->id_bayar)->row() : array();
		$data['proses'] = $this->input->get('proses');
		$this->load->view('tiket/signature', $data);
	}

	public function signature_act()
	{
		$tipe = $this->apl->get_nilai_pilih("tiket", "tipe", array('id_tiket' => $this->input->post('id_tiket')));
		$nama_gambar = time() . "-signature-" . $this->input->post('id_tiket') . ".png";
		$image_parts = explode(";base64,", $_POST['signed']);
		$image_type_aux = explode("image/", $image_parts[0]);
		$image_base64 = base64_decode($image_parts[1]);
		file_put_contents('upload/signature/' . $nama_gambar, $image_base64);

		$this->apl->updateData("tiket", array(
			'signature' => $nama_gambar,
			'encode_signature' => $image_parts[1],
		), array('id_tiket' => $this->input->post('id_tiket')));

		$this->pesan->pesan_success("Successfully Add Signature");

		echo '<script type="text/javascript">
  window.close() ;
</script>';
	}

	public function ajax_selesai()
	{
		$tipe = $this->apl->get_nilai_pilih("tiket", "tipe", array('id_tiket' => $this->input->post('id_tiket')));
		$ver_tutup = $this->apl->get_nilai_pilih("tiket_tipe", "ver_tutup", array('id' => $tipe));
		$this->apl->updateData(
			"tiket",
			array(
				'post' => 4,
				'tanggal_selesai' => $this->input->post('tanggal'),
				'ver_tutup' => $ver_tutup,
			),
			array('id_tiket' => $this->input->post('id_tiket'))
		);
		$data = array(

			'ket' => "Selesai",
			'tanggal' => $this->input->post('tanggal'),
			'id_tiket' => $this->input->post('id_tiket'),
			'id_admin' => $this->session->id_admin,
		);
		$this->apl->log(
			"TAMBAH SELESAI PENGERJAAN REQUEST",
			"",
			json_encode($data),
			'tiket',
			$this->input->post('id_tiket')
		);
		$this->apl->insertData('tiket_histori', $data);
		$this->pesan->pesan_success("Successfully Complete Work");
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_add_signature()
	{
		$tipe = $this->apl->get_nilai_pilih("tiket", "tipe", array('id_tiket' => $this->input->post('id_tiket')));

		$nama_gambar = time() . "-signature-" . $this->input->post('id_tiket') . ".png";
		$image_parts = explode(";base64,", $_POST['signed']);
		$image_type_aux = explode("image/", $image_parts[0]);
		$image_base64 = base64_decode($image_parts[1]);
		file_put_contents('upload/signature/' . $nama_gambar, $image_base64);

		$this->apl->updateData("tiket", array(
			'signature' => $nama_gambar,
			'encode_signature' => $image_parts[1],
		), array('id_tiket' => $this->input->post('id_tiket')));

		$this->pesan->pesan_success("Successfully Add Signature");
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_tambah_card()
	{

		/*
		$this->apl->updateData("tiket",
			array(
				'post' => 4,
				'tanggal_selesai' => $this->input->post('tanggal'),
			), array('id_tiket' => $this->input->post('id_tiket')));
		*/

		$data = array(
			'ket' => "No Kartu " . $this->input->post('nomor'),
			'tanggal' => $this->input->post('tanggal'),
			'id_tiket' => $this->input->post('id_tiket'),
			'id_admin' => $this->session->id_admin,
		);
		$this->apl->log(
			"TAMBAH No KARTU",
			"",
			json_encode($data),
			'tiket',
			$this->input->post('id_tiket')
		);
		$this->apl->insertData('tiket_histori', $data);

		$this->apl->updateData("card", array('hapus' => 1), array('id_tiket' => $this->input->post('id_tiket')));
		$card = array(
			'id_tiket' => $this->input->post('id_tiket'),
			'id_bast' => $this->apl->get_nilai_pilih("tiket", "id_bast", "id_tiket=" . $this->input->post('id_tiket')),
			'nomor' => $this->input->post('nomor'),
			'id_tag' => $this->apl->get_nilai_pilih(
				"tiket_detail",
				"id_tag",
				array(
					'id_tiket' => $this->input->post('id_tiket'),
					'tipe' => '1',
					'hapus' => '0',
				)
			),
			'id_admin' => $this->session->id_admin
		);
		$this->apl->insertData('card', $card);

		$this->apl->log(
			"TAMBAH CARD",
			"",
			json_encode($data),
			'card',
			$this->apl->get_nilai_pilih("card", "id_card", $card)
		);
		//$this->apl->insertData('tiket_histori', $data);
		$this->pesan->pesan_success("Successfully");
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_selesai_card()
	{

		$this->apl->updateData(
			"tiket",
			array(
				'post' => 4,
				'tanggal_selesai' => $this->input->post('tanggal'),
			),
			array('id_tiket' => $this->input->post('id_tiket'))
		);

		$data = array(
			'ket' => "Selesai",
			'tanggal' => $this->input->post('tanggal'),
			'id_tiket' => $this->input->post('id_tiket'),
			'id_admin' => $this->session->id_admin,
		);
		$this->apl->log(
			"TAMBAH SELESAI PENGERJAAN",
			"",
			json_encode($data),
			'tiket',
			$this->input->post('id_tiket')
		);
		$this->apl->insertData('tiket_histori', $data);
		$card = array(
			'id_tiket' => $this->input->post('id_tiket'),
			'id_bast' => $this->apl->get_nilai_pilih("tiket", "id_bast", "id_tiket=" . $this->input->post('id_tiket')),
			'nomor' => $this->input->post('nomor'),
			'id_tag' => $this->apl->get_nilai_pilih(
				"tiket_detail",
				"id_tag",
				array(
					'id_tiket' => $this->input->post('id_tiket'),
					'tipe' => '1',
					'hapus' => '0',
				)
			),
			'id_admin' => $this->session->id_admin
		);
		$this->apl->insertData('card', $card);

		$this->apl->log(
			"TAMBAH CARD",
			"",
			json_encode($data),
			'card',
			$this->apl->get_nilai_pilih("card", "id_card", $card)
		);
		$this->apl->insertData('tiket_histori', $data);
		$this->pesan->pesan_success("Successfully");
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_tutup()
	{
		$this->apl->updateData(
			"tiket",
			array(
				'post' => 5,
			),
			array('id_tiket' => $this->input->post('id_tiket'))
		);
		$data = array(

			'ket' => "Tutup Pekerjaan",
			'tanggal' => date('Y-m-d'),
			'id_tiket' => $this->input->post('id_tiket'),
			'id_admin' => $this->session->id_admin,
		);
		$this->apl->log(
			"TUTUP PENGERJAAN",
			"",
			json_encode($data),
			'tiket',
			$this->input->post('id_tiket')
		);
		$this->apl->insertData('tiket_histori', $data);
		$this->pesan->pesan_success("Successfully");
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_hold()
	{
		$this->apl->updateData(
			"tiket",
			array(
				'post' => 6,
				//'tanggal_selesai' => $this->input->post('tanggal'),

			),
			array('id_tiket' => $this->input->post('id_tiket'))
		);
		$data = array(

			'ket' => "Hold",
			'tanggal' => $this->input->post('tanggal'),
			'id_tiket' => $this->input->post('id_tiket'),
			'id_admin' => $this->session->id_admin,
		);
		$this->apl->log(
			"HOLD PEKERJAAN ",
			"",
			json_encode($data),
			'tiket',
			$this->input->post('id_tiket')
		);
		$this->apl->insertData('tiket_histori', $data);
		$this->pesan->pesan_success("Successfully Hold Job");
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_unhold()
	{
		$this->apl->updateData(
			"tiket",
			array(
				'post' => 3,
				//'tanggal_selesai' => $this->input->post('tanggal'),

			),
			array('id_tiket' => $this->input->post('id_tiket'))
		);
		$data = array(

			'ket' => "Un Hold",
			'tanggal' => $this->input->post('tanggal'),
			'id_tiket' => $this->input->post('id_tiket'),
			'id_admin' => $this->session->id_admin,
		);
		$this->apl->log(
			"UN HOLD PEKERJAAN ",
			"",
			json_encode($data),
			'tiket',
			$this->input->post('id_tiket')
		);
		$this->apl->insertData('tiket_histori', $data);
		$this->pesan->pesan_success("Successfully");
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_approval()
	{
		$this->apl->updateData(
			"tiket",
			array(
				'approv' => 1,
				'id_acc' => $this->session->id_admin,
			),
			array('id_tiket' => $this->input->post('id_tiket'))
		);


		$uid = $this->apl->get_nilai_pilih(
			"admin",
			"id_karyawan",
			array('id_admin' => $this->session->id_admin)
		);
		$a = $this->apl->getSelectedData("karyawan", array('id_karyawan' => $uid))->row();
		$val = "Nama Approval: " . $a->nama;
		$val .= " -- Note=" . $this->input->post('note');
		$val .= " -- Tanggal =" . date('Y-m-d');
		$val .= " -- TM =" . date('Y-m-d H:i:s');
		$val .= " -- TTD =" . site_url('karyawan/signature?id=' . $uid);

		$this->load->library('ciqrcode');
		$params['data'] = $val;
		$params['level'] = 'H';
		$params['size'] = 50;
		$params['cachedir'] = 'upload/qr_tiket/';
		$params['savename'] = 'upload/qr_tiket/signature-' . $this->input->post('id_tiket') . time() . ".png";
		$qr_code = $this->ciqrcode->generate($params);


		$data = array(

			'note' => $this->input->post('note'),
			'tanggal' => $this->input->post('tanggal'),
			'id_tiket' => $this->input->post('id_tiket'),
			'id_admin' => $this->session->id_admin,
			'jab' => 1,
			'post' => 4,
			'qr_code' => $qr_code,
		);
		$this->apl->log(
			"APPROVAL BM TIKET",
			"",
			json_encode($data),
			'tiket',
			$this->input->post('id_tiket')
		);
		$this->apl->insertData('tiket_approv', $data);
		$this->pesan->pesan_success("Successfully Approve");
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_pending()
	{
		$this->apl->updateData(
			"tiket",
			array(
				'post' => 1,
			),
			array('id_tiket' => $this->input->post('id_tiket'))
		);
		$data = array(
			'note' => $this->input->post('note'),
			'tanggal' => $this->input->post('tanggal'),
			'id_tiket' => $this->input->post('id_tiket'),
			'id_admin' => $this->session->id_admin,
			'jab' => 1,
			'status' => 0,
		);

		$this->apl->log(
			"RETURN BM TIKET",
			"",
			json_encode($data),
			'tiket',
			$this->input->post('id_tiket')
		);
		$this->apl->insertData('tiket_approv', $data);
		$this->pesan->pesan_success("Successfully Approve");
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_reject()
	{
		$this->apl->updateData(
			"tiket",
			array(
				'post' => 10,
			),
			array('id_tiket' => $this->input->post('id_tiket'))
		);
		$data = array(
			'note' => $this->input->post('note'),
			'tanggal' => $this->input->post('tanggal'),
			'id_tiket' => $this->input->post('id_tiket'),
			'id_admin' => $this->session->id_admin,
			'jab' => 1,
			'status' => 0,
		);

		$this->apl->log(
			"REJECT TIKET",
			"",
			json_encode($data),
			'tiket',
			$this->input->post('id_tiket')
		);
		$this->apl->insertData('tiket_approv', $data);
		$this->pesan->pesan_success("Successfully Reject");
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_add_note()
	{

		$data = array(
			'note' => $this->input->post('note'),
			'tanggal' => $this->input->post('tanggal'),
			'id_tiket' => $this->input->post('id_tiket'),
			'id_admin' => $this->session->id_admin,
			'jab' => 1,
			'status' => 0,
		);

		$this->apl->log(
			"REJECT BM TIKET",
			"",
			json_encode($data),
			'tiket',
			$this->input->post('id_tiket')
		);
		$this->apl->insertData('tiket_approv', $data);
		$this->pesan->pesan_success("Successfully Approve");
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_approv($id_tiket)
	{

		$t = $this->apl->getSelectedData("tiket", array('id_tiket' => $id_tiket))->row();
		$check_tipe = $t->tipe;
		//$post = ($check_tipe == '3' || $check_tipe == '5' || $check_tipe == '6') ? '2' : '3';
		$post = '3';
		$data = array(
			'post' => $post,
			'id_tr' => $this->session->id_admin,
			'tm_tr' => date('Y-m-d H:i:s')
		);
		if ($check_tipe == '5') {
			if ($t->id_dep == '2') {
				$data = array_merge(array('id_dept' => $this->session->id_admin), $data);
			}
		}

		$this->apl->log(
			"APPROV DEPT HEAD",
			json_encode($this->apl->getSelectedData(
				"tiket",
				array('id_tiket' => $id_tiket)
			)->row()),
			json_encode($data),
			'tiket',
			$id_tiket
		);
		$this->apl->updateData('tiket', $data, array('id_tiket' => $id_tiket));
		$this->pesan->pesan_success("Successfully Approve Request By Dept Head TR");

		$uid = $this->apl->get_nilai_pilih(
			"admin",
			"id_karyawan",
			array('id_admin' => $this->session->id_admin)
		);
		$a = $this->apl->getSelectedData("karyawan", array('id_karyawan' => $uid))->row();
		$val = "Nama Approval: " . $a->nama;
		$val .= " -- Note=" . $this->input->post('note');
		$val .= " -- Tanggal =" . date('Y-m-d');
		$val .= " -- TM =" . date('Y-m-d H:i:s');
		$val .= " -- TTD =" . site_url('karyawan/signature?id=' . $uid);

		$this->load->library('ciqrcode');
		$params['data'] = $val;
		$params['level'] = 'H';
		$params['size'] = 50;
		$params['cachedir'] = 'upload/qr_tiket/';
		$params['savename'] = 'upload/qr_tiket/signature-' . $this->input->post('id_tiket') . time() . ".png";
		$qr_code = $this->ciqrcode->generate($params);
		$data = array(
			'note' => $this->input->post('note'),
			'tanggal' => $this->input->post('tanggal'),
			'id_tiket' => $id_tiket,
			'id_admin' => $this->session->id_admin,
			'post' => 1,
			'qr_code' => $qr_code,
		);
		$this->apl->insertData('tiket_approv', $data);

		/**
		 * Jika Operasioanl otomatis dengan dept head
		 */
		if ($check_tipe == '5') {
			if ($t->id_dep == '2') {
				$data = array(
					'note' => $this->input->post('note'),
					'tanggal' => $this->input->post('tanggal'),
					'id_tiket' => $id_tiket,
					'id_admin' => $this->session->id_admin,
					'post' => 3,
					'qr_code' => $qr_code,
				);
				$this->apl->insertData('tiket_approv', $data);
			}
		}
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_approval_dep()
	{
		$this->apl->updateData(
			"tiket",
			array(
				'id_dept' => $this->session->id_admin,
			),
			array('id_tiket' => $this->input->post('id_tiket'))
		);


		$uid = $this->apl->get_nilai_pilih(
			"admin",
			"id_karyawan",
			array('id_admin' => $this->session->id_admin)
		);
		$a = $this->apl->getSelectedData("karyawan", array('id_karyawan' => $uid))->row();
		$val = "Nama Approval: " . $a->nama;
		$val .= " -- Note=" . $this->input->post('note');
		$val .= " -- Tanggal =" . date('Y-m-d');
		$val .= " -- TM =" . date('Y-m-d H:i:s');
		$val .= " -- TTD =" . site_url('karyawan/signature?id=' . $uid);

		$this->load->library('ciqrcode');
		$params['data'] = $val;
		$params['level'] = 'H';
		$params['size'] = 50;
		$params['cachedir'] = 'upload/qr_tiket/';
		$params['savename'] = 'upload/qr_tiket/signature-' . $this->input->post('id_tiket') . time() . ".png";
		$qr_code = $this->ciqrcode->generate($params);


		$data = array(
			'note' => $this->input->post('note'),
			'tanggal' => $this->input->post('tanggal'),
			'id_tiket' => $this->input->post('id_tiket'),
			'id_admin' => $this->session->id_admin,
			'jab' => 0,
			'post' => 3,
			'qr_code' => $qr_code,
		);

		$this->apl->log(
			"APPROVAL DEPT TUJUAN TIKET",
			"",
			json_encode($data),
			'tiket',
			$this->input->post('id_tiket')
		);
		$this->apl->insertData('tiket_approv', $data);
		$this->pesan->pesan_success("Successfully Approve");
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_approval_eng()
	{
		$this->apl->updateData(
			"tiket",
			array(
				'id_eng' => $this->session->id_admin,
			),
			array('id_tiket' => $this->input->post('id_tiket'))
		);
		$data = array(
			'note' => $this->input->post('note'),
			'tanggal' => $this->input->post('tanggal'),
			'id_tiket' => $this->input->post('id_tiket'),
			'id_admin' => $this->session->id_admin,
			'jab' => 0,
		);
		$this->apl->log(
			"APPROVAL ENG TIKET",
			"",
			json_encode($data),
			'tiket',
			$this->input->post('id_tiket')
		);
		$this->apl->insertData('tiket_approv', $data);
		$this->pesan->pesan_success("Successfully Approve");
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_approval_done()
	{
		$this->apl->updateData(
			"tiket",
			array(
				'ver_tutup' => 0,
			),
			array('id_tiket' => $this->input->post('id_tiket'))
		);

		$data = array(

			'note' => $this->input->post('note'),
			'tanggal' => $this->input->post('tanggal'),
			'id_tiket' => $this->input->post('id_tiket'),
			'id_admin' => $this->session->id_admin,
			'jab' => 1,
		);
		$this->apl->log(
			"APPROVAL BM TIKET DONE",
			"",
			json_encode($data),
			'tiket',
			$this->input->post('id_tiket')
		);
		$this->apl->insertData('tiket_approv', $data);
		$this->pesan->pesan_success("Successfully Approve");
		echo json_encode(array("status" => TRUE));
	}

	public function form_berkas()
	{
		$data['id_vendor'] = $this->input->post('id_vendor');
		$data['berkas'] = $this->tiket->getBerkasVendor($data['id_vendor'])->result();
		$this->load->view('tiket/view_berkas', $data);
	}

	public function ajax_delete($id)
	{
		$this->apl->updateData("tiket", array('hapus' => 1), array('id_tiket' => $id));
		$this->apl->updateData(
			"tiket_detail",
			array(
				'hapus' => 1,
				'hapus_tiket' => 1,
			),
			array('id_tiket' => $id)
		);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete_file($id)
	{
		$this->apl->updateData("tiket_histori", array('hapus' => 1), array('id' => $id));

		echo json_encode(array("status" => TRUE));
	}
}
