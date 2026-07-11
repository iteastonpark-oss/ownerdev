<?php
/**
 * Voting acara (penghuni): vote + hasil realtime.
 * $acara, $votings (tiap $v: ->opsi[], ->pilihan[], ->sudah, ->buka, ->tipe, ->tampil_hasil, ->status)
 */
?>
<a href="<?= site_url('acara/detail/' . $acara->kode); ?>" class="inline-flex items-center gap-xs font-label-md text-label-md text-on-surface-variant hover:text-on-surface mb-md">
	<span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>Kembali ke Acara
</a>

<div class="mb-md">
	<h1 class="font-headline-md text-headline-md font-bold text-on-surface"><?= htmlspecialchars($acara->nama); ?></h1>
	<p class="font-body-md text-body-md text-on-surface-variant">Voting</p>
</div>

<?php if (empty($votings)): ?>
	<div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm p-lg text-center text-on-surface-variant">
		<span class="material-symbols-outlined" style="font-size:40px;">how_to_vote</span>
		<p class="font-body-md text-body-md mt-sm">Belum ada voting untuk acara ini.</p>
	</div>
<?php endif; ?>

<?php foreach ($votings as $v):
	$show_form  = $v->buka && !$v->sudah;
	$show_hasil = ((int) $v->tampil_hasil === 1) && ($v->sudah || !$v->buka);
?>
	<div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm mb-lg">
		<div class="flex items-start justify-between gap-sm px-lg py-md border-b border-outline-variant">
			<div class="flex items-center gap-sm min-w-0">
				<span class="material-symbols-outlined text-primary">how_to_vote</span>
				<h2 class="font-headline-sm text-headline-sm font-bold text-on-surface"><?= htmlspecialchars($v->pertanyaan); ?></h2>
			</div>
			<?php if (!$v->buka): ?>
				<span class="shrink-0 inline-flex items-center px-sm py-xs rounded-full font-label-sm text-label-sm font-semibold" style="background:#e6e8ea;color:#4c4637">Ditutup</span>
			<?php elseif ($v->sudah): ?>
				<span class="shrink-0 inline-flex items-center px-sm py-xs rounded-full font-label-sm text-label-sm font-semibold" style="background:#dcfce7;color:#166534">Sudah Memilih</span>
			<?php endif; ?>
		</div>
		<div class="p-lg">

			<?php if ($show_form): ?>
				<form action="<?= site_url('acara/vote'); ?>" method="post" class="flex flex-col gap-sm">
					<input type="hidden" name="id_voting" value="<?= (int) $v->id_voting; ?>">
					<?php foreach ($v->opsi as $o): ?>
						<label class="flex items-center gap-sm rounded-lg border border-outline-variant px-md py-sm cursor-pointer hover:bg-surface-container">
							<?php if ($v->tipe === 'multi'): ?>
								<input type="checkbox" name="opsi[]" value="<?= (int) $o->id_opsi; ?>" class="text-primary">
							<?php else: ?>
								<input type="radio" name="opsi" value="<?= (int) $o->id_opsi; ?>" class="text-primary">
							<?php endif; ?>
							<span class="font-body-md text-body-md text-on-surface"><?= htmlspecialchars($o->teks_opsi); ?></span>
						</label>
					<?php endforeach; ?>
					<div class="mt-sm">
						<button type="submit" class="inline-flex items-center gap-xs bg-primary text-on-primary rounded-lg px-lg py-sm font-label-md text-label-md transition-all hover:!bg-[#15803d] hover:!text-white">
							<span class="material-symbols-outlined" style="font-size:20px;">send</span>Kirim Suara
						</button>
						<?php if ($v->tipe === 'multi'): ?><span class="ml-sm font-label-sm text-label-sm text-on-surface-variant">Boleh pilih lebih dari satu.</span><?php endif; ?>
					</div>
				</form>

			<?php elseif (!$v->sudah && $v->buka): ?>
				<!-- (tak terjadi: show_form menangani) -->
			<?php endif; ?>

			<?php if (!$show_form && !$show_hasil): ?>
				<div class="flex items-start gap-sm rounded-lg bg-surface-container p-md text-on-surface-variant">
					<span class="material-symbols-outlined" style="font-size:20px;">info</span>
					<span class="font-body-md text-body-md">
						<?php if ($v->sudah): ?>Anda sudah memberikan suara. Hasil belum dipublikasikan.
						<?php else: ?>Voting ini sudah ditutup.<?php endif; ?>
					</span>
				</div>
			<?php endif; ?>

			<?php if ($show_hasil): ?>
				<?php if ($show_form === false && $v->sudah): ?>
					<div class="mb-sm font-label-md text-label-md text-on-surface-variant"><span class="material-symbols-outlined align-middle" style="font-size:18px;">check_circle</span> Pilihan Anda tersimpan. Hasil sementara:</div>
				<?php endif; ?>
				<div data-poll="<?= site_url('acara/voting_hasil_ajax/' . $v->id_voting); ?>">
					<div class="flex justify-between mb-sm">
						<span class="font-label-md text-label-md text-on-surface-variant">Hasil Realtime</span>
						<span class="font-label-md text-label-md font-semibold text-on-surface">Total Pemilih: <span class="v-pemilih">0</span></span>
					</div>
					<div class="v-list flex flex-col gap-sm">
						<div class="text-on-surface-variant font-body-md text-body-md">Memuat...</div>
					</div>
				</div>
			<?php endif; ?>

		</div>
	</div>
<?php endforeach; ?>

<script>
	(function () {
		var palette = ['#715d00', '#2dce89', '#fb6340', '#11cdef', '#f5365c', '#8965e0', '#545f73', '#8898aa'];
		function esc(s) { var d = document.createElement('div'); d.textContent = s == null ? '' : s; return d.innerHTML; }

		function renderInto(box, data) {
			box.querySelector('.v-pemilih').textContent = data.pemilih;
			var total = 0;
			data.opsi.forEach(function (o) { total += parseInt(o.jml, 10) || 0; });
			var list = box.querySelector('.v-list');
			if (!data.opsi.length) { list.innerHTML = '<div class="text-on-surface-variant">Belum ada opsi.</div>'; return; }
			var html = '';
			data.opsi.forEach(function (o, i) {
				var jml = parseInt(o.jml, 10) || 0;
				var pct = total > 0 ? Math.round(jml / total * 100) : 0;
				html += '<div>'
					+ '<div class="flex justify-between mb-xs font-body-md text-body-md"><span class="text-on-surface">' + esc(o.teks_opsi) + '</span>'
					+ '<span class="font-semibold text-on-surface">' + jml + ' <span class="text-on-surface-variant">(' + pct + '%)</span></span></div>'
					+ '<div style="height:20px;background:#eceef0;border-radius:9999px;overflow:hidden;"><div style="height:100%;width:' + pct + '%;background:' + palette[i % palette.length] + ';border-radius:9999px;"></div></div>'
					+ '</div>';
			});
			list.innerHTML = html;
		}

		var boxes = Array.prototype.slice.call(document.querySelectorAll('[data-poll]'));
		function pollAll() {
			boxes.forEach(function (box) {
				fetch(box.getAttribute('data-poll'), { credentials: 'same-origin' })
					.then(function (r) { return r.json(); })
					.then(function (d) { if (d && d.status) renderInto(box, d); })
					.catch(function () {});
			});
			setTimeout(pollAll, 3000);
		}
		if (boxes.length) pollAll();
	})();
</script>
