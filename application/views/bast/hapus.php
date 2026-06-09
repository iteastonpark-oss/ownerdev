<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 8/11/2016
 * Time: 10:34 AM
 */
?>

<?php
$controller = $this->uri->segment(1) . "/" . $this->uri->segment(2);
?>
<div class="card">
	<?php $this->load->view('unit/bast/menu'); ?>

	<form action="<?= site_url($controller . '/hapus_act'); ?>"
		  method="post" enctype="multipart/form-data">
		<div class="card-body">

			<input type="hidden" name="id_bast" value="<?php echo $_GET['id']; ?>">


			<div class="form-group">
				<label for="" class="control-label">Alasan</label>
				<textarea name="alasan" class="form-control" required></textarea>
			</div>


			<div class="form-group">
				<label for="">Upload Memo</label>
				<div class="custom-file">
					<input type="file" name="file" class="custom-file-input" id="inputGroupFile"
						   required>
					<label id="label_link_download" class="custom-file-label"
						   for="link_download">Main Files</label>

				</div>
			</div>
		</div>
		<div class="card-footer">
			<button type="submit" class="btn btn-danger pull-right"><i class="fa fa-trash"></i> Delete</button>
		</div>
	</form>

</div>

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
