<?php
$i = 0;
foreach ($berkas as $b) { ?>
	<input type="hidden" name="id_upload[]" value="<?= $b->id_upload; ?>">
	<input type="hidden" name="nama_file[]" value="<?= $b->nama; ?>">
	<input type="hidden" name="folder[]" value="<?= $b->folder; ?>">
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label for="">Upload <?= $b->nama; ?></label>
				<!--
				<?= ($submit == 'edit') ? '' : 'required'; ?>
				-->
				<div class="custom-file">
					<input type="file" name="foto[]" class="custom-file-input" id="inputGroupFile"
							<?= ($submit == 'edit') ? '' : ''; ?> >
					<label id="label_link_download" class="custom-file-label"
						   for="link_download"><?php echo ($submit == 'edit') ? $b->file : 'Main Files'; ?></label>

				</div>
			</div>
		</div>
		<div class="col-md-6">
			<?php

			if ($submit == 'edit') {

				$cek_foto = $this->upload_model->cek_link($b->file, $b->folder);
				if ($cek_foto == '1') {
					echo $this->upload_model->tampil_gambar($b->file, "", $b->folder);
				}
			}
			?>
		</div>
	</div>
	<br>
	<?php
	$i++;
} ?>

<script>
	$('.custom-file-input').on('change', function () {
		let fileName = Array.from(this.files).map(x => x.name).join(', ')

		var size = $(this)[0].files[0].size;
		var extension = $(this).val().replace(/^.*\./, '');

		var acceptedFiles = ["jpg", "jpeg", "png", "pdf"];
		var isAcceptedImageFormat = ($.inArray(extension, acceptedFiles)) != -1;

		if (!isAcceptedImageFormat) {
			alert("Only formats are allowed : " + acceptedFiles.join(', '));
			this.value = "";
			return false;
		} else {
			if (size > 5242880) {/*1048576-1MB(You can change the size as you want)*/
				alert("File size too large! Please upload less than 5MB");
				this.value = "";
				return false;
			} else {
				$(this).next('.custom-file-label').html(fileName);
			}
		}
	});
</script>