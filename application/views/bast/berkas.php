<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 8/11/2016
 * Time: 10:34 AM
 */
?>

<?php
$controller = $this->uri->segment(1) . "/" . $this->uri->segment(2);
?>
<div class="card">
	<?php $this->load->view('unit/bast/menu'); ?>
	<div class="card-body">
		<div class="card-title"><?= $judul; ?></div>
		<table class="table table-datatables table-hover dt-responsive small nowrap" width="100%" cellspacing="0">
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
				"url": "<?php echo site_url($controller . '/ajax_list_berkas' . "?" . $_SERVER['QUERY_STRING']); ?>",
				"type": "POST",
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
	 *  Menampilkan FORM TAMBAH DATA
	 */
	function add_data() {
		save_method = 'add';
		$('#btnSave').html("SIMPAN").show();
		$('#form')[0].reset(); // reset form on modals
		$('#modal_form').modal('show'); // show bootstrap modal
		$('.modal-title').text('<?php echo "Tambah Data " . $controller; ?>'); // Set Title to Bootstrap modal title
		$('.modal-body').show(); //
		$('#page-input').show(); //

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
		$('#page-input').show(); //

		$.ajax({
			url: "<?php echo site_url($controller . '/ajax_edit_berkas/') ?>/" + id,
			type: "GET",
			dataType: "JSON",
			success: function (data) {
				$.each(data, function (key, value) {
					if (key != "file") {
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
		$('#page-hapus').show(); //
	}


	/**
	 * Simpan / Update /hapus (ACTION CRUD)
	 */

	$(document).ready(function () {
		$("#btnSave").on('click', function (e) {
			e.stopPropagation(); // Jika gagal masih muncul
			//alert("a");
			save();

		});

		function save() {
			if (save_method == 'hapus') {
				url = "<?php echo site_url($controller . '/ajax_delete_berkas') ?>/" + hapus_id;

			}
			if (save_method == 'add') {
				url = "<?php echo site_url($controller . '/ajax_add_berkas') ?>";
			}
			if (save_method == 'update') {
				url = "<?php echo site_url($controller . '/ajax_update_berkas') ?>";
			}
			console.log(url);
			var data = $('#form').serialize();
			//   alert(url + data);
			$("#form").validate({

				rules: {
					name: "required"
				},
				messages: {
					required: "This field is required.",
				},

				submitHandler: function (form) {

					$.ajax({
						url: url,
						type: "POST",
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

							<input type="hidden" name="id">
							<input type="hidden" name="id_bast" value="<?php echo $_GET['id']; ?>">


							<div class="form-group">
								<label for="" class="control-label">File Name</label>
								<?php
								echo $this->dropdown_model
										->getDropdownUploadByNama('id_upload', ''
												, 'class="form-control" required', '');
								?>
							</div>

							<div class="form-group">
								<label for="" class="control-label">Nomor</label>
								<input type="text" name="nomor" class="form-control">
							</div>


							<div class="form-group">
								<label for="">Upload File</label>

								<div class="custom-file">
									<input type="file" name="file" class="custom-file-input" id="inputGroupFile"
									>
									<label id="label_link_download" class="custom-file-label"
										   for="link_download">File</label>


								</div>
							</div>
							<script>
								$('.custom-file-input').on('change', function () {
									let fileName = Array.from(this.files).map(x => x.name).join(', ')
									$(this).next('.custom-file-label').html(fileName);

								});
							</script>

						</div>
						<div id="page-view"></div>
					</div>
				</div>

				<div class="modal-footer">
					<?php echo $this->tombol->get_simpan_js("save()", "cancel()"); ?>

				</div>
			</form>
		</div>
	</div>
</div>

