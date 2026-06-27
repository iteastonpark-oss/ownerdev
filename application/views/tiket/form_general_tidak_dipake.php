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
								<label for="" class="control-label">Complaint Date</label>
								<input type="date" name="tanggal" class="form-control"
									   value="<?= ($submit == 'edit') ? $tiket->tanggal : date('Y-m-d'); ?>"
									   required>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label for="" class="control-label">Email</label>
						<input type="email" name="email" class="form-control"
							   value="<?= ($submit == 'edit') ? $tiket->email : ''; ?>">
					</div>

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

					<div class="form-group">
						<label for="" class="control-label">Note</label>
						<input type="text" name="ket" class="form-control"
							   value="<?= ($submit == 'edit') ? $tiket->ket : ''; ?>" required>
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
							//print_r($checked);

							foreach ($pekerjaan->result() as $p) {
								$no++;
								$c = (in_array($p->id_pekerjaan, $checked)) ? 'checked' : '';
								$v = (in_array($p->id_pekerjaan, $checked)) ? $name[$p->id_pekerjaan] : '';
								?>
								<tr>
									<td><?= $no; ?></td>
									<td>
										<input type="checkbox" name="id_pekerjaan[]"
											   class="form-control form-control-sm"
											   value="<?= $p->id_pekerjaan; ?>" <?= $c; ?>>
									</td>
									<td><?= $p->nama; ?></td>
									<td>
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

			<!--

			<div class="card-footer">
				<div class="btn-group pull-right">
					<button class="btn btn-success" name="simpan"><i class="fa fa-save"></i> Save</button>
					<button class="btn btn-primary" name="proses"><i class="fa fa-send-o"></i> Save & Proses</button>
				</div>
			</div>
			-->

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
