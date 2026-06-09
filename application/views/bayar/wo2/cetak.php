<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 9/22/2016
 * Time: 9:30 AM
 */
?>


<?php
$this->load->view('layout/header');
?>
<div class="container">

	<?php
	$this->load->view('layout/kepala_cetak');

	?>
	<div class="row">
		<div class="col-sm-4">

		</div>
		<div class="col-sm-4">
			<h4>KWITANSI PEMBAYARAN</h4>
		</div>
		<div class="col-sm-4">

			<img src=""
				 class="pull-right" alt="" width="30%">
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-sm-6">
			No. <?php echo $bayar->kwt; ?>
		</div>
		<div class="col-sm-6">
			<p class="pull-right"><?php echo date('j M Y'); ?></p>
		</div>
	</div>
	<br>
	<table class="table table-sm table-borderless">
		<tr>
			<td>Sudah Terima dari</td>
			<td>
				: <?php echo $tiket->pelapor; ?> </td>
		</tr>

		<tr>
			<td>Banyaknya Uang (Rp.)</td>
			<td>:<?php echo "Rp. " . $this->apl->number_format($bayar->jumlah, 1);

				?>
			</td>
		</tr>
		<tr>
			<td>Untuk Pembayaran</td>
			<td>:
				<?php echo "Work Order"; ?> </td>
		</tr>
	</table>

	<div class="row">
		<div class="col-sm-8">
			<table>
				<tr>
					<td>Terbilang Rp</td>
					<td> : <?php echo "<i>" . $this->apl->terbilang($bayar->jumlah) . " Rupiah</i>"; ?></td>
				</tr>
			</table>

			<br>

		</div>
		<div class="col-sm-4">
			<p class="text-center">Finance,</p>
			<br>
			<br>
			<br>
		</div>
	</div>
</div>
