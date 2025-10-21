<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>OTP Verification</title>
    @vite('resources/css/app.css')
</head>

<body class="flex items-center justify-center min-h-screen bg-gray-50">

    <div class="bg-white w-[360px] rounded-2xl shadow-md p-8 flex flex-col items-center text-center">
        <!-- Illustration -->
        <div class="w-40 mb-4">
            <img src="https://illustrations.popsy.co/violet/person-with-checklist.svg" alt="OTP Illustration">
        </div>

        <!-- Title -->
        <h2 class="text-xl font-semibold text-gray-800 mb-2">OTP Verification</h2>
        <p class="text-gray-500 text-sm mb-6">
            Masukkan OTP yang dikirim ke <span class="font-medium text-gray-700">{{ Auth::user()->email }}</span>
        </p>

        <form action="{{ route('otp.verify') }}" method="POST" id="otpForm">
            @csrf

            <!-- OTP Input -->
            <div class="flex justify-center space-x-4 mb-4">
                @for ($i = 0; $i < 6; $i++)
                    <input type="text" maxlength="1"
                        class="otp-input w-10 h-12 border-b-2 border-gray-300 text-center text-xl focus:outline-none focus:border-blue-500"
                        inputmode="numeric" pattern="[0-9]*" />
                @endfor
            </div>

            <!-- Hidden field untuk gabungkan semua OTP -->
            <input type="hidden" name="otp" id="otpField">

            <!-- Resend -->
            <p class="text-gray-500 text-sm mb-6">
                Tidak menerima kode?
                <a id="resendLink" href="{{ route('otp.resend') }}"
                    class="text-blue-600 font-medium {{ $remaining > 0 ? 'pointer-events-none opacity-50' : '' }}">
                    Kirim ulang
                    @if ($remaining > 0)
                        (<span id="countdown">{{ $remaining }}</span>s)
                    @endif
                </a>
            </p>

            @if ($errors->any())
                <p class="text-red-500 text-sm mb-2">{{ $errors->first() }}</p>
            @endif

            <!-- Button -->
            <button type="submit"
                class="bg-blue-600 text-white w-full py-3 rounded-xl font-medium hover:bg-blue-700 transition">
                Verifikasi
            </button>
        </form>
    </div>

</body>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('.otp-input');
        const otpField = document.getElementById('otpField');
        const resendLink = document.getElementById('resendLink');
        const countdownElement = document.getElementById('countdown');
        const otpForm = document.getElementById('otpForm');

        // Ambil nilai sisa waktu dari backend (Blade PHP)
        let remaining = parseInt(countdownElement ? countdownElement.textContent : 0);
        let countdownTimer = null; // 🟢 simpan id timer agar bisa dibersihkan

        // --- COUNTDOWN SYSTEM (from backend) ---
        function startCountdown(seconds) {
            if (!countdownElement) return;

            // 🧹 Hentikan timer lama sebelum membuat timer baru
            if (countdownTimer) {
                clearInterval(countdownTimer);
                countdownTimer = null;
            }

            resendLink.classList.add('pointer-events-none', 'opacity-50');
            countdownElement.textContent = seconds;

            countdownTimer = setInterval(() => {
                seconds--;
                countdownElement.textContent = seconds;

                if (seconds <= 0) {
                    clearInterval(countdownTimer);
                    countdownTimer = null;

                    resendLink.classList.remove('pointer-events-none', 'opacity-50');
                    resendLink.textContent = 'Kirim ulang';
                }
            }, 1000);
        }

        // Jalankan countdown dari backend (jika masih ada sisa waktu)
        if (remaining > 0) {
            startCountdown(remaining);
        } else {
            resendLink.classList.remove('pointer-events-none', 'opacity-50');
            resendLink.textContent = 'Kirim ulang';
        }

        // --- OTP INPUT HANDLING ---
        if (inputs.length > 0) inputs[0].focus();

        inputs.forEach((input, index) => {
            input.addEventListener('input', e => {
                const value = e.target.value.replace(/[^0-9]/g, ''); // hanya angka
                e.target.value = value;

                // Fokus otomatis ke input berikutnya
                if (value && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }

                updateHiddenOtp();

                // Auto-submit jika semua input terisi
                if (otpField.value.length === inputs.length) {
                    otpForm.submit();
                }
            });

            // Navigasi ke belakang saat tekan backspace
            input.addEventListener('keydown', e => {
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });

            // Paste langsung semua angka OTP
            input.addEventListener('paste', e => {
                e.preventDefault();
                const pasteData = (e.clipboardData || window.clipboardData).getData('text');
                const digits = pasteData.replace(/\D/g, '').split('');
                digits.forEach((digit, i) => {
                    if (inputs[i]) inputs[i].value = digit;
                });
                updateHiddenOtp();

                if (otpField.value.length === inputs.length) {
                    otpForm.submit();
                }
            });
        });

        // Gabungkan semua input ke hidden field
        function updateHiddenOtp() {
            otpField.value = Array.from(inputs).map(input => input.value).join('');
        }
    });
</script>

</html>
