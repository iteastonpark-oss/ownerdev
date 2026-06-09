<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 9/22/2016
 * Time: 9:30 AM
 */
?>
<title><?= "Request-" . time(); ?></title>

<?php
$this->load->view('layout/header');
?>
<!--
<script type="text/javascript">
	var is_chrome = function () {
		return Boolean(window.chrome);
	}
	window.onload = function () {
		if (is_chrome) {
			/*
			 * These 2 lines are here because as usual, for other browsers,
			 * the window is a tiny 100x100 box that the user will barely see.
			 * On Chrome, it needs to be big enough for the dialogue to be read
			 * (NB, it also includes a page preview).
			*/
			window.moveTo(0, 0);
			window.resizeTo(640, 480);

			// This line causes the print dialogue to appear, as usual:
			window.print();

			/*
			 * This setTimeout isn't fired until after .print() has finished
			 * or the dialogue is closed/cancelled.
			 * It doesn't need to be a big pause, 500ms seems OK.
			*/
			setTimeout(function () {
				window.close();
			}, 500);
		} else {
			// For other browsers we can do things more briefly:
			window.print();
			window.close();
		}
	}
	/*
	window.onload = function () {
		window.print();
		window.close();
	}
	*/
</script>
-->
<style>
	.table-bordered th,
	.table-bordered td {
		border: 1px solid #000 !important;
	}
</style>
<div class="card">
	<div class="card-header">
		<?php
		$this->load->view('layout/kepala_cetak');
		?>
	</div>
	<div class="card-body">
		<?php $this->load->view('tiket/detail'); ?>
	</div>
</div>

