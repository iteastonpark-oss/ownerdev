<?php

/**
 * Created by PhpStorm.
 * User: 2kangs
 * Date: 1/22/2019
 * Time: 6:48 PM
 */
$controller = $this->uri->segment(1);

?>

<!-- Bottom Navbar -->
<nav class="navbar navbar-dark bg-info navbar-expand d-md-none d-lg-none d-xl-none fixed-bottom">
	<ul class="navbar-nav nav-justified w-100">
		<li class="nav-item">
			<a class="nav-link <?= ($controller == '') ? 'text-light' : ''; ?>" href="<?= site_url(''); ?>"
			   aria-controls="navbar-dashboards">
				<i class="fa fa-home"></i>
				<br><small>Home</small>
			</a>
		</li>


		<li class="nav-item">
			<a class="nav-link pb-1 <?= ($controller == 'bayar') ? 'text-light' : ''; ?>"
			   href="<?= site_url('bayar'); ?>"
			   aria-controls="navbar-dashboards">
				<i class="fa fa-bank"></i>
				<br><small>Invoice</small>

			</a>
		</li>

		<li class="nav-item">
			<a class="nav-link pb-1 <?= ($controller == 'tiket') ? 'text-light' : ''; ?>"
			   href="<?= site_url('tiket'); ?>"
			   aria-controls="navbar-dashboards">
				<i class="fa fa-comment"></i>
				<br><small>Request</small>

			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link pb-1 <?= ($controller == 'meter') ? 'text-light' : ''; ?>"
			   href="<?= site_url('meter'); ?>"
			   aria-controls="navbar-dashboards">
				<i class="fa fa-bars"></i>
				<br><small>Utility</small>

			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link pb-1 <?= ($controller == 'pbb') ? 'text-light' : ''; ?>"
			   href="<?= site_url('pbb'); ?>"
			   aria-controls="navbar-dashboards">
				<i class="fa fa-file-text-o"></i>
				<br><small>PBB</small>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link pb-1 <?= ($controller == 'blog') ? 'text-light' : ''; ?>"
			   href="<?= site_url('blog/menu'); ?>"
			   aria-controls="navbar-dashboards">
				<i class="fa fa-book"></i>
				<br><small>P3SRS</small>
			</a>
		</li>
	</ul>
</nav>
