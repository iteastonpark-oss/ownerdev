<?php
?>

<table class="table table-bordered table-striped table-sm small">
	<thead class="bg-secondary">
	<tr>
		<td>No</td>
		<td>Type</td>
		<td>Date</td>
		<td>Invoice</td>
		<td>Payment</td>
		<td>Amount</td>
	</tr>
	</thead>
	<tbody>
	<?php
	$no = 0;
	$total = 0;
	foreach ($h as $d) {
		$no++;
		$inv = ($d->tipe == '1') ? $d->total : '0';
		$kwt = ($d->tipe == '2') ? $d->total : '0';
		$total = $total + $inv - $kwt;
		$bg= ($d->tipe == '1') ? 'light' : 'secondary';
		?>
		<tr class="bg-<?= $bg;?>">
			<td><?= $no; ?></td>
			<td><?= ($d->tipe == '1') ? 'INV' : 'KWT'; ?></td>
			<td><?= $d->tanggal; ?></td>
			<td><span class="float-right"><?= $this->apl->number_format($inv, 1); ?></span></td>
			<td><span class="float-right"><?= $this->apl->number_format($kwt, 1); ?></span></td>
			<th><span class="float-right"><?= $this->apl->number_format($total, 1); ?></span></th>
		</tr>
		<?php
	}
	?>
	</tbody>
</table>
