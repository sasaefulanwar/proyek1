<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    // tampilkan form login
    public function login()
    {
        return view('admin.login');
    }



    // proses login
    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $admin = Admin::where('username', $request->username)->first();

        if (! $admin) {
            return back()->with('error', 'Akun tidak ditemukan.');
        }

        // Untuk login uji coba tanpa bcrypt
        if ($request->password !== $admin->password) {
            return back()->with('error', 'Password salah.');
        }

        // Simpan session
        Session::put('admin_id', $admin->id);
        Session::put('role', $admin->role);
        Session::put('admin_name', $admin->username);

        return redirect()->route('admin.dashboard')->with('success', 'Login berhasil.');
    }

    public function dashboard()
    {
        // Pastikan login
        if (!Session::has('role')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $role = Session::get('role');
        if (!in_array($role, ['admin', 'admin_apotek'])) {
            abort(403, 'Akses ditolak');
        }

        // Hitung total admin_apotek
        $totalAdminApotek = Admin::where('role', 'admin_apotek')->count();

        $title = "Admin Dashboard";
        $adminName = Session::get('admin_name');

        return view('admin.dashboard', compact('title', 'adminName', 'totalAdminApotek'));
    }




    // logout
    public function logout(Request $request)
    {
        $request->session()->flush(); // hapus semua session
        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }


    public function admin(Request $request)
    {
        // pastikan user sudah login & punya akses (sesuaikan seperti sebelumnya)
        if (! Session::has('role')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
        }
        if (Session::get('role') !== 'admin') {
            abort(403, 'Akses ditolak');
        }

        // gunakan nama variabel 'search' konsisten
        $search = $request->query('search');

        $adminsQuery = Admin::where('role', 'admin_apotek');

        if (! empty($search)) {
            $adminsQuery->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('nama_apotek', 'like', "%{$search}%")
                    ->orWhere('nama_penanggung_jawab', 'like', "%{$search}%");
            });
        }

        $admins = $adminsQuery->orderBy('id', 'desc')->get();

        $title = 'Daftar Admin';
        $adminName = Session::get('admin_name');

        // pastikan kirim $search ke view supaya tidak undefined
        return view('admin.admin', compact('admins', 'title', 'adminName', 'search'));
    }
}
