<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $artikel->judul ?? 'Detail Artikel' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


    <style>
        body {
            background-color: #f7f9fc;
            font-family: 'Poppins', sans-serif;
        }

        .artikel-header img {
            height: 380px;
            object-fit: cover;
            border-radius: 15px;
        }

        .artikel-content {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            padding: 30px;
            margin-top: -70px;
            position: relative;
            z-index: 2;
        }

        .artikel-lainnya img {
            height: 160px;
            object-fit: cover;
            border-radius: 10px;
        }

        .btn-back {
            background-color: #0f756b;
            color: #fff;
            border-radius: 50px;
        }

        .btn-back:hover {
            background-color: #0c5d56;
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="container my-5">

        <!-- Gambar Utama Artikel -->
        <div class="artikel-header text-center">
            @if ($artikel->gambar)
                <img src="{{ asset('storage/' . $artikel->gambar) }}" alt="Gambar Artikel"
                    class="w-100 mb-4 rounded-4 shadow-sm"
                    style="height: 450px; object-fit: cover; object-position: center;">
            @else
                <img src="{{ asset('images/default-artikel.jpg') }}" alt="Default Artikel"
                    class="w-100 mb-4 rounded-4 shadow-sm"
                    style="height: 450px; object-fit: cover; object-position: center;">
            @endif

        </div>

        <!-- Konten Artikel -->
        <div class="artikel-content">
            <h2 class="fw-bold">{{ $artikel->judul }}</h2>
            <p class="text-muted mb-2">
                <i class="fa-regular fa-user me-1"></i> {{ $artikel->penulis ?? 'Admin' }}
                <span class="mx-2">|</span>
                <i class="fa-regular fa-calendar me-1"></i> {{ $artikel->created_at->format('d M Y') }}
            </p>
            <hr>
            <div class="mt-3" style="text-align: justify; line-height: 1.8;">
                {!! $artikel->konten !!}
            </div>
            <div class="mt-4 text-start">
                <a href="{{ route('artikel') }}" class="btn btn-back px-4 py-2">
                    <i class="fa-solid fa-arrow-left me-2"></i> Kembali ke Artikel
                </a>
            </div>


        </div>

        {{-- <!-- Artikel Lainnya -->
        @if ($artikelLain->count() > 0)
            <div class="artikel-lainnya mt-5">
                <h4 class="fw-bold mb-4">Artikel Lainnya</h4>
                <div class="row">
                    @foreach ($artikelLain as $lain)
                        <div class="col-md-4 mb-4">
                            <div class="card border-0 shadow-sm h-100">
                                <img src="{{ $lain->gambar ? asset('storage/' . $lain->gambar) : asset('images/default-artikel.jpg') }}"
                                    alt="{{ $lain->judul }}" class="card-img-top">
                                <div class="card-body">
                                    <h6 class="fw-semibold">{{ $lain->judul }}</h6>
                                    <a href="{{ route('artikel.detail', $lain->id) }}"
                                        class="btn btn-outline-primary btn-sm rounded-pill mt-2">
                                        Baca Selengkapnya
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif --}}

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
