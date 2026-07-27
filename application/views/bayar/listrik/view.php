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
			<div class="row mb--5 mt--4">
				<div class="col-md-4">
					<div class="form-group">
						<label>Period</label>
						<!--
						<input type="month" name="bulan" class="form-control " value="<?= $bulan; ?>"
							   onchange="this.form.submit();">
						-->
						<?php
						echo $this->dropdown_model->getDropdownTahun("tahun", $tahun,
								'class="form-control" required onchange="this.form.submit()"');
						?>
					</div>
				</div>
			</div>
		</form>
	</div>
	<div class="table-responsive">
		<table class="table table-datatables table-hover dt-responsive nowrap table-sm" width="100%"
			   cellspacing="0">
			<thead>
			<tr class="active">
				<th>No</th>
				<th>Form</th>
				<th>Total</th>
				<th></th>
			</tr>
			</thead>
			<tfoot>
			<tr>
				<td></td>
				<th>Total</th>
				<th><span class="pull-right">Rp. <?= $this->apl->number_format($jumlah, 1); ?></span></th>
				<td></td>
			</tr>
			</tfoot>
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
			"paging": false,
			"filter": false,
			"bInfo": false,
			"ajax": {
				"url": "<?php echo site_url($controller . '/ajax_list' . "?" . $_SERVER['QUERY_STRING']); ?>",
				"type": "POST",
				"data": {
					'id_unit': '<?= $id_unit;?>',
					'id_tag': '<?= $id_tag;?>',
					'tanggal_awal': '<?= $tanggal_awal;?>',
					'tanggal_ahir': '<?= $tanggal_ahir;?>',
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


	var save_method;
	var id;


	$(document).on("click", '.bayar', function (e) {

		save_method = 'tambah_bayar';
		$('#modal_form').modal('show');
		$('#btnSave').html("Pay").show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url($controller . '/form_bayar/') ?>";
		$('.modal-title').text("Add Payment"); // Set title to Bootstrap modal title
		$.post(
			url,
			function (data) {
				$("#page-view").html(data).show();
			}
		);

	});

	$(document).on("click", '.edit_bayar', function (e) {

		save_method = 'update_bayar';

		id = $(this).data("id");
		$('#modal_form').modal('show');
		$('#btnSave').html("Update").show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url($controller . '/form_edit/') ?>" + id;
		$('.modal-title').text("Update Payment"); // Set title to Bootstrap modal title
		$.post(
			url,
			function (data) {
				$("#page-view").html(data).show();
			}
		);

	});


	function delete_data(id) {
		save_method = 'hapus';
		hapus_id = id;

		$('#btnSave').html("HAPUS").show();
		$('#form')[0].reset(); // reset form on modals
		$('#modal_form').modal('show'); // show bootstrap modal
		$('.modal-title').text('HAPUS <?php echo $controller; ?>'); // Set Title to Bootstrap modal title
		$('.modal-body').show(); //
		$('#page-hapus').html("Hapus data pembayaran ini ?").show(); //
	}

	$(document).ready(function () {
		$("#btnSave").on('click', function (e) {
			e.stopPropagation(); // Jika gagal masih muncul
			save();
		});

		function save() {
			if (save_method == 'hapus') {
				url = "<?php echo site_url($controller . '/ajax_delete') ?>/" + hapus_id;
			}
			if (save_method == 'tambah_bayar') {
				url = "<?php echo site_url($controller . '/ajax_add_bayar') ?>";
			}
			if (save_method == 'update_bayar') {
				url = "<?php echo site_url($controller . '/ajax_update_bayar') ?>";
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
							if (save_method == 'bayar') {
								window.open("<?php echo site_url($controller . '/print_kwt/') ?>/" + id, '_blank').focus();
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
