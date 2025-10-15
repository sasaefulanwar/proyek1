<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReverseController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Session;
use App\Models\Admin;


//home "/"
Route::get('/', [HomeController::class, 'index'])->name('home');


//buat map
Route::get('/reverse', [ReverseController::class, 'reverse'])->name('reverse');

Route::get('/login', [AdminController::class, 'login'])->name('login');
Route::post('/login', [AdminController::class, 'authenticate'])->name('login.post');
Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

Route::get('/admin/dashboard', function () {
    // 🔒 Cek apakah sudah login
    if (!Session::has('role')) {
        return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
    }

    $role = Session::get('role');
    if (!in_array($role, ['admin', 'admin_apotek'])) {
        abort(403, 'Akses ditolak');
    }

    // 📊 Ambil total admin_apotek
    $totalAdminApotek = Admin::where('role', 'admin_apotek')->count();

    $title = "Admin Dashboard";
    $adminName = Session::get('admin_name');

    // ✅ Kirim ke view
    return view('admin.dashboard', compact('title', 'adminName', 'totalAdminApotek'));
})->name('admin.dashboard');

Route::get('/admin/admin', [AdminController::class, 'admin'])->name('admin.list');
