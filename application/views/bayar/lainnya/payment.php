<?php

/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 9/22/2016
 * Time: 9:30 AM
 */
?>

<script src="<?php echo base_url('assets/getNumber.js') ?>"></script>
<script src="<?php echo base_url('assets/search-box.js') ?>"></script>
<h3>Payment Details</h3>
<input type="hidden" name="id_bayar" value="<?php echo ($submit == 'edit') ? $b->id_bayar : ''; ?>">
<input type="hidden" name="tipe" value="<?php echo ($submit == 'edit') ? $b->tipe : $tipe; ?>">


<div class="row">

	<div class="col-md-6">

		<?php
		if ($tipe == '1') {
		?>
			<div class="form-group">
				<label for="">Unit BAST</label>

				<?= $this->dropdown_model->getDropdownUnitBast(
					'id_bast',
					($submit == 'edit') ? $b->id_bast : '',
					'class="form-control satu" id="input_bast" required'
				); ?>
			</div>
		<?php } ?>
		<?php
		if ($tipe == '2') {
		?>
			<div class="form-group">
				<label for="">Vendor</label>
				<?= $this->dropdown_model->getDropdownVendor(
					'id_vendor',
					($submit == 'edit') ? $b->id_vendor : '',
					'class="form-control satu" id="input_vendor" required'
				); ?>
			</div>
		<?php } ?>
		<?php
		if ($tipe == '3') {
		?>

			<div class="form-group">
				<label for="">Name</label>
				<input type="text" name="nama" id="input_nama" class="form-control" value="<?= ($submit == 'edit') ? $b->nama : '' ?>" required>
			</div>
		<?php }
		?>


	</div>




	<div class="col-md-6">
		<div class="form-group">
			<label>Type Of Bill</label>
			<?php
			echo $this->dropdown_model->getDropdownTagLain(
				'id_tag',
				($submit == 'edit') ? $b->id_tag : '6000',
				'class="form-control"
							id="input_tagihan" required'
			);

			?>
		</div>
	</div>



</div>

<div id="detail_unit"></div>



<div class="row">
	<div class="col-md-3">
		<div class="form-group">
			<label>Price</label>
			<input type="text" id="input_harga" class="form-control  form-control-sm getNumber" value="<?= ($submit == 'edit') ? $b->harga_dasar : '0' ?>" required>
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-group">
			<label>Admin</label>
			<input type="text" id="input_admin" name="admin" class="form-control  form-control-sm getNumber" value="<?= ($submit == 'edit') ? $b->admin : '0' ?>" required>
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-group">
			<label>TAX</label>
			<input type="text" id="input_tax" name="tax" class="form-control  form-control-sm getNumber" value="<?= ($submit == 'edit') ? $b->tax : '0' ?>" required>
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-group">
			<label>Value</label>
			<input type="text" name="value" id="input_jumlah" class="form-control  form-control-sm getInt" value="<?= ($submit == 'edit') ? $b->value : '0'; ?>" required>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<label for="" class="control-label">Pay Date</label>
		<input type="date" name="tanggal" class="form-control form-control-sm" value="<?php echo ($submit == 'edit') ? $b->tanggal : date('Y-m-d'); ?>">
	</div>
	<div class="col-md-6">
		<label for="" class="control-label">Payment With</label>
		<?php
		echo $this->dropdown_model->getDropdownViaBayar(
			'id_via',
			($submit == 'edit') ? $b->id_via : '',
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
		<input type="text" name="bayar" class="form-control form-control-sm getNumber" id="total_bayar" value="<?php echo ($submit == 'edit') ? $b->jumlah : "0"; ?>" required>

	</div>
</div>


<script>
	var jumlah = 0;
	var tagihan = 0;
	var admin = 0;
	var tax = 0;

	$(document).ready(function() {
		//$('#input_harga').prop('readonly', true);
		tagihan = $('#input_tagihan').find(':selected').data('harga');
		admin = $('#input_tagihan').find(':selected').data('admin');
		tax = $('#input_tagihan').find(':selected').data('tax');
		document.getElementById('input_harga').value = tagihan.toLocaleString("en-US");
		document.getElementById('input_admin').value = admin.toLocaleString("en-US");

		/*
		jumlah = $('#input_jumlah').val();
		tagihan = $('#input_tagihan').find(':selected').data('harga');
		document.getElementById('input_harga').value = tagihan.toLocaleString("en-US");
		total();
		*/
	});

	$('#input_tagihan').change(function() {
		tagihan = $(this).find(':selected').data('harga');
		admin = $(this).find(':selected').data('admin');
		tax = $(this).find(':selected').data('tax');
		document.getElementById('input_harga').value = tagihan.toLocaleString("en-US");
		document.getElementById('input_admin').value = admin.toLocaleString("en-US");
	});


	$('#input_jumlah').keyup(function() {
		jumlah = $(this).val();
		total();
	});

	$('#input_harga').keyup(function() {
		tagihan = $(this).val().replace(",", "");
		total();
	});


	$('#total_bayar').keyup(function() {
		var total_bayar = $(this).val();

		var ppn = (tax / 100) * (total_bayar - admin);
		var jum = (parseInt(total_bayar) - admin - parseInt(ppn)) / parseInt(tagihan);
		$("#input_jumlah").val(jum);

		$("#input_tax").val(ppn);
	});


	function total() {
		jumlah = $('#input_jumlah').val();
		tagihan = $('#input_harga').val();
		var jum = parseInt(jumlah) * parseInt(tagihan);
		$("#total_bayar").val(jum.toLocaleString("en-US"));
	}





	$('[name=id_bast]').change(function() {
		show_detail_unit();
	})


	function show_detail_unit() {
		id_bast = $('[name=id_bast]').val();

		$.ajax({
			type: "GET",
			url: "<?php echo site_url('ajax/unit/detail_unit/') ?>" + id_bast,
			cache: false,
			success: function(respond) {
				$('#detail_unit').html(respond);
			}
		});

	}
</script>