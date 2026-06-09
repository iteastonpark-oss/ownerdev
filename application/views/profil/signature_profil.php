<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 4/20/2017
 * Time: 9:27 AM
 */
?>

<script src="<?php echo base_url('assets/jquery-ui.min.js') ?>"></script>
<style>
	.kbw-signature {
		width: 400px;
		height: 200px;
	}

	#sig canvas {
		width: 100% !important;
		height: auto;
	}
</style>


<script type="text/javascript"
		src="<?php echo base_url('assets/jquery.signature.package-1.2.0/js/jquery.signature.min.js') ?>"></script>
<link rel="stylesheet" type="text/css"
	  href="<?php echo base_url('assets/jquery.signature.package-1.2.0/css/jquery.signature.css') ?>">

<input type="hidden" name="id_karyawan" class="form-control" value="<?php echo $k->id_karyawan; ?> ">

<div class="text-center">
	<label class="" for="">Signature:</label>
	<br>
	<div id="sig"></div>
	<br>
	<button id="clear" class="btn btn-sm btn-warning">Clear Signature</button>
	<textarea id="signature64" name="signed" style="display: none"></textarea>
</div>

<script type="text/javascript">

	var sig = $('#sig').signature({syncField: '#signature64', syncFormat: 'PNG'});
	$('#clear').click(function (e) {
		e.preventDefault();
		sig.signature('clear');
		$("#signature64").val('');
	});
</script>
