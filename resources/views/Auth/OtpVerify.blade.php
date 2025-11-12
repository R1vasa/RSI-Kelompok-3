<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>OTP Verification</title>

    <!-- Import Tailwind CSS dari Laravel Vite -->
    @vite('resources/css/app.css')
</head>

<!-- Layout utama: tampilan terpusat dengan latar belakang abu muda -->

<body class="flex items-center justify-center min-h-screen bg-gray-50">

    <!-- Kartu utama untuk verifikasi OTP -->
    <div class="bg-white w-[360px] rounded-2xl shadow-md p-8 flex flex-col items-center text-center">

        <!-- Ilustrasi bagian atas -->
        <div class="w-40 mb-4">
            <img src="https://illustrations.popsy.co/violet/person-with-checklist.svg" alt="OTP Illustration">
        </div>

        <!-- Judul halaman -->
        <h2 class="text-xl font-semibold text-gray-800 mb-2">OTP Verification</h2>

        <!-- Informasi email penerima OTP -->
        <p class="text-gray-500 text-sm mb-6">
            Masukkan OTP yang dikirim ke
            <span class="font-medium text-gray-700">{{ Auth::user()->email }}</span>
        </p>

        <!-- Form verifikasi OTP -->
        <form action="{{ route('otp.verify') }}" method="POST" id="otpForm">
            @csrf

            <!-- Input 6 digit OTP (dibuat dengan loop Blade) -->
            <div class="flex justify-center space-x-4 mb-4">
                @for ($i = 0; $i < 6; $i++)
                    <input type="text" maxlength="1"
                        class="otp-input w-10 h-12 border-b-2 border-gray-300 text-center text-xl 
                               focus:outline-none focus:border-blue-500"
                        inputmode="numeric" pattern="[0-9]*" />
                @endfor
            </div>

            <!-- Field tersembunyi untuk menggabungkan semua input digit menjadi satu string -->
            <input type="hidden" name="otp" id="otpField">

            <!-- Tautan kirim ulang OTP -->
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

            <!-- Pesan error jika OTP salah -->
            @if ($errors->any())
                <p class="text-red-500 text-sm mb-2">{{ $errors->first() }}</p>
            @endif

            <!-- Tombol submit -->
            <button type="submit"
                class="bg-blue-600 text-white w-full py-3 rounded-xl font-medium hover:bg-blue-700 transition">
                Verifikasi
            </button>
        </form>
    </div>

</body>

<!-- Script untuk logika OTP dan sistem countdown -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ambil elemen-elemen penting dari halaman
        const inputs = document.querySelectorAll('.otp-input');
        const otpField = document.getElementById('otpField');
        const resendLink = document.getElementById('resendLink');
        const countdownElement = document.getElementById('countdown');
        const otpForm = document.getElementById('otpForm');

        // Ambil nilai sisa waktu dari backend (Blade PHP)
        let remaining = parseInt(countdownElement ? countdownElement.textContent : 0);
        let countdownTimer = null; // Menyimpan ID timer agar dapat dihapus

        /* -------------------------------------------------
           🔁 SISTEM COUNTDOWN PENGIRIMAN ULANG OTP
        ------------------------------------------------- */
        function startCountdown(seconds) {
            if (!countdownElement) return;

            // Hentikan timer sebelumnya jika ada
            if (countdownTimer) {
                clearInterval(countdownTimer);
                countdownTimer = null;
            }

            // Nonaktifkan tombol kirim ulang sementara
            resendLink.classList.add('pointer-events-none', 'opacity-50');
            countdownElement.textContent = seconds;

            // Jalankan timer setiap detik
            countdownTimer = setInterval(() => {
                seconds--;
                countdownElement.textContent = seconds;

                // Jika waktu habis, aktifkan kembali tombol kirim ulang
                if (seconds <= 0) {
                    clearInterval(countdownTimer);
                    countdownTimer = null;
                    resendLink.classList.remove('pointer-events-none', 'opacity-50');
                    resendLink.textContent = 'Kirim ulang';
                }
            }, 1000);
        }

        // Jalankan countdown jika masih ada waktu dari server
        if (remaining > 0) {
            startCountdown(remaining);
        } else {
            resendLink.classList.remove('pointer-events-none', 'opacity-50');
            resendLink.textContent = 'Kirim ulang';
        }

        /* -------------------------------------------------
           🔢 PENANGANAN INPUT OTP (6 DIGIT)
        ------------------------------------------------- */

        // Fokuskan ke input pertama saat halaman dimuat
        if (inputs.length > 0) inputs[0].focus();

        inputs.forEach((input, index) => {
            // Ketika user mengetik
            input.addEventListener('input', e => {
                const value = e.target.value.replace(/[^0-9]/g, ''); // Hanya angka
                e.target.value = value;

                // Otomatis pindah ke kolom berikutnya
                if (value && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }

                updateHiddenOtp();

                // Jika semua kolom sudah terisi → submit otomatis
                if (otpField.value.length === inputs.length) {
                    otpForm.submit();
                }
            });

            // Navigasi ke belakang saat backspace ditekan
            input.addEventListener('keydown', e => {
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });

            // Paste langsung seluruh kode OTP (misalnya dari clipboard)
            input.addEventListener('paste', e => {
                e.preventDefault();
                const pasteData = (e.clipboardData || window.clipboardData).getData('text');
                const digits = pasteData.replace(/\D/g, '').split('');
                digits.forEach((digit, i) => {
                    if (inputs[i]) inputs[i].value = digit;
                });
                updateHiddenOtp();

                // Submit otomatis jika semua input sudah lengkap
                if (otpField.value.length === inputs.length) {
                    otpForm.submit();
                }
            });
        });

        // Gabungkan semua nilai input menjadi satu string OTP
        function updateHiddenOtp() {
            otpField.value = Array.from(inputs).map(input => input.value).join('');
        }
    });
</script>

</html>
