<script src="<?php echo base_url('assets/search-box.js') ?>"></script>

<input type="hidden" name="id_tiket" value="<?= $j->id_tiket; ?>">
<div class="form-group">
	<label for="">Start Date of Work</label>
	<input type="date" name="tanggal_awal" class="form-control" value="<?= $j->tanggal_awal; ?>" required>
</div>
<div class="form-group">
	<label for="">End Date of Work</label>
	<input type="date" name="tanggal_ahir" class="form-control" value="<?= $j->tanggal_ahir; ?>" required>
</div>

<div class="form-group">
	<label for="">PIC</label>
	<?= $this->dropdown_model->getDropdownKaryawan("pic", $j->pic, 'class="form-control satu" required'); ?>

</div>


<script>
	$('[name=tanggal_awal]').change(function () {
		var tanggal1 = $(this).val();
		var tanggal2 = $('[name=tanggal_ahir]').val();
		if (tanggal1 > tanggal2) {

			var someDate = new Date(tanggal1);
			var numberOfDaysToAdd = 14;
			someDate.setDate(someDate.getDate() + numberOfDaysToAdd);
			//var dd = someDate.getDate();
			//var mm = someDate.getMonth() + 1;
			var dd = ('0' + (someDate.getDate())).slice(-2);
			var mm = ('0' + (someDate.getMonth() + 1)).slice(-2);
			var y = someDate.getFullYear();
			var someFormattedDate = y + '-' + mm + '-' + dd;
			$('[name=tanggal_ahir]').val(someFormattedDate);
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
