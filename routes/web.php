<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestBookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;

/*
 Web Routes
*/

// 1. HALAMAN PUBLIC (Buku Tamu Umum)
Route::get('/', [GuestBookController::class, 'index'])->name('bukutamu.index');
Route::post('/buku-tamu', [GuestBookController::class, 'store'])->name('bukutamu.store');

// 2. AUTENTIKASI (Login & Logout)
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 3. AREA ADMIN / DASHBOARD (Harus Login)
Route::middleware('auth')->group(function () {
    // Dashboard & PDF Export
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin/laporan/export-pdf', [DashboardController::class, 'exportPdf'])->name('laporan.exportPdf');

    // CRUD Tamu Admin (Tambah, Edit, Hapus)
    Route::post('/admin/tamu', [DashboardController::class, 'store'])->name('tamu.store');
    Route::put('/admin/tamu/{id}', [DashboardController::class, 'update'])->name('tamu.update');
    Route::delete('/admin/tamu/{id}', [DashboardController::class, 'destroy'])->name('tamu.destroy');
});

// 4. RUTE TESTING / DEBUGGING (Opsional)
Route::get('/cek-user', function () {
    return \App\Models\User::all();
});