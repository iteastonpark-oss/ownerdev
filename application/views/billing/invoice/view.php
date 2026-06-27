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
	<div class="card-header ">

		<form action="" method="post">
			<div class="row mb--5 mt--4">
				<div class="col-md-3">
					<div class="form-group">
						<label for="">Choose Unit</label>
						<?= $this->dropdown_model->getDropdownUnit('id_unit', $id_unit, 'class="form-control satu" onchange="this.form.submit()"'); ?>

					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Period</label>
						<div class="input-group">
							<input type="date" name="tanggal_awal" class="form-control" value="<?= $tanggal_awal; ?>">
							<input type="date" name="tanggal_ahir" class="form-control" value="<?= $tanggal_ahir; ?>">
							<div class="input-group-append">

								<button type="submit" class="btn btn-primary btn-sm">
									<i class="fa fa-calendar"> </i> Submit
								</button>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-3">

					<div class="form-group">
						<label>Shipping Method</label>
						<?php
						echo $this->dropdown_model->getDropdownPengiriman(
							'kirim',
							$kirim,
							'class="form-control" onchange="this.form.submit();"'
						);

						/*
						$options = array(
								'' => '--Choose Shipping--',
								'1' => 'Courier',
								'2' => 'EMAIL',
						);
						echo form_dropdown('kirim', $options, $kirim, array(
								'class' => 'form-control',
								'onchange' => 'this.form.submit()',
						));
						*/
						?>
					</div>
				</div>

			</div>
		</form>
	</div>
	<div class="table-responsive">
		<table class="table table-datatables table-hover dt-responsive nowrap table-sm small" width="100%" cellspacing="0">
			<thead>
				<tr class="active">
					<?php
					foreach ($field as $tr) {
						echo '<th>' . $tr . '</th>';
					}
					?>
				</tr>
			</thead>
			<tfoot>
				<tr class="active bg-secondary">
					<td></td>
					<td></td>
					<td>Total Per Page</td>
					<th>Rp. <span class="float-right" id="total_piutang"></span></th>
					<th>Rp. <span class="float-right" id="total_denda"></span></th>
					<th>Rp. <span class="float-right" id="total_sekarang"></span></th>
					<th>Rp. <span class="float-right" id="total_tagihan"></span></th>
					<td></td>
					<td></td>

				</tr>
			</tfoot>

			<!--
			<tfoot>
				<tr class="active bg-secondary">
					<td></td>
					<td></td>
					<td>Total</td>
					<th>Rp. <span class="float-right"><?= $this->apl->number_format($t->sekarang,1); ?></span></th>
					<th>Rp. <span class="float-right"><?= $this->apl->number_format($t->tagihan,1); ?></span></th>
					<td></td>
					<td></td>

				</tr>
			</tfoot>
				-->
		</table>

	</div>
	<div class="card-footer">
		<table class="table table-sm table-borderless">
			<tr>
				<th class="float-right">Previus Bill</th>
				<th>Rp. <span class="float-right"><?= $this->apl->number_format($t->piutang,1); ?></span></th>
								<th class="float-right">Fine</th>
								<th>Rp. <span class="float-right"><?= $this->apl->number_format($t->denda,1); ?></span></th>
				
			</tr>
			<tr>
				<th class="float-right">Current Bill</th>
				<th>Rp. <span class="float-right"><?= $this->apl->number_format($t->sekarang,1); ?></span></th>
								<th class="float-right">Bill</th>
								<th>Rp. <span class="float-right"><?= $this->apl->number_format($t->tagihan,1); ?></span></th>
				
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
			// Load data for the table's content from an Ajax source
			"ajax": {
				"url": "<?php echo site_url($controller . '/ajax_list' . "?" . $_SERVER['QUERY_STRING']); ?>",
				"type": "POST",

				"data": {
					'id_unit': '<?= $id_unit; ?>',
					'tanggal_awal': '<?= $tanggal_awal; ?>',
					'tanggal_ahir': '<?= $tanggal_ahir; ?>',
					'kirim': '<?= $kirim; ?>',
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
			'footerCallback': function(tfoot, data, start, end, display) {
				var response = this.api().ajax.json();
				if (response) {
					$('#total_tagihan').html(response['total_tagihan'].toLocaleString('en-US'));
					$('#total_denda').html(response['total_denda'].toLocaleString('en-US'));
					$('#total_sekarang').html(response['total_sekarang'].toLocaleString('en-US'));
					$('#total_piutang').html(response['total_piutang'].toLocaleString('en-US'));
				}
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

	$(document).on("click", '.edit_invoice_list', function(e) {
		save_method = 'edit_invoice_list';
		id = $(this).data('id');
		$('#modal_form').modal('show');
		$('#btnSave').html("Edit Invoice").show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url($controller . '/form_edit_invoice_list/') ?>/" + id;
		$('.modal-title').text("FORM EDIT INVOICE"); // Set title to Bootstrap modal title
		$.post(
			url,
			function(data) {
				$("#page-view").html(data).show();

			}
		);
	});


	$(document).on("click", '.print_data', function(e) {
		save_method = 'print';
		id = $(this).data('id');
		$('#modal_form').modal('show');
		$('#btnSave').html("Print").show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url($controller . '/print_priview_invoice/') ?>/" + id;
		$('.modal-title').text("Print Priview"); // Set title to Bootstrap modal title

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
		//alert(id);
		$('#modal_form').modal('show');
		$('#btnSave').html("Send").show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url('pesan/wa/ajax_get_invoice_billing') ?>/" + id;
		$('.modal-title').text("Kirim Whatsapp"); // Set title to Bootstrap modal title
		$.post(
			url,
			function(data) {
				$("#page-view").html(data).show();


			}
		);
	});

	$(document).on("click", '.email', function(e) {
		save_method = 'email';
		id = $(this).data('id');
		//alert(id);
		$('#modal_form').modal('show');
		$('#btnSave').html("Send").show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url('pesan/email/ajax_get_invoice_billing') ?>/" + id;
		$('.modal-title').text("Kirim Whatsapp"); // Set title to Bootstrap modal title
		$.post(
			url,
			function(data) {
				$("#page-view").html(data).show();


			}
		);
	});

	$(document).on("click", '.hapus_inv', function(e) {
		save_method = 'hapus_inv';
		id = $(this).data('id');
		$('#modal_form').modal('show');
		$('#btnSave').html("Hapus").show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url($controller . '/print_priview_invoice/') ?>/" + id;
		$('.modal-title').text("Delete Invoice"); // Set title to Bootstrap modal title
		$.post(
			url,
			function(data) {
				$("#page-view").html(data).show();
			}
		);
	});


	$(document).ready(function() {
		$("#btnSave").on('click', function(e) {
			$('#btnSave').hide();
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
			if (save_method == 'whatsapp') {
				url = "<?php echo site_url('pesan/wa/ajax_add_invoice') ?>";
			}
			if (save_method == 'email') {
				url = "<?php echo site_url('pesan/email/ajax_add_invoice') ?>";
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

							//notifikasi();
							if (save_method == 'print') {
								//alert("test");
								window.open("<?php echo site_url($controller . '/invoice_print/') ?>/" + id, '_blank').focus();
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