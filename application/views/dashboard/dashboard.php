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
