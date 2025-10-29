@extends('layouts.main_dashboard')
@section('title', $title ?? 'Data Obat')

@section('content')
    <div class="container mt-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold text-white">Data Obat</h2>
            <a href="{{ route('admin.tambah') }}" class="btn btn-primary d-flex align-items-center">
                <i class="bi bi-plus-circle me-2"></i> Tambah Obat
            </a>
        </div>

        <form method="GET" action="{{ route('admin.obat') }}">
            <input type="text" name="search" class="form-control mb-3 rounded-pill px-3"
                placeholder="Cari nama obat atau kategori" value="{{ $search ?? '' }}">
        </form>

        <div class="card table-card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-head">
                        <tr>
                            <th>No</th>
                            <th>Nama Obat</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($obats as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->nama_obat }}</td>
                                <td>{{ $item->kategori }}</td>
                                <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td>{{ $item->stok }}</td>
                                <td>
                                    @if ($item->status == 'Tersedia')
                                        <span class="badge bg-success px-3 py-2">Tersedia</span>
                                    @elseif ($item->status == 'Menipis')
                                        <span class="badge" style="background-color:#a17600;">Menipis</span>
                                    @else
                                        <span class="badge bg-secondary px-3 py-2">Habis</span>
                                    @endif
                                </td>
                                <td>
                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('admin.obat.edit', $item->id_obat) }}"
                                        class="btn btn-primary btn-sm">Edit</a>

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('admin.obat.delete', $item->id_obat) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Yakin ingin menghapus obat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-3">Tidak ada data obat</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
