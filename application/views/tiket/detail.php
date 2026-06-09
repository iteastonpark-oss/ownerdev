<style>
	table {
		font-size: 10pt
	}

	td {
		white-space: normal !important;
		word-wrap: break-word;
	}

	th {
		white-space: normal !important;
		word-wrap: break-word;
	}
</style>

<h6 class="heading-small text-muted mb-4">Information</h6>
<table class="table table-sm table-borderless small table-striped">
	<?php
	//$id_bast = $this->apl->get_nilai_pilih('tiket', 'id_bast', 'id_tiket=' . $t->id_tiket);
	$bast = $this->apl->getSelectedData('bast', array('id_bast' => $t->id_bast))->row();
	if ($bast) {
		$unit = $this->apl->get_nilai_pilih('db_unit', 'kode', 'id_unit=' . $bast->id_unit);
		$pemilik = $this->apl->get_nilai_pilih('pemilik', 'nama', 'id_pemilik=' . $bast->id_pemilik);

		?>
		<tr>
			<th>Unit / Owner</th>
			<th><?= $unit . ' / ' . $pemilik; ?></th>
		</tr>
	<?php } ?>
	<tr>
		<th>No Form</th>
		<th><?= $t->no_form; ?></th>
	</tr>
	<tr>
		<th>Statement By / Contact</th>
		<th><?= $t->pelapor . ' / ' . $t->kontak; ?></th>
	</tr>
	<tr>
		<th>Date Of Filing</th>
		<th><?= $this->apl->tgl_format($t->tanggal, 5); ?></th>
	</tr>
	<?php
	if ($t->tanggal_awal != '') {
		?>
		<tr>
			<th>Work Schedule</th>
			<th><?= $this->apl->tgl_format($t->tanggal_awal, 5) . " To " . $this->apl->tgl_format($t->tanggal_ahir, 5); ?></th>
		</tr>
		<tr>
			<th>Date of Completion</th>
			<th><?= $this->apl->tgl_format($t->tanggal_selesai, 5); ?></th>
		</tr>
	<?php } ?>

	<tr>
		<th>Craeted By</th>
		<th><?= $this->apl->get_nilai_pilih("admin", "nama_admin", "id_admin=" . $t->id_admin); ?></th>
	</tr>
	<?php if ($t->tipe == '6') { ?>
		<tr>
			<th>Lost/damaged card Number</th>
			<th><?= $t->kartu_hilang; ?></th>
		</tr>
	<?php } ?>
</table>
<label>Note</label>
<br>
<?php
$lainnya = ($t->lainnya != "") ? ' -- ' . $t->lainnya : '';
$nt = $t->ket . " " . $lainnya;
?>

<?php

$textToStore = nl2br(htmlentities($nt, ENT_QUOTES, 'UTF-8'));
echo $textToStore;
?>
<hr class="my-4"/>
<?php if (isset($p)) { ?>
	<h6 class="heading-small text-muted mb-4">Repair Data</h6>
	<table class="table table-sm table-bordered small">
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
<?php }
if ($d) { ?>
	<h6 class="heading-small text-muted mb-4"><?= ($t->rab == '0') ? 'Cost' : 'Budget Plan'; ?> </h6>
	<table class="table table-sm table-bordered small">
		<thead>
		<tr class="bg-secondary">
			<th>Name</th>
			<th>qty</th>
			<th>Price</th>
			<?php if ($t->rab == '0') { ?>
				<th>Deposit</th>
				<th>Material</th>
			<?php } ?>
		</tr>
		</thead>
		<tbody>
		<?php
		$total = 0;

		foreach ($d as $det) {
			$jumlah = $det->jumlah + $det->deposit + $det->material;
			$total += $jumlah;
			?>
			<tr>
				<td><?= $det->nama; ?></td>
				<td><?= $det->qty; ?></td>
				<td><span class="float-right"><?= $this->apl->number_format($det->jumlah, 1); ?></span></td>

				<?php if ($t->rab == '0') { ?>
					<td><span class="float-right"><?= $this->apl->number_format($det->deposit, 1); ?></span></td>
					<td><span class="float-right"><?= $this->apl->number_format($det->material, 1); ?></span></td>

				<?php } ?>
			</tr>
			<?php
		}
		?>
		</tbody>
		<tfoot>
		<tr>
			<td></td>
			<?php if ($t->rab == '0') { ?>
				<td></td>
				<td></td>
			<?php } ?>
			<td><span class="font-weight-bold">Total</span></td>
			<td><span class="font-weight-bold float-right"><?= $this->apl->number_format($total, 1); ?></span></td>
		</tr>
		</tfoot>
	</table>
<?php } ?>
<div class="row justify-content-end mt-5">
	<div class="col-xs-4 text-center">
		<?= $this->upload_model->tampil_gambar($t->signature, "", "signature"); ?>
		<hr class="py-0 my-0">
		<?= $t->pelapor; ?>
	</div>
</div>
<br>
<table class="table table-bordered">
	<tr>
		<td class="text-center">
			<span class="text-muted font-weight-bold">Request  By: Dept Head</span>
			<?php
			if ($t->qr == 1) {
				$qr = $this->apl->get_nilai_pilih("tiket_approv", "qr_code",
						array(
								'id_tiket' => $t->id_tiket,
								'id_admin' => $t->id_tr,
								'post' => 1,
						));

				if ($qr != "") {
					echo '<br><img src="' . base_url($qr) . '" width="120px" height="120px"><br>';
				}
			} else { ?>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
			<?php } ?>
			<span class="text-sm text-muted"><?= $this->apl->get_nilai_pilih("admin", "nama_admin", "id_admin=" . $t->id_tr); ?></span>
		</td>
		<?php
		if ($t->tipe == 3 || $t->tipe == 5 || $t->tipe == 6) {
			?>
			<td class="text-center">
				<span class="text-muted font-weight-bold">Cashier By</span>
				<?php
				if ($b) {
					if ($t->qr == 1) {
						$qr = $this->apl->get_nilai_pilih("tiket_approv", "qr_code",
								array(
										'id_tiket' => $t->id_tiket,
										'id_admin' => $b->id_admin,
										'post' => 2,
								));
						echo '<br><img src="' . base_url($qr) . '" width="120px" height="120px"><br>';
					} else { ?>
						<br>
						<br>
						<br>
						<br>
						<br>
						<br>
					<?php } ?>
					<span class="text-sm text-muted"><?php
						echo ($b) ? $this->apl->get_nilai_pilih("admin", "nama_admin", "id_admin=" . $b->id_admin) : '';
						?></span>
				<?php } ?>
			</td>
			<?php
		}
		if ($t->tipe == 4 || $t->tipe == 5 || $t->tipe == 6 || $t->tipe == 7) {
			if ($t->tipe == '4') {
				if ($t->tanggal > date('2021-10-27')) {
					?>
					<td class="text-center">
						<span class="text-muted font-weight-bold"><?= $this->apl->get_nilai_pilih("db_dep", "nama", array('id_dep' => $t->id_dep)); ?></span>
						<?php
						if ($t->post > 2 && $t->id_dept > 0) {
							if ($t->qr == 1) {
								$qr = $this->apl->get_nilai_pilih("tiket_approv", "qr_code",
										array(
												'id_tiket' => $t->id_tiket,
												'id_admin' => $t->id_dept,
												'post' => 3,
										));
								echo '<br><img src="' . base_url($qr) . '" width="120px" height="120px"><br>';
							} else { ?>
								<br>
								<br>
								<br>
								<br>
								<br>
								<br>
							<?php } ?>
							<span class="text-sm text-muted"><?php
								if ($t->post > 2) {
									echo ($t->id_dept > 0)
											? $this->apl->get_nilai_pilih("admin", "nama_admin", "id_admin=" . $t->id_dept)
											: '';
								}
								?></span>
						<?php } ?>
					</td>
					<?php
				}
			} else {
				?>
				<td class="text-center">
					<span class="text-muted font-weight-bold"><?= $this->apl->get_nilai_pilih("db_dep", "nama", array('id_dep' => $t->id_dep)); ?></span>
					<?php
					if ($t->post > 2 && $t->id_dept > 0) {
						if ($t->qr == 1) {
							$qr = $this->apl->get_nilai_pilih("tiket_approv", "qr_code",
									array(
											'id_tiket' => $t->id_tiket,
											'id_admin' => $t->id_dept,
											'post' => 3,
									));
							echo '<br><img src="' . base_url($qr) . '" width="120px" height="120px"><br>';
						} else { ?>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
						<?php } ?>
						<span class="text-sm text-muted"><?php
							if ($t->post > 2) {
								echo ($t->id_dept > 0)
										? $this->apl->get_nilai_pilih("admin", "nama_admin", "id_admin=" . $t->id_dept)
										: '';
							}
							?></span>
					<?php } ?>
				</td>


			<?php }
		}

		if ($t->qr == 1) { ?>
			<td class="text-center">
				<span class="text-muted font-weight-bold">Building Management</span>
				<?php

				if ($t->post > 2 && $t->id_acc > 0) {

					$qr = $this->apl->get_nilai_pilih("tiket_approv", "qr_code",
							array(
									'id_tiket' => $t->id_tiket,
									'id_admin' => $t->id_acc,
									'post' => 4,
							));
					echo '<br><img src="' . base_url($qr) . '" width="120px" height="120px"><br>';
				} ?>
				<span class="text-sm text-muted"><?php
					if ($t->post > 2) {
						echo ($t->id_acc > 0)
								? $this->apl->get_nilai_pilih("admin", "nama_admin", "id_admin=" . $t->id_acc)
								: '';
					}
					?></span>
			</td>
		<?php } ?>

	</tr>
</table>
<br>

<?php if (isset($a) and ($a)) { ?>
	<h6 class="heading-small text-muted mb-4">Note</h6>
	<table class="table table-sm table-striped">

		<tbody>
		<?php foreach ($a as $ap) { ?>
			<tr>
				<td><?= $ap->note; ?></td>
			</tr>
		<?php }
		if ($t->kebijakan == 1) {
			?>
			<tr>
				<td>Dokumen Lainnya</td>
				<td><a href="<?= base_url('upload/lainnya/' . $t->lainnya); ?>" target="_blank">
						<?= $t->lainnya; ?></a></td>
			</tr>
			<?php

		}
		?>

		</tbody>
	</table>
<?php }
if (isset($berkas) and ($berkas)) { ?>
	<h6 class="heading-small text-muted mb-4">File</h6>
	<table class="table table-sm table-striped">
		<thead>
		<tr>
			<td>Name</td>
			<td>File</td>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($berkas as $ber) { ?>
			<tr>
				<td><?= $ber->nama; ?></td>
				<!--
				<td><?= $this->upload_model->tampil_gambar_new_tab($ber->file, ""
						, $this->apl->get_nilai_pilih("db_upload", "folder", array('id_upload' => $ber->id_upload))
						, 'height="40px"'); ?></td>
				-->
				<td>
					<a href="<?= base_url('upload/' .
							$this->apl->get_nilai_pilih("db_upload", "folder", array('id_upload' => $ber->id_upload))
							. '/' . $ber->file); ?>" target="_blank">
						<?= $ber->file; ?></a>
				</td>

			</tr>
		<?php }
		if ($t->kebijakan == 1) {
			?>
			<tr>
				<td>Dokumen Lainnya</td>
				<td><a href="<?= base_url('upload/lainnya/' . $t->lainnya); ?>" target="_blank">
						<?= $t->lainnya; ?></a></td>
			</tr>
			<?php

		}
		?>

		</tbody>
	</table>
<?php }
//if ($proses != 'approval') {
if ($h) { ?>

	<h6 class="heading-small text-muted mb-4">Job History</h6>
	<table class="table table-sm table-borderless small">
		<thead>

		<tr class="bg-secondary">
			<th>History</th>
			<th>Date</th>
			<th>File</th>
		</tr>
		</thead>

		<tbody>
		<?php
		foreach ($h as $his) {
			?>
			<tr>
				<td><?= $his->ket; ?></td>
				<td><?= $this->apl->tgl_format($his->tanggal, 1); ?></td>
				<td><a href="<?= base_url('upload/histori/' . $his->file); ?>"
					   target="_blank"><?= $his->file; ?></a></td>
			</tr>
			</tr>
			<?php
		}
		?>
		</tbody>
	</table>
	<hr class="my-4"/>
	<?php
}
?>
<input type="hidden" name="id_tiket" value="<?= $t->id_tiket; ?>">

<?php
if ($proses == 'note') {
	?>
	<div class="form-group">
		<label for="">Add Note</label>
		<input type="text" name="note" class="form-control" value="">
	</div>
<?php }
if ($proses == 'done') { ?>
	<div class="form-group">
		<label for="">Complete On Date</label>
		<input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d'); ?>" required>

	</div>
<?php }
if ($proses == 'card') { ?>
	<div class="form-group">
		<label for="">Card Number</label>
		<input type="text" name="nomor"
			   class="form-control" value="" required>

	</div>
	<div class="form-group">
		<label for="">Complete On Date</label>
		<input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d'); ?>" required>

	</div>
<?php }
if ($proses == 'fo') { ?>
	<div class="form-group">
		<label for="">Field check</label>
		<input type="text" name="nomor"
			   class="form-control" value="" required>

	</div>
<?php }
if ($proses == 'approval') { ?>
	<div class="form-group">
		<label for="">Approve Date</label>
		<input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d'); ?>" required>

	</div>
	<div class="form-group">
		<label for="">Note</label>
		<input type="text" name="note" class="form-control" value="">

	</div>
<?php }
if ($proses == 'pending') { ?>
	<div class="form-group">
		<label for="">Reject Date</label>
		<input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d'); ?>" required>

	</div>
	<div class="form-group">
		<label for="">Note Reject</label>
		<input type="text" name="note" class="form-control" value="">
	</div>
<?php } ?>
