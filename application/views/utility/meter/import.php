<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 8/11/2016
 * Time: 10:34 AM
 */

$controller = $this->uri->segment(1) . '/' . $this->uri->segment(2);
$this->dropdown_model = new Dropdown_Model();
?>

<div class="card">
	<form action="" method="post">

		<div class="card-header py-0">
			<h4 class="card-title"><?php echo $judul; ?></h4>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Periode</label>
						<div class="input-group">
							<?php
							echo $this->dropdown_model->getDropdownBulan("bulan", $bulan,
									'class="form-control" onchange="this.form.submit()"')
							?>

							<?php
							echo $this->dropdown_model->getDropdownTahun("tahun", $tahun,
									'class="form-control" onchange="this.form.submit()"')
							?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Status Import</label>
						<?php

						$options = array(
								'' => '--Pilih Status Import--',
								'1' => 'Success',
								'2' => 'Failde',
						);
						echo form_dropdown("status", $options, $status,
								'class="form-control" onchange="this.form.submit()"');
						?>

					</div>
				</div>
			</div>

		</div>
	</form>
	<form action="<?php echo site_url($controller . '/actions_import'); ?>"
		  method="post"
		  method="post" enctype="multipart/form-data">

		<div class="card-header py-0">
			<input type="hidden" name="id_tag" value="<?= $id_tag; ?>">
			<input type="hidden" name="bulan" value="<?= $bulan; ?>" required>
			<input type="hidden" name="tahun" value="<?= $tahun; ?>" required>
			<div class="row mt-2 mb-1">
				<div class="col-md-6">
					<div class="custom-file">
						<input type="file" name="file" class="custom-file-input"
							   id="customFile"
							   required>
						<label class="custom-file-label" for="customFile">Choose file</label>
					</div>

					<!--
					<div class="input-group">
						<input type="file" name="file" required>
						<span class="input-group-btn"></span>
					</div>
					-->

				</div>
				<div class="col-md-6">
					<button type="submit" id="btn-import" class="btn btn-primary"><i class="fa fa-upload"></i> Upload
					</button>

				</div>
			</div>
		</div>
	</form>

	<div class="card-body">
		<table class="table table-datatables table-hover dt-responsive nowrap table-sm small" width="100%"
			   cellspacing="0">
			<thead>
			<tr class="active">
				<?php
				foreach ($field as $tr) {
					echo '<th>' . $tr . '</th>';
				}
				?>
			</tr>
			</thead>
		</table>

	</div>
</div>
<script type="text/javascript">

	$('input[type="file"]').change(function (e) {
		var fileName = e.target.files[0].name;
		$('.custom-file-label').html(fileName);
	});

	/**
	 *  View List data Ke Tabel
	 */
	$(document).ready(function () {
		table = $('.table-datatables').DataTable({
			"processing": true, //Feature control the processing indicator.
			"serverSide": true, //Feature control DataTables' server-side processing mode.
			"order": [], //Initial no order.
			// Load data for the table's content from an Ajax source
			"ajax": {
				"url": "<?php echo site_url($controller . '/ajax_list_import' . "?" . $_SERVER['QUERY_STRING']); ?>",
				"type": "POST",
				"data": {
					"id_tag": "<?= $id_tag;?>",
					"bulan": "<?= $bulan;?>",
					"tahun": "<?= $tahun;?>",
					"status": "<?= $status;?>",
				}
			},
			"columnDefs": [
				{
					"searchable": false,
					"orderable": false,
					"targets": 0
				},
				{
					"responsivePriority": 1,
					"targets": -1
				}
			],
			"order": [[0, 'asc']],
		});
		table.on('order.dt search.dt', function () {
			table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
				cell.innerHTML = i + 1;
			});
		}).draw();
	});

	function reload_table() {
		table.ajax.reload(null, false); //reload datatable ajax
	}


	/**
	 *  Menampilkan FORM EDIT DATA
	 */
	function edit_data(id) {
		save_method = 'update';
		$('.modal-body').show(); //
		$('#btnSave').html("UPDATE").show();
		$('#form')[0].reset(); // reset form on modals
		//Ajax Load data from ajax
		$('#page-input').show();

		$.ajax({
			url: "<?php echo site_url($controller . '/ajax_edit_error/') ?>/" + id,
			type: "GET",
			dataType: "JSON",
			success: function (data) {
				$.each(data, function (key, value) {

					if (key != "photo") {
						$('[name=' + key + ']').val(value);
						//console.log(key);
					}
				});
				$('#modal_form').modal('show'); // show bootstrap modal when complete loaded
				$('.modal-title').text("Edit  <?php echo $controller; ?>"); // Set title to Bootstrap modal title


			},
			error: function (jqXHR, textStatus, errorThrown) {
				alert('Error get data from ajax');
			}
		});
	}


	/**
	 *  MENGHAPUS DATA
	 */
	function delete_data(id) {
		save_method = 'hapus';
		hapus_id = id;

		$('#btnSave').html("HAPUS").show();
		$('#form')[0].reset(); // reset form on modals
		$('#modal_form').modal('show'); // show bootstrap modal
		$('.modal-title').text('HAPUS <?php echo $controller; ?>'); // Set Title to Bootstrap modal title
		$('.modal-body').show(); //
		$('#page-hapus').show();
	}


	/**
	 * Simpan / Update /hapus (ACTION CRUD)
	 */

	$(document).ready(function () {
		$("#btnSave").on('click', function (e) {
			e.stopPropagation(); // Jika gagal masih muncul


			save();

		});

		function save() {
			if (save_method == 'hapus') {
				url = "<?php echo site_url($controller . '/ajax_delete_error') ?>/" + hapus_id;

			}
			if (save_method == 'update') {
				url = "<?php echo site_url($controller . '/ajax_update_error') ?>";
			}
			console.log(url);
			var data = $('#form').serialize();
			$("#form").validate({
				rules: {
					name: "required",
				},
				messages: {
					required: "This field is required.",
				},
				submitHandler: function (form) {
					$.ajax({
						url: url,
						type: "POST",
						//data: $('#form').serialize(),
						//dataType: "JSON",

						processData: false,
						contentType: false,
						data: new FormData($('#form')[0]),

						success: function (data) {
							//if success close modal and reload ajax table
							$('#modal_form').modal('hide');//Hide Modal Bootstrap
							reload_table();
							notifikasi();

						},
						error: function (jqXHR, textStatus, errorThrown) {
							alert('Error adding / update data');
						}
					});

				}
			});
			e.preventDefault();
		}
	});


	const btn = document.getElementById('btn-import');
	btn.addEventListener('click', () => {
		// 👇️ hide button
		btn.style.display = 'none';
		$('.loader').show();
	});
</script>

<!-- Bootstrap modal -->
<div class="modal fade" data-backdrop="static" id="modal_form" role="dialog">
	<div class="modal-dialog  modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Modal title</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<form id="form" method="post" class="form-horizontal">
				<div class="modal-body">
					<div class="container-fluid">
						<div id="page-hapus">Apakah Anda Yakin Akan Menghapus Data ini ?</div>
						<div id="page-input">
							<input type="hidden" name="id_tag" value="<?= $id_tag; ?>">
							<input type="hidden" name="id_meter">
							<div class="form-group">
								<label>Periode</label>
								<div class="input-group">
									<?php
									echo $this->dropdown_model->getDropdownBulan("bulan", $bulan,
											'class="form-control" required')
									?>

									<?php
									echo $this->dropdown_model->getDropdownTahun("tahun", $tahun,
											'class="form-control" required')
									?>
								</div>
							</div>

							<div class="form-group">
								<label for="">No Rekening Unit</label>
								<?php
								echo $this->dropdown_model->getDropdownRekening("id_rekening", '',
										$id_tag, 'class="form-control" required')
								?>

							</div>
							<div id="detail_pelanggan"></div>

							<div class="form-group">
								<label for="">Meteran Terakhir</label>
								<input type="number" name="meter" class="form-control" value="0"
									   required>
							</div>


						</div>
						<div id="page-view"></div>
						<div id="page-menu"></div>
					</div>
				</div>

				<div class="modal-footer">
					<?php echo $this->tombol->get_simpan_js("save()", "cancel()"); ?>
				</div>
			</form>
		</div>
	</div>
</div>

