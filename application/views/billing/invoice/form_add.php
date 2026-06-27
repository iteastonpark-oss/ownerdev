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
	<div class="card-header">
		<form action="" method="post">
			<div class="row mb--5 mt--4">
				<div class="col-md-6">
					<div class="form-group">
						<label for="" class="control-label">Choose Unit BAST</label>
						<?= $this->dropdown_model->getDropdownUnitBast(
								'id_bast',
								$id_bast,
								'class="form-control banyak" onchange="this.form.submit()"'
						); ?>
					</div>
				</div>

				<div class="col-md-3"></div>
				<div class="col-md-3">
					<div class="form-group">
						<label for="" class="control-label">Bill Year</label>
						<?php
						echo $this->dropdown_model->getDropdownTahun(
								'tahun',
								$tahun,
								'class="form-control" onchange="this.form.submit()"'
						);
						?>
					</div>
				</div>
			</div>
		</form>
	</div>
	<?php
	if ($id_bast != '') {
		?>
		<form action="<?= site_url($controller . '/add_act'); ?>" method="post">
			<input type="hidden" name="id_bast" value="<?php echo $id_bast; ?>">

			<?php
			if ($list->num_rows() > 0) {
				?>

				<table class="table table-sm table-borderless small table-striped ">

					<thead>
					<th colspan="10"><span class="heading-small text-muted row">Period Bills (SF & SC)</span>
					</th>
					<tr class="bg-neutral">
						<th>Check</th>
						<th style="width:10%">Month</th>

						<?php
						foreach ($list->result() as $data_tagihan) {
							echo '<td>' . $data_tagihan->nama . '</td>';
						}
						?>
					</tr>
					</thead>
					<tbody>
					<?php
					foreach ($list->result() as $data_tagihan) {
						$periode = $this->apl->periode_tagihan(
								date(
										'm',
										strtotime(
												'+1 days',
												strtotime($data_tagihan->tanggal_ahir)
										)
								)
						);

						$tanggal_awal = date(
								'Y-m-d',
								strtotime(
										'+1 days',
										strtotime($data_tagihan->tanggal_ahir)
								)
						);
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
							echo '<th><input type="checkbox" data-i="' . $i . '"
            class="checkbox" name="tanggal_tagihan[]" value="' . date('Y-m-' . $tanggal_periode, $month) . '" 
            checked="checked"></th>';
							echo '<th class="text-center">' . date('M Y', $month) . '</th>';
						} else {
							echo '<th><input type="checkbox" data-i="' . $i . '"
            class="checkbox" name="tanggal_tagihan[]" value="' . date('Y-m-' . $tanggal_periode, $month) . '"></th>';
							echo '<th class="text-center">' . date('M Y', $month) . '</th>';
						}
						$no = 0;
						foreach ($list->result() as $data_tagihan) {
							$no++;
							$periode = $this->apl->periode_tagihan(
									date(
											'm',
											strtotime(
													'+1 days',
													strtotime($data_tagihan->tanggal_ahir)
											)
									)
							);
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


				<?php
			}
			if ($jumlah_utility != 0) {
				?>
				<hr>
				<table class="table table-sm table-borderless small table-striped">
					<thead>
					<th colspan="7"><span class="heading-small text-muted row">Utility</span>
					</th>
					<thead>
					<thead>
					<tr class="info">
						<th>Check</th>
						<th>Utility Name</th>
						<th>Price</th>
						<th>Input Date</th>
						<th>Meter</th>
						<th>Usage</th>
						<th>Bill Total</th>
					</tr>
					</thead>
					<tbody>
					<?php
					foreach ($list_utility->result() as $data_utility) {
						?>
						<tr>
							<td>
								<input type="checkbox" class="checkbox" name="utility[]"
									   value="<?php echo $data_utility->id; ?>" checked="checked">
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
			<hr>

			<table class="table table-sm table-borderless small table-striped">
				<thead>
				<th colspan="3"><span class="heading-small text-muted row">Other</span>
				</th>
				<thead>
				<tr class="info">
					<th>Check</th>
					<th>Name Bill</th>
					<th>Price</th>
				</tr>
				</thead>
				<tbody>
				<?php
				$array_lainnya = array();

				foreach ($list_lainnya->result() as $ll) {
					$array_lainnya[$ll->id_tag] = $ll->nama;
				};

				foreach ($list_lainnya_invoice->result() as $data_tagihan) {
					$checked = "";
					if (array_key_exists($data_tagihan->id_tag, $array_lainnya)) {
						$checked = "checked";
					}
					if($data_tagihan->tagihan==10){
						$checked = "checked";
					}
					?>
					<tr>
						<td>
							<input type="checkbox" class="checkbox" name="tagihan_id_invoice[]"
								   value="<?php echo $data_tagihan->id_tag; ?>" <?= $checked; ?>>
							<input type="hidden" name="nama_tagihan_invoice[<?php echo $data_tagihan->id_tag; ?>]"
								   value="<?php echo $data_tagihan->nama; ?>">
						</td>
						<td>
							<?php echo $data_tagihan->nama; ?>
						</td>
						<td>
							<!--
							<input type="text" name="total_invoice[<?php echo $data_tagihan->id_tag; ?>]"
								   class="form-control form-control-sm getNumeric"
								   value="<?php echo $data_tagihan->jumlah; ?>">
							-->
							<input type="text" name="total_invoice[<?php echo $data_tagihan->id_tag; ?>]"
								   class="form-control form-control-sm getNumeric"
								   value="<?= $this->apl->total_pertagihan($data_tagihan->tagihan,
										   $data_tagihan->jumlah, "", $piutang); ?>">
						</td>
					</tr>
					<?php
				}
				?>
				</tbody>
			</table>
			<hr>
			<table class="table table-borderless">
				<tr>
					<th>Current Bill</th>
					<th>Rp. <span class="pull-right" id="total_berjalan"></span></th>
				</tr>
				<tr>
					<th>Statement of Receivables</th>
					<th>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<?php
									$options = array(
											'IPL' => 'IPL',
											'AIR' => 'AIR',
											'MANUAL' => 'MANUAL',
									);
									echo form_dropdown(
											'ket_piutang',
											$options,
											'',
											'class="form-control form-control-sm ket_piutang"'
									)
									?>
								</div>

							</div>
							<div class="col-md-8">
								<div class="form-group" id="ket_piutang_1">
									<div class="input-group  input-group-sm">
										<?php
										echo $this->dropdown_model->getDropdownBulan(
												"bulan_dari",
												date('m', strtotime($p_awal)),
												'class="form-control form-control-sm" id="input_bulan"'
										)
										?>
										<?php
										echo $this->dropdown_model->getDropdownTahun(
												"tahun_dari",
												date('Y', strtotime($p_awal)),
												'class="form-control form-control-sm" id="input_tahun"'
										)
										?>
										<div class="input-group-append">
											<span class="text-muted ml-2 mr-2">s.d</span>
										</div>

										<?php
										echo $this->dropdown_model->getDropdownBulan(
												"bulan_ke",
												date('m', strtotime($p_ahir)),
												'class="form-control  form-control-sm" id="input_bulan"'
										)
										?>
										<?php
										echo $this->dropdown_model->getDropdownTahun(
												"tahun_ke",
												date('Y', strtotime($p_ahir)),
												'class="form-control form-control-sm" id="input_tahun"'
										)
										?>
									</div>
								</div>
								<div class="form-group" id="ket_piutang_2">
									<input type="text" name="ket_piutang_note" class="form-control form-control-sm"
										   value="">
								</div>

							</div>
						</div>
						<div class="form-group">
							<input type="text" name="piutang" class="form-control form-control-sm  getNumber"
								   value="<?= $piutang; ?>">
						</div>
					</th>
				</tr>

				<tr>
					<th>Late Charge (3% From Statement of Receivables)</th>
					<th>
						<input type="text" name="denda" class="form-control form-control-sm getNumber"
							   value="<?= ($piutang > 0) ? (3 / 100) * $piutang : '0'; ?>">
					</th>
				</tr>


				<tr>
					<th>Bill Post Date</th>
					<td>
						<?php
						if (date('d', strtotime(date('d-m-Y'))) > $this->apl->tanggal_bast()) {
							$tanggal_posting = date('01-m-Y', strtotime(
									'+1 month',
									strtotime(date('d-m-Y'))
							));
						} else {
							$tanggal_posting = date('01-m-Y', strtotime(date('d-m-Y')));
						}
						$tanggal_posting = date('Y-04-01');
						?>
						<input type="date" name="tanggal_cetak" class="form-control form-control-sm"
							   value="<?php echo date('Y-m-d', strtotime($tanggal_posting)); ?>">

					</td>
				</tr>
			</table>

			<div class="card-footer">
				<div class="btn-group pull-right">
					<button class="btn btn-success" name="simpan"><i class="fa fa-save"></i> Save</button>
					<button class="btn btn-primary" name="print"><i class="fa fa-print"></i> Save & Print
					</button>
				</div>
			</div>
		</form>
	<?php } ?>
</div>
<script>
	function changeTahunTambah(tahun) {

		save_method = 'posting_data_list';
		id = "<?php echo $id_bast; ?>";
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

	var ket_piutang = '';

	$(document).ready(function () {
		$("#ket_piutang_1").hide();
		$("#ket_piutang_2").hide();

		ket_piutang = $('.ket_piutang').val();
		if (ket_piutang == 'MANUAL') {
			$("#ket_piutang_1").hide();
			$("#ket_piutang_2").show();

		} else {
			$("#ket_piutang_1").show();
			$("#ket_piutang_2").hide();

		}

	});
	$('.ket_piutang').change(function () {
		ket_piutang = $(this).val();
		if (ket_piutang == 'MANUAL') {
			$("#ket_piutang_1").hide();
			$("#ket_piutang_2").show();

		} else {
			$("#ket_piutang_1").show();
			$("#ket_piutang_2").hide();

		}
	});


	$(document).ready(function () {
		hitung_bayar_angsuran();
	});

	$('input[name^=tanggal_tagihan]').change(function () {
		var i = $(this).data("i");
		var elements = document.getElementsByName("tanggal_tagihan[]");
		for (var k = 0; k < elements.length; k++) {
			if (elements[i].checked) {
				if (k <= i) {
					elements[k].checked = true;
				} else {
					elements[k].checked = false;
				}
			} else {
				if (k >= i) {
					elements[k].checked = false;
				}
			}
		}
		hitung_bayar_angsuran();
	});

	/*
	$('input[name^=tanggal_tagihan_adm]').change(function () {
		var i = $(this).data("i");
		var elements = document.getElementsByName("tanggal_tagihan_adm[]");
		for (var k = 0; k < elements.length; k++) {
			if (elements[i].checked) {
				if (k <= i) {
					elements[k].checked = true;
				} else {
					elements[k].checked = false;
				}
			} else {
				if (k >= i) {
					elements[k].checked = false;
				}
			}
		}
		hitung_bayar_angsuran();
	});
	*/
	$('input[name^=tagihan_id_invoice]').change(function () {

		hitung_bayar_angsuran();
	});

	$('input[name^=utility]').change(function () {

		hitung_bayar_angsuran();
	});

	function hitung_bayar_angsuran() {
		var total = 0;
		var tanggal_tagihan = document.getElementsByName("tanggal_tagihan[]");
		for (var i = 0; i < tanggal_tagihan.length; i++) {
			if (tanggal_tagihan[i].checked) {
				$('input[name^="total[' + i + ']"]').each(function () {
					total += parseFloat($(this).val());
				});
			}
		}

		/*
		var tanggal_tagihan_adm = document.getElementsByName("tanggal_tagihan_adm[]");
		for (var i = 0; i < tanggal_tagihan_adm.length; i++) {
			if (tanggal_tagihan_adm[i].checked) {
				$('input[name^="total_adm[' + i + ']"]').each(function () {
					total += parseFloat($(this).val());
				});
			}
		}
		*/
		var tagihan_id_invoice = document.getElementsByName("tagihan_id_invoice[]");
		for (var i = 0; i < tagihan_id_invoice.length; i++) {
			if (tagihan_id_invoice[i].checked) {
				$('input[name="total_invoice[' + tagihan_id_invoice[i].value + ']"]').each(function () {
					total += parseFloat($(this).val());
				});
			}
		}

		var utility = document.getElementsByName("utility[]");
		for (var i = 0; i < utility.length; i++) {
			if (utility[i].checked) {
				$('input[name="total_utility[' + utility[i].value + ']"]').each(function () {
					total += parseFloat($(this).val());
				});
			}
		}


		$("#total_berjalan").html(total.toLocaleString("en-US"));
	}
</script>
