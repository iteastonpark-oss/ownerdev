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

<style>
	body {
		padding-top: -10px;
		font-family:'Courier New', Courier, monospace;
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
<div class="container single_record">
	<div class="card mt--100">
		<div class="card-body">

			<table style="width: 100%" class="table-borderless">
				<tr>
					<td style="width: 60%"><img
								src="<?php echo base_url('upload/logo/' . $this->bm_model->get()->logo); ?>"
								class="text-center" alt="" width="40%">
					</td>
					<td>
						<b>
							<?php echo $this->bm_model->get()->nama; ?><br>
							<?php echo $this->bm_model->get()->alamat; ?><br>
							<?php echo $this->bm_model->get()->kota; ?>, <?php echo $this->bm_model->get()->provinsi; ?>
							<br>
							No. Telp. <?php echo $this->bm_model->get()->telp; ?><br>
						</b>
					</td>
				</tr>
			</table>
			<?php
			//echo $this->bm_model->get()->template_invoice;
			$this->load->view($this->bm_model->get()->template_invoice);
			?>
			<table style="font-size: 9pt;font-family: 'Century Gothic'; ">
				<tr>
					<td colspan="2"><br></td>
				</tr>

				<tr>
					<td style="width: 5%"></td>
					<td style="line-height: 8px; font-size: 6pt">
						<ul style="padding-left: 10px">
							<li>Harap Mencantumkan No. Unit dan No. Invoice pada kolom berita di slip transfer dan mohon
								di
								konfirmasi
								kebagian keuangan <?php echo $this->bm_model->get()->nama; ?></li>
							<li>Untuk mengambil official Receipt harap menunjukan slip transfer asli ke bagian kasir,
								email
								: <?php echo $this->bm_model->get()->email_finance; ?></li>
							<li>Denda 3% akan dikenakan dari total tagihan untuk setiap keterlambatan terhitung setelah
								jatuh
								tempo
							</li>
							<li>Biaya pengiriman Rp. 10.000 akan dibebankan kepada masing-masing pemilik unit untuk
								setiap
								pengiriman invoice, kwitansi, dll kecuali melalui email
							</li>
							<li>Pemutusan utilitas akan dilakukan untuk setiap unit yang belum melakukan pembayaran
								setelah
								jatuh tempo
							</li>
							<li>Abaikan apabila sudah melakukan pembayaran</li>
							<li>Apabila ada pertanyaann atau keluhan dapat disampaikan melalui No.
								Telp <?php echo $this->bm_model->get()->nama; ?>
								di <?php echo $this->bm_model->get()->telp; ?>
								atau <?php echo $this->bm_model->get()->telp2; ?></li>
						</ul>
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>
</body>
