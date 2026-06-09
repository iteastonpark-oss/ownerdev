<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 8/11/2016
 * Time: 10:34 AM
 */
?>

<?php
$controller = $this->uri->segment(1) . '/ajax';
?>

<div class="card">
	<?php $this->load->view('tiket/menu'); ?>


	<?php
	if ($tabs == 'proses') {
		?>
		<div class="card-header py-1">
			<div class="row justify-content-end">
				<div class="col-md-4">
					<form action="" method="post">
						<?php
						$options = array();
						$options[0] = '-- Choose --';
						$options[1] = 'Un Schedule';
						$options[2] = 'Progress';
						$options[3] = 'Due Date';
						$options[4] = 'Hold';
						echo form_dropdown('jatuh_tempo', $options, $jatuh_tempo
								, 'class="form-control" onchange="this.form.submit()"');
						?>
					</form>
				</div>
			</div>
		</div>
		<?php
	}
	?>

	<div class="table-responsive pb-4">
		<table class="table table-datatables table-hover dt-responsive nowrap table-sm" width="100%"
			   cellspacing="0">
			<thead class="thead-light">

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
			"paging": false,
			"filter": false,
			"fixedHeader": {
				header: true,
				footer: true,
				headerOffset: 60
			},
			// Load data for the table's content from an Ajax source
			"ajax": {
				"url": "<?php echo site_url($controller
						. '/ajax_list' . "?" . $_SERVER['QUERY_STRING']); ?>",
				"type": "POST",
				"data": {
					"tipe": "<?= $tipe;?>",
					"post": "<?= $post;?>",
					"jatuh_tempo": "<?= $jatuh_tempo ?>",
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


		$('[name=jatuh_tempo]').change(function () {
			reload_table();
		})
	});


	function reload_table() {
		table.ajax.reload(null, false); //reload datatable ajax
	}


	$(document).on("click", '.jadwal_pengerjaan', function (e) {

		id = $(this).data("id");
		save_method = 'jadwal_pengerjaan';
		$('#modal_form').modal('show');
		$('#btnSave').html("Add Schedule").show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url($controller . '/add_jadwal/') ?>" + id;
		$('.modal-title').text("Add Schedule"); // Set title to Bootstrap modal title
		$.post(
			url,
			function (data) {
				$("#page-view").html(data).show();
			}
		);
	});

	$(document).on("click", '.perpanjang_jadwal', function (e) {
		id = $(this).data("id");
		save_method = 'perpanjang_jadwal';
		$('#modal_form').modal('show');
		$('#btnSave').html("Extend Schedule").show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url($controller . '/add_perpanjangan/') ?>" + id;
		$('.modal-title').text("Add Extend the Schedule"); // Set title to Bootstrap modal title
		$.post(
			url,
			function (data) {
				$("#page-view").html(data).show();
			}
		);
	});

	$(document).on("click", '.tambah_histori', function (e) {

		id = $(this).data("id");
		save_method = 'tambah_histori';
		$('#modal_form').modal('show');
		$('#btnSave').html('<i class="fa fa-plus"></i> Add History').show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url($controller . '/add_histori/') ?>" + id;
		$('.modal-title').text("Add History"); // Set title to Bootstrap modal title
		$.post(
			url,
			function (data) {
				$("#page-view").html(data).show();
			}
		);
	});

	$(document).on("click", '.tambah_attachment', function (e) {

		id = $(this).data("id");
		save_method = 'tambah_attachment';
		$('#modal_form').modal('show');
		$('#btnSave').html('<i class="fa fa-plus"></i> Add Attachment').show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url($controller . '/add_attachment/') ?>" + id;
		$('.modal-title').text("Add Attachment"); // Set title to Bootstrap modal title
		$.post(
			url,
			function (data) {
				$("#page-view").html(data).show();
			}
		);
	});


	$(document).on("click", '.detail', function (e) {

		id = $(this).data("id");
		save_method = 'detail';
		$('#modal_form').modal('show');
		$('#btnSave').hide();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo URLADMIN.$controller . '/ajax_detail/'; ?>" + id + "?proses=detail";
		$('.modal-title').text("Detail"); // Set title to Bootstrap modal title
		$.post(
			url,
			function (data) {
				$("#page-view").html(data).show();
			}
		);
	});


	$(document).on("click", '.selesai_pengerjaan', function (e) {
		id = $(this).data("id");
		save_method = 'selesai_pengerjaan';
		$('#modal_form').modal('show');
		$('#btnSave').html("Selesai").show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url($controller . '/ajax_detail/') ?>" + id + "?proses=done";
		;
		$('.modal-title').text("Done"); // Set title to Bootstrap modal title
		$.post(
			url,
			function (data) {
				$("#page-view").html(data).show();
			}
		);
	});

	$(document).on("click", '.selesai_pengerjaan_card', function (e) {
		id = $(this).data("id");
		save_method = 'selesai_pengerjaan_card';
		$('#modal_form').modal('show');
		$('#btnSave').html("Done").show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url($controller . '/ajax_detail/') ?>" + id + "?proses=card";
		;
		$('.modal-title').text("Done"); // Set title to Bootstrap modal title
		$.post(
			url,
			function (data) {
				$("#page-view").html(data).show();
			}
		);
	});

	$(document).on("click", '.note', function (e) {
		id = $(this).data("id");
		save_method = 'note';
		$('#modal_form').modal('show');
		$('#btnSave').html("Add Note").show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url($controller . '/ajax_detail/') ?>" + id + "?proses=note";
		;
		$('.modal-title').text("Form Add Note"); // Set title to Bootstrap modal title
		$.post(
			url,
			function (data) {
				$("#page-view").html(data).show();
			}
		);
	});


	$(document).on("click", '.tambah_card', function (e) {
		id = $(this).data("id");
		save_method = 'tambah_card';
		$('#modal_form').modal('show');
		$('#btnSave').html("Add Card").show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url($controller . '/ajax_detail/') ?>" + id + "?proses=card";
		;
		$('.modal-title').text("Done"); // Set title to Bootstrap modal title
		$.post(
			url,
			function (data) {
				$("#page-view").html(data).show();
			}
		);
	});

	$(document).on("click", '.selesai_pengerjaan_fo', function (e) {
		id = $(this).data("id");
		save_method = 'selesai_pengerjaan_fo';
		$('#modal_form').modal('show');
		$('#btnSave').html("Done").show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url($controller . '/ajax_detail/') ?>" + id + "?proses=fo";
		;
		$('.modal-title').text("Done"); // Set title to Bootstrap modal title
		$.post(
			url,
			function (data) {
				$("#page-view").html(data).show();
			}
		);
	});

	$(document).on("click", '.tutup_pengerjaan', function (e) {
		id = $(this).data("id");
		save_method = 'tutup_pengerjaan';
		$('#modal_form').modal('show');
		$('#btnSave').html("Close Request").show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url($controller . '/ajax_detail/') ?>" + id + "?proses=detail";
		;
		$('.modal-title').text("Close"); // Set title to Bootstrap modal title
		$.post(
			url,
			function (data) {
				$("#page-view").html(data).show();
			}
		);
	});

	$(document).on("click", '.approval', function (e) {
		id = $(this).data("id");
		save_method = 'approval';
		$('#modal_form').modal('show');
		$('#btnSave').html("Approval").show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url($controller . '/ajax_detail/') ?>" + id + "?proses=approval";
		$('.modal-title').text("Form Approve"); // Set title to Bootstrap modal title
		$.post(
			url,
			function (data) {
				$("#page-view").html(data).show();
			}
		);
	});

	$(document).on("click", '.reject', function (e) {
		id = $(this).data("id");
		save_method = 'reject';
		$('#modal_form').modal('show');
		$('#btnSave').html("Reject").show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url($controller . '/ajax_detail/') ?>" + id + "?proses=pending";
		$('.modal-title').text("Form Reject"); // Set title to Bootstrap modal title
		$.post(
			url,
			function (data) {
				$("#page-view").html(data).show();
			}
		);
	});

	$(document).on("click", '.signature', function (e) {
		id = $(this).data("id");
		save_method = 'signature';
		$('#modal_form').modal('show');
		$('#btnSave').html("Signature").show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url($controller . '/ajax_signature/') ?>" + id;
		$('.modal-title').text("Form Signature"); // Set title to Bootstrap modal title
		$.post(
			url,
			function (data) {
				$("#page-view").html(data).show();
			}
		);
	});

	$(document).on("click", '.approv_data', function (e) {

		id = $(this).data("id");
		save_method = 'approv_data';
		$('#modal_form').modal('show');
		$('#btnSave').html("Approval").show();
		$('#form')[0].reset(); // reset form on modals
		$('.modal-title').text("Approval Dept Head"); // Set title to Bootstrap modal title
		$("#page-view").html("Approval This Data By Dept Head").show();
	});

	$(document).on("click", '.hapus', function (e) {
		hapus_id = $(this).data("id");
		save_method = 'hapus';
		$('#modal_form').modal('show');
		$('#btnSave').html("hapus").show();
		$('#form')[0].reset(); // reset form on modals
		$('.modal-title').text("Delete Request"); // Set title to Bootstrap modal title
		$("#page-view").html("Delete this Data ?").show();

	});


	$(document).on("click", '.hold_pengerjaan', function (e) {
		id = $(this).data("id");
		save_method = 'hold_pengerjaan';
		$('#modal_form').modal('show');
		$('#btnSave').html("Hold").show();
		$('#form')[0].reset(); // reset form on modals
		$('.modal-title').text("Hold Request"); // Set title to Bootstrap modal title
		$("#page-view").html('Hold this data ? ' +
			'<input type="hidden" name="id_tiket" value="' + id + '">').show();
	});

	$(document).on("click", '.unhold_pengerjaan', function (e) {
		id = $(this).data("id");
		save_method = 'unhold_pengerjaan';
		$('#modal_form').modal('show');
		$('#btnSave').html("Un Hold").show();
		$('#form')[0].reset(); // reset form on modals
		$('.modal-title').text("Hold Request"); // Set title to Bootstrap modal title
		$("#page-view").html('Un Hold this data ? ' +
			'<input type="hidden" name="id_tiket" value="' + id + '">').show();
	});


	$(document).ready(function () {
		$("#btnSave").on('click', function (e) {
			e.stopPropagation(); // Jika gagal masih muncul
			save();
		});

		function save() {
			if (save_method == 'signature') {
				url = "<?php echo site_url($controller . '/ajax_add_signature') ?>";
			}
			if (save_method == 'jadwal_pengerjaan') {
				url = "<?php echo site_url($controller . '/ajax_add_jadwal') ?>";
			}
			if (save_method == 'perpanjang_jadwal') {
				url = "<?php echo site_url($controller . '/ajax_add_perpanjangan') ?>";
			}
			if (save_method == 'tambah_histori') {
				url = "<?php echo site_url($controller . '/ajax_histori_jadwal') ?>";
			}
			if (save_method == 'tambah_attachment') {
				url = "<?php echo site_url($controller . '/ajax_attachment') ?>";
			}
			if (save_method == 'selesai_pengerjaan') {
				url = "<?php echo site_url($controller . '/ajax_selesai') ?>";
			}
			if (save_method == 'selesai_pengerjaan_card') {
				url = "<?php echo site_url($controller . '/ajax_selesai_card') ?>";
			}
			if (save_method == 'tambah_card') {
				url = "<?php echo site_url($controller . '/ajax_tambah_card') ?>";
			}
			if (save_method == 'selesai_pengerjaan_fo') {
				url = "<?php echo site_url($controller . '/ajax_selesai_fo') ?>";
			}
			if (save_method == 'tutup_pengerjaan') {
				url = "<?php echo site_url($controller . '/ajax_tutup') ?>";
			}
			if (save_method == 'approval') {
				url = "<?php echo site_url($controller . '/ajax_approval') ?>";
			}
			if (save_method == 'reject') {
				url = "<?php echo site_url($controller . '/ajax_reject') ?>";
			}
			if (save_method == 'note') {
				url = "<?php echo site_url($controller . '/ajax_add_note') ?>";
			}

			if (save_method == 'hold_pengerjaan') {
				url = "<?php echo site_url($controller . '/ajax_hold') ?>";
			}
			if (save_method == 'unhold_pengerjaan') {
				url = "<?php echo site_url($controller . '/ajax_unhold') ?>";
			}

			if (save_method == 'hapus') {
				url = "<?php echo site_url($controller . '/ajax_delete') ?>/" + hapus_id;

			}
			if (save_method == 'approv_data') {
				url = "<?php echo site_url($controller . '/ajax_approv/') ?>" + id;
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

