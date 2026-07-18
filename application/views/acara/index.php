<?php
/**
 * Daftar acara untuk penghuni (aktif + riwayat) beserta status RSVP unit.
 * Tailwind M3 (selaras dashboard/sidebar ownerdev).
 */
if (!function_exists('acara_badge')) {
	function acara_badge($r)
	{
		// [teks, bg, warna]
		if (empty($r->kehadiran)) {
			$b = ['Belum Konfirmasi', '#fef3c7', '#92400e'];
		} elseif ($r->kehadiran === 'hadir') {
			$m = $r->mode ? ' (' . ucfirst($r->mode) . ')' : '';
			$b = ['Hadir' . $m, '#dcfce7', '#166534'];
		} elseif ($r->kehadiran === 'dikuasakan') {
			$b = ['Dikuasakan', '#d5e0f8', '#3c475a'];
		} elseif ($r->kehadiran === 'diwakilkan') {
			$b = ['Diwakilkan (Keluarga)', '#e0d5f8', '#4c3c5a'];
		} else {
			$b = ['Tidak Hadir', '#e6e8ea', '#4c4637'];
		}
		return '<span class="inline-flex items-center shrink-0 whitespace-nowrap px-sm py-xs rounded-full font-label-sm text-label-sm font-semibold"'
			. ' style="background:' . $b[1] . ';color:' . $b[2] . '">' . htmlspecialchars($b[0]) . '</span>';
	}
}
if (!function_exists('acara_tgl')) {
	function acara_tgl($v)
	{
		if (empty($v) || $v === '0000-00-00 00:00:00') return '-';
		return date('d M Y, H:i', strtotime($v));
	}
}
?>

<!-- Acara Aktif -->
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm mb-lg">
	<div class="flex items-center gap-sm px-lg py-md border-b border-outline-variant">
		<span class="material-symbols-outlined text-primary">event</span>
		<h2 class="font-headline-sm text-headline-sm font-bold text-on-surface">Acara Aktif</h2>
	</div>
	<div class="p-lg">
		<?php if (empty($aktif)): ?>
			<div class="text-center py-lg text-on-surface-variant">
				<span class="material-symbols-outlined" style="font-size:40px;">event_busy</span>
				<p class="font-body-md text-body-md mt-sm">Belum ada acara aktif saat ini.</p>
			</div>
		<?php else: ?>
			<div class="grid grid-cols-1 md:grid-cols-2 gap-md">
				<?php foreach ($aktif as $a): ?>
					<div class="rounded-xl border border-outline-variant p-md flex flex-col">
						<div class="flex items-start justify-between gap-sm mb-sm">
							<h3 class="flex-1 min-w-0 break-words font-headline-md text-headline-md font-semibold text-on-surface leading-tight"><?= htmlspecialchars($a->nama); ?></h3>
							<?= acara_badge($a); ?>
						</div>
						<div class="flex items-center gap-xs font-body-md text-body-md text-on-surface-variant mb-xs">
							<span class="material-symbols-outlined" style="font-size:18px;">schedule</span><?= acara_tgl($a->tgl_mulai); ?>
						</div>
						<?php if (!empty($a->lokasi)): ?>
							<div class="flex items-center gap-xs font-body-md text-body-md text-on-surface-variant mb-xs">
								<span class="material-symbols-outlined" style="font-size:18px;">location_on</span><?= htmlspecialchars($a->lokasi); ?>
							</div>
						<?php endif; ?>
						<?php if (!empty($a->batas_rsvp) && $a->batas_rsvp !== '0000-00-00 00:00:00'): ?>
							<div class="flex items-center gap-xs font-label-md text-label-md text-error mb-sm">
								<span class="material-symbols-outlined" style="font-size:18px;">hourglass_top</span>Batas konfirmasi: <?= acara_tgl($a->batas_rsvp); ?>
							</div>
						<?php endif; ?>
						<div class="mt-auto pt-sm flex flex-col gap-xs">
							<a href="<?= site_url('acara/detail/' . $a->kode); ?>"
							   class="inline-flex items-center justify-center gap-xs w-full bg-primary text-on-primary rounded-lg py-sm font-label-md text-label-md transition-all hover:!bg-[#15803d] hover:!text-white">
								<span class="material-symbols-outlined" style="font-size:20px;">check_circle</span>Lihat &amp; Konfirmasi
							</a>
							<?php if (in_array($a->kehadiran, array('hadir', 'dikuasakan', 'diwakilkan'), true)): ?>
								<a href="<?= site_url('acara/qr/' . $a->kode . '/download'); ?>"
								   class="inline-flex items-center justify-center gap-xs w-full border border-primary text-primary rounded-lg py-sm font-label-md text-label-md transition-all hover:!bg-[#15803d] hover:!text-white hover:!border-[#15803d]">
									<span class="material-symbols-outlined" style="font-size:20px;">qr_code_2</span>Unduh QR Kehadiran
								</a>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</div>

<!-- Riwayat Acara -->
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm">
	<div class="flex items-center gap-sm px-lg py-md border-b border-outline-variant">
		<span class="material-symbols-outlined text-primary">history</span>
		<h2 class="font-headline-sm text-headline-sm font-bold text-on-surface">Riwayat Acara</h2>
	</div>
	<div class="p-lg">
		<?php if (empty($riwayat)): ?>
			<div class="text-center py-lg text-on-surface-variant">
				<span class="material-symbols-outlined" style="font-size:40px;">inbox</span>
				<p class="font-body-md text-body-md mt-sm">Belum ada riwayat acara.</p>
			</div>
		<?php else: ?>
			<div class="flex flex-col divide-y divide-outline-variant">
				<?php foreach ($riwayat as $r): ?>
					<div class="flex items-center justify-between gap-md py-sm">
						<div class="min-w-0">
							<p class="font-label-md text-label-md font-medium text-on-surface truncate"><?= htmlspecialchars($r->nama); ?></p>
							<p class="font-label-sm text-label-sm text-on-surface-variant"><?= acara_tgl($r->tgl_mulai); ?></p>
						</div>
						<div class="flex items-center gap-sm shrink-0">
							<?= acara_badge($r); ?>
							<a href="<?= site_url('acara/detail/' . $r->kode); ?>"
							   class="inline-flex items-center gap-xs font-label-md text-label-md text-primary hover:opacity-80">
								Lihat<span class="material-symbols-outlined" style="font-size:18px;">arrow_forward</span>
							</a>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</div>
