<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use App\Models\Apotek;

class RegisterApotekController extends Controller
{

    public function create()
    {
        return view('admin.register');
    }
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:admin,username',
            'email' => 'required|email|max:100|unique:admin,email',
            'password' => 'required|string|min:6|confirmed',
            'nama_apotek' => 'required|string|max:150',
            'nama_penanggung_jawab' => 'nullable|string|max:150',
        ]);

        DB::beginTransaction();
        try {
            $apotek = Apotek::create([
                'nama_apotek' => $request->nama_apotek,
                'email' => $request->email,
            ]);

            // 2️⃣ Simpan data admin apotek
            $rawPassword = $request->password;

            Admin::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($rawPassword), // hash password
                'role' => 'admin_apotek',
                'status' => 'menunggu',
                'nama_penanggung_jawab' => $request->nama_penanggung_jawab,
                'nama_apotek' => $request->nama_apotek,
                'id_apotek' => $apotek->id_apotek, // ambil ID dari apotek yang baru dibuat
                'temp_password' => encrypt($rawPassword), // simpan sementara password terenkripsi
            ]);

            DB::commit();

            return redirect()->route('login')->with(
                'success',
                'Pendaftaran berhasil! Silakan tunggu verifikasi dari admin.'
            );
        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
    }
}
