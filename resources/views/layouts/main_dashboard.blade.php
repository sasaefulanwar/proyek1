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
                {{-- DASHBOARD --}}
                <a href="{{ route('admin.dashboard') }}"
                    class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <span class="nav-icon">🏠</span>
                    <span class="nav-text">Dashboard</span>
                </a>

                {{-- ADMIN --}}
                @if (Session::get('role') === 'admin')
                    <a href="{{ route('admin.list') }}"
                        class="nav-link {{ request()->is('admin/admin') ? 'active' : '' }}">
                        <span class="nav-icon">👤</span>
                        <span class="nav-text">Admin</span>
                    </a>
                    <a href="{{ route('admin.apotek') }}"
                        class="nav-link {{ request()->is('admin/apotek') ? 'active' : '' }}">
                        <span class="nav-icon">🏪</span>
                        <span class="nav-text">Apotek</span>
                    </a>
                    <a href="{{ route('admin.artikel') }}"
                        class="nav-link {{ request()->is('admin/artikel') ? 'active' : '' }}">
                        <span class="nav-icon">📰</span>
                        <span class="nav-text">Artikel</span>

                    </a>
                @endif

                {{-- ADMIN APOTEK --}}
                @if (Session::get('role') === 'admin_apotek')
                    <a href="{{ route('admin.profile') }}"
                        class="nav-link {{ request()->is('admin/profile') ? 'active' : '' }}">
                        <span class="nav-icon">🏪</span>
                        <span class="nav-text">Profile Apotek</span>
                    </a>
                    <a href="{{ route('admin.obat') }}"
                        class="nav-link {{ request()->is('admin/obat') ? 'active' : '' }}">
                        <span class="nav-icon">💊</span>
                        <span class="nav-text">Obat</span>
                    </a>
                    <a href="{{ route('admin.laporan') }}"
                        class="nav-link {{ request()->is('admin/laporan') ? 'active' : '' }}">
                        <span class="nav-icon">📄</span>
                        <span class="nav-text">Laporan</span>
                    </a>
                @endif

                <form action="{{ route('logout') }}" method="POST" class="mt-3 logout-form">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100">
                        <span class="btn-icon">🔒</span>
                        <span class="btn-text">Keluar</span>
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
