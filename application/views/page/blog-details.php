<div class="container" data-aos="fade-up">

	<div class="row mt--9 mb--8">


		<div class="card">

			<div class="post-img">
				<?php
				//echo $post->banner;
				?>

				<img src="<?= $this->upload_model->link_web($post->banner, "post"); ?>"
					alt="" class="img-fluid w-100 img-thumbnail">
			</div>

			<div class="card-header">
				<h2 class="title"><?= $post->judul; ?></h2>

				<div class="meta-top mb--3">
					<ul>
						<!--
						<li class="d-flex align-items-center"><i class="bi bi-person"></i> <a href="blog-details.html">John Doe</a></li>
						-->
						<li class="d-flex align-items-center"><i class="bi bi-clock"></i>
							<a href="#">
								<time datetime="2020-01-01">
									<h5><?= $this->apl->tgl_format($post->tanggal, 1); ?></h5>
								</time>
							</a>
						</li>
					</ul>
				</div><!-- End meta top -->
			</div>
			<div class="content mb--9 mt--8">
				<?= $post->body; ?>
			</div><!-- End post content -->

			<div class="meta-bottom card-footer">
				<i class="bi bi-folder"></i>

				<i class="fa fa-user"></i> <?= $visit; ?>


			</div><!-- End meta bottom -->

		</div><!-- End blog post -->
	</div><!-- End blog post -->


</div>


<?php


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
	'id_post' => $this->input->get('id'),
	'id_bast' => $this->session->id_bast,

);
$web->insertVisit($insert);
?>