<!-- ======= Hero Section ======= -->
<section id="hero" class="hero">
	<div class="container position-relative">
		<div class="row gy-5 " data-aos="fade-in">
			<div class="col-lg-6 order-2 order-lg-1 d-flex flex-column
			justify-content-center text-center text-lg-start " style="margin-top: -40px">
				<h2>Welcome to <span>Easton Park Jatinangor</span></h2>
				<p class="text-light">Apartemen di daerah Jatinangor sangat strategis bertempat di daerah pusat
					pendidikan</p>
				<div class="d-flex justify-content-center justify-content-lg-start">
					<a href="#about" class="btn-get-started">Get Started</a>
					<a href="https://www.youtube.com/watch?v=Bdr_Vl0YB0I"
					   class="glightbox btn-watch-video d-flex align-items-center"><i
								class="bi bi-play-circle"></i><span>Watch Video</span></a>
				</div>
			</div>
			<div class="col-lg-6 order-1 order-lg-2 text-center p-5">
				<img src="<?= base_url('assets/web/assets/img/easton/gedung.png'); ?>"
					 class="img-thumbnail" data-aos="zoom-out" style="margin-top: -60px" data-aos-delay="100"
					 height="80%">
			</div>
		</div>
	</div>
	<br>
<!--
	<div class="icon-boxes position-relative">
		<div class="container position-relative">
			<div class="row gy-4 mt-5">


				<div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="500">
					<div class="icon-box">
						<div class="icon"><i class="bi bi-building"></i></div>
						<h4 class="title"><a href="" class="stretched-link">Apartment</a></h4>
					</div>
				</div>

				<div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
					<div class="icon-box">
						<div class="icon"><i class="bi bi-geo-alt"></i></div>
						<h4 class="title"><a href="" class="stretched-link">Pusat Pendidikan</a></h4>
					</div>
				</div>

				<div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
					<div class="icon-box">
						<div class="icon"><i class="bi bi-hearts"></i></div>
						<h4 class="title"><a href="" class="stretched-link">Hunian Nyaman</a></h4>
					</div>
				</div>

				<div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
					<div class="icon-box">
						<div class="icon"><i class="bi bi-coin"></i></div>
						<h4 class="title"><a href="" class="stretched-link">Inventasi</a></h4>
					</div>
				</div>

			</div>
		</div>
	</div>
-->
</section>
<!-- End Hero Section -->

<!-- ======= Blog Section ======= -->
<section id="blog" class="blog">
	<div class="container" data-aos="fade-up">
		<div class="section-header">
			<h2>Informasi</h2>
			<p>Semua Pengumuman,Artikel & Konten Tentang EPR Jatinangor</p>
		</div>
		<div class="row gy-4 posts-list">
			<?php
			foreach ($post as $p) {
				?>
				<div class="col-xl-4 col-md-6">
					<a href="<?= site_url('blog/details/' . $p->slug . '?id=' . $p->id_post); ?>">
						<article>
							<div class="post-img">
								<img src="<?= $this->upload_model->link($p->thumb, "post"); ?>"
									 alt="" class="img-fluid w-100">
							</div>
							<p class="post-category"><?= $p->kategori; ?></p>
							<h2 class="title">
								<?= $p->judul; ?>
							</h2>
							<div class="d-flex align-items-center">
								<img src="<?= base_url('assets/web/assets/img/blog/blog-author.jpg'); ?>" alt=""
									 class="img-fluid post-author-img flex-shrink-0">
								<div class="post-meta">
									<p class="post-author-list">Penulis</p>
									<p class="post-date">
										<time datetime="2022-01-01"><?= $p->tm; ?></time>
									</p>
								</div>

							</div>
						</article>
					</a>
				</div><!-- End post list item -->
				<?php
			}

			?>

		</div><!-- End blog posts list -->

		<div class="d-flex justify-content-center mt-2">
			<a href="<?= site_url('blog'); ?>" class="btn-get-started btn btn-primary float-right">Lainnya</a>

		</div>
	</div>
</section><!-- End Blog Section -->


<main id="main">

	<!-- ======= About Us Section ======= -->
	<section id="about" class="about">
		<div class="container" data-aos="fade-up">

			<div class="section-header">
				<h2>Tentang Kami</h2>
				<p>Easton Park Residence Jatinangor Hunian Nyaman di Pusat Pendidikan Jatinangor</p>
			</div>

			<div class="row gy-4">
				<div class="col-lg-6">
					<h3>Sekilas Tentang Easton Park Residence Jatinangor</h3>
					<img src="<?= base_url('assets/web/assets/img/easton/gedungeaston-bg.jpg'); ?>"
						 class="img-fluid rounded-4 mb-4"
						 alt="">
					<p>
						Apartemen ini berdiri di lahan seluas 6.727m2 dan luas bangunan
						49.650 m2. Hunian vertikal ini hanya memiliki lantai 22 lt dan satu tower dengan tiga sayap yang
						diberi nama
						Harvard, Oxford dan Stanford.</p>

					<p>
						Easton Park Residence ini diperkenalkan pada tahun 2012 dan telah rampung sepenuhnya pada tahun
						2015.
					</p>
				</div>
				<div class="col-lg-6">
					<div class="content ps-0 ps-lg-5">
						<p class="fst-italic">
							Apartemen Easton Park Residence, merupakan salah satu apartemen yang berada di Jatinangor.
							Dengan lokasi strategis, berada di sekeliling Kampus-kampus ternama di Jawa Barat, UNPAD,
							ITB, IPDN dan IKOPIN, menjadikan Easton Park sebagai apartemen favorit bagi mahasiswa
							jatinangor.
						</p>
						<p>
							Berikut 5 alasan kenapa Easton Park Residence menjadi apartemen paling pas untuk mahasiswa
							Jatinangor:
						</p>
						<ul>
							<li><i class="bi bi-check-circle-fill"></i> Akses mudah menuju kampus.
							</li>
							<li><i class="bi bi-check-circle-fill"></i> Fasilitas lengkap.
							</li>
							<li><i class="bi bi-check-circle-fill"></i> Dekat dengan pusat belanja.
							</li>
						</ul>

						<div class="position-relative mt-4">
							<img src="<?= base_url('assets/web/assets/img/easton/ilustrasi-mahasiswa.jpg'); ?>"
								 class="img-fluid rounded-4"
								 alt="">
						</div>
					</div>
				</div>
			</div>

		</div>
	</section><!-- End About Us Section -->

	<!-- ======= Clients Section ======= -->

	<!--
	<section id="clients" class="clients">
		<div class="container" data-aos="zoom-out">

			<div class="clients-slider swiper">
				<div class="swiper-wrapper align-items-center">
					<div class="swiper-slide"><img src="assets/img/clients/client-1.png" class="img-fluid" alt=""></div>
					<div class="swiper-slide"><img src="assets/img/clients/client-2.png" class="img-fluid" alt=""></div>
					<div class="swiper-slide"><img src="assets/img/clients/client-3.png" class="img-fluid" alt=""></div>
					<div class="swiper-slide"><img src="assets/img/clients/client-4.png" class="img-fluid" alt=""></div>
					<div class="swiper-slide"><img src="assets/img/clients/client-5.png" class="img-fluid" alt=""></div>
					<div class="swiper-slide"><img src="assets/img/clients/client-6.png" class="img-fluid" alt=""></div>
					<div class="swiper-slide"><img src="assets/img/clients/client-7.png" class="img-fluid" alt=""></div>
					<div class="swiper-slide"><img src="assets/img/clients/client-8.png" class="img-fluid" alt=""></div>
				</div>
			</div>

		</div>
	</section>
	-->
	<!-- End Clients Section -->

	<!-- ======= Stats Counter Section ======= -->
	<section id="stats-counter" class="stats-counter">
		<div class="container" data-aos="fade-up">

			<div class="row gy-4 align-items-center">

				<div class="col-lg-6">
					<img src="<?= base_url('assets/web/assets/img/stats-img.svg'); ?>" alt="" class="img-fluid">
				</div>

				<div class="col-lg-6">

					<div class="stats-item d-flex align-items-center">
						<span data-purecounter-start="0" data-purecounter-end="1535" data-purecounter-duration="1"
							  class="purecounter"></span>
						<p><strong>Unit</strong></p>
					</div><!-- End Stats Item -->

					<div class="stats-item d-flex align-items-center">
						<span data-purecounter-start="0" data-purecounter-end="1280" data-purecounter-duration="1"
							  class="purecounter"></span>
						<p><strong>BAST</strong> Yang sudah serah terima</p>
					</div><!-- End Stats Item -->

					<div class="stats-item d-flex align-items-center">
						<span data-purecounter-start="0" data-purecounter-end="20" data-purecounter-duration="1"
							  class="purecounter"></span>
						<p><strong>Commercial Area</strong></p>
					</div><!-- End Stats Item -->

				</div>

			</div>

		</div>
	</section><!-- End Stats Counter Section -->

	<!-- ======= Call To Action Section ======= -->
	<section id="call-to-action" class="call-to-action">
		<div class="container text-center" data-aos="zoom-out">
			<a href="https://www.youtube.com/watch?v=Bdr_Vl0YB0I" class="glightbox play-btn"></a>
			<h3>Call To Action</h3>
			<p> .</p>
			<a class="cta-btn" href="#">Call To Action</a>
		</div>
	</section><!-- End Call To Action Section -->

	<!-- ======= Our Services Section ======= -->
	<section id="services" class="services sections-bg">
		<div class="container" data-aos="fade-up">

			<div class="section-header">
				<h2>Ini adalah fasilitas kami</h2>
				<p>Fasilitas yang terdapat di Gedung Easton park juga tidak kalah lengkap dengan apartemen lain,
					terdapat Kolam renang Indoor, Gym area, loby, halaman parkir, mushola dan commercial area.</p>
			</div>

			<div class="row gy-4" data-aos="fade-up" data-aos-delay="100">

				<?php
				$object[] = (object)array('nama' => 'Kolam Renang', 'icon' => 'swimming-pool',
						'ket' => 'Easton Park menawarkan fasilitas berenang untuk bersama sama dengan keluarga anda',);
				$object[] = (object)array('nama' => 'Parkir', 'icon' => 'parking', 'ket' => 'Cakupan parkir kendaraan yang tersedia cukup luas',);
				$object[] = (object)array('nama' => 'Masjid', 'icon' => 'mosque', 'ket' => 'Terdapat masjid untuk peribadahan',);
				$object[] = (object)array('nama' => 'Keamanan 24 Jam', 'icon' => 'camera', 'ket' => 'Dilengkapi dengna security dan cctv 24 jam',);
				$object[] = (object)array('nama' => 'Lokasi Strategis', 'icon' => 'map-pin', 'ket' => 'Berdekatan Dengan Sarana Pendidikan, Pusat Perbelanjaan & Toll',);

				foreach ($object as $f) {
					?>
					<div class="col-lg-4 col-md-6">
						<div class="service-item  position-relative">
							<div class="icon">
								<i class="fa fa-<?= $f->icon; ?>"></i>
							</div>
							<h3><?= $f->nama; ?></h3>
							<p><?= $f->ket; ?></p>
							<!--
							<a href="#" class="readmore stretched-link">Read more <i class="bi bi-arrow-right"></i></a>
							-->
						</div>
					</div><!-- End Service Item -->

					<?php
				}

				?>

			</div>

		</div>
	</section><!-- End Our Services Section -->


	<!-- ======= Portfolio Section ======= -->

	<!--
	<section id="portfolio" class="portfolio sections-bg">
		<div class="container" data-aos="fade-up">

			<div class="section-header">
				<h2>Gallery</h2>
				<p>Gallery Foto dan Kegiatan Easton Park</p>
			</div>
			<div class="portfolio-isotope" data-portfolio-filter="*" data-portfolio-layout="masonry"
				 data-portfolio-sort="original-order" data-aos="fade-up" data-aos-delay="100">

				<div>
					<ul class="portfolio-flters">

						<li data-filter="*" class="filter-active">All</li>
						<?php
						$imgspath = 'upload';
						$path = 'upload';
						$dir = array();
						$dir_to_check = scandir($path);


						foreach ($dir_to_check as $item) {
							if ($item != '..' && $item != '.' && $item != 'post' && $item != '.cache' && is_dir($imgspath . "/" . $item)) {
								array_push($dir, $item);
								echo '<li data-filter=".filter-' . $item . '">' . ucfirst(str_replace("-", " ", $item)) . '</li>';
								$files = scandir($path . '/' . $item);
								$total = count($files);
								$images = array();
								for ($x = 0; $x < $total; $x++):
									if ($files[$x] != '.' && $files[$x] != '..') {
										$gambar[] = (object)array('file' => $item . '/' . $files[$x], 'nama_filter' => 'filter-' . $item);
									}
								endfor;
							}
						}
						?>
					</ul>
				</div>

				<div class="row gy-4 portfolio-container">
					<?php
					foreach ($gambar as $g) {
						?>
						<div class="col-xl-4 col-md-6 portfolio-item <?= $g->nama_filter; ?>">
							<div class="portfolio-wrap">
								<img src="<?= base_url($imgspath . '/' . $g->file); ?>"
									 class="img-fluid">
							</div>
						</div>
						<?php
					}

					?>


				</div>
			</div>
		</div>
	</section>
	-->
	<!-- End Portfolio Section -->

	<!-- ======= Our Team Section ======= -->
	<section id="contact_agent" class="team">
		<div class="container" data-aos="fade-up">

			<div class="section-header">
				<h2>Agent</h2>
				<p>Daftar Agent Resmi Easton Park Jatinangor</p>
			</div>

			<div class="row gy-4">


				<?php
				$agent = array(
						'Cozy',
						'Diajeng',
						'Dita',
						'Edurent',
						'Evy Yustica',
						'Heri',
						'Aisyah',
						'Rajes',
						'Ricky',
						'RM',
						'Sessy',
						'Sutandi',
						'Wati',
				);
				for ($i = 0; $i < count($agent); $i++) {
					?>

					<div class="col-xl-2 col-md-6" data-aos="fade-up" data-aos-delay="400">
						<div class="member">
							<img src="<?= base_url('assets/web/assets/img/agent/no-image.jpg'); ?>"
								 class="img-fluid" alt="">
							<h4><?= $agent[$i]; ?></h4>
							<!--
							<span>Accountant</span>
							-->
							<div class="social">
								<a href=""><i class="bi bi-twitter"></i></a>
								<a href=""><i class="bi bi-facebook"></i></a>
								<a href=""><i class="bi bi-instagram"></i></a>
								<a href=""><i class="bi bi-linkedin"></i></a>
							</div>
						</div>
					</div><!-- End Team Member -->
					<?php
				}
				?>

			</div>

		</div>
	</section><!-- End Our Team Section -->

	<!-- ======= Contact Section ======= -->
	<section id="contact" class="contact">
		<div class="container" data-aos="fade-up">

			<div class="section-header">
				<h2>Kontak</h2>
				<p>Berikut Kontak Resmi Kami</p>
			</div>


			<div class="info-container d-flex flex-column align-items-center justify-content-center">

				<div class="row">
					<div class="col-md-6 mt-2">

						<div class="info-item d-flex">
							<i class="bi bi-whatsapp flex-shrink-0"></i>
							<div>
								<h4>Whatsapp:</h4>
								<p> +62 823-1212-2021</p>
							</div>
						</div><!-- End Info Item -->

					</div>
					<div class="col-md-6 mt-2">
						<div class="info-item d-flex">
							<i class="bi bi-envelope flex-shrink-0"></i>
							<div>
								<h4>Email:</h4>
								<p>info@eprjatinangor.com</p>
							</div>
						</div><!-- End Info Item -->

					</div>
					<div class="col-md-6 mt-2">
						<div class="info-item d-flex">
							<i class="bi bi-phone flex-shrink-0"></i>
							<div>
								<h4>Call:</h4>
								<p>(022) 7780188</p>
							</div>
						</div><!-- End Info Item -->

					</div>
					<div class="col-md-6 mt-2">
						<div class="info-item d-flex">
							<i class="bi bi-clock flex-shrink-0"></i>
							<div>
								<h4>Jam Buka:</h4>
								<p>Setiap Hari: 08.00 - 17.00</p>
							</div>
						</div><!-- End Info Item -->

					</div>
				</div>


			</div>


		</div>
	</section><!-- End Contact Section -->

</main><!-- End #main -->
