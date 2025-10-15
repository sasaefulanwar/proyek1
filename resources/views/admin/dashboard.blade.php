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
                            <th>Alamat</th>
                            <th>Admin Pengelola</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>101</td>
                            <td>Apotek Sehat Makmur Jaya</td>
                            <td>Jl Mawar No.10</td>
                            <td>NovalGaming@gmail.com</td>
                            <td><span class="badge bg-warning text-dark">Menunggu</span></td>
                            <td>
                                <button class="btn btn-sm btn-primary">Verifikasi</button>
                                <button class="btn btn-sm btn-danger ms-2">Tolak</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endif

@endsection
