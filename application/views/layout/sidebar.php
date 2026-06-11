<?php
/**
 * Created by PhpStorm.
 * User: 2kangs
 * Date: 1/22/2019
 * Time: 6:48 PM
 */
?>

<!-- Backdrop overlay saat sidebar terbuka di mobile -->
<div id="sidenav-backdrop" class="fixed inset-0 bg-black/50 z-30 hidden md:hidden" onclick="toggleSidebar()"></div>

<nav class="fixed left-0 top-0 h-full w-64 bg-surface-container-lowest border-r border-outline-variant shadow-sm z-40 transition-transform duration-300 -translate-x-full md:translate-x-0" id="sidenav-main">
    <div class="h-full flex flex-col">
        <!-- Brand -->
        <div class="px-md border-b border-outline-variant flex items-center" style="height:64px">
            <a href="<?= site_url(''); ?>" class="flex items-center gap-md">
                <span class="material-symbols-outlined text-primary">apartment</span>
                <div class="flex flex-col">
                    <span class="font-label-md text-label-md font-bold text-on-surface">EPR Jatinangor</span>
                    <span class="font-label-sm text-label-sm text-on-surface-variant">Owner Portal</span>
                </div>
            </a>
        </div>

        <!-- Navigation -->
        <?php
        $seg = $this->uri->segment(1, '');
        $active   = 'flex items-center gap-md px-md py-sm rounded-lg bg-primary-container text-on-primary transition-all';
        $inactive = 'flex items-center gap-md px-md py-sm rounded-lg hover:bg-surface-container text-on-surface-variant hover:text-on-surface transition-all';
        $menus = [
            ''        => ['icon' => 'home',          'label' => 'Dashboard'],
            'bayar'   => ['icon' => 'receipt_long',  'label' => 'Invoice'],
            'tiket'   => ['icon' => 'support_agent', 'label' => 'Request'],
            'meter'   => ['icon' => 'water',         'label' => 'Utility'],
            'pbb'     => ['icon' => 'description',   'label' => 'PBB'],
            'blog'    => ['icon' => 'shield',        'label' => 'P3SRS'],
        ];
        ?>
        <div class="flex-1 overflow-y-auto p-md">
            <ul class="flex flex-col gap-sm">
                <?php foreach ($menus as $key => $menu): ?>
                <li>
                    <a class="<?= ($seg === $key) ? $active : $inactive ?>"
                       href="<?= site_url($key ?: '') ?>">
                        <span class="material-symbols-outlined"><?= $menu['icon'] ?></span>
                        <span class="font-label-md text-label-md font-medium"><?= $menu['label'] ?></span>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Unit Profile -->
        <?php
        // Ambil dari controller jika sudah dipass, fallback ke session
        $sidebar_unit    = isset($u) ? $u : null;
        $sidebar_pemilik = isset($p) ? $p : null;
        if (!$sidebar_unit && $this->session->id_unit) {
            $sidebar_unit = $this->db->get_where('db_unit', ['id_unit' => $this->session->id_unit])->row();
        }
        if (!$sidebar_pemilik && $this->session->id_pemilik) {
            $sidebar_pemilik = $this->db->get_where('pemilik', ['id_pemilik' => $this->session->id_pemilik])->row();
        }
        ?>
        <?php if ($sidebar_unit): ?>
        <div class="px-md pb-sm border-t border-outline-variant pt-md">
            <div class="rounded-lg bg-primary-container p-sm flex flex-col gap-xs">
                <div class="flex items-center gap-sm">
                    <span class="material-symbols-outlined text-on-primary-container text-base">apartment</span>
                    <span class="font-label-md text-label-md font-bold text-on-primary-container"><?= htmlspecialchars($sidebar_unit->kode) ?></span>
                    <span class="ml-auto font-label-sm text-label-sm text-on-primary-container opacity-70"><?= htmlspecialchars($sidebar_unit->luas) ?> m²</span>
                </div>
                <div class="flex flex-col gap-xs pl-sm">
                    <p class="font-label-sm text-label-sm text-on-primary-container">
                        <span class="opacity-70">Tower</span>&nbsp;
                        <span class="font-medium"><?= htmlspecialchars($sidebar_unit->tower) ?></span>
                    </p>
                    <p class="font-label-sm text-label-sm text-on-primary-container">
                        <span class="opacity-70">Tipe</span>&nbsp;
                        <span class="font-medium"><?= htmlspecialchars($sidebar_unit->tipe) ?></span>
                    </p>
                    <?php if ($sidebar_pemilik): ?>
                    <p class="font-label-sm text-label-sm text-on-primary-container truncate">
                        <span class="opacity-70">Pemilik</span>&nbsp;
                        <span class="font-medium"><?= htmlspecialchars($sidebar_pemilik->nama) ?></span>
                    </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- User Profile -->
        <div class="p-md border-t border-outline-variant">
            <div class="flex items-center gap-md p-sm rounded-lg bg-surface-container-low">
                <span class="material-symbols-outlined text-on-surface-variant">person</span>
                <div class="flex-1 min-w-0">
                    <p class="font-label-md text-label-md font-medium text-on-surface truncate"><?php echo $this->session->username; ?></p>
                    <p class="font-label-sm text-label-sm text-on-surface-variant truncate">Unit Owner</p>
                </div>
                <a href="<?= site_url('login/logout'); ?>" class="text-on-surface-variant hover:text-error transition-colors">
                    <span class="material-symbols-outlined">logout</span>
                </a>
            </div>
        </div>
    </div>
</nav>

<script>
    function toggleSidebar() {
        var nav = document.getElementById('sidenav-main');
        var backdrop = document.getElementById('sidenav-backdrop');
        var opened = !nav.classList.contains('-translate-x-full');
        if (opened) {
            nav.classList.add('-translate-x-full');
            backdrop.classList.add('hidden');
        } else {
            nav.classList.remove('-translate-x-full');
            backdrop.classList.remove('hidden');
        }
    }
    // Tutup sidebar setelah pilih menu di mobile
    document.querySelectorAll('#sidenav-main a').forEach(function (a) {
        a.addEventListener('click', function () {
            if (window.innerWidth < 768) {
                document.getElementById('sidenav-main').classList.add('-translate-x-full');
                document.getElementById('sidenav-backdrop').classList.add('hidden');
            }
        });
    });
</script>
