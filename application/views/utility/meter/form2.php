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

			<input type="hidden" name="id_huni" class="form-control"
				   value="<?= ($submit == 'edit') ? $huni->id_huni : ''; ?>">

			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="" class="control-label">Pilih Unit</label>

						<?= $this->dropdown_model->getDropdownUnitBast('id_bast',
							($submit == 'edit') ? $huni->id_bast : '',
							'class="form-control satu" required'); ?>

					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="" class="control-label">No FORM</label>
						<input type="text" name="no_form" class="form-control" value="<?php
						echo ($submit == 'edit') ? $huni->no_form : $no_form;
						?>" required>
					</div>
				</div>
			</div>
			<div class="row">

				<div class="col-md-6">

					<div class="form-group">
						<label for="" class="control-label">Tipe Penghuni</label>

						<?= $this->dropdown_model->getDropdownTipeHuni('tipe',
							($submit == 'edit') ? $huni->tipe : '',
							'class="form-control" required'); ?>
					</div>

				</div>
				<div class="col-md-6">

					<div class="form-group">
						<label for="" class="control-label">Nama</label>
						<input type="text" name="nama" class="form-control"
							   value="<?= ($submit == 'edit') ? $huni->nama : ''; ?>" required>
					</div>
				</div>

				<div class="col-md-6">

					<div class="form-group">
						<label for="" class="control-label">Tanggal Izin Huni</label>
						<input type="date" name="tanggal" class="form-control"
							   value="<?= ($submit == 'edit') ? $huni->tanggal_masuk : ''; ?>" required>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">

					<div class="form-group">
						<label for="" class="control-label">Kontak</label>
						<input type="text" name="hp" class="form-control"
							   value="<?= ($submit == 'edit') ? $huni->hp : ''; ?>" required>
					</div>

				</div>
				<div class="col-md-6">

					<div class="form-group">
						<label for="" class="control-label">Email</label>
						<input type="email" name="email" class="form-control"
							   value="<?= ($submit == 'edit') ? $huni->email : ''; ?>" required>
					</div>

				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="">Kontak Darurat</label>
						<input type="text" name="kontak_darurat" class="form-control"
							   value="<?= ($submit == 'edit') ? $huni->kontak_darurat : ''; ?>" required>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="">Hubungan</label>

						<input type="text" name="hubungan" class="form-control"
							   value="<?= ($submit == 'edit') ? $huni->hubungan : ''; ?>" required>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="" class="control-label">Periode Huni</label>

						<?= $this->dropdown_model->getDropdownPeriodeHuni('periode',
							($submit == 'edit') ? $huni->periode : '',
							'class="form-control" required'); ?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="">Periode</label>

						<div class="input-group">

							<input type="date" name="tanggal_awal" class="form-control"
								   value="<?= ($submit == 'edit') ? $huni->tanggal_awal : ''; ?>" required>
							<input type="date" name="tanggal_ahir" class="form-control"
								   value="<?= ($submit == 'edit') ? $huni->tanggal_ahir : ''; ?>" required>

						</div>
					</div>
				</div>
			</div>

		</div>


		<div class="card-footer">
			<div class="btn-group pull-right">
				<button class="btn btn-success" name="simpan"><i class="fa fa-save"></i> Simpan</button>
			</div>
		</div>


</div>
</form>

</div>
