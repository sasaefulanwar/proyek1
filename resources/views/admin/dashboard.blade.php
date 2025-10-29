@extends('layouts.main_dashboard')
@section('title', $title ?? 'Dashboard Admin')

@section('content')
    <h2 class="mb-4">Dashboard Admin</h2>


    {{-- === Hanya tampil jika role = admin === --}}
    @if (Session::get('role') === 'admin')
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="stat-card p-3 d-flex align-items-center">
                    <div class="stat-icon">👥</div>
                    <div class="stat-body ms-3">
                        <div class="stat-title">Total Admin</div>
                        <div class="stat-value">{{ $totalAdminApotek }}</div>
                    </div>
                </div>
            </div>


            <div class="col-md-4">
                <div class="stat-card p-3 d-flex align-items-center">
                    <div class="stat-icon">🏪</div>
                    <div class="stat-body ms-3">
                        <div class="stat-title">Total Apotek</div>
                        <div class="stat-value">{{ $totalAdminApotek }}</div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-card p-3 d-flex align-items-center">
                    <div class="stat-icon">💊</div>
                    <div class="stat-body ms-3">
                        <div class="stat-title">Total Obat</div>
                        <div class="stat-value">1,078</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card table-card">
            <div class="card-header text-center fw-bold">Menunggu Verifikasi</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-head">
                        <tr>
                            <th>ID</th>
                            <th>Nama Apotek</th>
                            <th>Email</th>
                            <th>Penanggung Jawab</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dataApotek as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->nama_apotek }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->nama_penanggung_jawab ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-warning text-dark">{{ ucfirst($item->status) }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('apotek.verifikasi', $item->id) }}"
                                        class="btn btn-sm btn-primary">Verifikasi</a>
                                    {{-- <form action="{{ route('apotek.tolak', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-danger ms-2">Tolak</button>
                                    </form> --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada apotek yang menunggu verifikasi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    @endif

@endsection
