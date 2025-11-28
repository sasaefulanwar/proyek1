<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kontak;

class kontakController extends Controller
{
   public function index() {
    return view('layouts.kontak', [
        'title' => 'Halaman Kontak',
        'slug' => 'kontak'
    ]);
}
    public function kirim(Request $request)
{
    $validated = $request->validate([
        'nama' => 'required|string',
        'email' => 'required|email',
        'nohp' => 'required|string',   
        'pesan' => 'required|string',
    ]);

    Kontak::create([
        'nama' => $request->nama,
        'email' => $request->email,
        'nohp' => $request->nohp,    
        'pesan' => $request->pesan,
    ]);

    return back()->with('success', 'Pesan Anda berhasil dikirim!');
}


}
