<?php
$controller = $this->uri->segment(1);
?>

<div class="card">
	<?php $this->load->view('unit/bast/menu'); ?>
	<div class="card-body">

		<?php if ($gp->num_rows() > 0) { ?>
			<h6 class="heading-small text-muted mb-4">Change Owner's History</h6>
			<table class="table table-hover dt-responsive nowrap table-striped table-sm" width="100%"
				   cellspacing="0">
				<tr class="table-primary">
					<?php
					foreach ($gp->list_fields() as $tr) {
						echo '<th>' . $tr . '</th>';
					}
					?>
				</tr>
				</thead>
				<?php
				$no = 0;
				$jumlah = $gp->num_fields();
				foreach ($gp->result_array() as $data) {
					$no++;
					echo '<tr>';
					for ($i = 0; $i < $jumlah; $i++) {
						$r = array_values($data);
						$r[0] = $no;
						//$r[3] = $this->apl->tgl_format($r[3], 1);
						echo '<td>' . $r[$i] . '</td>';
					}
					echo '</tr>';
				}
				?>
			</table>
			<br>
		<?php } ?>
		<div class="row">
			<div class="col-md-5">
				<table class="table card table-borderless table-sm card-body wrap">
					<tr>
						<th colspan="2"><h6 class="heading-small text-muted mb-4">
								Old Owner Data</h6>
						</th>
					</tr>

					<tr>
						<th class="pull-right">Name</th>
						<th>: <?= $p->nama; ?></th>
					</tr>
					<tr>
						<th class="pull-right">National ID Card</th>
						<th>: <?= $p->nik; ?></th>
					</tr>
					<tr>
						<th class="pull-right">Gender</th>
						<th>: <?= $p->jenis_kelamin; ?></th>
					</tr>
					<tr>
						<th class="pull-right">Tel</th>
						<th>: <?= $p->tlp; ?></th>
					</tr>
					<tr>
						<th class="pull-right">Hp</th>
						<th>: <?= $p->hp; ?></th>
					</tr>
					<tr>
						<th class="pull-right">Email</th>
						<th>: <?= $p->email; ?></th>
					</tr>
				</table>
			</div>
			<div class="col-md-7">

				<div class="card bg-secondary shadow">
					<div class="card-header bg-white border-0">

						<form action="" method="post">
							<div class="form-group">
								<label>Find For The ID Card/Owner's Name
									<small class="text-muted">(Add IF not found)</small>
								</label>
								<?php
								echo $this->dropdown_model->getDropdownPemilikTambahBast('nik', $nik, 'class="form-control satu" onchange="this.form.submit()"')
								?>
							</div>
						</form>
						<div class="row align-items-center">
							<div class="col-8">
								<h3 class="mb-0">New Owner Data</h3>
							</div>
						</div>

					</div>
					<div class="card-body">
						<?php if (isset($pb)) { ?>
							<table class="table table-borderless table-sm">
								<tr>
									<th colspan="2"><h6 class="heading-small text-muted mb-4">
											Profil</h6>
									</th>
								</tr>

								<tr>
									<th class="pull-right">Name</th>
									<th>: <?= $pb->nama; ?></th>
								</tr>
								<tr>
									<th class="pull-right">National ID Card</th>
									<th>: <?= $pb->nik; ?></th>
								</tr>
								<tr>
									<th class="pull-right">Gender</th>
									<th>: <?= $pb->jenis_kelamin; ?></th>
								</tr>
								<tr>

									<th class="pull-right">Address</th>


									<td class="flex-lg-wrap">: <?php echo $pb->alamat
												. "<br> Desa. " . $this->apl->get_nilai_pilih("lok_desa", "nama", "id_desa=" . $pb->id_desa)
												. "<br> Kec. " . $this->apl->get_nilai_pilih("lok_kec", "nama", "id_kec=" . $pb->id_kec)
												. "<br> ," . $this->apl->get_nilai_pilih("lok_kab", "nama", "id_kab=" . $pb->id_kab); ?></td>
								</tr>
								<tr>
									<th colspan="2"><h6 class="heading-small text-muted mb-4">
											Contact</h6>
									</th>
								</tr>
								<tr>
									<th class="pull-right">Tel.</th>
									<th>: <?= $pb->tlp; ?></th>
								</tr>
								<tr>
									<th class="pull-right">Hp</th>
									<th>: <?= $pb->hp; ?></th>
								</tr>
								<tr>
									<th class="pull-right">Whatsapp</th>
									<th>: <?= $pb->wa; ?></th>
								</tr>
								<tr>
									<th class="pull-right">Email</th>
									<th>: <?= $pb->email; ?></th>
								</tr>
							</table>
						<?php } ?>

					</div>
				</div>
			</div>
		</div>

		<?php if (isset($pb)) { ?>
			<form action="<?php echo site_url($controller . '/bast/ganti_pemilik_act'); ?>" method="post"
				  enctype="multipart/form-data">
				<input type="hidden" name="id_bast" value="<?= $b->id_bast; ?>">
				<input type="hidden" name="nik" value="<?= $nik; ?>">
				<input type="hidden" name="id_pemilik" value="<?= $pb->id_pemilik; ?>">
				<input type="hidden" name="id_pemilik_lama" value="<?= $p->id_pemilik; ?>">


				<div class="row">
					<div class="col-md-5"></div>
					<div class="col-md-7">
						<div class="row">
							<div class="col-md-6">

								<div class="form-group">
									<label for="">Ownership To</label>
									<input type="text" class="form-control" name="tangan"
										   value="<?= ($b->tangan + 1); ?>" required>

								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="">Date Of Change</label>
									<input type="date" class="form-control" name="tanggal"
										   value="<?= date('Y-m-d'); ?>" required>

								</div>

							</div>
						</div>
					</div>
				</div>

				<div class="card-footer">
					<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-save"></i> Save</button>
				</div>
			</form>

		<?php } ?>
	</div>

</div>
