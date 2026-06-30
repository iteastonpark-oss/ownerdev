<style>
    /* ===== REMOVE DEFAULT SELECT ARROW ===== */
    select::-ms-expand { display: none; }
    select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        text-indent: 0.01px;
        text-overflow: '';
    }

    /* ===== ANIMATIONS ===== */
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(2deg); }
    }
    @keyframes float-delayed {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(-2deg); }
    }
    @keyframes pulse-glow {
        0%, 100% { opacity: 0.4; transform: scale(1); }
        50% { opacity: 0.7; transform: scale(1.05); }
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes gradient-shift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    .btn-spinner {
        width: 18px; height: 18px;
        border: 2px solid rgba(255,255,255,0.35);
        border-top-color: #fff;
        border-radius: 50%;
        animation: spin 0.7s linear infinite;
        flex-shrink: 0;
    }

    .animate-float { animation: float 6s ease-in-out infinite; }
    .animate-float-delayed { animation: float-delayed 7s ease-in-out 1s infinite; }
    .animate-pulse-glow { animation: pulse-glow 4s ease-in-out infinite; }
    .animate-gradient {
        background-size: 200% 200%;
        animation: gradient-shift 8s ease infinite;
    }
    .animate-slide-up {
        animation: slideUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
    }
    .stagger-1 { animation-delay: 0.1s; }
    .stagger-2 { animation-delay: 0.2s; }
    .stagger-3 { animation-delay: 0.3s; }
    .stagger-4 { animation-delay: 0.4s; }
    .stagger-5 { animation-delay: 0.5s; }
    .stagger-6 { animation-delay: 0.6s; }

    .input-focus-ring {
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .input-focus-ring:focus-within {
        border-color: #715d00;
        box-shadow: 0 0 0 3px rgba(113, 93, 0, 0.15);
    }

    .btn-primary {
        background: linear-gradient(135deg, #715d00 0%, #b89e3d 100%);
        background-size: 200% 200%;
        animation: gradient-shift 4s ease infinite;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 28px -8px rgba(113, 93, 0, 0.4);
    }
    .btn-primary:active {
        transform: translateY(0) scale(0.98);
    }

    .card-glow {
        box-shadow:
            0 4px 6px -2px rgba(0, 0, 0, 0.03),
            0 12px 16px -4px rgba(0, 0, 0, 0.04),
            0 24px 40px -8px rgba(113, 93, 0, 0.08);
    }
</style>

<!-- Main Content -->
<main class="min-h-screen flex relative bg-background">
    <!-- ===== DECORATIVE LEFT PANEL ===== -->
    <div class="hidden lg:flex lg:w-[480px] xl:w-[560px] relative flex-shrink-0 overflow-hidden bg-primary">
        <!-- Animated gradient overlay -->
        <div class="absolute inset-0 animate-gradient" style="background: linear-gradient(135deg, #715d00 0%, #554500 25%, #b89e3d 50%, #715d00 75%, #554500 100%); background-size: 200% 200%;"></div>

        <!-- Pattern overlay -->
        <div class="absolute inset-0 opacity-[0.08]" style="background-image: url('data:image/svg+xml,%3Csvg width=\'80\' height=\'80\' viewBox=\'0 0 80 80\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M50 50c0-5.523 4.477-10 10-10s10 4.477 10 10-4.477 10-10 10c0 5.523-4.477 10-10 10s-10-4.477-10-10 4.477-10 10-10z\'/%3E%3C/g%3E%3C/svg%3E'); background-size: 120px 120px;"></div>

        <!-- Floating decorative elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -bottom-8 -left-8 w-72 h-72 rounded-3xl bg-on-primary/5 backdrop-blur-sm border border-on-primary/10 animate-float"></div>
            <div class="absolute top-[15%] right-[-20px] w-48 h-48 rounded-2xl bg-on-primary/8 backdrop-blur-sm border border-on-primary/10 animate-float-delayed"></div>
            <div class="absolute top-[10%] left-[20%] w-20 h-20 rounded-xl bg-primary-fixed/20 animate-pulse-glow"></div>
            <div class="absolute bottom-[30%] right-[15%] w-40 h-40 rounded-full bg-on-primary/5 backdrop-blur-sm border border-on-primary/10 animate-float" style="animation-duration: 8s;"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 flex flex-col justify-between p-xl w-full">
            <!-- Brand top -->
            <div class="animate-slide-up stagger-1">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-on-primary/20 backdrop-blur-sm flex items-center justify-center border border-on-primary/20">
                        <span class="material-symbols-outlined text-on-primary" style="font-size: 28px;">apartment</span>
                    </div>
                    <div>
                        <h2 class="font-headline-sm text-headline-sm font-bold text-on-primary">Easton Park</h2>
                        <p class="font-label-sm text-label-sm text-on-primary/70">Residence Jatinangor</p>
                    </div>
                </div>
            </div>

            <!-- Middle hero text -->
            <div class="space-y-lg">
                <div class="animate-slide-up stagger-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-on-primary/15 text-on-primary text-label-sm font-label-sm backdrop-blur-sm border border-on-primary/10 mb-md">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></span>
                        Owner Portal
                    </span>
                </div>
                <div class="animate-slide-up stagger-3">
                    <h1 class="font-display-lg text-display-lg font-bold text-on-primary leading-tight">
                        Kelola<br>
                        <span class="text-primary-fixed">Unit Anda</span>
                    </h1>
                    <p class="font-body-lg text-body-lg text-on-primary/70 mt-md max-w-sm">
                        Akses informasi tagihan, pemakaian air, dan layanan lainnya dengan mudah.
                    </p>
                </div>
            </div>

            <!-- Bottom feature list -->
            <div class="animate-slide-up stagger-4 space-y-md">
                <div class="flex items-center gap-3 text-on-primary/80">
                    <div class="w-8 h-8 rounded-lg bg-on-primary/15 flex items-center justify-center backdrop-blur-sm">
                        <span class="material-symbols-outlined text-on-primary" style="font-size: 18px;">receipt_long</span>
                    </div>
                    <span class="font-label-md text-label-md">Cek tagihan bulanan</span>
                </div>
                <div class="flex items-center gap-3 text-on-primary/80">
                    <div class="w-8 h-8 rounded-lg bg-on-primary/15 flex items-center justify-center backdrop-blur-sm">
                        <span class="material-symbols-outlined text-on-primary" style="font-size: 18px;">water_drop</span>
                    </div>
                    <span class="font-label-md text-label-md">Pantau pemakaian air</span>
                </div>
                <div class="flex items-center gap-3 text-on-primary/80">
                    <div class="w-8 h-8 rounded-lg bg-on-primary/15 flex items-center justify-center backdrop-blur-sm">
                        <span class="material-symbols-outlined text-on-primary" style="font-size: 18px;">support_agent</span>
                    </div>
                    <span class="font-label-md text-label-md">Ajukan permintaan perbaikan</span>
                </div>
            </div>

            <!-- Footer -->
            <p class="font-label-sm text-label-sm text-on-primary/50 animate-slide-up stagger-5">
                &copy; 2024 Easton Park Residence Jatinangor
            </p>
        </div>
    </div>

    <!-- ===== RIGHT PANEL - LOGIN FORM ===== -->
    <div class="flex-1 flex justify-center relative px-margin-mobile py-lg">
        <!-- Background decoration -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-[-15%] right-[-10%] w-[50%] h-[50%] bg-primary-fixed/10 rounded-full blur-[100px] animate-pulse-glow"></div>
            <div class="absolute bottom-[-10%] left-[-5%] w-[40%] h-[40%] bg-secondary-container/30 rounded-full blur-[80px]"></div>
        </div>

        <!-- Login Card -->
        <div class="relative z-10 w-full max-w-md my-auto">
            <!-- Mobile Brand (visible on small screens) -->
            <div class="lg:hidden text-center mb-lg animate-slide-up">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-primary shadow-lg mb-md">
                    <span class="material-symbols-outlined text-on-primary" style="font-size: 36px;">apartment</span>
                </div>
                <h2 class="font-headline-md text-headline-md font-bold text-on-surface">Easton Park Residence</h2>
                <p class="font-body-md text-body-md text-on-surface-variant">Building Management System</p>
            </div>

            <div class="bg-surface-container-lowest rounded-2xl card-glow border border-outline-variant p-md animate-slide-up">
                <!-- Card Header -->
                <div class="text-center mb-md">
                    <div class="w-10 h-10 rounded-lg bg-primary-container/20 flex items-center justify-center mx-auto mb-sm">
                        <span class="material-symbols-outlined text-primary" style="font-size: 20px; font-variation-settings: 'FILL' 1;">lock</span>
                    </div>
                    <h1 class="font-headline-sm text-headline-sm font-bold text-on-surface">Selamat Datang</h1>
                    <p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">Masuk ke portal owner Anda</p>
                </div>

                <!-- Login Form -->
                <form method="post" action="<?= site_url('login/login_act') ?>" class="space-y-md">
                    <!-- WhatsApp Input -->
                    <div class="space-y-xs">
                        <label class="font-label-sm text-label-sm font-medium text-on-surface block" for="whatsapp">Nomor WhatsApp</label>
                        <div class="relative input-focus-ring rounded-lg border border-outline-variant bg-surface-container-low overflow-hidden">
                            <div class="absolute inset-y-0 left-0 pl-md flex items-center pointer-events-none text-on-surface-variant">
                                <span class="material-symbols-outlined" style="font-size: 20px;">chat</span>
                            </div>
                            <input class="w-full bg-transparent border-0 pl-xl pr-md py-2.5 font-body-md text-on-surface placeholder:text-on-surface-variant/60 focus:outline-none focus:ring-0" id="whatsapp" name="hp" placeholder="Contoh: 08123456789" type="tel" required />
                        </div>
                        <p class="font-label-sm text-label-sm text-on-surface-variant/70">Masukkan nomor WhatsApp yang terdaftar</p>
                    </div>

                    <!-- Unit Selection (searchable combobox) -->
                    <div class="space-y-xs" id="unit-combo">
                        <label class="font-label-sm text-label-sm font-medium text-on-surface block" for="unit-combo-input">Pilih Unit</label>

                        <!-- Hidden native select = the real form field (name=id_bast, required) -->
                        <div class="hidden">
                            <?= $this->dropdown_model->getDropdownUnitBast('id_bast',
                                '',
                                'id="id_bast" required'); ?>
                        </div>

                        <div class="relative">
                            <!-- Display / search trigger -->
                            <div class="relative input-focus-ring rounded-lg border border-outline-variant bg-surface-container-low overflow-hidden">
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

                            <!-- Options panel -->
                            <div id="unit-combo-panel"
                                class="hidden absolute z-30 mt-1 w-full max-h-60 overflow-y-auto rounded-lg border border-outline-variant bg-surface-container-lowest card-glow"
                                role="listbox"></div>
                        </div>
                    </div>

                    <!-- Login Button -->
                    <button id="btn-login" class="btn-primary w-full text-on-primary font-label-md text-label-md font-bold py-3 px-md rounded-lg shadow-lg active:scale-[0.98] flex items-center justify-center gap-base mt-sm" type="submit">
                        <span id="btn-icon" class="material-symbols-outlined" style="font-size: 20px;">login</span>
                        <span id="btn-text">Masuk</span>
                    </button>
                </form>

                <!-- Footer -->
                <div class="mt-md pt-md border-t border-outline-variant">
                    <p class="font-label-sm text-label-sm text-center text-on-surface-variant mb-xs">Butuh bantuan? Hubungi kami</p>
                    <div class="space-y-1.5">
                        <a class="flex items-center justify-center gap-1.5 text-primary hover:text-primary-container transition-colors font-body-md" href="https://wa.me/6282312122021">
                            <span class="material-symbols-outlined" style="font-size: 18px;">whatsapp</span>
                            082312122021
                        </a>
                        <a class="flex items-center justify-center gap-1.5 text-primary hover:text-primary-container transition-colors font-body-md" href="tel:0227780188">
                            <span class="material-symbols-outlined" style="font-size: 18px;">call</span>
                            0227780188
                        </a>
                        <a class="flex items-center justify-center gap-1.5 text-primary hover:text-primary-container transition-colors font-body-md" href="https://eprjatinangor.com" target="_blank">
                            <span class="material-symbols-outlined" style="font-size: 18px;">language</span>
                            eprjatinangor.com
                        </a>
                        <a class="flex items-center justify-center gap-1.5 text-primary hover:text-primary-container transition-colors font-body-md" href="mailto:info@eprjatinangor.com">
                            <span class="material-symbols-outlined" style="font-size: 18px;">mail</span>
                            info@eprjatinangor.com
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
(function () {
    var combo  = document.getElementById('unit-combo');
    if (!combo) return;
    var select = document.getElementById('id_bast');
    var input  = document.getElementById('unit-combo-input');
    var panel  = document.getElementById('unit-combo-panel');
    var arrow  = document.getElementById('unit-combo-arrow');

    // Build item list from native <select> options (skip placeholder value="")
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

    input.addEventListener('focus', function () { render(input.value === selectedLabel() ? '' : input.value); open(); });
    input.addEventListener('input', function () { select.value = ''; activeIndex = -1; render(input.value); open(); });

    input.addEventListener('keydown', function (e) {
        if (!isOpen() && (e.key === 'ArrowDown' || e.key === 'ArrowUp')) { render(''); open(); return; }
        if (e.key === 'ArrowDown') { e.preventDefault(); activeIndex = Math.min(activeIndex + 1, visible.length - 1); highlight(activeIndex); }
        else if (e.key === 'ArrowUp') { e.preventDefault(); activeIndex = Math.max(activeIndex - 1, 0); highlight(activeIndex); }
        else if (e.key === 'Enter') { if (isOpen() && activeIndex >= 0) { e.preventDefault(); choose(visible[activeIndex]); } }
        else if (e.key === 'Escape') { close(); }
    });

    function selectedLabel() {
        var it = items.filter(function (i) { return i.value === select.value; })[0];
        return it ? it.label : '';
    }

    // Close on outside click; restore selected label if input left dangling
    document.addEventListener('click', function (e) {
        if (!combo.contains(e.target)) {
            input.value = selectedLabel();
            close();
        }
    });

    // Block submit if no valid unit picked; show loading state on valid submit
    select.form.addEventListener('submit', function (e) {
        if (!select.value) {
            e.preventDefault();
            input.value = '';
            input.focus();
            input.placeholder = 'Pilih unit terlebih dahulu';
            render(''); open();
            return;
        }
        // Show loading state
        var btn  = document.getElementById('btn-login');
        var icon = document.getElementById('btn-icon');
        var txt  = document.getElementById('btn-text');
        var spinner = document.createElement('span');
        spinner.className = 'btn-spinner';
        icon.replaceWith(spinner);
        txt.textContent = 'Mengirim OTP…';
        btn.disabled = true;
        btn.style.opacity = '0.85';
        btn.style.cursor  = 'not-allowed';
    });
})();
</script>