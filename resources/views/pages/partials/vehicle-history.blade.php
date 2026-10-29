<section class="vehicle-history-section">
    <div class="vehicle-history-heading">
        <div>
            <span class="vehicle-history-eyebrow">HISTORI KENDARAAN</span>
            <h2>Riwayat Pengujian</h2>
            <p>Riwayat pemeriksaan dan dokumentasi kendaraan.</p>
        </div>

        <span class="vehicle-history-count">
            {{ count($history ?? []) }} riwayat
        </span>
    </div>

    @forelse (($history ?? []) as $index => $item)
        @php
            $hasilUji = $item['hasil_uji'] ?? null;
            $fotos = $item['foto_kendaraan'] ?? [];
        @endphp

        <article class="vehicle-history-card">
            <div class="vehicle-history-card-heading">
                <div>
                    <span class="vehicle-history-index">
                        Riwayat #{{ $index + 1 }}
                    </span>

                    <h3>
                        @if (!empty($item['tanggal_uji']))
                            {{ \Carbon\Carbon::parse($item['tanggal_uji'])->format('d M Y') }}
                        @else
                            Tanggal pengujian tidak tersedia
                        @endif
                    </h3>
                </div>

                @if ($hasilUji && isset($hasilUji['tanggal_selesai']))
                    <span class="vehicle-history-finish-date">
                        Selesai:
                        {{ $hasilUji['tanggal_selesai'] ?: '-' }}
                    </span>
                @endif
            </div>

            <div class="vehicle-history-info-grid">
                <div class="vehicle-history-info">
                    <span>Tempat Pengujian</span>
                    <strong>{{ $item['tempat'] ?: '-' }}</strong>
                </div>

                <div class="vehicle-history-info">
                    <span>Tempat Tujuan</span>
                    <strong>{{ $item['tempat_tujuan'] ?: '-' }}</strong>
                </div>

                <div class="vehicle-history-info">
                    <span>Nama Penguji</span>
                    <strong>{{ $item['nama_penguji'] ?: '-' }}</strong>
                </div>

                <div class="vehicle-history-info">
                    <span>Pengujian Datang</span>
                    <strong>
                        {{ $hasilUji['tanggal_datang'] ?? '-' }}
                    </strong>
                </div>

                <div class="vehicle-history-info">
                    <span>Pengujian Ulang</span>
                    <strong>
                        {{ isset($hasilUji['uji_ulang'])
                            ? ($hasilUji['uji_ulang'] ? 'Ya' : 'Tidak')
                            : '-' }}
                    </strong>
                </div>
            </div>

            @if ($hasilUji)
                <div class="vehicle-history-results">
                    <h4>Hasil Pemeriksaan</h4>

                    <div class="vehicle-history-result-grid">
                        @foreach ([
                            'prauji',
                            'smoke',
                            'pitlift',
                            'lampu',
                            'break',
                        ] as $key)
                            @php
                                $result = $hasilUji[$key] ?? null;
                                $statusClass = $result['status_class'] ?? 'unknown';
                            @endphp

                            <div class="vehicle-history-result">
                                <span>
                                    {{ $result['label'] ?? ucfirst($key) }}
                                </span>

                                <strong class="vehicle-status vehicle-status-{{ $statusClass }}">
                                    {{ $result['status'] ?? 'BELUM ADA DATA' }}
                                </strong>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="vehicle-history-no-result">
                    Data hasil pemeriksaan tidak tersedia.
                </div>
            @endif

            @if (!empty($item['catatan']))
                <div class="vehicle-history-notes">
                    <span>Catatan</span>
                    <p>{{ $item['catatan'] }}</p>
                </div>
            @endif

            {{-- Foto hanya dibuka jika pengguna mengeklik bagian ini. --}}
            @if (count($fotos) > 0)
                <details class="vehicle-history-photo-details">
                    <summary>
                        <span class="vehicle-history-photo-icon">▧</span>

                        <span class="vehicle-history-photo-summary">
                            <strong>Lihat Foto Kendaraan</strong>
                            <small>
                                {{ count($fotos) }} foto dokumentasi
                            </small>
                        </span>

                        <span class="vehicle-history-photo-arrow">⌄</span>
                    </summary>

                    <div class="vehicle-history-photo-grid">
                        @foreach ($fotos as $foto)
                            <button
                                type="button"
                                class="vehicle-history-photo-button"
                                data-photo-src="{{ $foto['src'] }}"
                                data-photo-label="{{ $foto['label'] }}"
                                aria-label="Perbesar foto {{ $foto['label'] }}"
                            >
                                <div class="vehicle-history-photo-frame">
                                    <img
                                        data-src="{{ $foto['src'] }}"
                                        alt="{{ $foto['label'] }}"
                                        loading="lazy"
                                    >
                                </div>

                                <span>{{ $foto['label'] }}</span>
                                <small>Klik untuk memperbesar</small>
                            </button>
                        @endforeach
                    </div>
                </details>
            @else
                <div class="vehicle-history-no-photo">
                    Foto kendaraan tidak tersedia pada riwayat ini.
                </div>
            @endif
        </article>
    @empty
        <div class="vehicle-history-empty">
            <h3>Belum Ada Riwayat Pengujian</h3>
            <p>Riwayat pengujian kendaraan belum tersedia.</p>
        </div>
    @endforelse
</section>

{{-- Modal foto --}}
<div
    class="vehicle-history-photo-modal"
    id="vehicleHistoryPhotoModal"
    aria-hidden="true"
>
    <div
        class="vehicle-history-photo-backdrop"
        data-close-photo-modal
    ></div>

    <div
        class="vehicle-history-photo-modal-content"
        role="dialog"
        aria-modal="true"
        aria-label="Foto kendaraan"
    >
        <button
            type="button"
            class="vehicle-history-photo-close"
            data-close-photo-modal
            aria-label="Tutup foto"
        >&times;</button>

        <h3 id="vehicleHistoryPhotoModalTitle">Foto Kendaraan</h3>

        <img id="vehicleHistoryPhotoModalImage" src="" alt="">
    </div>
</div>

<script>
(function () {
    const initVehicleHistoryPhotos = function () {
        const root = document.querySelector('.vehicle-history-section');

        if (!root || root.dataset.photoInitialized === '1') {
            return;
        }

        root.dataset.photoInitialized = '1';

        const modal = document.getElementById('vehicleHistoryPhotoModal');
        const modalImage = document.getElementById('vehicleHistoryPhotoModalImage');
        const modalTitle = document.getElementById('vehicleHistoryPhotoModalTitle');

        if (!modal || !modalImage || !modalTitle) {
            return;
        }

        // Gambar baru diberi src ketika bagian foto dibuka.
        root.querySelectorAll('.vehicle-history-photo-details').forEach(function (details) {
            details.addEventListener('toggle', function () {
                if (!details.open) {
                    return;
                }

                details.querySelectorAll('img[data-src]').forEach(function (img) {
                    if (!img.getAttribute('src')) {
                        img.setAttribute('src', img.dataset.src);
                    }
                });
            });
        });

        // Delegasi event untuk tombol foto.
        root.addEventListener('click', function (event) {
            const button = event.target.closest('.vehicle-history-photo-button');

            if (!button) {
                return;
            }

            const src = button.dataset.photoSrc;

            if (!src) {
                return;
            }

            modalImage.src = src;
            modalImage.alt = button.dataset.photoLabel || 'Foto kendaraan';
            modalTitle.textContent = button.dataset.photoLabel || 'Foto Kendaraan';

            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('vehicle-history-modal-open');
        });

        const closeModal = function () {
            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
            modalImage.removeAttribute('src');
            document.body.classList.remove('vehicle-history-modal-open');
        };

        modal.querySelectorAll('[data-close-photo-modal]').forEach(function (element) {
            element.addEventListener('click', closeModal);
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && modal.classList.contains('is-open')) {
                closeModal();
            }
        });
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initVehicleHistoryPhotos);
    } else {
        initVehicleHistoryPhotos();
    }
})();
</script>