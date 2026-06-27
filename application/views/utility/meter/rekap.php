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
<style>
	td {
		padding-left: 10px !important;
		padding-right: 10px !important;
	}

	th {
		padding-left: 10px !important;
		padding-right: 10px !important;
	}

</style>
<div class="card">
	<div class="card-header">
		<form action="" method="post">
			<div class="row mb--5 mt--4">
				<div class="col-md-4">
					<div class="form-group">
						<label>Year</label>

						<?php
						echo $this->dropdown_model->getDropdownTahun("tahun", $tahun,
								'class="form-control" onchange="this.form.submit()"')
						?>
					</div>
				</div>
			</div>

		</form>
	</div>
	<div class="table-responsive">
		<table class="table-borderless table-sm table table-datatables"

			   cellspacing="0">
			<thead class="bg-secondary">
			<tr class="bg-secondary">
				<th rowspan="2">No</th>
				<th rowspan="2">Unit</th>
				<th rowspan="2">Account</th>
				<th rowspan="2"><?= ($tahun - 1); ?></th>
				<?php
				for ($i = 1; $i <= 12; $i++) {
					echo '<th colspan="2" class="text-center">' . $this->apl->bulan($i) . '</th>';
				}
				?>
			</tr>

			<tr class="active">

				<?php
				for ($i = 1; $i <= 24; $i++) {
					if ($i % 2 == 1) {
						echo '<th class="text-center">M3</th>';

					} else {
						echo '<th class="text-center">Con.</th>';

					}
				}
				?>
			</tr>
			</thead>
			<tfoot>
			<tr>

				<th colspan="3">Total Konsumsi</th>
				<th></th>
				<?php
				for ($i = 1; $i <= 24; $i++) {
					echo '<th></th>';
				}

				?>
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
			"scrollX": true,
			// Load data for the table's content from an Ajax source
			"ajax": {
				"url": "<?php echo site_url($controller . '/ajax_list_rekap' . "?" . $_SERVER['QUERY_STRING']); ?>",
				"type": "POST",
				"data": {
					"id_tag": "<?= $id_tag;?>",
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

			"fixedColumns": {
				leftColumns: 3,
			},
			'footerCallback': function (tfoot, data, start, end, display) {
				var response = this.api().ajax.json();
				if (response) {
					var api = this.api(), data;
					var intVal = function (i) {
						return typeof i === 'string' ?
								i.replace(/[\$,]/g, '') * 1 :
								typeof i === 'number' ?
										i : 0;
					};
					total = api
							.column(4)
							.data()
							.reduce(function (a, b) {
								return intVal(a) + intVal(b);
							}, 0);

					pageTotal = api
							.column(4)
							.data()
							.reduce(function (a, b) {
								return intVal(a) + intVal(b);
							}, 0);

					$(api.column(4).footer()).html(
							'' + pageTotal + ' M3'
					);
					var $th = $(tfoot).find('th');
					$th.eq(2).html(response['total_kons'][1]);
					$th.eq(3).html(response['total_kons'][2]);
					$th.eq(4).html(response['total_kons'][3]);
					$th.eq(5).html(response['total_kons'][4]);
					$th.eq(6).html(response['total_kons'][5]);
					$th.eq(7).html(response['total_kons'][6]);
					$th.eq(8).html(response['total_kons'][7]);
					$th.eq(9).html(response['total_kons'][8]);
					$th.eq(10).html(response['total_kons'][9]);
					$th.eq(11).html(response['total_kons'][10]);
					$th.eq(12).html(response['total_kons'][11]);
					$th.eq(13).html(response['total_kons'][12]);
					$th.eq(14).html(response['total_kons'][13]);
					$th.eq(15).html(response['total_kons'][14]);
					$th.eq(16).html(response['total_kons'][15]);
					$th.eq(17).html(response['total_kons'][16]);
					$th.eq(18).html(response['total_kons'][17]);
					$th.eq(19).html(response['total_kons'][18]);
					$th.eq(20).html(response['total_kons'][19]);
					$th.eq(21).html(response['total_kons'][20]);
					$th.eq(22).html(response['total_kons'][21]);
					$th.eq(23).html(response['total_kons'][22]);
					$th.eq(24).html(response['total_kons'][23]);
					$th.eq(25).html(response['total_kons'][24]);

				}
			},

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


</script>
