<?php

namespace App\Http\Controllers;

use App\Models\Apotek;
use App\Models\Artikel;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $title = 'MEDIFINDER';
        $slug = 'home';
        $konten = 'Ini konten';

        $lokasi = $request->query('lokasi');
        $apotek = Apotek::whereHas('admin', function ($query) {
            $query->where('status', 'Disetujui');
        });

        if ($lokasi) {
            $apotek->where('alamat', 'LIKE', "%{$lokasi}%");
        }
        $apotek = $apotek->get();

        return view('home', compact('title', 'slug', 'konten', 'apotek', 'lokasi'));
    }

    public function Artikel(Request $request)
    {
        $title = 'MEDIFINDER - Artikel';
        $slug = 'artikel';
        $konten = 'Ini konten';
        $data = Artikel::all();

        return view('artikel', compact('title', 'slug', 'konten', 'data'));
    }

    public function detail($id, Request $request)
    {
        $apotek = Apotek::findOrFail($id);

        $query = $apotek->obats(); // relasi ke model Obat

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_obat', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        $obats = $query->get();

        return view('detailapotek', compact('apotek', 'obats'));
    }

    public function detailArtikel($id_artikel)
    {
        $title = 'MEDIFINDER - Detail Artikel';
        $slug = 'artikel';

        // Ambil artikel berdasarkan id_artikel
        $artikel = Artikel::where('id_artikel', $id_artikel)->first();

        if (! $artikel) {
            return redirect()->route('artikel')->with('error', 'Artikel tidak ditemukan.');
        }

        // Ambil artikel lain untuk rekomendasi
        // $artikelLain = Artikel::where('id_artikel', '!=', $id_artikel)
        //     ->latest()
        //     ->take(3)
        //     ->get();

        // dd($id_artikel);
        return view('detail_artikel', compact('title', 'slug', 'artikel'));
    }

    
    public function Katalog(Request $request)
    {
        $title = 'MEDIFINDER - Katalog Obat';
        $slug = 'katalog';
        $konten = 'Ini konten';

        $q = $request->query('q'); // nama_obat pencarian (GET)

        // default kosong — tampilan awal tidak menampilkan apotek
        $apotek = collect();

        if ($q) {
            // join obat -> apotek, cari nama obat (case insensitive)
            // ambil apotek unik (distinct)
            $apotek = Apotek::join('obat', 'apotek.id_apotek', '=', 'obat.id_apotek')
                ->where('obat.nama_obat', 'like', '%'.$q.'%')
                ->select('apotek.*')
                ->distinct()
                ->orderBy('apotek.nama_apotek')
                ->paginate(12)
                ->appends(['q' => $q]); // supaya pagination keep query
        }

        return view('katalog_obat', compact('title', 'slug', 'konten', 'apotek', 'q'));
    }
}
