<div class="card">
	<div class="card-body">
		<h6 class="heading-small text-muted mb-4">Informasi</h6>
		<table class="table table-sm table-borderless small table-striped">
			<tr>
				<th>No Form</th>
				<th><?= $h->no_form; ?></th>
			</tr>
			<tr>
				<th>Tanggal Pengajuan</th>
				<th><?= $h->tanggal_masuk; ?></th>
			</tr>
			<tr>
				<th>Nama</th>
				<th><?= $h->nama; ?></th>
			</tr>
			<tr>
				<th>Kontak</th>
				<th><?= $h->hp; ?></th>
			</tr>
			<tr>
				<th>Email</th>
				<th><?= $h->email; ?></th>
			</tr>
			<tr>
				<th>Tipe Penghuni</th>
				<th><?= $this->apl->get_nilai_pilih("db_huni", "nama", "id=" . $h->tipe); ?></th>
			</tr>
		</table>
		<hr class="my-4"/>

		<h6 class="heading-small text-muted mb-4">Periode</h6>
		<table class="table table-sm table-borderless small table-striped">
			<tr>
				<th>Periode</th>
				<th><?php
					if ($h->periode == 1) {
						echo "HARIAN";
					}
					if ($h->periode == 2) {
						echo "BULANAN";
					}
					if ($h->periode == 3) {
						echo "TAHUNAN";
					}
					?></th>
			</tr>
			<tr>
				<th>Tanggal</th>
				<th><?= $h->tanggal_awal . " s.d " . $h->tanggal_ahir; ?></th>
			</tr>
			<tr>
				<th>Tanggal Keluar</th>
				<th><?= $h->tanggal_keluar; ?></th>
			</tr>
		</table>
		<hr class="my-4"/>

	</div>
