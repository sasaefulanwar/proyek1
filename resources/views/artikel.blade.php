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
            <h1>Artikel Yang Tersedia</h1>
            <div class="row g-4">
                @forelse ($data as $a)
                    <div class="col-md-3">
                        <div class="card shadow-sm border-0 rounded-4 overflow-hidden h-100">
                            <img src="{{ asset('storage/' . $a->gambar) }}" class="card-img-top" alt="Gambar Artikel"
                                style="height:200px; object-fit:cover;">
                            <div class="card-body text-center">
                                <h5 class="fw-bold mb-2">{{ $a->judul }}</h5>
                                <p class="text-muted" style="font-size: 0.9rem;">
                                    {{ Str::limit(strip_tags($a->konten), 100, '...') }}
                                </p>
                                <a href="{{ route('artikel.detail', ['id_artikel' => $a->id_artikel]) }}"
                                    class="btn btn-warning text-white fw-semibold px-4 py-2 rounded-pill shadow-sm">
                                    Baca Artikel
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Belum ada artikel yang tersedia.</p>
                @endforelse
            </div>
        </div>
    @endsection
