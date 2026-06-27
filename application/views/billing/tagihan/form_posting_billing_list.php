<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 8/11/2016
 * Time: 10:34 AM
 */
?>
<script src="<?php echo base_url('assets/getNumber.js') ?>"></script>
<script src="<?php echo base_url('assets/search-box.js') ?>"></script>
<script src="<?php echo base_url('assets/custom.js') ?>"></script>


<div class="row">
	<div class="col-md-3">
		<?php
		echo $this->dropdown_model->getDropdownTahun('tahun', $tahun,
			'class="form-control form-control-sm" data-id="' . $id_bast . '" onchange="changeTahunTambah(this.value)"');
		?>
	</div>
</div>
<hr>
<div class="row">
	<h6 class="heading-small text-muted">Periode (SF & SC)</h6>
	<input type="hidden" name="id_bast" value="<?php echo $id_bast; ?>">
	<table class="table table-sm table-borderless small table-striped">

		<thead>
		<tr class="info">
			<th rowspan="2">Check</th>
			<th rowspan="2" style="width:10%">BULAN</th>
			<th colspan="8" class="text-center">Tagihan</th>
		</tr>
		<tr class="info">
			<?php
			foreach ($list->result() as $data_tagihan) {
				echo '<td>' . $data_tagihan->nama . '</td>';

//echo '<td>Ket.</td>';
			}
			?>
		</tr>
		</thead>
		<tbody>
		<?php
		foreach ($list->result() as $data_tagihan) {
			$periode = $this->apl->periode_tagihan(date('m',
					strtotime('+1 days',
						strtotime($data_tagihan->tanggal_ahir)))
			);

			$tanggal_awal = date('Y-m-d',
				strtotime('+1 days',
					strtotime($data_tagihan->tanggal_ahir)));
		}
		$tanggal_ahir = $this->apl->periode_tagihan_ahir($periode);
		$i = 0;
		$start = $month = strtotime($tanggal_awal);
		$end = strtotime(date($tahun . '-12-01'));
		$month = strtotime("-1 month", $month);

		while ($month < $end) {
			$month = strtotime("+1 month", $month);
			echo '<tr>';
			$tanggal_periode = $this->apl->tanggal_periode();
			if ($month < strtotime($tanggal_ahir)) {
				echo '<th><input type="checkbox"
            class="checkbox" name="tanggal_tagihan[' . $i . ']" value="' . date('Y-m-' . $tanggal_periode, $month) . '" 
            checked="checked"></th>';
				echo '<th class="text-center">' . date('M Y', $month) . '</th>';
			} else {
				echo '<th><input type="checkbox"
            class="checkbox" name="tanggal_tagihan[' . $i . ']" value="' . date('Y-m-' . $tanggal_periode, $month) . '"></th>';
				echo '<th class="text-center">' . date('M Y', $month) . '</th>';
			}
			$no = 0;
			foreach ($list->result() as $data_tagihan) {
				$no++;
				$periode = $this->apl->periode_tagihan(date('m',
						strtotime('+1 days',
							strtotime($data_tagihan->tanggal_ahir)))
				);
				$total = $this->apl->total_pertagihan($data_tagihan->tagihan, $data_tagihan->jumlah, $data_tagihan->luas, '1');

				echo '<td>';
				echo '<input type="hidden" name="tagihan_id[' . $i . '][' . $no . ']" class="form-control form-control-sm" value="' . $data_tagihan->id_tag . '">';
				echo '<input type="hidden" name="nama_tagihan[' . $i . '][' . $no . ']" class="form-control form-control-sm" value="' . $data_tagihan->nama . '">';
				echo '<input type="text" name="total[' . $i . '][' . $no . ']" class="form-control form-control-sm getNumeric" value="' . $total . '">';
				echo '</td>';
			}
			echo '</tr>';
			$i++;
		}
		?>
		</tbody>
	</table>
	<h6 class="heading-small text-muted mb-2 mt-2">Administrasi</h6>
	<table class="table table-sm table-borderless small table-striped">
		<thead>
		<tr class="info">
			<th rowspan="2">Check</th>
			<th rowspan="2" style="width:10%">BULAN</th>
			<th colspan="8" class="text-center">Tagihan</th>
		</tr>
		<tr class="info">
			<?php
			foreach ($list_adm->result() as $data_tagihan) {
				echo '<td>' . $data_tagihan->nama . '</td>';
				// echo '<td>Ket.</td>';
			}
			?>

		</tr>
		</thead>
		<tbody>
		<?php
		foreach ($list_adm->result() as $data_tagihan) {
			$periode = $this->apl->periode_tagihan(date('m',
					strtotime('+1 days',
						strtotime($data_tagihan->tanggal_ahir)))
			);
			$tanggal_awal = date('Y-m-d',
				strtotime('+1 days',
					strtotime($data_tagihan->tanggal_ahir)));
		}
		$i = 0;
		$tanggal_ahir = $this->apl->periode_tagihan_ahir($periode);
		$start = $month = strtotime($tanggal_awal);
		$month = strtotime("-1 month", $month);
		while ($month < $end) {
			$month = strtotime("+1 month", $month);
			echo '<tr>';

			$tanggal_awal = $this->apl->periode_tagihan_awal($periode);
			$tanggal_ahir = $this->apl->periode_tagihan_ahir($periode);
			$tanggal_periode = $this->apl->tanggal_periode();

			if ($month < strtotime($tanggal_ahir)) {
				echo '<th><input type="checkbox"
            class="checkbox" name="tanggal_tagihan_adm[]" value="' . date('Y-m-' . $tanggal_periode, $month) . '" 
            checked="checked"></th>';
				echo '<th class="text-center">' . date('M Y', $month) . '</th>';
			} else {
				echo '<th><input type="checkbox"
            class="checkbox" name="tanggal_tagihan_adm[]" value="' . date('Y-m-' . $tanggal_periode, $month) . '"></th>';
				echo '<th class="text-center">' . date('M Y', $month) . '</th>';
			}
			$no = 0;
			foreach ($list_adm->result() as $data_tagihan) {
				$no++;
				$total = $this->apl->total_pertagihan($data_tagihan->tagihan, $data_tagihan->jumlah, $data_tagihan->luas, '1');

				echo '<td>';
				echo '<input type="hidden" name="tagihan_id_adm[' . $i . '][' . $no . ']" class="form-control form-control-sm" value="' . $data_tagihan->id_tag . '">';
				echo '<input type="hidden" name="nama_tagihan_adm[' . $i . '][' . $no . ']" class="form-control form-control-sm" value="' . $data_tagihan->nama . '">';
				echo '<input type="text" name="total_adm[' . $i . '][' . $no . ']" class="form-control form-control-sm getNumeric" value="' . $total . '">';
				echo '</td>';

				echo '<input type="hidden" name="ket_adm[' . $i . '][' . $no . ']"
                           class="form-control form-control-sm">';

			}
			echo '</tr>';
			$i++;
		}
		?>
		</tbody>
	</table>

	<h6 class="heading-small text-muted mb-2 mt-2">Lainnya</h6>
	<table class="table table-sm table-borderless small table-striped">
		<thead>
		<tr class="info">
			<th>Check</th>
			<th>Nama Tagihan</th>
			<th>Harga</th>
		</tr>
		</thead>
		<tbody>
		<?php
		foreach ($list_adm_invoice->result() as $data_tagihan) {
			?>
			<tr>
				<td>
					<input type="checkbox"
						   class="checkbox" name="tagihan_id_invoice[]"
						   value="<?php echo $data_tagihan->id_tag; ?>"
						   checked="checked">
					<input type="hidden" name="nama_tagihan_invoice[<?php echo $data_tagihan->id_tag; ?>]"
						   value="<?php echo $data_tagihan->nama; ?>">

				</td>
				<td>
					<?php echo $data_tagihan->nama; ?>
				</td>
				<td><input type="text" name="total_invoice[<?php echo $data_tagihan->id_tag; ?>]"
						   class="form-control form-control-sm getNumeric"
						   value="<?php echo $data_tagihan->jumlah; ?>">
				</td>
			</tr>
			<?php
		}
		?>
		</tbody>
	</table>

	<hr>
	<?php
	if ($jumlah_utility != 0) {
		?>
		<h6 class="heading-small text-muted mb-2 mt-2">Utility</h6>
		<table class="table table-sm table-borderless small table-striped">
			<thead>
			<tr class="info">
				<th>Check</th>
				<th>Nama Utility</th>
				<th>Harga</th>
				<th>Tanggal Input</th>
				<th>Meter</th>
				<th>Pemakaian</th>
				<th>Total Tagihan</th>
			</tr>
			</thead>
			<tbody>
			<?php
			foreach ($list_utility->result() as $data_utility) {
				?>
				<tr>
					<td>
						<input type="checkbox"
							   class="checkbox" name="utility[]" value="<?php echo $data_utility->id; ?>"
							   checked="checked">
						<input type="hidden" name="total_utility[<?php echo $data_utility->id; ?>]"
							   value="<?php echo $data_utility->total; ?>">

					</td>
					<td>
						<?php echo $data_utility->nama; ?>
					</td>
					<td>Rp. <span
							class="pull-right"><?php echo $this->apl->number_format($data_utility->jumlah, 1); ?></span>
					</td>
					<td><?php echo $data_utility->bulan . "-" . $data_utility->tahun; ?></td>

					<td><?php echo $data_utility->meter; ?></td>
					<td><?php echo $data_utility->pakai; ?></td>
					<td>
						Rp. <span
							class="pull-right"><?php echo $this->apl->number_format($data_utility->total, 1); ?></span>
					</td>
				</tr>
				<?php

				?>
				<?php
			}
			?>
			</tbody>
		</table>
	<?php } ?>
</div>
<hr>
<table class="table table-borderless">
	<tr>
		<th>Total Piutang</th>
		<th><span class="pull-right">Rp. <?= $this->apl->number_format($piutang, 1); ?></span></th>
	</tr>
	<!--
	<tr>
		<th>Denda Yang Muncul <small>* 3% dari piutang</small></th>
		<td>
			<input type="text" name="denda"
				   class="form-control form-control-sm getNumber" value="<?= $denda; ?>">
		</td>

	</tr>
	-->
	<tr>
		<th>Tanggal Posting Tagihan</th>
		<td>
			<?php
			if (date('d', strtotime(date('d-m-Y'))) > $this->apl->tanggal_bast()) {
				$tanggal_posting = date('01-m-Y', strtotime('+1 month',
					strtotime(date('d-m-Y'))));
			} else {
				$tanggal_posting = date('01-m-Y', strtotime(date('d-m-Y')));
			}
			?>
			<input type="date" name="tanggal_cetak" class="form-control form-control-sm"
				   value="<?php echo date('Y-m-d', strtotime($tanggal_posting)); ?>">

		</td>
	</tr>
</table>


<script>

	function changeTahunTambah(tahun) {

		save_method = 'posting_data_list';
		id = "<?php echo $id_bast;?>";
		$('#btnSave').html("Posting").show();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url('billing/tagihan/form_posting_billing_list/') ?>/" + id + "?tahun=" + tahun;
		$('.modal-title').text("FORM POSTING TAGIHAN"); // Set title to Bootstrap modal title

		$.post(
			url,
			function (data) {
				$("#page-view").html(data).show();

			}
		);

	};
</script>
