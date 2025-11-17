<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OTPController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\GoalsController;
use App\Http\Middleware\Verification;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GrafikController;
use App\Http\Controllers\ControllerAnggaran;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\ForumKasController;
use App\Http\Controllers\ForumTransController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    if (!Auth::check()) {
        return redirect('/login');
    }
    return app(DashboardController::class)->index();
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

Route::get('/dashboard', function () {
    return view('Pages/Dashboard');
})->name('dashboard');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    // Dashboard utama (setelah login)
    Route::get('/dashboard', function () {
        return view('Pages/Dashboard');
    })->name('dashboard');
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->name('dashboard.index')
    ->middleware('auth');

    // 🔹 ROUTE TRANSAKSI (pakai bahasa Indonesia)
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('/transaksi/tambah', [TransaksiController::class, 'create'])->name('transaksi.create');
    Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::get('/transaksi/{transaksi}/edit', [TransaksiController::class, 'edit'])->name('transaksi.edit');
    Route::put('/transaksi/{transaksi}', [TransaksiController::class, 'update'])->name('transaksi.update');
    Route::delete('/transaksi/{transaksi}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');
    Route::get('/laporan/transaksi/export', [LaporanController::class, 'exportPdf'])->name('laporan.export.pdf');

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

    // 🔹 ROUTE grafik
    Route::get('/grafik', [GrafikController::class, 'index'])->name('grafik.index');

    // 🔹 ROUTE anggaran
    Route::resource('anggaran', ControllerAnggaran::class);

    // 🔹 ROUTE Forum
    Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
    Route::get('/forum/tambah', [ForumController::class, 'indexAdd'])->name('forum.add');
    Route::post('/forum', [ForumController::class, 'add'])->name('forum.store');
    Route::post('/forum/join', [ForumController::class, 'joinSubmit'])->name('forum.join.submit');
});

Route::middleware('auth', 'hakAkses')->group(function () {
    // ROUTE hanya anggota
    Route::get('/forum/kas/{slug}', [ForumKasController::class, 'index'])->name('forum.kas');
    Route::get('/forum/transaksi/{slug}', [ForumTransController::class, 'index'])->name('forum.trans');
});

Route::middleware('auth', 'hakAkses', 'isBendahara')->group(function () {
    // ROUTE hanya bendahara
    Route::get('/forum/kas/{slug}/tambah', [ForumKasController::class, 'indexAdd'])->name('tambah.kas.index');
    Route::post('/forum/kas/{slug}/tambah', [ForumKasController::class, 'add'])->name('tambah.kas');
    Route::get('/forum/kas/{slug}/{id}/edit', [ForumKasController::class, 'indexUpdate'])->name('edit.kas.index');
    Route::put('/forum/kas/{slug}/{id}/edit', [ForumKasController::class, 'update'])->name('edit.kas');
    Route::delete('/forum/kas/{slug}/{id}', [ForumKasController::class, 'delete'])
        ->name('kas.destroy');

    Route::get('/forum/transaksi/{slug}/tambah', [ForumTransController::class, 'indexAdd'])->name('tambah.trans.index');
    Route::post('/forum/transaksi/{slug}/tambah', [ForumTransController::class, 'add'])->name('tambah.trans');
    Route::get('/forum/transaksi/{slug}/{id}/edit', [ForumTransController::class, 'indexUpdate'])->name('edit.trans.index');
    Route::put('/forum/transaksi/{slug}/{id}/edit', [ForumTransController::class, 'update'])->name('edit.trans');
    Route::delete('/forum/transaksi/{slug}/{id}', [ForumTransController::class, 'delete'])
        ->name('forum.transaksi.destroy');

    // Forum - Ekspor Laporan PDF
    Route::get('/forum/{slug}/laporan', [LaporanController::class, 'forumPDF'])
        ->name('forum.laporan.export');
});
