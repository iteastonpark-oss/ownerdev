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
				<div class="col-md-4">
					<div class="form-group">
						<label for="" class="control-label">Unit</label>

						<?= $this->dropdown_model->getDropdownUnitBast('id_bast',
								($submit == 'edit') ? $tiket->id_bast : '',
								'class="form-control satu" required'); ?>

					</div>
				</div>
				<div class="col-md-4">

					<div class="form-group">
						<label for="" class="control-label">No FORM</label>
						<input type="text" name="no_form" class="form-control" value="<?php
						echo ($submit == 'edit') ? $tiket->no_form : $no_form;
						?>" required>
					</div>
				</div>
				<div class="col-md-4">

					<div class="form-group">
						<label for="" class="control-label">Statement By</label>
						<input type="text" name="pelapor" class="form-control"
							   value="<?= ($submit == 'edit') ? $tiket->pelapor : ''; ?>" required>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">

					<div class="form-group">
						<label for="" class="control-label">Contact</label>
						<input type="text" name="kontak" class="form-control"
							   value="<?= ($submit == 'edit') ? $tiket->kontak : ''; ?>" required>
					</div>

				</div>
				<div class="col-md-4">

					<div class="form-group">
						<label for="" class="control-label">Via Request</label>
						<?= $this->dropdown_model->getDropdownViaTiket('via',
								($submit == 'edit') ? $tiket->via : '', 'class="form-control" required'); ?>

					</div>
				</div>
				<div class="col-md-4">

					<div class="form-group">
						<label for="" class="control-label">Date Request</label>
						<input type="date" name="tanggal" class="form-control"
							   value="<?= ($submit == 'edit') ? $tiket->tanggal : date('Y-m-d'); ?>"
							   required>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-4">

					<div class="form-group">
						<label for="" class="control-label">Email</label>
						<input type="email" name="email" class="form-control"
							   value="<?= ($submit == 'edit') ? $tiket->email : ''; ?>">
					</div>
				</div>
				<div class="col-md-8">
					<div class="row">

						<div class="col-md-4">
							<div class="form-group">
								<label for="" class="control-label">Reporter status</label>
								<?php
								echo $this->dropdown_model->getDropdownStatusPemohon("status_pemohon",
										($submit == 'edit') ? $tiket->status_pemohon : ''
										, 'class="form-control" required')
								?>
							</div>
						</div>
						<div class="col-md-4">

							<div class="form-group">
								<label for="" class="control-label">Submission Statement</label>
								<?php
								echo $this->dropdown_model->getDropdownKetPengajuan("ket", ($submit == 'edit') ? $tiket->ket : ''
										, 'class="form-control" required')
								?>
							</div>
						</div>
						<div class="col-md-4">

							<div class="form-group">
								<label for="" class="control-label">Lost/damaged card</label>
								<input type="text" name="kartu_hilang" class="form-control"
									   value="<?= ($submit == 'edit') ? $tiket->kartu_hilang : ''; ?>">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">

				<div class="col-md-6">

					<div class="form-group">
						<label for="" class="control-label">Note</label>
						<textarea name="lainnya" class="form-control"><?= ($submit == 'edit') ? $tiket->lainnya : ''; ?></textarea>
					</div>
				</div>
			</div>
			<div id="berkas"></div>
			<hr>
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label for="">Type Of Card</label>
						<?php
						echo $this->dropdown_model->getDropdownKartu('id_tag', ($submit == 'edit') ? $card->id_tag : '',
								'class="form-control satu" id="input_tagihan"')
						?>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="">Price</label>

						<input type="text" id="input_harga" name="jumlah"
							   class="form-control getNumber"
							   value="<?= ($submit == 'edit') ? $card->harga_satuan : '0'; ?>"
							   required>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="">Qty</label>

						<input type="number" id="input_qty" name="qty"
							   class="form-control" value="<?= ($submit == 'edit') ? $card->qty : '1'; ?>"
							   required>
					</div>
				</div>
			</div>

			<span class="pull-right font-weight-bold">Rp. <span id="total"></span></span>

			<br>
			<br>
		</div>


		<div class="card-footer">
			<button class="btn btn-success float-right" name="simpan"><i class="fa fa-save"></i> Save & Approv
			</button>
		</div>
		<!--

		<div class="card-footer">
			<div class="btn-group pull-right">
				<button class="btn btn-success" name="simpan"><i class="fa fa-save"></i> Save</button>
				<button class="btn btn-primary" name="payment"><i class="fa fa-send-o"></i> Save & Bill
				</button>
			</div>
		</div>
		-->
	</form>

</div>
<script>

	var harga = 0;
	var total = 0;
	var qty = 0;

	$(document).ready(function () {
		total_tagihan();

	});
	$('#input_tagihan').change(function () {
		harga = $(this).find(':selected').data('harga');

		var kunci_harga = $(this).find(':selected').data('kunci_harga');
		if (kunci_harga == 0) {
			$('#input_harga').prop('readonly', false);
		} else {
			$('#input_harga').prop('readonly', true);
		}
		document.getElementById('input_harga').value = harga.toLocaleString("en-US");
		total_tagihan();
	});

	$('#input_qty').keyup(function () {
		total_tagihan();
	});

	function total_tagihan() {

		qty = $('#input_qty').val();
		harga = $('#input_harga').val();

		total = parseInt(harga) * parseInt(qty);
		$("#total").html(total.toLocaleString("en-US"));
	}

	function validate(form) {
		if (total == 0) {
			alert("Harap masukan Atribut Access Card");
			return (false);
		}
		return (true);
	}


	var submit = "<?= $submit;?>";
	var ket = "";
	var status_pemohon = "";

	$(document).ready(function () {
		show_berkas();
	});

	$('[name=ket]').change(function () {
		show_berkas();
	})

	$('[name=status_pemohon]').change(function () {
		show_berkas();
	})


	function show_berkas() {
		ket = $('[name=ket]').val();
		status_pemohon = $('[name=status_pemohon]').val();

		$.ajax({
			type: "POST",
			url: "<?php echo site_url($controller . '/form_berkas/' . $this->input->get('id')) ?>",
			cache: false,
			data: {
				ket: ket,
				status_pemohon: status_pemohon,
				submit: submit,
			},

			success: function (respond) {
				$('#berkas').html(respond);
			}
		});

	}
</script>
