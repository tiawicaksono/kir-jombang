@extends('layouts.app')

@section('title', 'Berita & Pengumuman')

@section('meta_description', 'Berita, informasi, dan pengumuman layanan Dinas Perhubungan.')

@section('content')
<section class="news-hero">
    <div class="container news-hero-inner">
        <span class="news-eyebrow">INFORMASI TERKINI</span>
        <h1>Berita &amp; Pengumuman</h1>
        <p>
            Ikuti informasi dan kegiatan terbaru Dinas Perhubungan
            serta berbagai pengumuman layanan.
        </p>
    </div>
</section>

<section class="news-section">
    <div class="container">
        <div class="news-heading">
            <div>
                <span class="news-kicker">KABAR DISHUB</span>
                <h2>Berita dan pengumuman</h2>
                <p>Informasi yang dipublikasikan oleh pengelola.</p>
            </div>

            <span class="news-count">
                {{ $berita->total() }} berita
            </span>
        </div>

        @if ($berita->isNotEmpty())
            <div class="news-grid">
                @foreach ($berita as $item)
                    @php
                        $isYoutube = !empty($item->youtube_url);

                        $judul = $isYoutube && $item->youtube_title
                            ? $item->youtube_title
                            : $item->judul;

                        $gambar = $isYoutube
                            ? $item->youtube_thumbnail
                            : $item->gambar_src;

                        $urlDetail = $isYoutube
                            ? $item->youtube_url
                            : route('berita.show', $item->id);
                    @endphp

                    <article class="news-card">
                        <a
                            href="{{ $urlDetail }}"
                            class="news-card-image-link"
                            @if ($isYoutube)
                                target="_blank"
                                rel="noopener noreferrer"
                            @endif
                        >
                            <div class="news-card-image">
                                @if ($gambar)
                                    <img
                                        src="{{ $gambar }}"
                                        alt="{{ $judul }}"
                                        loading="lazy"
                                    >
                                @else
                                    <div class="news-no-image">
                                        <span>▤</span>
                                        <p>Berita Dishub</p>
                                    </div>
                                @endif

                                @if ($isYoutube)
                                    <span class="news-youtube-label">
                                        YOUTUBE
                                    </span>
                                    <span class="news-play">▶</span>
                                @endif
                            </div>
                        </a>

                        <div class="news-card-body">
                            <div class="news-date">
                                <span>●</span>

                                @if ($item->tanggal)
                                    {{ \Illuminate\Support\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                                @else
                                    Informasi Dishub
                                @endif
                            </div>

                            <h3>
                                <a
                                    href="{{ $urlDetail }}"
                                    class="news-title-link"
                                    @if ($isYoutube)
                                        target="_blank"
                                        rel="noopener noreferrer"
                                    @endif
                                >
                                    {{ $judul }}
                                </a>
                            </h3>

                            @if ($isYoutube)
                                <p class="news-excerpt">
                                    Video dari
                                    {{ $item->youtube_channel ?: 'YouTube' }}.
                                </p>
                            @else
                                <p class="news-excerpt">
                                    {{ \Illuminate\Support\Str::limit(
                                        trim(strip_tags((string) $item->berita)),
                                        160
                                    ) }}
                                </p>
                            @endif

                            <a
                                href="{{ $urlDetail }}"
                                class="news-detail-link"
                                @if ($isYoutube)
                                    target="_blank"
                                    rel="noopener noreferrer"
                                @endif
                            >
                                {{ $isYoutube ? 'Tonton di YouTube' : 'Baca selengkapnya' }}
                                <span>→</span>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="news-pagination">
                {{ $berita->links() }}
            </div>
        @else
            <div class="news-empty">
                <span>▤</span>
                <h3>Belum ada berita</h3>
                <p>Berita dan pengumuman akan ditampilkan di halaman ini.</p>
            </div>
        @endif
    </div>
</section>

<style>
.news-hero {
    position: relative;
    overflow: hidden;
    padding: 76px 0 72px;
    color: #fff;
    background:
        radial-gradient(circle at 85% 15%, rgba(74,153,255,.28), transparent 30%),
        linear-gradient(120deg, #062c67, #0757ae);
}
.news-hero-inner { position: relative; z-index: 1; }
.news-eyebrow, .news-kicker {
    display: inline-block;
    color: #64a9ff;
    font-size: .75rem;
    font-weight: 800;
    letter-spacing: .14em;
}
.news-hero .news-eyebrow { color: #b8dcff; }
.news-hero h1 {
    max-width: 760px;
    margin: 14px 0;
    font-size: clamp(2.1rem, 5vw, 3.5rem);
    line-height: 1.12;
    letter-spacing: -.04em;
}
.news-hero p {
    max-width: 620px;
    margin: 0;
    color: #d5e6ff;
    line-height: 1.8;
}
.news-section { padding: 58px 0 82px; }
.news-heading {
    display: flex;
    align-items: end;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 24px;
}
.news-heading h2 {
    margin: 8px 0;
    color: #102b50;
    font-size: clamp(1.5rem, 3vw, 2rem);
    letter-spacing: -.03em;
}
.news-heading p { margin: 0; color: #687b93; line-height: 1.7; }
.news-count {
    flex-shrink: 0;
    padding: 8px 12px;
    color: #31577f;
    font-size: .8rem;
    font-weight: 700;
    background: #eef5fc;
    border-radius: 30px;
}
.news-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 24px;
}
.news-card {
    min-width: 0;
    overflow: hidden;
    background: #fff;
    border: 1px solid #e3eaf2;
    border-radius: 16px;
    box-shadow: 0 8px 25px rgba(17,52,91,.045);
    transition: transform .2s ease, box-shadow .2s ease;
}
.news-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 32px rgba(17,52,91,.1);
}
.news-card-image-link {
    display: block;
    color: inherit;
    text-decoration: none;
}
.news-card-image {
    position: relative;
    height: 215px;
    overflow: hidden;
    background: #edf3fa;
}
.news-card-image img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .3s ease;
}
.news-card:hover .news-card-image img { transform: scale(1.035); }
.news-no-image {
    display: flex;
    width: 100%;
    height: 100%;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #7290b4;
    background: linear-gradient(135deg, #edf5ff, #dceaff);
}
.news-no-image span { font-size: 2.2rem; }
.news-no-image p { margin: 8px 0 0; font-size: .85rem; }
.news-youtube-label {
    position: absolute;
    top: 12px;
    left: 12px;
    padding: 6px 9px;
    color: #fff;
    font-size: .68rem;
    font-weight: 800;
    letter-spacing: .06em;
    background: #e62117;
    border-radius: 5px;
}
.news-play {
    position: absolute;
    top: 50%;
    left: 50%;
    display: grid;
    width: 54px;
    height: 54px;
    place-items: center;
    padding-left: 3px;
    color: #fff;
    font-size: 1.15rem;
    background: #e62117;
    border: 3px solid rgba(255,255,255,.9);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    box-shadow: 0 5px 18px rgba(0,0,0,.2);
}
.news-card-body { padding: 22px; }
.news-date {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #75869b;
    font-size: .78rem;
}
.news-date span { color: #1681e8; font-size: .55rem; }
.news-card h3 {
    margin: 13px 0 10px;
    color: #132f53;
    font-size: 1.12rem;
    line-height: 1.5;
}
.news-title-link {
    color: inherit;
    text-decoration: none;
}
.news-title-link:hover { color: #0757ae; }
.news-excerpt {
    margin: 0;
    color: #687b93;
    font-size: .91rem;
    line-height: 1.8;
    overflow-wrap: anywhere;
}
.news-detail-link {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    margin-top: 17px;
    color: #0757ae;
    font-size: .88rem;
    font-weight: 800;
    text-decoration: none;
}
.news-detail-link:hover { color: #063b78; }
.news-pagination { margin-top: 35px; }
.news-empty {
    padding: 55px 20px;
    text-align: center;
    background: #f7faff;
    border: 1px dashed #cbd9e9;
    border-radius: 16px;
}
.news-empty > span { color: #5c8ecb; font-size: 2.4rem; }
.news-empty h3 { margin: 12px 0 8px; color: #17385f; }
.news-empty p { margin: 0; color: #718198; }
@media (max-width: 900px) {
    .news-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}
@media (max-width: 600px) {
    .news-hero { padding: 55px 0; }
    .news-section { padding: 40px 0 55px; }
    .news-grid { grid-template-columns: 1fr; gap: 18px; }
    .news-card-image { height: 230px; }
    .news-heading { align-items: flex-start; }
    .news-count { font-size: .73rem; }
}
</style>
@endsection
