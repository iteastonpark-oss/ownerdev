<?php
/**
 * OTP Verification Page
 * Variables: $otp_expired_ts (Unix timestamp), passed from Auth::otp()
 */
$expired_ts = isset($otp_expired_ts) ? (int) $otp_expired_ts : 0;
?>
<style>
    body { background: #eee; }

    .otp-input {
        display: inline-block;
        width: 54px;
        height: 60px;
        text-align: center;
        font-size: 1.5rem;
        font-weight: 700;
        border: 2px solid #ced4da;
        border-radius: 10px;
        margin: 0 4px;
        transition: border-color .2s;
        outline: none;
    }
    .otp-input:focus { border-color: #5e72e4; box-shadow: 0 0 0 3px rgba(94,114,228,.2); }
    .otp-input.disabled { background: #f5f5f5; color: #aaa; pointer-events: none; }

    #countdown-ring {
        width: 72px; height: 72px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; font-weight: 700;
        margin: 0 auto 8px;
        background: conic-gradient(#5e72e4 var(--prog, 100%), #e9ecef 0);
        transition: background .4s;
    }
    #countdown-inner {
        width: 54px; height: 54px; border-radius: 50%;
        background: white;
        display: flex; align-items: center; justify-content: center;
        font-size: .95rem; font-weight: 700; color: #333;
    }

    #countdown-ring.expired { background: conic-gradient(#f5365c 100%, #e9ecef 0) !important; }
    #countdown-inner.expired { color: #f5365c; }

    #resend-btn {
        font-size: .85rem;
        padding: 8px 20px;
        border-radius: 20px;
    }

    .verify-btn { border-radius: 8px; font-size: 1rem; letter-spacing: .5px; }

    @keyframes spin { to { transform: rotate(360deg); } }
    .btn-spinner {
        display: inline-block;
        width: 16px; height: 16px;
        border: 2px solid rgba(255,255,255,.5);
        border-top-color: #fff;
        border-radius: 50%;
        animation: spin .7s linear infinite;
        vertical-align: middle;
        margin-right: 6px;
    }
</style>

<div class="card bg-gradient-gray-dark">
    <div class="card-body">
        <div class="main-content mt--9">

            <div class="container">
                <div class="header-body text-center">
                    <div class="row justify-content-center">
                        <div class="col-xl-5 col-lg-6 col-md-8 px-5">
                            <h1 class="text-white">Verifikasi OTP</h1>
                            <p class="text-lead text-white">Masukkan kode 4 digit yang dikirim via WhatsApp</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container mt--8">
                <div class="row justify-content-center">
                    <div class="col-lg-5 col-md-7">
                        <form id="otp-form" action="<?php echo site_url('login/login_otp'); ?>" method="post">
                            <div class="card bg-secondary border-0 mb-0">
                                <div class="card-body px-lg-5 py-lg-4 text-center">

                                    <!-- Countdown ring -->
                                    <div id="countdown-ring">
                                        <div id="countdown-inner" id="countdown-text">5:00</div>
                                    </div>
                                    <p id="countdown-label" class="text-muted small mb-3">OTP berlaku selama 5 menit</p>

                                    <!-- OTP inputs -->
                                    <div id="otp-inputs" class="mb-3">
                                        <input class="otp-input" type="text" name="otp[]" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                                        <input class="otp-input" type="text" name="otp[]" maxlength="1" inputmode="numeric">
                                        <input class="otp-input" type="text" name="otp[]" maxlength="1" inputmode="numeric">
                                        <input class="otp-input" type="text" name="otp[]" maxlength="1" inputmode="numeric">
                                    </div>

                                    <!-- Expired message (hidden initially) -->
                                    <div id="expired-msg" class="alert alert-danger d-none" role="alert">
                                        OTP sudah kadaluarsa. Klik "Kirim Ulang OTP" untuk mendapat kode baru.
                                    </div>

                                    <!-- Verify button -->
                                    <button id="verify-btn" type="submit" class="btn btn-primary btn-block verify-btn mb-3">
                                        Verifikasi
                                    </button>

                                    <!-- Resend button -->
                                    <button id="resend-btn" type="button" class="btn btn-outline-default" disabled>
                                        Kirim Ulang OTP
                                    </button>
                                    <p id="resend-hint" class="text-muted small mt-2 mb-0">
                                        Tombol aktif setelah OTP kadaluarsa
                                    </p>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
(function () {
    const EXPIRED_TS = <?php echo $expired_ts; ?>; // Unix timestamp (seconds)
    const TOTAL_SECS = 300; // 5 minutes

    const ring        = document.getElementById('countdown-ring');
    const inner       = document.getElementById('countdown-inner');
    const label       = document.getElementById('countdown-label');
    const expiredMsg  = document.getElementById('expired-msg');
    const verifyBtn   = document.getElementById('verify-btn');
    const resendBtn   = document.getElementById('resend-btn');
    const otpInputs   = document.querySelectorAll('.otp-input');
    const otpForm     = document.getElementById('otp-form');

    function secondsLeft() {
        return Math.max(0, EXPIRED_TS - Math.floor(Date.now() / 1000));
    }

    function pad(n) { return String(n).padStart(2, '0'); }

    function updateRing(secs) {
        const frac = secs / TOTAL_SECS;
        const deg  = Math.round(frac * 360);
        ring.style.background = 'conic-gradient(#5e72e4 ' + deg + 'deg, #e9ecef 0)';
        inner.textContent = pad(Math.floor(secs / 60)) + ':' + pad(secs % 60);
    }

    function markExpired() {
        ring.classList.add('expired');
        inner.classList.add('expired');
        inner.textContent = '0:00';
        label.textContent = 'OTP sudah kadaluarsa';
        expiredMsg.classList.remove('d-none');
        verifyBtn.disabled = true;
        otpInputs.forEach(function(i) { i.classList.add('disabled'); i.disabled = true; });
        resendBtn.disabled = false;
        resendBtn.classList.remove('btn-outline-default');
        resendBtn.classList.add('btn-warning');
    }

    // --- Countdown ticker ---
    var ticker = setInterval(function () {
        var secs = secondsLeft();
        updateRing(secs);
        if (secs <= 0) {
            clearInterval(ticker);
            markExpired();
        }
    }, 1000);

    // Initial render
    var initSecs = secondsLeft();
    if (initSecs <= 0) {
        updateRing(0);
        markExpired();
    } else {
        updateRing(initSecs);
    }

    // --- OTP input auto-advance & auto-submit ---
    otpInputs.forEach(function (input, idx) {
        input.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '').slice(-1);
            if (this.value && idx < otpInputs.length - 1) {
                otpInputs[idx + 1].focus();
            }
            // Auto-submit when 4th digit filled
            if (idx === otpInputs.length - 1 && this.value && !verifyBtn.disabled) {
                otpForm.submit();
            }
        });
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Backspace' && !this.value && idx > 0) {
                otpInputs[idx - 1].focus();
            }
        });
    });

    // Focus first input on load
    if (otpInputs.length && !otpInputs[0].disabled) {
        otpInputs[0].focus();
    }

    // --- Verify button spinner ---
    otpForm.addEventListener('submit', function () {
        verifyBtn.disabled = true;
        verifyBtn.innerHTML = '<span class="btn-spinner"></span>Memverifikasi…';
    });

    // --- Resend OTP ---
    resendBtn.addEventListener('click', function () {
        resendBtn.disabled = true;
        resendBtn.innerHTML = '<span class="btn-spinner"></span>Mengirim…';
        resendBtn.classList.remove('btn-warning');

        fetch('<?php echo site_url("login/resend_otp"); ?>', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin'
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                // Reset page with new expiry
                expiredMsg.classList.add('d-none');
                verifyBtn.disabled = false;
                verifyBtn.innerHTML = 'Verifikasi';
                resendBtn.innerHTML = 'Kirim Ulang OTP';
                resendBtn.classList.add('btn-outline-default');
                resendBtn.disabled = true;
                ring.classList.remove('expired');
                inner.classList.remove('expired');
                label.textContent = 'OTP berlaku selama 5 menit';
                otpInputs.forEach(function (i) {
                    i.classList.remove('disabled');
                    i.disabled = false;
                    i.value = '';
                });
                otpInputs[0].focus();

                // Restart countdown with new expired_at
                var newExpiredTs = data.expired_at;
                clearInterval(ticker);
                ticker = setInterval(function () {
                    var secs = Math.max(0, newExpiredTs - Math.floor(Date.now() / 1000));
                    updateRing(secs);
                    if (secs <= 0) { clearInterval(ticker); markExpired(); }
                }, 1000);
                updateRing(Math.max(0, newExpiredTs - Math.floor(Date.now() / 1000)));
            } else {
                alert(data.message || 'Gagal mengirim OTP. Silakan coba lagi.');
                resendBtn.innerHTML = 'Kirim Ulang OTP';
                resendBtn.classList.add('btn-warning');
                resendBtn.disabled = false;
            }
        })
        .catch(function () {
            alert('Koneksi bermasalah. Silakan coba lagi.');
            resendBtn.innerHTML = 'Kirim Ulang OTP';
            resendBtn.classList.add('btn-warning');
            resendBtn.disabled = false;
        });
    });
})();
</script>
