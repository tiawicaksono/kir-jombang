@extends('layouts.app')

@section('title', 'Kebijakan Privasi - Uji Kir Jombang')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/privacy-policy.css') }}">
@endpush

@section('content')
<section class="page-hero">
    <div class="container">
        <span class="page-eyebrow">INFORMASI LAYANAN</span>
        <h1>Kebijakan Privasi</h1>
        <p>Uji Kir Jombang</p>
    </div>
</section>

<section class="privacy-section">
    <div class="container">
        <article class="privacy-card">
            <div class="privacy-meta">
                <span>Uji Kir Jombang</span>
                <span>Terakhir diperbarui: 20 November 2026</span>
            </div>
    
            <div class="privacy-content">
                <p>
                    Kebijakan Privasi ini menjelaskan bagaimana aplikasi
                    <strong>Uji Kir Jombang</strong>, yang dikelola oleh
                    <strong>Dinas Perhubungan Kabupaten Jombang</strong>,
                    menggunakan dan melindungi informasi pengguna ketika
                    mengakses layanan pengecekan data kendaraan bermotor.
                </p>

                <p>
                    Kami berkomitmen untuk menjaga privasi dan keamanan
                    informasi pengguna dalam menggunakan aplikasi ini.
                </p>

                <h2>1. Informasi yang Digunakan</h2>
                <p>
                    Aplikasi <strong>Uji Kir Jombang</strong> merupakan
                    aplikasi layanan informasi publik yang dapat digunakan
                    tanpa login atau pembuatan akun.
                </p>
                <p>Untuk menggunakan layanan pencarian, pengguna dapat memasukkan:</p>
                <ul>
                    <li>Nomor uji kendaraan bermotor.</li>
                    <li>Lima digit terakhir nomor rangka kendaraan.</li>
                </ul>
                <p>
                    Informasi tersebut digunakan untuk mencari dan menampilkan
                    data kendaraan serta riwayat pengujian yang tersedia pada
                    sistem Dinas Perhubungan Kabupaten Jombang.
                </p>

                <h2>2. Tujuan Penggunaan Informasi</h2>
                <p>Informasi yang dimasukkan pengguna digunakan untuk:</p>
                <ul>
                    <li>Melakukan pencarian data kendaraan bermotor.</li>
                    <li>Menampilkan informasi hasil dan riwayat pengujian kendaraan.</li>
                    <li>Memberikan kemudahan akses informasi pelayanan pengujian kendaraan bermotor.</li>
                </ul>
                <p>
                    Informasi tersebut tidak digunakan untuk membuat akun pengguna
                    maupun untuk keperluan pemasaran.
                </p>

                <h2>3. Pengumpulan dan Penyimpanan Data</h2>
                <p>
                    Aplikasi tidak menyediakan fitur registrasi, login, maupun
                    pembuatan akun pengguna. Aplikasi tidak meminta pengguna
                    memberikan nama, alamat rumah, atau nomor telepon untuk
                    menggunakan fitur pencarian.
                </p>
                <p>
                    Informasi pencarian digunakan untuk memproses permintaan layanan.
                    Sistem atau infrastruktur server dapat mencatat informasi teknis
                    tertentu, seperti alamat IP, waktu akses, dan log permintaan,
                    untuk kebutuhan operasional, keamanan, pemeliharaan, serta
                    penanganan gangguan layanan.
                </p>

                <h2>4. Pembagian Informasi kepada Pihak Ketiga</h2>
                <p>
                    Kami tidak menjual atau memperdagangkan informasi pengguna.
                    Informasi dapat diproses oleh penyedia infrastruktur atau
                    layanan teknologi yang mendukung operasional aplikasi,
                    sepanjang diperlukan untuk penyelenggaraan layanan dan
                    dengan memperhatikan keamanan informasi.
                </p>
                <p>
                    Informasi juga dapat diberikan apabila diwajibkan berdasarkan
                    ketentuan peraturan perundang-undangan yang berlaku.
                </p>

                <h2>5. Keamanan Informasi</h2>
                <p>
                    Dinas Perhubungan Kabupaten Jombang berupaya menerapkan
                    langkah-langkah teknis dan administratif yang wajar untuk
                    melindungi informasi dari akses, perubahan, pengungkapan,
                    atau penggunaan yang tidak sah.
                </p>
                <p>
                    Meskipun demikian, tidak ada sistem elektronik yang dapat
                    menjamin keamanan secara mutlak.
                </p>

                <h2>6. Tautan dan Layanan Eksternal</h2>
                <p>
                    Apabila aplikasi menyediakan tautan menuju situs atau layanan
                    eksternal, kebijakan privasi masing-masing penyedia layanan
                    tersebut berlaku secara terpisah. Kami tidak bertanggung jawab
                    atas praktik privasi dan keamanan layanan eksternal yang berada
                    di luar pengelolaan kami.
                </p>

                <h2>7. Privasi Anak</h2>
                <p>
                    Aplikasi ini merupakan layanan informasi publik mengenai
                    pengujian kendaraan bermotor dan tidak secara khusus ditujukan
                    kepada anak-anak. Aplikasi tidak secara sengaja meminta
                    anak-anak memberikan informasi pribadi untuk membuat akun
                    atau menggunakan layanan pencarian.
                </p>

                <h2>8. Perubahan Kebijakan Privasi</h2>
                <p>
                    Kebijakan Privasi ini dapat diperbarui sewaktu-waktu untuk
                    menyesuaikan perubahan layanan, teknologi, maupun ketentuan
                    peraturan perundang-undangan. Setiap perubahan akan ditampilkan
                    melalui halaman ini dengan tanggal pembaruan terbaru.
                </p>

                <h2>9. Kontak</h2>
                <p>
                    Apabila pengguna memiliki pertanyaan, masukan, atau keluhan
                    terkait Kebijakan Privasi ini, silakan menghubungi:
                </p>

                <div class="privacy-contact">
                    <strong>Dinas Perhubungan Kabupaten Jombang</strong>
                    <span>Aplikasi: Uji Kir Jombang</span>
                    <span>Email:
                        <a href="mailto:wicaksono.tia@gmail.com">
                            wicaksono.tia@gmail.com
                        </a>
                    </span>
                </div>
            </div>

        </article>
    </div>
</section>
@endsection