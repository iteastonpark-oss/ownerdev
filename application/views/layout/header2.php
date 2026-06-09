<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 4/8/2016
 * Time: 2:58 PM
 */
?>
<head>
	<title>BMS</title>
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<!-- Argon Scripts -->
	<!-- Core -->
	<script src="<?php echo base_url('assets/jquery-3.3.1.min.js') ?>"></script>


	<script src="<?php echo base_url('assets/DataTables-1.10.9/media/js/jquery.dataTables.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/DataTables-1.10.9/media/js/dataTables.bootstrap.min.js') ?>"></script>
	<script
			src="<?php echo base_url('assets/DataTables-1.10.9/extensions/Responsive/js/dataTables.responsive.js') ?>"></script>
	<script
			src="<?php echo base_url('assets/DataTables-1.10.9/extensions/Responsive/js/responsive.bootstrap.min.js') ?>"></script>
	<script
			src="<?php echo base_url('assets/DataTables-1.10.9/extensions/FixedColumns/js/dataTables.fixedColumns.min.js') ?>"></script>

	<script src="<?php echo base_url('assets/datatable.js') ?>"></script>
	<script src="<?php echo base_url('assets/getNumber/jquery.number.js') ?>"></script>
	<script src="<?php echo base_url('assets/getNumber.js') ?>"></script>

	<script src="<?php echo base_url('assets/select2-4.0.13/dist/js/select2.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/search-box.js') ?>"></script>


	<script src="<?php echo base_url('assets/Highcharts-4.2.6/js/highcharts.js') ?>"></script>
	<script src="<?php echo base_url('assets/Highcharts-4.2.6/js/modules/data.js') ?>"></script>
	<script src="<?php echo base_url('assets/Highcharts-4.2.6/js/modules/exporting.js') ?>"></script>

	<!-- Fonts -->
	<!-- Icons -->

	<link href="<?php echo base_url('assets/argon/vendor/@fortawesome/fontawesome-free/css/all.min.css'); ?>"
		  rel="stylesheet">
	<link rel="stylesheet" href="<?php echo base_url('assets/font-awesome-4.7.0/css/font-awesome.min.css'); ?>">

	<!-- Argon CSS -->
	<link type="text/css" href="<?php echo base_url('assets/argon/css/argon.css?v=1.0.0'); ?>" rel="stylesheet">

	<link rel="stylesheet"
		  href="<?php echo base_url('assets/DataTables-1.10.9/media/css/dataTables.bootstrap.min.css'); ?>">
	<link rel="stylesheet"
		  href="<?php echo base_url('assets/DataTables-1.10.9/extensions/Responsive/css/responsive.dataTables.css') ?>">
	<link rel="stylesheet"
		  href="<?php echo base_url('assets/DataTables-1.10.9/extensions/Responsive/css/responsive.bootstrap.css') ?>">
	<link rel="stylesheet"
		  href="<?php echo base_url('assets/DataTables-1.10.9/extensions/FixedColumns/css/fixedColumns.bootstrap.css') ?>">


	<link rel="stylesheet" href="<?php echo base_url('assets/select2-4.0.13/dist/css/select2.min.css'); ?>">


</head>
<?php
flush();
?>


<style>
	body#modal_form {
		overflow: visible;
	}

	/*
	.modal-full {
		min-width: 100%;
		margin: 0;
	}
	*/
</style>

<script>


	$(document).ready(function () {
		/*

	$("#btnSave").on('click', function (e) {
		//$('.modal-footer').hide();
		var valid = $('#form').validate();
		if (valid) {
			var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
			if ($(this).html() !== loadingText) {
				$('#btnSave').prop('disabled', true);
				$('#btnSave').html(loadingText).show();
			}
		}
		*/
		/*
		$('#form').validate(function () {
			//e.stopPropagation();
			// Jika gagal masih muncul
			var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
			if ($(this).html() !== loadingText) {
				$('#btnSave').prop('disabled', true);
				$('#btnSave').html(loadingText).show();
			}
		});

	});

		 */

		$('[name=tanggal_awal]').change(function () {
			var tanggal1 = $(this).val();
			var tanggal2 = $('[name=tanggal_ahir]').val();
			if (tanggal1 > tanggal2) {
				$('[name=tanggal_ahir]').val(tanggal1);
			}
		})

		$('[name=tanggal_ahir]').change(function () {
			var tanggal2 = $(this).val();
			var tanggal1 = $('[name=tanggal_awal]').val();
			if (tanggal2 < tanggal1) {
				$('[name=tanggal_awal]').val(tanggal2);
			}
		})
	})


	window.addEventListener("pageshow", function (event) {
		var historyTraversal = event.persisted ||
				(typeof window.performance != "undefined" &&
						window.performance.navigation.type === 2);
		if (historyTraversal) {
			// Handle page restore.
			reload_table();
		}
	});
</script>
