@extends('layouts.main')

@section('title', $title ?? 'MediFinder')

@section('hero')
    <div class="hero-content container">
        <p class="muted-hashtag">#ApotekTapiOnline</p>
        <h1 class="hero-title">Platform<br>Apotek<br>Online</h1>
        <a class="hero-cta" href="#">Tentang MediFinder →</a>
    </div>
@endsection

@section('content')
    <div class="container py-5">

        {{-- Bagian Tentang & Lokasi --}}
        <div class="row align-items-center mb-5">
            {{-- Kolom Kiri: Tentang --}}
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h2 class="fw-bold mb-3">Tentang Medifinder</h2>
                <h1 class="fw-bold" style="color:#008080; line-height:1.2;">
                    <em>Platform</em> Khusus<br>Untuk Pencarian<br>Apotek Online
                </h1>
                <p class="mt-3 text-muted">
                    Unduh aplikasi MediFinder dan temukan kemudahan akses ke lebih dari 50.000 produk kesehatan,
                    mulai dari obat-obatan, vitamin, suplemen, hingga peralatan medis.
                    Nikmati fitur unggulan seperti konsultasi online, beli obat dari rumah, dan pilih apotek favorit Anda.
                </p>

                <ul class="features-list">
                    <li>
                        <span class="icon"><i class="fa-solid fa-check"></i></span>
                        <span class="label">Pencarian apotek fleksibel</span>
                    </li>

                    <li>
                        <span class="icon"><i class="fa-solid fa-check"></i></span>
                        <span class="label">Konsultasi online</span>
                    </li>

                    <li>
                        <span class="icon"><i class="fa-solid fa-check"></i></span>
                        <span class="label">Beli obat dari rumah</span>
                    </li>

                    <li>
                        <span class="icon"><i class="fa-solid fa-check"></i></span>
                        <span class="label">Bisa pilih apotek favorit</span>
                    </li>
                </ul>

            </div>

            {{-- Kolom Kanan: Map --}}
            <div class="col-lg-6">
                <h4 class="fw-bold mb-2">Lokasi Anda</h4>
                <div id="map" style="height:60vh; border-radius:12px; overflow:hidden;"></div>
                <div id="address" class="mt-2 fw-semibold text-teal"></div>
            </div>
        </div>

        <hr class="my-4">

        {{-- Daftar Apotek --}}
        <h3 class="fw-bold mb-4">
            Daftar Apotek di Wilayah
            <span id="namaWilayah" style="color:#008080;">
                {{ $lokasi ?? '(Menunggu lokasi...)' }}
            </span>
        </h3>

        <div class="row g-4">
            @forelse ($apotek as $a)
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 rounded-4 overflow-hidden h-100">
                        <img src="{{ asset('storage/' . $a->foto_apotek) }}" class="card-img-top" alt="Apotek"
                            style="height:200px; object-fit:cover;">
                        <div class="card-body text-center">
                            <h5 class="fw-bold mb-1">{{ $a->nama_apotek }}</h5>
                            <p class="text-muted mb-3">{{ $a->alamat }}</p>
                            <span class="badge {{ $a->status_buka == 'Buka' ? 'bg-success' : 'bg-danger' }}">
                                {{ $a->status_buka }}
                            </span>
                            <div class="mt-3">
                                <a href="{{ route('apotek.detail', $a->id_apotek) }}"
                                    class="btn btn-warning text-white fw-semibold px-4 py-2 rounded-pill shadow-sm">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted">Belum ada apotek yang cocok di wilayah ini.</p>
            @endforelse
        </div>


    </div>

    {{-- Leaflet CSS & JS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <style>
        .text-teal {
            color: #008080;
        }

        .btn-warning {
            background-color: #ffb400;
            border: none;
        }

        .btn-warning:hover {
            background-color: #e6a300;
        }
    </style>

    <script>
        const map = L.map('map').fitWorld();
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        let userMarker = null;

        async function reverseViaProxy(lat, lon) {
            const url = "{{ route('reverse') }}?lat=" + encodeURIComponent(lat) + "&lon=" + encodeURIComponent(lon);
            const resp = await fetch(url);
            if (!resp.ok) throw new Error('HTTP ' + resp.status);
            return await resp.json();
        }

        async function onLocationFound(e) {
            const lat = e.latlng.lat;
            const lon = e.latlng.lng;
            const userLatLng = e.latlng;

            if (userMarker) userMarker.setLatLng(userLatLng);
            else userMarker = L.marker(userLatLng).addTo(map).bindPopup('Lokasi Anda').openPopup();

            map.setView(userLatLng, 15);

            try {
                const json = await reverseViaProxy(lat, lon);
                const addr = json.address || {};
                const kel = addr.suburb || addr.village || '';
                const kec = addr.city_district || addr.county || '';
                const kota = addr.city || '';
                const prov = addr.state || '';

                const text = [kel, `Kec.${kec}`, kota, prov].filter(Boolean).join(', ');

                document.getElementById('address').textContent = text;
                document.getElementById('namaWilayah').textContent = text;

                // ⛔️ Cegah reload berulang
                const currentParams = new URLSearchParams(window.location.search);
                if (!currentParams.has('lokasi') && kel) {
                    window.location.href = "/?lokasi=" + encodeURIComponent(kel);
                }

            } catch (err) {
                document.getElementById('address').textContent = 'Gagal memuat lokasi';
            }
        }

        function onLocationError(e) {
            document.getElementById('address').textContent = 'Tidak dapat mengambil lokasi: ' + e.message;
            map.setView([-6.3265, 108.3215], 13);
        }

        map.locate({
            setView: false,
            maxZoom: 16
        });
        map.on('locationfound', onLocationFound);
        map.on('locationerror', onLocationError);
    </script>
@endsection
