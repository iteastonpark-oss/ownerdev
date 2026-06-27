<h5 for="" class="text-muted">Detail</h5>

<div class="form-group">
	<label for="">Invoice </label>

	<div class="input-group ">
		<?= $this->dropdown_model->getDropdownBilling(
			"id_billing",
			($submit == 'edit') ? '' : $b->id_billing,
			$bast->id_bast,
			'class="form-control form-control-sm" id="input_billing"'

		); ?>
		<div class="input-group-append">
			<button type="button" name="get_data" id="get_data" class="btn btn-success btn-sm btn-block">
				<i class="fa fa-plus"></i> Get
			</button>

		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-4">
		<?php
		echo $this->dropdown_model->getDropdownTagihanInvoiceDanDeposit(
			'',
			'',
			'class="form-control form-control-sm"
							id="input_tagihan"'
		)
		?>

	</div>
	<div class="col-md-3">
		<input type="text" id="input_jumlah" class="form-control form-control-sm getNumber" value="0" required>
	</div>
	<div class="col-md-3">
		<div class="input-group">
			<?php
			echo $this->dropdown_model->getDropdownBulan(
				"bulan_dari",
				date('m'),
				'class="form-control form-control-sm" id="input_bulan"'
			)
			?>

			<?php
			echo $this->dropdown_model->getDropdownTahun(
				"tahun_dari",
				date('Y'),
				'class="form-control form-control-sm" id="input_tahun"'
			)
			?>
		</div>
	</div>
	<div class="col-md-2">
		<button type="button" name="add" id="add" class="btn btn-success btn-sm btn-block">
			<i class="fa fa-plus"></i> Add
		</button>
	</div>
</div>

<br>
<table class="table table-borderless table-sm">
	<thead class="table-active">
		<tr>
			<td>Invoice</td>
			<td>Name</td>
			<td>Total</td>
			<td>Month</td>
			<td>Year</td>
			<td>
				<a class="btn btn-sm btn-danger hapus_semua"><i class="fa fa-remove"></i> All</a>
			</td>
		</tr>
	</thead>
	<tbody id="dynamic_field">
		<?php
		$i = 0;
		foreach ($detail as $b) {
			if ($b->jumlah > 0) {
		?>
				<tr id="row2<?php echo $b->id_detail; ?>" class="dynamic-added">
					<th>
						<?= $this->apl->get_nilai_pilih(
							"billing",
							"invoice",
							array('id_billing' => $b->id_billing)
						); ?>
						<input type="hidden" name="id_billing[]" class="form-control form-control-sm" value="<?php echo $b->id_billing; ?>" required>
						</td>
					<th class="py-0">
						<?php
						echo ($b->id_tag != null && $b->id_tag != 0) ? $this->apl->get_nilai_pilih(
							"db_tag",
							"nama",
							array('id_tag' => $b->id_tag)
						) : 'UnAlokasi'; ?>
						<input type="hidden" name="id_tag[]" class="form-control form-control-sm" value="<?php echo $b->id_tag; ?>" required>

						</td>
					<td class="pull-right py-0">

						<?php //$this->apl->number_format($b->jumlah, 1);
						?>
						<input type="text" name="jumlah[]" class="form-control form-control-sm getNumber" data-tagihan="<?= $b->jumlah; ?>" value="<?php echo $b->jumlah; ?>" required>
					</td>
					<td class="py-0">
						<?= $b->bulan; ?>
						<input type="hidden" name="bulan[]" class="form-control form-control-sm" value="<?php echo $b->bulan; ?>" required>
					</td>
					<td class="py-0">
						<?= $b->tahun; ?>
						<input type="hidden" name="tahun[]" class="form-control form-control-sm" value="<?php echo $b->tahun; ?>" required>
					</td>

					<td class="py-0">
						<button type="button" name="remove" data-id="<?php echo $b->id_detail; ?>" class="btn btn-danger btn-sm btn_remove">X
						</button>
					</td>
				</tr>
		<?php
			}
		}
		?>
	</tbody>
</table>

<div class="row">
	<div class="col-md-6">
		<label for="" class="control-label">Pay Date</label>

		<input type="date" name="tanggal" class="form-control form-control-sm" value="<?php echo ($submit == 'edit') ? $bayar->tanggal : date('Y-m-d'); ?>">
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

<div class="row">
	<div class="col-md-6">
		<label for="" class="control-label">Note</label>
		<input type="text" name="ket" class="form-control form-control-sm" value="<?php echo ($submit == 'edit') ? $bayar->ket : ''; ?>">
	</div>
	<div class="col-md-6">
		<label for="" class="control-label">Total Amount Paid</label>
		<input type="text" name="bayar" class="form-control form-control-sm getNumber" id="total_bayar_baru" value="0" required readonly>
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

	/**
	$("input[name^=jumlah]").on('change', function() {
		//alert("sekarang : "+$(this).val()+" Tagihan"+$(this).data("tagihan"));
		if (parseFloat($(this).data("tagihan")) < $(this).val()) {
			alert("data pembayaran lebih besar dari tagihan");
			//document.getElementById("input_jumlah").value = $(this).data("tagihan");
			$(this).val($(this).data("tagihan"));
			//$(this).setAttribute('value', "0");
		}
	});
	*/
	function hitung_total() {
		jumlah = 0;



		$('input[name^="jumlah"]').each(function() {
			jumlah += parseFloat($(this).val());
		});

		if (jumlah == 0) {
			document.getElementById("total_bayar_baru").value = "";

		} else {
			document.getElementById("total_bayar_baru").value = jumlah.toLocaleString('en-US');

		}
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
		document.getElementById("total_bayar_baru").value = masuk.toLocaleString("en-US");
		//document.getElementById("total_bayar_baru").value = NumberFormat('en-US').format(masuk);
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
	$("#add").click(function() {
		var id_tag = $('#input_tagihan').val();
		var jumlah = $('#input_jumlah').val();
		var bulan = $('#input_bulan').val();
		var tahun = $('#input_tahun').val();
		var id_billing = $('#input_billing').val();

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
		if (id_billing == '') {
			alert("Harap masukan Billing");
			return (false);
		}

		i++;
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('bayar/invoice/alokasi_bayar_detail') ?>",
			cache: false,
			data: {
				id_tag: id_tag,
				jumlah: jumlah,
				bulan: bulan,
				tahun: tahun,
				id_billing: id_billing
			},

			success: function(respond) {

				$('#dynamic_field').append('' +
					'<tr id="row' + i + '" class="dynamic-added">' + respond +
					'<td><button type="button" name="remove" id="' + i + '" class="btn btn-danger btn-sm btn_remove">X</button></td>' +
					'</tr>');

				hitung_total();
			}
		});


	});


	$("#get_data").click(function() {
		var id_billing = $('#input_billing').val();
		if (id_billing == '') {
			alert("Harap masukan Billing");
			return (false);
		}

		i++;
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('bayar/invoice/alokasi_bayar_detail_billing') ?>",
			cache: false,
			data: {
				id_billing: id_billing
			},
			success: function(respond) {
				$('#dynamic_field').append(respond);

				hitung_total();
			}
		});


	});

	/*
	$("#input_billing").on('change', function() {
		alert("Aaa");
	});
	*/


	$(document).on('click', '.btn_remove', function() {
		var button_id = $(this).attr("id");
		$('#row' + button_id + '').remove();
		var button_id2 = $(this).data("id");
		$('#row2' + button_id2 + '').remove();

		hitung_total();
	});


	$(document).on('click', '.hapus_semua', function() {
		$('.dynamic-added').remove();
		hitung_total();
	});
</script>
