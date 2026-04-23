<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WargaController;
use App\Http\Controllers\PerpindahanController;
use App\Http\Controllers\PilahSampahController;
use App\Http\Controllers\IuranSampahController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin,user'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Fitur yang bisa diakses Admin & User
    Route::resource('warga', WargaController::class)->except(['show', 'destroy']);
    Route::resource('perpindahan', PerpindahanController::class)->except(['show', 'destroy']);
    Route::resource('pilah-sampah', PilahSampahController::class)->except(['show', 'destroy']);
    Route::resource('iuran-sampah', IuranSampahController::class)->except(['show', 'destroy']);
    Route::get('wilayah', [WilayahController::class, 'index'])->name('wilayah.index');

    // Fitur KHUSUS Admin
    Route::middleware(['role:admin'])->group(function () {
        Route::delete('warga/{warga}', [WargaController::class, 'destroy'])->name('warga.destroy');
        Route::delete('perpindahan/{perpindahan}', [PerpindahanController::class, 'destroy'])->name('perpindahan.destroy');
        Route::delete('pilah-sampah/{pilah_sampah}', [PilahSampahController::class, 'destroy'])->name('pilah-sampah.destroy');
        Route::delete('iuran-sampah/{iuran_sampah}', [IuranSampahController::class, 'destroy'])->name('iuran-sampah.destroy');
        
        Route::resource('wilayah', WilayahController::class)->except(['show', 'index']);
        
        Route::post('/perpindahan/{perpindahan}/approve', [PerpindahanController::class, 'approve'])->name('perpindahan.approve');
        Route::post('/perpindahan/{perpindahan}/reject', [PerpindahanController::class, 'reject'])->name('perpindahan.reject');
    });
});
