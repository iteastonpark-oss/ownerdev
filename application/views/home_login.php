<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="en">
<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 4/8/2016
 * Time: 2:58 PM
 */
//header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");


//header_remove('Cache-Control');

$pesan = new Pesan();
$this->pesan = new Pesan();
$tombol = new Tombol();
$this->tombol = new Tombol();
$apl = new Apl();
$this->apl = new Apl();

$dropdown_model = new Dropdown_Model();
$this->dropdown_model = new Dropdown_Model();


require_once 'layout/header.php';
require_once 'layout/notifikasi_jquery.php';
require_once 'layout/loader.php';


$sidebar = "layout/sidebar";
$navbar = "layout/navbar";
?>


<body class="bg-secondary">
<?php
if ($this->session->login) {
	if ($this->session->tipe == 'owner') {
		$this->load->view($sidebar);
		//$this->load->view('layout/bottombar');
	}
}
?>
<div class="main-content " id="panel">
	<?php
	$this->load->view($navbar);
	?>
	<!-- Page content -->
	<div class="container-fluid bg-secondary mt--6">
		<br>
		<?php
		require_once $page . '.php';
		$this->load->view('layout/footer');
		?>
	</div>


</div>
</body>


</html>


<?php
//print_r($this->session->userdata());


?>
<script>
	$(document).ready(function () {

		if (window.history && window.history.pushState) {
			$('#modal_form').on('show.bs.modal', function (e) {
				window.history.pushState('forward', null, '');
			});

			$(window).on('popstate', function () {
				$('#modal_form').modal('hide');
			});
		}


		$('#page-hapus').hide(); //
		$('#page-input').hide(); //
		$('#page-view').hide(); //
		$('#page-menu').hide(); //


	});
	$(document).on("hidden.bs.modal", function (e) {

		$('#page-hapus').hide(); //
		$('#page-input').hide(); //
		$('#page-view').hide(); //
		$('#page-menu').hide(); //
		$('#btnSave').hide();
		$(".modal-footer").show();
		$('#btnSave').prop('disabled', false);


	});


</script>

<!-- Bootstrap modal -->
<div class="modal fade bd-example-modal-lg" data-backdrop="static" id="modal_form" role="dialog">
	<div class="modal-dialog  modal-lg modal-full">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Modal title</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form" method="post" class="form-horizontal">
				<div class="modal-body">
					<div class="container-fluid">
						<div id="page-hapus"></div>
						<div id="page-input"></div>
						<div id="page-view"></div>
						<div id="page-menu"></div>
					</div>
				</div>
				<div class="modal-footer">
					<?php
					echo $this->tombol->get_simpan_js("save()", "cancel()");
					?>
				</div>
			</form>
		</div>
	</div>
</div>


