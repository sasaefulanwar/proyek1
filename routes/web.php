<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReverseController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegisterApotekController;
use Illuminate\Support\Facades\Session;
use App\Models\Admin;
use App\Http\Controllers\kontakController;

//home "/"
Route::get('/', [HomeController::class, 'index'])->name('home');

// Artikel
Route::get('/artikel', [HomeController::class, 'Artikel'])->name('artikel');

//kontak 
Route::get('/kontak', [kontakController::class, 'index'])->name('layouts.kontak');
Route::post('/kontak/kirim', [kontakController::class, 'kirim'])->name('kontak.kirim');


//buat map
Route::get('/reverse', [ReverseController::class, 'reverse'])->name('reverse');

Route::get('/login', [AdminController::class, 'login'])->name('login');
Route::post('/login', [AdminController::class, 'authenticate'])->name('login.post');
Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

Route::get('/admin/admin', [AdminController::class, 'admin'])->name('admin.list');
Route::get('/admin/apotek', [AdminController::class, 'apotek'])->name('admin.apotek');
Route::get('/admin/artikel', [AdminController::class, 'artikel'])->name('admin.artikel');
Route::get('/admin/artikel/tambah', [AdminController::class, 'Tambahartikel'])->name('admin.artikel.tambah');
Route::post('/admin/artikel/store', [AdminController::class, 'storeArtikel'])->name('admin.artikel.store');

Route::get('/admin/artikel/edit/{id}', [AdminController::class, 'editArtikel'])->name('admin.artikel.edit');
Route::put('/admin/artikel/update/{id}', [AdminController::class, 'updateArtikel'])->name('admin.artikel.update');
Route::delete('/admin/artikel/delete/{id}', [AdminController::class, 'deleteArtikel'])->name('admin.artikel.delete');

//Admin apotek
Route::get('/admin/obat', [AdminController::class, 'obat'])->name('admin.obat');
Route::get('/admin/obat/tambah', [AdminController::class, 'tambahObat'])->name('admin.tambah');
Route::post('/admin/obat/store', [AdminController::class, 'storeObat'])->name('admin.store');
Route::delete('/admin/obat/{id}', [AdminController::class, 'deleteObat'])->name('admin.obat.delete');
Route::get('/admin/obat/edit/{id_obat}', [AdminController::class, 'editObat'])->name('admin.obat.edit');
Route::put('/admin/obat/update/{id_obat}', [AdminController::class, 'updateObat'])->name('admin.obat.update');
Route::get('/admin/profile', [AdminController::class, 'ProfileApotek'])->name('admin.profile');
Route::put('/admin/profile/update/{id_apotek}', [AdminController::class, 'updateApotek'])->name('apotek.update');
Route::get('/admin/laporan', [AdminController::class, 'Laporan'])->name('admin.laporan');


Route::get('/apotek/register', [RegisterApotekController::class, 'create'])->name('apotek.create');
Route::post('/apotek/register', [RegisterApotekController::class, 'store'])->name('apotek.store');
Route::get('/apotek/verifikasi/{id_apotek}', [AdminController::class, 'verifikasiApotek'])->name('apotek.verifikasi');
