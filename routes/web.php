<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IuranSampahController;
use App\Http\Controllers\KesehatanWargaController;
use App\Http\Controllers\PerpindahanController;
use App\Http\Controllers\PilahSampahController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WargaController;
use App\Http\Controllers\WilayahController;
use Illuminate\Support\Facades\Route;

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
    Route::get('/dashboard/statistik.xlsx', [DashboardController::class, 'exportMonthlyExcel'])->name('dashboard.export');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Fitur yang bisa diakses Admin & User
    Route::resource('warga', WargaController::class)->except(['show', 'destroy']);
    Route::get('warga/{warga}/kesehatan', [KesehatanWargaController::class, 'index'])->name('warga.kesehatan.index');
    Route::get('warga/{warga}/kesehatan/create', [KesehatanWargaController::class, 'create'])->name('warga.kesehatan.create');
    Route::post('warga/{warga}/kesehatan', [KesehatanWargaController::class, 'store'])->name('warga.kesehatan.store');
    Route::get('warga/{warga}/kesehatan/{kesehatan}/edit', [KesehatanWargaController::class, 'edit'])->name('warga.kesehatan.edit');
    Route::put('warga/{warga}/kesehatan/{kesehatan}', [KesehatanWargaController::class, 'update'])->name('warga.kesehatan.update');
    Route::resource('perpindahan', PerpindahanController::class)->except(['show', 'destroy']);
    Route::resource('pilah-sampah', PilahSampahController::class)->except(['show', 'destroy']);
    Route::resource('iuran-sampah', IuranSampahController::class)->except(['show', 'destroy']);
    Route::get('wilayah', [WilayahController::class, 'index'])->name('wilayah.index');

    // Fitur KHUSUS Admin
    Route::middleware(['role:admin'])->group(function () {
        Route::delete('warga/{warga}', [WargaController::class, 'destroy'])->name('warga.destroy');
        Route::delete('warga/{warga}/kesehatan/{kesehatan}', [KesehatanWargaController::class, 'destroy'])->name('warga.kesehatan.destroy');
        Route::delete('perpindahan/{perpindahan}', [PerpindahanController::class, 'destroy'])->name('perpindahan.destroy');
        Route::delete('pilah-sampah/{pilah_sampah}', [PilahSampahController::class, 'destroy'])->name('pilah-sampah.destroy');
        Route::delete('iuran-sampah/{iuran_sampah}', [IuranSampahController::class, 'destroy'])->name('iuran-sampah.destroy');

        Route::resource('wilayah', WilayahController::class)->except(['show', 'index']);

        Route::post('/perpindahan/{perpindahan}/approve', [PerpindahanController::class, 'approve'])->name('perpindahan.approve');
        Route::post('/perpindahan/{perpindahan}/reject', [PerpindahanController::class, 'reject'])->name('perpindahan.reject');
    });
});
