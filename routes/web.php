<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ModulController;
use App\Http\Controllers\SponsorController;

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// routes/web.php

Route::post('/upload-image', [ModulController::class, 'uploadImage'])->name('upload.image');
// Protected routes
Route::middleware('auth.anggota')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('jabatan', JabatanController::class);
    Route::resource('divisi', DivisiController::class);
    Route::resource('anggota', AnggotaController::class)->parameters(['anggota' => 'anggota']);
    Route::resource('news', NewsController::class);
    Route::resource('modul', ModulController::class);
    Route::resource('sponsor', SponsorController::class);
});
