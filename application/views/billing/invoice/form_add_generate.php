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
				<div class="col-md-3"></div>
				<div class="col-md-3">
				</div>
			</div>
		</form>
	</div>
	<form action="<?= site_url($controller . '/add_act_g'); ?>"
		  method="post">
		<table class="table table-sm table-borderless small table-striped ">

			<thead>
			<th colspan="10"><span class="heading-small text-muted row">Period Bills (SF & SC)</span>
			</th>
			<tr class="bg-neutral">
				<th>Check</th>
				<th style="width:10%">Month</th>

			</tr>
			</thead>
			<tbody>
			<?php

			$i = 0;
			$periode = $this->apl->rentang_periode();
			//echo $periode;
			$tanggal_awal = $this->apl->periode_tagihan_awal($periode);
			$tanggal_ahir = $this->apl->periode_tagihan_ahir($periode);
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
				echo '</tr>';
				$i++;
			}
			?>
			</tbody>

			<thead>
			<th colspan="10"><span class="heading-small text-muted row">Utility</span>
			</th>
			<tr class="bg-neutral">
				<th>Check</th>
				<th style="width:10%">Month</th>

			</tr>
			</thead>
			<tbody>
			<?php

			$i = 0;
			$periode = ($periode == 1) ? '4' : ($periode - 1);
			$tanggal_awal = $this->apl->periode_tagihan_awal($periode);
			$tanggal_ahir = $this->apl->periode_tagihan_ahir($periode);
			$start = $month = strtotime($tanggal_awal);
			$end = strtotime(date($tahun . '-12-01'));
			$month = strtotime("-1 month", $month);

			while ($month < $end) {
				$month = strtotime("+1 month", $month);
				echo '<tr>';
				$tanggal_periode = $this->apl->tanggal_periode();
				if ($month < strtotime($tanggal_ahir)) {
					echo '<th><input type="checkbox" data-i="' . $i . '"
            class="checkbox" name="tanggal_tagihan_utility[]" value="' . date('Y-m-' . $tanggal_periode, $month) . '" 
            checked="checked"></th>';
					echo '<th class="text-center">' . date('M Y', $month) . '</th>';
				} else {
					echo '<th><input type="checkbox" data-i="' . $i . '"
            class="checkbox" name="tanggal_tagihan_utility[]" value="' . date('Y-m-' . $tanggal_periode, $month) . '"></th>';
					echo '<th class="text-center">' . date('M Y', $month) . '</th>';
				}
				echo '</tr>';
				$i++;
			}
			?>
			</tbody>
		</table>
		<hr>
		<table class="table table-borderless">
			<tr>
				<th>Bill Post Date</th>
				<td>
					<?php
					if (date('d', strtotime(date('d-m-Y'))) > $this->apl->tanggal_bast()) {
						$tanggal_posting = date('01-m-Y', strtotime('+1 month',
								strtotime(date('d-m-Y'))));
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
			<div class="form-group">
				<label for="" class="control-label">Choose Exception Units</label>
				<?= $this->dropdown_model->getDropdownUnitBast('id_bast_kecuali[]','',
						'class="form-control js-example-basic-multiple" multiple',''); ?>

				<script>
					$(document).ready(function() {
						var s2 =$('.js-example-basic-multiple').select2({
							placeholder: "Choose event type",
							tags: true
						});
						//var vals = [1, 2, 3];
						var vals = [<?php echo $unit_kecuali;?>];

						vals.forEach(function(e){
							if(!s2.find('option:contains(' + e + ')').length)
								s2.append($('<option>').text(e));
						});
						s2.val(vals).trigger("change");
					});
				</script>
			</div>
			<div class="btn-group pull-right">
				<button class="btn btn-success" name="simpan"><i class="fa fa-save"></i> Save</button>

			</div>
		</div>
	</form>
</div>
<script>


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

	/*
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
	*/
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
