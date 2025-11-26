<?php

namespace App\Http\Controllers;

use App\Models\Apotek;

class ApiController extends Controller
{
    public function TampilApotek()
    {
        $apotek = Apotek::with('obats')
            ->whereHas('admin', function ($query) {
                $query->where('status', 'Disetujui');
            })
            ->get();

        return response()->json([
            'message' => 'Data apotek berhasil ditampilkan',
            'data' => $apotek,
        ], 200);
    }

    public function showApotek($id)
    {
        $apotek = Apotek::with('obats')
            ->whereHas('admin', function ($query) {
                $query->where('status', 'Disetujui');
            })
            ->where('id_apotek', $id)
            ->firstOrFail();

        return response()->json([
            'message' => 'Detail apotek berhasil ditampilkan',
            'data' => $apotek,
        ], 200);
    }
}
