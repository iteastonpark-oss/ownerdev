<main id="main">
	<!-- ======= Portfolio Section ======= -->
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

						<!--
						<li data-filter=".filter-kegiatan">Kegiatan</li>
						<li data-filter=".filter-branding">Branding</li>
						-->
					</ul><!-- End Portfolio Filters -->
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
						</div><!-- End Portfolio Item -->
						<?php
					}

					?>


				</div><!-- End Portfolio Container -->

			</div>
		</div>
	</section><!-- End Portfolio Section -->

</main><!-- End #main -->
