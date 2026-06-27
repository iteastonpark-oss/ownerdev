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
	$this->load->view('bayar/invoice/menu');
	?>
	<div class="card-header">

		<form action="" method="post">
			<div class="row   mt--4 mb--5">
				<div class="col-md-4">
					<div class="form-group">
						<label for="">Choose Unit BAST</label>
						<?= $this->dropdown_model->getDropdownUnit('id_unit', $id_unit, 'class="form-control satu" onchange="this.form.submit()"'); ?>

					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label>Period</label>
						<div class="input-group">
							<input type="date" name="tanggal_awal" class="form-control" value="<?= $tanggal_awal; ?>">
							<input type="date" name="tanggal_ahir" class="form-control" value="<?= $tanggal_ahir; ?>">
							<div class="input-group-append">
								<button type="submit" class="btn btn-primary">
									<i class="fa fa-calendar"> </i> Submit
								</button>
							</div>
						</div>
					</div>
				</div>

			</div>
		</form>
	</div>
	<div class="table-responsive">
		<table class="table table-datatables table-hover  nowrap table-sm" width="100%" cellspacing="0">
			<thead>
				<tr>
					<th colspan="6"></th>
					<th colspan="7" class="text-center">Allocation</th>
					<th></th>
					<th></th>
				</tr>
				<tr class="active">
					<?php
					foreach ($field as $tr) {
						echo '<th>' . $tr . '</th>';
					}
					?>
				</tr>
			</thead>
			<!--
			<tfoot>
			<tr>
				<td></td>
				<td></td>
				<td>Total</td>
				<td class="bg-primary">
					<span class="pull-right">Rp. <?= $this->apl->number_format($jumlah, 1); ?></span>
				</td>
			</tr>
			</tfoot>
	-->
		</table>

	</div>

	<div class="card-footer">
		<table class="table table-borderless col-md-6">
			<tr>
				<th class="pull-right">Total</th>
				<td>: <span class="pull-right">Rp. <?= $this->apl->number_format($jumlah, 1); ?></span></td>
			</tr>
		</table>
	</div>
</div>
<script type="text/javascript">
	/**
	 *  View List data Ke Tabel
	 */
	$(document).ready(function() {
		table = $('.table-datatables').DataTable({
			"processing": true, //Feature control the processing indicator.
			"serverSide": true, //Feature control DataTables' server-side processing mode.
			"order": [], //Initial no order.
			"scrollX": true,
			"ajax": {
				"url": "<?php echo site_url($controller . '/ajax_list_report' . "?" . $_SERVER['QUERY_STRING']); ?>",
				"type": "POST",
				"data": {
					'id_unit': '<?= $id_unit; ?>',
					'tanggal_awal': '<?= $tanggal_awal; ?>',
					'tanggal_ahir': '<?= $tanggal_ahir; ?>',
				}
			},
			"columnDefs": [{
					"searchable": false,
					"orderable": false,
					"targets": 0
				},
				{
					"responsivePriority": 1,
					"targets": -1
				}
			],
			"fixedColumns": {
				leftColumns: 6,
				rightColumns: 1,
			},
			"order": [
				[0, 'asc']
			],
		});
		table.on('order.dt search.dt', function() {
			table.column(0, {
				search: 'applied',
				order: 'applied'
			}).nodes().each(function(cell, i) {
				cell.innerHTML = i + 1;
			});
		}).draw();
	});

	function reload_table() {
		table.ajax.reload(null, false); //reload datatable ajax
	}

	var save_method;
	var id;
	$(document).on("click", '.edit_bayar', function(e) {

		id = $(this).data("id");
		save_method = 'update_bayar';
		$('#modal_form').modal('show');
		$('#btnSave').html("Update Bayar").show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url($controller . '/update_bayar/') ?>/" + id;
		$('.modal-title').text("Edit Pembayaran"); // Set title to Bootstrap modal title
		$.post(
			url,
			function(data) {
				$("#page-view").html(data).show();
			}
		);
	});
	$(document).on("click", '.edit_bayar_detail', function(e) {

		id = $(this).data("id");
		save_method = 'update_bayar_detail';
		$('#modal_form').modal('show');
		$('#btnSave').html("Update Bayar").show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url($controller . '/update_bayar_detail/') ?>/" + id;
		$('.modal-title').text("Edit Pembayaran"); // Set title to Bootstrap modal title
		$.post(
			url,
			function(data) {
				$("#page-view").html(data).show();
			}
		);

	});

	$(document).on("click", '.alokasi_bayar', function(e) {

		id = $(this).data("id");
		save_method = 'alokasi_bayar';
		$('#modal_form').modal('show');
		$('#btnSave').html("Alokasi Bayar").show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url($controller . '/alokasi_bayar/') ?>/" + id;
		$('.modal-title').text("Alokasi Pembayaran"); // Set title to Bootstrap modal title
		$.post(
			url,
			function(data) {
				$("#page-view").html(data).show();
			}
		);

	});




	$(document).on("click", '.whatsapp', function(e) {
		save_method = 'whatsapp';
		id = $(this).data('id');
		$('#modal_form').modal('show');
		$('#btnSave').html("Send").show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url('pesan/wa/ajax_get_bayar_billing/') ?>/" + id;
		$('.modal-title').text("Kirim Whatsapp"); // Set title to Bootstrap modal title
		$.post(
			url,
			function(data) {
				$("#page-view").html(data).show();


			}
		);
	});

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
		$('#page-hapus').html("Hapus data pembayaran ini ?").show(); //
	}

	$(document).ready(function() {
		$("#btnSave").on('click', function(e) {
			e.stopPropagation(); // Jika gagal masih muncul
			save();
		});

		function save() {
			if (save_method == 'hapus') {
				url = "<?php echo site_url($controller . '/ajax_delete') ?>/" + hapus_id;
			}
			if (save_method == 'update_bayar') {
				url = "<?php echo site_url($controller . '/ajax_update_bayar') ?>";
			}
			if (save_method == 'update_bayar_detail') {
				url = "<?php echo site_url($controller . '/ajax_update_bayar_detail') ?>";
			}
			if (save_method == 'alokasi_bayar') {
				url = "<?php echo site_url($controller . '/ajax_alokasi_bayar') ?>";
			}
			if (save_method == 'whatsapp') {
				url = "<?php echo site_url('pesan/wa/ajax_kirm_wa_kwt') ?>";
			}
			console.log(url);
			$("#form").validate({
				rules: {
					name: "required",
				},
				messages: {
					required: "This field is required.",
				},

				submitHandler: function(form) {
					$.ajax({
						url: url,
						type: "POST",
						processData: false,
						contentType: false,
						data: new FormData($('#form')[0]),

						success: function(data) {
							//if success close modal and reload ajax table
							$('#modal_form').modal('hide'); //Hide Modal Bootstrap
							reload_table();
							if (save_method == 'bayar') {
								window.open("<?php echo site_url($controller . '/print_kwt/') ?>/" + id, '_blank').focus();
							}
							notifikasi();
						},
						error: function(jqXHR, textStatus, errorThrown) {
							alert('Error adding / update data');
						}
					});

				}
			});
			e.preventDefault();
		}
	});
</script>