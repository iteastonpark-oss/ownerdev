<main id="main">


	</div><!-- End Breadcrumbs -->
	<!-- ======= Blog Details Section ======= -->
	<section id="blog" class="blog">
		<div class="container" data-aos="fade-up">


			<article class="blog-details">

				<div class="post-img">
					<img src="<?= $this->upload_model->link($post->banner, "post"); ?>"
						 alt="" class="img-fluid w-100">
				</div>

				<h2 class="title"><?= $post->judul; ?></h2>

				<div class="meta-top">
					<ul>
						<!--
						<li class="d-flex align-items-center"><i class="bi bi-person"></i> <a href="blog-details.html">John Doe</a></li>
						-->
						<li class="d-flex align-items-center"><i class="bi bi-clock"></i>
							<a href="#">
								<time datetime="2020-01-01"><?= $this->apl->tgl_format($post->tm, 1); ?></time>
							</a>
						</li>
					</ul>
				</div><!-- End meta top -->

				<div class="content">
					<?= $post->body; ?>
				</div><!-- End post content -->

				<div class="meta-bottom">
					<i class="bi bi-folder"></i>
					<ul class="cats">
						<li>
							<a href="<?= site_url('blog/category/' . $post->id_kategori); ?>"><?= $post->kategori; ?></a>
						</li>


					</ul>

					<i class="bi bi-people"></i>
					<ul class="tags">
						<li>
							<a href="<?= site_url('post/edit?id=' . $this->input->get('id')); ?>"
							   target="_blank">
								Edit</a>
						</li>
					</ul>

				</div><!-- End meta bottom -->

			</article><!-- End blog post -->


		</div>
	</section><!-- End Blog Details Section -->

</main><!-- End #main -->
