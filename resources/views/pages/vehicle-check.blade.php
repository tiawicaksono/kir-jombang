@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/data-kendaraan.css') }}">
    <link rel="stylesheet" href="{{ asset('css/riwayat-kendaraan.css') }}">
@endpush
@section('content')
<section class="vehicle-page">
    <div class="vehicle-page__hero">
        <div class="vehicle-page__hero-inner">
            <span class="vehicle-eyebrow">LAYANAN INFORMASI KENDARAAN</span>
            <h1>Cek Data Kendaraan</h1>
            <p>
                Periksa informasi kendaraan berdasarkan nomor uji dan
                lima digit terakhir nomor rangka.
            </p>
        </div>
    </div>

    <div class="vehicle-container">
        <div class="vehicle-search-card">
            <div class="vehicle-search-card__heading">
                <span class="vehicle-search-icon" aria-hidden="true">⌕</span>
                <div>
                    <h2>Pencarian Kendaraan</h2>
                    <p>Masukkan data sesuai identitas kendaraan.</p>
                </div>
            </div>

            <form
                action="{{ url('/cek-kendaraan') }}"
                method="POST"
                class="vehicle-search-form"
            >
                @csrf

                <div class="vehicle-form-grid">
                    <div class="vehicle-form-field">
                        <label for="nouji">Nomor Uji</label>
                        <input
                            id="nouji"
                            type="text"
                            name="nouji"
                            value="{{ old('nouji') }}"
                            placeholder="Contoh: ABC12345"
                            maxlength="30"
                            autocomplete="off"
                            spellcheck="false"
                            required
                        >

                        @error('nouji')
                            <small class="vehicle-error">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>

                    <div class="vehicle-form-field">
                        <label for="chassis_last5">
                            5 Digit Terakhir Nomor Rangka
                        </label>
                        <input
                            id="chassis_last5"
                            type="text"
                            name="chassis_last5"
                            value="{{ old('chassis_last5') }}"
                            placeholder="Contoh: 12345"
                            inputmode="numeric"
                            maxlength="5"
                            pattern="[0-9]{5}"
                            autocomplete="off"
                            required
                        >

                        @error('chassis_last5')
                            <small class="vehicle-error">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                </div>

                @error('lookup')
                    <div class="vehicle-alert vehicle-alert--error">
                        {{ $message }}
                    </div>
                @enderror

                <button type="submit" class="vehicle-search-button">
                    Cari Data Kendaraan
                    <span aria-hidden="true">→</span>
                </button>

                <p class="vehicle-search-note">
                    <span aria-hidden="true">ⓘ</span>
                    Data ditampilkan berdasarkan hasil pencocokan nomor uji
                    dan nomor rangka.
                </p>
            </form>
        </div>

        @if ($searched && !$vehicle)
            <div class="vehicle-empty-state">
                <div class="vehicle-empty-state__icon" aria-hidden="true">?</div>
                <h2>Data Kendaraan Tidak Ditemukan</h2>
                <p>
                    Periksa kembali nomor uji dan lima digit terakhir nomor
                    rangka, kemudian coba lakukan pencarian lagi.
                </p>
            </div>
        @endif

        @if ($vehicle)
            <div class="vehicle-result">
                <div class="vehicle-result__heading">
                    <div>
                        <span class="vehicle-eyebrow">HASIL PENCARIAN</span>
                        <h2>Informasi Kendaraan</h2>
                        <p>Data kendaraan sesuai identitas yang dicari.</p>
                    </div>

                    <span class="vehicle-result-badge">
                        <span></span> Data ditemukan
                    </span>
                </div>

                <div class="vehicle-identity">
                    <div class="vehicle-identity__item">
                        <span>Nomor Uji</span>
                        <strong>{{ $vehicle['nouji'] }}</strong>
                    </div>

                    <div class="vehicle-identity__item">
                        <span>Masa Berlaku Uji</span>
                        <strong>
                            {{ $vehicle['masaberlakuuji'] !== ''
                                ? $vehicle['masaberlakuuji']
                                : 'Tidak tersedia' }}
                        </strong>
                    </div>
                </div>

                <section class="vehicle-details-section">
                    <div class="vehicle-section-heading">
                        <span class="vehicle-section-heading__number">01</span>
                        <div>
                            <h3>Spesifikasi Kendaraan</h3>
                            <p>Identitas, berat, dan dimensi kendaraan.</p>
                        </div>
                    </div>

                    @if (count($vehicle['details']))
                        <div class="vehicle-details-grid">
                            @foreach ($vehicle['details'] as $item)
                                <div class="vehicle-detail">
                                    <span>{{ $item['label'] }}</span>
                                    <strong>{{ $item['value'] }}</strong>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="vehicle-muted">
                            Informasi spesifikasi belum tersedia.
                        </p>
                    @endif
                </section>

                @include('pages.partials.vehicle-history', [
                    'history' => $vehicle['riwayat'] ?? [],
                ])

                <p class="vehicle-disclaimer">
                    Informasi ini ditampilkan berdasarkan data yang tersedia
                    pada sistem pengujian kendaraan.
                </p>
            </div>
        @endif
    </div>
</section>
@endsection
