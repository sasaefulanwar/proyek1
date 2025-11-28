@extends('layouts.main')

@section('title', $title ?? 'Halaman Kontak | MediFinder')

@section('hero')
    <div class="hero-content container">
        <p class="muted-hashtag">#ApotekTapiOnline</p>
        <h1 class="hero-title">Platfrom<br>Appotek<br>Online</h1>
        <a href="{{ route('home') }}" style="color: #FFD700; text-decoration: none; font-weight: bold;">
  ← Kembali ke Beranda
</a>

    </div>
@endsection

@section('content')
    <div class="container py-5">
        <h1 class="mb-4">Kontak Kami </h1>
  
        @if(session('success'))
            <div style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 6px; margin-bottom: 15px;">
                {{ session('success') }}
            </div>
        @endif

    
        <form action="{{ route('kontak.kirim') }}" method="POST" style="max-width: 600px;">
            @csrf

            <div style="margin-bottom: 15px;">
                <label for="nama" style="font-weight:bold;">Nama :</label>
                <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required
                       style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
                @error('nama')
                    <small style="color:red;">{{ $message }}</small>
                @enderror
            </div>

            <div style="margin-bottom: 15px;">
                <label for="email" style="font-weight:bold;">Email :</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                       style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
                @error('email')
                    <small style="color:red;">{{ $message }}</small>
                @enderror
            </div>

            <div style="margin-bottom: 15px;">
                <label for="no_handphone" style="font-weight:bold;">No Handphone :</label>
                <input type="text" id="nohp" name="nohp" value="{{ old('nohp') }}" required
                       style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
                @error('nohp')
                    <small style="color:red;">{{ $message }}</small>
                @enderror
            </div>

            <div style="margin-bottom: 15px;">
                <label for="pesan" style="font-weight:bold;">Pesan :</label>
                <textarea id="pesan" name="pesan" rows="5" required
                          style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">{{ old('pesan') }}</textarea>
                @error('pesan')
                    <small style="color:red;">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit"
                    style="background-color:#28a745; color:white; border:none; padding:12px 20px; border-radius:6px; cursor:pointer;">
                Kirim Pesan
            </button>
        </form>
    </div>
@endsection
