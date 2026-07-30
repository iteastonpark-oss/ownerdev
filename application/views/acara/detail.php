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
if (!function_exists('acara_dokumen_kosong')) {
	function acara_dokumen_kosong($teks)
	{
		echo '<div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm p-lg flex items-start gap-sm text-on-surface-variant">'
			. '<span class="material-symbols-outlined" style="font-size:20px;">info</span>'
			. '<span class="font-body-md text-body-md">' . htmlspecialchars($teks) . '</span></div>';
	}
}
if (!function_exists('acara_dokumen_view')) {
	/**
	 * Tampilan file undangan/tata tertib: preview inline (PDF/gambar) + tombol unduh.
	 * File Word (.doc/.docx) tidak dipreview browser, tampil sebagai kartu unduh saja.
	 */
	function acara_dokumen_view($url, $field, $filename, $deskripsi = '')
	{
		$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
		$is_pdf = ($ext === 'pdf');
		$is_img = in_array($ext, array('jpg', 'jpeg', 'png'), true);
		?>
		<div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm">
			<div class="p-lg">
				<?php if ($deskripsi !== ''): ?>
					<div class="font-body-md text-body-md text-on-surface mb-md acara-rich-text"><?= $deskripsi; ?></div>
				<?php endif; ?>
				<?php if ($is_pdf): ?>
					<iframe src="<?= $url; ?>" class="w-full rounded-lg border border-outline-variant" style="height:70vh;"></iframe>
				<?php elseif ($is_img): ?>
					<img src="<?= $url; ?>" alt="" class="w-full rounded-lg border border-outline-variant">
				<?php else: ?>
					<div class="flex items-center gap-sm rounded-lg bg-surface-container p-md text-on-surface-variant">
						<span class="material-symbols-outlined" style="font-size:24px;">description</span>
						<span class="font-body-md text-body-md">Dokumen (Word) — gunakan tombol unduh untuk membuka.</span>
					</div>
				<?php endif; ?>
				<a href="<?= $url; ?>?dl=1"
				   class="mt-md inline-flex items-center gap-xs bg-primary text-on-primary rounded-lg px-lg py-sm font-label-md text-label-md transition-all hover:!bg-[#15803d] hover:!text-white">
					<span class="material-symbols-outlined" style="font-size:20px;">download</span>Unduh
				</a>
			</div>
		</div>
		<?php
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
			<div class="font-body-md text-body-md text-on-surface mb-md acara-rich-text"><?= $acara->deskripsi; ?></div>
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

<!-- Tab nav: vertikal menumpuk (full width) di HP supaya semua tab langsung kelihatan tanpa geser, horizontal seperti biasa di layar lebar -->
<div class="flex flex-col gap-xs sm:flex-row sm:gap-0 sm:border-b sm:border-outline-variant mb-lg sm:overflow-x-auto">
	<?php
	$tabs = array(
		'registrasi' => array('label' => 'Registrasi Kehadiran', 'icon' => 'how_to_reg'),
		'undangan'   => array('label' => 'File Undangan', 'icon' => 'mail'),
		'tatib'      => array('label' => 'Tata Tertib', 'icon' => 'rule'),
		'materi'     => array('label' => 'Materi', 'icon' => 'menu_book'),
	);
	$first = true;
	foreach ($tabs as $key => $t):
		// !bg-primary/!text-primary/!border-primary/!bg-transparent/!border-transparent pakai
		// modifier "!" krn Argon (argon.css, dimuat global) mendefinisikan class bawaan Bootstrap
		// yang sama dengan `!important`, yg tanpa modifier ini akan menang lawan utility Tailwind.
		$activeCls = $first
			? '!bg-primary text-on-primary sm:!bg-transparent sm:!text-primary !border-transparent sm:border-b-2 sm:!border-primary'
			: 'bg-surface-container text-on-surface-variant sm:!bg-transparent hover:bg-surface-container-high sm:hover:text-on-surface !border-transparent sm:border-b-2 sm:hover:!border-outline-variant';
	?>
		<button type="button" class="acara-tab-btn flex items-center gap-xs w-full sm:w-auto whitespace-nowrap rounded-lg sm:rounded-none px-lg py-sm font-label-md text-label-md border transition-all <?= $activeCls; ?>"
				data-tab="<?= $key; ?>">
			<span class="material-symbols-outlined" style="font-size:18px;"><?= $t['icon']; ?></span><?= $t['label']; ?>
		</button>
	<?php $first = false; endforeach; ?>
</div>

<!-- Tab 1: Registrasi Kehadiran -->
<div class="acara-tab-panel" data-tab="registrasi">

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

</div><!-- /Tab 1: Registrasi Kehadiran -->

<!-- Tab 2: File Undangan -->
<div class="acara-tab-panel hidden" data-tab="undangan">
	<?php if (!empty($acara->file_undangan)): ?>
		<?php acara_dokumen_view(site_url('acara/dokumen/' . $acara->kode . '/undangan'), 'file_undangan', $acara->file_undangan); ?>
	<?php else: ?>
		<?php acara_dokumen_kosong('Belum ada file undangan yang diunggah untuk acara ini.'); ?>
	<?php endif; ?>
</div>

<!-- Tab 3: Tata Tertib -->
<div class="acara-tab-panel hidden" data-tab="tatib">
	<?php if (!empty($acara->file_tata_tertib)): ?>
		<?php acara_dokumen_view(site_url('acara/dokumen/' . $acara->kode . '/tatib'), 'file_tata_tertib', $acara->file_tata_tertib); ?>
	<?php else: ?>
		<?php acara_dokumen_kosong('Belum ada file tata tertib yang diunggah untuk acara ini.'); ?>
	<?php endif; ?>
</div>

<!-- Tab 4: Materi -->
<div class="acara-tab-panel hidden" data-tab="materi">
	<?php if (!empty($acara->file_materi)): ?>
		<?php acara_dokumen_view(site_url('acara/dokumen/' . $acara->kode . '/materi'), 'file_materi', $acara->file_materi, (string) $acara->deskripsi_materi); ?>
	<?php elseif (!empty($acara->deskripsi_materi)): ?>
		<div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm p-lg">
			<div class="font-body-md text-body-md text-on-surface acara-rich-text"><?= $acara->deskripsi_materi; ?></div>
		</div>
	<?php else: ?>
		<?php acara_dokumen_kosong('Belum ada materi yang diunggah untuk acara ini.'); ?>
	<?php endif; ?>
</div>

<style>
	/* Konten kaya (link/list) dari editor Quill di admin BMS — reset Tailwind preflight menghapus style default link/list. */
	.acara-rich-text a { color: var(--md-sys-color-primary, #15803d); text-decoration: underline; }
	.acara-rich-text ul { list-style: disc; padding-left: 1.5em; }
	.acara-rich-text ol { list-style: decimal; padding-left: 1.5em; }
	.acara-rich-text p:not(:last-child) { margin-bottom: 0.5em; }
</style>

<script>
	(function () {
		document.querySelectorAll('.acara-rich-text a').forEach(function (a) {
			a.setAttribute('target', '_blank');
			a.setAttribute('rel', 'noopener noreferrer');
		});

		var btns = document.querySelectorAll('.acara-tab-btn');
		var panels = document.querySelectorAll('.acara-tab-panel');
		// Sinkron dgn class PHP di atas ($activeCls) — modifier "!" wajib krn argon.css.
		var activeCls = ['!bg-primary', 'text-on-primary', 'sm:!bg-transparent', 'sm:!text-primary', 'sm:!border-primary'];
		var inactiveCls = ['bg-surface-container', 'text-on-surface-variant', 'sm:!bg-transparent'];
		btns.forEach(function (btn) {
			btn.addEventListener('click', function () {
				var tab = btn.getAttribute('data-tab');
				btns.forEach(function (b) {
					var active = b === btn;
					b.classList.toggle('!border-transparent', !active);
					activeCls.forEach(function (c) { b.classList.toggle(c, active); });
					inactiveCls.forEach(function (c) { b.classList.toggle(c, !active); });
				});
				panels.forEach(function (p) {
					p.classList.toggle('hidden', p.getAttribute('data-tab') !== tab);
				});
			});
		});
	})();
</script>
