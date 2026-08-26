<?php
$force = isset($force) ? $force : false;
?>
<div class="max-w-md mx-auto">
    <?php if ($force): ?>
    <div class="rounded-lg bg-error-container text-on-error-container px-md py-sm mb-md flex items-start gap-sm">
        <span class="material-symbols-outlined" style="font-size: 20px;">info</span>
        <p class="font-body-sm text-body-sm">Untuk keamanan akun Anda, password default WAJIB diganti sebelum melanjutkan.</p>
    </div>
    <?php endif; ?>

    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant p-md">
        <div class="text-center mb-md">
            <div class="w-10 h-10 rounded-lg bg-primary-container/20 flex items-center justify-center mx-auto mb-sm">
                <span class="material-symbols-outlined text-primary" style="font-size: 20px;">key</span>
            </div>
            <h1 class="font-headline-sm text-headline-sm font-bold text-on-surface">Ubah Password</h1>
            <p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">Minimal 8 karakter, gunakan kombinasi yang tidak mudah ditebak.</p>
        </div>

        <form method="post" action="<?= site_url('login/ganti_password_act') ?>" class="space-y-md">
            <?php if (!$force): ?>
            <div class="space-y-xs">
                <label class="font-label-sm text-label-sm font-medium text-on-surface block" for="current_password">Password Saat Ini</label>
                <input class="w-full rounded-lg border border-outline-variant bg-surface-container-low px-md py-2.5 font-body-md text-on-surface focus:outline-none" id="current_password" name="current_password" type="password" autocomplete="current-password" required />
            </div>
            <?php endif; ?>

            <div class="space-y-xs">
                <label class="font-label-sm text-label-sm font-medium text-on-surface block" for="new_password">Password Baru</label>
                <input class="w-full rounded-lg border border-outline-variant bg-surface-container-low px-md py-2.5 font-body-md text-on-surface focus:outline-none" id="new_password" name="new_password" type="password" minlength="8" autocomplete="new-password" required />
            </div>

            <div class="space-y-xs">
                <label class="font-label-sm text-label-sm font-medium text-on-surface block" for="confirm_password">Konfirmasi Password Baru</label>
                <input class="w-full rounded-lg border border-outline-variant bg-surface-container-low px-md py-2.5 font-body-md text-on-surface focus:outline-none" id="confirm_password" name="confirm_password" type="password" minlength="8" autocomplete="new-password" required />
            </div>

            <button class="btn-primary w-full text-on-primary font-label-md text-label-md font-bold py-3 px-md rounded-lg shadow-lg" type="submit" style="background: linear-gradient(135deg, #715d00 0%, #b89e3d 100%);">
                Simpan Password Baru
            </button>

            <?php if (!$force): ?>
            <a href="<?= site_url('') ?>" class="block text-center font-label-sm text-label-sm text-on-surface-variant hover:text-primary">Batal, kembali ke Dashboard</a>
            <?php endif; ?>
        </form>
    </div>
</div>
