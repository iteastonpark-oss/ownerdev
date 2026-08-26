<style>
	/* Konten CMS (WYSIWYG lama) kadang pakai <tt>/<code> cuma buat trik indentasi
	   spasi, bukan untuk tampilkan kode. Plugin typography Tailwind (prose)
	   otomatis kasih gaya monospace/kotak ke tag itu -- bentrok dgn heading/teks
	   normal di sekitarnya. Netralkan supaya tampil sebagai teks biasa. */
	.content tt, .content code, .content kbd {
		font-family: inherit;
		font-size: inherit;
		font-weight: inherit;
		background: none;
		border: none;
		padding: 0;
		color: inherit;
	}

	/* Argon CSS set warna teks default GLOBAL (body{color:#525f7f}, abu-kebiruan)
	   yang ikut kepakai di semua <p>/<li>/heading di sini, padahal di editor CMS
	   (CKEditor) teksnya hitam polos -- makanya seluruh isi artikel keliatan biru
	   semua, bukan cuma link. Kembalikan ke warna teks normal, link tetap dibedakan. */
	.content, .content p, .content li, .content h1, .content h2, .content h3,
	.content h4, .content h5, .content h6, .content strong, .content em {
		color: #1a1a1a !important;
	}
	.content a {
		color: #2563eb !important;
		text-decoration: underline;
	}
</style>

<div class="container" data-aos="fade-up">

	<div class="row mt-md mb-lg">


		<div class="card">

			<div class="post-img">
				<?php
				//echo $post->banner;
				?>

				<img src="<?= $this->upload_model->link_web($post->banner, "post"); ?>"
					alt="" class="img-fluid w-100 img-thumbnail"
					onerror="this.onerror=null;this.src='<?= base_url('assets/no_pict.png'); ?>';">
			</div>

			<div class="card-header px-md">
				<h2 class="title"><?= $post->judul; ?></h2>

				<div class="meta-top mb-sm">
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
			<div class="content prose max-w-none py-md px-md">
				<?= $post->body; ?>
			</div><!-- End post content -->

			<div class="meta-bottom card-footer px-md">
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