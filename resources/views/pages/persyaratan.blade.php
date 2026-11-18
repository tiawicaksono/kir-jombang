
@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/persyaratan.css') }}">
@endpush

@section('title', 'Persyaratan Pengujian')

@section('meta_description', 'Informasi persyaratan Pengujian Kendaraan Bermotor UPT Dishub Kabupaten Jombang.')

@section('content')

<section class="req-hero">
    <div class="container req-hero-grid">
        <div>
            <span class="req-eyebrow">PUSAT INFORMASI LAYANAN</span>

            <h1>
                Persyaratan
                <span>Pengujian Kendaraan.</span>
            </h1>

            <p>
                Persiapkan dokumen dan kendaraan Anda sebelum datang
                ke tempat pelayanan agar proses pengujian lebih tertib
                dan lancar.
            </p>

            <div class="req-hero-actions">
                <a href="#daftar-persyaratan" class="req-btn req-btn-white">
                    Lihat Persyaratan <span>↓</span>
                </a>

                <a href="{{ route('vehicles.form') }}"
                   class="req-btn req-btn-outline">
                    Cek Data Kendaraan ↗
                </a>
            </div>
        </div>

        <div class="req-hero-card">
            <div class="req-hero-icon">▤</div>

            <span class="req-card-label">PERSIAPAN PENGUJIAN</span>

            <h2>
                Dokumen lengkap.<br>
                Pelayanan lebih siap.
            </h2>

            <p>
                Periksa kembali dokumen dan konfirmasikan ketentuan
                terbaru sebelum berangkat.
            </p>

            <div class="req-card-line"></div>

            <div class="req-card-footer">
                <span>01</span>
                <span>Dokumen · Kendaraan · Konfirmasi</span>
            </div>
        </div>
    </div>
</section>


<section class="req-content section" id="daftar-persyaratan">
    <div class="container">

        <div class="req-heading">
            <span class="req-eyebrow-dark">
                SEBELUM DATANG KE LOKASI
            </span>

            <h2>Siapkan kebutuhan pengujian Anda.</h2>

            <p>
                Berikut merupakan daftar dokumen yang perlu dipersiapkan
                berdasarkan jenis pelayanan pengujian kendaraan bermotor.
                Pastikan seluruh dokumen tersedia sebelum mengajukan layanan.
            </p>
        </div>


        <div class="req-notice">
            <span class="req-notice-icon">i</span>

            <div>
                <strong>Perhatian</strong>

                <p>
                    Persyaratan dapat berbeda berdasarkan jenis layanan
                    dan kondisi kendaraan. Pastikan dokumen yang dibawa
                    sesuai dengan ketentuan pelayanan yang berlaku.
                </p>
            </div>
        </div>


        {{-- DAFTAR JENIS UJI DAN PERSYARATAN --}}
        <div class="req-grid">

            @forelse($jenisUji as $jenis)

                <article class="req-panel">

                    <div class="req-panel-head">

                        <span class="req-panel-number">
                            {{ str_pad($jenis->id, 2, '0', STR_PAD_LEFT) }}
                        </span>

                        <div>
                            <span class="req-panel-kicker">
                                PERSYARATAN PENGUJIAN
                            </span>

                            <h3>{{ $jenis->nama }}</h3>
                        </div>

                    </div>


                    @if($jenis->keterangan)
                        <p class="req-panel-desc">
                            {{ $jenis->keterangan }}
                        </p>
                    @endif


                    <ul class="req-checklist">

                        @forelse(
                            $detailPersyaratan->get($jenis->id, collect())
                            as $detail
                        )

                            <li>
                                <span>✓</span>

                                <div>
                                    <strong>
                                        {{ $detail->persyaratan }}
                                    </strong>
                                </div>
                            </li>

                        @empty

                            <li class="req-empty-item">
                                <div>
                                    <small>
                                        Belum ada persyaratan yang tersedia.
                                    </small>
                                </div>
                            </li>

                        @endforelse

                    </ul>

                </article>

            @empty

                <div class="req-empty">
                    Belum ada data persyaratan pengujian.
                </div>

            @endforelse

        </div>

    </div>
</section>


<section class="req-steps-section">
    <div class="container">

        <div class="req-heading req-heading-light">
            <span class="req-eyebrow">ALUR PERSIAPAN</span>

            <h2>Empat langkah sebelum pengujian.</h2>
        </div>


        <div class="req-steps">

            <article>
                <span>01</span>
                <h3>Tentukan layanan</h3>
                <p>
                    Pastikan jenis pengujian yang dibutuhkan kendaraan Anda.
                </p>
            </article>

            <article>
                <span>02</span>
                <h3>Siapkan dokumen</h3>
                <p>
                    Periksa seluruh dokumen sesuai persyaratan jenis pengujian.
                </p>
            </article>

            <article>
                <span>03</span>
                <h3>Konfirmasi jadwal</h3>
                <p>
                    Tanyakan jadwal, biaya resmi, dan prosedur pendaftaran
                    kepada petugas.
                </p>
            </article>

            <article>
                <span>04</span>
                <h3>Datang untuk pengujian</h3>
                <p>
                    Bawa kendaraan dan dokumen sesuai ketentuan pelayanan.
                </p>
            </article>

        </div>

    </div>
</section>


<section class="req-bottom">
    <div class="container req-bottom-inner">

        <div>
            <span class="req-eyebrow-dark">LANGKAH SELANJUTNYA</span>

            <h2>Ingin memeriksa data kendaraan?</h2>

            <p>
                Gunakan layanan pengecekan data kendaraan yang tersedia
                di website.
            </p>
        </div>

        <a href="{{ route('vehicles.form') }}"
           class="req-btn req-btn-primary">
            Cek Data Kendaraan <span>→</span>
        </a>

    </div>
</section>

@endsection
