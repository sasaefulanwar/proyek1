@extends('layouts.main_dashboard')
@section('title', $title ?? 'Detail Apotek')

@section('content')
    <div class="container mt-2">
        <div class="admin-header mb-4">
            <h2 class="fw-bold text-white mb-3">Profil Apotek</h2>
        </div>

        <div class="card table-card shadow-sm">
            <div class="card-body p-4">

                <div class="mb-3">
                    <label class="fw-bold">Nama Apotek</label>
                    <p class="mb-0">{{ $apotek->nama_apotek ?? '-' }}</p>
                </div>

                <div class="mb-3">
                    <label class="fw-bold">Alamat</label>
                    <p class="mb-0">{{ $apotek->alamat ?? '-' }}</p>
                </div>

                <div class="mb-3">
                    <label class="fw-bold">Telepon</label>
                    <p class="mb-0">{{ $apotek->telepon ?? '-' }}</p>
                </div>

                <div class="mb-3">
                    <label class="fw-bold">Email</label>
                    <p class="mb-0">{{ $apotek->email ?? '-' }}</p>
                </div>

                <div class="mb-3">
                    <label class="fw-bold">Jam Operasional</label>
                    <p class="mb-0">{{ $apotek->jam_operasional ?? '-' }}</p>
                </div>

                <div class="mb-3">
                    <label class="fw-bold">Deskripsi</label>
                    <p class="mb-0">{{ $apotek->deskripsi ?? '-' }}</p>
                </div>

                <div class="mb-3">
                    <label class="fw-bold">Link Lokasi Google Maps</label>
                    @if (!empty($apotek->link_lokasi))
                        <p class="mb-1">
                            <a href="{{ $apotek->link_lokasi }}" target="_blank">
                                {{ $apotek->link_lokasi }}
                            </a>
                        </p>
                        <small class="text-muted">Klik link untuk membuka lokasi di Google Maps.</small>
                    @else
                        <p class="mb-0 text-muted">Belum ada link lokasi.</p>
                    @endif
                </div>

                <div class="mb-3">
                    <label class="fw-bold">Status Buka</label>
                    <p class="mb-0">
                        @if (($apotek->status_buka ?? '') === 'Buka')
                            <span class="badge bg-success">Buka</span>
                        @elseif (($apotek->status_buka ?? '') === 'Tutup')
                            <span class="badge bg-danger">Tutup</span>
                        @else
                            <span class="badge bg-secondary">{{ $apotek->status_buka ?? '-' }}</span>
                        @endif
                    </p>
                </div>

                <div class="mb-3">
                    <label class="fw-bold">Logo Apotek</label><br>
                    @if (!empty($apotek->foto_apotek))
                        <img src="{{ asset('storage/' . $apotek->foto_apotek) }}" alt="Logo Apotek" width="120"
                            class="mb-2 rounded">
                    @else
                        <p class="mb-0 text-muted">Belum ada logo apotek.</p>
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection
