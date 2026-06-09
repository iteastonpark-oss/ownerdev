<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 9/22/2016
 * Time: 9:30 AM
 */
?>
<title><?= "FO-" . time(); ?></title>

<?php
$this->load->view('layout/header');
?>
<div class="container">

	<div class="card">
		<div class="card-header">
			<?php
			$this->load->view('layout/kepala_cetak');
			?>
		</div>
		<div class="card-body">

			<h6 class="heading-small text-muted mb-4">Information</h6>
			<table class="table table-sm table-borderless small table-striped">
				<tr>
					<th>No Form</th>
					<th><?= $t->no_form; ?></th>
				</tr>
				<tr>
					<th>Date Of Filing</th>
					<th><?= $this->apl->tgl_format($t->tanggal, 5); ?></th>
				</tr>
				<tr>
					<th>Work Schedule</th>
					<th><?= $this->apl->tgl_format($t->tanggal_awal, 5) . " to "
						. $this->apl->tgl_format($t->tanggal_ahir, 5); ?></th>
				</tr>
				<tr>
					<th>Date of Completion</th>
					<th><?= $t->tanggal_selesai; ?></th>
				</tr>
			</table>
			<hr class="my-4"/>
			<?php
			if (isset($p)) {
				?>

				<h6 class="heading-small text-muted mb-4">Repair Data</h6>
				<table class="table table-sm table-borderless small">
					<thead>

					<tr class="bg-secondary">
						<th>Repair Name</th>
						<th>Note</th>
					</tr>
					</thead>

					<tbody>
					<?php
					foreach ($p as $det) {
						?>
						<tr>
							<td><?= $this->apl->get_nilai_pilih("db_pekerjaan", "nama", "id_pekerjaan=" . $det->id_pekerjaan); ?></td>
							<td><?= $det->nama; ?></td>
						</tr>
						<?php
					}
					?>
					</tbody>
				</table>
			<?php } ?>
			<h6 class="heading-small text-muted mb-4">Cost</h6>
			<table class="table table-sm table-borderless small">
				<thead>

				<tr class="bg-secondary">
					<th>Name</th>
					<th>qty</th>
					<th>Supervision</th>
					<th>Deposit</th>
				</tr>
				</thead>

				<tbody>
				<?php
				$jumlah = 0;
				foreach ($d as $det) {
					$jumlah = $det->jumlah + $det->deposit + $det->material;
					?>
					<tr>
						<td><?= $det->nama; ?></td>
						<td><?= $det->qty; ?></td>
						<td><?= $this->apl->number_format($det->jumlah, 1); ?></td>
						<td><?= $this->apl->number_format($det->deposit, 1); ?></td>
					</tr>
					<?php
				}
				?>
				</tbody>
				<tfoot>
				<tr>
					<td></td>
					<td></td>
					<td><span class="font-weight-bold">Total</span></td>
					<td><span class="font-weight-bold"><?= $this->apl->number_format($jumlah, 1); ?></span></td>
				</tr>
				</tfoot>
			</table>
			<br>
			<br>
			<table class="table table-bordered">
				<tr>
					<td>
						<span class="text-muted">Created By</span>
						<br>
						<br>
						<span class="text-sm text-muted"><?= $this->apl->get_nilai_pilih("admin", "nama_admin", "id_admin=" . $t->id_admin); ?></span>

					</td>
					<td>
						<span class="text-muted">Received By</span>
						<br>
						<br>

						<span class="text-sm text-muted"><?php
							if ($t->post > 2) {
								//print_r($b);
								echo ($b) ? $this->apl->get_nilai_pilih("admin", "nama_admin", "id_admin=" . $b->id_admin) : '';
							}
							?></span>

					</td>
				</tr>

			</table>
			<br>
			<br>
			<h6 class="heading-small text-muted mb-4">Job History</h6>
			<table class="table table-sm table-borderless small">
				<thead>

				<tr class="bg-secondary">
					<th>History</th>
					<th>Date</th>
				</tr>
				</thead>

				<tbody>
				<?php
				foreach ($h as $his) {
					?>
					<tr>
						<td><?= $his->ket; ?></td>
						<td><?= $this->apl->tgl_format($his->tanggal, 1); ?></td>
					</tr>
					<?php
				}
				?>
				</tbody>
			</table>
			<hr class="my-4"/>


		</div>
	</div>
</div>
