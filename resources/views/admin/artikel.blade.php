@extends('layouts.main_dashboard')
@section('title', $title ?? 'Artikel')

@section('content')
    <div class="container mt-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold text-white">Artikel</h2>
            <a href="{{ route('admin.artikel.tambah') }}" class="btn btn-primary d-flex align-items-center">
                <i class="fa-solid fa-plus me-2"></i> Tambah Artikel
            </a>
        </div>

        <form method="GET" action="{{ route('admin.artikel') }}">
            <input type="text" name="search" class="form-control mb-3 rounded-pill px-3" placeholder="Cari Judul Artikel"
                value="{{ request('search') }}">
        </form>

        <div class="card table-card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-head">
                        <tr>
                            <th>No</th>
                            <th>ID Artikel</th>
                            <th>Judul Artikel</th>
                            <th>Tanggal Publikasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($artikels as $index => $a)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $a->id_artikel }}</td>
                                <td>{{ $a->judul }}</td>
                                <td>{{ \Carbon\Carbon::parse($a->tanggal_publikasi)->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.artikel.edit', $a->id_artikel) }}"
                                        class="btn btn-warning btn-sm"><i class="fa-solid fa-pen-to-square"></i> Edit</a>

                                    <form action="{{ route('admin.artikel.delete', $a->id_artikel) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin ingin menghapus artikel ini?')"><i class="fa-solid fa-trash"></i> Hapus</button>
                                    </form>

                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">
                                    Tidak ada artikel ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
