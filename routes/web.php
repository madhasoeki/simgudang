<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard', ['title' => 'Dashboard']);
});

Route::get('/kelola-barang', function () {
    return view('kelola-barang', ['title' => 'Kelola Barang']);
});

Route::get('/barang-masuk', function () {
    return view('barang-masuk', ['title' => 'Catat Barang Masuk']);
});

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

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');