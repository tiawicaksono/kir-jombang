@extends('layouts.app')

@section('title', $item->judul)

@section('meta_description', \Illuminate\Support\Str::limit(
    trim(strip_tags($item->berita ?? '')),
    160
))

@section('content')
@php
    /*
    |--------------------------------------------------------------------------
    | Persiapan data gambar
    |--------------------------------------------------------------------------
    */
    $gambar = trim((string) ($item->gambar_src ?? $item->gambar ?? ''));

    if ($gambar !== '' && !preg_match('/^data:image\//i', $gambar)) {
        $gambar = 'data:image/jpeg;base64,' . preg_replace('/\s+/', '', $gambar);
    }

    /*
    |--------------------------------------------------------------------------
    | Persiapan tanggal
    |--------------------------------------------------------------------------
    */
    $tanggalBerita = null;

    if (!empty($item->tanggal)) {
        try {
            $tanggalBerita = \Illuminate\Support\Carbon::parse($item->tanggal);
        } catch (\Throwable $e) {
            $tanggalBerita = null;
        }
    }
@endphp

<section class="article-hero">
    <div class="container">
        <nav class="article-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ url('/') }}">Beranda</a>
            <span>/</span>
            <a href="{{ route('berita.index') }}">Berita</a>
            <span>/</span>
            <span class="breadcrumb-current">Detail Berita</span>
        </nav>

        <a class="article-back" href="{{ route('berita.index') }}">
            <span aria-hidden="true">←</span>
            Kembali ke Berita
        </a>

        <div class="article-category">
            <span class="category-dot"></span>
            BERITA &amp; PENGUMUMAN
        </div>

        <h1>{{ $item->judul }}</h1>

        <div class="article-meta">
            <span class="article-meta-item">
                <span class="meta-icon" aria-hidden="true">◷</span>
                {{ $tanggalBerita
                    ? $tanggalBerita->translatedFormat('d F Y, H:i') . ' WIB'
                    : 'Tanggal tidak tersedia' }}
            </span>

            <span class="article-meta-divider"></span>

            <span class="article-meta-item">
                <span class="meta-icon" aria-hidden="true">▤</span>
                Dinas Perhubungan
            </span>
        </div>
    </div>

    <div class="hero-decoration hero-decoration-one"></div>
    <div class="hero-decoration hero-decoration-two"></div>
</section>

<section class="article-section">
    <div class="container">
        <div class="article-layout">

            {{-- KONTEN UTAMA --}}
            <main class="article-main">

                @if ($gambar !== '')
                    <div class="article-cover">
                        <img
                            src="{{ $gambar }}"
                            alt="{{ $item->judul }}"
                            loading="eager"
                            onerror="this.closest('.article-cover').classList.add('image-error'); this.remove();"
                        >

                        <div class="article-cover-fallback">
                            <span aria-hidden="true">▧</span>
                            <p>Gambar berita tidak tersedia</p>
                        </div>
                    </div>
                @endif

                <div class="article-body">
                    <div class="article-body-label">
                        <span></span>
                        INFORMASI RESMI
                    </div>

                    <div class="article-content">
                        {!! nl2br(e((string) ($item->berita ?? ''))) !!}
                    </div>

                    <div class="article-share">
                        <div class="share-text">
                            <strong>Bagikan informasi</strong>
                            <span>Bantu sebarkan informasi kepada masyarakat.</span>
                        </div>

                        <button
                            type="button"
                            class="share-button"
                            onclick="shareBerita()"
                            aria-label="Bagikan berita"
                        >
                            <span aria-hidden="true">↗</span>
                            Bagikan
                        </button>
                    </div>
                </div>

                <div class="article-bottom">
                    <a href="{{ route('berita.index') }}" class="back-to-list">
                        <span aria-hidden="true">←</span>
                        Kembali ke semua berita
                    </a>

                    <span class="article-bottom-brand">
                        Informasi Dishub
                    </span>
                </div>
            </main>

            {{-- SIDEBAR --}}
            <aside class="article-sidebar">

                <div class="article-sidebar-card">
                    <div class="sidebar-icon" aria-hidden="true">✦</div>

                    <span class="article-sidebar-label">
                        INFORMASI PUBLIK
                    </span>

                    <h2>Informasi resmi untuk masyarakat</h2>

                    <p>
                        Dapatkan berita, pengumuman, dan informasi terbaru
                        seputar pelayanan Dinas Perhubungan.
                    </p>

                    <a href="{{ route('berita.index') }}" class="sidebar-cta">
                        Jelajahi berita
                        <span aria-hidden="true">→</span>
                    </a>

                    <div class="sidebar-decoration"></div>
                </div>

                @if (isset($beritaLainnya) && $beritaLainnya->isNotEmpty())
                    <div class="article-related">
                        <div class="related-heading">
                            <div>
                                <span class="related-eyebrow">BACA JUGA</span>
                                <h3>Berita lainnya</h3>
                            </div>

                            <a
                                href="{{ route('berita.index') }}"
                                aria-label="Lihat semua berita"
                                title="Lihat semua berita"
                            >↗</a>
                        </div>

                        <div class="related-list">
                            @foreach ($beritaLainnya as $beritaLain)
                                @php
                                    $tanggalLain = null;

                                    if (!empty($beritaLain->tanggal)) {
                                        try {
                                            $tanggalLain = \Illuminate\Support\Carbon::parse(
                                                $beritaLain->tanggal
                                            );
                                        } catch (\Throwable $e) {
                                            $tanggalLain = null;
                                        }
                                    }
                                @endphp

                                <a
                                    class="article-related-item"
                                    href="{{ route('berita.show', $beritaLain->id) }}"
                                >
                                    <span class="article-related-date">
                                        <span aria-hidden="true">◷</span>
                                        {{ $tanggalLain
                                            ? $tanggalLain->translatedFormat('d M Y')
                                            : 'Tanggal tidak tersedia' }}
                                    </span>

                                    <strong>{{ $beritaLain->judul }}</strong>

                                    <span class="article-related-arrow" aria-hidden="true">
                                        →
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="article-help-card">
                    <span class="help-icon" aria-hidden="true">i</span>
                    <div>
                        <strong>Butuh informasi lain?</strong>
                        <p>Kunjungi halaman berita dan pengumuman kami.</p>
                        <a href="{{ route('berita.index') }}">
                            Lihat semua informasi
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

<style>
/* =========================================================
   DETAIL BERITA - DISHUB
   ========================================================= */

.article-hero {
    position: relative;
    isolation: isolate;
    overflow: hidden;
    padding: 30px 0 62px;
    color: #fff;
    background:
        radial-gradient(circle at 90% 10%, rgba(48, 158, 255, .22), transparent 30%),
        linear-gradient(120deg, #062554 0%, #07478f 55%, #0878c9 100%);
}

.article-hero .container {
    position: relative;
    z-index: 2;
}

.article-breadcrumb {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 10px;
    margin-bottom: 35px;
    color: rgba(222, 239, 255, .65);
    font-size: 11px;
}

.article-breadcrumb a {
    color: #d9ebff;
    text-decoration: none;
    transition: color .2s ease;
}

.article-breadcrumb a:hover {
    color: #fff;
}

.breadcrumb-current {
    color: #fff;
}

.article-back {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 30px;
    color: #d6e9ff;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    transition: gap .2s ease, color .2s ease;
}

.article-back:hover {
    gap: 14px;
    color: #fff;
}

.article-back span {
    display: grid;
    width: 30px;
    height: 30px;
    place-items: center;
    border: 1px solid rgba(255, 255, 255, .22);
    border-radius: 9px;
    background: rgba(255, 255, 255, .08);
    font-size: 17px;
}

.article-category {
    display: flex;
    align-items: center;
    gap: 9px;
    margin-bottom: 16px;
    color: #a9d9ff;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.9px;
}

.category-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #57c4ff;
    box-shadow: 0 0 0 4px rgba(87, 196, 255, .13);
}

.article-hero h1 {
    max-width: 940px;
    margin: 0;
    color: #fff;
    font-size: clamp(27px, 3.6vw, 43px);
    font-weight: 800;
    line-height: 1.28;
    letter-spacing: -.9px;
    overflow-wrap: anywhere;
}

.article-meta {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 17px;
    margin-top: 25px;
    color: #d5e8ff;
    font-size: 11px;
}

.article-meta-item {
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.meta-icon {
    color: #8bcaff;
    font-size: 16px;
}

.article-meta-divider {
    width: 4px;
    height: 4px;
    border-radius: 50%;
    background: rgba(255, 255, 255, .5);
}

.hero-decoration {
    position: absolute;
    z-index: 1;
    pointer-events: none;
    border: 1px solid rgba(255, 255, 255, .08);
    border-radius: 50%;
}

.hero-decoration-one {
    top: -190px;
    right: -90px;
    width: 440px;
    height: 440px;
}

.hero-decoration-two {
    top: -130px;
    right: -30px;
    width: 320px;
    height: 320px;
}

/* =========================================================
   MAIN LAYOUT
   ========================================================= */

.article-section {
    min-height: 400px;
    padding: 52px 0 85px;
    background: #f4f8fd;
}

.article-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 300px;
    align-items: start;
    gap: 28px;
}

.article-main {
    min-width: 0;
    overflow: hidden;
    border: 1px solid #e4edf7;
    border-radius: 17px;
    background: #fff;
    box-shadow: 0 12px 38px rgba(20, 56, 100, .055);
}

/* =========================================================
   COVER IMAGE
   ========================================================= */

.article-cover {
    position: relative;
    display: grid;
    min-height: 160px;
    max-height: 500px;
    overflow: hidden;
    place-items: center;
    background: #eaf3fc;
}

.article-cover img {
    position: relative;
    z-index: 2;
    display: block;
    width: 100%;
    max-height: 500px;
    object-fit: contain;
    background: #edf4fb;
}

.article-cover-fallback {
    display: none;
    width: 100%;
    min-height: 190px;
    padding: 30px;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #7890ab;
    text-align: center;
}

.article-cover-fallback > span {
    font-size: 35px;
}

.article-cover-fallback p {
    margin: 10px 0 0;
    font-size: 12px;
}

.article-cover.image-error .article-cover-fallback {
    display: flex;
}

/* =========================================================
   ARTICLE CONTENT
   ========================================================= */

.article-body {
    padding: 35px 38px 25px;
}

.article-body-label {
    display: flex;
    align-items: center;
    gap: 9px;
    margin-bottom: 23px;
    color: #1874bd;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.3px;
}

.article-body-label span {
    width: 24px;
    height: 3px;
    border-radius: 3px;
    background: #1684d4;
}

.article-content {
    color: #405773;
    font-size: 14px;
    line-height: 2.05;
    overflow-wrap: anywhere;
    word-break: normal;
}

.article-content::first-letter {
    color: inherit;
}

.article-share {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-top: 36px;
    padding: 20px 0 5px;
    border-top: 1px solid #edf2f8;
}

.share-text {
    display: grid;
    gap: 5px;
}

.share-text strong {
    color: #183b62;
    font-size: 12px;
}

.share-text span {
    color: #8494a8;
    font-size: 11px;
    line-height: 1.7;
}

.share-button {
    display: inline-flex;
    flex-shrink: 0;
    align-items: center;
    justify-content: center;
    gap: 9px;
    min-height: 39px;
    padding: 0 16px;
    border: 1px solid #dceafa;
    border-radius: 9px;
    color: #0869b5;
    background: #f2f8ff;
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    cursor: pointer;
    transition: background .2s ease, border-color .2s ease;
}

.share-button:hover {
    border-color: #a8d1f5;
    background: #e5f2ff;
}

.share-button span {
    font-size: 16px;
}

/* =========================================================
   FOOTER ARTICLE
   ========================================================= */

.article-bottom {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin: 0 38px;
    padding: 22px 0 27px;
    border-top: 1px solid #edf2f8;
}

.back-to-list {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    color: #0875cb;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
}

.back-to-list:hover {
    color: #064b91;
}

.back-to-list span {
    font-size: 17px;
}

.article-bottom-brand {
    color: #a1afc0;
    font-size: 10px;
}

/* =========================================================
   SIDEBAR
   ========================================================= */

.article-sidebar {
    display: grid;
    gap: 20px;
    min-width: 0;
}

.article-sidebar-card {
    position: relative;
    isolation: isolate;
    overflow: hidden;
    padding: 27px 25px 24px;
    border: 0;
    border-radius: 16px;
    color: #fff;
    background: linear-gradient(145deg, #073675 0%, #0755a6 60%, #087bd0 100%);
    box-shadow: 0 12px 30px rgba(6, 67, 133, .14);
}

.article-sidebar-card > *:not(.sidebar-decoration) {
    position: relative;
    z-index: 2;
}

.sidebar-icon {
    display: grid;
    width: 43px;
    height: 43px;
    margin-bottom: 24px;
    place-items: center;
    border: 1px solid rgba(255, 255, 255, .18);
    border-radius: 12px;
    color: #d8efff;
    background: rgba(255, 255, 255, .1);
    font-size: 23px;
}

.article-sidebar-label {
    color: #9dd4ff;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.5px;
}

.article-sidebar-card h2 {
    margin: 13px 0 12px;
    color: #fff;
    font-size: 22px;
    line-height: 1.4;
    letter-spacing: -.3px;
}

.article-sidebar-card p {
    margin: 0 0 22px;
    color: #d7eaff;
    font-size: 12px;
    line-height: 1.9;
}

.sidebar-cta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    padding-top: 17px;
    border-top: 1px solid rgba(255, 255, 255, .2);
    color: #fff;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
}

.sidebar-cta span {
    font-size: 18px;
    transition: transform .2s ease;
}

.sidebar-cta:hover span {
    transform: translateX(4px);
}

.sidebar-decoration {
    position: absolute;
    z-index: 1;
    top: -65px;
    right: -75px;
    width: 180px;
    height: 180px;
    border: 1px solid rgba(255, 255, 255, .09);
    border-radius: 50%;
    pointer-events: none;
}

/* =========================================================
   RELATED NEWS
   ========================================================= */

.article-related {
    padding: 23px 22px 10px;
    border: 1px solid #e4edf7;
    border-radius: 15px;
    background: #fff;
    box-shadow: 0 7px 24px rgba(21, 54, 95, .035);
}

.related-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 17px;
}

.related-eyebrow {
    color: #1680ca;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.5px;
}

.related-heading h3 {
    margin: 7px 0 0;
    color: #17385f;
    font-size: 18px;
    letter-spacing: -.3px;
}

.related-heading > a {
    display: grid;
    width: 34px;
    height: 34px;
    flex-shrink: 0;
    place-items: center;
    border: 1px solid #e2edf8;
    border-radius: 9px;
    color: #0875cb;
    background: #f5faff;
    text-decoration: none;
    font-size: 16px;
}

.article-related-item {
    position: relative;
    display: block;
    padding: 16px 24px 17px 0;
    border-top: 1px solid #edf2f8;
    text-decoration: none;
}

.article-related-date {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 8px;
    color: #8798ad;
    font-size: 10px;
}

.article-related-item strong {
    display: block;
    color: #294766;
    font-size: 12px;
    line-height: 1.75;
    overflow-wrap: anywhere;
    transition: color .2s ease;
}

.article-related-item:hover strong {
    color: #0875cb;
}

.article-related-arrow {
    position: absolute;
    top: 42px;
    right: 0;
    color: #1680ca;
    font-size: 15px;
    transition: transform .2s ease;
}

.article-related-item:hover .article-related-arrow {
    transform: translateX(3px);
}

/* =========================================================
   HELP CARD
   ========================================================= */

.article-help-card {
    display: flex;
    align-items: flex-start;
    gap: 13px;
    padding: 20px;
    border: 1px solid #e4edf7;
    border-radius: 14px;
    background: #fff;
}

.help-icon {
    display: grid;
    width: 30px;
    height: 30px;
    flex-shrink: 0;
    place-items: center;
    border-radius: 9px;
    color: #0875cb;
    background: #eaf5ff;
    font-size: 15px;
    font-weight: 800;
}

.article-help-card strong {
    display: block;
    margin-bottom: 6px;
    color: #244565;
    font-size: 12px;
}

.article-help-card p {
    margin: 0 0 10px;
    color: #8192a7;
    font-size: 11px;
    line-height: 1.8;
}

.article-help-card a {
    color: #0875cb;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
}

/* =========================================================
   RESPONSIVE
   ========================================================= */

@media (min-width: 1200px) {
    .article-layout {
        grid-template-columns: minmax(0, 1fr) 300px;
        gap: 32px;
    }

    .article-body {
        padding: 40px 44px 28px;
    }

    .article-bottom {
        margin-right: 44px;
        margin-left: 44px;
    }
}

@media (max-width: 950px) {
    .article-layout {
        grid-template-columns: minmax(0, 1fr) 260px;
        gap: 20px;
    }

    .article-body {
        padding: 28px;
    }

    .article-bottom {
        margin-right: 28px;
        margin-left: 28px;
    }

    .article-sidebar-card {
        padding: 23px 20px;
    }

    .article-sidebar-card h2 {
        font-size: 20px;
    }
}

@media (max-width: 760px) {
    .article-hero {
        padding: 25px 0 43px;
    }

    .article-breadcrumb {
        margin-bottom: 27px;
    }

    .article-back {
        margin-bottom: 25px;
    }

    .article-layout {
        grid-template-columns: minmax(0, 1fr);
        gap: 24px;
    }

    .article-sidebar {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        align-items: start;
    }

    .article-sidebar-card {
        grid-column: 1 / -1;
    }

    .article-related,
    .article-help-card {
        min-width: 0;
    }

    .article-help-card {
        padding: 17px;
    }

    .article-section {
        padding: 30px 0 55px;
    }
}

@media (max-width: 520px) {
    .article-hero {
        padding: 22px 0 37px;
    }

    .article-breadcrumb {
        gap: 7px;
        margin-bottom: 24px;
        font-size: 10px;
    }

    .article-category {
        font-size: 9px;
        letter-spacing: 1.4px;
    }

    .article-hero h1 {
        font-size: 27px;
        letter-spacing: -.5px;
    }

    .article-meta {
        gap: 10px;
        margin-top: 19px;
        font-size: 10px;
    }

    .article-meta-divider {
        display: none;
    }

    .article-section {
        padding: 23px 0 45px;
    }

    .article-main {
        border-radius: 13px;
    }

    .article-cover,
    .article-cover img {
        max-height: 320px;
    }

    .article-body {
        padding: 24px 20px 20px;
    }

    .article-body-label {
        margin-bottom: 17px;
    }

    .article-content {
        font-size: 13px;
        line-height: 1.95;
    }

    .article-share {
        align-items: flex-start;
        flex-direction: column;
        gap: 15px;
        margin-top: 26px;
    }

    .share-button {
        width: 100%;
    }

    .article-bottom {
        align-items: flex-start;
        flex-direction: column;
        gap: 12px;
        margin: 0 20px;
        padding: 18px 0 22px;
    }

    .article-sidebar {
        grid-template-columns: minmax(0, 1fr);
    }

    .article-sidebar-card {
        grid-column: auto;
        padding: 25px;
    }

    .article-related {
        padding: 22px 20px 8px;
    }
}
</style>

<script>
function shareBerita() {
    const shareData = {
        title: @json($item->judul),
        text: @json(\Illuminate\Support\Str::limit(
            trim(strip_tags($item->berita ?? '')),
            140
        )),
        url: window.location.href
    };

    if (navigator.share) {
        navigator.share(shareData).catch(function (error) {
            if (error && error.name !== 'AbortError') {
                copyBeritaUrl();
            }
        });
        return;
    }

    copyBeritaUrl();
}

function copyBeritaUrl() {
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(window.location.href)
            .then(function () {
                alert('Tautan berita berhasil disalin.');
            })
            .catch(function () {
                window.prompt('Salin tautan berita berikut:', window.location.href);
            });
        return;
    }

    window.prompt('Salin tautan berita berikut:', window.location.href);
}
</script>
@endsection