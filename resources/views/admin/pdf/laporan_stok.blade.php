<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Stok {{ $from }} - {{ $to }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #c9a02f;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #f5c66a;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <h3>Laporan Stok</h3>
    <p>Periode: {{ $from }} sampai {{ $to }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Obat</th>
                <th>Stok</th>
                <th>Harga Satuan</th>
                <th>Nilai Stok</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->created_at)->format('Y-m-d') }}</td>
                    <td>{{ $row->nama_obat }}</td>
                    <td class="text-right">{{ $row->stok }}</td>
                    <td class="text-right">{{ 'Rp ' . number_format($row->harga, 0, ',', '.') }}</td>
                    <td class="text-right">{{ 'Rp ' . number_format($row->stok * $row->harga, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="5" class="text-right"><strong>Total Nilai Stok</strong></td>
                <td class="text-right"><strong>{{ 'Rp ' . number_format($totalNilai ?? 0, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>
</body>

</html>
