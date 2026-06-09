<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 8/11/2016
 * Time: 10:34 AM
 */
?>
<script src="<?php echo base_url('assets/custom.js') ?>"></script>
<div class="container-fluid">
	<input type="hidden" name="id" value="<?php echo $id; ?>">
	<?php $this->load->view('billing/invoice/print_isi'); ?>

	<?php
	if ($bast->kirim == 2) {
		echo '<div class="btn-group">';
		$email = explode(',', $bast->email_surat);
		for ($i = 0; $i < count($email); $i++) {
			echo '<a href="mailto:' . $email[$i] . '?Subject=Billing%20Statement" class="btn btn-primary"
				   target="_blank">' . $email[$i] . '</a>';
		}
		if (count($email) > 1) {
			echo '<a href="mailto:' . str_replace(",", ";", $bast->email_surat)
				. '?Subject=Billing%20Statement"
				   class="btn btn-success"
				   target="_blank"><i class="fa fa-list"></i> Email</a>';
		}
		echo '</div>';
	}
	?>
</div>

