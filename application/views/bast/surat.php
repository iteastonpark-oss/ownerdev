<?php
$controller = $this->uri->segment(1);
?>

<div class="card">
	<?php $this->load->view('unit/bast/menu'); ?>
	<form action="<?php echo site_url($controller . '/bast/surat_act'); ?>" method="post"
		  enctype="multipart/form-data">
		<input type="hidden" name="id_bast" value="<?= $b->id_bast; ?>">
		<div class="card-body">
			<div class="form-group">
				<label>Mailing Address</label>
				<input type="text" class="form-control" name="alamat_surat"
					   placeholder="Mailing Address"
					   value="<?= $b->alamat_surat ?>">
			</div>
			<div class="form-group">
				<label>Mailing City</label>
				<input type="text" class="form-control" name="kota_surat"
					   placeholder="Mailing City"
					   value="<?= $b->kota_surat ?>">
			</div>
			<div class="form-group">
				<label>Shipping Method</label>
				<?php
				/*
				$options = $this->apl->get_dropdown_list(
						"select id_kirim,nama as pilih
 from `db_kirim` ",
						"");

				echo form_dropdown('kirim', $options, $b->kirim, array(
						'class' => 'form-control',
				));
				*/

				echo $this->dropdown_model->getDropdownPengiriman('kirim', $b->kirim, 'class="form-control"');
				?>
			</div>
			<div class="form-group">
				<label>E-mail</label>
				<input type="text" class="form-control" name="email_surat"
					   placeholder="E-mail"
					   value="<?= $b->email_surat ?>">
			</div>
			<div class="form-group">
				<label>Hp</label>
				<input type="text" class="form-control" name="hp_surat"
					   placeholder="Hp"
					   value="<?= $b->hp_surat ?>">
			</div>
			<div class="form-group">
				<label>Whatsapp</label>
				<input type="text" class="form-control" name="wa_surat"
					   placeholder="Whatsapp"
					   value="<?= $b->wa_surat ?>">
			</div>
			<div class="form-group">
				<label>Tel.</label>
				<input type="text" class="form-control" name="tlp_surat"
					   placeholder="Tel."
					   value="<?= $b->tlp_surat ?>">
			</div>
		</div>
		<div class="card-footer">
			<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-save"></i> Save</button>
		</div>
	</form>
</div>
<script>
	$(document).ready(function () {
		$('[name=wa_surat]').tagsInput();
		$('[name=hp_surat]').tagsInput();


	})
</script>
