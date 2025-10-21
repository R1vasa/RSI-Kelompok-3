<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\GoalsController;

Route::get('/', function () {
    return Auth::check() ? view('Pages/Dashboard') : redirect('/login');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'indexRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.create');
    Route::get('/login', [AuthController::class, 'indexLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'Login'])->name('login.create');
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