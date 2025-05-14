<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\OpnameController;
use App\Http\Controllers\ProjekController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransaksiMasukController;
use App\Http\Controllers\TransaksiKeluarController;

// Autentikasi (tanpa middleware)
Auth::routes(['register' => false]);

// Semua route di bawah ini membutuhkan login
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Kelola daftar barang
    Route::prefix('barang')->name('barang.')->group(function () {
        Route::get('/', [BarangController::class, 'index'])->name('index');
        Route::get('/create', [BarangController::class, 'create'])->name('create');
        Route::post('/', [BarangController::class, 'store'])->name('store');
        Route::get('/{kode}/edit', [BarangController::class, 'edit'])->name('edit');
        Route::put('/{kode}', [BarangController::class, 'update'])->name('update');
    });

    // Kelola daftar projek
    Route::prefix('projek')->name('projek.')->group(function () {
        Route::get('/', [ProjekController::class, 'index'])->name('index');
        Route::get('/create', [ProjekController::class, 'createProjek'])->name('create');
        Route::post('/', [ProjekController::class, 'storeProjek'])->name('store');
        Route::get('/{id}/edit', [ProjekController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProjekController::class, 'update'])->name('update');
        Route::get('/list', [ProjekController::class, 'list'])->name('list');
    });

    // Transaksi masuk
    Route::prefix('transaksi-masuk')->name('transaksi-masuk.')->group(function () {
        Route::get('/', [TransaksiMasukController::class, 'index'])->name('index');
        Route::get('/data', [TransaksiMasukController::class, 'data'])->name('data');
        Route::get('/create', [TransaksiMasukController::class, 'create'])->name('create');
        Route::post('/', [TransaksiMasukController::class, 'store'])->name('store');
    });

    // Transaksi keluar
    Route::prefix('transaksi-keluar')->name('transaksi-keluar.')->group(function () {
        Route::get('/', [TransaksiKeluarController::class, 'index'])->name('index');
        Route::get('/data', [TransaksiKeluarController::class, 'data'])->name('data');
        Route::get('/create', [TransaksiKeluarController::class, 'create'])->name('create');
        Route::post('/', [TransaksiKeluarController::class, 'store'])->name('store');
        Route::get('/harga-barang', [TransaksiKeluarController::class, 'hargaBarang'])->name('harga-barang');
    });

    // Grouping routes for Opname with prefix and name
    Route::prefix('opname')->name('opname.')->group(function () {
        Route::get('/', [OpnameController::class, 'index'])->name('index');
        Route::get('/data', [OpnameController::class, 'data'])->name('data');
        Route::post('/{id}/approve', [OpnameController::class, 'approve'])->name('approve');
        Route::get('/{id}/input-lapangan', [OpnameController::class, 'showInputForm'])->name('input-form');
        Route::put('/{id}/simpan-lapangan', [OpnameController::class, 'simpanLapangan'])->name('simpan-lapangan');
        Route::get('/miss', [OpnameController::class, 'missIndex'])->name('miss.index');
        Route::get('/miss/data', [OpnameController::class, 'dataMiss'])->name('miss.data');
    });

    // Grouping routes for Laporan and Rekap with prefix and name
    Route::prefix('laporan-project')->name('laporan-project.')->group(function () {
        Route::get('/', function () {
            return view('laporan-project', ['title' => 'Laporan Per Project']);
        })->name('index');
        Route::get('/data', [TransaksiKeluarController::class, 'laporanProjectData'])->name('data');
    });

    Route::prefix('rekap-projek')->name('rekap-projek.')->group(function () {
        Route::get('/', [RekapController::class, 'index'])->name('index');
        Route::get('/data', [RekapController::class, 'data'])->name('data');
        Route::put('/{id}/status', [RekapController::class, 'updateStatus'])->name('update-status');
    });

    // Grouping routes for User Management with middleware and prefix
    Route::middleware(['role:super-admin'])->prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::resource('/', UserController::class)->except(['show']);
        Route::post('/{user}/change-password', [UserController::class, 'changePassword'])->name('change-password');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
    });
});