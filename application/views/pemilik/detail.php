<?php
$controller = $this->uri->segment(1);
?>

<div class="card">
	<?php $this->load->view('pemilik/menu'); ?>
	<div class="card-body">
		<div class="row">
			<div class="col-xl-4 order-xl-2 mb-5 mb-xl-0">
				<div class="card card-profile shadow">
					<div class="row justify-content-center">
						<div class="col-lg-3 order-lg-2">
							<div class="card-profile-image">
								<a href="#">
									<?php
									echo $this->upload_model->tampil_gambar_modal($pemilik->foto, "....", "pemilik");
									?>
								</a>
							</div>
						</div>
					</div>
					<div class="card-body pt-0 pt-md-4">
						<div class="text-center mt-5">
							<br>
							<br>
							<h5 class="title">
								<?php echo $pemilik->nama; ?></h5>
							<label for="">(<?php echo $pemilik->nik; ?>)</label>
							<br>

						</div>
					</div>

				</div>
			</div>
			<div class="col-xl-8 order-xl-1">
				<div class="card bg-secondary shadow">
					<div class="card-header bg-white border-0">
						<div class="row align-items-center">
							<div class="col-8">
								<h3 class="mb-0">Owner Information</h3>
							</div>
						</div>
					</div>
					<div class="card-body">
						<h6 class="heading-small text-muted mb-4">Profil</h6>
						<div class="pl-lg-4">
							<table class="table table-borderless table-sm table-striped card-body
                            wrap">
								<tr>
									<th class="pull-right">Address</th>
									<td>: <?php echo $pemilik->alamat
												. "<br> Desa. " . $this->apl->get_nilai_pilih("lok_desa", "nama", "id_desa=" . $pemilik->id_desa)
												. " Kec. " . $this->apl->get_nilai_pilih("lok_kec", "nama", "id_kec=" . $pemilik->id_kec)
												. " ," . $this->apl->get_nilai_pilih("lok_kab", "nama", "id_kab=" . $pemilik->id_kab); ?></td>
								</tr>
								<tr>
									<th class="pull-right">Gender</th>
									<td>: <?php echo $pemilik->jenis_kelamin; ?></td>
								</tr>
								<tr>
									<th class="pull-right">Place and Date of birth</th>
									<td>
										: <?php echo $pemilik->tempat_lahir . ", " . $pemilik->tanggal_lahir; ?></td>
								</tr>
								<tr>
									<th class="pull-right">Hp</th>

									<td>: <?php echo $pemilik->hp; ?></td>
								</tr>
								<tr>
									<th class="pull-right">Whatsapp</th>

									<td>: <?php echo $pemilik->wa; ?></td>
								</tr>
								<tr>
									<th class="pull-right">Tel.</th>

									<td>: <?php echo $pemilik->tlp; ?></td>
								</tr>
								<tr>
									<th class="pull-right">Email</th>

									<td>: <?php echo $pemilik->email; ?></td>
								</tr>
							</table>

						</div>
						<hr class="my-4"/>

					</div>
				</div>
				<hr>
			</div>
		</div>

	</div>


</div>
