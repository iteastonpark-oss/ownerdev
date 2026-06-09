<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 8/11/2016
 * Time: 10:34 AM
 */
?>

<?php
$controller = $this->uri->segment(1) . '/' . $this->uri->segment(2);
?>

<div class="card">

	<?php
	//$this->load->view('utility/menu');

	?>

	<div class="table-responsive">
		<table class="table table-datatables table-hover dt-responsive nowrap table-sm" width="100%"
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
				"url": "<?php echo site_url($controller . '/ajax_list' . "?" . $_SERVER['QUERY_STRING']); ?>",
				"type": "POST",
				"data": {
					"id_tag": "<?= $id_tag;?>",
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
	 *  Menampilkan FORM TAMBAH DATA
	 */
	function add_data() {
		save_method = 'add';
		$('#btnSave').html("SIMPAN").show();
		$('#form')[0].reset(); // reset form on modals
		$('#modal_form').modal('show'); // show bootstrap modal
		$('.modal-title').text('Form Add'); // Set Title to Bootstrap modal title
		$('.modal-body').show(); //
		$('#page-input').show(); //
		$('#page-edit').hide(); //

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
		$('#page-input').hide(); //
		$('#page-edit').show(); //

		$.ajax({
			url: "<?php echo site_url($controller . '/ajax_edit/') ?>/" + id,
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
				$('.modal-title').text("Form Update"); // Set title to Bootstrap modal title


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

		$('#btnSave').html("Delete").show();
		$('#form')[0].reset(); // reset form on modals
		$('#modal_form').modal('show'); // show bootstrap modal
		$('.modal-title').text('Delete'); // Set Title to Bootstrap modal title
		$('.modal-body').show(); //
		$('#page-hapus').show(); //
		$('#page-edit').hide(); //
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
				url = "<?php echo site_url($controller . '/ajax_delete') ?>/" + hapus_id;

			}
			if (save_method == 'add') {
				url = "<?php echo site_url($controller . '/ajax_add') ?>";
			}
			if (save_method == 'update') {
				url = "<?php echo site_url($controller . '/ajax_update') ?>";
			}
			console.log(url);
			var data = $('#form').serialize();
			//   alert(url + data);


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
						<div id="page-hapus">Delete This Data ?</div>
						<div id="page-input">

							<input type="hidden" name="id_rekening" value="">
							<input type="hidden" name="id_tag" value="<?= $id_tag; ?>">
							<div class="form-group">
								<label for="">Add Unit BAST</label>
								<?= $this->dropdown_model->getDropdownUnitRekening("id_unit", "",
										$id_tag, 'class="form-control satu" required'); ?>

							</div>
							<div class="form-group">
								<label for="">Meter Account</label>
								<input type="text" name="no_rekening" class="form-control" value="" required>
							</div>
							<div class="form-group">
								<label for="">Start</label>
								<input type="number" name="meter_awal" class="form-control" value="" required>
							</div>
							<div class="form-group">
								<label for="">Start Month</label>
								<?= $this->dropdown_model->getDropdownBulan("bulan", date('m'), 'class="form-control" required'); ?>
							</div>
							<div class="form-group">
								<label for="">Start Year</label>
								<?= $this->dropdown_model->getDropdownTahun("tahun", date('Y'), 'class="form-control" required'); ?>
							</div>

						</div>
						<div id="page-edit">
							<input type="hidden" name="id_rekening" value="">
							<div class="form-group">
								<label for="">Meter Account</label>
								<input type="text" name="rekening" class="form-control" value="" required>
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

