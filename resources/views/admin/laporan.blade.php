@extends('layouts.main_dashboard')
@section('title', $title ?? 'Laporan')

@section('content')
    <div class="container mt-2">
        <h2 class="text-white fw-bold">Laporan</h2>

        <div class="mb-3 text-end">
            @if ($data && $data->count() > 0)
                <a href="{{ route('admin.laporan.export', request()->query()) }}" class="btn btn-success">
                    <i class="fa-solid fa-print me-2"></i> Cetak Laporan
                </a>
            @else
                <button class="btn btn-success" disabled>
                    <i class="fa-solid fa-print me-2"></i> Cetak Laporan
                </button>
            @endif
        </div>

        <div class="card mb-3 p-3" style="background:#f0f0f0;">
            <form method="GET" action="{{ route('admin.laporan') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Tanggal Awal</label>
                    <input type="date" name="from" class="form-control" value="{{ $from ?? '' }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" name="to" class="form-control" value="{{ $to ?? '' }}" required>
                </div>

                <div class="col-md-6 text-end">
                    <button type="submit" class="btn btn-primary"> <i class="fa-solid fa-eye me-2"></i> Tampilkan</button>
                </div>
            </form>
        </div>



        <div class="card">
            <div class="card-body p-0">
                <table class="table mb-0" style="border: 3px solid #f5b100; border-collapse: collapse;">

                    <thead class="fw-bold" style="background:#fff7e0;">
                        <tr>
                            <th style="border: 3px solid #f5b100;">No</th>
                            <th style="border: 3px solid #f5b100;">Tanggal</th>
                            <th style="border: 3px solid #f5b100;">Nama Obat</th>
                            <th style="border: 3px solid #f5b100;">Stok</th>
                            <th style="border: 3px solid #f5b100;">Harga Satuan</th>
                            <th style="border: 3px solid #f5b100;">Nilai Stok</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if ($data && $data->count() > 0)
                            @foreach ($data as $i => $row)
                                <tr>
                                    <td style="border: 3px solid #f5b100;">{{ $i + 1 }}</td>
                                    <td style="border: 3px solid #f5b100;">
                                        {{ \Carbon\Carbon::parse($row->created_at)->format('Y-m-d') }}
                                    </td>
                                    <td style="border: 3px solid #f5b100;">{{ $row->nama_obat }}</td>
                                    <td style="border: 3px solid #f5b100;">{{ $row->stok }}</td>
                                    <td style="border: 3px solid #f5b100;">
                                        {{ 'Rp ' . number_format($row->harga, 0, ',', '.') }}
                                    </td>
                                    <td style="border: 3px solid #f5b100;">
                                        {{ 'Rp ' . number_format($row->stok * $row->harga, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center py-4" style="border: 3px solid #f5b100;">
                                    Silakan pilih periode lalu tekan <strong>Tampilkan</strong>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection
