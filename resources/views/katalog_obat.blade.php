@extends('layouts.main')

@section('title', $title ?? 'MediFinder')

@section('hero')
    <div class="hero-content container">
        <p class="muted-hashtag">#ApotekTapiOnline</p>
        <h1 class="hero-title">Platform<br>Apotek<br>Online</h1>
        <a class="hero-cta" href="#">Tentang MediFinder →</a </div>
    @endsection

    @section('content')
        <div class="container py-5">
            <h1 class="mb-1">Katalog Obat</h1>
            <p class="muted-instruction mb-4">Cari nama obat untuk menemukan apotek terdekat yang menyediakan produk yang
                Anda butuhkan.</p>

            <!-- Search card -->
            <div class="search-card mb-4">
                <form action="{{ route('katalog') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-8">
                        <input type="search" name="q" class="form-control search-input"
                            placeholder="Masukkan nama obat, contoh: Paracetamol 500mg" value="{{ $q ?? '' }}"
                            autofocus>
                        {{-- <div class="search-hint">Coba masukkan merek, dosis, atau kata kunci (mis. "Paracetamol", "500mg").
                        </div> --}}
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary-custom w-100">
                            <i class="fa-solid fa-eye me-2"></i> Tampilkan
                        </button>
                    </div>

                    <div class="col-md-2">
                        @if (!empty($q))
                            <a href="{{ route('katalog') }}"
                                class="btn btn-outline-secondary w-100 btn-outline-reset">Reset</a>
                        @else
                            <button type="button" class="btn btn-outline-secondary w-100 btn-outline-reset"
                                disabled>Reset</button>
                        @endif
                    </div>
                </form>
            </div>

            <!-- If no query show friendly empty state -->
            @if (empty($q))
                <div class="py-5 text-center text-muted">
                    <i class="fa-solid fa-magnifying-glass" style="font-size:60px; color:#0f756b; opacity:0.85;"></i>

                    <p class="mt-3 mb-0">
                        <strong>Mulai dengan mengetik nama obat</strong> di kolom pencarian di atas,
                        lalu tekan <strong>Tampilkan</strong>.
                    </p>

                    <small class="d-block mt-2">
                        Contoh: <em>Paracetamol 500mg</em>, <em>Amoxicillin 500mg</em>, <em>Vitamin C 100mg</em>
                    </small>
                </div>
            @else
                <div class="mb-3">
                    <small class="text-muted">Daftar Apotek yang menyediakan Obat : <strong>{{ $q }}</strong></small>
                </div>

                @if ($apotek->count() == 0)
                    <div class="py-4 text-center text-muted">
                        <p class="mb-0">Maaf, belum ditemukan apotek yang mencatat obat
                            <strong>{{ $q }}</strong>.
                        </p>
                        <p class="mb-0">Coba gunakan varian nama atau hapus dosis (contoh: cari "Paracetamol").</p>
                    </div>
                @else
                    <div class="row g-4">
                        @foreach ($apotek as $a)
                            <div class="col-md-3">
                                <div class="card apotek-card h-100">
                                    <img src="{{ $a->foto_apotek ? asset('storage/' . $a->foto_apotek) : '/mnt/data/7ab8e933-9cec-45b8-b3bf-9fa1e1899022.png' }}"
                                        class="card-img-top" alt="Apotek {{ $a->nama_apotek }}">
                                    <div class="card-body text-center">
                                        <h5 class="fw-bold mb-1">{{ $a->nama_apotek }}</h5>
                                        <p class="text-muted mb-2" style="min-height:44px;">{{ Str::limit($a->alamat, 80) }}
                                        </p>

                                        <div class="d-flex justify-content-center align-items-center gap-2 mb-3">
                                            <span
                                                class="apotek-badge {{ isset($a->status_buka) && $a->status_buka == 'Buka' ? 'bg-success text-white' : 'bg-danger text-white' }}">
                                                {{ $a->status_buka ?? 'Tidak diketahui' }}
                                            </span>

                                            {{-- Optional: tampilkan jarak jika ada (latitude/longitude) --}}
                                            @if (isset($a->jarak))
                                                <small class="text-muted">• {{ round($a->jarak, 1) }} km</small>
                                            @endif
                                        </div>

                                        <div class="d-grid">
                                            <a href="{{ route('apotek.detail', $a->id_apotek) }}"
                                                class="btn btn-warning text-white fw-semibold px-4 py-2 rounded-pill shadow-sm">
                                                Lihat Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- pagination --}}
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $apotek->links() }}
                    </div>
                @endif
            @endif
        </div>
    @endsection
