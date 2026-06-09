<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 8/11/2016
 * Time: 10:34 AM
 */

$controller = $this->uri->segment(1) . '/' . $this->uri->segment(2);
$this->dropdown_model = new Dropdown_Model();
?>

<div class="card">
	<div class="card-header">
		<h4 class="card-title"><?php echo $judul; ?></h4>
	</div>
	<form action="<?php echo site_url($controller . '/actions'); ?>" onsubmit="return validate(this);" method="post"
		  enctype="multipart/form-data">

		<div class="card-body">
			<input type="hidden" name="submit" value="<?php echo $submit; ?>">

			<input type="hidden" name="id_tiket" class="form-control"
				   value="<?= ($submit == 'edit') ? $tiket->id_tiket : ''; ?>">

			<div class="row">
				<input type="hidden" name="id_bast" class="form-control"
					   value="<?= ($submit == 'edit') ? $tiket->id_bast : $this->session->id_bast; ?>"
					   readonly>
				<div class="col-md-6">

					<div class="form-group">
						<label for="" class="control-label">No FORM</label>
						<input type="text" name="no_form" class="form-control" value="<?php
						echo ($submit == 'edit') ? $tiket->no_form : $no_form;
						?>" required>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">

					<div class="form-group">
						<label for="" class="control-label">Statement By</label>
						<input type="text" name="pelapor" class="form-control"
							   value="<?= ($submit == 'edit') ? $tiket->pelapor : ''; ?>" required>
					</div>
				</div>
				<div class="col-md-6">

					<div class="form-group">
						<label for="" class="control-label">Contact</label>
						<input type="text" name="kontak" class="form-control"
							   value="<?= ($submit == 'edit') ? $tiket->kontak : ''; ?>" required>
					</div>

				</div>
			</div>
			<div class="row">
				<div class="col-md-6">

					<div class="form-group">
						<label for="" class="control-label">Via Request</label>
						<?= $this->dropdown_model->getDropdownViaTiket('via',
								($submit == 'edit') ? $tiket->via : '', 'class="form-control" required'); ?>

					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="" class="control-label">Work Order Date</label>
						<input type="date" name="tanggal" class="form-control"
							   value="<?= ($submit == 'edit') ? $tiket->tanggal : date('Y-m-d'); ?>"
							   required>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">

					<div class="form-group">
						<label for="" class="control-label">E-mail</label>
						<input type="email" name="email" class="form-control"
							   value="<?= ($submit == 'edit') ? $tiket->email : ''; ?>">
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="" class="control-label">Note</label>
						<input type="text" name="ket" class="form-control"
							   value="<?= ($submit == 'edit') ? $tiket->ket : ''; ?>" required>
					</div>

				</div>
			</div>

			<div class="row">
				<div class="col-md-6">

					<div class="form-group">
						<label for="">Type Of Price List </label>
						<?php
						$options = array(
								'0' => 'FROM PRICE LIST',
								'1' => 'RAB',
						);
						echo form_dropdown('rab', $options, ($submit == 'edit') ? $tiket->rab : '0'
								, 'class="form-control" id="rab"')
						?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="">Destination department</label>
						<?php
						$options = array(
								'1' => 'ENGINEERING',
								'2' => 'OPERATIONAL',
						);
						echo form_dropdown('id_dep', $options, ($submit == 'edit') ? $tiket->id_dep : '1'
								, 'class="form-control"')
						?>
					</div>
				</div>
			</div>


			<div id="price_list">
				<label for="">Price</label>

				<div class="row">
					<div class="col-md-8">
						<?php
						echo $this->dropdown_model
								->getDropdownPekerjaanWorkOrder('id_tag',
										($submit == 'edit')
												? ($pekerjaan) ? $pekerjaan->id_tag : ''
												: ''
										,
										'class="form-control satu" id="input_tagihan"')
						?>
					</div>
					<div class="col-md-4">
						<input type="number" id="input_qty" name="qty"
							   class="form-control getInt" value="<?= ($submit == 'edit')
								? ($pekerjaan) ? $pekerjaan->qty : '1'
								: '1'; ?>"
							   required readonly>
					</div>
				</div>


				<div class="row mt-5">
					<div class="col-md-4">
						<div class="form-group">
							<label>Price</label>
							<input type="hidden" id="input_harga_hidden"
								   value="<?= ($submit == 'edit')
										   ? ($pekerjaan) ? ($pekerjaan->jumlah / $pekerjaan->qty) : '0'
										   : '0' ?>"
								   required>
							<input type="text" id="input_harga"
								   class="form-control getNumber" name="jumlah"
								   value="<?= ($submit == 'edit')
										   ? ($pekerjaan) ? $pekerjaan->jumlah : '0'
										   : '0' ?>"
								   required>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Material</label>

							<input type="hidden" id="input_material_hidden"
								   value="<?= ($submit == 'edit')
										   ? ($pekerjaan) ? ($pekerjaan->material / $pekerjaan->qty) : '0'
										   : '0' ?>"
								   required>
							<input type="text" id="input_material" name="material"
								   class="form-control getNumber"
								   value="<?= ($submit == 'edit')
										   ? ($pekerjaan) ? $pekerjaan->material : '0'
										   : '0' ?>"
								   required>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Deposit</label>
							<input type="hidden" id="input_deposit_hidden"
								   value="<?= ($submit == 'edit')
										   ? ($pekerjaan) ? ($pekerjaan->deposit / $pekerjaan->qty) : '0'
										   : '0' ?>"
								   required>

							<input type="text" id="input_deposit" name="deposit"
								   class="form-control getNumber"
								   value="<?= ($submit == 'edit')
										   ? ($pekerjaan) ? $pekerjaan->deposit : '0'
										   : '0'; ?>"
								   required>
						</div>
					</div>
				</div>
				<span class="pull-right font-weight-bold">Rp. <span id="total"></span></span>
				<br>
				<br>
			</div>
		</div>

		<div class="card-footer">
			<button class="btn btn-success pull-right" name="simpan"><i class="fa fa-save"></i> Save & Approv
			</button>
		</div>

	</form>

</div>


<script>

	var harga = 0;
	var tagihan = 0;
	var material = 0;
	var deposit = 0;
	var total = 0;
	var qty = 0;

	$(document).ready(function () {
		$('#price_list').hide();
		tampil_price_list();
	});

	$('#rab').change(function () {
		tampil_price_list();
	});

	function tampil_price_list() {
		var rab = $('#rab').val();
		if (rab == '0') {
			$('#payment').show();
			$('#price_list').show();
		}
		if (rab == '1') {
			$('#payment').hide();
			$('#price_list').hide();
		}

	}


	$(document).ready(function () {
		qty = parseInt($('#input_qty').val());
		$('#input_tagihan').change(function () {
			/*
			var kunci_harga = $(this).find(':selected').data('kunci_harga');
			if (kunci_harga == 0) {
				$('#input_jumlah').prop('readonly', false);
			} else {
				$('#input_jumlah').prop('readonly', true);
			}
			 */


			harga = $(this).find(':selected').data('harga');
			material = $(this).find(':selected').data('material');
			deposit = $(this).find(':selected').data('deposit');

			document.getElementById('input_harga_hidden').value = harga;
			document.getElementById('input_material_hidden').value = material;
			document.getElementById('input_deposit_hidden').value = deposit;

			document.getElementById('input_harga').value = (harga * qty).toLocaleString("en-US");
			document.getElementById('input_material').value = (material * qty).toLocaleString("en-US");
			document.getElementById('input_deposit').value = (deposit * qty).toLocaleString("en-US");

			total_tagihan();
		});


		total_tagihan();

	});

	$('#input_qty').keyup(function () {


		qty = parseInt($(this).val());

		harga = $('#input_harga_hidden').val();
		material = $('#input_material_hidden').val();
		deposit = $('#input_deposit_hidden').val();

		harga = $('#input_harga_hidden').val();
		material = $('#input_material_hidden').val();
		deposit = $('#input_deposit_hidden').val();

		document.getElementById('input_harga').value = (harga * qty).toLocaleString("en-US");
		document.getElementById('input_material').value = (material * qty).toLocaleString("en-US");
		document.getElementById('input_deposit').value = (deposit * qty).toLocaleString("en-US");
		total_tagihan();

	});


	$('#input_harga').keyup(function () {
		total_tagihan();
		//alert("A");
	});
	$('#input_material').keyup(function () {
		total_tagihan();
		//alert("A");
	});
	$('#input_deposit').keyup(function () {
		total_tagihan();
		//alert("A");
	});
	function total_tagihan() {
		harga = parseInt($('#input_harga').val());
		material = $('#input_material').val();
		deposit = $('#input_deposit').val();
		total = parseInt(harga) + parseInt(material) + parseInt(deposit);
		$("#total").html(total.toLocaleString("en-US"));
	}

	var i = 1;

	function validate(form) {

		var rab = $('#rab').val();
		if (rab == '1') {
			total = 1
		}

		if (total == 0) {
			alert("Harap masukan Atribut Work Order");
			return (false);
		}
		return (true);
	}

</script>
