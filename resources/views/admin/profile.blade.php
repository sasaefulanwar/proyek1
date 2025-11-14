@extends('layouts.main_dashboard')
@section('title', $title ?? 'Daftar Admin')

@section('content')
    <div class="container mt-2">
        <div class="admin-header mb-4">
            <h2 class="fw-bold text-white mb-3">Profil Apotek</h2>
        </div>

        <div class="card table-card shadow-sm">
            <div class="card-body p-4">

                <form action="{{ route('apotek.update', $apotek->id_apotek ?? '') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="fw-bold">Nama Apotek</label>
                        <input type="text" name="nama_apotek" value="{{ $apotek->nama_apotek ?? '' }}"
                            class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2" required>{{ $apotek->alamat ?? '' }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Telepon</label>
                        <input type="text" name="telepon" value="{{ $apotek->telepon ?? '' }}" class="form-control"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Email</label>
                        <input type="email" name="email" value="{{ $apotek->email ?? '' }}" class="form-control"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Jam Operasional</label>
                        <input type="text" name="jam_operasional" value="{{ $apotek->jam_operasional ?? '' }}"
                            class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3">{{ $apotek->deskripsi ?? '' }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Link Lokasi Google Maps</label>
                        <input type="text" name="link_lokasi" value="{{ $apotek->link_lokasi ?? '' }}" class="form-control"
                            placeholder="https://maps.app.goo.gl/xxxxxx">
                        <small class="text-muted">Tempelkan link 'Share location' dari Google Maps.</small>
                    </div>


                    <div class="mb-3">
                        <label class="fw-bold">Status Buka</label>
                        <select name="status_buka" class="form-select" required>
                            <option value="Buka" {{ ($apotek->status_buka ?? '') == 'Buka' ? 'selected' : '' }}>Buka
                            </option>
                            <option value="Tutup" {{ ($apotek->status_buka ?? '') == 'Tutup' ? 'selected' : '' }}>Tutup
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Logo Apotek</label><br>
                        @if (!empty($apotek->foto_apotek))
                            <img src="{{ asset('storage/' . $apotek->foto_apotek) }}" alt="Logo Apotek" width="100"
                                class="mb-2 rounded">
                        @endif
                        <input type="file" name="foto_apotek" class="form-control">
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
