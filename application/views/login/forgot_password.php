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

            <!-- Unit Selection (searchable combobox — sama seperti halaman login) -->
            <div class="space-y-xs" id="unit-combo">
                <label class="font-label-sm text-label-sm font-medium text-on-surface block" for="unit-combo-input">Pilih Unit</label>

                <!-- Hidden native select = the real form field (name=id_bast, required) -->
                <div class="hidden">
                    <?= $this->dropdown_model->getDropdownUnitBast('id_bast',
                        '',
                        'id="id_bast" required'); ?>
                </div>

                <div class="relative">
                    <div class="relative rounded-lg border border-outline-variant bg-surface-container-low overflow-hidden">
                        <div class="absolute inset-y-0 left-0 pl-md flex items-center pointer-events-none text-on-surface-variant z-10">
                            <span class="material-symbols-outlined" style="font-size: 20px;">apartment</span>
                        </div>
                        <input type="text" id="unit-combo-input" autocomplete="off"
                            class="w-full bg-transparent border-0 pl-xl pr-xl py-2.5 font-body-md text-on-surface placeholder:text-on-surface-variant/60 focus:outline-none focus:ring-0 cursor-pointer"
                            placeholder="Cari atau pilih unit..." />
                        <div class="absolute inset-y-0 right-0 px-md flex items-center pointer-events-none text-on-surface-variant z-10 bg-surface-container-low">
                            <span class="material-symbols-outlined" id="unit-combo-arrow" style="font-size: 20px; transition: transform .2s;">expand_more</span>
                        </div>
                    </div>

                    <div id="unit-combo-panel"
                        class="hidden absolute z-30 mt-1 w-full max-h-60 overflow-y-auto rounded-lg border border-outline-variant bg-surface-container-lowest shadow-lg"
                        role="listbox"></div>
                </div>
            </div>

            <button class="w-full text-on-primary font-label-md text-label-md font-bold py-3 px-md rounded-lg shadow-lg mt-sm" type="submit" style="background: linear-gradient(135deg, #715d00 0%, #b89e3d 100%);">
                Kirim Link Reset Password
            </button>

            <a href="<?= site_url('login') ?>" class="block text-center font-label-sm text-label-sm text-on-surface-variant hover:text-primary mt-sm">Kembali ke Login</a>
        </form>
    </div>
</div>

<script>
(function () {
    var combo  = document.getElementById('unit-combo');
    if (!combo) return;
    var select = document.getElementById('id_bast');
    var input  = document.getElementById('unit-combo-input');
    var panel  = document.getElementById('unit-combo-panel');
    var arrow  = document.getElementById('unit-combo-arrow');

    var items = [];
    Array.prototype.forEach.call(select.options, function (opt) {
        if (opt.value === '') return;
        items.push({ value: opt.value, label: opt.text });
    });

    var activeIndex = -1;
    var visible = [];

    function open()  { panel.classList.remove('hidden'); arrow.style.transform = 'rotate(180deg)'; }
    function close() { panel.classList.add('hidden');    arrow.style.transform = 'rotate(0deg)'; activeIndex = -1; }
    function isOpen() { return !panel.classList.contains('hidden'); }

    function render(filter) {
        filter = (filter || '').toLowerCase().trim();
        panel.innerHTML = '';
        visible = items.filter(function (it) {
            return filter === '' || it.label.toLowerCase().indexOf(filter) !== -1;
        });
        if (visible.length === 0) {
            var empty = document.createElement('div');
            empty.className = 'px-md py-2.5 font-body-sm text-on-surface-variant';
            empty.textContent = 'Unit tidak ditemukan';
            panel.appendChild(empty);
            return;
        }
        visible.forEach(function (it, i) {
            var el = document.createElement('div');
            el.className = 'px-md py-2.5 font-body-md text-on-surface cursor-pointer hover:bg-surface-container-low' +
                (select.value === it.value ? ' bg-primary-container/20 font-medium' : '');
            el.setAttribute('role', 'option');
            el.dataset.index = i;
            el.textContent = it.label;
            el.addEventListener('mousedown', function (e) {
                e.preventDefault();
                choose(it);
            });
            panel.appendChild(el);
        });
    }

    function choose(it) {
        select.value = it.value;
        input.value = it.label;
        close();
    }

    function highlight(idx) {
        var nodes = panel.querySelectorAll('[role="option"]');
        nodes.forEach(function (n) { n.classList.remove('bg-surface-container-low'); });
        if (idx >= 0 && idx < nodes.length) {
            nodes[idx].classList.add('bg-surface-container-low');
            nodes[idx].scrollIntoView({ block: 'nearest' });
        }
    }

    function selectedLabel() {
        var it = items.filter(function (i) { return i.value === select.value; })[0];
        return it ? it.label : '';
    }

    input.addEventListener('focus', function () { render(input.value === selectedLabel() ? '' : input.value); open(); });
    input.addEventListener('input', function () { select.value = ''; activeIndex = -1; render(input.value); open(); });

    input.addEventListener('keydown', function (e) {
        if (!isOpen() && (e.key === 'ArrowDown' || e.key === 'ArrowUp')) { render(''); open(); return; }
        if (e.key === 'ArrowDown') { e.preventDefault(); activeIndex = Math.min(activeIndex + 1, visible.length - 1); highlight(activeIndex); }
        else if (e.key === 'ArrowUp') { e.preventDefault(); activeIndex = Math.max(activeIndex - 1, 0); highlight(activeIndex); }
        else if (e.key === 'Enter') { if (isOpen() && activeIndex >= 0) { e.preventDefault(); choose(visible[activeIndex]); } }
        else if (e.key === 'Escape') { close(); }
    });

    document.addEventListener('click', function (e) {
        if (!combo.contains(e.target)) {
            input.value = selectedLabel();
            close();
        }
    });

    select.form.addEventListener('submit', function (e) {
        if (!select.value) {
            e.preventDefault();
            input.value = '';
            input.focus();
            input.placeholder = 'Pilih unit terlebih dahulu';
            render(''); open();
        }
    });
})();
</script>
