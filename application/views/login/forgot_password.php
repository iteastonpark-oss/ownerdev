<div class="max-w-md mx-auto my-auto py-lg">
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant p-md">
        <div class="text-center mb-md">
            <div class="w-10 h-10 rounded-lg bg-primary-container/20 flex items-center justify-center mx-auto mb-sm">
                <span class="material-symbols-outlined text-primary" style="font-size: 20px;">mail</span>
            </div>
            <h1 class="font-headline-sm text-headline-sm font-bold text-on-surface">Lupa Password</h1>
            <p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">Masukkan Nomor WhatsApp &amp; Unit yang terdaftar, kami akan kirim link reset password ke email Anda.</p>
        </div>

        <form method="post" action="<?= site_url('login/forgot_password_act') ?>" class="space-y-xs">
            <div class="space-y-xs">
                <label class="font-label-sm text-label-sm font-medium text-on-surface block" for="whatsapp">Nomor WhatsApp</label>
                <input class="w-full rounded-lg border border-outline-variant bg-surface-container-low px-md py-2.5 font-body-md text-on-surface focus:outline-none" id="whatsapp" name="hp" placeholder="Contoh: 08123456789" type="tel" required />
            </div>

            <div class="space-y-xs">
                <label class="font-label-sm text-label-sm font-medium text-on-surface block" for="id_bast">Pilih Unit</label>
                <?= $this->dropdown_model->getDropdownUnitBast('id_bast',
                    '',
                    'id="id_bast" class="w-full rounded-lg border border-outline-variant bg-surface-container-low px-md py-2.5 font-body-md text-on-surface focus:outline-none" required'); ?>
            </div>

            <button class="w-full text-on-primary font-label-md text-label-md font-bold py-3 px-md rounded-lg shadow-lg mt-sm" type="submit" style="background: linear-gradient(135deg, #715d00 0%, #b89e3d 100%);">
                Kirim Link Reset Password
            </button>

            <a href="<?= site_url('login') ?>" class="block text-center font-label-sm text-label-sm text-on-surface-variant hover:text-primary mt-sm">Kembali ke Login</a>
        </form>
    </div>
</div>
