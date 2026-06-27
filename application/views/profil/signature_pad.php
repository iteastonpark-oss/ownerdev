<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 4/20/2017
 * Time: 9:27 AM
 */
?>
<meta name="viewport"
	  content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">

<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">

<link rel="stylesheet"
	  href="<?php echo base_url('assets/signature_pad-3.0.0-beta.4/package/docs/css/signature-pad.css'); ?>">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/signature_pad-3.0.0-beta.4/package/docs/css/ie9.css'); ?>">
<![endif]-->


<div class="justify-content-center">
	<form action="<?= site_url('profil/signature_act'); ?>" method="post">
		<input type="hidden" name="id_karyawan" class="form-control" value="<?php echo $k->id_karyawan; ?>">

		<div id="signature-pad" class="signature-pad">
			<div class="signature-pad--body">
				<canvas id="sig"></canvas>

			</div>
			<div class="signature-pad--footer">
				<div class="description">Sign above</div>
				<textarea id="signature64" class="form-control" name="signed" style="display: none"></textarea>
				<div class="signature-pad--actions">
					<div>
						<button type="button" class="btn btn-warning pull-left" id="clear">Clear</button>
						<!--
						<button type="button" class="button" data-action="change-color">Change color</button>
						<button type="button" class="button" data-action="undo">Undo</button>
						-->
					</div>
					<div>
						<button type="submit" class="btn btn-primary pull-right" id="save">Save as PNG</button>
					</div>
				</div>
			</div>
		</div>
	</form>

</div>


<script src="<?php echo base_url('assets/signature_pad-3.0.0-beta.4/package/docs/js/signature_pad.umd.js'); ?>"></script>
<script src="<?php echo base_url('assets/signature_pad-3.0.0-beta.4/package/docs/js/app.js'); ?>"></script>
<script>

	//var saveButton = document.getElementById('save');
	var cancelButton = document.getElementById('clear');

	/*
	saveButton.addEventListener('click', function (event) {
		var data = signaturePad.toDataURL('image/png');
		$("#signature64").val(data);
// Send data to server instead...
		//window.open(data);
	});
	*/
	cancelButton.addEventListener('click', function (event) {
		signaturePad.clear();
		$("#signature64").val("");
	});


	//Event if Drawn
	var canvas = document.getElementById("sig");
	$("#sig").click(function () {
		var data = signaturePad.toDataURL('image/png');
		//alert(data);
		$("#signature64").val(data);

	});
	canvas.addEventListener("touchend", function (e) {
		var data = signaturePad.toDataURL('image/png');
		//alert(data);
		$("#signature64").val(data);
	}, false);
</script>
