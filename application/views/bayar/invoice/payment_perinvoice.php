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
<input type="hidden" name="id_bast" value="<?= $bast->id_bast; ?>">
<!--
<input type="hidden" name="id_billing" value="<?= $b->id_billing; ?>">
-->
<table class="table table-sm table-borderless">
	<tr>
		<td>Unit</td>
		<td><?php echo $unit->kode; ?></td>
	</tr>

	<tr>
		<td>Bill</td>
		<td><?php
			echo $this->apl->number_format($piutang->piutang, 1);
			?></td>
	</tr>
</table>

<?php
$this->load->view('bayar/invoice/payment_detail');
?>
