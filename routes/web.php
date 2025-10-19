<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
