<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 8/11/2016
 * Time: 10:34 AM
 */
?>

<?php
$controller = $this->uri->segment(1) . "/" . $this->uri->segment(2);
$this->dropdown_model = new Dropdown_Model();
?>
<div class="card">
	<form action="<?= site_url($controller . '/edit_act'); ?>"
		  method="post">

		<div class="card-body">
			<h5 class="text-center"><b>BILLING STATMENT</b></h5>
			<table class="table table-borderless table-striped table-sm small">
				<tr>
					<td style="width: 20%">No. Invoice</td>
					<td style="width: 30%"><?php echo $billing->invoice; ?></td>
					<td style="width: 30%">
						<input type="checkbox" class="check_no_invoice"
							   name="check_no_invoice" value="1"> Check If Different No Invoice
						<script>
							var check_baru;
							$('.no_invoice').hide();
							$('.check_no_invoice').click(function () {
								if (this.checked == true) {
									check_baru = 1;
								} else {
									check_baru = 0;
								}
								panggil_no_invoice_baru();
							})

							function panggil_no_invoice_baru() {
								if (check_baru == 1) {
									$('.no_invoice').show();
								} else {
									$('.no_invoice').hide();
								}
							}
						</script>
					</td>

					<td style="width: 20%">
						<div class="no_invoice">
							<input type="text" name="" class="form-control form-control-sm" id="no_invoice"
								   value="<?php echo $no_invoice_baru; ?>" readonly>

						</div>
					</td>
				</tr>


				<tr>
					<td>Due Date</td>
					<td><?php echo $billing->tanggal_jt; ?></td>
					<td></td>
					<td></td>
				</tr>
			</table>
			<h6 class="heading-small text-muted mb-2 mt-2">Period Bills</h6>
			<input type="checkbox" class="update_tagihan_periode ml-4"
				   name="update_tagihan_periode" value="1"> Check if the update period bills
			<script>
				var check_periode;

				$(document).ready(function () {
					$('.form-input-periode').hide();
				});

				$('.update_tagihan_periode').click(function () {
					if (this.checked == true) {
						check_periode = 1;
					} else {
						check_periode = 0;
					}
					panggil_form_periode();
				})

				function panggil_form_periode() {
					if (check_periode == 1) {
						$('.form-input-periode').show();
					} else {
						$('.form-input-periode').hide();
					}
				}
			</script>
			<div class="form-input-periode">
				<table class="table table-sm table-borderless small table-striped">
					<thead>
					<tr class="info">
						<th rowspan="2">Check</th>
						<th rowspan="2" style="width:10%">Month</th>
						<th colspan="4" class="text-center">Bill</th>
					</tr>
					<tr class="info">
						<?php
						foreach ($list->result() as $data_tagihan) {
							echo '<td>' . $data_tagihan->nama . '</td>';
						}
						?>

					</tr>
					</thead>
					<tbody>
					<?php
					$i = 0;
					$start = $month = strtotime($tanggal_tagihan_awal);
					$tahun_ahir = $this->apl->get_nilai_pilih("tagihan", "MAX(tanggal_ahir)", array('id_bast' => $billing->id_bast));
					$end = strtotime(date(date('Y', strtotime($tahun_ahir)) . '-12-01'));
					$month = strtotime("-1 month", $month);
					while ($month < $end) {
						$month = strtotime("+1 month", $month);
						echo '<tr>';
						//echo date('Y-m', $month) . " <= " . date('Y-m', strtotime($tanggal_tagihan_ahir)) . "<br>";
						$tanggal_periode = $this->apl->tanggal_periode();
						//if (date('Y-m', $month) <= date('Y-m', strtotime($tanggal_tagihan_ahir))) {
						if ($month <= strtotime($tanggal_tagihan_ahir)) {

							echo '<th><input type="checkbox"
            class="checkbox" name="tanggal_tagihan[]" value="' . date('Y-m-' . $tanggal_periode, $month) . '" 
            checked="checked"></th>';
							echo '<th class="text-center">' . date('M Y', $month) . '</th>';
						} else {
							echo '<th><input type="checkbox"
            class="checkbox" name="tanggal_tagihan[]" value="' . date('Y-m-' . $tanggal_periode, $month) . '"></th>';
							echo '<th class="text-center">' . date('M Y', $month) . '</th>';
						}
						$no = 0;
						foreach ($list->result() as $data_tagihan) {
							$no++;
							$periode = $this->apl->periode_tagihan(date('m',
									strtotime('+1 days',
										strtotime($data_tagihan->tanggal_ahir)))
							);
							$tanggal_awal = $this->apl->periode_tagihan_awal($periode);
							$tanggal_ahir = $this->apl->periode_tagihan_ahir($periode);
							$total = $this->apl->total_pertagihan($data_tagihan->tagihan, $data_tagihan->jumlah, $data_tagihan->luas, '1');
							echo '<td class="py-0">';
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

				<table class="table table-sm table-borderless small table-striped">
					<thead>
					<tr class="info">
						<th colspan="8"><span class="heading-small text-muted row">
								Administrative bills
							</span></th>
					</tr>

					<tr class="info">
						<th rowspan="2">Check</th>
						<th rowspan="2" style="width:10%">BULAN</th>
						<th colspan="4" class="text-center">Tagihan</th>
					</tr>
					<tr class="info">
						<?php
						foreach ($list_adm->result() as $data_tagihan) {
							echo '<td>' . $data_tagihan->nama . '</td>';
						}
						?>

					</tr>
					</thead>
					<tbody>
					<?php

					$start = $month = strtotime($tanggal_tagihan_awal_adm);
					$end = strtotime(date(date('Y', strtotime($tahun_ahir)) . '-12-01'));
					$month = strtotime("-1 month", $month);
					$i = 0;
					while ($month < $end) {
						$month = strtotime("+1 month", $month);
						echo '<tr>';
						$tanggal_periode = $this->apl->tanggal_periode();
						if ($month <= strtotime($tanggal_tagihan_ahir_adm)) {
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
							$periode = $this->apl->periode_tagihan(date('m',
									strtotime('+1 days',
										strtotime($data_tagihan->tanggal_ahir)))
							);
							$tanggal_awal = $this->apl->periode_tagihan_awal($periode);
							$tanggal_ahir = $this->apl->periode_tagihan_ahir($periode);
							$total = $this->apl->total_pertagihan($data_tagihan->tagihan, $data_tagihan->jumlah, $data_tagihan->luas, '1');
							echo '<td class="py-0">';
							echo '<input type="hidden" name="tagihan_id_adm[' . $i . '][' . $no . ']" class="form-control form-control-sm" value="' . $data_tagihan->id_tag . '">';
							echo '<input type="hidden" name="nama_tagihan_adm[' . $i . '][' . $no . ']" class="form-control form-control-sm" value="' . $data_tagihan->nama . '">';
							echo '<input type="text" name="total_adm[' . $i . '][' . $no . ']" class="form-control form-control-sm getNumeric" value="' . $total . '">';

							echo '</td>';

						}
						echo '</tr>';
						$i++;
					}
					?>
					</tbody>
				</table>
			</div>
			<hr>
			<h6 class="heading-small text-muted mb-2 mt-2">Tagihan Lainnya</h6>
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
					$cek = $this->apl->getSelectedData("billing_detail", array(
						'id_tag' => $data_tagihan->id_tag,
						'id_billing' => $billing->id_billing,
						'hapus' => 0,
					))->row();
					//echo ($cek) ? "checked" : "";
					?>
					<tr>
						<td>
							<input type="checkbox"
								   class="checkbox" name="tagihan_id_invoice[]"
								   value="<?php echo $data_tagihan->id_tag; ?>"
								<?php echo ($cek) ? "checked" : ""; ?>>
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

					?>
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
				<input type="checkbox" class="update_tagihan_utility"
					   name="update_tagihan_utility" value="1"> Ceklis Jika Update Tagihan Air
				<script>
					var check_utility;

					$(document).ready(function () {
						$('.form-input-utility').hide();
					});

					$('.update_tagihan_utility').click(function () {
						if (this.checked == true) {
							check_utility = 1;
						} else {
							check_utility = 0;
						}
						panggil_form_utility();
					})

					function panggil_form_utility() {
						if (check_utility == 1) {
							$('.form-input-utility').show();
						} else {
							$('.form-input-utility').hide();
						}
					}
				</script>
				<div class="form-input-utility">
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
							$cek = ($data_utility->post == 0) ? '' : 'checked';
							?>
							<tr>
								<td>
									<input type="checkbox"
										   class="checkbox" name="utility[]" value="<?php echo $data_utility->id; ?>"
										   <?= $cek; ?>>
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
				</div>
			<?php } ?>

			<!--
		<table class="table table-borderless">
			<tr>
				<th>Total Piutang</th>
				<th><span class="pull-right">Rp. <?= $this->apl->number_format($piutang, 1); ?></span></th>
			</tr>

			<tr>
				<th>Denda Yang Muncul <small>* 3% dari piutang</small></th>
				<td>
					<input type="text" name="denda"
						   class="form-control form-control-sm getNumber" value="<?= $denda; ?>">
				</td>
			</tr>

			<tr>
				<th>
					Tanggal Terbit
				</th>
				<td>
					<input type="hidden" name="id_billing" value="<?php echo $billing->id_billing; ?>">
					<input type="hidden" name="id_bast" value="<?php echo $billing->id_bast; ?>">
					<input type="date" name="tanggal_terbit" class="form-control form-control-sm"
						   value="<?php echo $billing->tanggal_terbit; ?>">
				</td>
			</tr>
		</table>
		-->

			<table class="table table-borderless table-sm small">
				<!--
				<tr>
					<th>Total Tagihan Berjalan</th>
					<th>Rp. <span class="pull-right" id="total_berjalan"></span></th>
				</tr>
				-->
				<tr>
					<th>Total Piutang</th>
					<th>
						<div class="form-group">
							<?php
							$options = array('IPL' => 'IPL', 'AIR' => 'AIR');
							echo form_dropdown('ket_piutang', $options, '', 'class="form-control"')
							?>
						</div>
						<div class="form-group">
							<div class="input-group">
								<?php
								echo $this->dropdown_model->getDropdownBulan("bulan_dari", date('m', strtotime($p_awal)),
									'class="form-control" id="input_bulan"')
								?>

								<?php
								echo $this->dropdown_model->getDropdownTahun("tahun_dari", date('Y', strtotime($p_awal)),
									'class="form-control" id="input_tahun"')
								?>
								<div class="input-group-append">
									<span class="input-group-text" id="basic-addon1">S.D</span>
								</div>

								<?php
								echo $this->dropdown_model->getDropdownBulan("bulan_ke", date('m', strtotime($p_ahir)),
									'class="form-control" id="input_bulan"')
								?>

								<?php
								echo $this->dropdown_model->getDropdownTahun("tahun_ke", date('Y', strtotime($p_ahir)),
									'class="form-control" id="input_tahun"')
								?>
							</div>
						</div>
						<div class="form-group">
							<input type="text" name="piutang" class="form-control getNumber"
								   value="<?= $piutang; ?>">
						</div>
					</th>
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
					<th>Tanggal Terbit</th>
					<td>
						<input type="hidden" name="id_billing" value="<?php echo $billing->id_billing; ?>">
						<input type="hidden" name="id_bast" value="<?php echo $billing->id_bast; ?>">
						<input type="date" name="tanggal_terbit" class="form-control form-control-sm"
							   value="<?php echo $billing->tanggal_terbit; ?>">

					</td>
				</tr>
			</table>

		</div>

		<div class="card-footer">
			<div class="btn-group pull-right">
				<button class="btn btn-success" name="simpan"><i class="fa fa-edit"></i> Update</button>
				</button>
			</div>
		</div>
	</form>
</div>
