
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
					<label for="">Image</label>
					<div class="custom-file">
						<input type="file" name="file"
							   class="form-control"
							   id="selectAvatar"
							   capture="camera"
							   accept="image/*"
						>
					</div>
				</div>


				<img id="avatar" class="img-thumbnail">
				<textarea id="textArea" name="file_base64" style="display: none"></textarea>

				<script>
					const input = document.getElementById("selectAvatar");
					const avatar = document.getElementById("avatar");
					const textArea = document.getElementById("textArea");
					const convertBase64 = (file) => {
						return new Promise((resolve, reject) => {
							const fileReader = new FileReader();
							fileReader.readAsDataURL(file);
							fileReader.onload = () => {
								resolve(fileReader.result);
							};
							fileReader.onerror = (error) => {
								reject(error);
							};
						});
					};

					const resizeImage = (base64Str, maxWidth = 400, maxHeight = 350) => {
						return new Promise((resolve) => {
							let img = new Image()
							img.src = base64Str
							img.onload = () => {
								let canvas = document.createElement('canvas')
								const MAX_WIDTH = maxWidth
								const MAX_HEIGHT = maxHeight
								let width = img.width
								let height = img.height

								if (width > height) {
									if (width > MAX_WIDTH) {
										height *= MAX_WIDTH / width
										width = MAX_WIDTH
									}
								} else {
									if (height > MAX_HEIGHT) {
										width *= MAX_HEIGHT / height
										height = MAX_HEIGHT
									}
								}
								canvas.width = width
								canvas.height = height
								let ctx = canvas.getContext('2d')
								ctx.drawImage(img, 0, 0, width, height)
								resolve(canvas.toDataURL())
							}
						})
					}

					const uploadImage = async (event) => {
						const file = event.target.files[0];
						const base64 = await convertBase64(file);
						const resize= await resizeImage(base64);
						avatar.src = resize;
						textArea.value = resize;
					};

					input.addEventListener("change", (e) => {
						uploadImage(e);
					});
				</script>
			</div>
		</div>


<script>

	$('.input-dokumen').on('change', function () {
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
			if (size > 2097152) {/*1048576-1MB(You can change the size as you want)*/
				alert("Ukuran file terlalu besar! Harap unggah kurang dari 2MB");
				this.value = "";
				return false;
			} else {
				$(this).next('.label-dokumen').html(fileName);
			}
		}
	});
</script>
