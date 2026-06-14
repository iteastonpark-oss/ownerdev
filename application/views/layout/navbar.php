<?php
/**
 * Created by PhpStorm.
 * User: 2kangs
 * Date: 1/22/2019
 * Time: 6:50 PM
 */
?>

<!-- Top navbar -->
<nav class="fixed top-0 left-0 md:left-64 right-0 bg-primary text-on-primary border-b border-primary-container shadow-sm z-50">
    <div class="flex justify-between items-center px-sm md:px-md" style="height:64px">
        <div class="flex items-center gap-sm min-w-0">
            <button onclick="toggleSidebar();" class="md:hidden rounded-lg hover:bg-primary-container/20 transition-colors p-base">
                <span class="material-symbols-outlined">menu</span>
            </button>
            <div class="flex flex-col min-w-0">
                <span class="font-headline-sm text-headline-sm font-bold truncate"><?= $this->bm_model->get()->judul; ?></span>
                <span class="font-label-sm text-label-sm text-on-primary/80 uppercase tracking-wider truncate hidden sm:block">Building Management System</span>
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
<div class="md:pl-64">
    <div class="px-sm md:px-md py-sm bg-surface-container-low border-b border-outline-variant">
        <div class="flex flex-wrap justify-between items-center gap-sm">
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
