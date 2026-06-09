<?php

/**
 * Created by PhpStorm.
 * User: 2kangs
 * Date: 6/25/2019
 * Time: 8:24 PM
 */
?>
<td>
	<?= $this->apl->get_nilai_pilih(
		"billing",
		"invoice",
		array('id_billing' => $id_billing)
	); ?>
	<input type="hidden" name="id_billing[]" class="form-control form-control-sm" value="<?php echo $id_billing; ?>" required>

</td>
<td>
	<?= $this->apl->get_nilai_pilih("db_tag", "nama", array('id_tag' => $id_tag)); ?>
	<input type="hidden" name="id_tag[]" class="form-control form-control-sm" value="<?php echo $id_tag; ?>" required>
</td>
<td class="pull-right">
	<?= $this->apl->number_format($jumlah, 1); ?>
	<input type="hidden" name="jumlah[]" class="form-control form-control-sm" value="<?php echo $jumlah; ?>" required>
</td>

<td>
	<?= $bulan; ?>
	<input type="hidden" name="bulan[]" class="form-control form-control-sm" value="<?php echo $bulan; ?>" required>
</td>
<td>
	<?= $tahun; ?>
	<input type="hidden" name="tahun[]" class="form-control form-control-sm" value="<?php echo $tahun; ?>" required>
</td>