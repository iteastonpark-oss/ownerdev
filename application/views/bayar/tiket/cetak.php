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
<div class="container mt--1">
	<br>
	<br>
	<?php
	$this->load->view('layout/kepala_cetak');
	?>
	<div class="row justify-content-center">
		<div class="col-sm-4">
			<h4>KWITANSI PEMBAYARAN</h4>
		</div>
	</div>
	<hr class="mt-0">
	<div class="row">
		<div class="col-sm-6">
			No. <?php echo $bayar->kwt; ?>
		</div>
		<div class="col-sm-6">
			<p class="pull-right"><?php echo date('j M Y'); ?></p>
		</div>
	</div>
	<?php
	$bast = $this->apl->getSelectedData('bast', array('id_bast' => $tiket->id_bast))->row();
	$unit = $this->apl->get_nilai_pilih('db_unit', 'kode', 'id_unit=' . $bast->id_unit);
	$pemilik = $this->apl->get_nilai_pilih('pemilik', 'nama', 'id_pemilik=' . $bast->id_pemilik);
	?>
	<!--
	<div class="row">
		<div class="col-sm-6">
			Sudah Terima dari
		</div>
		<div class="col-sm-6">
			: <?php echo $tiket->pelapor; ?>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6">
			Unit / Pemilik
		</div>
		<div class="col-sm-6">
			: <?= $unit . ' / ' . $pemilik; ?>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
			Banyaknya Uang (Rp.)
		</div>
		<div class="col-sm-6">
			: <?php echo "Rp. " . $this->apl->number_format($b->jumlah + $b->material + $b->deposit, 1);

				?>
		</div>
	</div>
	-->
	<table class="ml-4 table-borderless" style="font-size: 12pt; width: 100%; font-family:'Courier New', Courier, monospace">
		<tr class="py-0">
			<th style="width: 30%">Sudah Terima dari</th>
			<th>
				: <?php echo $tiket->pelapor; ?> </th>
		</tr>


		<tr>
			<th>Unit / Pemilik</th>
			<th>: <?= $unit . ' / ' . $pemilik; ?></th>
		</tr>


		<tr>
			<th>Banyaknya Uang (Rp.)</th>
			<th>: <?php echo "Rp. " . $this->apl->number_format($b->jumlah + $b->material + $b->deposit, 1);

					?>
			</th>
		</tr>
		<tr>
			<th>Untuk Pembayaran</th>
			<th>:
				<?php echo $this->apl->get_nilai_pilih("tiket_tipe", "nama", "id="
					. $tiket->tipe); ?> </th>
		</tr>
		<tr>
			<th>Catatan</th>
			<th>: <?= $tiket->ket; ?> </th>
		</tr>
	</table>
	<hr>


	<hr class="py-0">
	<div class="row">
		<div class="col-8">
			<table class="table table-sm table-borderless">
				<tr class="py-0">
					<th class="py-0"><span style="font-size: 14pt">Terbilang Rp</span></th>
					<th class="py-0">
						<span style="font-size: 14pt"> : <?php echo "<i>" . $this->apl->terbilang($bayar->jumlah) . " Rupiah</i>"; ?></span>
					</th>
				</tr>
				<tr class="py-0">
					<th class="py-0"><span style="font-size: 14pt">Cara Bayar</span></th>
					<th class="py-0"><span style="font-size: 14pt">
							: <?php echo $this->apl->get_nilai_pilih("db_via", "nama", "id_via=" . $bayar->id_via); ?></span>
					</th>
				</tr>
				<tr>
					<th class="py-0"><span style="font-size: 14pt">Ket</span></th>
					<th class="py-0"><span style="font-size: 14pt">
							: <?= $bayar->ket; ?></span></th>
				</tr>
			</table>
		</div>
		<div class="col-4">
			<span class="text-center py-0"><span class="text-center">Finance,</span></span>
			<?= '<br><img src="' . base_url($bayar->qr_code) . '" width="120px" height="120px"><br>'; ?>
			<span class="text-center"><span class="text-center">Kasir</span>
				<br><?= $ka; ?>
			</span>
		</div>
	</div>
</div>