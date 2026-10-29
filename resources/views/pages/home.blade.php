@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/contact.css') }}">
@endpush
@section('title', 'Beranda')
@section('meta_description', 'Informasi layanan Pengujian Kendaraan Bermotor, persyaratan, alur pelayanan, dan pengecekan data kendaraan.')
@section('content')
<section class="hero" id="beranda">
    <div class="hero-shape"></div>
    <div class="container hero-grid">
        <div class="hero-copy">
            <div class="eyebrow eyebrow-light"><span class="eyebrow-dot"></span> LAYANAN PUBLIK · PENGUJIAN KENDARAAN</div>
            <h1>Perjalanan Aman<br>Dimulai dari <span>Kendaraan Laik Jalan.</span></h1>
            <p>Pelayanan Pengujian Kendaraan Bermotor yang profesional, transparan, dan berorientasi pada keselamatan di jalan.</p>
            <div class="hero-actions"><a class="button button-white" href="{{ route('vehicles.form') }}">Cek Data Kendaraan <span>→</span></a><a class="button button-outline-light" href="#layanan">Jelajahi Layanan</a></div>
            <div class="hero-trust"><span class="trust-icon">✓</span><span>Informasi layanan resmi dan mudah diakses</span></div>
        </div>
        <div class="hero-visual">
            <div class="hero-image"></div>
            <div class="floating-card floating-top"><span class="floating-icon">✦</span><span><strong>Keselamatan Prioritas</strong><small>Utamakan kendaraan laik jalan</small></span></div>
            <div class="floating-card floating-bottom"><span class="mini-stat">01</span><span><strong>Layanan Terintegrasi</strong><small>Informasi dalam satu tempat</small></span></div>
            <div class="hero-orbit"></div>
        </div>
    </div>
    <div class="hero-bottom-line"></div>
</section>

<section class="feature-strip container" aria-label="Keunggulan layanan">
    <article class="feature-item"><span class="feature-icon"><svg viewBox="0 0 24 24"><path d="M3 13h18l-2-6H5l-2 6Zm2 0v5h14v-5M7 18v2m10-2v2M7 10h.01M17 10h.01"/></svg></span><div><h3>Pengujian Berkala</h3><p>Mendukung pemenuhan standar teknis kendaraan.</p></div></article>
    <article class="feature-item"><span class="feature-icon"><svg viewBox="0 0 24 24"><path d="M12 3 20 7v5c0 5-3.4 7.8-8 9-4.6-1.2-8-4-8-9V7l8-4Z"/><path d="m8.5 12 2.3 2.3 4.8-5"/></svg></span><div><h3>Utamakan Keselamatan</h3><p>Informasi yang mendorong kendaraan laik jalan.</p></div></article>
    <article class="feature-item"><span class="feature-icon"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></span><div><h3>Informasi Mudah</h3><p>Akses informasi kendaraan secara praktis.</p></div></article>
    <article class="feature-item"><span class="feature-icon"><svg viewBox="0 0 24 24"><path d="M4 5h16v14H4zM8 9h8M8 13h5"/></svg></span><div><h3>Pelayanan Transparan</h3><p>Informasi layanan yang jelas dan terarah.</p></div></article>
</section>

<section class="section process-section" id="layanan">
    <div class="container">
        <div class="section-heading"><div><span class="eyebrow">LAYANAN KAMI</span><h2>Lebih Mudah Memahami<br><span>Proses Pengujian KIR.</span></h2></div><p>Kenali tahapan umum pengujian kendaraan bermotor. Persyaratan dan alur rinci mengikuti ketentuan kantor pengujian setempat.</p></div>
        <div class="process-grid">
            <article class="process-card"><span class="process-number">01</span><div class="process-icon">▤</div><h3>Persiapan Dokumen</h3><p>Siapkan dokumen kendaraan sesuai persyaratan layanan yang berlaku.</p><span class="process-line"></span></article>
            <article class="process-card"><span class="process-number">02</span><div class="process-icon">⌕</div><h3>Pemeriksaan Kendaraan</h3><p>Kendaraan menjalani pemeriksaan teknis sesuai prosedur pengujian.</p><span class="process-line"></span></article>
            <article class="process-card"><span class="process-number">03</span><div class="process-icon">✓</div><h3>Evaluasi Hasil Uji</h3><p>Hasil pemeriksaan dievaluasi oleh petugas penguji berwenang.</p><span class="process-line"></span></article>
            <article class="process-card"><span class="process-number">04</span><div class="process-icon">▣</div><h3>Informasi Hasil</h3><p>Ikuti informasi hasil dan masa berlaku sesuai data yang tercatat.</p><span class="process-line"></span></article>
        </div>
    </div>
</section>

<section class="check-promo" id="cek">
    <div class="container check-promo-grid">
        <div class="check-promo-copy"><span class="eyebrow eyebrow-light">LAYANAN DIGITAL</span><h2>Cek Data Kendaraan<br>Tanpa Ribet.</h2><p>Gunakan nomor uji dan lima digit terakhir nomor rangka untuk mencari informasi kendaraan yang tercatat.</p><a class="button button-white" href="{{ route('vehicles.form') }}">Mulai Pengecekan <span>→</span></a><div class="secure-note"><span>◈</span> Pengecekan menggunakan data verifikasi kendaraan</div></div>
        <div class="check-illustration"><div class="circle circle-one"></div><div class="circle circle-two"></div><div class="truck-art"><svg viewBox="0 0 460 260" role="img" aria-label="Ilustrasi kendaraan angkutan"><path d="M45 62h230v118H45z" fill="#fff" stroke="#c9ddf5" stroke-width="4"/><path d="M275 100h78l58 60v20H275z" fill="#eaf4ff" stroke="#c9ddf5" stroke-width="4"/><path d="M293 111h49l37 39h-86z" fill="#6fb7f7"/><path d="M25 180h386v20H25z" fill="#fff"/><circle cx="106" cy="197" r="27" fill="#082f68"/><circle cx="106" cy="197" r="12" fill="#a9c9ec"/><circle cx="333" cy="197" r="27" fill="#082f68"/><circle cx="333" cy="197" r="12" fill="#a9c9ec"/><path d="M67 87h180M67 111h180M67 135h180" stroke="#d1e4f8" stroke-width="6" stroke-linecap="round"/><path d="M24 223h400" stroke="#6da6e9" stroke-width="4" stroke-linecap="round"/></svg></div><div class="floating-check"><span>✓</span><div><strong>Praktis & Terarah</strong><small>Informasi kendaraan dalam beberapa langkah</small></div></div><div class="check-dots"></div></div>
    </div>
</section>

<section class="section about-section" id="tentang">
    <div class="container about-grid">
        <div class="about-visual"><div class="about-photo"></div><div class="about-badge"><span>✦</span><div><strong>Keselamatan</strong><small>Menjadi prioritas bersama</small></div></div><div class="about-decoration"></div></div>
        <div class="about-copy"><span class="eyebrow">TENTANG PENGUJIAN KIR</span><h2>Membangun Transportasi yang <span>Aman dan Andal.</span></h2><p>Pengujian Kendaraan Bermotor merupakan bagian penting dalam mendukung keselamatan lalu lintas. Melalui pemeriksaan sesuai ketentuan, kondisi teknis kendaraan dapat dievaluasi agar memenuhi persyaratan laik jalan.</p><p>Website ini disiapkan sebagai pusat informasi layanan, persyaratan, dan pengecekan data kendaraan yang tersedia secara daring.</p><a class="text-link" href="#informasi">Lihat informasi layanan <span>→</span></a></div>
    </div>
</section>

<section class="section info-section" id="informasi">
    <div class="container">
        <div class="section-heading section-heading-center"><span class="eyebrow">PUSAT INFORMASI</span><h2>Informasi yang Anda <span>Butuhkan.</span></h2><p>Pilih informasi yang sesuai dengan kebutuhan Anda. Konfirmasi ketentuan terbaru kepada kantor pelayanan terkait.</p></div>
        <div class="info-grid">
            <a class="info-card" href="{{ route('vehicles.form') }}"><span class="info-icon">⌕</span><span class="info-card-arrow">↗</span><h3>Cek Data Kendaraan</h3><p>Telusuri informasi kendaraan menggunakan data verifikasi yang diminta.</p><span class="info-card-link">Mulai cek <b>→</b></span></a>
            <a class="info-card" href="{{ route('requirements') }}"><span class="info-icon">▤</span><span class="info-card-arrow">↗</span><h3>Persyaratan Pengujian</h3><p>Pastikan dokumen kendaraan dan persyaratan administrasi telah disiapkan.</p><span class="info-card-link">Informasi persyaratan <b>→</b></span></a>
            <a class="info-card" href="{{ route('service.schedule') }}"><span class="info-icon">◷</span><span class="info-card-arrow">↗</span><h3>Jadwal Pelayanan</h3><p>Dapatkan informasi jam pelayanan langsung dari kantor pengujian setempat.</p><span class="info-card-link">Lihat informasi <b>→</b></span></a>
            <a class="info-card" href="{{ route('berita.index') }}"><span class="info-icon">▧</span><span class="info-card-arrow">↗</span><h3>Berita & Pengumuman</h3><p>Ikuti pengumuman dan pembaruan layanan dari instansi terkait.</p><span class="info-card-link">Info layanan <b>→</b></span></a>
        </div>
    </div>
</section>
@include('pages.partials.contact')
@endsection
