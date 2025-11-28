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
                        <div class="stat-value">
                            {{ isset($jumlahDataObat) ? number_format($jumlahDataObat, 0, ',', '.') : '0' }}
                        </div>
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

    @if (Session::get('role') === 'admin_apotek' && isset($admin))
        @if ($admin->status === 'menunggu')
            <div class="alert alert-danger d-flex align-items-center mt-3" role="alert">
                <i class="fa-solid fa-xmark-circle me-2 fs-4"></i>
                <div>
                    <strong>Akun Anda belum terverifikasi.</strong>
                    Silakan menunggu hingga 24 jam untuk proses verifikasi dan lengkapi data apotek Anda.
                </div>
            </div>
        @elseif ($admin->status === 'disetujui')
            <div class="d-flex gap-3 mt-3">

                {{-- Card Total Obat --}}
                <div class="card shadow-sm border-warning" style="min-width: 280px; border-width: 3px;">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Total Obat</h6>
                        <h3 class="fw-bold">{{ $totalObat }}</h3>

                        <span class="text-muted">
                            Habis:
                            <span class="text-danger fw-bold">{{ $obatHabis }}</span>
                        </span>
                    </div>
                </div>

                {{-- Card Pencarian Teratas --}}
                <div class="card shadow-sm border-warning" style="min-width: 280px; border-width: 3px;">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Pencarian Teratas</h6>
                        <h4 class="fw-bold mb-1">Paracetamol</h4>
                        <small class="text-muted">+12% Minggu ini</small>
                    </div>
                </div>

                {{-- Card Kunjungan Hari Ini --}}
                <div class="card shadow-sm border-warning" style="min-width: 280px; border-width: 3px;">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Kunjungan (Hari ini)</h6>
                        <h3 class="fw-bold">501</h3>
                        <small class="text-muted">Jam ramai: 10:00 - 12:00</small>
                    </div>
                </div>
            </div>

            <div class="card mt-4 shadow-sm" style="border: 3px solid #f5b100; border-radius: 15px;">
                <div class="card-body">
                    <h4 class="fw-bold mb-3">Tabel Stok Singkat</h4>
                    <table class="table table-bordered" style="border-color: #f5b100; border-width: 2px;">
                        <thead class="fw-bold">
                            <tr style="border-color: #f5b100;">
                                <th style="border-color: #f5b100;">Nama Obat</th>
                                <th style="border-color: #f5b100;">Kategori</th>
                                <th style="border-color: #f5b100;">Stok</th>
                                <th style="border-color: #f5b100;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($obatTerbaru as $ob)
                                <tr>
                                    <td style="border-color: #f5b100;">{{ $ob->nama_obat }}</td>
                                    <td style="border-color: #f5b100;">{{ $ob->kategori }}</td>
                                    <td style="border-color: #f5b100;">{{ $ob->stok }}</td>

                                    <td style="border-color: #f5b100;">
                                        @php
                                            if ($ob->stok == 0) {
                                                $status = ['Habis', '#e74c3c'];
                                            } elseif ($ob->stok < 10) {
                                                $status = ['Menipis', '#d4a017'];
                                            } else {
                                                $status = ['Tersedia', '#2ecc71'];
                                            }
                                        @endphp

                                        <span class="badge"
                                            style="background-color: {{ $status[1] }}; padding: 8px 15px;">
                                            {{ $status[0] }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3">
                                        Tidak ada data obat.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>
        @endif
    @endif

    {{-- <div class="alert alert-success d-flex align-items-center mt-3" role="alert">
                <i class="fa-solid fa-circle-check me-2 fs-4"></i>
                <div>
                    <strong>Akun Anda telah terverifikasi.</strong> Selamat, Anda sekarang dapat mengakses seluruh fitur.
                </div>
            </div> --}}

@endsection
