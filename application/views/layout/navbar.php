<?php
/**
 * Created by PhpStorm.
 * User: 2kangs
 * Date: 1/22/2019
 * Time: 6:50 PM
 */
?>

<!-- Top navbar -->
<nav class="fixed top-0 left-64 right-0 bg-primary text-on-primary border-b border-primary-container shadow-sm z-30">
    <div class="flex justify-between items-center px-md" style="height:64px">
        <div class="flex items-center gap-sm">
            <button onclick="window.history.back();" class="rounded-lg hover:bg-primary-container/20 transition-colors p-base">
                <span class="material-symbols-outlined">arrow_back</span>
            </button>
            <div class="flex flex-col">
                <span class="font-headline-sm text-headline-sm font-bold"><?= $this->bm_model->get()->judul; ?></span>
                <span class="font-label-sm text-label-sm text-on-primary/80 uppercase tracking-wider">Building Management System</span>
            </div>
        </div>

        <div class="flex items-center gap-md">
            <div id="notifikasi"></div>
            <a href="<?php echo site_url('login/logout'); ?>" class="flex items-center gap-sm p-sm rounded-lg hover:bg-primary-container/20 transition-colors">
                <span class="material-symbols-outlined">logout</span>
            </a>
        </div>
    </div>
</nav>

<!-- Page Header -->
<div class="pl-64">
    <div class="px-md py-sm bg-surface-container-low border-b border-outline-variant">
        <div class="flex justify-between items-center">
            <h1 class="font-headline-md text-headline-md font-bold text-on-surface">
                <?php echo ucwords((isset($judul)) ? $judul : 'Building Management System'); ?>
            </h1>
            <div>
                <?php
                if ($this->session->login) {
                    echo isset($tombol_view) ? $tombol_view : '';
                }
                ?>
            </div>
        </div>
    </div>
</div>
