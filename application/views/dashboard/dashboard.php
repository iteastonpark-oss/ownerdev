<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 4/8/2016
 * Time: 3:00 PM
 */
?>

<?php
$controller = strtolower($this->uri->segment(1));
?>

<div class="card bg-green">
	<div class="card-body">
		<div class="header-body mt--4">
			<div class="row align-items-center">
				<div class="col-lg-6">
					<div class="pr-5">
						<h1 class="display-2 text-white font-weight-bold mb-0">Selamat Datang di halaman Portal Owner
							Unit <?= $u->kode; ?></h1>
						<p class="text-white mt-4">Ini adalah halaman informasi untuk owner</p>

					</div>
				</div>
				<div class="col-lg-6">
					<div class="row pt-5">
						<div class="col-6">
							<a href="<?= site_url('bayar/invoice/bayar?id_unit=' . $this->session->id_unit); ?>">

								<div class="card">
									<div class="card-body">
										<div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow mb-4">
											<i class="ni ni-money-coins"></i>
										</div>
										<h5 class="h3">Outstanding</h5>
										<p>Rp. <?= $this->apl->number_format($tagihan, 1); ?></p>
									</div>
								</div>

							</a>
						</div>
						<div class="col-6 pt-lg-5 pt-4">
							<a href="<?= site_url('meter/utility/view/air'); ?>">

								<div class="card mb-4">
									<div class="card-body">
										<div class="icon icon-shape bg-gradient-success text-white rounded-circle shadow mb-4">
											<i class="ni ni-active-40"></i>
										</div>
										<h5 class="h3">Water Consumption</h5>
										<p><?= $air; ?> M3</p>
									</div>
								</div>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
