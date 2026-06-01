<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\CatinController;
use App\Http\Controllers\PendampingController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\JadwalController;
// use App\Http\Controllers\KuisController;
use App\Http\Controllers\UjianController;
use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AbsensiController;
use App\Http\Controllers\Admin\KuisController; 
use App\Http\Controllers\ReminderController; // Ditambahkan untuk fitur notifikasi H-7

// --- LANDING PAGE ---
Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');

// PUBLIC ROUTE FOR SERTIFIKAT APP
Route::get('/sertifikat/public/download/{id}/{person?}', [SertifikatController::class, 'download']);
Route::get('/sertifikat/public/stream/{id}/{person?}', [SertifikatController::class, 'stream']);

// --- ADMIN ROUTES (URL tetap pakai /admin/) ---
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.process');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware(['auth', 'IsAdmin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Resource Materi tetap seperti biasa
        Route::resource('materi', MateriController::class);
        Route::post('/materi/upload-file', [MateriController::class, 'uploadFile'])->name('materi.uploadFile');
        Route::get('/materi/{id}/video', [MateriController::class, 'serveVideo'])->name('materi.serveVideo');
        Route::get('/materi/{id}/download', [MateriController::class, 'downloadVideo'])->name('materi.downloadVideo');

        // FIX ERROR: admin.materi.kuis.manage (Sekarang ke MateriController)
        Route::get('/materi/kuis/{id}', [MateriController::class, 'manageKuis'])->name('materi.kuis.manage');
        Route::get('/materi/{id}/logs', [MateriController::class, 'showLogs'])->name('materi.logs');
        Route::get('admin/catin/{id}', [CatinController::class, 'show'])->name('admin.catin.show');

        // RUTE KUIS (Semua arahkan ke MateriController)
        Route::prefix('kuis')->name('kuis.')->group(function () {
            Route::get('/{id}', [MateriController::class, 'manageKuis'])->name('index');
            Route::post('/store-soal', [MateriController::class, 'storeSoal'])->name('storeSoal');
            Route::put('/update-soal/{id}', [MateriController::class, 'updateSoal'])->name('updateSoal');
            Route::delete('/soal-delete/{id}', [MateriController::class, 'destroySoal'])->name('destroySoal');
            Route::post('/update-inline/{id}', [MateriController::class, 'updateInline'])->name('updateInline');
            Route::post('/update-urutan', [MateriController::class, 'updateUrutan'])->name('updateUrutan');
            Route::get('/logs/{id}', [MateriController::class, 'showKuisLogs'])->name('logs');
            Route::get('/logs/{id}/json', [MateriController::class, 'apiKuisLogs'])->name('logs.json');
        });

        // Master Data
        Route::resource('catin', CatinController::class);
        Route::resource('pendamping', PendampingController::class);
        
        // Routes untuk check unique di Pendamping (AJAX)
        Route::prefix('pendamping')->name('pendamping.')->group(function () {
            Route::post('/check-nip', [PendampingController::class, 'checkNip'])->name('checkNip');
            Route::post('/check-email', [PendampingController::class, 'checkEmail'])->name('checkEmail');
            Route::post('/check-whatsapp', [PendampingController::class, 'checkWhatsapp'])->name('checkWhatsapp');
        });
        
        Route::resource('jadwal', JadwalController::class);
        
        // Rute khusus untuk tombol pengingat H-7
        // Rute khusus untuk tombol pengingat H-7 (Global)
        Route::post('/jadwal/kirim-reminder-h7', [ReminderController::class, 'sendH7Reminder'])->name('jadwal.reminder');

        // Bagian Ujian & Sertifikat
        Route::prefix('ujian')->name('ujian.')->group(function () {
            Route::get('/', [UjianController::class, 'index'])->name('index'); 
            Route::get('/create', [UjianController::class, 'create'])->name('create');
            Route::get('/soal', [UjianController::class, 'indexSoal'])->name('soal');
            Route::post('/soal', [UjianController::class, 'storeSoal'])->name('soal.store');
            Route::put('/soal/{id}', [UjianController::class, 'updateSoal'])->name('soal.update');
            Route::delete('/soal/{id}', [UjianController::class, 'destroySoal'])->name('soal.destroy');
            Route::get('/export-pdf', [UjianController::class, 'exportPdf'])->name('export.pdf');
            Route::get('/export-excel', [UjianController::class, 'exportExcel'])->name('export.excel');
            
            Route::post('/', [UjianController::class, 'store'])->name('store');
            Route::get('/{id}', [UjianController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [UjianController::class, 'edit'])->name('edit');
            Route::put('/{id}', [UjianController::class, 'update'])->name('update');
            Route::delete('/{id}', [UjianController::class, 'destroy'])->name('destroy');
            Route::post('/reset/{id}', [UjianController::class, 'resetUjian'])->name('reset');
        });

        Route::prefix('sertifikat')->name('sertifikat.')->group(function () {
            Route::get('/', [SertifikatController::class, 'index'])->name('index');
            
            // PERBAIKAN RUTE CETAK: Cukup tulis path turunannya dan controller yang benar
            Route::get('/cetak/{id}/{person}', [SertifikatController::class, 'stream'])->name('cetak');
            Route::get('/download/{id}/{person}', [SertifikatController::class, 'download'])->name('download');
            Route::get('/preview/{id}/{person}', [SertifikatController::class, 'preview'])->name('preview');
        });

        Route::get('/settings', [AdminAuthController::class, 'profile'])->name('settings');
        Route::post('/profile/update', [AdminAuthController::class, 'updateProfile'])->name('profile.update');
        Route::resource('pengumuman', PengumumanController::class);

        // LAPORAN
        Route::prefix('report')->name('report.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/export', [ReportController::class, 'export'])->name('export');
        });

        // ==========================================
        // RUTE ABSENSI WEB ADMIN (SUDAH DIPERBARUI)
        // ==========================================
        Route::get('/absensi/{jadwal_id}', [AbsensiController::class, 'show'])->name('jadwal.presensi');
        Route::post('/jadwal/{jadwal_id}/absensi-manual', [AbsensiController::class, 'storeManual'])->name('presensi.storeManual');
        Route::delete('/absensi/{id}', [AbsensiController::class, 'destroy'])->name('presensi.destroy');
    });
});

Route::get('/materi-display/{id}', function ($id) {
    $materi = \App\Models\Materi::findOrFail($id);
    $path = $materi->file; 

    if (!Storage::disk('public')->exists($path)) {
        abort(404);
    }

    $file = Storage::disk('public')->get($path);
    $type = Storage::disk('public')->mimeType($path);

    return response($file, 200)->header('Content-Type', $type);
})->name('materi.display');