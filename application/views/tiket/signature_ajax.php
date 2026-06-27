<!-- Argon CSS -->
<link type="text/css" href="<?php echo base_url('assets/argon/css/bootstrap/bootstrap.css'); ?>" rel="stylesheet">
<link type="text/css" href="<?php echo base_url('assets/argon/css/argon.css?v=1.0.0'); ?>" rel="stylesheet">

<!-- Argon CSS -->
<style>
	table {
		font-size: 10pt
	}

	td {
		white-space: normal !important;
		word-wrap: break-word;
	}
</style>
<h6 class="heading-small text-muted mb-4">Information</h6>
<table class="table table-sm table-borderless small table-striped">
	<?php
	//$id_bast = $this->apl->get_nilai_pilih('tiket', 'id_bast', 'id_tiket=' . $t->id_tiket);
	$bast = $this->apl->getSelectedData('bast', array('id_bast' => $t->id_bast))->row();
	$unit = $this->apl->get_nilai_pilih('db_unit', 'kode', 'id_unit=' . $bast->id_unit);
	$pemilik = $this->apl->get_nilai_pilih('pemilik', 'nama', 'id_pemilik=' . $bast->id_pemilik);
	?>
	<tr>
		<th>Unit / Owner</th>
		<th><?= $unit . ' / ' . $pemilik; ?></th>
	</tr>
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
	<tr>
		<th>Note</th>
		<th><?= $t->ket; ?></th>
	</tr>
</table>
<hr class="my-4"/>
<?php
if ($p) {
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
	<?php
}
if ($d) { ?>
	<h6 class="heading-small text-muted mb-4"><?= ($t->rab == '0') ? 'Cost' : 'Budget Plan'; ?> </h6>
	<table class="table table-sm table-borderless small">
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
				<td><?= $this->apl->number_format($det->jumlah, 1); ?></td>

				<?php if ($t->rab == '0') { ?>
					<td><?= $this->apl->number_format($det->deposit, 1); ?></td>
					<td><?= $this->apl->number_format($det->material, 1); ?></td>

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
			<td><span class="font-weight-bold"><?= $this->apl->number_format($total, 1); ?></span></td>
		</tr>
		</tfoot>
	</table>
	<?php
	$cek = $this->upload_model->cek_link($t->signature, 'signature');
	if ($cek == '1') {
		?>
		<div class="row justify-content-end">
			<div class="col-md-4 text-center">
				<?= $this->upload_model->tampil_gambar($t->signature, "", "signature"); ?>
				<hr class="py-0 my-0">
				<?= $t->pelapor; ?>

			</div>
		</div>
	<?php }
}
if ($berkas) { ?>
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
?>


<link rel="stylesheet"
	  href="<?php echo base_url('assets/signature_pad-3.0.0-beta.4/package/docs/css/signature-pad.css'); ?>">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/signature_pad-3.0.0-beta.4/package/docs/css/ie9.css'); ?>">
<![endif]-->


<div class="justify-content-center">
	<input type="hidden" name="id_tiket" value="<?= $t->id_tiket; ?>">
	<div id="signature-pad" class="signature-pad">
		<div class="signature-pad--body">
			<canvas id="sig"></canvas>

		</div>
		<div class="signature-pad--footer">
			<div class="description">Sign above</div>
			<textarea id="signature64" class="form-control" name="signed" style="display: none"></textarea>
			<div class="signature-pad--actions">
				<div>
					<button type="button" class="btn btn-warning pull-left" id="clear">Clear</button>
				</div>
			</div>
		</div>
	</div>
</div>


<script src="<?php echo base_url('assets/signature_pad-3.0.0-beta.4/package/docs/js/signature_pad.umd.js'); ?>"></script>
<script src="<?php echo base_url('assets/signature_pad-3.0.0-beta.4/package/docs/js/app.js'); ?>"></script>

<script type="text/javascript"
		src="<?php echo base_url('assets/jquery.signature.package-1.2.0/js/jquery.ui.touch-punch.min.js') ?>"></script>

<script>
	var canvas = document.getElementById("sig");
	var cancelButton = document.getElementById('clear');

	cancelButton.addEventListener('click', function (event) {
		signaturePad.clear();
		$("#signature64").val("");
	});

	$("#sig").click(function () {
		var data = signaturePad.toDataURL('image/png');
		$("#signature64").val(data);

	});
	canvas.addEventListener("touchend", function (e) {
		var data = signaturePad.toDataURL('image/png');
		$("#signature64").val(data);
	}, false);
</script>


<!--
<script src="<?php echo base_url('assets/jquery-ui.min.js') ?>"></script>
<style>
	.kbw-signature {
		width: 400px;
		height: 200px;
	}

	#sig canvas {
		width: 100% !important;
		height: auto;
	}
</style>

<script type="text/javascript"
		src="<?php echo base_url('assets/jquery.signature.package-1.2.0/js/jquery.signature.min.js') ?>"></script>
<script type="text/javascript"
		src="<?php echo base_url('assets/jquery.signature.package-1.2.0/js/jquery.ui.touch-punch.min.js') ?>"></script>

<link rel="stylesheet" type="text/css"
	  href="<?php echo base_url('assets/jquery.signature.package-1.2.0/css/jquery.signature.css') ?>">

<div class="text-center">
	<input type="hidden" name="id_tiket" value="<?= $t->id_tiket; ?>">
	<label class="" for="">Signature:</label>
	<br>
	<div id="sig"></div>
	<br>
	<button id="clear" class="btn btn-sm btn-warning">Clear Signature</button>
	<textarea id="signature64" name="signed" style="display: none"></textarea>
</div>

<script type="text/javascript">

	var sig = $('#sig').signature({syncField: '#signature64', syncFormat: 'PNG'});
	$('#clear').click(function (e) {
		e.preventDefault();
		sig.signature('clear');
		$("#signature64").val('');
	});
</script>
-->
