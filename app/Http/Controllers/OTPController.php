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
    public function sendOtp(Request $request)
    {
        $user = Auth::user();

        // Hapus OTP lama
        UserOtp::where('id_users', $user->id)->delete();

        // Buat kode OTP
        $otp = random_int(100000, 999999);

        // Simpan ke database
        UserOtp::create([
            'id_users'   => $user->id,
            'otp_code'   => $otp,
            'purpose'    => 'register',
            'attempt_count' => 0,
            'expires_at' => now()->addMinutes(5),
            'resend_at'  => now(),
        ]);

        // Kirim email
        Mail::to($user->email)->send(new SendOtpMail($otp, $user));

        return redirect()->route('otp.verify.page')
            ->with('info', 'Kode OTP telah dikirim ke email Anda.');
    }

    public function index()
    {
        $user = Auth::user();
        $otp = UserOtp::where('id_users', $user->id)->latest()->first();

        $remaining = 0;
        $waitTime = 30; // detik

        if ($otp && $otp->resend_at) {
            // ✅ FIX: Hitung mundur yang benar (dari sekarang ke masa depan)
            $canResendAt = $otp->resend_at->addSeconds($waitTime);

            if (now()->lt($canResendAt)) {
                // Masih dalam periode tunggu
                $remaining = now()->diffInSeconds($canResendAt, false);
                if ($remaining < 0) $remaining = 0;
            }
        }

        return view('auth.otpverify', compact('remaining'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);

        $userId = Auth::id();
        $otpRecord = UserOtp::where('id_users', $userId)
            ->where('otp_code', $request->otp)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otpRecord) {
            $userOtp = UserOtp::where('id_users', $userId)->latest()->first();
            if ($userOtp) {
                $userOtp->increment('attempt_count');

                // ✅ FIX: Jangan ubah resend_at saat gagal verifikasi
                if ($userOtp->attempt_count >= 3) {
                    return back()->withErrors(['otp' => 'Terlalu banyak percobaan. Silakan kirim ulang OTP.']);
                }
            }
            return redirect()->route('otp.verify.page')
                ->withErrors(['otp' => 'Kode OTP salah atau sudah kadaluarsa.']);
        }

        // ✅ Cek batas percobaan
        if ($otpRecord->attempt_count >= 3) {
            return back()->withErrors(['otp' => 'Terlalu banyak percobaan. Silakan kirim ulang OTP.']);
        }

        // ✅ Jika OTP benar → update user jadi active
        $user = User::find($otpRecord->id_users);
        if ($user) {
            $user->update(['status' => 'active']);
        }

        // Hapus OTP setelah digunakan
        $otpRecord->delete();

        return redirect('/login')->with('success', 'Akun berhasil diverifikasi! Silakan login.');
    }

    public function resend()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect('/login')->withErrors(['email' => 'Silakan login untuk mengirim ulang OTP.']);
        }

        $latestOtp = UserOtp::where('id_users', $user->id)->latest()->first();
        $waitTime = 30;

        // 🔒 Cegah spam resend
        if ($latestOtp && $latestOtp->resend_at) {
            $canResendAt = $latestOtp->resend_at->addSeconds($waitTime);

            if (now()->lt($canResendAt)) {
                $remaining = now()->diffInSeconds($canResendAt, false);
                if ($remaining < 0) $remaining = 0;
                return back()->withErrors(['otp' => "Tunggu $remaining detik sebelum meminta OTP lagi."]);
            }
        }

        // 🔄 Buat OTP baru
        $otp = random_int(100000, 999999);

        UserOtp::updateOrCreate(
            ['id_users' => $user->id],
            [
                'otp_code' => $otp,
                'attempt_count' => 0,
                'expires_at' => now()->addMinutes(5),
                'resend_at' => now(), // Reset waktu resend
            ]
        );

        Mail::to($user->email)->send(new SendOtpMail($otp, $user));

        return redirect()->route('otp.verify.page')->with('success', 'Kode OTP baru telah dikirim ke email Anda.');
    }
}
