<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Apotek - {{ $apotek->nama_apotek }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('images/Logo_remove.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background-color: #ffffff;
            color: #0f756b;
            font-family: 'Poppins', sans-serif;
        }

        .header-section {
            background-color: #0f756b;
            color: white;
            padding: 40px 0 80px;
            border-bottom-left-radius: 30px;
            border-bottom-right-radius: 30px;
        }

        .apotek-image {
            border-radius: 20px;
            width: 100%;
            height: 400px;
            object-fit: cover;
            margin-top: 20px;
            border: 5px solid white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .content-section {
            margin-top: -60px;
            background-color: white;
            border-radius: 25px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .info-box {
            background-color: #0f756b;
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
        }

        .info-box p {
            margin-bottom: 8px;
        }

        .btn-back {
            background-color: white;
            color: #0f756b;
            border: 2px solid #0f756b;
            transition: 0.3s;
        }

        .btn-back:hover {
            background-color: #0f756b;
            color: white;
        }

        .status-badge {
            color: white;
            padding: 8px 30px;
            border-radius: 20px;
            font-weight: 600;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        .bg-success {
            background-color: #28a745 !important;
        }

        .bg-danger {
            background-color: #dc3545 !important;
        }

        .btn-cari {
            background-color: #0f756b;
            color: white
        }

        thead.table-ijo {
            background-color: #0f756b;
        }
    </style>
</head>

<body>

    {{-- Header --}}

    <div class="header-section text-center">
        <div class="container">
            <div class="text-start mt-4">
                <a href="{{ url('/') }}" class="btn btn-back rounded-pill px-4">
                    <i class="fa-solid fa-arrow-left me-2"></i>Kembali ke Beranda
                </a>
            </div>
            <h1 class="fw-bold">{{ $apotek->nama_apotek }}</h1>
            <img src="{{ $apotek->foto_apotek ? asset('storage/' . $apotek->foto_apotek) : asset('images/default_apotek.jpg') }}"
                alt="Foto Apotek" class="apotek-image">
        </div>
    </div>

    {{-- Konten --}}
    <div class="container content-section mt-4">
        <div class="text-end mb-3">
            <span class="status-badge 
        {{ $apotek->status_buka == 'Buka' ? 'bg-success' : 'bg-danger' }}">
                {{ $apotek->status_buka }}
            </span>
        </div>

        <h2>Informasi :</h2>
        <div class="info-box">
            <p><strong>Alamat :</strong> {{ $apotek->alamat ?? '-' }}</p>
            <p><strong>No. Telepon :</strong> {{ $apotek->telepon ?? '-' }}</p>
            <p><strong>Jam Operasional :</strong> {{ $apotek->jam_operasional ?? '-' }}</p>
            <p><strong>Status :</strong> {{ $apotek->status_buka ?? '-' }}</p>

            @if (!empty($apotek->link_lokasi))
                <a href="{{ $apotek->link_lokasi }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary mt-2">
                    <i class="bi bi-geo-alt-fill"></i> Lihat di Google Maps
                </a>
            @else
                <p class="text-muted mt-2">Lokasi belum ditambahkan</p>
            @endif
        </div>



        <h2>Deskripsi :</h2>
        <div class="info-box">
            <p>{{ $apotek->deskripsi ?? 'Belum ada deskripsi apotek.' }}</p>
        </div>

        <h2 class="text-center mt-5">Daftar Obat yang Tersedia</h2>

        <div class="d-flex justify-content-end mt-3">
            <form action="" method="GET" class="d-flex" style="max-width: 300px; width:100%;">
                <input type="text" name="search" class="form-control me-2" placeholder="Cari nama atau kategori..."
                    value="{{ request('search') }}">
                <button type="submit" class="btn btn-cari px-3">Cari</button>
            </form>
        </div>

        <div class="table-responsive mt-4">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-ijo table-success">
                    <tr>
                        <th>Gambar</th>
                        <th>Nama Obat</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($obats as $obat)
                        <tr>
                            <td>
                                @if ($obat->gambar_obat)
                                    <img src="{{ asset('storage/' . $obat->gambar_obat) }}"
                                        alt="{{ $obat->nama_obat }}"
                                        style="width: 80px; height: 80px; object-fit: cover; border-radius: 10px;">
                                @else
                                    <span class="text-muted">Tidak ada</span>
                                @endif
                            </td>
                            <td>{{ $obat->nama_obat }}</td>
                            <td>{{ $obat->kategori }}</td>
                            <td>Rp{{ number_format($obat->harga, 0, ',', '.') }}</td>
                            <td>{{ $obat->stok }}</td>
                            <td>
                                <span
                                    class="badge 
                            {{ $obat->status == 'Tersedia' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $obat->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Belum ada obat yang tersedia di apotek ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>

</html>
