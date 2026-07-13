<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 4/8/2016
 * Time: 3:00 PM
 */
?>

<?php
$controller = strtolower($this->uri->segment(1));
?>

<div class="mb-lg">
    <!-- Welcome Card -->
    <div class="bg-primary rounded-xl p-xl text-on-primary mb-lg">
        <div class="flex flex-col md:flex-row items-center justify-between gap-md">
            <div>
                <h1 class="font-headline-lg text-headline-lg font-bold mb-sm">Selamat Datang di halaman Portal Owner</h1>
                <p class="font-body-lg text-body-lg text-on-primary/80">Unit <?= $u->kode; ?></p>
                <p class="font-body-md text-body-md text-on-primary/70 mt-sm">Ini adalah halaman informasi untuk owner</p>
            </div>
            <div class="w-32 h-32 rounded-2xl bg-primary-container/30 flex items-center justify-center">
                <span class="material-symbols-outlined" style="font-size: 80px; font-variation-settings: 'FILL' 1;">apartment</span>
            </div>
        </div>
    </div>

    <!-- Pengumuman Acara -->
    <?php if (!empty($pengumuman_acara)):
        if (!function_exists('dash_acara_tgl')) {
            function dash_acara_tgl($v)
            {
                if (empty($v) || $v === '0000-00-00 00:00:00') return '-';
                return date('d M Y, H:i', strtotime($v));
            }
        }
    ?>
        <?php foreach ($pengumuman_acara as $a):
            $sudah = !empty($a->kehadiran);
        ?>
            <div class="bg-tertiary-container rounded-xl border border-outline-variant p-lg mb-lg">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-md">
                    <div class="flex items-start gap-md min-w-0">
                        <div class="w-12 h-12 rounded-xl bg-tertiary/20 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-tertiary" style="font-size:28px; font-variation-settings:'FILL' 1;">campaign</span>
                        </div>
                        <div class="min-w-0">
                            <p class="font-label-md text-label-md text-tertiary font-semibold mb-xs">Pengumuman Acara</p>
                            <h2 class="font-headline-md text-headline-md font-bold text-on-tertiary-container break-words leading-tight"><?= htmlspecialchars($a->nama); ?></h2>
                            <div class="flex flex-wrap items-center gap-md mt-xs font-body-md text-body-md text-on-tertiary-container/80">
                                <span class="inline-flex items-center gap-xs"><span class="material-symbols-outlined" style="font-size:18px;">schedule</span><?= dash_acara_tgl($a->tgl_mulai); ?></span>
                                <?php if (!empty($a->lokasi)): ?>
                                    <span class="inline-flex items-center gap-xs"><span class="material-symbols-outlined" style="font-size:18px;">location_on</span><?= htmlspecialchars($a->lokasi); ?></span>
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($a->batas_rsvp) && $a->batas_rsvp !== '0000-00-00 00:00:00'): ?>
                                <div class="inline-flex items-center gap-xs mt-xs font-label-md text-label-md text-error">
                                    <span class="material-symbols-outlined" style="font-size:18px;">hourglass_top</span>Batas konfirmasi: <?= dash_acara_tgl($a->batas_rsvp); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <a href="<?= site_url('acara/detail/' . $a->kode); ?>"
                       class="inline-flex items-center justify-center gap-xs shrink-0 rounded-lg px-lg py-sm font-label-md text-label-md transition-all <?= $sudah ? 'border border-tertiary text-tertiary hover:!bg-tertiary hover:!text-on-tertiary' : 'bg-tertiary text-on-tertiary hover:!bg-[#15803d] hover:!text-white'; ?>">
                        <span class="material-symbols-outlined" style="font-size:20px;"><?= $sudah ? 'task_alt' : 'check_circle'; ?></span><?= $sudah ? 'Kehadiran Terkonfirmasi' : 'Konfirmasi Kehadiran'; ?>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
        <a href="<?= site_url('bayar/invoice/bayar?id_unit=' . $this->session->id_unit); ?>" class="bg-surface-container-lowest rounded-xl border border-outline-variant p-lg shadow-sm hover:shadow-md transition-all">
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-label-md text-label-md text-on-surface-variant mb-xs">Outstanding</p>
                    <p class="font-display-lg text-display-lg font-bold text-on-surface">Rp. <?= $this->apl->number_format($tagihan, 1); ?></p>
                </div>
                <div class="w-14 h-14 rounded-xl bg-error-container flex items-center justify-center">
                    <span class="material-symbols-outlined text-error" style="font-size: 32px;">payments</span>
                </div>
            </div>
            <div class="mt-md flex items-center gap-xs text-primary">
                <span class="font-label-md text-label-md">Lihat Invoice</span>
                <span class="material-symbols-outlined">arrow_forward</span>
            </div>
        </a>

        <a href="<?= site_url('meter/utility/view/air'); ?>" class="bg-surface-container-lowest rounded-xl border border-outline-variant p-lg shadow-sm hover:shadow-md transition-all">
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-label-md text-label-md text-on-surface-variant mb-xs">Water Consumption</p>
                    <p class="font-display-lg text-display-lg font-bold text-on-surface"><?= $air; ?> M3</p>
                </div>
                <div class="w-14 h-14 rounded-xl bg-tertiary-container flex items-center justify-center">
                    <span class="material-symbols-outlined text-tertiary" style="font-size: 32px;">water_drop</span>
                </div>
            </div>
            <div class="mt-md flex items-center gap-xs text-primary">
                <span class="font-label-md text-label-md">Lihat Detail</span>
                <span class="material-symbols-outlined">arrow_forward</span>
            </div>
        </a>
    </div>
</div>
