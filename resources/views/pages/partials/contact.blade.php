{{-- resources/views/partials/contact.blade.php --}}

<section class="contact-section" id="kontak">
    <div class="container">
        <div class="contact-heading">
            <span class="eyebrow">HUBUNGI KAMI</span>
            <h2>Informasi <span>Kontak.</span></h2>
            <p>
                Hubungi layanan Pengujian Kendaraan Bermotor Jombang
                atau kunjungi lokasi pelayanan untuk mendapatkan informasi lebih lanjut.
            </p>
        </div>

        <div class="contact-grid">
            {{-- ALAMAT --}}
            <article class="contact-card">
                <div class="contact-icon contact-icon-blue">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/>
                        <circle cx="12" cy="10" r="2.5"/>
                    </svg>
                </div>

                <span class="contact-label">LOKASI PELAYANAN</span>
                <h3>Uji KIR PKB Jombang</h3>
                <p>
                    Desa Janti, Kecamatan Jogoroto,
                    Kabupaten Jombang, Jawa Timur 61485.
                </p>

                <a class="contact-link"
                   href="https://www.google.com/maps/search/?api=1&query=Uji+KIR+PKB+JOMBANG"
                   target="_blank" rel="noopener noreferrer">
                    Buka Google Maps <span>↗</span>
                </a>
            </article>

            {{-- TELEPON --}}
            <article class="contact-card">
                <div class="contact-icon contact-icon-green">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M22 16.9v3a2 2 0 0 1-2.2 2
                            19.8 19.8 0 0 1-8.6-3.1
                            19.4 19.4 0 0 1-6-6
                            19.8 19.8 0 0 1-3.1-8.7
                            A2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7
                            c.1 1 .4 2 .7 2.9a2 2 0 0 1-.5 2.1L8 9.9
                            a16 16 0 0 0 6 6l1.2-1.3
                            a2 2 0 0 1 2.1-.5c.9.3 1.9.6 2.9.7
                            a2 2 0 0 1 1.8 2.1Z"/>
                    </svg>
                </div>

                <span class="contact-label">NOMOR TELEPON</span>
                <h3>Hubungi Kami</h3>
                <p>
                    Untuk pertanyaan seputar informasi pelayanan,
                    silakan hubungi nomor telepon berikut.
                </p>

                <a class="contact-link" href="tel:+6281235905090">
                    0812-3590-5090 <span>↗</span>
                </a>
            </article>

            {{-- INSTAGRAM --}}
            <article class="contact-card">
                <div class="contact-icon contact-icon-pink">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <rect x="3" y="3" width="18" height="18" rx="5"/>
                        <circle cx="12" cy="12" r="4"/>
                        <circle cx="17.5" cy="6.5" r=".8"
                                fill="currentColor" stroke="none"/>
                    </svg>
                </div>

                <span class="contact-label">MEDIA SOSIAL</span>
                <h3>Instagram</h3>
                <p>
                    Ikuti informasi, dokumentasi, dan pembaruan
                    seputar layanan Uji KIR PKB Jombang.
                </p>

                <a class="contact-link"
                   href="https://www.instagram.com/ujikir.jombang/"
                   target="_blank" rel="noopener noreferrer">
                    @ujikir.jombang <span>↗</span>
                </a>
            </article>
        </div>

        {{-- PETA LOKASI --}}
        <div class="contact-map">
            <div class="contact-map-content">
                <span class="contact-label">TEMUKAN LOKASI KAMI</span>
                <h3>Uji KIR PKB Jombang</h3>
                <p>
                    Desa Janti, Kecamatan Jogoroto,
                    Kabupaten Jombang, Jawa Timur 61485.
                </p>

                <div class="contact-map-note">
                    <span class="map-pin-small">●</span>
                    <span>Pengujian Kendaraan Bermotor</span>
                </div>

                <a href="https://www.google.com/maps/search/?api=1&query=Uji+KIR+PKB+JOMBANG"
                   target="_blank" rel="noopener noreferrer"
                   class="contact-button">
                    Petunjuk Arah <span>↗</span>
                </a>
            </div>

            <div class="contact-map-frame">
                <iframe
                    title="Peta lokasi Uji KIR PKB Jombang"
                    src="https://maps.google.com/maps?q=Uji%20KIR%20PKB%20JOMBANG&t=&z=15&ie=UTF8&iwloc=&output=embed"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    allowfullscreen>
                </iframe>
            </div>
        </div>
    </div>
</section>