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
	//$this->load->view('bayar/invoice/menu');
	?>

	<div class="table-responsive">
		<?php if ($id_unit != '') { ?>
			<table class="table table-datatables table-hover nowrap table-sm" width="100%" cellspacing="0">
				<thead class="table-secondary">
				<tr class="active bg-active">
					<th>Form</th>
					<th>Total</th>
					<th>Aksi</th>
				</tr>
				</thead>
				<!--
				<tfoot>
					<tr class="active bg-secondary">
						<td></td>
						<td></td>
						<td></td>
						<td>Total</td>
						<th>Rp. <span class="float-right" id="total_tagihan"></span></th>
						<th>Rp. <span class="float-right" id="total_denda"></span></th>
						<th>Rp. <span class="float-right" id="total_bayar"></span></th>
						<th>Rp. <span class="float-right" id="total_piutang"></span></th>
						<td></td>
						<td></td>

					</tr>
				</tfoot>
				-->
			</table>
		<?php } ?>
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
			"paging": false,
			"filter": false,
			"fixedHeader": {
				header: true,
				footer: true,
				headerOffset: 60
			},
			"offsetTop": 60,
			//"scrollY": 500,
			//"scrollCollapse": true,
			"order": [], //Initial no order.
			"ajax": {
				"url": "<?php echo site_url($controller . '/ajax_list' . "?" . $_SERVER['QUERY_STRING']); ?>",
				"type": "POST",
				"data": {
					'id_unit': '<?= $id_unit; ?>'
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
			"order": [
				[0, 'asc']
			],
			'footerCallback': function (tfoot, data, start, end, display) {
				var response = this.api().ajax.json();
				if (response) {
					$('#total_tagihan').html(response['total_tagihan'].toLocaleString('en-US'));
					$('#total_denda').html(response['total_denda'].toLocaleString('en-US'));
					$('#total_bayar').html(response['total_bayar'].toLocaleString('en-US'));
					$('#total_piutang').html(response['total_piutang'].toLocaleString('en-US'));
				}
			},
			"rowCallback": function (row, data, index) {
				/*
				if (data[2] < data[4]) {
					//Highlight the cell value
					$(row).find('td:eq(2)').css('color', 'red');
					//Highlight the row
					$(row).addClass("danger");
				}
				*/


				if (data[8] == '<label class="btn btn-sm btn-info btn-block">Invoice</label>') {
					$(row).addClass("bg-secondary");
				}
				if ((data[8] == '<label class="btn btn-sm btn-success btn-block">Payment</label>') || (data[8] == '<label class="btn btn-sm btn-warning btn-block">Credit Note</label>')) {
					$(row).addClass("bg-light");
					//data[8]='<label class="btn btn-sm btn-success btn-block">Payment</label>';
				}
			},
		});
		/*
		table.on('order.dt search.dt', function() {
			table.column(0, {
				search: 'applied',
				order: 'applied'
			}).nodes().each(function(cell, i) {
				cell.innerHTML = i + 1;
			});
		}).draw();
		 */
	});

	function reload_table() {
		table.ajax.reload(null, false); //reload datatable ajax
	}

	var save_method;
	var id;
	$(document).on("click", '.bayar', function (e) {
		save_method = 'bayar';
		id = $(this).data('id');
		$('#modal_form').modal('show');
		$('#btnSave').html("Bayar").show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url($controller . '/form_bayar/') ?>/" + id;
		$('.modal-title').text("FORM PAYMENT"); // Set title to Bootstrap modal title
		$.post(
			url,
			function (data) {
				$("#page-view").html(data).show();
			}
		);
	});

	$(document).on("click", '.histori', function (e) {
		id = $(this).data('id');
		$('#modal_form').modal('show');
		$('#btnSave').html("Bayar").hide();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url($controller . '/histori_cut_off/') ?>/" + id;
		$('.modal-title').text("HISTORI"); // Set title to Bootstrap modal title
		$.post(
			url,
			function (data) {
				$("#page-view").html(data).show();
			}
		);
	});

	$(document).on("click", '.bayar_perinvoice', function (e) {
		save_method = 'bayar_perinvoice';
		id = $(this).data('id');
		$('#modal_form').modal('show');
		$('#btnSave').html("Bayar").show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url($controller . '/form_bayar_perinvoice/') ?>/" + id;
		$('.modal-title').text("FORM PAYMENT"); // Set title to Bootstrap modal title
		$.post(
			url,
			function (data) {
				$("#page-view").html(data).show();
			}
		);
	});
	$(document).on("click", '.cut_off', function (e) {
		save_method = 'cut_off';
		id = $(this).data('id');
		$('#modal_form').modal('show');
		$('#btnSave').html("Update Cut Off").show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url($controller . '/form_cut_off/') ?>/" + id;
		$('.modal-title').text("FORM PAYMENT"); // Set title to Bootstrap modal title
		$.post(
			url,
			function (data) {
				$("#page-view").html(data).show();
			}
		);
	});


	/**
	 *
	 * INVOICE
	 */
	$(document).on("click", '.print_data', function (e) {
		save_method = 'print';
		id = $(this).data('id');
		$('#modal_form').modal('show');
		$('#btnSave').html("Print").show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url('billing/invoice/print_priview_invoice'); ?>/" + id;
		$('.modal-title').text("Print Priview"); // Set title to Bootstrap modal title

		$.post(
			url,
			function (data) {
				$("#page-view").html(data).show();
			}
		);
	});
	$(document).on("click", '.edit_invoice_list', function (e) {
		save_method = 'edit_invoice_list';
		id = $(this).data('id');
		$('#modal_form').modal('show');
		$('#btnSave').html("Edit Invoice").show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url('billing/invoice/form_edit_invoice_list/') ?>/" + id;
		$('.modal-title').text("FORM EDIT INVOICE"); // Set title to Bootstrap modal title
		$.post(
			url,
			function (data) {
				$("#page-view").html(data).show();

			}
		);
	});

	$(document).on("click", '.whatsapp', function (e) {
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
		var url = "<?php echo site_url('billing/invoice/print_priview_invoice/') ?>/" + id;
		$('.modal-title').text("Delete Invoice"); // Set title to Bootstrap modal title
		$.post(
			url,
			function (data) {
				$("#page-view").html(data).show();
			}
		);
	});


	/**@abstract
	 *
	 * Aksi Bayar
	 */

	$(document).on("click", '.edit_bayar_detail', function (e) {

		id = $(this).data("id");
		save_method = 'update_bayar_detail';
		$('#modal_form').modal('show');
		$('#btnSave').html("Update Bayar").show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url($controller . '/update_bayar_detail/') ?>/" + id;
		$('.modal-title').text("Edit Pembayaran"); // Set title to Bootstrap modal title
		$.post(
			url,
			function (data) {
				$("#page-view").html(data).show();
			}
		);

	});


	$(document).on("click", '.whatsapp_kwt', function (e) {
		save_method = 'whatsapp_kwt';
		id = $(this).data('id');
		$('#modal_form').modal('show');
		$('#btnSave').html("Send").show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url('pesan/wa/ajax_get_bayar_billing/') ?>/" + id;
		$('.modal-title').text("Kirim Whatsapp"); // Set title to Bootstrap modal title
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
		$('#page-hapus').html("Delete this payment data ?").show(); //
	}


	/**
	 * Credit Note
	 *
	 */
	$(document).on("click", '.cn', function (e) {
		save_method = 'cn';
		id = $(this).data('id');
		$('#modal_form').modal('show');
		$('#btnSave').html("Bayar").show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url('cn/invoice/form_cn/') ?>/" + id;
		$('.modal-title').text("FORM CREDIT NOTE"); // Set title to Bootstrap modal title
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
			/**
			 * Pembayaran
			 */
			if (save_method == 'bayar') {
				url = "<?php echo site_url($controller . '/ajax_add_bayar') ?>";
			}
			if (save_method == 'bayar_perinvoice') {
				url = "<?php echo site_url($controller . '/ajax_add_bayar_perinvoice') ?>";
			}


			if (save_method == 'hapus') {
				url = "<?php echo site_url($controller . '/ajax_delete') ?>/" + hapus_id;
			}
			if (save_method == 'update_bayar_detail') {
				url = "<?php echo site_url($controller . '/ajax_update_bayar_detail') ?>";
			}
			if (save_method == 'whatsapp_kwt') {
				url = "<?php echo site_url('pesan/wa/ajax_kirm_wa_kwt') ?>";
			}
			/**
			 * Cut OFf
			 */
			if (save_method == 'cut_off') {
				url = "<?php echo site_url($controller . '/ajax_update_cut_off') ?>";
			}

			/**
			 * CN
			 */
			if (save_method == 'cn') {
				url = "<?php echo site_url('cn/invoice/ajax_add') ?>";
			}


			/**
			 * Aksi Invoice
			 *
			 */
			if (save_method == 'edit_invoice_list') {
				url = "<?php echo site_url('billing/invoice/ajax_edit_invoice_list') ?>";
			}
			if (save_method == 'hapus_inv') {
				url = "<?php echo site_url('billing/invoice/ajax_hapus_invoice/') ?>" + id;
			}
			if (save_method == 'whatsapp') {
				url = "<?php echo site_url('pesan/wa/ajax_add_invoice') ?>";
			}
			if (save_method == 'print') {
				url = "<?php echo site_url('billing/invoice/print_invoice/'); ?>";
				window.open("<?php echo URLADMIN . 'billing/invoice/invoice_print'; ?>/" + id, '_blank').focus();
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
							$('#modal_form').modal('hide'); //Hide Modal Bootstrap
							reload_table();
							window.onbeforeunload = function () {
								window.scrollTo(0, 0);
							}
							/**
							if (save_method == 'bayar') {
								window.open("<?php echo site_url($controller . '/cetak') ?>?id=" + id, '_blank').focus();
							}
							 */
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
