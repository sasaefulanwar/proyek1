@extends('layouts.main_dashboard')
@section('title', $title ?? 'Daftar Admin')

@section('content')
    <div class="container mt-2">
        <div class="admin-header mb-4">
            <h2 class="fw-bold text-white mb-3">Data Apotek</h2>

            <form method="GET" action="{{ route('admin.list') }}" class="search-form d-flex align-items-center">
                <div class="search-box d-flex align-items-center me-2">
                    <span class="search-icon me-2">🔍</span>
                    <input type="text" name="search" class="form-control search-input" placeholder="Cari Apotek"
                        value="{{ $search ?? '' }}">
                </div>
                <button type="submit" class="btn btn-primary search-btn">Cari</button>
            </form>
        </div>


        <div class="card table-card shadow-sm">
            <div class="card-header text-center fw-bold">Daftar Apotek Tersedia</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-head">
                        <tr>
                            <th>ID</th>
                            <th>Nama Apotek</th>
                            <th>Lokasi</th>
                            <th>Email</th>
                            <th>Status Toko</th>
                            <th>Aksi</th> {{-- kolom baru --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($admins as $admin)
                            <tr>
                                <td>{{ $admin->id_apotek }}</td>
                                <td>{{ $admin->nama_apotek }}</td>
                                <td>{{ $admin->alamat }}</td>
                                <td>{{ $admin->email ?? '-' }}</td>
                                <td>
                                    @if ($admin->status_buka === 'Buka')
                                        <span class="badge bg-success">Buka</span>
                                    @elseif ($admin->status_buka === 'Tutup')
                                        <span class="badge bg-danger">Tutup</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $admin->status_buka }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.apotek.detail', $admin->id_apotek) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="fa-solid fa-eye me-1"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Tidak ada yang tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
