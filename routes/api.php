<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\CatinApiController;
use App\Http\Controllers\Api\PendampingApiController;

// Public: Login Mobile
Route::post('/login', [AuthApiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    
    // API Khusus Catin (Flutter)
    Route::prefix('catin')->group(function () {
        Route::get('/profile', [CatinApiController::class, 'profile']);
        Route::post('/update-profile', [CatinApiController::class, 'updateProfile']);
        Route::get('/materi', [CatinApiController::class, 'getMateri']);
        Route::post('/absensi', [CatinApiController::class, 'absensi']);
        Route::get('/progres', [CatinApiController::class, 'checkProgres']); // Syarat skor > 70
    });

    // API Khusus Pendamping (Flutter)
    Route::prefix('pendamping')->group(function () {
        Route::get('/catin-list', [PendampingApiController::class, 'index']);
        Route::post('/feedback', [PendampingApiController::class, 'storeFeedback']);
    });

    Route::post('/logout', [AuthApiController::class, 'logout']);
});