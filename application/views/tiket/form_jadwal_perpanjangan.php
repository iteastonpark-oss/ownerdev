<script src="<?php echo base_url('assets/getNumber.js') ?>"></script>

<input type="hidden" name="id_tiket" value="<?= $j->id_tiket; ?>">
<table class="table table-borderless table-striped">
	<tr>
		<th colspan="2">Schedule Period 1</th>
	</tr>
	<tr>
		<th>Start Date</th>
		<td><?= $this->apl->tgl_format($j->tanggal_awal, 5); ?></td>
	</tr>
	<tr>
		<th>End Date</th>
		<td><?= $this->apl->tgl_format($j->tanggal_ahir, 5); ?></td>
	</tr>
</table>

<div class="form-group">
	<label for="">Start Date of Work</label>
	<input type="date" name="tanggal_awal" class="form-control"
		   value="<?php
		   $tanggal_awal = date('Y-m-d', strtotime('+1 days', strtotime($j->tanggal_ahir)));
		   echo $tanggal_awal; ?>" required>
</div>
<div class=" form-group">
	<label for="">End Date of Work</label>
	<input type="date" name="tanggal_ahir" class="form-control"
		   value="<?= date('Y-m-d', strtotime('+15 days', strtotime($tanggal_awal))); ?>" required>
</div>

<div class="form-group">
	<label for="">Supervisi</label>
	<input type="hidden" name="id_tag" value="5000">
	<input type="text" name="jumlah" class="form-control getNumber"
		   value="<?= $d->jumlah; ?>"
		   required>
</div>
<div class="form-group">
	<label for="">Deposit</label>
	<input type="text" name="deposit" class="form-control getNumber"
		   value="<?= $d->deposit; ?>"
		   required>
</div>

<script>
	$('[name=tanggal_awal]').change(function () {
		var tanggal1 = $(this).val();
		var tanggal2 = $('[name=tanggal_ahir]').val();
		if (tanggal1 > tanggal2) {
			$('[name=tanggal_ahir]').val(tanggal1);
		}
	})

	$('[name=tanggal_ahir]').change(function () {
		var tanggal2 = $(this).val();
		var tanggal1 = $('[name=tanggal_awal]').val();
		if (tanggal2 < tanggal1) {
			$('[name=tanggal_awal]').val(tanggal2);
		}
	})
</script>
