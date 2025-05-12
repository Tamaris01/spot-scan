<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PenggunaParkirController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\KelolaKendaraanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardPengelolaController;
use App\Http\Controllers\DashboardPenggunaController;
use App\Http\Controllers\KonfirmasiPendaftaranController;
use App\Http\Controllers\MonitoringParkirController;
use App\Http\Controllers\LaporanParkirController;
use App\Http\Controllers\RiwayatParkirController;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rute untuk halaman login dan register
Route::middleware(['redirect.if.authenticated'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
});

// Logout route (dapat diakses oleh semua pengguna yang terautentikasi)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Routes for 'pengguna' users
Route::middleware(['auth:pengguna'])->group(function () {
    Route::get('/dashboard/pengguna', [DashboardPenggunaController::class, 'dashboard'])->name('pengguna.dashboard');

    // Profil pengguna
    Route::get('/profile/pengguna', [ProfileController::class, 'showProfile'])->name('pengguna.profile.show');
    Route::post('/profile/pengguna/update', [ProfileController::class, 'update'])->name('pengguna.profile.update');

    // Rute untuk melihat dan memperbarui kendaraan pengguna
    Route::get('/kendaraan-saya', [KendaraanController::class, 'showKendaraanUser'])->name('pengguna.kendaraan');
    Route::put('/kendaraan-saya/update/{plat_nomor}', [KendaraanController::class, 'update'])->name('pengguna.kendaraan.update');

    // Riwayat parkir pengguna
    Route::get('/riwayat-parkir', [RiwayatParkirController::class, 'riwayatParkir'])->name('pengguna.riwayat_parkir');
});

// Routes for 'pengelola' 
Route::middleware(['auth:pengelola'])->group(function () {
    Route::get('/api/dashboard', [DashboardPengelolaController::class, 'getDashboardData'])->name('api.dashboard');

    Route::get('/dashboard/pengelola', [DashboardPengelolaController::class, 'dashboard'])->name('pengelola.dashboard');
    Route::get('dashboard/pengelola/chart1', [DashboardPengelolaController::class, 'chart1Data'])->name('pengelola.dashboard.chart1.data');
    Route::get('dashboard/pengelola/chart2', [DashboardPengelolaController::class, 'chart2Data'])->name('pengelola.dashboard.chart2.data');
    Route::get('dashboard/pengelola/chart3', [DashboardPengelolaController::class, 'chart3Data'])->name('pengelola.dashboard.chart3.data');
    Route::get('dashboard/pengelola/chart4', [DashboardPengelolaController::class, 'chart4Data'])->name('pengelola.dashboard.chart4.data');

    // Profil pengelola
    Route::get('/profile/pengelola', [ProfileController::class, 'showProfile'])->name('pengelola.profile.show');
    Route::post('/profile/pengelola/update', [ProfileController::class, 'update'])->name('pengelola.profile.update');


    // Route untuk menampilkan semua data monitoring parkir
    Route::get('/monitoring', [MonitoringParkirController::class, 'index'])->name('pengelola.monitoring.index');
    Route::get('/monitoring/search', [MonitoringParkirController::class, 'search'])->name('pengelola.monitoring.search');
    Route::post('/monitoring/scan', [MonitoringParkirController::class, 'scanQRCode']);

    // Route untuk menampilkan laporan parkir
    Route::get('/laporan-parkir', [LaporanParkirController::class, 'index'])->name('pengelola.laporan_parkir.index');
    Route::get('/laporan-parkir search', [LaporanParkirController::class, 'search'])->name('pengelola.laporan_parkir.search');
    Route::get('/laporan-parkir/unduh', [LaporanParkirController::class, 'unduhLaporanParkir'])->name('laporan_parkir.unduh');

    // CRUD untuk pengguna parkir
    Route::get('/kelola-pengguna', [PenggunaParkirController::class, 'index'])->name('pengelola.kelola_pengguna.index');
    Route::get('/kelola-pengguna/search', [PenggunaParkirController::class, 'search'])->name('pengelola.kelola_pengguna.search');
    Route::get('/kelola-pengguna/tambah', [PenggunaParkirController::class, 'create'])->name('pengelola.kelola_pengguna.create');
    Route::post('/kelola-pengguna/store', [PenggunaParkirController::class, 'store'])->name('pengelola.kelola_pengguna.store');
    Route::get('/kelola-pengguna/edit/{id_pengguna}', [PenggunaParkirController::class, 'edit'])->name('pengelola.kelola_pengguna.edit');
    Route::put('/kelola-pengguna/update/{id_pengguna}', [PenggunaParkirController::class, 'update'])->name('pengelola.kelola_pengguna.update');
    Route::delete('/kelola-pengguna/delete/{id_pengguna}', [PenggunaParkirController::class, 'destroy'])->name('pengelola.kelola_pengguna.destroy');

    // CRUD untuk kendaraan
    Route::get('/kelola-kendaraan', [KelolaKendaraanController::class, 'index'])->name('pengelola.kelola_kendaraan.index');
    Route::get('/find-pengguna', [KelolaKendaraanController::class, 'findPenggunaById'])->name('find.pengguna');
    Route::post('/kelola-kendaraan/store', [KelolaKendaraanController::class, 'store'])->name('pengelola.kelola_kendaraan.store');
    Route::get('/kelola-kendaraan/search', [KelolaKendaraanController::class, 'search'])->name('pengelola.kelola_kendaraan.search');
    Route::get('/kelola-kendaraan/edit/{plat_nomor}', [KelolaKendaraanController::class, 'edit'])->name('pengelola.kelola_kendaraan.edit');
    Route::put('/kelola-kendaraan/update/{plat_nomor}', [KelolaKendaraanController::class, 'update'])->name('pengelola.kelola_kendaraan.update');
    Route::delete('/kelola-kendaraan/delete/{plat_nomor}', [KelolaKendaraanController::class, 'destroy'])->name('pengelola.kelola_kendaraan.delete');




    //Konfirmasi Pendaftaran
    Route::get('/konfirmasi', [KonfirmasiPendaftaranController::class, 'index'])->name('pengelola.konfirmasi_pendaftaran');
    Route::get('/pengelola/konfirmasi-pendaftaran/search', [KonfirmasiPendaftaranController::class, 'search'])->name('pengelola.konfirmasi_pendaftaran.search');
    Route::post('/pengelola/konfirmasi/terima/{id_pengguna}', [KonfirmasiPendaftaranController::class, 'terima'])->name('pengelola.konfirmasi_pendaftaran.terima');
    Route::post('/pengelola/konfirmasi/tolak/{id_pengguna}', [KonfirmasiPendaftaranController::class, 'tolak'])->name('pengelola.konfirmasi_pendaftaran.tolak');
});
