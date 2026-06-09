<?php

/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 9/22/2016
 * Time: 9:30 AM
 */
?>
<title><?= $bayar->kwt . "-" . time(); ?></title>


<script type="text/javascript">
	var is_chrome = function() {
		return Boolean(window.chrome);
	}
	window.onload = function() {
		if (is_chrome) {
			/*
			 * These 2 lines are here because as usual, for other browsers,
			 * the window is a tiny 100x100 box that the user will barely see.
			 * On Chrome, it needs to be big enough for the dialogue to be read
			 * (NB, it also includes a page preview).
			 */
			window.moveTo(0, 0);
			window.resizeTo(640, 480);

			// This line causes the print dialogue to appear, as usual:
			window.print();

			/*
			 * This setTimeout isn't fired until after .print() has finished
			 * or the dialogue is closed/cancelled.
			 * It doesn't need to be a big pause, 500ms seems OK.
			 */
			setTimeout(function() {
				window.close();
			}, 500);
		} else {
			// For other browsers we can do things more briefly:
			window.print();
			window.close();
		}
	}
	/*
	window.onload = function () {
		window.print();
		window.close();
	}
	*/
</script>

<?php
$this->load->view('layout/header');
?>
<style>
	table .table-responsive {
		/*font-size: 40pt !important;*/
	}


	@page {
		size: letter;
		margin: 0;
	}
	@media print {
		html, body {
			width: 215.9mm;
			height: 279.4mm;
			font-family: Consolas, Menlo, Monaco, 'Andale Mono WT', 'Andale Mono', 'Lucida Console', 'Lucida Sans Typewriter', 'DejaVu Sans Mono', 'Bitstream Vera Sans Mono', 'Liberation Mono', 'Nimbus Mono L', 'Courier New', Courier, monospace;

		}
		/* ... the rest of the rules ... */
	}
</style>
<div class="card">

	<div class="card-header">
		<?php $this->load->view('layout/kepala_cetak'); ?>
		<div class="text-center justify-content-center">
			<h4 class=" ">KWITANSI PEMBAYARAN</h4>
		</div>
	</div>
	<div class="card-body" style="font-size: 14pt; width: 100%; font-family:'Courier New', Courier, monospace">
		<table class="table table-sm table-borderless font-weight-bold">
			<tr>
				<th><span style="font-size: 12pt">No Unit</span></th>
				<td><span style="font-size: 12pt">
						: <?php echo $unit; ?></span></td>
				<td></td>
				<th class="text-right"><span class="font-weight-bold" style="font-size: 12pt">No. <?php echo $bayar->kwt; ?></span>
				</th>
			</tr>
			<tr>
				<th><span style="font-size: 12pt">Nama</span></th>
				<td><span style="font-size: 12pt">
						: <?php

							$id_pemilik = $this->apl->get_nilai_pilih("bast", "id_pemilik", "id_bast=" . $bayar->id_bast);
							echo $this->apl->get_nilai_pilih("pemilik", "nama", "id_pemilik=" . $id_pemilik); ?> </span>
				</td>
				<td></td>
				<td class="text-right"><span class="text-right" style="font-size: 12pt"> <?php echo $this->apl->tgl_format(date('j M Y'), 5); ?></span>
				</td>
			</tr>
		</table>
		<br>
		<table class="ml-4 table-borderless">
			<tr>
				<th><span style="font-size: 12pt">Sudah Terima dari</span></th>
				<th>
					<span style="font-size: 12pt">: <?php echo $unit; ?> </span>
				</th>
			</tr>

			<tr>
				<th><span style="font-size: 12pt">Banyaknya Uang (Rp.)</span></th>
				<th><span style="font-size: 12pt">: <?php echo "Rp. " . $this->apl->number_format($bayar->jumlah, 1);

													?></span>
				</th>
			</tr>
			<tr>
				<th><span style="font-size: 12pt">Untuk Pembayaran</span></th>
				<th><span style="font-size: 12pt">: <?= $l->ket; ?> </span></th>
			</tr>
		</table>
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
				<p class="">Penerima,</p>
				<?= '<br><img src="' . base_url($bayar->qr_code) . '" width="120px" height="120px"><br>'; ?>
				<span class="text-center"><span class="text-center">Kasir</span>
					<br><?= $ka; ?>
				</span>

			</div>
		</div>
	</div>

</div>
