<?php
/**
 * Menu P3SRS owner: kategori dikelompokkan (Informasi & Laporan di atas,
 * Dokumen Legalitas & Lainnya di bawah). Pemetaan: config/p3srs_menu.php,
 * data dari Blog::menu() -> Web_Model::getMenuP3srs().
 *
 * @var array $menu     ['utama' => [...], 'dokumen' => [...]]
 * @var array $menu_cfg isi config p3srs_menu (judul, ikon_symbol, ikon_default)
 */
if (!function_exists('p3srs_menu_tgl')) {
	function p3srs_menu_tgl($ymd)
	{
		$bln = array(1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des');
		$t = strtotime($ymd);
		if (!$t) {
			return '';
		}
		return date('j', $t) . ' ' . $bln[(int) date('n', $t)] . ' ' . date('Y', $t);
	}
}

$bagian = array(
	'utama' => array('icon' => 'folder_special'),
	'dokumen' => array('icon' => 'inventory_2'),
);
?>

<div class="mb-md">
	<h1 class="font-headline-md text-headline-md text-on-surface">Menu Laporan P3SRS</h1>
	<p class="font-body-md text-body-md text-on-surface-variant mt-xs">Pilih kategori untuk melihat laporan dan dokumen
		pengelolaan.</p>
</div>

<?php foreach ($bagian as $kelompok => $b): ?>
	<?php if (empty($menu[$kelompok])) continue; ?>
	<section class="mb-lg">
		<h2 class="flex items-center gap-sm font-headline-sm text-headline-sm text-on-surface mb-sm">
			<span class="material-symbols-outlined text-primary"
				  style="font-size:24px; font-variation-settings:'FILL' 1;"><?= $b['icon']; ?></span>
			<?= htmlspecialchars($menu_cfg['judul'][$kelompok]); ?>
		</h2>

		<div class="grid grid-cols-1 sm:grid-cols-2 2xl:grid-cols-3 gap-sm md:gap-md">
			<?php foreach ($menu[$kelompok] as $k):
				$simbol = isset($menu_cfg['ikon_symbol'][$k->ikon])
					? $menu_cfg['ikon_symbol'][$k->ikon]
					: $menu_cfg['ikon_symbol'][$menu_cfg['ikon_default']];
				$utama = ($kelompok === 'utama');
				?>
				<a href="<?= site_url('blog/kategori/' . $k->id_kategori); ?>"
				   class="group flex items-center gap-sm p-sm md:p-md bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm hover:shadow-md hover:border-primary active:bg-surface-container-low transition min-h-[72px]">
					<div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 <?= $utama ? 'bg-primary-fixed text-on-primary-fixed' : 'bg-secondary-container text-on-secondary-container'; ?>">
						<span class="material-symbols-outlined"
							  style="font-size:28px; font-variation-settings:'FILL' 1;"><?= $simbol; ?></span>
					</div>

					<div class="min-w-0 flex-1">
						<h3 class="font-body-lg text-body-lg font-semibold text-on-surface leading-snug break-words">
							<?= htmlspecialchars($k->nama); ?>
						</h3>

						<?php if ($k->deskripsi !== ''): ?>
							<p class="font-label-md text-label-md text-on-surface-variant mt-xs"><?= htmlspecialchars($k->deskripsi); ?></p>
						<?php endif; ?>

						<?php if ($k->jenis !== 'laporan'): ?>
							<?php if ($k->total > 0): ?>
								<p class="flex flex-wrap items-center gap-x-sm gap-y-xs font-label-sm text-label-sm text-tertiary mt-xs">
									<span class="inline-flex items-center gap-xs">
										<span class="material-symbols-outlined" style="font-size:16px;">article</span><?= (int) $k->total; ?> dokumen
									</span>
									<?php if (!empty($k->terakhir)): ?>
										<span class="inline-flex items-center gap-xs">
											<span class="material-symbols-outlined" style="font-size:16px;">update</span>Terakhir diperbarui <?= p3srs_menu_tgl($k->terakhir); ?>
										</span>
									<?php endif; ?>
								</p>
							<?php else: ?>
								<p class="font-label-sm text-label-sm text-outline mt-xs">Belum ada dokumen</p>
							<?php endif; ?>
						<?php endif; ?>
					</div>

					<span class="material-symbols-outlined text-outline group-hover:text-primary shrink-0">chevron_right</span>
				</a>
			<?php endforeach; ?>
		</div>
	</section>
<?php endforeach; ?>
