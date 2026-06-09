<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 8/11/2016
 * Time: 10:34 AM
 */
?>

<script src="<?php echo base_url('assets/getNumber.js') ?>"></script>
<input type="hidden" name="id_detail" value="<?= $d->id_detail;?>">
<!--
<div class="form-group">
	<label for="" class="control-label">ID Tagihan</label>
	<?php
	echo $this->dropdown_model->getDropdownTagihan('id_tag', $d->id_tag, 'class="form-control"');

	?>
</div>
<div class="form-group">
	<label for="" class="control-label">Tanggal</label>
	<input type="date" name="tanggal" class="form-control" value="<?= $d->tanggal;?>" required>
</div>
<div class="form-group">
	<label for="" class="control-label">Note</label>
	<input type="text" name="note" class="form-control" value="<?= $d->note;?>" required>
</div>
-->
<div class="form-group">
	<label for="" class="control-label">Jumlah</label>
	<input type="text" name="jumlah" class="form-control getNumber" value="<?= $d->jumlah;?>" required>
</div>
