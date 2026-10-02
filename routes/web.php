<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OpdController;
use App\Http\Controllers\NotifikasiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Utama Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Rute Pengecekan Keaktifan Website (Uptime HTTP Ping)
// Mengganti syncRss menjadi checkAllStatus agar sesuai dengan controller
Route::get('/cek-status', [DashboardController::class, 'checkAllStatus'])->name('dashboard.sync');

// CRUD Data OPD
Route::resource('opd', OpdController::class);

// Fitur Logout
Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/');
})->name('logout');

// API Notifikasi
Route::get('/api/notifikasi', [NotifikasiController::class, 'index'])->name('api.notifikasi.index');
Route::post('/api/notifikasi/mark-read', [NotifikasiController::class, 'markAsRead'])->name('api.notifikasi.markRead');

Route::get('/api/cek-status-json', [DashboardController::class, 'checkAllStatusJson'])->name('dinas.check-status-json');