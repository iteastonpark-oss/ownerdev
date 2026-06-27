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
<input type="hidden" name="jumlah_sebelumnya" value="<?= $bayar->jumlah; ?>">

<table class="table table-sm table-borderless">
	<tr>
		<th>Unit</td>
		<th><?php echo $unit->kode; ?></td>
	</tr>
	
	<tr>
		<th>Payment</td>
		<th><?php echo $this->apl->number_format($bayar->jumlah,1); ?></td>
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
<br>
<?php
$this->load->view('bayar/invoice/payment_detail');
?>






