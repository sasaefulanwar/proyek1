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
    <div class="container mt-5">
        <h2>Lokasi Anda</h2>
        <div id="status">Mencari lokasi...</div>
        <div id="map" style="height:60vh; min-height:360px; border-radius: 12px; overflow: hidden;"></div>
        <div id="address" style="margin-top:12px; font-weight:600;"></div>

        <hr class="my-4">

        <h3>Daftar Apotek di Wilayah <span id="namaWilayah" style="color:#008080;">(Menunggu lokasi...)</span></h3>

        <div class="row mt-4">
            <!-- Card Apotek Dummy -->
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title">Apotek Sehat Sentosa</h5>
                        <p class="card-text">Jl. Raya Indramayu No. 10</p>
                        <button class="btn btn-teal">Lihat Detail</button>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title">Apotek Mandiri Farma</h5>
                        <p class="card-text">Jl. Sudirman No. 25</p>
                        <button class="btn btn-teal">Lihat Detail</button>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title">Apotek Keluarga Indramayu</h5>
                        <p class="card-text">Jl. Pahlawan No. 7</p>
                        <button class="btn btn-teal">Lihat Detail</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Leaflet CSS & JS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

        <style>
           
        </style>

        <script>
            const map = L.map('map').fitWorld();
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            let userMarker = null;

            async function reverseViaProxy(lat, lon) {
                const url = "{{ route('reverse') }}?lat=" + encodeURIComponent(lat) + "&lon=" + encodeURIComponent(lon) +
                    "&email=your@domain.com";
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
                document.getElementById('status').textContent = 'Lokasi ditemukan. Mengambil nama wilayah...';

                try {
                    const json = await reverseViaProxy(lat, lon);
                    const addr = json.address || {};
                    const kelurahan = addr.suburb || addr.neighbourhood || addr.village || addr.hamlet || '';
                    const kecamatan = addr.city_district || addr.state_district || addr.county || '';
                    const kabkot = addr.city || addr.town || addr.municipality || addr.county || '';
                    const prov = addr.state || '';

                    const parts = [];
                    if (kelurahan) parts.push(kelurahan);
                    if (kecamatan) parts.push('Kec. ' + kecamatan);
                    if (kabkot && !parts.join(', ').includes(kabkot)) parts.push(kabkot);
                    if (prov && !parts.join(', ').includes(prov)) parts.push(prov);

                    const human = parts.length ? parts.join(', ') : (json.display_name || 'Nama daerah tidak tersedia');

                    document.getElementById('status').textContent = 'Lokasi Anda saat ini berada di:';
                    document.getElementById('address').textContent = human;
                    document.getElementById('namaWilayah').textContent = human;

                    if (userMarker) userMarker.bindPopup('<strong>Lokasi Anda</strong><br>' + human).openPopup();
                } catch (err) {
                    console.error(err);
                    document.getElementById('status').textContent = 'Gagal melakukan reverse geocode';
                    document.getElementById('address').textContent = err.message || String(err);
                }
            }

            function onLocationError(e) {
                document.getElementById('status').textContent = 'Tidak bisa mendapatkan lokasi: ' + e.message;
                document.getElementById('address').textContent = 'Buka lewat https atau localhost dan izinkan akses lokasi.';
                map.setView([-6.3265, 108.3215], 13);
            }

            map.locate({
                setView: false,
                maxZoom: 16,
                watch: false
            });
            map.on('locationfound', onLocationFound);
            map.on('locationerror', onLocationError);
        </script>
    </div>
@endsection
