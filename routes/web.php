<?php

use Illuminate\Support\Facades\Route;
// BARIS INI WAJIB ADA: Memberitahu Laravel lokasi Controller kamu
use App\Http\Controllers\AdminAuthController;

Route::get('/', function () {
    return view('welcome');
});

// Route untuk menampilkan halaman login
Route::get('/login-admin', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');

// Tambahkan ini juga supaya saat tombol "Masuk" diklik tidak error 404
Route::post('/login-admin', [AdminAuthController::class, 'login'])->name('login');

// Halaman Dashboard Admin
Route::get('/admin/dashboard', function () {
    // Karena file ada di folder views/auth/admin-dashboard.blade.php
    return view('auth.admin-dashboard'); 
})->name('admin.dashboard');