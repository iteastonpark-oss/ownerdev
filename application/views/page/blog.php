<div class="row gy-4 posts-list">
	<?php
	foreach ($post as $p) {
		?>
		<div class="col-6">

			<a href="<?= site_url('blog/details/' . $p->slug . '?id=' . $p->id_post); ?>">
				<div class="card">

					<div class="post-img">
						<img src="<?= $this->upload_model->link_web($p->thumb, "post"); ?>"
							 alt="" class="img-fluid w-100 img-thumbnail" width="30px">
					</div>
					<div class="card-body">
						<h4 class="title">
							<?= $p->judul; ?>
						</h4>
						<div class="d-flex align-items-center mb--4">
							<!--
							<img src="<?= base_url('assets/web/assets/img/blog/blog-author.jpg'); ?>" alt=""
								 class="img-fluid post-author-img flex-shrink-0" width="30px">
							-->
							<div class="post-meta">
								<p class="post-author-list"><h6>Penulis</h6></p>
								<p class="post-date mt--3">
									<time datetime="2022-01-01"><h6><?= $p->tm; ?></h6></time>
								</p>
							</div>

						</div>
					</div>

				</div>
			</a>

		</div><!-- End post list item -->
		<?php
	}
	for ($i = 0; $i < 6; $i++) {
		?>

		<?php
	}
	?>

</div><!-- End blog posts list -->

<div class="Page">
	<ul class="paginationpagination justify-content-center">
		<?php

		for ($i = 1; $i <= $pages; $i++) { ?>
			<li class="page-item <?= ($i == $halaman) ? 'active' : ''; ?>">
				<a href="?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a></li>
		<?php } ?>
	</ul>
</div><!-- End blog pagination -->
