<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 9/22/2016
 * Time: 9:30 AM
 */
?>

<script src="<?php echo base_url('assets/getNumber.js') ?>"></script>
<h3>Rincian Pembayaran</h3>
<input type="hidden" name="id_bayar" value="<?php echo ($submit == 'edit') ? $b->id_bayar : ''; ?>">
<input type="hidden" name="id_tiket" value="<?= $tiket->id_tiket; ?>">

<table class="table table-sm table-borderless">
	<tr>
		<td>No Form</td>
		<td><?php echo $tiket->no_form; ?></td>
	</tr>

	<?php
	if ($submit == 'tambah') {
		?>
		<tr>
			<td>Bill</td>
			<td><?php echo $this->apl->number_format($tagihan, 1); ?></td>
		</tr>
	<?php } ?>
</table>

<div class="row">
	<div class="col-md-6">
		<label for="" class="control-label">Pay Date</label>
		<input type="date" name="tanggal" class="form-control"
			   value="<?php echo date('Y-m-d'); ?>">
	</div>
	<div class="col-md-6">
		<label for="" class="control-label">Payment With</label>
		<?php
		echo $this->dropdown_model->getDropdownViaBayar('id_via', ($submit == 'edit') ? $bayar->id_via : '',
				'class="form-control" required');
		?>
	</div>


</div>
<hr>
<div class="row">

	<div class="col-md-6">
		<label for="" class="control-label">Note</label>
		<input type="text" name="ket" class="form-control"
			   value="<?php echo ($submit == 'edit') ? $b->ket : ''; ?>">
	</div>
	<div class="col-md-6">
		<label for="" class="control-label">Amount Paid</label>
		<input type="text" name="bayar" class="form-control getNumber total_bayar"
			   value="<?php echo ($submit == 'edit') ? $b->jumlah : $tagihan; ?>" required
			   readonly
		>

	</div>
</div>


