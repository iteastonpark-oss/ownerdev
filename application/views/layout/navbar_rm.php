<?php
/**
 * Created by PhpStorm.
 * User: 2kangs
 * Date: 1/22/2019
 * Time: 6:50 PM
 */
?>

<!-- Top navbar -->
<nav class="navbar navbar-top navbar-expand navbar-dark bg-blue border-bottom py-2">

	<div class="container-fluid">
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<a class="h4 mb-0 py-0 text-white text-uppercase d-none d-lg-inline-block"
			   href="<?php echo site_url(''); ?>">
				PT NUSALIMA KELOLA SARANA
				<p><small>RENTAL MANAGEMENT SYSTEM</small></p>
			</a>
			<ul class="navbar-nav align-items-center ml-md-auto">
				<li class="nav-item d-xl-none">
					<!-- Sidenav toggler -->
					<div class="pr-3 sidenav-toggler sidenav-toggler-dark" data-action="sidenav-pin"
						 data-target="#sidenav-main">
						<div class="sidenav-toggler-inner">
							<span class="navbar-toggler-icon"></span>
						</div>
					</div>
				</li>
			</ul>

			<?php if ($this->session->login) { ?>
				<!-- Navbar links -->
				<ul class="navbar-nav align-items-center ml-auto ml-md-0">
					<li class="nav-item dropdown">
						<a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
						   aria-expanded="false">
							<div class="media align-items-center">
                              <span class="avatar avatar-sm bg-info rounded-circle">

                           	<i class="fa fa-user fa-2x"></i>
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
							<a class="dropdown-item" href="<?php echo site_url('profil'); ?>"><i
										class="fa fa-edit"></i>
								Profil</a>
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

<div class="header bg-blue pb-6">
	<div class="container-fluid">
		<br>
		<div class="header-body">
			<div class="row align-items-center py-1">
				<div class="col-lg-6 col-7">
					<?php
					if ($this->session->login) {

						?>
						<h6 class="h2 text-white d-inline-block mb-0">
							<?php echo ucwords((isset($judul)) ? $judul : 'Rental Management System'); ?></h6>
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
				<div class="col-lg-6 col-5 text-right">
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

