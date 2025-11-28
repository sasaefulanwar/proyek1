<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Admin;
use App\Models\Apotek;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $admin = Admin::where('email', $googleUser->getEmail())->first();

            if (!$admin) {

                DB::beginTransaction();
                $apotek = Apotek::create([
                    'nama_apotek' => 'Apotek ' . $googleUser->getName() ,
                    'email' => $googleUser->getEmail(),
                ]);

                $admin = Admin::create([
                    'username' => Str::slug(explode('@', $googleUser->getEmail())[0]),
                    'email' => $googleUser->getEmail(),
                    'nama_penanggung_jawab' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'password' => bcrypt(Str::random(16)),
                    'role' => 'admin_apotek',
                    'status' => 'menunggu',
                    'id_apotek' => $apotek->id_apotek, // relasi apotek
                    'nama_apotek' => $apotek->nama_apotek,
                ]);

                DB::commit();
            }

            // Simpan session login
            Session::put('id', $admin->id);
            Session::put('admin_name', $admin->nama_penanggung_jawab);
            Session::put('role', $admin->role);
            Session::put('id_apotek', $admin->id_apotek);

            return redirect()->route('admin.dashboard')
                ->with('success', 'Login menggunakan Google berhasil!');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect('/login')->with('error', 'Gagal login menggunakan Google. (' . $e->getMessage() . ')');
        }
    }
}
