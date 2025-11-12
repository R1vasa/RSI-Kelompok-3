<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman registrasi.
     */
    public function indexRegister()
    {
        return view("auth.register");
    }

    /**
     * Proses registrasi user baru.
     * - Melakukan validasi input.
     * - Mengecek apakah email sudah terdaftar.
     * - Jika belum, membuat akun baru.
     * - Langsung login dan arahkan ke proses verifikasi OTP.
     */
    public function register(Request $request)
    {
        // Validasi input user
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ], [
            'nama.required' => 'Nama harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        // Cek apakah email sudah digunakan
        $existingUser = User::where('email', $validatedData['email'])->first();

        if ($existingUser) {
            // Jika akun sudah aktif → tampilkan pesan error
            if ($existingUser->status === 'Active') {
                return back()->withErrors([
                    'email' => 'Email sudah digunakan. Silakan login.'
                ])->withInput();
            } else {
                // Jika akun belum aktif → arahkan ke halaman OTP
                return redirect()->route('otp.send', ['id' => $existingUser->id])
                    ->with('info', 'Akun Anda belum aktif. OTP dikirim ulang ke email.');
            }
        }

        // Jika belum ada user dengan email tersebut → buat user baru
        $user = User::create([
            'nama' => $validatedData['nama'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']), // Enkripsi password
        ]);

        // Login otomatis setelah registrasi
        Auth::login($user);

        // Arahkan ke halaman OTP untuk verifikasi
        return redirect()->route('otp.send', ['id' => $user->id])
            ->with('success', 'Registrasi berhasil! Silakan verifikasi akun Anda.');
    }

    /**
     * Redirect ke halaman login Google menggunakan Socialite.
     * - Menambahkan parameter 'prompt=select_account' agar pengguna bisa memilih akun Google.
     */
    public function googleRedirect()
    {
        // Dapatkan URL redirect awal
        $redirect = Socialite::driver('google')->redirect();

        // Ambil URL target
        $url = $redirect->getTargetUrl();

        // Tambahkan parameter agar pengguna bisa memilih akun
        $url .= '&prompt=select_account';

        // Redirect ke URL Google
        return redirect()->away($url);
    }

    /**
     * Callback setelah pengguna login dengan Google.
     * - Mendapatkan data user dari Google.
     * - Jika belum ada di database → buat akun baru.
     * - Jika sudah ada → langsung login.
     */
    public function googleCallback()
    {
        try {
            // Ambil data user dari Google
            $googleUser = Socialite::driver('google')->user();

            // Cari user berdasarkan email
            $user = User::where('email', $googleUser->getEmail())->first();

            // Jika belum ada → buat user baru dengan status aktif
            if (!$user) {
                $user = User::updateOrCreate([
                    'nama' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make(uniqid()), // Password acak
                    'status' => 'Active',
                ]);
            }

            // Login user
            Auth::login($user);

            // Arahkan ke halaman utama
            return redirect('/');
        } catch (\Exception $e) {
            // Jika gagal login Google
            return redirect('/login')->withErrors('Gagal login dengan Google. Silakan coba lagi.');
        }
    }

    /**
     * Menampilkan halaman login.
     */
    public function indexLogin()
    {
        return view("auth.Login");
    }

    /**
     * Proses login user.
     * - Validasi email dan password.
     * - Jika cocok → login dan redirect ke halaman utama.
     * - Jika tidak cocok → tampilkan pesan error.
     */
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password harus diisi.',
        ]);

        // Ambil data login
        $credentials = $request->only('email', 'password');

        // Coba login menggunakan data tersebut
        Auth::attempt($credentials);

        // Jika berhasil login
        if (Auth::check()) {
            return redirect('/');
        }

        // Jika gagal login
        return back()->withErrors(
            'Maaf email atau password Anda salah.'
        )->onlyInput('email');
    }

    /**
     * Menampilkan halaman pengiriman OTP.
     * (Biasanya dipanggil setelah registrasi untuk verifikasi akun.)
     */
    public function sendOtp()
    {
        $user = Auth::user();
        return view('auth.otp-send', ['email' => $user->email]);
    }

    /**
     * Logout user dari sistem.
     * - Hapus sesi.
     * - Regenerasi token CSRF.
     * - Redirect ke halaman login.
     */
    public function logout(Request $request)
    {
        Auth::logout(); // Keluar dari sesi login
        $request->session()->invalidate(); // Hapus semua data sesi
        $request->session()->regenerateToken(); // Buat token baru untuk keamanan
        return redirect('/login'); // Arahkan ke halaman login
    }
}
