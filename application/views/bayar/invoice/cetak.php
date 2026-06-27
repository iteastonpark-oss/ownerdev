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
//echo $ka;
//die();
?>


<style>

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

	table {
		font-size: 12pt
	}

	td {
		white-space: normal !important;
		word-wrap: break-word;
	}
</style>

<div class="container text-monospace mt--3">
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

	<table class="table-borderless table-sm" style="font-size: 14pt; width: 100%; font-family:'Courier New', Courier, monospace">
		<tr>
			<th style="font-size: 12pt">Sudah Terima dari</th>
			<td style="font-size: 12pt"><b>
				: <?php

					if ($bast_sewa) {
						echo $bast_sewa->nama;
					} else {
						echo $this->apl->get_nilai_pilih("pemilik", "nama", array('id_pemilik' => $bast->id_pemilik));
					}
					?>
				<?php echo "<b><i class='pull-right'>( " . $this->apl->get_nilai_pilih("db_unit", "kode", array('id_unit' => $bast->id_unit))
					. " )</i></b>"; ?>
				</b>
			</td>
		</tr>

		<tr>
			<th style="font-size: 12pt">Banyaknya Uang (Rp.)</th>
			<td style="font-size: 12pt"><b>:<?php echo " Rp. " . $this->apl->number_format($bayar->jumlah, 1); ?> </b>

				<?php echo "<b><i class='pull-right'>( " . $this->apl->terbilang($bayar->jumlah)
					. " Rupiah )</i></b>"; ?>
			</td>
		</tr>
		<tr>
			<th style="font-size: 12pt">Cara Bayar</th>
			<td style="font-size: 12pt"><b>:
					<?php echo $this->apl->get_nilai_pilih("db_via", "nama", "id_via=" . $bayar->id_via); ?> </b></td>
		</tr>
		<tr>
			<th style="font-size: 12pt">Tanggal Pembayaran</th>
			<td style="font-size: 12pt"><b>:
					<?php echo $this->apl->tgl_format($b->tanggal, 5); ?></b></td>
		</tr>
		<tr>
			<th style="font-size: 12pt">Untuk Pembayaran</th>
			<td style="font-size: 12pt"><b>:

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
				</b>
			</td>
		</tr>

	</table>

	<div class="row">
		<div class="col-sm-8">

		</div>
		<div class="col-sm-4 text-center">
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
