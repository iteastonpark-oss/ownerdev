<?php
/**
 * Detail undangan + form RSVP. Tailwind M3.
 * $acara, $peserta (row|null), $bisa_rsvp (bool)
 */
if (!function_exists('acara_tgl')) {
	function acara_tgl($v)
	{
		if (empty($v) || $v === '0000-00-00 00:00:00') return '-';
		return date('d M Y, H:i', strtotime($v));
	}
}
$keh   = $peserta ? $peserta->kehadiran : '';
$mode  = $peserta ? $peserta->mode : '';
$namaH = $peserta ? $peserta->nama_hadir : '';
$namaW = $peserta ? $peserta->nama_wakil : '';
$inputCls = 'w-full border border-outline-variant rounded-lg px-md py-sm font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary';
?>

<a href="<?= site_url('acara'); ?>" class="inline-flex items-center gap-xs font-label-md text-label-md text-on-surface-variant hover:text-on-surface mb-md">
	<span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>Kembali
</a>

<!-- Detail undangan -->
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm mb-lg">
	<div class="flex items-center gap-sm px-lg py-md border-b border-outline-variant">
		<span class="material-symbols-outlined text-primary">event</span>
		<h2 class="font-headline-sm text-headline-sm font-bold text-on-surface"><?= htmlspecialchars($acara->nama); ?></h2>
	</div>
	<div class="p-lg">
		<?php if (!empty($acara->banner)): ?>
			<img src="<?= site_url('acara/banner/' . $acara->kode); ?>" alt="Banner <?= htmlspecialchars($acara->nama); ?>"
				 class="w-full rounded-lg border border-outline-variant mb-md">
		<?php endif; ?>
		<?php if (!empty($acara->deskripsi)): ?>
			<p class="font-body-md text-body-md text-on-surface mb-md"><?= nl2br(htmlspecialchars($acara->deskripsi)); ?></p>
		<?php endif; ?>
		<div class="grid grid-cols-1 sm:grid-cols-2 gap-sm font-body-md text-body-md text-on-surface-variant">
			<div class="flex items-center gap-xs"><span class="material-symbols-outlined text-primary" style="font-size:18px;">schedule</span><strong class="text-on-surface">Mulai:</strong> <?= acara_tgl($acara->tgl_mulai); ?></div>
			<div class="flex items-center gap-xs"><span class="material-symbols-outlined text-primary" style="font-size:18px;">schedule</span><strong class="text-on-surface">Selesai:</strong> <?= acara_tgl($acara->tgl_selesai); ?></div>
			<?php if (!empty($acara->lokasi)): ?>
				<div class="flex items-center gap-xs"><span class="material-symbols-outlined text-primary" style="font-size:18px;">location_on</span><strong class="text-on-surface">Lokasi:</strong> <?= htmlspecialchars($acara->lokasi); ?>
					<?php if (!empty($acara->link_maps)): ?>
						<a href="<?= htmlspecialchars($acara->link_maps); ?>" target="_blank" rel="noopener" class="text-primary hover:opacity-80">(Buka Maps)</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<?php if (!empty($acara->link_online)): ?>
				<div class="flex items-center gap-xs"><span class="material-symbols-outlined text-primary" style="font-size:18px;">videocam</span><strong class="text-on-surface">Online:</strong>
					<a href="<?= htmlspecialchars($acara->link_online); ?>" target="_blank" rel="noopener" class="text-primary hover:opacity-80">Buka tautan</a>
				</div>
			<?php endif; ?>
			<?php if (!empty($acara->batas_rsvp) && $acara->batas_rsvp !== '0000-00-00 00:00:00'): ?>
				<div class="flex items-center gap-xs text-error"><span class="material-symbols-outlined" style="font-size:18px;">hourglass_top</span><strong>Batas konfirmasi:</strong> <?= acara_tgl($acara->batas_rsvp); ?></div>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php if (!empty($ada_voting)): ?>
	<a href="<?= site_url('acara/voting/' . $acara->kode); ?>"
	   class="flex items-center justify-between gap-sm bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm p-lg mb-lg hover:bg-surface-container transition-all">
		<span class="flex items-center gap-sm">
			<span class="material-symbols-outlined text-primary">how_to_vote</span>
			<span class="font-label-md text-label-md font-semibold text-on-surface">Voting Acara (<?= (int) $ada_voting; ?>)</span>
		</span>
		<span class="material-symbols-outlined text-on-surface-variant">arrow_forward</span>
	</a>
<?php endif; ?>

<!-- QR Kehadiran -->
<?php if ($peserta && !empty($peserta->qr_token) && $keh !== 'online'): ?>
	<div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm mb-lg">
		<div class="flex items-center gap-sm px-lg py-md border-b border-outline-variant">
			<span class="material-symbols-outlined text-primary">qr_code_2</span>
			<h2 class="font-headline-sm text-headline-sm font-bold text-on-surface">QR Kehadiran</h2>
		</div>
		<div class="p-lg flex flex-col items-center text-center">
			<img src="<?= site_url('acara/qr/' . $acara->kode); ?>" alt="QR Kehadiran"
				 class="w-48 h-48 rounded-lg border border-outline-variant p-sm bg-white">
			<p class="font-body-md text-body-md text-on-surface-variant mt-md max-w-md">
				Tunjukkan QR ini saat check-in di lokasi acara. Petugas akan memindainya untuk verifikasi kehadiran<?php
					if ($keh === 'dikuasakan') echo ' (bawa juga surat kuasa, surat izin huni &amp; KTP asli penerima kuasa)';
				?>.
			</p>
			<a href="<?= site_url('acara/qr/' . $acara->kode . '/download'); ?>"
			   class="mt-md inline-flex items-center gap-xs bg-primary text-on-primary rounded-lg px-lg py-sm font-label-md text-label-md transition-all hover:!bg-[#15803d] hover:!text-white">
				<span class="material-symbols-outlined" style="font-size:20px;">download</span>Unduh QR
			</a>
		</div>
	</div>
<?php endif; ?>

<!-- Konfirmasi kehadiran -->
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm">
	<div class="flex items-center gap-sm px-lg py-md border-b border-outline-variant">
		<span class="material-symbols-outlined text-primary">how_to_reg</span>
		<h2 class="font-headline-sm text-headline-sm font-bold text-on-surface">Konfirmasi Kehadiran</h2>
	</div>
	<div class="p-lg">

		<?php if (!$bisa_rsvp): ?>
			<div class="flex items-start gap-sm rounded-lg bg-surface-container p-md text-on-surface-variant mb-md">
				<span class="material-symbols-outlined" style="font-size:20px;"><?= !empty($sudah_checkin) ? 'task_alt' : 'info'; ?></span>
				<span class="font-body-md text-body-md">
					<?php if (!empty($sudah_checkin)): ?>
						Kehadiran Anda <strong>sudah tercatat (check-in)</strong>. Perubahan hanya dapat dilakukan oleh pengurus.
					<?php else: ?>
						Konfirmasi kehadiran untuk acara ini <strong>sudah ditutup</strong>.
					<?php endif; ?>
				</span>
			</div>
			<?php if ($peserta): ?>
				<p class="font-body-md text-body-md text-on-surface">Kehadiran Anda:
					<strong>
						<?php if ($keh === 'online'): ?>Online
						<?php elseif ($keh === 'pemilik'): ?>Offline - Pemilik
						<?php elseif ($keh === 'dikuasakan'): ?>Offline - Dikuasakan (<?= htmlspecialchars($namaW); ?>)
						<?php else: ?>-<?php endif; ?>
					</strong>
				</p>
			<?php else: ?>
				<p class="font-body-md text-body-md text-on-surface-variant">Anda tidak melakukan konfirmasi untuk acara ini.</p>
			<?php endif; ?>

		<?php else: ?>

			<?php if ($peserta): ?>
				<div class="flex items-start gap-sm rounded-lg p-md mb-md" style="background:#d5e0f8;color:#3c475a">
					<span class="material-symbols-outlined" style="font-size:20px;">info</span>
					<span class="font-body-md text-body-md">Anda sudah mengonfirmasi. Anda dapat mengubahnya sebelum batas waktu.</span>
				</div>
			<?php endif; ?>

			<form action="<?= site_url('acara/rsvp'); ?>" method="post" enctype="multipart/form-data" class="flex flex-col gap-md">
				<input type="hidden" name="id_acara" value="<?= (int) $acara->id_acara; ?>">

				<?php
				$cara_awal = ($keh === 'online') ? 'online' : ($keh ? 'offline' : '');
				$offline_tipe_awal = in_array($keh, ['pemilik', 'dikuasakan'], true) ? $keh : '';
				?>
				<div>
					<label class="block font-label-md text-label-md font-semibold text-on-surface mb-sm">Cara Hadir</label>
					<div class="flex flex-col gap-sm">
						<?php $cara_opsi = ['online' => 'Online', 'offline' => 'Offline (datang ke lokasi)']; ?>
						<?php foreach ($cara_opsi as $val => $lbl): ?>
							<label class="flex items-center gap-sm rounded-lg border border-outline-variant px-md py-sm cursor-pointer hover:bg-surface-container">
								<input type="radio" name="cara" value="<?= $val; ?>" class="cara-radio text-primary" <?= $cara_awal === $val ? 'checked' : ''; ?>>
								<span class="font-body-md text-body-md text-on-surface"><?= $lbl; ?></span>
							</label>
						<?php endforeach; ?>
					</div>
				</div>

				<!-- Status Kehadiran (hanya untuk Offline) -->
				<div id="blok-offline-tipe" class="rounded-lg border border-outline-variant p-md" style="display:none;">
					<label class="block font-label-md text-label-md font-semibold text-on-surface mb-sm">Status Kehadiran</label>
					<div class="flex flex-col gap-sm">
						<?php $tipe_opsi = ['pemilik' => 'Pemilik (datang sendiri)', 'dikuasakan' => 'Dikuasakan']; ?>
						<?php foreach ($tipe_opsi as $val => $lbl): ?>
							<label class="flex items-center gap-sm cursor-pointer">
								<input type="radio" name="offline_tipe" value="<?= $val; ?>" class="tipe-radio text-primary" <?= $offline_tipe_awal === $val ? 'checked' : ''; ?>>
								<span class="font-body-md text-body-md text-on-surface"><?= $lbl; ?></span>
							</label>
						<?php endforeach; ?>
					</div>
				</div>

				<!-- Dikuasakan -->
				<div id="blok-kuasa" class="rounded-lg border border-outline-variant p-md flex flex-col gap-md" style="display:none;">
					<div>
						<label class="block font-label-md text-label-md font-semibold text-on-surface mb-xs">Nama Penerima Kuasa</label>
						<input type="text" name="nama_wakil_kuasa" value="<?= $keh === 'dikuasakan' ? htmlspecialchars($namaW) : ''; ?>" class="<?= $inputCls; ?>">
					</div>
					<div>
						<label class="block font-label-md text-label-md font-semibold text-on-surface mb-xs">Surat Kuasa <span class="font-label-sm text-label-sm text-on-surface-variant">(PDF/JPG/PNG, maks 5MB)</span></label>
						<input type="file" name="surat_kuasa" accept=".pdf,.jpg,.jpeg,.png" class="w-full font-body-md text-body-md text-on-surface-variant">
						<?php if ($peserta && !empty($peserta->surat_kuasa)): ?>
							<p class="flex items-center gap-xs font-label-sm text-label-sm mt-xs" style="color:#166534"><span class="material-symbols-outlined" style="font-size:16px;">check_circle</span>Sudah diunggah. Kosongkan bila tidak ingin mengganti.</p>
						<?php endif; ?>
					</div>
					<div>
						<label class="block font-label-md text-label-md font-semibold text-on-surface mb-xs">Foto KTP Penerima Kuasa <span class="font-label-sm text-label-sm text-on-surface-variant">(PDF/JPG/PNG, maks 5MB)</span></label>
						<input type="file" name="ktp_wakil" accept=".pdf,.jpg,.jpeg,.png" class="w-full font-body-md text-body-md text-on-surface-variant">
						<?php if ($peserta && !empty($peserta->ktp_wakil) && $keh === 'dikuasakan'): ?>
							<p class="flex items-center gap-xs font-label-sm text-label-sm mt-xs" style="color:#166534"><span class="material-symbols-outlined" style="font-size:16px;">check_circle</span>Sudah diunggah. Kosongkan bila tidak ingin mengganti.</p>
						<?php endif; ?>
					</div>
					<div>
						<label class="block font-label-md text-label-md font-semibold text-on-surface mb-xs">Surat Izin Huni <span class="font-label-sm text-label-sm text-on-surface-variant">(PDF/JPG/PNG, maks 5MB)</span></label>
						<input type="file" name="surat_izin_huni" accept=".pdf,.jpg,.jpeg,.png" class="w-full font-body-md text-body-md text-on-surface-variant">
						<?php if ($peserta && !empty($peserta->surat_izin_huni)): ?>
							<p class="flex items-center gap-xs font-label-sm text-label-sm mt-xs" style="color:#166534"><span class="material-symbols-outlined" style="font-size:16px;">check_circle</span>Sudah diunggah. Kosongkan bila tidak ingin mengganti.</p>
						<?php endif; ?>
					</div>
				</div>

				<div>
					<label class="block font-label-md text-label-md font-semibold text-on-surface mb-xs">Nama yang Hadir <span class="font-label-sm text-label-sm text-on-surface-variant">(opsional)</span></label>
					<input type="text" name="nama_hadir" value="<?= htmlspecialchars($namaH); ?>" placeholder="Nama orang yang akan hadir" class="<?= $inputCls; ?>">
				</div>

				<div>
					<button type="submit" class="inline-flex items-center justify-center gap-xs bg-primary text-on-primary rounded-lg px-lg py-sm font-label-md text-label-md transition-all hover:!bg-[#15803d] hover:!text-white">
						<span class="material-symbols-outlined" style="font-size:20px;">send</span>Simpan Konfirmasi
					</button>
				</div>
			</form>

			<script>
				(function () {
					function refresh() {
						var cara = document.querySelector('.cara-radio:checked');
						var caraVal = cara ? cara.value : '';
						var tipe = document.querySelector('.tipe-radio:checked');
						var tipeVal = (caraVal === 'offline' && tipe) ? tipe.value : '';

						document.getElementById('blok-offline-tipe').style.display = (caraVal === 'offline') ? 'block' : 'none';
						document.getElementById('blok-kuasa').style.display    = (tipeVal === 'dikuasakan') ? 'block' : 'none';
					}
					document.querySelectorAll('.cara-radio, .tipe-radio').forEach(function (r) { r.addEventListener('change', refresh); });
					refresh();
				})();
			</script>

		<?php endif; ?>
	</div>
</div>
