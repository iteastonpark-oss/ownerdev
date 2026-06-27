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

	<div class="card-header">

		<form action="" method="post">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label for="">Pilih No Unit</label>
						<?= $this->dropdown_model->getDropdownUnit('id_unit', $id_unit, 'class="form-control satu" onchange="this.form.submit()"'); ?>

					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label>Periode</label>
						<div class="input-group">
							<input type="date" name="tanggal_awal"
								   class="form-control form-control-sm" value="<?= $tanggal_awal; ?>">
							<input type="date" name="tanggal_ahir"
								   class="form-control form-control-sm" value="<?= $tanggal_ahir; ?>">
							<div class="input-group-append">

								<button type="submit" class="btn btn-primary btn-sm">
									<i class="fa fa-calendar"> </i> Pilih
								</button>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-4">

					<div class="form-group">
						<label>Metode Pengiriman</label>
						<?php
						$options = array(
							'' => '--PILIH PENGIRIMAN--',
							'1' => 'KURIR',
							'2' => 'EMAIL',
						);
						echo form_dropdown('kirim', $options, $kirim, array(
							'class' => 'form-control form-control-sm',
							'onchange' => 'this.form.submit()',
						));
						?>
					</div>
				</div>

			</div>
		</form>
	</div>
	<div class="card-body">
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
					'id_unit': '<?= $id_unit;?>',
					'tanggal_awal': '<?= $tanggal_awal;?>',
					'tanggal_ahir': '<?= $tanggal_ahir;?>',
					'kirim': '<?= $kirim;?>',
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

	$(document).on("click", '.edit_invoice_list', function (e) {
		save_method = 'edit_invoice_list';
		id = $(this).data('id');
		$('#modal_form').modal('show');
		$('#btnSave').html("Edit Invoice").show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url($controller . '/form_edit_invoice_list/') ?>/" + id;
		$('.modal-title').text("FORM EDIT INVOICE"); // Set title to Bootstrap modal title
		$.post(
			url,
			function (data) {
				$("#page-view").html(data).show();

			}
		);
	});


	$(document).on("click", '.print_data', function (e) {
		save_method = 'print';
		id = $(this).data('id');
		$('#modal_form').modal('show');
		$('#btnSave').html("Print").show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url($controller . '/print_priview_invoice/') ?>/" + id;
		$('.modal-title').text("Print Priview"); // Set title to Bootstrap modal title

		$.post(
			url,
			function (data) {
				$("#page-view").html(data).show();
			}
		);
	});

	$(document).on("click", '.hapus_inv', function (e) {
		save_method = 'hapus_inv';
		id = $(this).data('id');
		$('#modal_form').modal('show');
		$('#btnSave').html("Hapus").show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url($controller . '/print_priview_invoice/') ?>/" + id;
		$('.modal-title').text("Hapus Invoice"); // Set title to Bootstrap modal title
		$.post(
			url,
			function (data) {
				$("#page-view").html(data).show();
			}
		);
	});


	$(document).ready(function () {
		$("#btnSave").on('click', function (e) {
			e.stopPropagation(); // Jika gagal masih muncul
			save();
		});

		function save() {
			if (save_method == 'edit_invoice_list') {
				url = "<?php echo site_url($controller . '/ajax_edit_invoice_list') ?>";
			}
			if (save_method == 'hapus_inv') {
				url = "<?php echo site_url($controller . '/ajax_hapus_invoice/') ?>" + id;
			}
			if (save_method == 'print') {

				url = "<?php echo site_url($controller . '/print_invoice/') ?>";
				window.open("<?php echo site_url($controller . '/invoice_print/') ?>/" + id, '_blank').focus();

			}
			console.log(url);
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
						processData: false,
						contentType: false,
						data: new FormData($('#form')[0]),

						success: function (data) {
							//if success close modal and reload ajax table
							$('#modal_form').modal('hide');//Hide Modal Bootstrap
							reload_table();
							//notifikasi();
							if (save_method == 'print') {
								//alert("test");
								window.open("<?php echo site_url($controller . '/invoice_print/') ?>/" + id, '_blank').focus();
							}
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
