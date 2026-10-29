@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/jadwal-layanan.css') }}">
@endpush
@section('title', 'Jadwal Pelayanan')
@section('meta_description', 'Informasi jadwal pelayanan dan pengujian kendaraan bermotor Dishub.')

@section('content')
<section class="schedule-hero">
    <div class="container schedule-hero-inner">
        <div class="schedule-hero-copy">
            <span class="schedule-eyebrow">INFORMASI PELAYANAN</span>
            <h1>Jadwal <span>Pelayanan</span></h1>
            <p>
                Persiapkan kunjungan Anda untuk layanan Pengujian
                Kendaraan Bermotor. Periksa informasi pelayanan sebelum
                datang ke lokasi pengujian.
            </p>

            <div class="schedule-hero-actions">
                <a href="#jadwal" class="schedule-btn schedule-btn-white">
                    Lihat Jadwal <span>↓</span>
                </a>
                <a href="{{ route('vehicles.form') }}"
                   class="schedule-btn schedule-btn-outline">
                    Cek Kendaraan <span>↗</span>
                </a>
            </div>
        </div>

        <div class="schedule-hero-visual" aria-hidden="true">
            <div class="schedule-calendar">
                <div class="schedule-calendar-top">
                    <span>INFO PELAYANAN</span>
                    <span class="schedule-calendar-dot"></span>
                </div>
                <div class="schedule-calendar-icon">▦</div>
                <strong>Atur waktu</strong>
                <p>Pastikan jadwal sebelum berkunjung.</p>
                <div class="schedule-calendar-line"></div>
                <div class="schedule-calendar-line short"></div>
                <div class="schedule-calendar-footer">
                    <span>Dishub</span>
                    <span>•</span>
                    <span>Pengujian Kendaraan</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="schedule-section" id="jadwal">
    <div class="container">
        <div class="schedule-section-heading">
            <div>
                <span class="schedule-eyebrow schedule-eyebrow-blue">
                    WAKTU PELAYANAN
                </span>
                <h2>Informasi Jadwal</h2>
                <p>
                    Gunakan informasi berikut sebagai panduan untuk
                    merencanakan kunjungan Anda.
                </p>
            </div>
            <span class="schedule-status">
                <span></span> Informasi pelayanan
            </span>
        </div>

        <div class="schedule-notice">
            <span class="schedule-notice-icon">i</span>
            <div>
                <strong>Informasi Jam Pelayanan</strong>
                <p>
                    Pelayanan Pengujian Kendaraan Bermotor berlangsung pada
                    hari Senin sampai Kamis pukul 08.00–11.00 WIB dan Jumat
                    pukul 08.00–10.00 WIB. Sabtu, Minggu, dan hari libur
                    nasional atau tanggal merah tidak ada pelayanan.
                </p>
            </div>
        </div>

        <div class="schedule-grid">
            <article class="schedule-card">
                <div class="schedule-card-icon">◷</div>
                <span class="schedule-card-label">SENIN – KAMIS</span>
                <h3>08.00 – 11.00 WIB</h3>
                <p>
                    Pelayanan pengujian kendaraan bermotor pada hari
                    Senin, Selasa, Rabu, dan Kamis.
                </p>
                <div class="schedule-card-footer">
                    <span>Hari kerja</span>
                    <span>→</span>
                </div>
            </article>

            <article class="schedule-card">
                <div class="schedule-card-icon">◴</div>
                <span class="schedule-card-label">HARI JUMAT</span>
                <h3>08.00 – 10.00 WIB</h3>
                <p>
                    Pelayanan pengujian kendaraan bermotor pada hari Jumat
                    dengan jam pelayanan lebih singkat.
                </p>
                <div class="schedule-card-footer">
                    <span>Hari kerja</span>
                    <span>→</span>
                </div>
            </article>

            <article class="schedule-card">
                <div class="schedule-card-icon">▣</div>
                <span class="schedule-card-label">HARI LIBUR</span>
                <h3>Tidak Ada Pelayanan</h3>
                <p>
                    Sabtu, Minggu, serta hari libur nasional dan tanggal
                    merah tidak ada pelayanan pengujian.
                </p>
                <div class="schedule-card-footer">
                    <span>Pelayanan tutup</span>
                    <span>—</span>
                </div>
            </article>
        </div>
    </div>
</section>

<section class="schedule-steps-section">
    <div class="container schedule-steps-layout">
        <div class="schedule-steps-intro">
            <span class="schedule-eyebrow schedule-eyebrow-blue">
                RENCANAKAN KUNJUNGAN
            </span>
            <h2>Datang dengan persiapan yang tepat.</h2>
            <p>
                Beberapa langkah sederhana membantu Anda mempersiapkan
                proses pelayanan dengan lebih baik.
            </p>
        </div>

        <div class="schedule-steps">
            <article class="schedule-step">
                <span class="schedule-step-number">01</span>
                <div>
                    <h3>Sesuaikan waktu kedatangan</h3>
                    <p>
                        Datang pada Senin–Kamis pukul 08.00–11.00 WIB
                        atau Jumat pukul 08.00–10.00 WIB. Hindari
                        hari Sabtu, Minggu, dan hari libur nasional.
                    </p>
                </div>
            </article>

            <article class="schedule-step">
                <span class="schedule-step-number">02</span>
                <div>
                    <h3>Siapkan dokumen</h3>
                    <p>
                        Pelajari dokumen yang dibutuhkan sesuai jenis
                        pengujian kendaraan Anda.
                    </p>
                </div>
            </article>

            <article class="schedule-step">
                <span class="schedule-step-number">03</span>
                <div>
                    <h3>Periksa data kendaraan</h3>
                    <p>
                        Gunakan layanan pengecekan kendaraan untuk melihat
                        data kendaraan yang tersedia.
                    </p>
                </div>
            </article>
        </div>
    </div>
</section>

<section class="schedule-cta-section">
    <div class="container">
        <div class="schedule-cta">
            <div>
                <span class="schedule-eyebrow">LAYANAN DIGITAL</span>
                <h2>Persiapkan pengujian kendaraan Anda.</h2>
                <p>
                    Periksa data kendaraan dan pelajari persyaratan
                    sebelum datang ke lokasi pelayanan.
                </p>
            </div>
            <div class="schedule-cta-actions">
                <a href="{{ route('vehicles.form') }}"
                   class="schedule-btn schedule-btn-white">
                    Cek Kendaraan <span>↗</span>
                </a>
                <a href="{{ route('requirements') }}"
                   class="schedule-cta-link">
                    Persyaratan pengujian →
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
