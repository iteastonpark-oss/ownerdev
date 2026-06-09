<div class="row">
	<div class="col-xl-4 order-xl-2 mb-5 mb-xl-0">

		<div class="card bg-secondary shadow mb-3">
			<div class="card-header bg-white border-0">
				<div class="row align-items-center">
					<div class="col-8">
						<h3 class="mb-0">Unit Information</h3>
					</div>
				</div>
			</div>
			<div class="card-body">
				<table class="table table-borderless table-sm card-body wrap">
					<tr>
						<th class="pull-right">Unit</th>
						<td>: <?= $u->kode; ?></td>
					</tr>
					<tr>
						<th class="pull-right">Large</th>
						<td>: <?= $u->luas; ?></td>
					</tr>
					<tr>
						<th class="pull-right">Floor</th>
						<td>: <?= $u->lantai; ?></td>
					</tr>
					<tr>
						<th class="pull-right">Type</th>
						<td>: <?= $u->tipe; ?></td>
					</tr>
					<tr>
						<th class="pull-right">Tower</th>
						<td>: <?= $u->tower; ?></td>
					</tr>
					<tr>
						<th class="pull-right">Code VA</th>
						<td>: <?= $u->va; ?></td>
					</tr>
				</table>


			</div>
		</div>
		<div class="card bg-secondary shadow">
			<div class="card-header bg-white border-0">
				<div class="row align-items-center">
					<div class="col-8">
						<h3 class="mb-0">Bill Information</h3>
					</div>
				</div>
			</div>
			<div class="card-body">
				Rp. <?= $this->apl->number_format($tagihan, 1); ?>
			</div>
		</div>

		<div class="card bg-secondary shadow">
			<div class="card-header bg-white border-0">
				<div class="row align-items-center">
					<div class="col-8">
						<h3 class="mb-0">Water Consumption</h3>
					</div>
				</div>
			</div>
			<div class="card-body">
				<?= $air . " M3"; ?>
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


					<?php
					$this->apl->anchor('<a href="' . site_url('pemilik/edit/?id=' . $p->id_pemilik) . '"
							   class="btn btn-warning pull-right" title="edit"><i class="fa fa-edit"></i></a>'
							, 'Edit Data pemilik', 'pemilik')

					?>
				</div>
			</div>
			<div class="card-body">
				<table class="table table-borderless table-sm card-body wrap">
					<tr>
						<th colspan="2"><h6 class="heading-small text-muted mb-4">
								Profil</h6>
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
						<th class="pull-right">Place & Date of Birth</th>
						<th>: <?= $p->tempat_lahir . " ," . $p->tanggal_lahir; ?></th>
					</tr>
					<tr>
						<th class="pull-right">Marital Status</th>
						<th>: <?= $p->status_perkawinan; ?></th>
					</tr>
					<tr>

						<th class="pull-right">Address</th>
						<td class="flex-lg-wrap">: <?php echo $p->alamat
									. "<br> Desa. " . $this->apl->get_nilai_pilih("lok_desa", "nama", "id_desa=" . $p->id_desa)
									. "<br> Kec. " . $this->apl->get_nilai_pilih("lok_kec", "nama", "id_kec=" . $p->id_kec)
									. " ," . $this->apl->get_nilai_pilih("lok_kab", "nama", "id_kab=" . $p->id_kab); ?></td>
					</tr>
					<tr>
						<th colspan="2"><h6 class="heading-small text-muted mb-4">
								Contact</h6>
						</th>
					</tr>
					<tr>
						<th class="pull-right">Tel.</th>
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
					<tr>
						<th colspan="2"><h6 class="heading-small text-muted mb-4">
								Employment data</h6>
						</th>
					</tr>
					<tr>
						<th class="pull-right">Company Name</th>
						<th>: <?= $p->kantor; ?></th>
					</tr>
					<tr>
						<th class="pull-right">Company Address</th>
						<th>: <?= $p->alamat_kantor . " " . $p->kota_kantor; ?></th>
					</tr>

					<tr>
						<th colspan="2"><h6 class="heading-small text-muted mt-2">
								Correspondance</h6>
						</th>
					</tr>
					<tr>
						<th class="pull-right">Hp</th>
						<th>: <?= $b->hp_surat; ?></th>
					</tr>
					<tr>
						<th class="pull-right">Whatsapp</th>
						<th>: <?= $b->wa_surat; ?></th>
					</tr>
					<tr>
						<th class="pull-right">Email</th>
						<th>: <?= $b->email_surat; ?></th>
					</tr>
					<tr>
						<th class="pull-right">Address</th>
						<th>: <?= $b->alamat_surat; ?></th>
					</tr>
				</table>


			</div>
		</div>

	</div>
</div>


