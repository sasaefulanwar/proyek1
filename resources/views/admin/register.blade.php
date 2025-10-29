<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Apotek</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        :root {
            --teal: #008080;
            --accent: #ffd400;
        }

        html,
        body {
            height: 100%;
            margin: 0;
            font-family: "Poppins", sans-serif;
            background-color: var(--teal);
        }

        .login-wrapper {
            display: flex;
            height: 100vh;
        }

        .left-bg {
            flex: 1;
            background-color: var(--teal);
        }

        .login-card {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
        }

        .login-box {
            width: 420px;
            padding: 28px;
        }

        .login-box h4 {
            font-weight: 700;
            margin-bottom: 6px;
            text-align: center;
        }

        .login-sub {
            color: #8a8a8a;
            font-size: 13px;
            text-align: center;
            margin-bottom: 16px;
        }

        .form-control {
            border-radius: 6px;
            background: #f2f6fb;
            height: 48px;
            border: 1px solid rgba(0, 0, 0, .08);
            padding: 10px 12px;
        }

        .btn-primary {
            background: var(--accent);
            border: none;
            color: #000;
            font-weight: 700;
        }

        .text-error {
            color: #b02a37;
            font-size: .9rem;
        }

        @media (max-width:768px) {
            .left-bg {
                display: none
            }

            .login-box {
                width: 92%
            }
        }
    </style>
</head>

<body>
    <div class="login-wrapper">
        <div class="left-bg"></div>

        <div class="login-card">
            <div class="login-box">
                <h4>Daftar Apotek</h4>
                <p class="login-sub">Isi data untuk mendaftarkan akun Admin Apotek. Akun akan ditinjau oleh admin.</p>

                {{-- Alert success / error flash --}}
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('apotek.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Username --}}
                    <div class="mb-3">
                        <input type="text" name="username" value="{{ old('username') }}" class="form-control"
                            placeholder="Username" required>
                        @error('username')
                            <div class="text-error mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                            placeholder="Email" required>
                        @error('email')
                            <div class="text-error mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                        @error('password')
                            <div class="text-error mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password confirmation --}}
                    <div class="mb-3">
                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="Konfirmasi Password" required>
                    </div>

                    {{-- Nama Apotek --}}
                    <div class="mb-3">
                        <input type="text" name="nama_apotek" value="{{ old('nama_apotek') }}" class="form-control"
                            placeholder="Nama Apotek" required>
                        @error('nama_apotek')
                            <div class="text-error mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nama Penanggung Jawab (opsional) --}}
                    <div class="mb-3">
                        <input type="text" name="nama_penanggung_jawab" value="{{ old('nama_penanggung_jawab') }}"
                            class="form-control" placeholder="Nama Penanggung Jawab (opsional)">
                        @error('nama_penanggung_jawab')
                            <div class="text-error mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- (Opsional) Foto - jika nanti ingin enable --}}
                    {{-- <div class="mb-3">
            <label class="form-label">Foto Apotek (opsional)</label>
            <input type="file" name="foto_apotek" class="form-control">
            @error('foto_apotek') <div class="text-error mt-1">{{ $message }}</div> @enderror
          </div> --}}

                    <div class="d-grid mt-3">
                        <button type="submit" class="btn btn-primary">Daftar</button>
                    </div>
                </form>

                <p class="small-note mt-3 text-center">Sudah punya akun? <a href="{{ route('login') }}"
                        style="color: #008080; font-weight:600">Masuk</a></p>
            </div>
        </div>
    </div>
</body>

</html>
