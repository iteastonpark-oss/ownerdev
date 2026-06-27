<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 9/22/2016
 * Time: 9:30 AM
 */
?>

<script src="<?php echo base_url('assets/getNumber.js') ?>"></script>
<h3>Payment Details</h3>
<input type="hidden" name="id_bayar" value="<?php echo ($submit == 'edit') ? $bayar->id_bayar : ''; ?>">
<input type="hidden" name="id_bast" value="<?= $bast->id_bast; ?>">

<table class="table table-sm table-borderless">
	<tr>
		<td>Unit</td>
		<td><?php echo $unit->kode; ?></td>
	</tr>

	<?php
	if ($submit == 'tambah') {
		?>
		<tr>
			<td>AR</td>
			<td><?php echo $this->apl->number_format($d->piutang, 1); ?></td>
		</tr>
	<?php } ?>
</table>

<div class="row">
	<div class="col-md-6">
		<label for="" class="control-label">Pay Date</label>
		<input type="date" name="tanggal" class="form-control form-control-sm"
			   value="<?php echo date('Y-m-d'); ?>">
	</div>
	<div class="col-md-6">
		<label for="" class="control-label">Payment With</label>
		<?php
		echo $this->dropdown_model->getDropdownViaBayar('id_via', ($submit == 'edit') ? $bayar->id_via : '',
				'class="form-control form-control-sm" required');
		?>
	</div>


</div>
<hr>
<div class="row">

	<div class="col-md-6">
		<label for="" class="control-label">Note</label>
		<input type="text" name="ket" class="form-control form-control-sm"
			   value="<?php echo ($submit == 'edit') ? $bayar->ket : ''; ?>">
	</div>
	<div class="col-md-6">
		<label for="" class="control-label">Amount Paid</label>
		<input type="text" name="bayar" class="form-control form-control-sm getNumber total_bayar"
			   value="<?php echo ($submit == 'edit') ? $bayar->jumlah : $d->piutang; ?>" required>

	</div>
</div>


