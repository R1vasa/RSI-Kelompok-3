<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OTPController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\GoalsController;
use App\Http\Middleware\Verification;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return Auth::check() ? view('Pages/Dashboard') : redirect('/login');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'indexRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.create');
    Route::get('/login', [AuthController::class, 'indexLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'Login'])->name('login.create');
    Route::get('/auth-google-redirect', [AuthController::class, 'googleRedirect'])->name('google.redirect');
    Route::get('/auth-google-callback', [AuthController::class, 'googleCallback'])->name('google.callback');
});

Route::middleware('auth',)->group(function () {
    Route::get('/otp/send', [OtpController::class, 'sendOtp'])->name('otp.send');
    Route::get('/otp/verify', [OtpController::class, 'index'])->name('otp.verify.page');
    Route::post('/otp/verify', [OTPController::class, 'verifyOtp'])->name('otp.verify');
    Route::get('/otp/resend', [OtpController::class, 'resend'])->name('otp.resend');
});

Route::middleware(['auth', 'verification'])->group(function () {
    Route::get('/', function () {
        return view('Pages/Dashboard');
    })->name('dashboard');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    // Dashboard utama (setelah login)
    Route::get('/dashboard', function () {
        return view('Pages/Dashboard');
    })->name('dashboard');

    // 🔹 ROUTE TRANSAKSI (pakai bahasa Indonesia)
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('/transaksi/tambah', [TransaksiController::class, 'create'])->name('transaksi.create');
    Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::get('/transaksi/{transaksi}/edit', [TransaksiController::class, 'edit'])->name('transaksi.edit');
    Route::put('/transaksi/{transaksi}', [TransaksiController::class, 'update'])->name('transaksi.update');
    Route::delete('/transaksi/{transaksi}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');

    // 🔹 ROUTE goals (pakai bahasa Indonesia)
    Route::get('/goals', [GoalsController::class, 'index'])->name('goals.index');
    Route::get('/goals/tambah', [GoalsController::class, 'create'])->name('goals.create');
    Route::post('/goals', [GoalsController::class, 'store'])->name('goals.store');
    Route::get('/goals/{goals}/edit', [GoalsController::class, 'edit'])->name('goals.edit');
    Route::put('/goals/{goals}', [GoalsController::class, 'update'])->name('goals.update');
    Route::delete('/goals/{goals}', [GoalsController::class, 'destroy'])->name('goals.destroy');

    // 🔹 ROUTE setor tabungan
    Route::get('/setor/{id}/tambah', [GoalsController::class, 'setorcreate'])->name('setor.create');
    Route::post('/goals/{id}/setor', [GoalsController::class, 'setorstore'])->name('setor.store');
});
