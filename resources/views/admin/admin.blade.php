@extends('layouts.main_dashboard')
@section('title', $title ?? 'Daftar Admin')

@section('content')
    <div class="container mt-2">
        <div class="admin-header mb-4">
            <h2 class="fw-bold text-white mb-3">Data Admin Apotek</h2>

            <form method="GET" action="{{ route('admin.list') }}" class="search-form d-flex align-items-center">
                <div class="search-box d-flex align-items-center me-2">
                    <span class="search-icon me-2">🔍</span>
                    <input type="text" name="search" class="form-control search-input" placeholder="Cari Admin"
                        value="{{ $search ?? '' }}">
                </div>
                <button type="submit" class="btn btn-primary search-btn">Cari</button>
            </form>
        </div>


        <div class="card table-card shadow-sm">
            <div class="card-header text-center fw-bold">Daftar Admin</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-head">
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Nama Apotek</th>
                            <th>Email</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($admins as $admin)
                            <tr>
                                <td>{{ $admin->id }}</td>
                                <td>{{ $admin->username }}</td>
                                <td>{{ $admin->nama_apotek }}</td>
                                <td>{{ $admin->email ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $admin->status === 'menunggu' ? 'bg-danger' : 'bg-success' }}">
                                        {{ $admin->status === 'menunggu' ? 'Belum Aktif' : 'Aktif' }}
                                    </span>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Tidak ada yang tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
