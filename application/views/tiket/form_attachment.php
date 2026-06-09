<input type="hidden" name="id_tiket" id="id_tiket" value="<?= $t->id_tiket; ?>">

<div class="form-group">
	<label for="" class="control-label">File Name</label>
	<?php
	echo $this->dropdown_model
			->getDropdownUploadByNama('id_upload', ''
					, 'class="form-control" required', '');
	?>
</div>

<div class="form-group">
	<label for="">Nomor/Name</label>
	<input type="text" name="nomor" class="form-control" value="" required>
</div>

<div class="form-group">
	<label for="">Attachment</label>
	<div class="custom-file">
		<input type="file" name="file" class="custom-file-input" id="inputGroupFile" required>
		<label id="label_link_download" class="custom-file-label" for="link_download">Main Files</label>

	</div>

</div>

<script>
	$('.custom-file-input').on('change', function () {
		let fileName = Array.from(this.files).map(x => x.name).join(', ')

		var size = $(this)[0].files[0].size;
		var extension = $(this).val().replace(/^.*\./, '');

		var acceptedFiles = ["docx", "doc", "pdf", "xls", "xlsx", "jpeg", "jpg", "png"];
		var isAcceptedImageFormat = ($.inArray(extension, acceptedFiles)) != -1;

		if (!isAcceptedImageFormat) {
			alert("Hanya format yang diizinkan : " + acceptedFiles.join(', '));
			this.value = "";
			return false;
		} else {
			if (size > 2097152) {
				/*1048576-1MB(You can change the size as you want)*/
				alert("Ukuran file terlalu besar! Harap unggah kurang dari 2MB");
				this.value = "";
				return false;
			} else {
				$(this).next('.custom-file-label').html(fileName);
			}
		}
	});

	function deleteFile(id) {
		id_tiket = $('#id_tiket').val();
		$.ajax({
			url: '<?= site_url('tiket/ajax/ajax_delete_file/'); ?>' + id,
			type: 'GET',
			dataType: "JSON",
			processData: false,
			contentType: false,
			success: function (data) {

				$.post(
					"<?php echo site_url('tiket/ajax/add_attachment/') ?>" + id_tiket,
					function (data) {
						$("#page-view").html(data).show();
					}
				);

			},
			error: function (xhr, desc, err) {
				console.log("error");
			}
		});
	}
</script>
