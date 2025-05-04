<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\TransaksiMasukController;

Route::get('/', function () {
    return view('dashboard', ['title' => 'Dashboard']);
});

Route::get('/kelola-barang', [BarangController::class, 'index'])->name('barang.index');
Route::get('/kelola-barang/create', [BarangController::class, 'create'])->name('barang.create');
Route::post('/kelola-barang', [BarangController::class, 'store'])->name('barang.store');

Route::get('/transaksi-masuk', [TransaksiMasukController::class, 'index'])->name('transaksi-masuk.index');
Route::get('/transaksi-masuk/data', [TransaksiMasukController::class, 'data']);
Route::get('/transaksi-masuk/create', [TransaksiMasukController::class, 'create'])->name('transaksi-masuk.create');
Route::post('/transaksi-masuk', [TransaksiMasukController::class, 'store'])->name('transaksi-masuk.store');

Route::get('/barang-keluar', function () {
    return view('barang-keluar', ['title' => 'Catat Barang Keluar']);
});

Route::get('/stock-opname', function () {
    return view('stock-opname', ['title' => 'Stock Opname']);
});

Route::get('/data-miss', function () {
    return view('data-miss', ['title' => 'Data Miss']);
});

Route::get('/laporan-project', function () {
    return view('laporan-project', ['title' => 'Laporan Per Project']);
});

Route::get('/rekap-barang', function () {
    return view('rekap-barang-keluar', ['title' => 'Rekap Barang Keluar']);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');