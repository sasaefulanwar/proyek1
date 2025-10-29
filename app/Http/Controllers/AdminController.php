<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Apotek;
use App\Models\Obat;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApotekApprovedMail;
use Illuminate\Support\Facades\Storage;


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

        $valid = false;

        try {
            // ✅ Coba verifikasi pakai bcrypt
            if (Hash::check($request->password, $admin->password)) {
                $valid = true;
            }
        } catch (\Throwable $e) {
            // ❌ Kalau gagal (bukan bcrypt), bandingkan langsung (plaintext)
            if ($request->password === $admin->password) {
                $valid = true;
            }
        }

        if (! $valid) {
            return back()->with('error', 'Password salah.');
        }

        // Simpan session
        Session::put('id', $admin->id);
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

        return view('admin.admin', compact('admins', 'title', 'adminName', 'search'));
    }


    public function apotek(Request $request)
    {
        if (! Session::has('role')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
        }
        if (Session::get('role') !== 'admin') {
            abort(403, 'Akses ditolak');
        }

        $search = $request->query('search');

        // Buat query (BELUM mengeksekusi ke DB)
        $adminsQuery = Apotek::query();

        if (! empty($search)) {
            $adminsQuery->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('nama_apotek', 'like', "%{$search}%")
                    ->orWhere('nama_penanggung_jawab', 'like', "%{$search}%");
            });
        }

        // Eksekusi query, urutkan dulu lalu ambil hasil
        // Jika ingin semua: ->get()
        $admins = $adminsQuery->orderBy('id_apotek', 'desc')->get();

        // --- atau jika mau pagination (direkomendasikan untuk banyak data) ---
        // $admins = $adminsQuery->orderBy('id', 'desc')->paginate(10)->appends($request->query());

        $title = 'Daftar Apotek';
        $adminName = Session::get('admin_name');

        return view('admin.apotek', compact('admins', 'title', 'adminName', 'search'));
    }

    public function tambahObat()
    {
        $apotek = Apotek::all();
        $title = "Tambah Obat";
        return view('admin.tambahobat', compact('apotek', 'title'));
    }
    public function storeObat(Request $request)
    {
        $request->validate([
            'nama_obat' => 'required|string|max:100',
            'kategori' => 'nullable|string|max:50',
            'stok' => 'required|numeric|min:1',
            'harga' => 'required|numeric|min:100',
        ]);

        $adminId = session('id');
        $admin = Admin::find($adminId);

        if (! $admin) {
            return redirect()->back()->with('error', 'Data admin tidak ditemukan.');
        }

        $idApotek = $admin->id_apotek;

        Obat::create([
            'nama_obat' => $request->nama_obat,
            'kategori' => $request->kategori,
            'stok' => $request->stok,
            'harga' => $request->harga,
            'id_apotek' => $idApotek,
            'id_admin' => $adminId,
        ]);

        return redirect()->route('admin.obat')->with('success', 'Data obat berhasil ditambahkan.');
    }

    public function editObat($id_obat)
    {
        if (!Session::has('role')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        if (Session::get('role') !== 'admin_apotek') {
            abort(403, 'Akses ditolak');
        }

        $obat = Obat::find($id_obat);
        if (!$obat) {
            return redirect()->route('admin.obat')->with('error', 'Data obat tidak ditemukan.');
        }

        $apotek = Apotek::all();
        $title = 'Edit Obat';

        return view('admin.editobat', compact('obat', 'apotek', 'title'));
    }

    public function updateObat(Request $request, $id_obat)
    {
        $request->validate([
            'nama_obat' => 'required|string|max:100',
            'kategori' => 'required|string|max:50',
            'stok' => 'required|numeric|min:1',
            'harga' => 'required|numeric|min:100',
        ]);

        $obat = Obat::find($id_obat);
        if (!$obat) {
            return redirect()->route('admin.obat')->with('error', 'Data obat tidak ditemukan.');
        }

        $obat->update([
            'nama_obat' => $request->nama_obat,
            'kategori' => $request->kategori,
            'stok' => $request->stok,
            'harga' => $request->harga,
        ]);

        return redirect()->route('admin.obat')->with('success', 'Data obat berhasil diperbarui.');
    }

    public function deleteObat($id)
    {
        if (!Session::has('role')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        if (Session::get('role') !== 'admin_apotek') {
            abort(403, 'Akses ditolak');
        }

        $obat = Obat::find($id);

        if (! $obat) {
            return redirect()->route('admin.obat')->with('error', 'Data obat tidak ditemukan.');
        }

        $obat->delete();

        return redirect()->route('admin.obat')->with('success', 'Data obat berhasil dihapus.');
    }


    public function artikel(Request $request)
    {
        if (! Session::has('role')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
        }
        if (Session::get('role') !== 'admin') {
            abort(403, 'Akses ditolak');
        }

        // $search = $request->query('search');

        // $adminsQuery = Admin::where('role', 'admin_apotek');

        // if (! empty($search)) {
        //     $adminsQuery->where(function ($q) use ($search) {
        //         $q->where('username', 'like', "%{$search}%")
        //             ->orWhere('nama_apotek', 'like', "%{$search}%")
        //             ->orWhere('nama_penanggung_jawab', 'like', "%{$search}%");
        //     });
        // }

        // $admins = $adminsQuery->orderBy('id_admin', 'desc')->get();

        $title = 'Daftar Artikel';
        $adminName = Session::get('admin_name');

        return view('admin.artikel', compact('title', 'adminName'));
    }

    public function obat(Request $request)
    {
        if (! Session::has('role')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        if (Session::get('role') !== 'admin_apotek') {
            abort(403, 'Akses ditolak');
        }

        $title = 'Data Obat';
        $adminName = Session::get('admin_name');
        $adminId = Session::get('id');

        $admin = Admin::find($adminId);
        $id_apotek = $admin ? $admin->id_apotek : null;


        $search = $request->query('search');
        $query = Obat::where('id_admin', $adminId);

        if ($id_apotek) {
            $query->where('id_apotek', $id_apotek);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_obat', 'like', "%$search%")
                    ->orWhere('kategori', 'like', "%$search%");
            });
        }

        $obats = $query->orderBy('nama_obat')->get();

        return view('admin.obat', compact('title', 'adminName', 'obats', 'search'));
    }


    public function ProfileApotek(Request $request)
    {
        if (!Session::has('role')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        if (Session::get('role') !== 'admin_apotek') {
            abort(403, 'Akses ditolak');
        }

        $title = 'Profil Apotek';
        $adminName = Session::get('admin_name');

        // Ambil data admin login + relasi apotek
        $adminId = Session::get('id');
        $admin = Admin::with('apotek')->find($adminId);

        // Ambil data apotek dari relasi
        $apotek = $admin?->apotek;

        return view('admin.profile', compact('title', 'adminName', 'apotek'));
    }

    public function updateApotek(Request $request, $id_apotek)
    {
        if (!Session::has('role')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        if (Session::get('role') !== 'admin_apotek') {
            abort(403, 'Akses ditolak');
        }

        // Validasi input
        $request->validate([
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'jam_operasional' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'foto_apotek' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Ambil data apotek berdasarkan ID
        $apotek = Apotek::findOrFail($id_apotek);

        // Update data dasar
        $apotek->alamat = $request->alamat;
        $apotek->telepon = $request->telepon;
        $apotek->email = $request->email;
        $apotek->jam_operasional = $request->jam_operasional;
        $apotek->deskripsi = $request->deskripsi;

        // Jika ada upload foto baru
        if ($request->hasFile('foto_apotek')) {
            // Hapus foto lama jika ada
            if ($apotek->foto_apotek && Storage::exists('public/' . $apotek->foto_apotek)) {
                Storage::delete('public/' . $apotek->foto_apotek);
            }

            // Simpan foto baru
            $path = $request->file('foto_apotek')->store('apotek', 'public');
            $apotek->foto_apotek = $path;
        }

        $apotek->save();

        return redirect()->route('admin.profile')->with('success', 'Profil apotek berhasil diperbarui.');
    }

    public function Laporan(Request $request)
    {
        if (! Session::has('role')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
        }
        if (Session::get('role') !== 'admin_apotek') {
            abort(403, 'Akses ditolak');
        }

        // $search = $request->query('search');

        // $adminsQuery = Admin::where('role', 'admin_apotek');

        // if (! empty($search)) {
        //     $adminsQuery->where(function ($q) use ($search) {
        //         $q->where('username', 'like', "%{$search}%")
        //             ->orWhere('nama_apotek', 'like', "%{$search}%")
        //             ->orWhere('nama_penanggung_jawab', 'like', "%{$search}%");
        //     });
        // }

        // $admins = $adminsQuery->orderBy('id_admin', 'desc')->get();

        $title = 'Laporan';
        $adminName = Session::get('admin_name');

        return view('admin.laporan', compact('title', 'adminName'));
    }

    public function verifikasiApotek($id)
    {
        // Ambil data admin apotek
        $admin = Admin::findOrFail($id);

        // Ubah status menjadi disetujui
        $admin->update(['status' => 'disetujui']);

        // Kirim email ke admin apotek
        try {
            Mail::send('emails.verifikasi_apotek', ['admin' => $admin], function ($message) use ($admin) {
                $message->to($admin->email)
                    ->subject('Akun Anda Telah Diverifikasi - MediFinder');
            });
        } catch (\Throwable $e) {
            return back()->with('error', 'Verifikasi berhasil, tetapi gagal mengirim email: ' . $e->getMessage());
        }

        return back()->with('success', 'Akun apotek berhasil diverifikasi dan email telah dikirim.');
    }

    public function daftarApotek()
    {
        // Ambil semua admin_apotek yang belum diverifikasi
        $dataApotek = Admin::where('role', 'admin_apotek')
            ->where('status', 'menunggu')
            ->get();

        return view('admin.daftar_apotek', compact('dataApotek'));
    }
}
