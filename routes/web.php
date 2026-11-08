<?php

use App\Http\Controllers\AlatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\TeknisiController;
use Illuminate\Support\Facades\Route;

/*
 * |--------------------------------------------------------------------------
 * | Web Routes
 * |--------------------------------------------------------------------------
 */

// ===== BEBAS AKSES (tanpa login) =====
Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// ===== WAJIB LOGIN =====
Route::middleware('ceklogin')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Data Master
    Route::resource('teknisi', TeknisiController::class);
    Route::resource('alat', AlatController::class);

    // Peminjaman
    Route::resource('peminjaman', PeminjamanController::class);
    // Route::put('/peminjaman/kembalikan/{id_detail}', [PeminjamanController::class, 'kembalikan'])
    //     ->name('peminjaman.kembalikan');
});
