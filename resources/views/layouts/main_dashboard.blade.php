<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'MediFinder')</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    {{-- Favicon + Custom CSS --}}
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('images/Logo_remove.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .disabled-link {
            pointer-events: none;
            /* tidak bisa diklik */
            opacity: 0.5;
            /* efek buram */
            cursor: not-allowed;
        }
    </style>


</head>

<body>
    <div class="admin-layout d-flex">
        {{-- === SIDEBAR === --}}
        <aside class="sidebar p-3">
            <div class="brand d-flex align-items-center mb-4">
                <img src="{{ asset('images/Logo_remove.png') }}" alt="Logo" class="brand-logo me-2">
                <span class="brand-text">MediFinder</span>
            </div>

            <nav class="nav flex-column ">

                @if (Session::get('role') === 'admin_apotek')
                    <div class="text-center mb-3">
                        @if ($apotek && $apotek->foto_apotek)
                            <img src="{{ asset('storage/' . $apotek->foto_apotek) }}" alt="Foto Apotek"
                                class="rounded-3 mb-2" style="width: 100%; height: 100px; object-fit: cover;">
                        @else
                            <img src="{{ asset('images/default-apotek.jpg') }}" alt="Foto Default"
                                class="rounded-3 mb-2" style="width: 100%; height: 100px; object-fit: cover;">
                        @endif

                        <h6 class="text-black fw-bold">{{ $apotek->nama_apotek ?? 'Nama Apotek' }}</h6>
                    </div>
                @endif

                {{-- DASHBOARD --}}
                <a href="{{ route('admin.dashboard') }}"
                    class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <span class="nav-icon text-warning"><i class="fa-solid fa-gauge"></i></span>
                    <span class="nav-text text-black ms-2">Dashboard</span>
                </a>

                {{-- ADMIN --}}
                @if (Session::get('role') === 'admin')
                    <a href="{{ route('admin.list') }}"
                        class="nav-link {{ request()->is('admin/admin') ? 'active' : '' }}">
                        <span class="nav-icon text-warning"><i class="fa-solid fa-users"></i></span>
                        <span class="nav-text text-black ms-2">Admin Apotek</span>
                    </a>
                    <a href="{{ route('admin.apotek') }}"
                        class="nav-link {{ request()->is('admin/apotek') ? 'active' : '' }}">
                        <span class="nav-icon text-warning"><i class="fa-solid fa-store"></i></span>
                        <span class="nav-text text-black ms-2">Daftar Apotek</span>
                    </a>
                    <a href="{{ route('admin.artikel') }}"
                        class="nav-link {{ request()->is('admin/artikel') ? 'active' : '' }}">
                        <span class="nav-icon text-warning"><i class="fa-solid fa-newspaper"></i></span>
                        <span class="nav-text text-black ms-2">Artikel</span>

                    </a>
                @endif

                {{-- ADMIN APOTEK --}}
                @if (Session::get('role') === 'admin_apotek')
                    <a href="{{ route('admin.profile') }}"
                        class="nav-link {{ request()->is('admin/profile') ? 'active' : '' }}">
                        <span class="nav-icon text-warning"><i class="fa-solid fa-store"></i></span>
                        <span class="nav-text text-black ms-2">Profil Apotek</span>
                    </a>

                    <a href="{{ $status === 'menunggu' ? '#' : route('admin.obat') }}"
                        class="nav-link {{ request()->is('admin/obat') ? 'active' : '' }} {{ $status === 'menunggu' ? 'disabled-link' : '' }}">
                        <span class="nav-icon {{ $status === 'menunggu' ? 'text-secondary' : 'text-warning' }}"><i
                                class="fa-solid fa-pills"></i></span>
                        <span
                            class="nav-text {{ $status === 'menunggu' ? 'text-secondary' : 'text-black' }} ms-2">Obat</span>
                    </a>

                    <a href="{{ $status === 'menunggu' ? '#' : route('admin.laporan') }}"
                        class="nav-link {{ request()->is('admin/laporan') ? 'active' : '' }} {{ $status === 'menunggu' ? 'disabled-link' : '' }}">
                        <span class="nav-icon {{ $status === 'menunggu' ? 'text-secondary' : 'text-warning' }}"><i
                                class="fa-solid fa-chart-line"></i></span>
                        <span
                            class="nav-text {{ $status === 'menunggu' ? 'text-secondary' : 'text-black' }} ms-2">Laporan</span>
                    </a>
                @endif

                <form action="{{ route('logout') }}" method="POST" class="mt-3 logout-form">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100">
                        <span class="nav-icon text-warning"><i class="fa-solid fa-right-from-bracket"></i></span>
                        <span class="nav-text text-white ms-2">Keluar</span>
                    </button>
                </form>
            </nav>

        </aside>

        {{-- === MAIN CONTENT === --}}
        <div class="main-content flex-fill">
            {{-- HEADER --}}
            <header class="topbar d-flex justify-content-between align-items-center px-4">
                <div class="top-left">
                    <button class="btn btn-sm btn-outline-black" id="sidebarToggle">☰</button>
                </div>
                <div class="top-right d-flex align-items-center">
                    <div class="me-3 text-end">
                        <div class="top-name">{{ Session::get('admin_name', 'Admin') }}</div>
                        <div class="top-role small text-black">
                            @if (Session::get('role') === 'admin')
                                Admin
                            @elseif (Session::get('role') === 'admin_apotek')
                                Admin Apotek
                            @else
                                Super Admin
                            @endif
                        </div>

                    </div>
                    <div class="avatar ms-2">A</div>
                </div>
            </header>

            {{-- === CONTENT AREA === --}}
            <main class="content-area p-4">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>

    {{-- Sidebar Toggle --}}
    <script>
        const btn = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.sidebar');
        btn?.addEventListener('click', function() {
            sidebar?.classList.toggle('collapsed');
            document.body.classList.toggle('sidebar-collapsed'); // opsional, jika mau efek global
        });
    </script>


    @stack('scripts')
</body>

</html>
