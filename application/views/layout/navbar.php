<?php
/**
 * Created by PhpStorm.
 * User: 2kangs
 * Date: 1/22/2019
 * Time: 6:50 PM
 */
?>

<!-- Top navbar -->
<nav class="navbar navbar-top navbar-expand navbar-dark fixed-top
bg-<?= $this->bm_model->get()->tema; ?> border-bottom py-2">

	<div class="container-fluid">
		<div class="collapse navbar-collapse" id="">

			<?php if ($this->session->login) { ?>

				<ul class="navbar-nav align-items-center ml-md-auto">
					<!--
					<span class="navbar-toggler-icon"></span>
					-->
					<a href="#" onclick="window.history.back();"><i class="fa fa-arrow-left fa-1x text-white"></i></a>
					<?php
					$this->load->view('layout/bottombar');
					?>
					</li>
				</ul>
			<?php } ?>
			<div class="ml-3">
				<a class="h4 mb-0 py-0 text-white text-uppercase ml-md-auto d-lg-inline-block"
				   href="<?php echo site_url(''); ?>">
					<?= $this->bm_model->get()->judul; ?>
					<br>
					<span><small>BUILDING MANAGEMENT SYSTEM</small></span>
				</a>
			</div>

			<?php if ($this->session->login) { ?>

				<!-- Navbar links -->
				<ul class="navbar-nav align-items-center ml-auto ml-md-0">

					<div id="notifikasi"></div>
					<li class="nav-item dropdown">
						<a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
						   aria-expanded="false">
							<div class="media align-items-center">
                              <span class="avatar avatar-sm bg-info rounded-circle">

                           	<i class="fa fa-building fa-2x"></i>
							 </span>
								<div class="media-body ml-2 d-none d-lg-block">
								<span
										class="mb-0 text-sm  font-weight-bold"><?php echo $this->session->username; ?></span>
								</div>
							</div>
						</a>
						<div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
							<div class=" dropdown-header noti-title">
								<h6 class="text-overflow m-0">Welcome!</h6>
							</div>
							<a class="dropdown-item" href="<?php echo site_url('bast'); ?>"><i
										class="fa fa-building-o"></i>
								Profil Unit</a>
							<a class="dropdown-item" href="<?php echo site_url('login/logout'); ?>"><i
										class="fa fa-sign-out"></i> Log Out</a>
						</div>
					</li>
				</ul>


			<?php } ?>

		</div>
	</div>
</nav>
<!-- Topnav -->

<div class="header bg-<?= $this->bm_model->get()->tema; ?> pb-2" style="padding-top: 60px">

	<div class="container-fluid">

		<br>
		<div class="header-body">
			<!--
			<a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block"
			   href="<?php echo site_url(''); ?>"><?php echo (isset($judul)) ? $judul : 'Building Management System'; ?></a>
			-->
			<div class="row align-items-center py-1 mb-3">
				<div class="col-lg-8 col-8">

					<?php
					if ($this->session->login) {

						?>
						<h6 class="h2 text-white d-inline-block mb-0 mt--1">
							<?php echo ucwords((isset($judul)) ? $judul : 'Building Management System'); ?>
						</h6>
						<!--
						<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
							<ol class="breadcrumb breadcrumb-links bg-transparent">
								<li class="breadcrumb-item active">
									<a href="<?php echo site_url(); ?>"
									   class="text-white link"><i class="fas fa-home"></i>
										Home</a>
								</li>
								<?php
						$jumlah_segment = $this->uri->total_segments();
						$menu = 'home';
						for ($i = 1; $i <= $jumlah_segment; $i++) {
							if ($i == $jumlah_segment) {
								echo '<li class="breadcrumb-item active" aria-current="page"> '
										. ucwords($this->uri->segment($i)) . '</li > ';
							} else {
								if ($i == '1') {
									/*
									echo '<li class="breadcrumb-item active" aria-current="page"><a href = "'
										. site_url($this->uri->segment($i)) . '" class="text-white"> '
										. ucwords($this->uri->segment($i)) . '</a ></li > ';
									*/
									echo '<li class="breadcrumb-item active" aria-current="page"><a href = "#" 
                                            class="text-white"> '
											. ucwords($this->uri->segment($i)) . '</a ></li > ';

								}
								if ($i == '2') {
									echo '<li class="breadcrumb-item text-white"> <a href = "'
											. site_url($this->uri->segment(1) . "/" . $this->uri->segment(2)) . '" class="text-white"> '
											. ucwords($this->uri->segment($i)) . '</a></li > ';
								}
							}
						}
						?>
							</ol>
						</nav>
						-->
					<?php } else {
						echo '<br><br>';
					} ?>
				</div>
				<div class="col-lg-4 col-4 text-right">
					<?php
					if ($this->session->login) {
						echo isset($tombol_view) ? $tombol_view : '<br>';
					}
					?>
				</div>

			</div>
		</div>

	</div>


</div>
