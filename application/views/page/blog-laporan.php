<div class="mt-5">
	<?= $content; ?>
	<div class="meta-bottom card-footer">
		<i class="bi bi-folder"></i>

		<i class="fa fa-user"></i> <?= $visit; ?>


	</div><!-- End meta bottom -->
</div>

<?php

if($tampil=='1') {
	$web = new Web_Model();
	$ip = $_SERVER['REMOTE_ADDR'];
	$browser = $_SERVER['HTTP_USER_AGENT'];
//$url = site_url();
	$url = "";
	$jumlah_segment = $this->uri->total_segments();
	for ($i = 1; $i <= $jumlah_segment; $i++) {
		$url .= ($i == $jumlah_segment) ? $this->uri->segment($i) : $this->uri->segment($i) . "/";
	}
	$qs = $_SERVER['QUERY_STRING'];
	$insert = array(
			'ip' => $ip,
			'browser' => $browser,
			'page' => $url,
			'qs' => $qs,
			'periode' => $periode,
			'id_bast' => $this->session->id_bast,

	);
	$web->insertVisit($insert);
}
?>
