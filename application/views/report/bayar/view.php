<?php
$controller = $this->uri->segment(1) ;

?>

<div class="card">
	<div class="card-body">
		<form action="" method="post">
			<div class="row mt--3">
				<div class="col-6">

					<div class="form-group">
						<label>Period</label>
							<input type="month" name="bulan"
								   class="form-control " value="<?= $bulan; ?>" onchange="this.form.submit()">


					</div>
				</div>

				<div class="col-6">
					<label for="" class="control-label">Type</label>
					<?php
					echo $this->dropdown_model->getDropdownTipePembayaran(
						'status',
						$status,
						'class="form-control"  onchange="this.form.submit()"'
					);
					?>
				</div>
			</div>
		</form>
	</div>
	<div class="table-responsive mt--5">

		<table class="table table-datatables table-hover dt-responsive nowrap table-sm small text-sm" width="100%"
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
			<tfoot>
			<tr>
				<th colspan="4">Total</th>
				<th>Rp. <span class="float-right"><?= $this->apl->number_format($jumlah, 1); ?></span></th>
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
			"paging" : false,
			"filter" : false,
			"bInfo" : false,
			// Load data for the table's content from an Ajax source
			"ajax": {
				"url": "<?php echo site_url($controller
						. '/ajax_list' . "?" . $_SERVER['QUERY_STRING']); ?>",
				"type": "POST",
				"data": {
					"unit": "<?= $unit;?>",
					"tanggal_awal": "<?= $tanggal_awal;?>",
					"tanggal_ahir": "<?= $tanggal_ahir;?>",
					"status": "<?= $status;?>",
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

</script>

