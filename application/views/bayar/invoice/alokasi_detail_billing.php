<?php
$i = 0;
foreach ($detail as $b) {
	if ($b->jumlah > 0) {
?>
		<tr id="row2<?php echo $b->id_detail; ?>" class="dynamic-added">
			<th>
				<?= $this->apl->get_nilai_pilih(
					"billing",
					"invoice",
					array('id_billing' => $b->id_billing)
				); ?>
				<input type="hidden" name="id_billing[]" class="form-control form-control-sm" value="<?php echo $b->id_billing; ?>" required>
				</td>
			<th class="py-0">
				<?php
				echo ($b->id_tag != null && $b->id_tag != 0) ? $this->apl->get_nilai_pilih(
					"db_tag",
					"nama",
					array('id_tag' => $b->id_tag)
				) : 'UnAlokasi'; ?>
				<input type="hidden" name="id_tag[]" class="form-control form-control-sm" value="<?php echo $b->id_tag; ?>" required>

				</td>
			<td class="pull-right py-0">

				<?php //$this->apl->number_format($b->jumlah, 1);
				?>
				<input type="text" name="jumlah[]" class="form-control form-control-sm getNumber" data-tagihan="<?= $b->jumlah; ?>" value="<?php echo $b->jumlah; ?>" required>
			</td>
			<td class="py-0">
				<?= $b->bulan; ?>
				<input type="hidden" name="bulan[]" class="form-control form-control-sm" value="<?php echo $b->bulan; ?>" required>
			</td>
			<td class="py-0">
				<?= $b->tahun; ?>
				<input type="hidden" name="tahun[]" class="form-control form-control-sm" value="<?php echo $b->tahun; ?>" required>
			</td>

			<td class="py-0">
				<button type="button" name="remove" data-id="<?php echo $b->id_detail; ?>" class="btn btn-danger btn-sm btn_remove">X
				</button>
			</td>
		</tr>
<?php
	}
}
?>