<?php
?>

<div class="card">
	<div class="card-header">Report Cash Off Name</div>
	<div class="card-body">
		<form action="" method="post">
			<div class="row">
				<div class="col-md-4">

					<div class="form-group">
						<label>Period</label>
						<div class="input-group">
							<input type="date" name="tanggal_awal" class="form-control" value="<?= $tanggal_awal; ?>">
							<input type="date" name="tanggal_ahir" class="form-control" value="<?= $tanggal_ahir; ?>">

							<button type="submit" class="btn btn-primary btn-sm">
								<i class="fa fa-calendar"> </i> Submit
							</button>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<label for="" class="control-label">Payment With</label>
					<?php
					echo $this->dropdown_model->getDropdownViaBayar(
						'id_via',
						$id_via,
						'class="form-control" required onchange="this.form.submit()"'
					);
					?>
				</div>
			</div>
		</form>
	</div>
	<div class="table-responsive">
		<table class="table table-borderless table-striped table-sm small">
			<thead class="bg-secondary">
				<tr>
					<th>NO</th>
					<th>Date</th>
					<th>Unit</th>
					<th>No Receipt</th>
					<th>Cash/Bank</th>
					<th>TOTAL</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$no = 0;
				$total = 0;
				foreach ($data as $d) {
					$no++;
					$total += $d->jumlah;
				?>
					<tr>
						<td><?= $no; ?></td>
						<td><?= $d->tanggal; ?></td>
						<td><?= $d->kode; ?></td>
						<td><?= $d->kwt; ?></td>
						<td><?= $d->nama; ?></td>
						<th>Rp <span class="float-right"><?= $this->apl->number_format($d->jumlah, 1); ?></span>
						</th>
					</tr>
				<?php
				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="4">Total</th>
					<th>Rp <span class="float-right"><?= $this->apl->number_format($total, 1); ?></span>
					</th>

				</tr>
			</tfoot>
		</table>

	</div>
</div>

<script>
	$(document).ready(function() {


		for (let n = 0; n < <?= $no; ?>; n++) {
			total += $('#jumlah_' + n).val();
			$('#qty_' + n).keyup(function() {
				var jumlah = $('#jumlah_' + n).val();
				var qty = $(this).val();
				var total = jumlah * qty;
				document.getElementById('total_' + n).value = total.toLocaleString('en-US');
				total_kas()
			})
		}

		function total_kas() {
			let tot = 0;
			for (let n = 0; n < <?= $no; ?>; n++) {
				let t = parseInt($('#total_' + n).val().replace(",", ""));
				tot += t;
			}
			//alert(total);
			document.getElementById('total_uang').value = tot.toLocaleString('en-US');
		}
	});
</script>