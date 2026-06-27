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

	<?php //$this->load->view('utility/menu'); ?>

	<div class="card-header">
		<form action="" method="post">
			<div class="row mb--5 mt--4">
				<div class="col-md-3">
					<div class="form-group">
						<label>Periode</label>
						<div class="input-group">


							<?php
							echo $this->dropdown_model->getDropdownTahun("tahun", $tahun,
									'class="form-control" onchange="this.form.submit()"')
							?>
						</div>
					</div>
				</div>
			</div>

		</form>
	</div>

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
			"paging": false,
			"filter": false,
			"bInfo": false,
			"fixedHeader": {
				header: true,
				footer: true,
				headerOffset: 60
			},
			// Load data for the table's content from an Ajax source
			"ajax": {
				"url": "<?php echo site_url($controller . '/ajax_list' . "?" . $_SERVER['QUERY_STRING']); ?>",
				"type": "POST",
				"data": {
					"id_tag": "<?= $id_tag;?>",
					"bulan": "<?= $bulan;?>",
					"tahun": "<?= $tahun;?>",
				}
			},
			"columnDefs": [
				{
					"searchable": false,
					"orderable": false,
					"targets": 0
				},

			],
			"order": [[0, 'asc']],
		});

	});

	function reload_table() {
		table.ajax.reload(null, false); //reload datatable ajax
	}

	/**
	 *  Menampilkan FORM TAMBAH DATA
	 */
	function add_data() {
		save_method = 'add';
		/*
		$('#btnSave').html("SIMPAN").show();
		$('#form')[0].reset(); // reset form on modals
		$('#modal_form').modal('show'); // show bootstrap modal
		$('.modal-title').text('Add Data'); // Set Title to Bootstrap modal title
		$('.modal-body').show(); //
		$('#page-input').show();
		 */

		$('#modal_form').modal('show');
		$('#btnSave').html('<i class="fa fa-plus"></i> Add Meter').show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url($controller . '/ajax_add_meter') ?>";
		$('.modal-title').text("Add Meter"); // Set title to Bootstrap modal title
		$.post(
			url,
			{
				"id_tag": "<?= $id_tag;?>",
				"bulan": "<?= $bulan;?>",
				"tahun": "<?= $tahun;?>",
			},
			function (data) {
				$("#page-view").html(data).show();
			}
		);

	}


	/**
	 *  Menampilkan FORM EDIT DATA
	 */
	function edit_data(id) {
		save_method = 'update';
		$('#modal_form').modal('show');
		$('#btnSave').html('<i class="fa fa-plus"></i> Update Meter').show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url($controller . '/ajax_update_meter/') ?>" + id;
		$('.modal-title').text("Add Meter"); // Set title to Bootstrap modal title
		$.post(
			url,
			{
				"id_tag": "<?= $id_tag;?>",
			},
			function (data) {
				$("#page-view").html(data).show();
			}
		);
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
		$('#page-hapus').show();
	}


	$(document).on("click", '.detail', function (e) {
		id = $(this).data('id');
		$('#modal_form').modal('show');
		$('#btnSave').html("").hide();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url($controller . '/ajax_detail/') ?>/" + id;
		$('.modal-title').text("Detail"); // Set title to Bootstrap modal title
		$.post(
			url,
			function (data) {
				$("#page-view").html(data).show();
			}
		);
	});

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
