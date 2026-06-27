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
	<div class="table-responsive">
		<table class="table table-datatables table-hover  nowrap table-sm" width="100%"
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

			"ajax": {
				"url": "<?php echo site_url($controller . '/ajax_list_konfirmasi' . "?" . $_SERVER['QUERY_STRING']); ?>",
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

	var save_method;
	var id;
	$(document).on("click", '.konfirmasi-bayar', function (e) {

		id = $(this).data("id");
		save_method = 'konfirmasi-bayar';
		$('#modal_form').modal('show');
		$('#btnSave').html("Confirm").show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url($controller . '/form_konfirmasi_bayar/') ?>/" + id;
		$('.modal-title').text("Pay Confirm"); // Set title to Bootstrap modal title
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

	$(document).ready(function () {
		$("#btnSave").on('click', function (e) {
			e.stopPropagation(); // Jika gagal masih muncul
			save();
		});

		function save() {
			if (save_method == 'hapus') {
				url = "<?php echo site_url($controller . '/ajax_delete_konfirmasi') ?>/" + hapus_id;
			}
			if (save_method == 'konfirmasi-bayar') {
				url = "<?php echo site_url($controller . '/ajax_add_konfirmasi') ?>";
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
