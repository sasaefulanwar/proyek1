@extends('layouts.main_dashboard')
@section('title', $title ?? 'Edit Artikel')

@section('content')
<div class="container mt-3">
    <div class="card shadow-sm" style="border-radius: 12px; border: 3px solid orange;">
        <div class="card-body p-4">
            <h3 class="mb-4 text-center fw-bold">Edit Artikel</h3>

            @if (session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
            @endif

            <form action="{{ route('admin.artikel.update', $artikel->id_artikel) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="judul" class="form-label fw-semibold">Judul Artikel</label>
                    <input type="text" name="judul" id="judul" class="form-control @error('judul') is-invalid @enderror"
                        value="{{ old('judul', $artikel->judul) }}" placeholder="Masukkan judul artikel">
                    @error('judul')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="gambar" class="form-label fw-semibold">Gambar Artikel</label><br>
                    @if($artikel->gambar)
                        <img src="{{ asset('storage/' . $artikel->gambar) }}" alt="Gambar Artikel" width="120" class="mb-2 rounded">
                    @endif
                    <input type="file" name="gambar" id="gambar" class="form-control @error('gambar') is-invalid @enderror">
                    @error('gambar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="konten" class="form-label fw-semibold">Konten</label>
                    <textarea name="konten" id="konten" rows="8" class="form-control @error('konten') is-invalid @enderror">{{ old('konten', $artikel->konten) }}</textarea>
                    @error('konten')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="text-end mt-4">
                    <a href="{{ route('admin.artikel') }}" class="btn btn-secondary me-2 px-4">Kembali</a>
                    <button type="submit" class="btn btn-success px-4">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- TinyMCE --}}
<script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: 'textarea#konten',
        height: 300,
        menubar: false,
        plugins: 'lists link image preview code',
        toolbar: 'undo redo | bold italic underline | bullist numlist | link image | code preview',
        branding: false
    });
</script>
@endsection
