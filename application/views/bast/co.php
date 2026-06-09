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
		<div class="row">
			<div class="col-md-6">

				<form action="" method="post">
					<div class="form-group">
						<label for="">Utilites Cut Off</label>
						<?php
						$options = array(
								'' => 'ALL UNIT',
								'0' => 'OPEN',
								'1' => 'UTILITES CUT OFF',
						);
						echo form_dropdown("co", $options, $co
								, 'class="form-control" onchange="this.form.submit()"');
						?>
					</div>
				</form>
			</div>
		</div>
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
					"co": "<?= $co;?>",
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

	$(document).on("click", '.cut_off', function (e) {

		id = $(this).data("id");
		save_method = 'cut_off';
		$('#modal_form').modal('show');
		$('#btnSave').html('Cut Off').show();
		$('#form')[0].reset(); // reset form on modals
		$('.modal-title').text("Form Cut Off"); // Set title to Bootstrap modal title

		var url = "<?php echo site_url($controller . '/form_cut_off/') ?>" + id;
		$('.modal-title').text("Form Cut Off"); // Set title to Bootstrap modal title
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
			if (save_method == 'cut_off') {
				url = "<?php echo site_url($controller . '/ajax_cut_off') ?>";
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


