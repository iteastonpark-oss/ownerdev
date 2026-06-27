<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 9/22/2016
 * Time: 9:30 AM
 */
?>
<title><?= "WO-" . time(); ?></title>

<?php
$this->load->view('layout/header');
?>
<div class="container-fluid">
	<div class="card">
		<div class="card-header">
			<?php $this->load->view('layout/kepala_cetak'); ?>
		</div>
		<div class="card-body">
			<?php $this->load->view('tiket/detail'); ?>
		</div>
	</div>
</div>
