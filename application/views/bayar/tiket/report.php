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
	$this->load->view('bayar/tiket/menu');
	?>
	<div class="card-body py-0">

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
								   class="form-control" value="<?= $tanggal_awal; ?>">
							<input type="date" name="tanggal_ahir"
								   class="form-control" value="<?= $tanggal_ahir; ?>">
							<div class="input-group-append">
								<button type="submit" class="btn btn-primary">
									<i class="fa fa-calendar"> </i> Pilih
								</button>
							</div>
						</div>
					</div>
				</div>

			</div>
		</form>
	</div>

	<div class="table-responsive">
		<table class="table table-datatables table-hover dt-responsive nowrap table-sm" width="100%"
			   cellspacing="0">
			<thead>
			<tr class="bg-secondary">
				<?php
				foreach ($field as $tr) {
					echo '<th>' . $tr . '</th>';
				}
				?>
			</tr>
			</thead>
			<tfoot>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<th>Total</th>
				<th><span class="float-right">Rp. <?= $this->apl->number_format($jumlah, 1); ?></span></th>
				<td></td>
				<td></td>
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
			"ajax": {
				"url": "<?php echo site_url($controller . '/ajax_list_report' . "?" . $_SERVER['QUERY_STRING']); ?>",
				"type": "POST",
				"data": {
					'tipe': '<?= $tipe;?>',
					'id_unit': '<?= $id_unit;?>',
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


	var save_method;
	var id;


	$(document).on("click", '.edit_bayar', function (e) {

		id = $(this).data("id");
		save_method = 'update_bayar';
		$('#modal_form').modal('show');
		$('#btnSave').html("Update Bayar").show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url($controller . '/update_bayar/') ?>/" + id;
		$('.modal-title').text("Tambah Pembayaran"); // Set title to Bootstrap modal title
		$.post(
				url,
				function (data) {
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



	$(document).on("click", '.whatsapp', function (e) {
		save_method = 'whatsapp';
		id = $(this).data('id');
		$('#modal_form').modal('show');
		$('#btnSave').html("Send").show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url('pesan/wa/ajax_get_bayar_tiket/') ?>/" + id;
		$('.modal-title').text("Kirim Whatsapp"); // Set title to Bootstrap modal title
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
			if (save_method == 'hapus') {
				url = "<?php echo site_url($controller . '/ajax_delete') ?>/" + hapus_id;
			}
			if (save_method == 'update_bayar') {
				url = "<?php echo site_url($controller . '/ajax_update_bayar') ?>";
			}
			if (save_method == 'whatsapp') {
				url = "<?php echo site_url('pesan/wa/ajax_kirm_wa_tiket') ?>";
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
