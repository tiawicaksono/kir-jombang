 @extends('layouts.app')

@section('title', 'Persyaratan Pengujian')
@section('meta_description', 'Informasi persiapan dokumen dan tahapan Pengujian Kendaraan Bermotor UPT Dishub Kabupaten Jombang.')

@section('content')
<section class="req-hero">
    <div class="container req-hero-grid">
        <div>
            <span class="req-eyebrow">PUSAT INFORMASI LAYANAN</span>
            <h1>Persyaratan <span>Pengujian Kendaraan.</span></h1>
            <p>Persiapkan dokumen dan kendaraan Anda sebelum datang ke tempat pelayanan agar proses pengujian lebih tertib dan lancar.</p>
            <div class="req-hero-actions">
                <a href="#daftar-persyaratan" class="req-btn req-btn-white">Lihat Persyaratan <span>↓</span></a>
                <a href="{{ route('vehicles.form') }}" class="req-btn req-btn-outline">Cek Data Kendaraan ↗</a>
            </div>
        </div>

        <div class="req-hero-card">
            <div class="req-hero-icon">▤</div>
            <span class="req-card-label">PERSIAPAN PENGUJIAN</span>
            <h2>Dokumen lengkap.<br>Pelayanan lebih siap.</h2>
            <p>Periksa kembali dokumen dan konfirmasikan ketentuan terbaru sebelum berangkat.</p>
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
            <span class="req-eyebrow-dark">SEBELUM DATANG KE LOKASI</span>
            <h2>Siapkan kebutuhan pengujian Anda.</h2>
            <p>Gunakan daftar berikut sebagai panduan awal. Dokumen yang wajib diserahkan dapat berbeda menurut jenis layanan dan ketentuan yang berlaku di UPT Pengujian Kendaraan Bermotor Kabupaten Jombang.</p>
        </div>

        <div class="req-notice">
            <span class="req-notice-icon">i</span>
            <div>
                <strong>Perhatian</strong>
                <p>Daftar ini merupakan panduan persiapan umum, bukan daftar persyaratan resmi yang telah diverifikasi oleh UPT. Konfirmasikan persyaratan, format dokumen, biaya, dan jadwal kepada petugas sebelum mengajukan layanan.</p>
            </div>
        </div>

        <div class="req-grid">
            <article class="req-panel">
                <div class="req-panel-head">
                    <span class="req-panel-number">01</span>
                    <div>
                        <span class="req-panel-kicker">LAYANAN KENDARAAN</span>
                        <h3>Uji berkala pertama</h3>
                    </div>
                </div>
                <p class="req-panel-desc">Untuk kendaraan yang akan menjalani pengujian berkala pertama kali.</p>
                <ul class="req-checklist">
                    <li><span>✓</span><div><strong>Identitas pemilik atau penanggung jawab</strong><small>Siapkan identitas yang sesuai dengan dokumen kendaraan.</small></div></li>
                    <li><span>✓</span><div><strong>STNK</strong><small>Siapkan dokumen registrasi kendaraan untuk pemeriksaan administrasi.</small></div></li>
                    <li><span>✓</span><div><strong>Bukti kepemilikan kendaraan</strong><small>Siapkan BPKB atau dokumen lain yang relevan sesuai ketentuan.</small></div></li>
                    <li><span>✓</span><div><strong>Dokumen teknis kendaraan</strong><small>Siapkan dokumen tipe, sertifikasi, atau dokumen teknis lain apabila dipersyaratkan.</small></div></li>
                    <li><span>✓</span><div><strong>Kendaraan yang akan diuji</strong><small>Pastikan kendaraan tersedia untuk menjalani pemeriksaan teknis.</small></div></li>
                </ul>
            </article>

            <article class="req-panel">
                <div class="req-panel-head">
                    <span class="req-panel-number">02</span>
                    <div>
                        <span class="req-panel-kicker">LAYANAN BERKALA</span>
                        <h3>Uji berkala berikutnya</h3>
                    </div>
                </div>
                <p class="req-panel-desc">Untuk kendaraan yang telah memiliki riwayat pengujian berkala.</p>
                <ul class="req-checklist">
                    <li><span>✓</span><div><strong>Identitas pemilik atau penanggung jawab</strong><small>Siapkan identitas untuk keperluan verifikasi administrasi.</small></div></li>
                    <li><span>✓</span><div><strong>STNK kendaraan</strong><small>Siapkan dokumen registrasi kendaraan yang masih relevan.</small></div></li>
                    <li><span>✓</span><div><strong>Bukti atau data pengujian sebelumnya</strong><small>Siapkan kartu uji, bukti lulus uji, atau data pengujian yang tersedia sesuai ketentuan.</small></div></li>
                    <li><span>✓</span><div><strong>Dokumen pendukung perubahan</strong><small>Jika terdapat perubahan data atau kondisi kendaraan, siapkan dokumen pendukung yang relevan.</small></div></li>
                    <li><span>✓</span><div><strong>Kendaraan yang akan diuji</strong><small>Pastikan kendaraan siap untuk menjalani pemeriksaan berkala.</small></div></li>
                </ul>
            </article>
        </div>

        <article class="req-panel req-extra">
            <div class="req-panel-head">
                <span class="req-panel-number">03</span>
                <div>
                    <span class="req-panel-kicker">KONDISI KHUSUS</span>
                    <h3>Dokumen tambahan bila diperlukan</h3>
                </div>
            </div>
            <div class="req-extra-grid">
                <div class="req-extra-item"><span>↗</span><div><strong>Pengurusan melalui perwakilan</strong><p>Konfirmasikan kebutuhan surat kuasa dan identitas pihak yang mewakili.</p></div></div>
                <div class="req-extra-item"><span>↗</span><div><strong>Perubahan identitas atau spesifikasi</strong><p>Konfirmasikan dokumen pendukung untuk perubahan data kendaraan.</p></div></div>
                <div class="req-extra-item"><span>↗</span><div><strong>Dokumen hilang atau tidak tersedia</strong><p>Tanyakan prosedur penggantian atau alternatif dokumen yang dapat diterima.</p></div></div>
                <div class="req-extra-item"><span>↗</span><div><strong>Mutasi atau perubahan status kendaraan</strong><p>Konfirmasikan persyaratan khusus berdasarkan kondisi administrasi kendaraan.</p></div></div>
            </div>
        </article>
    </div>
</section>

<section class="req-steps-section">
    <div class="container">
        <div class="req-heading req-heading-light">
            <span class="req-eyebrow">ALUR PERSIAPAN</span>
            <h2>Empat langkah sebelum pengujian.</h2>
        </div>

        <div class="req-steps">
            <article><span>01</span><h3>Tentukan layanan</h3><p>Pastikan jenis pengujian yang dibutuhkan kendaraan Anda.</p></article>
            <article><span>02</span><h3>Siapkan dokumen</h3><p>Periksa dokumen utama dan konfirmasikan dokumen tambahan.</p></article>
            <article><span>03</span><h3>Konfirmasi jadwal</h3><p>Tanyakan jadwal, biaya resmi, dan prosedur pendaftaran kepada petugas.</p></article>
            <article><span>04</span><h3>Datang untuk pengujian</h3><p>Bawa kendaraan dan dokumen sesuai arahan petugas pelayanan.</p></article>
        </div>
    </div>
</section>

<section class="req-bottom">
    <div class="container req-bottom-inner">
        <div>
            <span class="req-eyebrow-dark">LANGKAH SELANJUTNYA</span>
            <h2>Ingin memeriksa data kendaraan?</h2>
            <p>Gunakan layanan pengecekan data kendaraan yang tersedia di website.</p>
        </div>
        <a href="{{ route('vehicles.form') }}" class="req-btn req-btn-primary">Cek Data Kendaraan <span>→</span></a>
    </div>
</section>

<style>
.req-hero{position:relative;overflow:hidden;background:linear-gradient(120deg,#062c65 0%,#084da5 60%,#1475d4 100%);color:#fff;padding:76px 0 82px}
.req-hero:before{content:"";position:absolute;width:480px;height:480px;border:1px solid #ffffff20;border-radius:50%;right:-130px;top:-230px;box-shadow:0 0 0 55px #ffffff08,0 0 0 110px #ffffff05}
.req-hero-grid{position:relative;display:grid;grid-template-columns:1.2fr .8fr;align-items:center;gap:64px}
.req-eyebrow,.req-eyebrow-dark{display:inline-block;font-size:.75rem;font-weight:800;letter-spacing:.14em;margin-bottom:17px}
.req-eyebrow{color:#bcdcff}.req-eyebrow-dark{color:#1764b4}
.req-hero h1{font-family:Manrope,sans-serif;font-size:clamp(2.4rem,5vw,4.25rem);font-weight:800;line-height:1.12;letter-spacing:-.045em;margin:0 0 22px}
.req-hero h1 span{color:#9dceff}
.req-hero-grid>div:first-child>p{max-width:570px;color:#d8e8ff;font-size:1.04rem;line-height:1.9;margin:0}
.req-hero-actions{display:flex;flex-wrap:wrap;gap:12px;margin-top:30px}
.req-btn{display:inline-flex;align-items:center;justify-content:center;gap:12px;border-radius:9px;padding:14px 19px;font-size:.9rem;font-weight:700;text-decoration:none;transition:transform .2s,background .2s}
.req-btn:hover{transform:translateY(-2px)}
.req-btn-white{background:#fff;color:#0a438d}.req-btn-outline{border:1px solid #ffffff70;color:#fff}
.req-hero-card{background:#ffffff12;border:1px solid #ffffff30;border-radius:20px;padding:32px;backdrop-filter:blur(8px);box-shadow:0 22px 70px #001d4c30}
.req-hero-icon{display:grid;place-items:center;width:56px;height:56px;border-radius:15px;background:#ffffff18;color:#ffda78;font-size:1.8rem;margin-bottom:28px}
.req-card-label{font-size:.68rem;letter-spacing:.15em;font-weight:800;color:#b8d9ff}
.req-hero-card h2{font-family:Manrope,sans-serif;font-size:1.8rem;line-height:1.3;margin:12px 0}
.req-hero-card p{color:#d9e9ff;line-height:1.8;font-size:.9rem}
.req-card-line{height:1px;background:#ffffff30;margin:24px 0}
.req-card-footer{display:flex;justify-content:space-between;gap:12px;color:#d8e8ff;font-size:.76rem}
.req-card-footer span:first-child{color:#ffda78;font-weight:800}
.req-content{padding-top:76px;padding-bottom:80px}
.req-heading{max-width:760px;margin-bottom:30px}
.req-heading h2,.req-bottom h2{font-family:Manrope,sans-serif;font-size:clamp(1.8rem,3.5vw,2.6rem);line-height:1.25;letter-spacing:-.035em;margin:0 0 15px;color:#102f56}
.req-heading>p,.req-bottom p{line-height:1.85;color:#64748b;margin:0}
.req-notice{display:flex;align-items:flex-start;gap:16px;padding:20px;border:1px solid #c9e1ff;background:#f0f7ff;border-radius:13px;margin:30px 0}
.req-notice-icon{display:grid;place-items:center;flex:0 0 30px;height:30px;border-radius:50%;background:#d5e9ff;color:#0755a8;font-weight:800}
.req-notice strong{display:block;color:#124a85;margin:2px 0 5px}
.req-notice p{font-size:.88rem;line-height:1.75;color:#46617e;margin:0}
.req-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:22px}
.req-panel{border:1px solid #e0e9f4;background:#fff;border-radius:16px;padding:27px;box-shadow:0 10px 35px #143d6b08}
.req-panel-head{display:flex;align-items:center;gap:15px;margin-bottom:15px}
.req-panel-number{display:grid;place-items:center;flex:0 0 48px;height:48px;background:#eaf3ff;color:#0b5ab0;border-radius:12px;font-weight:800}
.req-panel-kicker{display:block;color:#5780ab;font-size:.65rem;letter-spacing:.12em;font-weight:800;margin-bottom:5px}
.req-panel h3{font-family:Manrope,sans-serif;color:#12345a;font-size:1.25rem;margin:0}
.req-panel-desc{font-size:.88rem;line-height:1.75;color:#64748b;margin:0 0 22px}
.req-checklist{list-style:none;padding:0;margin:0}
.req-checklist li{display:flex;gap:12px;padding:15px 0;border-top:1px solid #edf2f8}
.req-checklist li>span{display:grid;place-items:center;flex:0 0 24px;height:24px;border-radius:50%;background:#e7f7ef;color:#168252;font-size:.78rem;font-weight:800}
.req-checklist strong{display:block;font-size:.88rem;color:#203d5e;margin:2px 0 5px}
.req-checklist small{display:block;font-size:.8rem;color:#718096;line-height:1.7}
.req-extra{margin-top:22px}
.req-extra-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:22px;margin-top:22px}
.req-extra-item{display:flex;gap:12px;padding:16px;background:#f7faff;border-radius:11px}
.req-extra-item>span{color:#1767bd;font-size:1.3rem}
.req-extra-item strong{color:#1a3d63;font-size:.9rem}
.req-extra-item p{font-size:.82rem;color:#6b7f95;line-height:1.7;margin:6px 0 0}
.req-steps-section{background:#072e63;padding:72px 0;color:#fff}
.req-heading-light h2{color:#fff}.req-eyebrow{color:#b9d9ff}
.req-steps{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:20px}
.req-steps article{border-top:1px solid #ffffff35;padding:22px 15px 0 0}
.req-steps article>span{display:block;font-family:Manrope,sans-serif;font-size:1.8rem;font-weight:800;color:#7fbdff;margin-bottom:18px}
.req-steps h3{font-family:Manrope,sans-serif;font-size:1.05rem;margin:0 0 10px}
.req-steps p{color:#c7d9ee;font-size:.86rem;line-height:1.8;margin:0}
.req-bottom{padding:55px 0;background:#f4f8fd}
.req-bottom-inner{display:flex;align-items:center;justify-content:space-between;gap:30px}
.req-bottom h2{font-size:clamp(1.6rem,3vw,2.25rem)}
.req-btn-primary{background:#0b5fc3;color:#fff;white-space:nowrap}
@media(max-width:800px){.req-hero{padding:55px 0}.req-hero-grid{grid-template-columns:1fr;gap:35px}.req-hero-card{max-width:520px}.req-grid{grid-template-columns:1fr}.req-steps{grid-template-columns:repeat(2,minmax(0,1fr))}.req-bottom-inner{align-items:flex-start;flex-direction:column}}
@media(max-width:520px){.req-hero h1{font-size:2.4rem}.req-hero-card,.req-panel{padding:22px}.req-hero-actions{align-items:stretch;flex-direction:column}.req-btn{width:100%;box-sizing:border-box}.req-extra-grid{grid-template-columns:1fr}.req-steps{grid-template-columns:1fr}.req-card-footer{flex-direction:column}}
</style>
@endsection