<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OTPController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Verification;

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
