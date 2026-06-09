<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 8/11/2016
 * Time: 10:34 AM
 */

$this->dropdown_model = new Dropdown_Model();
?>

<script src="<?php echo base_url('assets/search-box.js') ?>"></script>
<input type="hidden" name="id_meter" id="id_meter" value="<?= ($submit == 'edit') ? $m->id_meter : ''; ?>">
<div class="form-group">
	<label>Period</label>
	<div class="input-group">
		<?php
		echo $this->dropdown_model->getDropdownBulan("bulan", ($submit == 'edit') ? $m->bulan : $bulan,
				'class="form-control" required')
		?>

		<?php
		echo $this->dropdown_model->getDropdownTahun("tahun", ($submit == 'edit') ? $m->tahun : $tahun,
				'class="form-control" required')
		?>
	</div>
</div>

<div class="form-group">
	<label for="">Unit Meter Account</label>
	<?php
	echo $this->dropdown_model->getDropdownRekening("id_rekening", ($submit == 'edit') ? $m->id_rekening : '', $id_tag,
			'class="form-control satu" id="id_rekening" required')
	?>

</div>
<div id="detail_pelanggan"></div>

<div class="form-group">
	<label for="">Last Meter</label>
	<input type="number" name="meter" class="form-control" value="<?= ($submit == 'edit') ? $m->meter : '0'; ?>"
		   required>
</div>


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
<?php
//echo $link_file;
?>
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

	input.addEventListener("change", (e) => {
		//avatar.removeAttr('src');
		uploadImage(e);
	});


	$(document).ready(function () {
		show_detail_pelanggan();
	});
	/*
	$('[name=id_rekening]').change(function () {
		show_detail_pelanggan();
	})
	 */
	$('#id_rekening').change(function () {
		//alert("Test");
		show_detail_pelanggan();
	})


	function show_detail_pelanggan() {
		id_rekening = $('#id_rekening').val();

		$.ajax({
			type: "POST",
			url: "<?php echo site_url('ajax/unit/detail_unit_utility/') ?>" + id_rekening,
			data: {
				'id_meter': $('#id_meter').val(),
			},
			cache: false,
			success: function (respond) {
				$('#detail_pelanggan').html(respond);
			}
		});

	}
</script>
