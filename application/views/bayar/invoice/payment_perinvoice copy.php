<?php

/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 9/22/2016
 * Time: 9:30 AM
 */
?>

<script src="<?php echo base_url('assets/getNumber.js') ?>"></script>
<h3>Rincian Pembayaran</h3>
<input type="hidden" name="id_bast" value="<?= $bast->id_bast; ?>">
<input type="hidden" name="id_billing" value="<?= $b->id_billing; ?>">

<table class="table table-sm table-borderless">
	<tr>
		<td>Unit</td>
		<td><?php echo $unit->kode; ?></td>
	</tr>
	<tr>
		<td>Bill Total</td>
		<td><?php echo $b->invoice; ?></td>
	</tr>

	<tr>
		<td>Bill</td>
		<td><?php
			echo $this->apl->number_format($piutang->piutang, 1); 
			?></td>
	</tr>
</table>
<table class="table table-bordered small table-sm" id="boxes">
	<thead class="bg-secondary">
		<tr>
			<td><input type="checkbox" id="pilihsemua" checked="checked"></td>
			<th>Name</th>
			<th>Total</th>
			<th>Period</th>
		</tr>
	</thead>
	<tbody >
		<?php
		$no = 0;
		foreach ($detail as $d) {
		?>
			<tr>
				<td><input type="checkbox" name="id_detail[]" id="check_bayar[]" 
				data-id="<?= $no; ?>" value="<?= $d->id_detail; ?>" 
				checked="checked" onclick="hitung_total()"></td>
				<th>
					<?= $d->ket; ?>
					<input type="hidden" name="id_tag[]" value="<?= $d->id_tag; ?>">
					</td>
				<th class="py-0">
					<input type="text" 
					data-tagihan="<?= $d->jumlah; ?>"
					name="jumlah[<?= $no; ?>]" id="input_jumlah" class="form-control form-control-sm getNumber" value="<?= $d->jumlah; ?>" required>

					</td>
				<th>
					<input type="hidden" name="bulan[]" value="<?= $d->bulan; ?>">
					<input type="hidden" name="tahun[]" value="<?= $d->tahun; ?>">
					<?= $d->bulan . " " . $d->tahun; ?>
					</td>
			</tr>
		<?php
			$no++;
		}
		?>
	</tbody>
	<tbody id="dynamic_field"></tbody>
	
</table>

<div class="row">
	<div class="col-md-4">
		<?php
		echo $this->dropdown_model->getDropdownTagihanInvoiceDanDeposit('', '',
			'class="form-control form-control-sm"
							id="input_tagihan"')
		?>

	</div>
	<div class="col-md-3">
		<input type="text" id="input_jumlah"
			   class="form-control form-control-sm getNumber" value="0"
			   required>
	</div>
	<div class="col-md-3">
		<div class="input-group">
			<?php
			echo $this->dropdown_model->getDropdownBulan("bulan_dari", date('m'),
				'class="form-control form-control-sm" id="input_bulan"')
			?>

			<?php
			echo $this->dropdown_model->getDropdownTahun("tahun_dari", date('Y'),
				'class="form-control form-control-sm" id="input_tahun"')
			?>
		</div>
	</div>
	<div class="col-md-2">
		<button type="button" name="add" id="add"
				class="btn btn-success btn-sm btn-block">
			<i class="fa fa-plus"></i> Add
		</button>
	</div>
</div>

<div class="row">
	<div class="col-md-6">
		<label for="" class="control-label">Pay Date</label>
		<input type="date" name="tanggal" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>">
	</div>
	<div class="col-md-6">
		<label for="" class="control-label">Payment With</label>
		<?php
		echo $this->dropdown_model->getDropdownViaBayar(
			'id_via',
			($submit == 'edit') ? $bayar->id_via : '',
			'class="form-control form-control-sm" required'
		);
		?>
	</div>


</div>
<hr>
<div class="row">
	<div class="col-md-6">
		<label for="" class="control-label">Note</label>
		<input type="text" name="ket" class="form-control form-control-sm" value="<?php echo ($submit == 'edit') ? $b->ket : ''; ?>">
	</div>
	<div class="col-md-6">
		<label for="" class="control-label">Amount Paid</label>
		<input type="text" name="bayar" class="form-control form-control-sm getNumber total_bayar" id="total_bayar_baru" value="" required readonly>

	</div>
</div>

<script>
	$(document).ready(function() {
		//$("#boxes input[type='checkbox']").click(function() {
		hitung_total();
		//});
	});


	$("input[name^=jumlah]").on('keydown paste input', function() {
		hitung_total();
	});

	$("input[name^=jumlah]").on('change', function() {
		//alert("sekarang : "+$(this).val()+" Tagihan"+$(this).data("tagihan"));
		if(parseFloat($(this).data("tagihan")) < $(this).val()){
			alert("data pembayaran lebih besar dari tagihan");	
			//document.getElementById("input_jumlah").value = $(this).data("tagihan");
			$(this).val($(this).data("tagihan"));
			//$(this).setAttribute('value', "0");
		}
	});

	function hitung_total() {
		var total = 0;
		var id_detail = document.getElementsByName("id_detail[]");
		for (var i = 0; i < id_detail.length; i++) {
			if (id_detail[i].checked) {
				$('input[name^="jumlah[' + i + ']"]').each(function() {
					total += parseFloat($(this).val());
				});
			}
		}
		var masuk = total;
		document.getElementById("total_bayar_baru").value = masuk.toLocaleString();
	}



	function hitung_total_dulu() {
		var total = 0;
		/**
		$("#boxes input[type='checkbox']:checked").each(function() {
			total += parseInt($(this).data("exval"), 10);
		});
		
		var tanggal_tagihan = document.getElementsByName("tanggal_tagihan[]");
		for (var i = 0; i < tanggal_tagihan.length; i++) {
			if (tanggal_tagihan[i].checked) {
				$('input[name^="total[' + i + ']"]').each(function() {
					total += parseFloat($(this).val());
				});
			}
		}
		*/
		$("#boxes input[type='checkbox']:checked").each(function() {
			//total += parseInt($(this).data("exval"), 10);
			total += parseFloat($('input[name^="jumlah[' + $(this).data("id") + ']"]').val());
		});
		var masuk = total;
		document.getElementById("total_bayar_baru").value = masuk.toLocaleString();
	}

	//  $(document).ready(function () {
	if ($("#boxes input[type='checkbox']").length == $("#boxes input[type='checkbox']:checked").length) {
		$("#pilihsemua").prop('checked', true);
	} else {
		$("#pilihsemua").prop('checked', false);
	}

	$("#pilihsemua").click(function() { // Jika Checkbox Pilih Semua di ceklis maka semua sub checkbox akan diceklis juga
		$("#boxes input[type='checkbox']").prop('checked', this.checked);
		hitung_total();
	});

	$("#boxes input[type='checkbox']").click(function() {
		if ($("#boxes input[type='checkbox']").length == $("#boxes input[type='checkbox']:checked").length) {
			$("#pilihsemua").prop('checked', true);
		} else {
			$("#pilihsemua").prop('checked', false);
		}
	});
	//    });




	var i = 1;
	$("#add").click(function () {
		var id_tag = $('#input_tagihan').val();
		var jumlah = $('#input_jumlah').val();
		var bulan = $('#input_bulan').val();
		var tahun = $('#input_tahun').val();

		if (id_tag == '') {
			alert("Harap masukan Jenis Tagihan");
			return (false);
		}
		if (jumlah == 0) {
			alert("Harap masukan harga");
			return (false);
		}
		if (bulan == '') {
			alert("Harap masukan Bulan");
			return (false);
		}
		if (tahun == '') {
			alert("Harap masukan Tahun");
			return (false);
		}

		i++;
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('bayar/invoice/payment_alokasi_bayar_detail') ?>",
			cache: false,
			data: {id_tag: id_tag, jumlah: jumlah, bulan: bulan, tahun: tahun},
			success: function (respond) {
				$('#dynamic_field').append('' +
					'<tr id="row' + i + '" class="dynamic-added">' + respond +
					'<td><button type="button" name="remove" id="' + i + '" class="btn btn-danger btn-sm btn_remove">X</button></td>' +
					'</tr>');

				hitung_total();
			}
		});


	});


	$(document).on('click', '.btn_remove', function () {
		var button_id = $(this).attr("id");
		$('#row' + button_id + '').remove();
		var button_id2 = $(this).data("id");
		$('#row2' + button_id2 + '').remove();

		hitung_total();
	});

</script>