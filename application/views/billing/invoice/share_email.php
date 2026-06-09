<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 9/30/2016
 * Time: 1:41 PM
 */
?>

<style>
	table {
		width: 100%;
	}

	#table_style {
		font-size: 8pt;
		font-family: 'Century Gothic';
		white-space: normal;
		line-height: 25px;
		border: solid 2px #000000;
	}

	#table_style td {
		padding-left: 10px;
		padding-right: 10px;

		border: 1px solid #000000;

	}

	#table_style th {
		padding-left: 10px;
		padding-right: 10px;
		border: 1px solid #000000;
		/*
		border: 1px solid #000000;
		*/
	}

	#table_style td td {
		padding-left: 10px;
		padding-right: 10px;
		border: 1px solid #FFFFFF;
		/*
		border: 1px solid #000000;
		*/
	}

	.pull-right {
		float: right;
		right: 0;
	}

	/**
	.pull-left {
		float: left;
		font-size: 9pt;
		left: 0
	}

	 */

	.putih {
		border: 0px solid #FFFFFF;
	}
</style>

<h4 style="text-align: center"><b>BILLING STATEMENT</b></h4>
<table class="table-striped text-sm small" style="table-layout:fixed;" id="table_style">
	<tr>
		<th colspan="2"><span class="pull-left">A. <b>TAGIHAN BERJALAN</b></span></th>
	</tr>
	<tr>
		<th class="text-center">Keterangan</th>
		<th class="text-center">Jumlah</th>
	</tr>
	<?php
	$no = 0;
	$tagihan_berjalan = 0;
	$total_berjalan = 0;
	$sub_tagihan = 0;
	$sc = 0;
	$sf = 0;
	$array_detail = array();
	foreach ($billing_detail as $data_detail) {
		$rincian = '';
		if ($data_detail->jenis_tagihan == '1') {
			$rincian = "Periode : " . $this->apl->tgl_format($data_detail->tanggal_awal, 2)
					. " s.d " . $this->apl->tgl_format($data_detail->tanggal_ahir, 2);
			if ($data_detail->id_tag == '1') {
				$sf = $data_detail->tagih;
				//$tagih=$data_detail->tagih
			}
			if ($data_detail->id_tag == '2') {
				$sc = $data_detail->tagih;
			}

			$sub_tagihan = $sc + $sf;
			$array_detail['Iuaran Pemeliharaan'] = array($rincian,
					'Rp.<span class="pull-right">' . $this->apl->number_format($sub_tagihan, 1) . '</span>');
		}
		if ($data_detail->jenis_tagihan == '2') {
			$query = "SELECT (MAX(meter)-SUM(`pakai`)) as meter_awal,MAX(meter) as meter_ahir,
                    SUM(`pakai`) as meter_guna
                    ,MIN(bulan) as bulan_awal,MIN(tahun) as tahun_awal
                    ,MAX(bulan) as bulan_ahir,MAX(tahun) as tahun_ahir
                  FROM utility INNER JOIN utility_rekening 
                  ON utility_rekening.id_rekening=utility.id_rekening
                  
                  WHERE id_tag='$data_detail->id_tag'
                AND id_billing='$data_detail->id_billing' AND utility.hapus=0 AND post=1";
			$meter = $this->apl->manualQuery($query)->row();
			$rincian = '<span class="pull-right">Periode : ' . $this->apl->bulan($meter->bulan_awal, 2) . ' ' . $meter->tahun_awal;
			$rincian .= " s.d " . $this->apl->bulan($meter->bulan_ahir, 2) . ' ' . $meter->tahun_ahir . '</span>';
			$rincian .= '<table class="table-borderless" style="font-size: 8pt">
                <tr>
                <td>Meter Awal</td>
             <td> : ' . $meter->meter_awal . ' M2</td>';
			$rincian .= '</tr>
                <tr><td class="putih">Meter Akhir</td>
                <td> : ' . $meter->meter_ahir . ' M2</td>';
			$rincian .= '</tr>
                <tr><td>Pemakaian</td><td> : ' . $meter->meter_guna . ' M2</td>
                </tr>
                </table>';
			$sub_tagihan = $data_detail->tagih;
			$array_detail[$data_detail->ket] = array($rincian, '<br>Rp.<span class="pull-right">' . $this->apl->number_format($sub_tagihan, 1) . '</span>');
		}
		if ($data_detail->jenis_tagihan == '3') {
			if ($data_detail->id_tag == '10') {
				$rincian = "Periode : " . $this->apl->tgl_format($data_detail->tanggal_awal, 2) . " s.d "
						. $this->apl->tgl_format($data_detail->tanggal_ahir, 2);
			}
			$sub_tagihan = $data_detail->tagih;
			$array_detail[$data_detail->ket] = array($rincian, 'Rp.<span class="pull-right">' . $this->apl->number_format($sub_tagihan, 1) . '</span>');
		}
		if ($data_detail->jenis_tagihan == '5') {
			$sub_tagihan = $data_detail->tagih;
			$array_detail[$data_detail->ket] = array($rincian, 'Rp.<span class="pull-right">' . $this->apl->number_format($sub_tagihan, 1) . '</span>');
		}

		$total_berjalan += $data_detail->tagih;
	}
	foreach ($array_detail as $a => $b) {
		echo '<tr><td>' . $a . ' <i class="pull-right"><span class="small">' . $b[0] . '</span></td>
                <td>' . $b[1] . '</td></tr>';
	}
	?>
	<tr>
		<td>Total Tagihan Berjalan</td>
		<td>Rp.<span
					class="pull-right"><?php echo $this->apl->number_format($total_berjalan, 1); ?></span>
		</td>
	</tr>
	<tr style="border: 5px solid #FFFFFF;border-bottom: 2px solid #000000;border-top: 2px solid #000000">
		<td colspan="2"><p style="padding-top: 5px"></p></td>
	</tr>
	<tr>
		<th colspan="2"><span class="pull-left">B. <b>TAGIHAN SEBELUMNYA</b></span></th>
	</tr>


	<?php
	if ($tagihan_sebelumnya->tagih > 0) {
		?>
		<tr>
			<!--
			<td>Tagihan Sebelumnya <span class="pull-right"><?php echo "Periode : "
					. $this->apl->tgl_format($tagihan_sebelumnya->tanggal_awal, 4)
					. " s.d " . $this->apl->tgl_format($tagihan_sebelumnya->tanggal_ahir, 4); ?></span></td>
			-->
			<td>Tagihan Sebelumnya <span class="pull-right"><?php echo $tagihan_sebelumnya->note; ?></span></td>
			<td>Rp.<span class="pull-right"><?php
					echo $this->apl->number_format($tagihan_sebelumnya->tagih, 1); ?>
				</span>
			</td>
		</tr>
	<?php } else {
		?>
		<tr>
			<td>Tagihan Sebelumnya</td>
			<td>Rp.<span class="pull-right"><?php
					//echo $this->apl->number_format($tagihan_sebelumnya->tagih, 1);
					echo $this->apl->number_format(0, 1);
					?></span>
			</td>
		</tr>
		<?php
	} ?>


	<?php
	if ($denda > 0) {
		?>
		<tr>
			<td>Denda</td>
			<td>Rp.<span class="pull-right">
                <?php
				//$denda = 0;
				echo $this->apl->number_format($denda, 1); ?>
            </span></td>
		</tr>
	<?php } ?>

	<tr>
		<!--
		<td>(Lebih / Kurang) Bayar</td>
		-->
		<td>Lebih Bayar</td>
		<td>Rp.<span class="pull-right"><?php echo $this->apl->number_format($over_payment * -1, 1); ?>
			</span></td>
	</tr>
	<tr style="border: 5px solid #FFFFFF;border-bottom: 2px solid #000000;border-top: 2px solid #000000">
		<td colspan="2"><p style="padding-top: 5px"></p></td>
	</tr>
	<tr>
		<th><span class="pull-left">C. <b>TOTAL TAGIHAN  ( A + B )</b></span></th>
		<td>Rp. <span class="pull-right">
                <b>
				<?php
				$tagihan_sebelumnya = $tagihan_sebelumnya->tagih;
				$total_tagihan = $tagihan_sebelumnya + $denda + $total_berjalan;
				echo $this->apl->number_format($total_tagihan, 1);
				?>
					</b>
				</span>
		</td>
	</tr>
</table>

<table>
	<tr>
		<td><h4>Terbilang : </h4></td>
		<td><h4 class="pull-left"><i>
					<?php echo ($total_tagihan >= 0) ? $this->apl->terbilang($total_tagihan) : ''; ?> Rupiah</i></h4>
		</td>
	</tr>
</table>

<span><b><small>Cara Pembayaran :</small> </b></span>
<p><b>Pembayaran Dapat di transfer ke Rek. Virtual Account berikut :</b></p>
<table class="table table-sm small table-borderless">
	<tr>
		<?php
		foreach ($va as $vir) {
			$kode_va = ($vir->tipe == '1') ? $unit->va : $unit->va2;
			?>
			<td>
				<table style="font-size: 8pt">
					<tr>
						<td>BANK</td>
						<td>: <?php echo $vir->nama; ?></td>
					</tr>
					<tr>
						<td>A/N</td>
						<td>: <?php
							$pemilik_id = $this->apl->get_nilai_pilih("bast", "id_pemilik", array('id_bast' => $billing->id_bast));
							echo $this->apl->get_nilai_pilih("pemilik", "nama", array('id_pemilik' => $pemilik_id));
							?></td>
					</tr>
					<tr>
						<td>A/V</td>
						<td>: <?php echo $vir->va . " - " . $kode_va; ?></td>
					</tr>
				</table>
			</td>
			<?php
		}
		?>
	</tr>
</table>

<ul style="nav-list">
	<li class="nav-item">Harap Mencantumkan No. Unit dan No. Invoice pada kolom berita di slip transfer dan
		mohon
		di
		konfirmasi
		kebagian keuangan <?php echo $this->bm_model->get()->nama; ?></li>
	<li class="nav-item">Untuk mengambil official Receipt harap menunjukan slip transfer asli ke bagian
		kasir,
		email
		: <?php echo $this->bm_model->get()->email_finance; ?></li>
	<li class="nav-item">Denda 3% akan dikenakan dari total tagihan untuk setiap keterlambatan terhitung
		setelah
		jatuh
		tempo
	</li>
	<li class="nav-item">Biaya pengiriman Rp. 10.000 akan dibebankan kepada masing-masing pemilik unit untuk
		setiap
		pengiriman invoice, kwitansi, dll kecuali melalui email
	</li>
	<li class="nav-item">Pemutusan utilitas akan dilakukan untuk setiap unit yang belum melakukan pembayaran
		setelah
		jatuh tempo
	</li>
	<li class="nav-item">Abaikan apabila sudah melakukan pembayaran</li>
	<li class="nav-item">Apabila ada pertanyaann atau keluhan dapat disampaikan melalui No.
		Telp <?php echo $this->bm_model->get()->nama; ?>
		di <?php echo $this->bm_model->get()->telp; ?>
		atau <?php echo $this->bm_model->get()->telp2; ?></li>
</ul>

----
<div class="row">
	<div class="col-md-4">

		<div class="float-left">
				<span>
				Regards,
				</span>
			<br>
			<span>
				Finance Easton Park

				</span>
			<br>
			<img
					src="<?php echo base_url('upload/logo/' . $this->bm_model->get()->logo); ?>"
					class="float-left" alt="" width="20%">
			<br>
			<br>
			<br>
			<br>

			+6222 778 0188
		</div>

	</div>
</div>
</div>
