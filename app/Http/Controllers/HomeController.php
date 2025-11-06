<?php

namespace App\Http\Controllers;

use App\Models\Apotek;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $title = "MEDIFINDER";
        $slug = "home";
        $konten = "Ini konten";

        $lokasi = $request->query('lokasi');
        $apotek = collect();

        if ($lokasi) {
            // cari apotek yang alamatnya mengandung kata lokasi
            $apotek = Apotek::where('alamat', 'LIKE', "%{$lokasi}%")->get();
        }

        return view('home', compact('title', 'slug', 'konten', 'apotek', 'lokasi'));
    }

    public function Artikel(Request $request)
    {
        $title = "MEDIFINDER - Artikel";
        $slug = "artikel";
        $konten = "Ini konten";

        return view('artikel', compact('title', 'slug', 'konten'));
    }
}
