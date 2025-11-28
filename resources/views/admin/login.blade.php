<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
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
            background: #ffffff;
        }

        .login-box {
            width: 380px;
        }

        .login-box h4 {
            font-weight: 700;
            font-size: 18px;
            margin-bottom: 6px;
            text-align: center;
        }

        .login-sub {
            color: #8a8a8a;
            font-size: 13px;
            text-align: center;
            margin-bottom: 22px;
            line-height: 1.45;
        }

        .form-control {
            border-radius: 6px;
            background: #f2f6fb;
            height: 48px;
            border: 1px solid rgba(0, 0, 0, 0.08);
            padding: 10px 12px;
        }

        .form-control:focus {
            border-color: rgba(0, 128, 128, 0.6);
            box-shadow: none;
        }

        .field-with-icon {
            position: relative;
        }

        .field-with-icon .toggle-visibility {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            cursor: pointer;
            color: #444;
        }

        .forgot-link {
            font-size: 13px;
            color: #444;
        }

        .btn-primary-cta {
            display: block;
            width: 170px;
            margin: 18px auto 8px;
            background: var(--accent);
            color: #111;
            border: none;
            padding: 10px 18px;
            font-weight: 700;
            border-radius: 8px;
            box-shadow: 0 6px 14px rgba(255, 212, 0, 0.18);
        }

        .small-note {
            text-align: center;
            font-size: 13px;
            color: #9a9a9a;
            margin-top: 8px;
        }

        @media (max-width: 768px) {
            .login-wrapper {
                flex-direction: column;
            }

            .left-bg {
                display: none;
            }

            .login-card {
                flex: none;
                width: 100%;
                height: 100vh;
            }

            .login-box {
                width: 90%;
            }
        }
    </style>
</head>

<body>
    <div class="login-wrapper">
        <div class="left-bg"></div>

        <div class="login-card">
            <div class="login-box">
                <h4>Admin Login</h4>
                <p class="login-sub">Halo, masukkan detail Anda di sini untuk masuk ke dashboard</p>

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <input type="text" name="username" class="form-control" placeholder="Username" required>
                    </div>

                    <div class="mb-2 field-with-icon">
                        <input id="passwordInput" name="password" type="password" class="form-control"
                            placeholder="Password" required>
                        <button type="button" class="toggle-visibility" onclick="togglePassword()">
                            <svg id="eyeIcon" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="#222" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </button>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div></div>
                        <a href="#" class="forgot-link">Lupa password?</a>
                    </div>

                    <button type="submit" class="btn btn-primary-cta">Masuk</button>
                    <a href="{{ route('google.login') }}" class="btn btn-danger w-100">
                        <i class="fab fa-google me-2"></i> Login dengan Google
                    </a>

                    <p class="small-note">
                        Tidak punya akun?
                        <a href="{{ route('apotek.create') }}"
                            style="color: #008080; text-decoration: none; font-weight:600">
                            Ajukan sekarang
                        </a>
                    </p>

                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const eyeIcon = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.innerHTML =
                    '<path d="M17.94 17.94A10.12 10.12 0 0 1 12 20c-7 0-11-8-11-8a19.77 19.77 0 0 1 4.23-5.3M22.54 11.88A19.77 19.77 0 0 1 18 6.7M1 1l22 22" />';
            } else {
                input.type = 'password';
                eyeIcon.innerHTML =
                    '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" /><circle cx="12" cy="12" r="3" />';
            }
        }
    </script>
</body>

</html>
