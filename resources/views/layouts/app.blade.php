<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#073b83">
    <meta name="description" content="@yield('meta_description', 'Informasi layanan Pengujian Kendaraan Bermotor Dishub.')">
    <title>@yield('title', 'Dishub KIR') · Dinas Perhubungan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="site-header">
        <div class="container nav-wrap">
            <a class="brand" href="{{ route('home') }}" aria-label="Dishub KIR - Beranda">
                <span class="brand-mark"><svg viewBox="0 0 48 48" aria-hidden="true"><circle cx="24" cy="24" r="22" fill="#fff"/><path d="M24 5 39 14v20L24 43 9 34V14z" fill="#0b62cf"/><path d="M24 10 34 16v16L24 38 14 32V16z" fill="none" stroke="#f6c453" stroke-width="2"/><path d="M17 26h14M19 21h10M20 16h8" stroke="#fff" stroke-width="2.4" stroke-linecap="round"/></svg></span>
                <span><strong>UPT Pengujian Kendaraan Bermotor</strong><small>Dinas Perhubungan Kab. Jombang, Jawa Timur</small></span>
            </a>
            <button class="menu-toggle" type="button" aria-label="Buka menu" aria-expanded="false" data-menu-toggle><span></span><span></span><span></span></button>
            <nav class="main-nav" data-menu>
                <a href="{{ route('home') }}#beranda">Beranda</a>
                <a href="{{ route('home') }}#tentang">Tentang</a>
                <a href="{{ route('home') }}#layanan">Layanan</a>
                <a href="{{ route('home') }}#informasi">Informasi</a>
                <a href="{{ route('home') }}#kontak">Kontak</a>
                <a class="button button-primary nav-cta" href="{{ route('vehicles.form') }}">Cek Kendaraan <span>↗</span></a>
            </nav>
        </div>
    </header>
    <main>@yield('content')</main>
    <footer class="site-footer" id="kontak">
        <div class="container footer-grid">
            <div class="footer-brand">
                <a class="brand brand-light" href="{{ route('home') }}">
                    <span class="brand-mark"><svg viewBox="0 0 48 48" aria-hidden="true"><circle cx="24" cy="24" r="22" fill="#fff"/><path d="M24 5 39 14v20L24 43 9 34V14z" fill="#0b62cf"/><path d="M24 10 34 16v16L24 38 14 32V16z" fill="none" stroke="#f6c453" stroke-width="2"/><path d="M17 26h14M19 21h10M20 16h8" stroke="#fff" stroke-width="2.4" stroke-linecap="round"/></svg></span>
                    <span><strong>UPT Pengujian Kendaraan Bermotor</strong><small>Dinas Perhubungan Kab. Jombang, Jawa Timur</small></span>
                </a>
                <p>Mendukung kendaraan yang laik jalan melalui pelayanan pengujian yang profesional, transparan, dan terpercaya.</p>
            </div>
            <div><h3>Jelajahi</h3><a href="{{ route('home') }}#tentang">Tentang Kami</a><a href="{{ route('home') }}#layanan">Layanan</a><a href="{{ route('home') }}#informasi">Informasi</a><a href="{{ route('vehicles.form') }}">Cek Kendaraan</a></div>
            <div><h3>Informasi Layanan</h3><p>Pelayanan Pengujian Kendaraan Bermotor</p><p>Silakan hubungi kantor Dishub setempat untuk jadwal, persyaratan, dan informasi resmi terbaru.</p></div>
        </div>
        <div class="container footer-bottom"><span>© {{ date('Y') }} Dishub KIR. Informasi layanan publik.</span><span>Pelayanan publik · Keselamatan di jalan</span></div>
    </footer>
</body>
</html>
