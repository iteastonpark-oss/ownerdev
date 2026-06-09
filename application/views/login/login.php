<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 4/15/2016
 * Time: 9:06 AM
 */
?>
<div class="card bg-neutral">
	<div class="card-body">
<div class="main-content mt--9">

		<div class="container">
			<div class="header-body text-center">
				<div class="row justify-content-center">
					<div class="col-xl-5 col-lg-6 col-md-8 px-5">
						<h1 class="text-white">Welcome Owner!</h1>
						<p class="text-lead text-white"></p>
					</div>
				</div>
			</div>
		</div>
	<!-- Page content -->

	<!-- Page content -->
	<div class="container mt--8">
		<div class="row justify-content-center">
			<div class="col-lg-5 col-md-7">
				<div class="card bg-light border-0 mb-0">

					<!--
					<div class="alert alert-warning" role="alert">
						<h4 class="alert-heading">Maaf!</h4>
						<p>Untuk Portal Ownner sementara di tutup sampai proses blast, untuk menghindari antrian permintaan OTP lewat whatsapp!</p>
						<hr>
						<p class="mb-0">Terimakasih</p>
					</div>
					<?php
					//die();
					?>
					-->
					<div class="card-body px-lg-5 py-lg-5">

						<form action="<?php echo site_url('login/login_act'); ?>" method="post">
							<div class="form-group">
								<label class="control-label">Number Whatsapp</label>
								<div class="input-group">
									<input type="text"
										   class="form-control input-sm"
										   name="hp" placeholder="Number Whatsapp" required>
									<div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="fa fa-whatsapp"></i></span>
									</div>
								</div>
							</div>
							<div class="form-group mt--4">
								<label class="control-label">Unit</label>

								<?= $this->dropdown_model->getDropdownUnitBast('id_bast',
										'',
										'class="form-control satu" required'); ?>
							</div>
							<br>
							<button type="submit"
									class="btn btn-primary btn-block"><i class="fa fa-sign-in"></i> Login
							</button>

						</form>

					</div>
				</div>

			</div>
		</div>
	</div>
</div>

	</div>
</div>
