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
<h3>Rincian Pembayaran</h3>
<input type="hidden" name="id_bayar" value="<?php echo ($submit == 'edit') ? $b->id_bayar : ''; ?>">


<div class="row">
	<div class="col-md-6">

		<div class="form-group">
			<label for="">Cari Unit BAST</label>
			<?= $this->dropdown_model->getDropdownUnitBast('id_bast',
				($submit == 'edit') ? $b->id_bast : '', 'class="form-control satu" required'); ?>

		</div>
	</div>

</div>


<div class="row">
	<div class="col-md-4">
		<div class="form-group">
			<label>Pilih Tagihan</label>
			<?php
			echo $this->dropdown_model->getDropdownTagLainnya('id_tag', ($submit == 'edit') ? $b->id_tag : '',
				'class="form-control  form-control-sm"
							id="input_tagihan"')
			?>
		</div>
	</div>
	<div class="col-md-4">
		<div class="form-group">
			<label>Harga</label>
			<input type="text" id="input_harga"
				   class="form-control  form-control-sm getNumber" value="0"
				   required>
		</div>
	</div>
	<div class="col-md-4">
		<div class="form-group">
			<label>Value</label>
			<input type="text" name="value" id="input_jumlah"
				   class="form-control  form-control-sm getInt"
				   value="<?= ($submit == 'edit') ? $b->value : '0'; ?>"
				   required>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<label for="" class="control-label">Tanggal Bayar</label>
		<input type="date" name="tanggal" class="form-control form-control-sm"
			   value="<?php echo ($submit == 'edit') ? $b->tanggal : date('Y-m-d'); ?>">
	</div>
	<div class="col-md-6">
		<label for="" class="control-label">Pembayaran Dengan</label>
		<?php
		echo $this->dropdown_model->getDropdownViaBayar('id_via', ($submit == 'edit') ? $b->id_via : '',
			'class="form-control form-control-sm" required');
		?>
	</div>


</div>
<hr>
<div class="row">
	<div class="col-md-6">
		<label for="" class="control-label">Keterangan</label>
		<input type="text" name="ket" class="form-control form-control-sm"
			   value="<?php echo ($submit == 'edit') ? $b->ket : ''; ?>">
	</div>
	<div class="col-md-6">
		<label for="" class="control-label">Jumlah Bayar</label>
		<input type="text" name="bayar" class="form-control form-control-sm getNumber" id="total_bayar"
			   value="<?php echo ($submit == 'edit') ? $b->jumlah : "0"; ?>" required>

	</div>
</div>


<script>
	var jumlah = 0;
	var tagihan = 0;

	$(document).ready(function () {
		$('#input_harga').prop('readonly', true);
		tagihan = $('#input_tagihan').find(':selected').data('harga');
		document.getElementById('input_harga').value = tagihan.toLocaleString("en-US");

		/*
		jumlah = $('#input_jumlah').val();
		tagihan = $('#input_tagihan').find(':selected').data('harga');
		document.getElementById('input_harga').value = tagihan.toLocaleString("en-US");
		total();
		*/
	});

	$('#input_tagihan').change(function () {
		tagihan = $(this).find(':selected').data('harga');
		document.getElementById('input_harga').value = tagihan.toLocaleString("en-US");
	});


	$('#input_jumlah').keyup(function () {
		jumlah = $(this).val();
		total();
	});


	$('#total_bayar').keyup(function () {
		var total_bayar = $(this).val();
		var jum = parseInt(total_bayar) / parseInt(tagihan);
		$("#input_jumlah").val(jum);
	});


	function total() {
		var jum = parseInt(jumlah) * parseInt(tagihan);
		$("#total_bayar").val(jum.toLocaleString("en-US"));
	}


</script>
