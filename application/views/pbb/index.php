<?php $controller = $this->uri->segment(1); ?>

<style>
	.pbb-table .badge { position: relative !important; top: 3px !important; background: initial !important; }
	.pbb-table .badge-success   { background-color: #28a745 !important; color: #fff !important; }
	.pbb-table .badge-warning   { background-color: #ffc107 !important; color: #212529 !important; }
	.pbb-table .badge-secondary { background-color: #6c757d !important; color: #fff !important; }
</style>

<div class="card shadow">
	<div class="card-header py-3">
		<h4 class="mb-0"><i class="fa fa-file-text-o mr-2"></i>Pajak Bumi dan Bangunan (PBB)</h4>
	</div>
	<div class="card-body">

	<?php if (!$pbb): ?>
		<div class="text-center py-5 text-muted">
			<i class="fa fa-inbox fa-3x mb-3 d-block"></i>
			<p>Data PBB untuk unit Anda belum tersedia.<br>
			Silakan hubungi manajemen Easton Park untuk informasi lebih lanjut.</p>
		</div>

	<?php else: ?>

		<!-- Info NOP -->
		<div class="alert alert-light border mb-4 py-2 small">
			<strong>NOP (Nomor Objek Pajak):</strong>
			<?= $pbb->nop ? htmlspecialchars($pbb->nop) : '<span class="text-muted">Belum diisi</span>'; ?>
		</div>

		<?php if (empty($detail)): ?>
		<div class="text-center py-4 text-muted">
			<i class="fa fa-inbox fa-2x mb-2 d-block"></i>
			Belum ada rincian tagihan PBB.
		</div>

		<?php else: ?>
		<div class="table-responsive pbb-table">
			<table class="table table-sm table-bordered table-hover small">
				<thead class="thead-dark">
					<tr>
						<th class="text-center" style="width:70px;">Tahun</th>
						<th class="text-right" style="width:150px;">Tagihan (Rp)</th>
						<th class="text-center" style="width:110px;">Status Bayar</th>
						<th class="text-center" style="width:110px;">Dokumen PDF</th>
						<th class="text-center" style="width:120px;">Tgl Upload</th>
						<th class="text-center" style="width:150px;">Bukti Bayar</th>
						<th class="text-center">Aksi</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($detail as $d):
					$lunas   = ((float)$d->tagihan == 0);
					$has_pdf = !empty($d->file_pdf);
					$has_bukti = !empty($d->bukti_bayar);
				?>
				<tr>
					<td class="text-center font-weight-bold"><?= $d->tahun; ?></td>
					<td class="text-right">
						<?= (float)$d->tagihan > 0
							? 'Rp ' . number_format((float)$d->tagihan, 0, ',', '.')
							: '-'; ?>
					</td>
					<td class="text-center">
						<?php if ($lunas): ?>
						<span class="badge badge-success">Lunas</span>
						<?php else: ?>
						<span class="badge badge-warning">Belum Lunas</span>
						<?php endif; ?>
					</td>
					<td class="text-center">
						<?php if ($has_pdf): ?>
						<span class="badge badge-success"><i class="fa fa-check"></i> Tersedia</span>
						<?php else: ?>
						<span class="badge badge-secondary">Belum ada</span>
						<?php endif; ?>
					</td>
					<td class="text-center text-muted small">
						<?= $d->tgl_upload ? date('d/m/Y', strtotime($d->tgl_upload)) : '-'; ?>
					</td>
					<td class="text-center">
						<?php if ($has_bukti): ?>
						<a href="<?= site_url($controller . '/bukti/' . $d->id_detail); ?>" target="_blank"
							class="btn btn-sm btn-outline-success" title="Lihat bukti bayar">
							<i class="fa fa-check-circle"></i> Lihat
						</a>
						<button type="button" class="btn btn-sm btn-link p-0 ml-1 btn-upload-bukti"
							data-id="<?= $d->id_detail; ?>" data-tahun="<?= $d->tahun; ?>" title="Ganti bukti">
							<i class="fa fa-refresh"></i>
						</button>
						<?php else: ?>
						<button type="button" class="btn btn-sm btn-primary btn-upload-bukti"
							data-id="<?= $d->id_detail; ?>" data-tahun="<?= $d->tahun; ?>">
							<i class="fa fa-upload"></i> Upload
						</button>
						<?php endif; ?>
						<?php if ($d->bukti_tgl_upload): ?>
						<div class="text-muted small mt-1"><?= date('d/m/Y H:i', strtotime($d->bukti_tgl_upload)); ?></div>
						<?php endif; ?>
					</td>
					<td class="text-center">
						<?php if ($has_pdf): ?>
						<a href="<?= site_url($controller . '/download/' . $d->id_detail); ?>"
							target="_blank" class="btn btn-sm btn-danger" title="Lihat/Download PDF">
							<i class="fa fa-file-pdf-o"></i> Lihat PDF
						</a>
						<?php else: ?>
						<span class="text-muted small">—</span>
						<?php endif; ?>
					</td>
				</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>

		<div class="alert alert-info mt-3 small">
			<i class="fa fa-info-circle mr-1"></i>
			Dokumen PDF PBB diunggah oleh manajemen Easton Park.
			Bukti bayar PBB dapat Anda unggah sendiri (PDF/JPG/PNG, maks 5&nbsp;MB) dan akan tercatat di sistem manajemen.
			Jika dokumen tahun tertentu belum tersedia, silakan hubungi kantor manajemen.
		</div>
		<?php endif; ?>

	<?php endif; ?>

	</div>
</div>

<!-- Modal upload bukti bayar PBB -->
<div class="modal fade" id="modalBukti" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<form action="<?= site_url($controller . '/upload_bukti'); ?>" method="post" enctype="multipart/form-data">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"><i class="fa fa-upload mr-2"></i>Upload Bukti Bayar PBB</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<input type="hidden" name="id_detail" id="bukti-id-detail" value="">
					<p class="small text-muted mb-2">Tahun PBB: <strong id="bukti-tahun">-</strong></p>
					<div class="form-group mb-2">
						<label class="small">Pilih file bukti (PDF / JPG / PNG, maks 5 MB)</label>
						<input type="file" name="bukti" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png" required>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
					<button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-upload mr-1"></i>Unggah</button>
				</div>
			</div>
		</form>
	</div>
</div>

<script>
document.querySelectorAll('.btn-upload-bukti').forEach(function (b) {
	b.addEventListener('click', function () {
		document.getElementById('bukti-id-detail').value = this.getAttribute('data-id');
		document.getElementById('bukti-tahun').textContent = this.getAttribute('data-tahun');
		if (window.jQuery) { jQuery('#modalBukti').modal('show'); }
		else { document.getElementById('modalBukti').style.display = 'block'; }
	});
});
</script>
