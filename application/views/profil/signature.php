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
<script type="text/javascript"
		src="<?php echo base_url('assets/jquery.signature.package-1.2.0/js/jquery.ui.touch-punch.min.js') ?>"></script>
<link rel="stylesheet" type="text/css"
	  href="<?php echo base_url('assets/jquery.signature.package-1.2.0/css/jquery.signature.css') ?>">

<div class="card">
	<form action="<?= site_url('profil/signature_act'); ?>" method="post">
		<div class="card-body">
			<input type="hidden" name="id_karyawan" class="form-control" value="<?php echo $k->id_karyawan; ?>">

			<div class="text-center">
				<label class="" for="">Signature:</label>
				<br>
				<div id="sig"></div>
				<br>
				<textarea id="signature64" name="signed" style="display: none"></textarea>
			</div>
		</div>
		<div class="card-footer">
			<div class="row justify-content-center">
				<div class="col-md-4">

					<div class="btn-group btn-block">
						<button id="clear" class="btn btn-warning"><i class="fa fa-remove"></i> Clear Signature</button>
						<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save</button>

					</div>
				</div>
			</div>
		</div>
	</form>
</div>
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
<script type="text/javascript">
	var sig = $('#sig').signature({syncField: '#signature64', syncFormat: 'PNG'});
	$('#clear').click(function (e) {
		e.preventDefault();
		sig.signature('clear');
		$("#signature64").val('');
	});
</script>
