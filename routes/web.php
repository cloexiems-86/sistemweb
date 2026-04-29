<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\CatinController;
use App\Http\Controllers\PendampingController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\JadwalController;
// use App\Http\Controllers\KuisController;
use App\Http\Controllers\UjianController;
use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AbsensiController;
use App\Http\Controllers\Admin\KuisController; 

// --- LANDING PAGE ---
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Tambahkan di baris setelah landing page
Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');

// --- ADMIN ROUTES (URL tetap pakai /admin/) ---
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.process');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware(['auth', 'IsAdmin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // ... di dalam Route::middleware(['auth', 'IsAdmin'])->group(function () {

        // Resource Materi tetap seperti biasa
    Route::resource('materi', MateriController::class);

    // FIX ERROR: admin.materi.kuis.manage (Sekarang ke MateriController)
    // Di Controller kamu namanya 'manageKuis'
    Route::get('/materi/kuis/{id}', [MateriController::class, 'manageKuis'])->name('materi.kuis.manage');

    // FIX ERROR: admin.materi.logs
    Route::get('/materi/{id}/logs', [MateriController::class, 'showLogs'])->name('materi.logs');

    Route::get('admin/catin/{id}', [CatinController::class, 'show'])->name('admin.catin.show');


// RUTE KUIS (Semua arahkan ke MateriController)
    Route::prefix('kuis')->name('kuis.')->group(function () {
        // Karena kamu tidak punya function index() khusus kuis yang berdiri sendiri, 
        // biasanya rute 'index' ini diarahkan kembali ke manageKuis atau list materi
        Route::get('/{id}', [MateriController::class, 'manageKuis'])->name('index');
        
        Route::post('/store-soal', [MateriController::class, 'storeSoal'])->name('storeSoal');
        Route::put('/update-soal/{id}', [MateriController::class, 'updateSoal'])->name('updateSoal');
        Route::delete('/soal-delete/{id}', [MateriController::class, 'destroySoal'])->name('destroySoal');
        
        Route::post('/update-inline/{id}', [MateriController::class, 'updateInline'])->name('updateInline');
        Route::post('/update-urutan', [MateriController::class, 'updateUrutan'])->name('updateUrutan');
        
        // Rute untuk logs kuis
        Route::get('/logs/{id}', [MateriController::class, 'showKuisLogs'])->name('logs');
    });
// ...

        // Master Data
        Route::resource('catin', CatinController::class);
        Route::resource('pendamping', PendampingController::class);
        Route::resource('materi', MateriController::class);
        Route::resource('jadwal', JadwalController::class);



        // Bagian Ujian & Sertifikat
        Route::prefix('ujian')->name('ujian.')->group(function () {
            Route::get('/', [UjianController::class, 'index'])->name('index'); 
            Route::get('/create', [UjianController::class, 'create'])->name('create'); // FIX image_67ed5b
            Route::get('/soal', [UjianController::class, 'indexSoal'])->name('soal');
            Route::post('/soal', [UjianController::class, 'storeSoal'])->name('soal.store');
            Route::delete('/soal/{id}', [UjianController::class, 'destroySoal'])->name('soal.destroy');
            Route::post('/reset/{id}', [UjianController::class, 'resetUjian'])->name('reset');
        });

        Route::prefix('sertifikat')->name('sertifikat.')->group(function () {
            Route::get('/', [SertifikatController::class, 'index'])->name('index');
            Route::get('/download/{id}', [SertifikatController::class, 'download'])->name('download');
            Route::get('/preview/{id}', [SertifikatController::class, 'preview'])->name('preview');
        });

        Route::get('/settings', [AdminAuthController::class, 'profile'])->name('settings');
        Route::post('/profile/update', [AdminAuthController::class, 'updateProfile'])->name('profile.update');

                // Tambahkan di dalam group admin
        Route::resource('pengumuman', PengumumanController::class);

// Di dalam web.php
// Cukup tulis 'jadwal.presensi' karena otomatis jadi 'admin.jadwal.presensi'
        Route::get('/absensi/{jadwal_id}', [AbsensiController::class, 'show'])->name('jadwal.presensi');

// Begitu juga yang ini
        Route::post('/absensi/update-status/{id}', [AbsensiController::class, 'updateStatus'])->name('presensi.update');
    });
});