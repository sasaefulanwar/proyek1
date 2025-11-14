<nav class="bg-hero py-4">
    <div class="container">
        <!-- pill wrapper -->
        <div class="nav-pill mx-auto d-flex align-items-center justify-content-between px-3">
            <!-- logo -->
            <a class="d-flex align-items-center gap-2 logo-link" href="{{ url('/') }}">
                <img src="{{ asset('images/Logo.png') }}" alt="Logo" class="logo-img">
                <span class="visually-hidden">Home</span>
            </a>

            <!-- nav links (center) -->
            <ul class="navbar-nav d-none d-lg-flex flex-row align-items-center gap-3 mb-0">
                <li class="nav-item">
                    <a class="nav-link {{ $slug === 'home' ? 'active' : '' }}" href="/">Kemitraan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $slug === 'katalog' ? 'active' : '' }}" href="/katalog">Katalog Produk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $slug === 'artikel' ? 'active' : '' }}" href="/artikel">Artikel</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $slug === 'kontak' ? 'active' : '' }}" href="/kontak">Kontak Kami</a>
                </li>
            </ul>

            <!-- right controls -->
            <div class="d-flex align-items-center gap-3 ms-3">
                <a class="btn btn-pill-shop" href="/login">Daftarkan Apotekmu</a>

                <!-- language circle -->
                <div class="lang-circle d-flex align-items-center justify-content-center">
                    <img src="{{ asset('images/Group 2.png') }}" alt="ID" class="flag-img">
                </div>
            </div>
        </div>
    </div>
</nav>
