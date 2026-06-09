<?php

/**
 * Created by PhpStorm.
 * User: 2kangs
 * Date: 1/22/2019
 * Time: 6:48 PM
 */
?>

<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light " id="sidenav-main">
	<div class="scrollbar-inner">
		<!-- Brand -->
		<div class="sidenav-header d-flex align-items-center">
			<a class="navbar-brand text-black-50" href="<?= site_url(''); ?>">

				<span class="font-weight-bold">BMS</span>
			</a>
			<div class="ml-auto">
				<!-- Sidenav toggler -->
				<div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin"
					 data-target="#sidenav-main">
					<div class="sidenav-toggler-inner">
						<i class="sidenav-toggler-line"></i>
						<i class="sidenav-toggler-line"></i>
						<i class="sidenav-toggler-line"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="navbar-inner">
			<!-- Collapse -->
			<div class="collapse navbar-collapse" id="sidenav-collapse-main">
				<ul class="navbar-nav">
					<li class="nav-item">
						<a class="nav-link text-gray-dark  pb-1" href="<?= site_url('');?>"
						   aria-controls="navbar-dashboards">
							<i class="fa fa-home text-primary"></i>
							<span class="nav-link-text ml-2"> Dashboard</span>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link text-gray-dark  pb-1" href="<?= site_url('bayar');?>"
						   aria-controls="navbar-dashboards">
							<i class="fa fa-bank text-primary"></i>
							<span class="nav-link-text ml-2"> Invoice</span>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link text-gray-dark  pb-1" href="<?= site_url('tiket');?>"
						   aria-controls="navbar-dashboards">
							<i class="fa fa-comment text-primary"></i>
							<span class="nav-link-text ml-2"> Request</span>
						</a>
					</li><li class="nav-item">
						<a class="nav-link text-gray-dark  pb-1" href="<?= site_url('meter');?>"
						   aria-controls="navbar-dashboards">
							<i class="fa fa-bars text-primary"></i>
							<span class="nav-link-text ml-2"> Utility</span>
						</a>
					</li>
					</li>
					<li class="nav-item">
						<a class="nav-link text-gray-dark pb-1" href="<?= site_url('pbb'); ?>"
						   aria-controls="navbar-dashboards">
							<i class="fa fa-file-text-o text-primary"></i>
							<span class="nav-link-text ml-2"> PBB</span>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link text-gray-dark  pb-1" href="<?= site_url('blog/menu');?>"
						   aria-controls="navbar-dashboards">
							<i class="fa fa-bars text-primary"></i>
							<span class="nav-link-text ml-2"> P3SRS</span>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</nav>
