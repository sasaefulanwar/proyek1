@extends('layouts.main_dashboard')
@section('title', $title ?? 'Tambah Obat')

@section('content')
    <div class="container mt-3">
        <div class="card table-card shadow-sm" style="border-radius: 12px; border: 3px solid orange;">
            <div class="card-body p-4">
                <h3 class="mb-4 text-center fw-bold">Tambah Obat</h3>

                <form action="{{ route('admin.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="nama_obat" class="form-label fw-semibold">Nama Obat</label>
                        <input type="text" name="nama_obat" id="nama_obat"
                            class="form-control @error('nama_obat') is-invalid @enderror" value="{{ old('nama_obat') }}"
                            placeholder="Masukkan nama obat">
                        @error('nama_obat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="kategori" class="form-label fw-semibold">Kategori</label>
                        <input type="text" name="kategori" id="kategori"
                            class="form-control @error('kategori') is-invalid @enderror" value="{{ old('kategori') }}"
                            placeholder="Masukkan kategori obat">
                        @error('kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="stok" class="form-label fw-semibold">Stok</label>
                        <input type="number" name="stok" id="stok"
                            class="form-control @error('stok') is-invalid @enderror" value="{{ old('stok') }}"
                            placeholder="Masukkan jumlah stok">
                        @error('stok')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="harga" class="form-label fw-semibold">Harga (Rp)</label>
                        <input type="number" name="harga" id="harga"
                            class="form-control @error('harga') is-invalid @enderror" value="{{ old('harga') }}"
                            placeholder="Masukkan harga obat">
                        @error('harga')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="gambar_obat" class="form-label fw-semibold">Gambar Obat</label>
                        <input type="file" name="gambar_obat" id="gambar_obat"
                            class="form-control @error('gambar_obat') is-invalid @enderror" accept="image/*">
                        @error('gambar_obat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="text-end mt-4">
                        <a href="{{ route('admin.obat') }}" class="btn btn-secondary me-2 px-4">Kembali</a>
                        <button type="submit" class="btn btn-primary px-4">Simpan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
