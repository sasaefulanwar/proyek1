<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ReverseController extends Controller
{
    public function reverse(Request $request)
    {
        $lat = $request->query('lat');
        $lon = $request->query('lon');
        $email = $request->query('email', null); // optional

        if (! $lat || ! $lon) {
            return response()->json(['error' => 'Missing lat or lon'], 400);
        }

        $base = 'https://nominatim.openstreetmap.org/reverse';
        $params = [
            'format' => 'jsonv2',
            'lat' => $lat,
            'lon' => $lon,
            'addressdetails' => 1,
            'accept-language' => 'id',
        ];
        if ($email) $params['email'] = $email;

        // Identifikasi User-Agent agar sesuai kebijakan Nominatim
        $userAgent = 'MediFinder/1.0 (yourdomain.example)';

        try {
            $resp = Http::withHeaders([
                'User-Agent' => $userAgent
            ])->timeout(10)->get($base, $params);

            if ($resp->failed()) {
                return response()->json([
                    'error' => 'Failed to fetch from Nominatim',
                    'status' => $resp->status()
                ], 502);
            }

            return response($resp->body(), 200)
                ->header('Content-Type', 'application/json; charset=utf-8');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Exception: ' . $e->getMessage()], 502);
        }
    }
}
