<?php

/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 9/22/2016
 * Time: 9:30 AM
 */
?>

<title><?php time() . " Kwt-Bayar"; ?></title>
<?php
//echo $ka;
//die();
?>


<?php
$this->load->view('layout/header');
//echo $ka;
//die();
?>

<script>
	document.getElementsByTagName('body')[0].style.display = 'none';
</script>

<style>
	table {
		font-size: 10pt
	}

	td {
		white-space: normal !important;
		word-wrap: break-word;
	}
</style>

<body>
	<div class="container-fluid text-monospace">
		<?php $this->load->view('layout/kepala_cetak'); ?>
		<div class="row justify-content-center">
			<div class="col-sm-4">
				<h3>KWITANSI PEMBAYARAN</h3>
			</div>
		</div>
		<hr class="my-0">
		<div class="pull-right">
			<h4>No. <?php echo $bayar->kwt; ?></h4>
		</div>

		<table class="" style="font-size: 12pt; font-family:'Courier New', Courier, monospace">
			<tr>
				<th style="font-size: 12pt">Sudah Terima dari</th>
				<td style="font-size: 12pt">
					: <?php echo $this->apl->get_nilai_pilih("pemilik", "nama", array('id_pemilik' => $bast->id_pemilik)); ?>
					<?php echo "<b><i class='pull-right'>( " . $this->apl->get_nilai_pilih("db_unit", "kode", array('id_unit' => $bast->id_unit))
						. " )</i></b>"; ?>

				</td>
			</tr>

			<tr>
				<th style="font-size: 12pt">Banyaknya Uang (Rp.)</th>
				<td style="font-size: 12pt">:<?php echo " Rp. " . $this->apl->number_format($bayar->jumlah, 1); ?>

					<?php echo "<b><i class='pull-right'>( " . $this->apl->terbilang($bayar->jumlah)
						. " Rupiah )</i></b>"; ?>
				</td>
			</tr>
			<tr>
				<th style="font-size: 12pt">Cara Bayar</th>
				<td style="font-size: 12pt">:
					<?php echo $this->apl->get_nilai_pilih("db_via", "nama", "id_via=" . $bayar->id_via); ?> </td>
			</tr>
			<tr>
				<th style="font-size: 12pt">Tanggal Pembayaran</th>
				<td style="font-size: 12pt">:
					<?php echo $this->apl->tgl_format($b->tanggal, 5); ?></td>
			</tr>
			<tr>
				<th style="font-size: 12pt">Untuk Pembayaran</th>
				<td style="font-size: 12pt">:

					<?php
					echo $bayar->ket;

					/*
				if($bayar->kwt=='1139/Kwt-INV/IV/2021'){
					echo "IPL Juli 2017 - Desember 2017 (Sisa tagihan Rp,10.000.000 Periode Januari 2018 - Juni 2021)";
				} else {
					echo $b->ket;

				}
				*/

					?>

				</td>
			</tr>

		</table>

		<div class="row">
			<div class="col-6">

			</div>
			<div class="col-6 text-center">
				<span class="text-center py-0"><?php
												echo $this->bm_model->get()->kota
													. ', ' . $this->apl->tgl_format($b->tanggal, 5); ?></span>
				<br>
				<span class="text-center py-0"><span class="text-center">Finance,</span></span>
				<?= '<br><img src="' . base_url($bayar->qr_code) . '" width="120px" height="120px"><br>'; ?>
				<span class="text-center"><span class="text-center">Kasir</span>
					<br><?= $ka; ?>
				</span>
			</div>
		</div>
	</div>
</body>