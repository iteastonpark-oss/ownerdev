
		<div class="card-title">
			PIC : <?= $t->pic_nama; ?>
		</div>
		<table class="table table-sm table-borderless small ">
			<thead>

			<tr class="bg-secondary">
				<th>Note</th>
				<th>Date</th>
				<th>File</th>
			</tr>
			</thead>

			<tbody>
			<?php
			foreach ($h as $his) {
				?>
				<tr>
					<td><?= $his->ket; ?></td>
					<td><?= $this->apl->tgl_format($his->tanggal, 1); ?></td>
					<td><a href="<?= base_url('upload/histori/' . $his->file); ?>"
						   target="_blank"><?= $his->file; ?></a></td>
						   <td>
						   <a href="javascript:deleteFile(<?= $his->id; ?>);"
					class="btn btn-warning btn-sm"> <i class="fa fa-trash"></i></a>

						   </td>
				</tr>
				<?php
			}
			?>
			</tbody>

		</table>


		<input type="hidden" name="id_tiket" value="<?= $t->id_tiket; ?>">
		<div class="form-group">
			<label for="">Working Description</label>
			<textarea name="ket" class="form-control" required></textarea>
			<?php
			/**
			 * if ($t->tipe == '3') {
			 * $options = array(
			 * 'Foto Sebelum' => 'Foto Sebelum',
			 * 'Foto Sesudah' => 'Foto Sesudah',
			 * );
			 * echo form_dropdown('ket', $options, '', 'class="form-control" required');
			 *
			 * ?>
			 *
			 *
			 * <?php } else { ?>
			 * <textarea name="ket" class="form-control" required></textarea>
			 * <?php }
			 *
			 * */
			?>
		</div>

		<div class="row">
			<div class="col-md-6">

				<div class="form-group">
					<label for="">Date</label>
					<input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d'); ?>" required>
				</div>
			</div>
			<div class="col-md-6">

				<div class="form-group">
					<label for="">Attachment</label>
					<div class="custom-file">
						<input type="file" name="file" class="custom-file-input" id="inputGroupFile" required>
						<label id="label_link_download" class="custom-file-label" for="link_download">Main Files</label>

					</div>

				</div>


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
			success: function(data) {

				$.post(
					"<?php echo site_url('tiket/ajax/add_histori/') ?>" + id_tiket,
					function(data) {
						$("#page-view").html(data).show();
					}
				);

			},
			error: function(xhr, desc, err) {
				console.log("error");
			}
		});
	}
</script>
