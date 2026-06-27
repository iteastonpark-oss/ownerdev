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
				<div class="col-md-6">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="" class="control-label">Unit</label>

								<?= $this->dropdown_model->getDropdownUnitBast('id_bast',
										($submit == 'edit') ? $tiket->id_bast : '',
										'class="form-control satu" required'); ?>

							</div>

						</div>
						<div class="col-md-6">

							<div class="form-group">
								<label for="" class="control-label">No FORM</label>
								<input type="text" name="no_form" class="form-control" value="<?php
								echo ($submit == 'edit') ? $tiket->no_form : "$no_form";
								?>" required>
							</div>
						</div>
					</div>
					<div id="detail_unit"></div>

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
								<label for="" class="control-label">Date</label>
								<input type="date" name="tanggal" class="form-control"
									   value="<?= ($submit == 'edit') ? $tiket->tanggal : date('Y-m-d'); ?>"
									   required>
							</div>
						</div>
					</div>


				</div>
				<div class="col-md-6">
					<div class="card">
						<table class="table table-striped table-sm small card-body">
							<tr>
								<td>No</td>
								<td>checkbox</td>
								<td>Repair Name</td>
								<td>Note</td>
							</tr>
							<?php
							$no = 0;
							$checked = array();
							$name = array();
							if ($submit == 'edit') {
								foreach ($detail as $d) {
									$checked[] = $d->id_pekerjaan;
									$name[$d->id_pekerjaan] = $d->nama;
								}
							}
							foreach ($pekerjaan->result() as $p) {
								$no++;
								$c = (in_array($p->id_pekerjaan, $checked)) ? 'checked' : '';
								$v = (in_array($p->id_pekerjaan, $checked)) ? $name[$p->id_pekerjaan] : '';
								?>
								<tr>
									<td><?= $no; ?></td>
									<td class="py-0">
										<input type="checkbox" name="id_pekerjaan[]"
											   class="form-control form-control-sm"
											   value="<?= $p->id_pekerjaan; ?>" <?= $c; ?>>
									</td>
									<td><?= $p->nama; ?></td>
									<td class="py-0">
										<input type="text" id="nama[<?= $p->id_pekerjaan; ?>]"
											   class="form-control form-control-sm"
											   name="nama[<?= $p->id_pekerjaan; ?>]"
											   value="<?= $v; ?>"
										>
									</td>
								</tr>
								<?php
							}
							?>

						</table>
					</div>
				</div>


			</div>

			<div class="row">
				<div class="col-md-6">
					<div class="row">
						<div class="col-md-6">

							<div class="form-group">
								<label for="" class="control-label">Contractor</label>
								<?= $this->dropdown_model->getDropdownVendorKontraktor('id_vendor',
										($submit == 'edit') ? $tiket->id_vendor : '', 'class="form-control satu" required'); ?>

							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="" class="control-label">E-mail</label>
								<input type="email" name="email" class="form-control"
									   value="<?= ($submit == 'edit') ? $tiket->email : ''; ?>">
							</div>

						</div>
					</div>


					<div class="form-group">
						<label for="" class="control-label">Note</label>
						<input type="text" name="ket" class="form-control"
							   value="<?= ($submit == 'edit') ? $tiket->ket : ''; ?>" required>
					</div>

					<div id="berkas"></div>

				</div>
				<div class="col-md-6">


					<div class="form-group">
						<label for="">Fitt Out Bill</label>
						<?php

						echo $this->dropdown_model
								->getDropdownHargaFo('id_tag',
										($submit == 'edit') ? $biaya->id_tag : '',
										'class="form-control" id="input_tagihan" required')
						?>

					</div>
					<div class="row">
						<div class="col-md-6">

							<div class="form-group">
								<label for="">Supervisi</label>
								<input type="text" name="jumlah" class="form-control getNumber"
									   value="<?= ($submit == 'edit') ? $biaya->jumlah
											   : '0'; ?>"
									   required <?= ($submit == 'edit' && $tiket->kebijakan == '1') ? '' : 'readonly'; ?>>
							</div>
						</div>
						<div class="col-md-6">

							<div class="form-group">
								<label for="">Deposit</label>
								<input type="text" name="deposit" class="form-control getNumber"
									   value="<?= ($submit == 'edit') ? $biaya->deposit : '0'; ?>"
									   required <?= ($submit == 'edit' && $tiket->kebijakan == '1') ? '' : 'readonly'; ?>>
							</div>
						</div>
					</div>


					<div class="form-group">
						<label for="">Upload policy files for price changes</label>

						<div class="custom-file">
							<input type="file" name="lainnya" class="custom-file-input" id="inputGroupFile">
							<label id="label_link_download" class="custom-file-label"
								   for="link_download"><?= ($submit == 'edit') ? $tiket->lainnya : 'File'; ?></label>


						</div>
					</div>
					<script>
						$('.custom-file-input').on('change', function () {
							let fileName = Array.from(this.files).map(x => x.name).join(', ')
							$(this).next('.custom-file-label').html(fileName);
							$('[name=jumlah]').attr('readonly', false);
							$('[name=deposit]').attr('readonly', false);

						});
					</script>


					<!--
					<div class="form-group">
						<label for="">Supervisi</label>
						<?php
					$id_tag = ($submit == 'edit') ? $biaya->id_tag : 5000;
					?>
						<input type="hidden" name="id_tag" value="5000">
						<input type="text" name="jumlah" class="form-control getNumber"
							   value="<?= ($submit == 'edit') ? $biaya->jumlah
							: $this->apl->get_nilai_pilih("db_tag", "jumlah", "id_tag=" . $id_tag); ?>"
							   required>
					</div>
					<div class="form-group">
						<label for="">Deposit</label>
						<input type="text" name="deposit" class="form-control getNumber"
							   value="<?= ($submit == 'edit') ? $biaya->deposit
							: $this->apl->get_nilai_pilih("db_tag", "deposit", "id_tag=" . $id_tag); ?>"
							   required>
					</div>
					-->


					<span class="pull-right font-weight-bold">Rp. <span id="total"></span></span>
					<br>
					<br>

				</div>
			</div>


			<div class="card-footer text-right">
				<button class="btn btn-success pull-right" name="simpan"><i class="fa fa-save"></i> Save</button>
				<!--
				<button class="btn btn-primary" name="payment"><i class="fa fa-send-o"></i> Save & Bill
				-->
			</div>


		</div>
	</form>

</div>


<script>

	$(document).ready(function () {
		$('input[name^="nama"]').attr('readonly', true);
		$('input[name^="nama"]').removeAttr('required');
		$('input[name^=id_pekerjaan]').change(function () {
			if (this.checked) {
				$('input[name="nama[' + $(this).val() + ']"]').attr('readonly', false);
				$('input[name="nama[' + $(this).val() + ']"]').attr('required', '');
			} else {
				$('input[name="nama[' + $(this).val() + ']"]').attr('readonly', true);
				$('input[name="nama[' + $(this).val() + ']"]').removeAttr('required');
			}
		});
		var elements = document.getElementsByName("id_pekerjaan[]");
		for (var i = 0; i < elements.length; i++) {
			if (elements[i].checked) {
				$('input[name="nama[' + elements[i].value + ']"]').attr('readonly', false);
				$('input[name="nama[' + elements[i].value + ']"]').attr('required', '');
			} else {
				$('input[name="nama[' + elements[i].value + ']"]').attr('readonly', true);
				$('input[name="nama[' + elements[i].value + ']"]').removeAttrs('required');

			}
		}
	});


	(function () {
		const checkboxes = document.getElementsByName("id_pekerjaan[]");
		const checkboxLength = checkboxes.length;
		const firstCheckbox = checkboxLength > 0 ? checkboxes[0] : null;

		function init() {
			if (firstCheckbox) {
				for (let i = 0; i < checkboxLength; i++) {
					checkboxes[i].addEventListener('change', checkValidity);
				}

				checkValidity();
			}
		}

		function isChecked() {
			for (let i = 0; i < checkboxLength; i++) {
				if (checkboxes[i].checked) return true;
			}

			return false;
		}

		function checkValidity() {
			const errorMessage = !isChecked() ? 'Pilih Salah Satu.' : '';
			firstCheckbox.setCustomValidity(errorMessage);
		}

		init();
	})();


	var jumlah = 0;
	var deposit = 0;
	var total = 0;
	var id_tagihan = "";


	$(document).ready(function () {
		$('#input_tagihan').change(function () {
			id_tagihan = $(this).val();
			jumlah = $(this).find(':selected').data('harga');
			deposit = $(this).find(':selected').data('deposit');


			//document.getElementById('input_deposit').value = deposit.toLocaleString("en-US");
			$('[name=jumlah]').val(jumlah.toLocaleString("en-US"));
			$('[name=deposit]').val(deposit.toLocaleString("en-US"));
			biaya();
			total_tagihan();
		});
	});


	$(document).ready(function () {
		biaya();
		total_tagihan();
		show_berkas();
		show_detail_unit();

	});

	function biaya() {
		id_tagihan = $('#input_tagihan').val();
		if (id_tagihan == "" || id_tagihan == null) {
			$('[name=jumlah]').val("0");
			$('[name=deposit]').val("0");
		}
	}

	$('[name=jumlah]').keyup(function () {
		total_tagihan();
	});
	$('[name=deposit]').keyup(function () {
		total_tagihan();
	});

	function total_tagihan() {
		jumlah = $('[name=jumlah]').val();
		deposit = $('[name=deposit]').val();
		total = parseInt(jumlah) + parseInt(deposit);
		$("#total").html(total.toLocaleString("en-US"));
	}

	function validate(form) {
		if (total == 0) {
			alert("Harap masukan Atribut Harga dan pengerjaan");
			return (false);
		}
		return (true);
	}


	$('[name=id_vendor]').change(function () {
		show_berkas();
	})


	$('[name=id_bast]').change(function () {
		show_detail_unit();
	})

	function show_berkas() {
		id_vendor = $('[name=id_vendor]').val();

		$.ajax({
			type: "POST",
			url: "<?php echo site_url($controller . '/form_berkas') ?>",
			cache: false,
			data: {
				id_vendor: id_vendor,
			},

			success: function (respond) {
				$('#berkas').html(respond);
			}
		});

	}

	function show_detail_unit() {
		id_bast = $('[name=id_bast]').val();

		$.ajax({
			type: "GET",
			url: "<?php echo site_url('ajax/unit/detail_unit/') ?>" + id_bast,
			cache: false,
			success: function (respond) {
				$('#detail_unit').html(respond);
			}
		});

	}

</script>
