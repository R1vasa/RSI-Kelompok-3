<?php

namespace App\Http\Controllers;

use App\Mail\SendOtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\UserOtp;
use Carbon\Carbon;

class OTPController extends Controller
{
    /**
     * Mengirim OTP (kode verifikasi) ke email user yang sedang login.
     * 
     * Fitur:
     * - Menghapus OTP lama agar hanya ada satu OTP aktif per user.
     * - Membuat OTP baru yang berlaku selama 5 menit.
     * - Mengirimkan OTP ke email menggunakan Mailable `SendOtpMail`.
     * - Mengarahkan user ke halaman verifikasi OTP.
     */
    public function sendOtp(Request $request)
    {
        $user = Auth::user();

        // Hapus OTP lama agar tidak ada duplikasi kode aktif
        UserOtp::where('id_users', $user->id)->delete();

        // Buat kode OTP acak 6 digit
        $otp = random_int(100000, 999999);

        // Simpan OTP baru ke database
        UserOtp::create([
            'id_users'      => $user->id,
            'otp_code'      => $otp,
            'purpose'       => 'register',
            'attempt_count' => 0, // jumlah percobaan verifikasi
            'expires_at'    => now()->addMinutes(5), // masa berlaku 5 menit
            'resend_at'     => now(), // waktu pengiriman
        ]);

        // Kirim OTP ke email user
        Mail::to($user->email)->send(new SendOtpMail($otp, $user));

        // Redirect ke halaman verifikasi OTP
        return redirect()->route('otp.verify.page')
            ->with('info', 'Kode OTP telah dikirim ke email Anda.');
    }

    /**
     * Menampilkan halaman verifikasi OTP.
     * 
     * Fitur:
     * - Menentukan waktu tunggu sebelum user bisa meminta OTP baru lagi (30 detik).
     * - Menghitung mundur waktu sisa berdasarkan field `resend_at`.
     */
    public function index()
    {
        $user = Auth::user();
        $otp = UserOtp::where('id_users', $user->id)->latest()->first();

        $remaining = 0;
        $waitTime = 30; // jeda antar pengiriman OTP (detik)

        if ($otp && $otp->resend_at) {
            // Hitung waktu user bisa meminta ulang OTP berikutnya
            $canResendAt = $otp->resend_at->addSeconds($waitTime);

            // Jika waktu tunggu belum habis
            if (now()->lt($canResendAt)) {
                $remaining = now()->diffInSeconds($canResendAt, false);
                if ($remaining < 0) $remaining = 0;
            }
        }

        return view('auth.otpverify', compact('remaining'));
    }

    /**
     * Memverifikasi kode OTP yang dimasukkan user.
     * 
     * Langkah:
     * 1. Validasi input kode OTP.
     * 2. Cek apakah OTP cocok dan masih berlaku.
     * 3. Batasi jumlah percobaan maksimal 3 kali.
     * 4. Jika benar → aktifkan akun user dan hapus OTP.
     */
    public function verifyOtp(Request $request)
    {
        // Pastikan OTP terdiri dari 6 digit angka
        $request->validate(['otp' => 'required|digits:6']);

        $userId = Auth::id();

        // Cek apakah OTP cocok dan belum kedaluwarsa
        $otpRecord = UserOtp::where('id_users', $userId)
            ->where('otp_code', $request->otp)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otpRecord) {
            // Jika tidak cocok, tambahkan attempt_count (jumlah percobaan)
            $userOtp = UserOtp::where('id_users', $userId)->latest()->first();
            if ($userOtp) {
                $userOtp->increment('attempt_count');

                // Jika sudah 3 kali gagal, minta user untuk kirim ulang OTP
                if ($userOtp->attempt_count >= 3) {
                    return back()->withErrors(['otp' => 'Terlalu banyak percobaan. Silakan kirim ulang OTP.']);
                }
            }

            return redirect()->route('otp.verify.page')
                ->withErrors(['otp' => 'Kode OTP salah atau sudah kadaluarsa.']);
        }

        // Cegah verifikasi jika user sudah melebihi batas percobaan
        if ($otpRecord->attempt_count >= 3) {
            return back()->withErrors(['otp' => 'Terlalu banyak percobaan. Silakan kirim ulang OTP.']);
        }

        // Jika OTP benar → ubah status user jadi aktif
        $user = User::find($otpRecord->id_users);
        if ($user) {
            $user->update(['status' => 'active']);
        }

        // Hapus OTP setelah berhasil digunakan
        $otpRecord->delete();

        return redirect('/login')->with('success', 'Akun berhasil diverifikasi! Silakan login.');
    }

    /**
     * Mengirim ulang OTP baru ke email user.
     * 
     * Fitur:
     * - Cegah spam dengan delay 30 detik antar pengiriman.
     * - Generate OTP baru dan reset percobaan verifikasi.
     * - Kirim ulang ke email user.
     */
    public function resend()
    {
        $user = Auth::user();

        // Pastikan user sudah login
        if (!$user) {
            return redirect('/login')->withErrors(['email' => 'Silakan login untuk mengirim ulang OTP.']);
        }

        $latestOtp = UserOtp::where('id_users', $user->id)->latest()->first();
        $waitTime = 30; // waktu tunggu 30 detik sebelum bisa resend

        // 🔒 Cegah spam pengiriman OTP
        if ($latestOtp && $latestOtp->resend_at) {
            $canResendAt = $latestOtp->resend_at->addSeconds($waitTime);

            if (now()->lt($canResendAt)) {
                $remaining = now()->diffInSeconds($canResendAt, false);
                if ($remaining < 0) $remaining = 0;
                return back()->withErrors(['otp' => "Tunggu $remaining detik sebelum meminta OTP lagi."]);
            }
        }

        // Buat OTP baru
        $otp = random_int(100000, 999999);

        // Update atau buat ulang OTP untuk user
        UserOtp::updateOrCreate(
            ['id_users' => $user->id],
            [
                'otp_code'      => $otp,
                'attempt_count' => 0,              // reset percobaan
                'expires_at'    => now()->addMinutes(5), // masa aktif 5 menit
                'resend_at'     => now(),          // waktu dikirim ulang
            ]
        );

        // Kirim OTP baru ke email
        Mail::to($user->email)->send(new SendOtpMail($otp, $user));

        return redirect()->route('otp.verify.page')->with('success', 'Kode OTP baru telah dikirim ke email Anda.');
    }
}
