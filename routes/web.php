<?php

use Illuminate\Support\Facades\Route;
// BARIS INI WAJIB ADA: Memberitahu Laravel lokasi Controller kamu
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\CatinController; // Tambahkan ini
use App\Http\Controllers\PendampingController; // Tambahkan ini

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


// Route Data Catin (CRUD)
Route::get('/admin/data-catin', [CatinController::class, 'index'])->name('admin.catin.index');

// TAMBAHKAN BARIS INI: Untuk menampilkan halaman form tambah
Route::get('/admin/data-catin/tambah', [CatinController::class, 'create'])->name('admin.catin.create');

// Route untuk Data Pendamping
Route::get('/admin/data-pendamping', function () {
    // Ganti 'auth.data-pendamping' dengan nama file blade yang kamu buat nanti
    return view('auth.data-pendamping'); 
})->name('admin.pendamping.index');