<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function indexRegister()
    {
        return view("auth.register");
    }

    public function register(Request $request)
    {
        // Validasi input
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

        $existingUser = User::where('email', $validatedData['email'])->first();

        if ($existingUser) {
            if ($existingUser->status === 'Active') {
                return back()->withErrors([
                    'email' => 'Email sudah digunakan. Silakan login.'
                ])->withInput();
            } else {
                // Belum aktif → arahkan ke OTP
                return redirect()->route('otp.send', ['id' => $existingUser->id])
                    ->with('info', 'Akun Anda belum aktif. OTP dikirim ulang ke email.');
            }
        }

        // Buat user baru
        $user = User::create([
            'nama' => $validatedData['nama'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        Auth::login($user);

        return redirect()->route('otp.send', ['id' => $user->id])
            ->with('success', 'Registrasi berhasil! Silakan verifikasi akun Anda.');
    }

    public function googleRedirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::updateOrCreate([
                    'nama' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make(uniqid()),
                    'status' => 'Active',
                ]);
            }

            Auth::login($user);
            return redirect('/');
        } catch (\Exception $e) {
            return redirect('/login')->withErrors('Gagal login dengan Google. Silakan coba lagi.');
        }
    }

    public function indexLogin()
    {
        return view("auth.Login");
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password harus diisi.',
        ]);

        $credentials = $request->only('email', 'password');
        Auth::attempt($credentials);

        if (Auth::check()) {
            return redirect('/');
        }

        return back()->withErrors(
            'Maaf email atau password Anda salah.'
        )->onlyInput('email');
    }

    public function sendOtp()
    {
        $user = Auth::user();
        return view('auth.otp-send', ['email' => $user->email]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
