<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Apotek;
use App\Models\Artikel;
use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            if (Hash::check($request->password, $admin->password)) {
                $valid = true;
            }
        } catch (\Throwable $e) {
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
        if (! Session::has('role')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $role = Session::get('role');
        if (! in_array($role, ['admin', 'admin_apotek'])) {
            abort(403, 'Akses ditolak');
        }

        $admin = Admin::find(Session::get('id'));

        $apotek = null;
        $status = null;

        if ($role === 'admin_apotek' && $admin && $admin->id_apotek) {
            $apotek = Apotek::where('id_apotek', $admin->id_apotek)->first();
            $status = $admin->status; // ambil status admin_apotek
        }

        $totalAdminApotek = Admin::where('role', 'admin_apotek')->count();
        $dataApotek = Admin::where('role', 'admin_apotek')
            ->where('status', 'menunggu')
            ->get();

        $title = 'Admin Dashboard';
        $adminName = Session::get('admin_name');

        return view('admin.dashboard', compact(
            'title',
            'adminName',
            'totalAdminApotek',
            'dataApotek',
            'apotek',
            'status',
            'admin'
        ));
    }

    // logout
    public function logout(Request $request)
    {
        $request->session()->flush(); // hapus semua session

        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }

    public function admin(Request $request)
    {
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

    public function Tambahartikel()
    {
        $title = 'Tambah Artikel';

        return view('admin.tambahartikel', compact('title'));
    }

    // Menyimpan artikel baru
    public function storeArtikel(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $slug = Str::slug($request->judul, '-');
        $gambarPath = null;

        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('artikel', 'public');
        }

        Artikel::create([
            'judul' => $request->judul,
            'slug' => $slug,
            'konten' => $request->konten,
            'gambar' => $gambarPath,
            'tanggal_publikasi' => now()->format('Y-m-d'),
        ]);

        return redirect()->route('admin.artikel')->with('success', 'Artikel berhasil ditambahkan!');
    }

    public function editArtikel($id)
    {
        $title = 'Edit Artikel';
        $artikel = Artikel::findOrFail($id);

        return view('admin.editartikel', compact('title', 'artikel'));
    }

    public function updateArtikel(Request $request, $id)
    {
        $artikel = Artikel::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $artikel->judul = $request->judul;
        $artikel->slug = Str::slug($request->judul);
        $artikel->konten = $request->konten;
        $artikel->tanggal_publikasi = now();

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/artikel'), $filename);
            $artikel->gambar = $filename;
        }

        $artikel->save();

        return redirect()->route('admin.artikel')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function deleteArtikel($id)
    {
        $artikel = Artikel::findOrFail($id);

        // hapus file gambar juga jika ada
        if ($artikel->gambar && file_exists(public_path('uploads/artikel/'.$artikel->gambar))) {
            unlink(public_path('storage/'.$artikel->gambar));
        }

        $artikel->delete();

        return redirect()->route('admin.artikel')->with('success', 'Artikel berhasil dihapus.');
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
        $adminsQuery = Apotek::query();

        if (! empty($search)) {
            $adminsQuery->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('nama_apotek', 'like', "%{$search}%")
                    ->orWhere('nama_penanggung_jawab', 'like', "%{$search}%");
            });
        }

        $admins = $adminsQuery->orderBy('id_apotek', 'desc')->get();

        // --- atau jika mau pagination (direkomendasikan untuk banyak data) ---
        // $admins = $adminsQuery->orderBy('id', 'desc')->paginate(10)->appends($request->query());

        $title = 'Daftar Apotek';
        $adminName = Session::get('admin_name');

        return view('admin.apotek', compact('admins', 'title', 'adminName', 'search'));
    }

    public function obat(Request $request)
    {
        if (! Session::has('role')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $role = Session::get('role');
        if (! in_array($role, ['admin', 'admin_apotek'])) {
            abort(403, 'Akses ditolak');
        }

        if (Session::get('role') === 'admin_apotek') {
            $admin = Admin::find(Session::get('id'));
            if ($admin && $admin->status === 'menunggu') {
                return redirect()->route('admin.profile')
                    ->with('error', 'Akun Anda belum terverifikasi. Anda hanya dapat mengakses halaman profil apotek.');
            }
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

        $apotek = null;
        if ($role === 'admin_apotek' && $admin && $admin->id_apotek) {
            $apotek = Apotek::where('id_apotek', $admin->id_apotek)->first();
        }

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_obat', 'like', "%$search%")
                    ->orWhere('kategori', 'like', "%$search%");
            });
        }

        $obats = $query->orderBy('nama_obat')->get();

        return view('admin.obat', compact('title', 'adminName', 'obats', 'search', 'apotek'));
    }

    public function tambahObat()
    {
        if (! Session::has('role')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $role = Session::get('role');
        if (! in_array($role, ['admin', 'admin_apotek'])) {
            abort(403, 'Akses ditolak');
        }

        $adminName = Session::get('admin_name');
        $adminId = Session::get('id');
        $admin = Admin::find($adminId);
        $id_apotek = $admin ? $admin->id_apotek : null;

        $apotek = Apotek::where('id_apotek', $id_apotek)->first();

        $title = 'Tambah Obat';

        return view('admin.tambah_obat', compact('apotek', 'title', 'adminName'));
    }

    public function storeObat(Request $request)
    {
        $request->validate([
            'nama_obat' => 'required|string|max:100',
            'kategori' => 'nullable|string|max:50',
            'stok' => 'required|numeric|min:1',
            'harga' => 'required|numeric|min:100',
            'gambar_obat' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $adminId = session('id');
        $admin = Admin::find($adminId);

        if (! $admin) {
            return redirect()->back()->with('error', 'Data admin tidak ditemukan.');
        }

        $idApotek = $admin->id_apotek;
        $pathGambar = null;

        // 🖼️ Upload gambar jika ada
        if ($request->hasFile('gambar_obat')) {
            $pathGambar = $request->file('gambar_obat')->store('obat', 'public');
        }

        Obat::create([
            'nama_obat' => $request->nama_obat,
            'kategori' => $request->kategori,
            'stok' => $request->stok,
            'harga' => $request->harga,
            'id_apotek' => $idApotek,
            'id_admin' => $adminId,
            'gambar_obat' => $pathGambar, // ✅ simpan path gambar
        ]);

        return redirect()->route('admin.obat')->with('success', 'Data obat berhasil ditambahkan.');
    }

    public function editObat($id_obat)
    {
        if (! Session::has('role')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
        }
        $role = Session::get('role');
        if (! in_array($role, ['admin', 'admin_apotek'])) {
            abort(403, 'Akses ditolak');
        }

        $adminName = Session::get('admin_name');
        $adminId = Session::get('id');
        $admin = Admin::find($adminId);
        $id_apotek = $admin ? $admin->id_apotek : null;

        $obat = Obat::find($id_obat);
        if (! $obat) {
            return redirect()->route('admin.obat')->with('error', 'Data obat tidak ditemukan.');
        }

        $apotek = Apotek::where('id_apotek', $id_apotek)->first();
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
            'gambar_obat' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $obat = Obat::find($id_obat);
        if (! $obat) {
            return redirect()->route('admin.obat')->with('error', 'Data obat tidak ditemukan.');
        }

        // 🖼️ Update gambar jika ada file baru
        if ($request->hasFile('gambar_obat')) {
            if ($obat->gambar_obat && Storage::disk('public')->exists($obat->gambar_obat)) {
                Storage::disk('public')->delete($obat->gambar_obat);
            }
            $pathGambar = $request->file('gambar_obat')->store('obat', 'public');
            $obat->gambar_obat = $pathGambar;
        }

        $obat->update([
            'nama_obat' => $request->nama_obat,
            'kategori' => $request->kategori,
            'stok' => $request->stok,
            'harga' => $request->harga,
            'gambar_obat' => $obat->gambar_obat,
        ]);

        return redirect()->route('admin.obat')->with('success', 'Data obat berhasil diperbarui.');
    }

    public function deleteObat($id)
    {
        if (! Session::has('role')) {
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
        if (! session()->has('role')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        if (session('role') !== 'admin') {
            abort(403, 'Akses ditolak');
        }

        $title = 'Kelola Artikel';

        $search = $request->query('search');
        $artikelQuery = Artikel::query();

        if (! empty($search)) {
            $artikelQuery->where('judul', 'LIKE', "%{$search}%");
        }

        $artikels = $artikelQuery->orderBy('tanggal_publikasi', 'desc')->get();

        return view('admin.artikel', compact('title', 'artikels'));
    }

    public function ProfileApotek(Request $request)
    {
        if (! Session::has('role')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $role = Session::get('role');
        if (! in_array($role, ['admin', 'admin_apotek'])) {
            abort(403, 'Akses ditolak');
        }

        $admin = Admin::find(Session::get('id'));

        $apotek = null;
        $status = null;

        if ($role === 'admin_apotek' && $admin && $admin->id_apotek) {
            $apotek = Apotek::where('id_apotek', $admin->id_apotek)->first();
            $status = $admin->status; // ambil status admin_apotek
        }

        $title = 'Profil Apotek';
        $adminName = Session::get('admin_name');

        // Ambil data admin login + relasi apotek
        $adminId = Session::get('id');
        $admin = Admin::with('apotek')->find($adminId);

        // Ambil data apotek dari relasi
        $apotek = $admin?->apotek;

        return view('admin.profile', compact('title', 'adminName', 'apotek', 'status', 'admin'));
    }

    public function updateApotek(Request $request, $id_apotek)
    {
        if (! Session::has('role')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $role = Session::get('role');
        if (! in_array($role, ['admin', 'admin_apotek'])) {
            abort(403, 'Akses ditolak');
        }

        $admin = Admin::find(Session::get('id'));

        $apotek = null;
        $status = null;

        if ($role === 'admin_apotek' && $admin && $admin->id_apotek) {
            $apotek = Apotek::where('id_apotek', $admin->id_apotek)->first();
            $status = $admin->status; // ambil status admin_apotek
        }

        // Validasi input
        $request->validate([
            'nama_apotek' => 'nullable|string|max:150',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'jam_operasional' => 'nullable|string|max:255',
            'status_buka' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'link_lokasi' => 'nullable|string|max:255',
            'foto_apotek' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Ambil data apotek berdasarkan ID
        $apotek = Apotek::findOrFail($id_apotek);

        // Update data dasar
        $apotek->nama_apotek = $request->nama_apotek ?? $apotek->nama_apotek;
        $apotek->alamat = $request->alamat;
        $apotek->telepon = $request->telepon;
        $apotek->email = $request->email;
        $apotek->jam_operasional = $request->jam_operasional;
        $apotek->status_buka = $request->status_buka;
        $apotek->deskripsi = $request->deskripsi;
        $apotek->link_lokasi = $request->link_lokasi ?? $apotek->link_lokasi;

        // Jika ada upload foto baru
        if ($request->hasFile('foto_apotek')) {
            if ($apotek->foto_apotek && Storage::exists('public/'.$apotek->foto_apotek)) {
                Storage::delete('public/'.$apotek->foto_apotek);
            }

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
        $role = Session::get('role');
        if (! in_array($role, ['admin', 'admin_apotek'])) {
            abort(403, 'Akses ditolak');
        }

        if (Session::get('role') === 'admin_apotek') {
            $admin = Admin::find(Session::get('id'));
            if ($admin && $admin->status === 'menunggu') {
                return redirect()->route('admin.profile')
                    ->with('error', 'Akun Anda belum terverifikasi. Anda hanya dapat mengakses halaman profil apotek.');
            }
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
        $adminName = Session::get('admin_name');
        $adminId = Session::get('id');

        $admin = Admin::find($adminId);
        $id_apotek = $admin ? $admin->id_apotek : null;

        $apotek = null;
        if ($role === 'admin_apotek' && $admin && $admin->id_apotek) {
            $apotek = Apotek::where('id_apotek', $admin->id_apotek)->first();
        }

        $title = 'Laporan';
        $adminName = Session::get('admin_name');

        return view('admin.laporan', compact('title', 'adminName', 'apotek'));
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
            return back()->with('error', 'Verifikasi berhasil, tetapi gagal mengirim email: '.$e->getMessage());
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
