<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 8/11/2016
 * Time: 10:34 AM
 */
?>


<title><?php echo $unit->kode . "-Print Invoice-" . time(); ?></title>
<?php
//$this->load->view('layout/header');
?>

<?php
if ($redirect != '') {
	?>
	<script type="text/javascript">
		var is_chrome = function () {
			return Boolean(window.chrome);
		}
		window.onload = function () {
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
				setTimeout(function () {
					//window.close();
					window.location.href = "<?= site_url($this->input->get('red')) ?>";

				}, 500);
			} else {
				// For other browsers we can do things more briefly:
				window.print();
				//window.close();
				window.location.href = "<?= site_url($this->input->get('red')) ?>";
			}
		}

	</script>
<?php } else {
	?>
	<script type="text/javascript">
		var is_chrome = function () {
			return Boolean(window.chrome);
		}
		window.onload = function () {
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
				setTimeout(function () {
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
} ?>
<style>
	body {
		padding-top: -10px;
	}

	p {
		font-size: 8pt;
		font-family: "Century Gothic";
	}

	table, tr, td {
		font-size: 8pt;
		font-family: "Century Gothic";
		border-collapse: collapse;

	}

	.text-center {
		text-align: center;
	}

	.single_record {
		page-break-after: always;
	}

</style>
<body>
<div class="container-fluid single_record">
	<table style="width: 100%" class="table-borderless">
		<tr>
			<td style="width: 60%"><img src="<?php echo base_url('upload/logo/' . $this->bm_model->get()->logo); ?>"
										class="text-center" alt="" width="40%">
			</td>
			<td>
				<b>
					<?php echo $this->bm_model->get()->nama; ?><br>
					<?php echo $this->bm_model->get()->alamat; ?><br>
					<?php echo $this->bm_model->get()->kota; ?>, <?php echo $this->bm_model->get()->provinsi; ?><br>
					No. Telp. <?php echo $this->bm_model->get()->telp; ?><br>
				</b>
			</td>
		</tr>
	</table>
	<?php
	if($unit->id_group=='1')
	{
		//$this->load->view('billing/invoice/print_isi');
		$this->load->view($this->bm_model->get()->template_invoice);

	} else {
		//$this->load->view('billing/invoice/print_isi_ca');
		$this->load->view($this->bm_model->get()->template_invoice."_ca");

	}
	?>
	<table style="font-size: 9pt;font-family: 'Century Gothic'; ">
		<tr>
			<td colspan="2"><br></td>
		</tr>

		<tr>
			<td style="width: 5%"></td>
			<td style="line-height: 8px; font-size: 6pt">
				<ul style="padding-left: 10px">
					<li>Harap Mencantumkan No. Unit dan No. Invoice pada kolom berita di slip transfer dan mohon di
						konfirmasi
						kebagian keuangan <?php echo $this->bm_model->get()->nama; ?></li>
					<li>Untuk mengambil official Receipt harap menunjukan slip transfer asli ke bagian kasir, email
						: <?php echo $this->bm_model->get()->email_finance; ?></li>
					<li>Denda 3% akan dikenakan dari total tagihan untuk setiap keterlambatan terhitung setelah jatuh
						tempo
					</li>
					<li>Biaya pengiriman Rp. 10.000 akan dibebankan kepada masing-masing pemilik unit untuk setiap
						pengiriman invoice, kwitansi, dll kecuali melalui email
					</li>
					<li>Pemutusan utilitas akan dilakukan untuk setiap unit yang belum melakukan pembayaran setelah
						jatuh tempo
					</li>
					<li>Abaikan apabila sudah melakukan pembayaran</li>
					<li>Apabila ada pertanyaann atau keluhan dapat disampaikan melalui No.
						Telp <?php echo $this->bm_model->get()->nama; ?> di <?php echo $this->bm_model->get()->telp; ?>
						atau <?php echo $this->bm_model->get()->telp2; ?></li>
				</ul>
			</td>
		</tr>
	</table>

	<table style="width: 100%">
		<tr>
			<td style="width: 50%">
				<?php
				// echo base_url() . $qr_code;
				//echo '<img class="img-rounded" src="' . base_url() . $qr_code . '" width="40" height="40"> ';

				?>
			</td>
			<td><p class="text-center">Finance</p>
				<br>
				<br>
				<p class="text-center">Building Management</p></td>
		</tr>
	</table>

</div>
</body>
