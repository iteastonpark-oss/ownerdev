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

					<div class="form-group">
						<label for="" class="control-label">No FORM</label>
						<input type="text" name="no_form" class="form-control" value="<?php
						echo ($submit == 'edit') ? $tiket->no_form : $no_form;
						?>" required>
					</div>
				</div>
				<div class="col-md-6">

					<div class="form-group">
						<label for="" class="control-label">Statement By</label>
						<input type="text" name="pelapor" class="form-control"
							   value="<?= ($submit == 'edit') ? $tiket->pelapor : ''; ?>" required>
					</div>
				</div>

				<div class="col-md-6">

					<div class="form-group">
						<label for="" class="control-label">Complaint date

						</label>
						<input type="date" name="tanggal" class="form-control"
							   value="<?= ($submit == 'edit') ? $tiket->tanggal : date('Y-m-d'); ?>"
							   required>
					</div>
				</div>
				<div class="col-md-6">


					<div class="form-group">
						<label for="">Destination department</label>
						<?= $this->dropdown_model->getDropdownDepartemen("id_dep", ($submit == 'edit') ? $tiket->id_dep : ''
								, 'class="form-control" required');
						?>
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">

						<label for="" class="control-label">Note</label>
						<textarea name="ket"
								  class="form-control"><?= ($submit == 'edit') ? $tiket->ket : ''; ?></textarea>
					</div>

				</div>
				<div class="col-md-6">

					<div class="form-group">
						<label for="">Choose Equipment</label>
						<select class="pilih_asset" id="input_barang" name="id_asset">
							<?php
							if($submit=='edit'){
								?>
								<option value="<?= $detail->id_asset;?>" selected><?= $asset->nomor.' - '.$asset->nama;?></option>
							<?php
							}
							?>
							<!--
								<option value="3620194" selected="selected">select2/select2</option>
							-->
						</select>
					</div>
				</div>
			</div>
			<script>
				$('.pilih_asset').select2({
					placeholder: '-- Choose --',
					ajax: {
						url: '<?= site_url('asset/alat/ajax_cari'); ?>',
						dataType: 'json',
						delay: 250,

						data: function (params) {
							return {
								cari: params.term
							};
						},
						processResults: function (data) {
							var results = [];
							$.each(data, function (index, item) {
								results.push({
									id: item.id_asset,
									text: item.nomor + " - " + item.nama,
									id_perintah: item.id_perintah,

								});

							});
							return {
								results: results,
							};
						},

						// Additional AJAX parameters go here; see the end of this chapter for the full code of this example

						//minimumResultsForSearch: 3,
					},
					escapeMarkup: function (markup) {
						return markup;
					},
					minimumInputLength: 2,
				});

			</script>

		</div>


		<div class="card-footer">
			<button class="btn btn-success pull-right" name="simpan"><i class="fa fa-save"></i> Save
			</button>
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

	/*
	function validate() {
		var checked = false;
		var elements = document.getElementsByName("id_pekerjaan[]");
		var checkboxLength = elements.length;
		var firstCheckbox = checkboxLength > 0 ? elements[0] : null;

		for (var i = 0; i < elements.length; i++) {
			if (elements[i].checked) {
				checked = true;
			}
		}
		if (!checked) {
			firstCheckbox.setCustomValidity('Pilih Salah Satu');
		}
		return checked;
	}
	*/
</script>
