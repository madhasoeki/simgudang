<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\OpnameController;
use App\Http\Controllers\TransaksiMasukController;
use App\Http\Controllers\TransaksiKeluarController;

Route::get('/', function () {
    return view('dashboard', ['title' => 'Dashboard']);
});

// Tampilkan stok barang dan kelola daftar barang
Route::get('/kelola-barang', [BarangController::class, 'index'])->name('barang.index');
Route::get('/kelola-barang/create', [BarangController::class, 'create'])->name('barang.create');
Route::post('/kelola-barang', [BarangController::class, 'store'])->name('barang.store');

// Tampilkan daftar barang masuk dan tambahkan daftar barang masuk
Route::get('/transaksi-masuk', [TransaksiMasukController::class, 'index'])->name('transaksi-masuk.index');
Route::get('/transaksi-masuk/data', [TransaksiMasukController::class, 'data']);
Route::get('/transaksi-masuk/create', [TransaksiMasukController::class, 'create'])->name('transaksi-masuk.create');
Route::post('/transaksi-masuk', [TransaksiMasukController::class, 'store'])->name('transaksi-masuk.store');

// Transaksi Keluar
Route::get('/transaksi-keluar', [TransaksiKeluarController::class, 'index'])->name('transaksi-keluar.index');
Route::get('/transaksi-keluar/data', [TransaksiKeluarController::class, 'data'])->name('transaksi-keluar.data');
Route::get('/transaksi-keluar/create', [TransaksiKeluarController::class, 'create'])->name('transaksi-keluar.create');
Route::post('/transaksi-keluar', [TransaksiKeluarController::class, 'store'])->name('transaksi-keluar.store');


Route::get('/stock-opname', [OpnameController::class, 'index'])->name('stock-opname');
Route::get('/stock-opname/data', [OpnameController::class, 'data'])->name('stock-opname.data');
Route::post('/stock-opname/{id}/approve', [OpnameController::class, 'approve'])->name('stock-opname.approve');
Route::get('/stock-opname/{id}/input-lapangan', [OpnameController::class, 'showInputForm'])->name('stock-opname.input-form');
Route::put('/stock-opname/{id}/simpan-lapangan', [OpnameController::class, 'simpanLapangan'])->name('stock-opname.simpan-lapangan');

// Route::get('/stock-opname', function () {
//     return view('stock-opname', ['title' => 'Stock Opname']);
// });

Route::get('/data-miss', function () {
    return view('data-miss', ['title' => 'Data Miss']);
});

Route::get('/laporan-project', function () {
    return view('laporan-project', ['title' => 'Laporan Per Project']);
});

Route::get('/rekap-projek', function () {
    return view('rekap-barang-keluar', ['title' => 'Rekap Barang Keluar']);
});
Route::get('/projek/create', [RekapController::class, 'createProjek'])->name('projek.create');
Route::post('/projek/store', [RekapController::class, 'storeProjek'])->name('projek.store');
Route::get('/rekap-projek/data', [RekapController::class, 'data'])->name('rekap-projek.data');
Route::put('/rekap-projek/{id}/status', [RekapController::class, 'updateStatus'])->name('rekap-projek.update-status');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');