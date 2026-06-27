<?php
$controller = $this->uri->segment(1);

$this->dropdown_model = new Dropdown_Model();
?>

<div class="card">
	<div class="card-header"><h4 class="card-title"><?php echo $judul; ?></h4></div>
	<form action="<?php echo site_url($controller . '/actions'); ?>"
		  method="post" enctype="multipart/form-data">

		<div class="card-body">
			<input type="hidden" name="submit" value="<?php echo $submit; ?>">

			<input type="hidden" name="id_bast" class="form-control"
				   value="<?= $this->session->id_bast; ?>">


			<input type="hidden" name="id_pemilik" id="id_pemilik" class="form-control"
				   value="<?= ($submit == 'edit') ? $pemilik->id_pemilik : ''; ?>">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label for="">Name of the Owner</label>
						<input type="text" name="nama" class="form-control"
							   value="<?= ($submit == 'edit') ? $pemilik->nama : ''; ?>" required readonly>

					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="">National ID Card</label>
						<input type="text" name="nik" class="form-control" id="nik"
							   value="<?= ($submit == 'edit') ? $pemilik->nik : ''; ?>" required>

						<div id="status"></div>

					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="">Gender</label>
						<?php
						$jenis_kelamin = ($submit == 'edit') ? $pemilik->jenis_kelamin : '';
						echo $this->dropdown_model->getDropdownGender("jenis_kelamin", $jenis_kelamin, 'class="custom-select" ')
						?>
					</div>
				</div>
			</div>
			<div class="row">

				<div class="col-md-4">
					<div class="row">
						<div class="col-md-6">

							<div class="form-group">
								<label for="">Place of birth</label>
								<?= $this->dropdown_model->getDropdownKotaNama("tempat_lahir",
										($submit == 'edit') ? $pemilik->tempat_lahir : '',
										'class="form-control  satu"');
								?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="">Date of birth</label>
								<input type="date" class="form-control" name="tanggal_lahir"
									   value="<?= ($submit == 'edit') ? $pemilik->tanggal_lahir : ''; ?>">
							</div>

						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="">Marital Status</label>
						<?= $this->dropdown_model->getDropdownStatusPerkawinan("status_perkawinan",
								($submit == 'edit') ? $pemilik->status_perkawinan : '', 'class="form-control"');
						?>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="">Religion</label>
						<?= $this->dropdown_model->getDropdownAgama("agama",
								($submit == 'edit') ? $pemilik->agama : '', 'class="form-control"');
						?>
					</div>
				</div>
			</div>


			<div class="card-title">The Address According to ID</div>
			<div class="row">


				<div class="col-md-8">
					<div class="row">

						<div class="col-md-3">
							<div class="form-group">
								<label for="alamat_ktp">Province</label>
								<?php
								$id_prov = ($submit == 'edit') ? $pemilik->id_prov : '';
								echo $this->dropdown_model->getDropdownProv("id_prov", $id_prov,
										'class="form-control satu" id="id_prov"')
								?>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="alamat_ktp">City</label>

								<?php

								$id_kab = ($submit == 'edit') ? $pemilik->id_kab : '';
								echo $this->dropdown_model->getKabupatenByProv("id_kab", $id_kab, $id_prov,
										'class="form-control satu" id="id_kab"')
								?>
							</div>
						</div>
						<div class="col-md-3">

							<div class="form-group">
								<label for="alamat_ktp">Sub-District</label>
								<?php
								$id_kec = ($submit == 'edit') ? $pemilik->id_kec : '';
								echo $this->dropdown_model->getKecamatanByKota("id_kec", $id_kec, $id_kab,
										'class="form-control satu" id="id_kec"')

								?>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="alamat_ktp">Village</label>
								<?php
								$id_desa = ($submit == 'edit') ? $pemilik->id_desa : '';
								echo $this->dropdown_model->getDesaByKec("id_desa", $id_desa, $id_kec,
										'class="form-control satu" id="id_desa"')
								?>
							</div>
						</div>
					</div>
				</div>


				<div class="col-md-4">
					<div class="form-group">
						<label for="alamat_ktp">Complete Address</label>
						<textarea class="form-control form-control-sm"
								  name="alamat"><?php echo ($submit == 'edit') ? $pemilik->alamat : ''; ?></textarea>
					</div>
				</div>
			</div>
			<!--
			<div class="card-title">Contact</div>
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label for="">Hp</label>
						<input type="text" name="hp" class="form-control"
							   value="<?= ($submit == 'edit') ? $pemilik->hp : ''; ?>">
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<label for="">Whatsapp</label>
						<input type="text" name="wa" class="form-control"
							   value="<?= ($submit == 'edit') ? $pemilik->wa : ''; ?>">
					</div>
				</div>
				<div class="col-md-4">

					<div class="form-group">
						<label for="">Tel</label>
						<input type="text" name="tlp" class="form-control"
							   value="<?= ($submit == 'edit') ? $pemilik->tlp : ''; ?>">
					</div>
				</div>
				<div class="col-md-4">

					<div class="form-group">
						<label for="">Email</label>
						<input type="email" name="email" class="form-control"
							   value="<?= ($submit == 'edit') ? $pemilik->email : ''; ?>">
					</div>
				</div>
			</div>
			-->
			<div class="card-title">Employment data</div>
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label for="">Company Name</label>
						<input type="text" name="kantor" class="form-control"
							   value="<?= ($submit == 'edit') ? $pemilik->kantor : ''; ?>">
					</div>
				</div>
				<div class="col-md-4">

					<div class="form-group">
						<label for="">Company's Address</label>
						<input type="text" name="alamat_kantor" class="form-control"
							   value="<?= ($submit == 'edit') ? $pemilik->alamat_kantor : ''; ?>">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="">Company's City</label>
						<?= $this->dropdown_model->getDropdownKotaNama("kota_kantor",
								($submit == 'edit') ? $pemilik->kota_kantor : '', 'class="form-control satu"');
						?>
					</div>
				</div>
			</div>

			<hr>

			<?php
			$url_foto = "https://bms.eprjatinangor.com/upload/berkas/" . $pemilik->foto;
			//echo $url_foto;
			if (read_file($url_foto)) {
				$cek_foto = "1";
			} else {
				$cek_foto = "0";
			}

			?>
			<br>


			<div class="form-group">
				<label for="">Photo Profil *</label>
				<!--
				<div class="custom-file">
					<input type="file" name="foto"
						   class="form-control"
						   id="selectAvatar"
						   type="file"
						   name="foto"
						   accept="image/*"
					/>
				</div>
				-->
				<div class="custom-file">
					<input type="file"
						   name="foto"
						   class="custom-file-input"
						   id="selectAvatar"

						   <?= ($cek_foto==0) ? 'required' : '' ;?>
						   accept="image/*"
					>
					<label id="label_link_ktp" class="custom-file-label"
						   for="link_download">Main Files</label>

				</div>
			</div>
			<img id="avatar" class="img-thumbnail"  src="<?= ($cek_foto==0) ? '' : $url_foto ;?>">
			<textarea id="textArea" name="file_base64" style="display: none"></textarea>

			<hr>
			<?php
			$cek_ktp = "0";
			if ($ktp) {
				$url_ktp = "https://bms.eprjatinangor.com/upload/berkas/" . $ktp->file;
				//echo $url_ktp;
				if (read_file($url_ktp)) {
					$cek_ktp = "1";
				} else {
					$cek_ktp = "0";
				}

			}
			?>
			<div class="form-group">
				<label for="">Foto KTP *</label>
				<!--
				<div class="custom-file">
					<input type="file" name="foto_ktp"
						   class="form-control"
						   id="selectAvatar_ktp"
						   type="file"
						   accept="image/*"
					/>

				</div>
				-->
				<div class="custom-file">
					<input type="file" name="foto_ktp" class="custom-file-input" id="selectAvatar_ktp"
							<?= ($cek_ktp==0) ? 'required' : '' ;?>
						   accept="image/*"
					>
					<label id="label_link_ktp" class="custom-file-label"
						   for="link_download">Main Files</label>

				</div>

			</div>
			<img id="avatar_ktp" class="img-thumbnail" src="<?= ($cek_ktp==0) ? '' : $url_ktp ;?>">

			<textarea id="textArea_ktp" name="file_base64_ktp" style="display: none"></textarea>

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
					const resize = await resizeImage(base64);
					avatar.src = resize;
					textArea.value = resize;
				};

				//input.addEventListener("change", (e) => {
				//uploadImage(e);
				input.addEventListener("change", (e) => {
					uploadImage(e);

				});


				const input_ktp = document.getElementById("selectAvatar_ktp");
				const avatar_ktp = document.getElementById("avatar_ktp");
				const textArea_ktp = document.getElementById("textArea_ktp");

				const uploadImage_ktp = async (event) => {
					const file_ktp = event.target.files[0];
					const base64_ktp = await convertBase64(file_ktp);
					const resize_ktp = await resizeImage(base64_ktp);
					avatar_ktp.src = resize_ktp;
					textArea_ktp.value = resize_ktp;
				};

				input_ktp.addEventListener("change", (e) => {
					uploadImage_ktp(e);
					//alert("Upload KTP");
				});
			</script>


			<div class="form-group">

				<label for="">Kartu Keluarga (Harus Sesuai) *</label>
				<?php
				$cek_kk = "0";
				if ($kk) {
					$url_kk = "https://bms.eprjatinangor.com/upload/berkas/" . $kk->file;
					//echo $url_ktp;
					if (read_file($url_kk)) {
						$cek_kk = "1";
					} else {
						$cek_kk = "0";
					}
					//$cek_foto = ($submit == 'edit') ? $this->upload_model->cek_link_url($url_foto) : '0';
					//echo "<br>".$cek_foto;
					if ($cek_kk == '1') {
						?>
						<a href="<?= $url_ktp; ?>"><i class="fa fa-download"></i> Kartu Keluarga</a>
						<?php
					}
				}
				?>
				<div class="custom-file">
					<div class="custom-file">
						<input type="file" name="file_kk" class="custom-file-input" id="inputGroupFile"
								<?= ($cek_kk==0) ? 'required' : '' ;?>
						>
						<label id="label_link_download" class="custom-file-label"
							   for="link_download">Main Files</label>

					</div>
				</div>
			</div>
			<script>
				$('#selectAvatar_ktp').on('change', function () {
					let fileName = Array.from(this.files).map(x => x.name).join(', ')

					var size = $(this)[0].files[0].size;
					var extension = $(this).val().replace(/^.*\./, '');

					var acceptedFiles = ["jpg", "jpeg", "png"];
					var isAcceptedImageFormat = ($.inArray(extension, acceptedFiles)) != -1;

					if (!isAcceptedImageFormat) {
						alert("Only formats are allowed : " + acceptedFiles.join(', '));
						this.value = "";
						return false;
					} else {
						if (size > 1048576) {/*1048576-1MB(You can change the size as you want)*/
							alert("File size too large! Please upload less than 1MB");
							this.value = "";
							return false;
						} else {
							$(this).next('#label_link_ktp').html(fileName);
						}
					}
				});

				$('#selectAvatar').on('change', function () {
					let fileName = Array.from(this.files).map(x => x.name).join(', ')

					var size = $(this)[0].files[0].size;
					var extension = $(this).val().replace(/^.*\./, '');

					var acceptedFiles = ["jpg", "jpeg", "png"];
					var isAcceptedImageFormat = ($.inArray(extension, acceptedFiles)) != -1;

					if (!isAcceptedImageFormat) {
						alert("Only formats are allowed : " + acceptedFiles.join(', '));
						this.value = "";
						return false;
					} else {
						if (size > 1048576) {/*1048576-1MB(You can change the size as you want)*/
							alert("File size too large! Please upload less than 1MB");
							this.value = "";
							return false;
						} else {
							$(this).next('#label_link_profil').html(fileName);
						}
					}
				});


				$('#inputGroupFile').on('change', function () {
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
						if (size > 1048576) {/*1048576-1MB(You can change the size as you want)*/
							alert("File size too large! Please upload less than 1MB");
							this.value = "";
							return false;
						} else {
							$(this).next('#label_link_download').html(fileName);
						}
					}
				});
			</script>
		</div>
		<div class="card-footer">

			<div class="pull-right">
				<button class="btn btn-success btn-block" id="save"><i class="fa fa-save"></i> Save</button>

			</div>
		</div>

	</form>

</div>


<script>

	$(function () {
		$.ajaxSetup({
			type: "POST",
			url: "<?php echo site_url('home/dropdown_lokasi') ?>",
			cache: false,
		});

		$("#id_prov").change(function () {
			var value = $(this).val();
			if (value > 0) {
				$.ajax({
					data: {modul: 'kabupaten', id: value},
					success: function (respond) {
						$("#id_kab").html(respond);
					}
				})
			}

		});
		$("#id_kab").change(function () {
			var value = $(this).val();
			if (value > 0) {
				$.ajax({
					data: {modul: 'kecamatan', id: value},
					success: function (respond) {
						// $("#id_kec").html("");
						$("#id_kec").html(respond);
					}
				})
			}

		});
		$("#id_kec").change(function () {
			var value = $(this).val();
			if (value > 0) {
				$.ajax({
					data: {modul: 'desa', id: value},
					success: function (respond) {
						$("#id_desa").html(respond);
					}
				})
			}

		});
	})


	$("body").on('keyup', "#nik", function () {
		var nik = $("#nik").val();
		var id_pemilik = $("#id_pemilik").val();
		if (nik.length >= 1) {
			$("#status").html(' <i class="fa fa-spinner fa-spin"></i> Checking availability...');
			$.ajax({
				url: "<?php echo site_url('pemilik/ajax_cek_nik');?>",
				type: "POST",
				data: "nik=" + nik + "&id_pemilik=" + id_pemilik,
				success: function (data) {
					var html = '';
					$.each(data, function (name, value) {
						var sts = value.pesan;
						if (sts == '1') {
							html += '<div class="text-success"> <b>' + nik +
								'</b> Tersedia! </div>';
							$("#save").show();

						}
						if (sts == '0') {
							html += '<div class="text-warning"> <b>' + nik +
								'</b> Sudah digunakan! </div>';
							$("#save").hide();

						}
					});
					$("#status").html(html);
				},
			});
		}
	});


</script>
