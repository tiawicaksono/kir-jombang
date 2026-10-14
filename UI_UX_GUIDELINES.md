# UI/UX Guidelines — Antarmuka dan Design System

> Tanggal analisis: 14 Oktober 2026. Metode: pemeriksaan statis repository; bukan hasil pengujian terhadap database/server produksi. **Terbukti** berarti ada implementasi di kode; **inferensi** berarti disimpulkan dari pemakaian; **rekomendasi** berarti perubahan yang belum diimplementasikan. Contoh data bersifat sintetis. Nilai kredensial sengaja tidak disalin.

Dokumen terkait: [PRD](PRD.md) · [Arsitektur](TECH_SPEC.md) · [Database](DATABASE_SCHEMA.md) · [Kontrak endpoint](API_CONTRACT.md) · [UI/UX](UI_UX_GUIDELINES.md).

**Daftar isi**

- [1. Karakter antarmuka](#1-karakter-antarmuka)
- [2. Struktur layout dan navigasi](#2-struktur-layout-dan-navigasi)
- [3. Palette dan token visual existing](#3-palette-dan-token-visual-existing)
- [4. Komponen utama](#4-komponen-utama)
- [5. Alur interaksi utama](#5-alur-interaksi-utama)
- [6. Responsivitas dan viewport](#6-responsivitas-dan-viewport)
- [7. Mobile web dan manifest](#7-mobile-web-dan-manifest)
- [8. Aksesibilitas dan konsistensi yang direkomendasikan](#8-aksesibilitas-dan-konsistensi-yang-direkomendasikan)
- [9. Pedoman implementasi perubahan UI](#9-pedoman-implementasi-perubahan-ui)
- [10. Inventaris view dan aset fitur](#10-inventaris-view-dan-aset-fitur)

## 1. Karakter antarmuka

Web adalah aplikasi server-rendered Yii dengan tema **AdminLTE 2.3.0**, **Bootstrap 3.3.5**, jQuery dan **jQuery EasyUI**. Halaman dibentuk oleh PHP views, layout, komponen menu, JavaScript modul dan respons AJAX. Tidak ditemukan SPA React/Vue, Redux, Vuex, atau state store terpusat. State tersebar pada session server, DOM/hidden input, pilihan datagrid, variabel JavaScript dan database.

Panduan ini mendokumentasikan desain yang ada dan memisahkan perbaikan yang direkomendasikan. Tampilan belum diuji melalui browser hidup karena DB operasional tidak tersedia. Istilah `main_android` pada layout adalah nama layout web, bukan bukti UI aplikasi Android native.

## 2. Struktur layout dan navigasi

| Sumber | Fungsi dan karakter |
|---|---|
| `themes/adminlte/views/layouts/main.php` | `skin-blue layout-top-nav`, menu atas, identitas sesi, logout, content, footer, overlay/popup |
| `themes/adminlte/views/layouts/main_top.php` | Variasi navigasi atas, `css/main_top.css`; digunakan misalnya pada posisi CIS |
| `themes/adminlte/views/layouts/main_android.php` | `skin-blue sidebar-mini`, dipilih `site/home` untuk dashboard |
| `main_ori.php` | Layout alternatif/legacy; jangan diasumsikan aktif di semua halaman |
| `protected/views/layouts/column1.php` dan layout fallback | Wrapper konten dasar; default base controller `//layouts/column1` |
| `protected/views/site/main_login.php` | Login utama, layout terpisah dari dashboard |
| `protected/modules/cis/views/default/main_login.php` | Login alur CIS |
| `protected/components/MainMenu.php` dan `views/mainMenu.php` | Menu dari `TblMenu`; parent/submenu, sort dan ekspresi kondisi |
| `protected/modules/*/views/` | Halaman/partial bisnis per controller |
| `protected/modules/*/assets/` | JS/CSS khusus modul, dipublikasikan Yii asset manager |

Menu tidak di-hardcode seluruhnya: `tbl_menu` menentukan `menu_label`, `menu_link`, `parent_id`, `menu_status`, `menu_sort`, parameter dan kondisi. Urutan/menu efektif hanya dapat dipastikan dari isi database. Nama akun tampil dari sesi; menyembunyikan menu tidak cukup untuk mengamankan endpoint.

## 3. Palette dan token visual existing

Sumber utama `css/main.css`, `css/main_top.css`, `themes/adminlte/dist/css/AdminLTE.css`, skin-blue dan CSS Bootstrap. Warna berikut adalah observasi kode, bukan rancangan branding baru.

| Peran | Warna | Bukti/pemakaian |
|---|---|---|
| Primary/header box | `#3c8dbc` | `.box-header`, skin-blue |
| Primary gelap | `#367fa9` | Variasi skin-blue/brand |
| Permukaan konten | `#ffffff` | `.content`, `.content-wrapper`, tombol putih |
| Isi box | `#f0f5fa` | `.box-body` override global |
| Nilai input | `#fe4e52` | `.form-control`, font bold; bukan khusus error |
| Disabled/readonly | `#d7d7d7` | Background `.form-control[disabled/readonly]` |
| Penanda wajib | `#ff0000` | Border/text `.wajib-isi` |
| Success | `#00a65a` | Kelas status AdminLTE |
| Warning | `#f39c12` | Kelas status AdminLTE |
| Danger | `#dd4b39` | Kelas status AdminLTE |
| Info | `#00c0ef` | Kelas status AdminLTE |
| Overlay | `#000000` pada opacity 0.5 | `#overlay`, fixed, z-index 9999 |

Typography dasar AdminLTE menggunakan keluarga `Source Sans Pro`, Helvetica Neue, Helvetica, Arial, sans-serif; ketersediaan font aktual mengikuti aset/network. Bootstrap baseline 14px; aplikasi banyak memberi ukuran dan font inline, terutama halaman display/IKM/cetak. Tidak ada sistem token spacing/typography terpusat. Grid memakai padding sel **4px** dan header tebal/rata tengah dari `css/main.css`.

**Rekomendasi:** pisahkan warna nilai input normal dari error, pertahankan satu warna aksi utama, serta gunakan label teks/ikon bersama warna untuk status. Jangan mengklaim palette memenuhi rasio kontras tanpa pengukuran per pasangan foreground/background.

## 4. Komponen utama

### 4.1 Form

Pola existing: Bootstrap `.form-control`, `form-group`, kolom `col-*`; input uppercase `.text-besar`, `.wajib-isi`, numeric plugin, input mask, datepicker, dropdown master dan iCheck. Login memakai Yii `CActiveForm`; beberapa form bisnis mem-post nested `FORM[...]` ke controller, lainnya field flat.

Aturan pemeliharaan: pertahankan `name` dan ID yang dirujuk JS/server saat merapikan markup. Tanggal harus menyebut format `dd/mm/yyyy` bila endpoint mengharapkannya. Readonly dapat berbeda dari disabled dalam submit form; verifikasi serialisasi sebelum mengganti atribut. Validasi browser tidak menggantikan pemeriksaan server.

**Target UX:** label terhubung melalui `for/id`, error field terdekat dengan ringkasan, required secara semantik, fokus ke error pertama, tombol simpan nonaktif selama request, nilai tidak hilang ketika gagal, konfirmasi sukses setelah hasil server diterima. Semua ini target perbaikan, bukan jaminan existing.

### 4.2 Datagrid dan tabel

EasyUI adalah pola dominan untuk daftar operasional: query filter, pagination, pemilihan baris/checkbox, sorting, toolbar dan reload. Respons lazim `{total, rows}`; parameter `page` mulai 1, `rows` default berbeda per action, `sort` dan `order` diteruskan ke server. Beberapa sel adalah HTML ikon/status/tombol yang dibangun controller.

Halaman seperti kendaraan terdaftar, kedatangan, validasi, riwayat dan hasil uji memakai pola ini. Semua field/kolom nyata bergantung view dan respons grid, bukan satu komponen tabel universal. DataTables juga dibundel di tema, tetapi keberadaan file library tidak berarti semua tabel memakai DataTables.

**Target UX:** filter dan tanggal tetap terlihat saat refresh; tampilkan kosong/loading/error secara berbeda; pertahankan pilihan hanya jika ID masih valid; sediakan konfirmasi bulk action dengan jumlah objek; pada mobile gunakan horizontal scrolling terarah atau ringkasan detail. Escape data pengguna sebelum menjadikannya HTML.

### 4.3 Dialog, modal dan feedback

Kode menggunakan EasyUI dialog/messager serta pola Bootstrap dan popup custom. `#overlay` dan `#popup` menjadi indikator proses global; popup memuat `loading-smooth.gif`. Halaman dapat terblokir bila callback gagal tidak membersihkan overlay.

**Target UX:** satu dialog aktif, fokus awal/return focus, navigasi keyboard, tombol batal yang jelas, informasi objek yang akan diubah, serta callback error/timeout yang selalu menghentikan loading. Hindari pesan sukses hanya berdasarkan HTTP 200 karena endpoint dapat mengembalikan body kosong atau flag bisnis gagal.

### 4.4 Box/card dan dashboard

AdminLTE memakai `.box`, `.box-header`, `.box-body`, serta komponen ringkasan `.small-box`/panel statistik sesuai view. Warna header/isi dioverride CSS global. Dashboard menampilkan retribusi, jumlah kendaraan dan status lulus/tidak lulus dengan grafik. Nama kelas `card` modern tidak menjadi basis komponen existing; pertahankan kosakata AdminLTE 2 pada pemeliharaan.

### 4.5 Status, foto, barcode dan cetak

Status proses menggunakan ikon approve/reject/process, teks lulus/tidak lulus dan data flag. Foto kendaraan/scan dapat berasal dari base64. Tampilkan placeholder yang eksplisit untuk foto belum tersedia; jangan merender string `-` atau `A` sebagai gambar.

QR/barcode dibuat melalui aset JS yang dibundel dan alur cetak tertentu. PHPExcel/mPDF serta HTML print memiliki layout dokumen tersendiri. Dokumen cetak tidak boleh mengikuti layout responsive dashboard secara otomatis; verifikasi ukuran kertas, margin, logo, tanda tangan, nomor seri dan page break pada printer yang dipakai.

## 5. Alur interaksi utama

| Alur | Interaksi existing | Hal yang harus divalidasi |
|---|---|---|
| Login | Isi username/password → validasi → redirect `/pkb` → modul | Error login, session expiry, tujuan redirect berbasis role |
| Pendaftaran | Cari identitas → isi jenis/tanggal → simpan/scan → pesan hasil | Duplikasi, form tanggal, loading, field wajib, kegagalan upload |
| Validasi/kedatangan | Filter grid → pilih baris → proses satu/banyak → reload | Aksi pada objek yang tepat dan hasil server sesungguhnya |
| Prauji/mekanis | Pilih kendaraan → nilai/indikator/foto → simpan → status | Sumber data uji, rentang/satuan, audit perubahan |
| Verifikasi/cetak | Tinjau tahap → keputusan/banding → cetak | Preview sesuai record, hak akses, cetak ulang |
| Riwayat | Pilih kategori/no uji → grid → detail/foto/dokumen | Banyak hasil pencarian, data tidak ada, gambar gagal |
| Rekomendasi | Pilih kendaraan/jenis → detail/status → persetujuan/cetak | Urutan persetujuan dan identitas instansi |
| Survei IKM | Pilih ekspresi puas/cukup/kecewa → pertanyaan berikutnya | Layout kiosk, penyimpanan tiga jawaban, reset akhir |

## 6. Responsivitas dan viewport

Bootstrap 3 menyediakan breakpoint `xs <768px`, `sm ≥768px`, `md ≥992px`, `lg ≥1200px` menurut CSS yang dibundel. Navbar memiliki tombol collapse dan kelas seperti `hidden-xs`. Itu memberikan fondasi responsif, tetapi belum membuktikan semua halaman usable pada mobile.

Layout utama memasang viewport dengan `maximum-scale=1.0` dan `user-scalable=0`, sehingga zoom pengguna dibatasi. Grid/form operasional padat; IKM mempunyai dimensi/offset inline besar (gambar 459×320, teks lebar 880px, margin 300px pada respons controller). Kiosk harus dinilai terpisah dari ponsel.

| Viewport pengujian yang disarankan | Fokus |
|---|---|
| 360×800 dan 390×844 | Menu, form satu kolom, dialog tidak terpotong, scroll tabel, zoom |
| 768×1024 | Tablet portrait: toolbar, checkbox bulk, keyboard virtual |
| 1024×768 | Loket/penguji: form dan grid, tinggi layar terbatas |
| 1366×768 dan 1920×1080 | Desktop/display: kepadatan informasi dan pembacaan jarak jauh |
| Print A4/perangkat aktual | Margin, skala, tanda tangan, page break dan nomor dokumen |

Ukuran pengujian adalah rekomendasi, bukan daftar perangkat tersertifikasi. **Target perbaikan:** izinkan zoom, gunakan viewport standar, stack form di layar sempit, tetapkan lebar dialog relatif, sediakan horizontal scroll pada tabel lebar, dan pisahkan layout kiosk fixed dari layanan mobile.

## 7. Mobile web dan manifest

`js/manifest.json` masih menggunakan `short_name: CIS PKB`, `name: DISHUB SAMPIT`, dan `start_url: /pkbsurabaya`. Ini tidak konsisten dengan konfigurasi PKB SAMPANG dan dapat membuka path yang salah pada instalasi baru. Manifest saja tidak membuktikan PWA/offline; tidak ditemukan service worker aplikasi yang menyediakan caching offline.

API mobile tidak menentukan desain layar native. Dokumentasi mobile yang dapat dipastikan adalah data/aksi di [API_CONTRACT.md](API_CONTRACT.md); navigasi native, state management, push notification dan tema mobile belum tersedia dalam source.

## 8. Aksesibilitas dan konsistensi yang direkomendasikan

- Semua field, ikon tindakan dan status mempunyai nama yang dapat dibaca teknologi bantu; gunakan tombol untuk aksi, link untuk navigasi.
- Pastikan urutan tab, fokus dialog, tombol Escape sesuai kebutuhan, dan akses semua alur tanpa mouse.
- Jangan mengandalkan merah/hijau saja untuk status; sertakan teks LULUS/TIDAK LULUS/PROSES dan keterangan.
- Allow zoom dan ukur kontras teks, input merah, disabled serta box header sebelum menyatakan compliance.
- Gunakan data contoh tersamarkan pada screenshot/demo; detail pemilik/foto hanya ditampilkan sesuai izin.
- Selaraskan branding Sampang pada login, dashboard, manifest, rekomendasi, footer dan dokumen cetak.
- Audit pemuatan jQuery berulang pada layout; satu instance dan urutan plugin yang konsisten menghindari plugin terlepas.

## 9. Pedoman implementasi perubahan UI

Edit view dan asset sumber di modul/tema, bukan salinan hasil publish pada `assets/`. Lacak endpoint yang dipanggil sebelum mengganti nama input, selector, event atau struktur response. Pisahkan CSS khusus modul agar override `.form-control`/`.box-body` tidak merusak halaman lain. Uji satu alur lengkap dengan berhasil, kosong, invalid, gagal jaringan dan sesi habis; verifikasi cetak tersendiri. Repository belum mempunyai component library atau visual regression baseline yang dapat dianggap otoritatif.

## 10. Inventaris view dan aset fitur

Lampiran berikut memetakan semua view aplikasi/tema serta aset modul yang relevan. File dalam folder vendor/plugins tidak dianggap otomatis sebagai fitur aktif; layout dan view pemanggil adalah bukti penggunaannya.

### `protected/modules/capture/views/default`

[index.php](protected/modules/capture/views/default/index.php).

### `protected/modules/cis/views/default`

[form_change_password.php](protected/modules/cis/views/default/form_change_password.php), [main_login.php](protected/modules/cis/views/default/main_login.php).

### `protected/modules/cis/views/pengujian`

[form_change_password.php](protected/modules/cis/views/pengujian/form_change_password.php), [index_emisi.php](protected/modules/cis/views/pengujian/index_emisi.php), [index_lampu.php](protected/modules/cis/views/pengujian/index_lampu.php), [index_pitlift.php](protected/modules/cis/views/pengujian/index_pitlift.php), [index_prauji.php](protected/modules/cis/views/pengujian/index_prauji.php), [index_prauji_gandengan.php](protected/modules/cis/views/pengujian/index_prauji_gandengan.php), [index_rem.php](protected/modules/cis/views/pengujian/index_rem.php), [index_rem_gandengan.php](protected/modules/cis/views/pengujian/index_rem_gandengan.php), [pengujian.php](protected/modules/cis/views/pengujian/pengujian.php).

### `protected/modules/cis/views/posisi`

[index.php](protected/modules/cis/views/posisi/index.php).

### `protected/modules/display/views/cisdua`

[all.php](protected/modules/display/views/cisdua/all.php), [cis_dua.php](protected/modules/display/views/cisdua/cis_dua.php), [emisi.php](protected/modules/display/views/cisdua/emisi.php), [lampu.php](protected/modules/display/views/cisdua/lampu.php), [pitlift.php](protected/modules/display/views/cisdua/pitlift.php), [prauji.php](protected/modules/display/views/cisdua/prauji.php), [rem.php](protected/modules/display/views/cisdua/rem.php).

### `protected/modules/display/views/cissatu`

[all.php](protected/modules/display/views/cissatu/all.php), [cis_satu.php](protected/modules/display/views/cissatu/cis_satu.php), [emisi.php](protected/modules/display/views/cissatu/emisi.php), [lampu.php](protected/modules/display/views/cissatu/lampu.php), [pitlift.php](protected/modules/display/views/cissatu/pitlift.php), [prauji.php](protected/modules/display/views/cissatu/prauji.php), [prauji_1.php](protected/modules/display/views/cissatu/prauji_1.php), [rem.php](protected/modules/display/views/cissatu/rem.php).

### `protected/modules/display/views/default`

[cis.php](protected/modules/display/views/default/cis.php).

### `protected/modules/display/views/hasil`

[hasil.php](protected/modules/display/views/hasil/hasil.php).

### `protected/modules/display/views/iyasaka`

[all.php](protected/modules/display/views/iyasaka/all.php), [cis_iyasaka.php](protected/modules/display/views/iyasaka/cis_iyasaka.php), [emisi.php](protected/modules/display/views/iyasaka/emisi.php), [lampu.php](protected/modules/display/views/iyasaka/lampu.php), [pitlift.php](protected/modules/display/views/iyasaka/pitlift.php), [rem.php](protected/modules/display/views/iyasaka/rem.php).

### `protected/modules/display/views/muller`

[all.php](protected/modules/display/views/muller/all.php), [cis_muller.php](protected/modules/display/views/muller/cis_muller.php), [emisi.php](protected/modules/display/views/muller/emisi.php), [lampu.php](protected/modules/display/views/muller/lampu.php), [pitlift.php](protected/modules/display/views/muller/pitlift.php), [prauji.php](protected/modules/display/views/muller/prauji.php), [rem.php](protected/modules/display/views/muller/rem.php).

### `protected/modules/ikm/views/default`

[index.php](protected/modules/ikm/views/default/index.php).

### `protected/modules/loket/views/Default`

[cek_scan.php](protected/modules/loket/views/Default/cek_scan.php), [form_tl.php](protected/modules/loket/views/Default/form_tl.php), [mancing.php](protected/modules/loket/views/Default/mancing.php), [munculin_kembali.php](protected/modules/loket/views/Default/munculin_kembali.php), [riwayat_double.php](protected/modules/loket/views/Default/riwayat_double.php).

### `protected/modules/loket/views/cekTerdaftar`

[cekterdaftar.php](protected/modules/loket/views/cekTerdaftar/cekterdaftar.php).

### `protected/modules/loket/views/editbuku`

[index.php](protected/modules/loket/views/editbuku/index.php), [index_2.php](protected/modules/loket/views/editbuku/index_2.php), [index_baru.php](protected/modules/loket/views/editbuku/index_baru.php), [index_edit.php](protected/modules/loket/views/editbuku/index_edit.php).

### `protected/modules/loket/views/kedatangan`

[kedatangan.php](protected/modules/loket/views/kedatangan/kedatangan.php).

### `protected/modules/loket/views/rollingalat`

[cis1.php](protected/modules/loket/views/rollingalat/cis1.php), [cis2.php](protected/modules/loket/views/rollingalat/cis2.php), [index.php](protected/modules/loket/views/rollingalat/index.php).

### `protected/modules/loket/views/terdaftar`

[terdaftar.php](protected/modules/loket/views/terdaftar/terdaftar.php).

### `protected/modules/loket/views/ujiulang`

[uji_ulang.php](protected/modules/loket/views/ujiulang/uji_ulang.php).

### `protected/modules/loket/views/upload`

[upload.php](protected/modules/loket/views/upload/upload.php).

### `protected/modules/master/views/default`

[index.php](protected/modules/master/views/default/index.php).

### `protected/modules/master/views/riwayat`

[index.php](protected/modules/master/views/riwayat/index.php).

### `protected/modules/pengujian/views/blokir`

[blokir.php](protected/modules/pengujian/views/blokir/blokir.php).

### `protected/modules/pengujian/views/default`

[status_proses.php](protected/modules/pengujian/views/default/status_proses.php).

### `protected/modules/pengujian/views/printhasil`

[cetak_l.php](protected/modules/pengujian/views/printhasil/cetak_l.php), [cetak_l_sementara.php](protected/modules/pengujian/views/printhasil/cetak_l_sementara.php), [cetak_tl.php](protected/modules/pengujian/views/printhasil/cetak_tl.php), [cetak_tl_dimensi.php](protected/modules/pengujian/views/printhasil/cetak_tl_dimensi.php), [index.php](protected/modules/pengujian/views/printhasil/index.php).

### `protected/modules/pengujian/views/riwayat`

[cetak_kartu_kuning.php](protected/modules/pengujian/views/riwayat/cetak_kartu_kuning.php), [detail_kendaraan.php](protected/modules/pengujian/views/riwayat/detail_kendaraan.php), [riwayat.php](protected/modules/pengujian/views/riwayat/riwayat.php), [riwayat_ori.php](protected/modules/pengujian/views/riwayat/riwayat_ori.php).

### `protected/modules/pengujian/views/verifikasi`

[cetak_l.php](protected/modules/pengujian/views/verifikasi/cetak_l.php), [cetak_l_sementara.php](protected/modules/pengujian/views/verifikasi/cetak_l_sementara.php), [verifikasi.php](protected/modules/pengujian/views/verifikasi/verifikasi.php).

### `protected/modules/pengukuran/views/pengukuran`

[index.php](protected/modules/pengukuran/views/pengukuran/index.php).

### `protected/modules/pengukuran/views/prauji`

[index_prauji.php](protected/modules/pengukuran/views/prauji/index_prauji.php), [replace_file.php](protected/modules/pengukuran/views/prauji/replace_file.php).

### `protected/modules/recommendation/views/bukuuji`

[index.php](protected/modules/recommendation/views/bukuuji/index.php), [proses.php](protected/modules/recommendation/views/bukuuji/proses.php).

### `protected/modules/recommendation/views/default`

[proses.php](protected/modules/recommendation/views/default/proses.php), [proses_mk.php](protected/modules/recommendation/views/default/proses_mk.php), [proses_nk.php](protected/modules/recommendation/views/default/proses_nk.php), [proses_us.php](protected/modules/recommendation/views/default/proses_us.php), [status_rekomendasi.php](protected/modules/recommendation/views/default/status_rekomendasi.php).

### `protected/modules/recommendation/views/rekom`

[print_mutke_numke.php](protected/modules/recommendation/views/rekom/print_mutke_numke.php), [print_rekom.php](protected/modules/recommendation/views/rekom/print_rekom.php), [print_rekom_ori.php](protected/modules/recommendation/views/rekom/print_rekom_ori.php), [status_rekomendasi.php](protected/modules/recommendation/views/rekom/status_rekomendasi.php).

### `protected/modules/recommendation/views/report`

[proses.php](protected/modules/recommendation/views/report/proses.php), [proses_mk.php](protected/modules/recommendation/views/report/proses_mk.php), [proses_nk.php](protected/modules/recommendation/views/report/proses_nk.php), [proses_us.php](protected/modules/recommendation/views/report/proses_us.php), [rekomendasi.php](protected/modules/recommendation/views/report/rekomendasi.php), [rekomendasi1.php](protected/modules/recommendation/views/report/rekomendasi1.php).

### `protected/modules/report/views/bukuuji`

[index.php](protected/modules/report/views/bukuuji/index.php).

### `protected/modules/report/views/default`

[index.php](protected/modules/report/views/default/index.php).

### `protected/modules/report/views/kementrian`

[berkala.php](protected/modules/report/views/kementrian/berkala.php), [berkala_12000.php](protected/modules/report/views/kementrian/berkala_12000.php), [berkala_3500.php](protected/modules/report/views/kementrian/berkala_3500.php), [berkala_3501_6000.php](protected/modules/report/views/kementrian/berkala_3501_6000.php), [berkala_6001_9000.php](protected/modules/report/views/kementrian/berkala_6001_9000.php), [berkala_9001_12000.php](protected/modules/report/views/kementrian/berkala_9001_12000.php), [berkala_ltl.php](protected/modules/report/views/kementrian/berkala_ltl.php), [kbwu.php](protected/modules/report/views/kementrian/kbwu.php), [pertama.php](protected/modules/report/views/kementrian/pertama.php).

### `protected/modules/report/views/kendaraanKecamatan`

[index.php](protected/modules/report/views/kendaraanKecamatan/index.php).

### `protected/modules/report/views/pendapatan`

[index.php](protected/modules/report/views/pendapatan/index.php).

### `protected/modules/report/views/pengujian`

[grafik_buku_uji.php](protected/modules/report/views/pengujian/grafik_buku_uji.php), [index_buku_uji.php](protected/modules/report/views/pengujian/index_buku_uji.php), [index_uji_pertama.php](protected/modules/report/views/pengujian/index_uji_pertama.php), [table_buku_uji.php](protected/modules/report/views/pengujian/table_buku_uji.php).

### `protected/modules/report/views/rekomendasi`

[index.php](protected/modules/report/views/rekomendasi/index.php).

### `protected/modules/report/views/retribusi`

[data_kendaraan.php](protected/modules/report/views/retribusi/data_kendaraan.php), [data_kendaraan_per_bln.php](protected/modules/report/views/retribusi/data_kendaraan_per_bln.php), [hasil_pemeriksaan_tgl.php](protected/modules/report/views/retribusi/hasil_pemeriksaan_tgl.php), [index.php](protected/modules/report/views/retribusi/index.php).

### `protected/modules/report/views/tahunan`

[index_report_kelulusan.php](protected/modules/report/views/tahunan/index_report_kelulusan.php), [index_report_mutasi.php](protected/modules/report/views/tahunan/index_report_mutasi.php), [index_report_numpang_uji.php](protected/modules/report/views/tahunan/index_report_numpang_uji.php), [index_report_pendapatan.php](protected/modules/report/views/tahunan/index_report_pendapatan.php), [index_report_terdaftar.php](protected/modules/report/views/tahunan/index_report_terdaftar.php), [index_report_uji_kendaraan.php](protected/modules/report/views/tahunan/index_report_uji_kendaraan.php).

### `protected/modules/report/views/tl`

[index.php](protected/modules/report/views/tl/index.php).

### `protected/modules/report/views/ujikendaraan`

[index.php](protected/modules/report/views/ujikendaraan/index.php).

### `protected/modules/retribusi/views/bukuuji`

[cetak_buku_uji.php](protected/modules/retribusi/views/bukuuji/cetak_buku_uji.php), [cetak_l.php](protected/modules/retribusi/views/bukuuji/cetak_l.php), [list_buku_uji.php](protected/modules/retribusi/views/bukuuji/list_buku_uji.php).

### `protected/modules/retribusi/views/default`

[cetak_bap.php](protected/modules/retribusi/views/default/cetak_bap.php), [cetak_checked_retribusi.php](protected/modules/retribusi/views/default/cetak_checked_retribusi.php), [cetak_gesekan.php](protected/modules/retribusi/views/default/cetak_gesekan.php), [cetak_permohonan.php](protected/modules/retribusi/views/default/cetak_permohonan.php), [cetak_retribusi.php](protected/modules/retribusi/views/default/cetak_retribusi.php), [cetak_stiker.php](protected/modules/retribusi/views/default/cetak_stiker.php), [detail_retribusi.php](protected/modules/retribusi/views/default/detail_retribusi.php), [form_retribusi.php](protected/modules/retribusi/views/default/form_retribusi.php), [form_retribusi_coba.php](protected/modules/retribusi/views/default/form_retribusi_coba.php), [list_retribusi.php](protected/modules/retribusi/views/default/list_retribusi.php), [print.php](protected/modules/retribusi/views/default/print.php), [replace_file.php](protected/modules/retribusi/views/default/replace_file.php).

### `protected/modules/retribusi/views/validasi`

[cetak_checked_retribusi.php](protected/modules/retribusi/views/validasi/cetak_checked_retribusi.php), [cetak_checked_skrd.php](protected/modules/retribusi/views/validasi/cetak_checked_skrd.php), [cetak_retribusi.php](protected/modules/retribusi/views/validasi/cetak_retribusi.php), [cetak_retribusi_skrd.php](protected/modules/retribusi/views/validasi/cetak_retribusi_skrd.php), [cetak_skrd.php](protected/modules/retribusi/views/validasi/cetak_skrd.php), [list_validasi.php](protected/modules/retribusi/views/validasi/list_validasi.php).

### `protected/modules/rights/views`

[_flash.php](protected/modules/rights/views/_flash.php), [_menu.php](protected/modules/rights/views/_menu.php).

### `protected/modules/rights/views/assignment`

[_form.php](protected/modules/rights/views/assignment/_form.php), [user.php](protected/modules/rights/views/assignment/user.php), [view.php](protected/modules/rights/views/assignment/view.php).

### `protected/modules/rights/views/authItem`

[_childForm.php](protected/modules/rights/views/authItem/_childForm.php), [_form.php](protected/modules/rights/views/authItem/_form.php), [_generateItems.php](protected/modules/rights/views/authItem/_generateItems.php), [create.php](protected/modules/rights/views/authItem/create.php), [generate.php](protected/modules/rights/views/authItem/generate.php), [operations.php](protected/modules/rights/views/authItem/operations.php), [permissions.php](protected/modules/rights/views/authItem/permissions.php), [roles.php](protected/modules/rights/views/authItem/roles.php), [tasks.php](protected/modules/rights/views/authItem/tasks.php), [update.php](protected/modules/rights/views/authItem/update.php).

### `protected/modules/rights/views/install`

[confirm.php](protected/modules/rights/views/install/confirm.php), [error.php](protected/modules/rights/views/install/error.php), [ready.php](protected/modules/rights/views/install/ready.php).

### `protected/modules/rights/views/layouts`

[main.php](protected/modules/rights/views/layouts/main.php).

### `protected/modules/user/views/admin`

[_form.php](protected/modules/user/views/admin/_form.php), [_search.php](protected/modules/user/views/admin/_search.php), [create.php](protected/modules/user/views/admin/create.php), [index.php](protected/modules/user/views/admin/index.php), [update.php](protected/modules/user/views/admin/update.php), [view.php](protected/modules/user/views/admin/view.php).

### `protected/modules/user/views/profile`

[changepassword.php](protected/modules/user/views/profile/changepassword.php), [edit.php](protected/modules/user/views/profile/edit.php), [profile.php](protected/modules/user/views/profile/profile.php).

### `protected/modules/user/views/profileField`

[_form.php](protected/modules/user/views/profileField/_form.php), [_search.php](protected/modules/user/views/profileField/_search.php), [_view.php](protected/modules/user/views/profileField/_view.php), [admin.php](protected/modules/user/views/profileField/admin.php), [create.php](protected/modules/user/views/profileField/create.php), [update.php](protected/modules/user/views/profileField/update.php), [view.php](protected/modules/user/views/profileField/view.php).

### `protected/modules/user/views/recovery`

[changepassword.php](protected/modules/user/views/recovery/changepassword.php), [recovery.php](protected/modules/user/views/recovery/recovery.php).

### `protected/modules/user/views/user`

[index.php](protected/modules/user/views/user/index.php), [login.php](protected/modules/user/views/user/login.php), [message.php](protected/modules/user/views/user/message.php), [registration.php](protected/modules/user/views/user/registration.php), [view.php](protected/modules/user/views/user/view.php).

### `protected/views/layouts`

[column1.php](protected/views/layouts/column1.php), [main.php](protected/views/layouts/main.php).

### `protected/views/other`

[grafik_bar.php](protected/views/other/grafik_bar.php), [tl.php](protected/views/other/tl.php).

### `protected/views/other/grafik`

[kelulusan.php](protected/views/other/grafik/kelulusan.php), [kelulusan_bar_lls.php](protected/views/other/grafik/kelulusan_bar_lls.php), [kelulusan_bar_tl.php](protected/views/other/grafik/kelulusan_bar_tl.php), [mutasi.php](protected/views/other/grafik/mutasi.php), [numpang_uji.php](protected/views/other/grafik/numpang_uji.php), [ubah_laporan.php](protected/views/other/grafik/ubah_laporan.php), [uji_bar.php](protected/views/other/grafik/uji_bar.php).

### `protected/views/other/table`

[kelulusan.php](protected/views/other/table/kelulusan.php), [mutasi.php](protected/views/other/table/mutasi.php), [numpang_uji.php](protected/views/other/table/numpang_uji.php).

### `protected/views/site`

[data_kendaraan.php](protected/views/site/data_kendaraan.php), [form_change_password.php](protected/views/site/form_change_password.php), [form_profile.php](protected/views/site/form_profile.php), [form_upload.php](protected/views/site/form_upload.php), [hasil_pemeriksaan.php](protected/views/site/hasil_pemeriksaan.php), [home_bar_retribusi.php](protected/views/site/home_bar_retribusi.php), [home_employee.php](protected/views/site/home_employee.php), [index.php](protected/views/site/index.php), [main_login.php](protected/views/site/main_login.php), [taxiScheduleTest.php](protected/views/site/taxiScheduleTest.php), [welcome.php](protected/views/site/welcome.php).

### `themes/adminlte/views/layouts`

[main.php](themes/adminlte/views/layouts/main.php), [main_android.php](themes/adminlte/views/layouts/main_android.php), [main_ori.php](themes/adminlte/views/layouts/main_ori.php), [main_top.php](themes/adminlte/views/layouts/main_top.php).

### JavaScript/CSS khusus modul

**android**: [assets/css/android.css](protected/modules/android/assets/css/android.css), [assets/css/status_recommendation.css](protected/modules/android/assets/css/status_recommendation.css), [assets/js/android.js](protected/modules/android/assets/js/android.js), [assets/js/recommendation.js](protected/modules/android/assets/js/recommendation.js).

**cis**: [assets/css/check_radio.css](protected/modules/cis/assets/css/check_radio.css), [assets/css/verifikasi_edit_kelulusan.css](protected/modules/cis/assets/css/verifikasi_edit_kelulusan.css), [assets/js/emisi.js](protected/modules/cis/assets/js/emisi.js), [assets/js/jquery.numeric.js](protected/modules/cis/assets/js/jquery.numeric.js), [assets/js/lampu.js](protected/modules/cis/assets/js/lampu.js), [assets/js/pitlift.js](protected/modules/cis/assets/js/pitlift.js), [assets/js/posisi.js](protected/modules/cis/assets/js/posisi.js), [assets/js/prauji.js](protected/modules/cis/assets/js/prauji.js), [assets/js/rem.js](protected/modules/cis/assets/js/rem.js).

**display**: [assets/css/android.css](protected/modules/display/assets/css/android.css), [assets/css/status_recommendation.css](protected/modules/display/assets/css/status_recommendation.css), [assets/js/android.js](protected/modules/display/assets/js/android.js), [assets/js/hasil.js](protected/modules/display/assets/js/hasil.js), [assets/js/recommendation.js](protected/modules/display/assets/js/recommendation.js).

**ikm**: [assets/css/bootstrap.css](protected/modules/ikm/assets/css/bootstrap.css), [assets/css/ionicons.min.css](protected/modules/ikm/assets/css/ionicons.min.css), [assets/css/keyframes.css](protected/modules/ikm/assets/css/keyframes.css), [assets/css/style.css](protected/modules/ikm/assets/css/style.css), [assets/js/functions.js](protected/modules/ikm/assets/js/functions.js), [assets/js/jquery-1.9.1.min.js](protected/modules/ikm/assets/js/jquery-1.9.1.min.js), [assets/js/jquery.min.js](protected/modules/ikm/assets/js/jquery.min.js), [assets/js/jquery.smoothState.min.js](protected/modules/ikm/assets/js/jquery.smoothState.min.js).

**loket**: [assets/css/rollingalat.css](protected/modules/loket/assets/css/rollingalat.css), [assets/js/cekterdaftar.js](protected/modules/loket/assets/js/cekterdaftar.js), [assets/js/datang.js](protected/modules/loket/assets/js/datang.js), [assets/js/edit_buku.js](protected/modules/loket/assets/js/edit_buku.js), [assets/js/loket.js](protected/modules/loket/assets/js/loket.js), [assets/js/rollingalat.js](protected/modules/loket/assets/js/rollingalat.js), [assets/js/terdaftar.js](protected/modules/loket/assets/js/terdaftar.js).

**master**: [assets/js/master.js](protected/modules/master/assets/js/master.js), [assets/js/master_riwayat.js](protected/modules/master/assets/js/master_riwayat.js).

**pengujian**: [assets/css/verifikasi_edit_kelulusan.css](protected/modules/pengujian/assets/css/verifikasi_edit_kelulusan.css), [assets/js/riwayat.js](protected/modules/pengujian/assets/js/riwayat.js), [assets/js/riwayat_ori.js](protected/modules/pengujian/assets/js/riwayat_ori.js), [assets/js/verifikasi.js](protected/modules/pengujian/assets/js/verifikasi.js).

**pengukuran**: [assets/css/check_radio.css](protected/modules/pengukuran/assets/css/check_radio.css), [assets/css/verifikasi_edit_kelulusan.css](protected/modules/pengukuran/assets/css/verifikasi_edit_kelulusan.css), [assets/js/jquery.numeric.js](protected/modules/pengukuran/assets/js/jquery.numeric.js), [assets/js/pengukuran.js](protected/modules/pengukuran/assets/js/pengukuran.js), [assets/js/prauji.js](protected/modules/pengukuran/assets/js/prauji.js).

**recommendation**: [assets/css/check_radio.css](protected/modules/recommendation/assets/css/check_radio.css), [assets/css/recommendation.css](protected/modules/recommendation/assets/css/recommendation.css), [assets/css/status_recommendation.css](protected/modules/recommendation/assets/css/status_recommendation.css), [assets/js/recommendation.js](protected/modules/recommendation/assets/js/recommendation.js), [assets/js/uploadfile.js](protected/modules/recommendation/assets/js/uploadfile.js).

**report**: [assets/css/daterangepicker.css](protected/modules/report/assets/css/daterangepicker.css), [assets/css/report.css](protected/modules/report/assets/css/report.css), [assets/js/daterangepicker.js](protected/modules/report/assets/js/daterangepicker.js), [assets/js/moment.js](protected/modules/report/assets/js/moment.js), [assets/js/report.js](protected/modules/report/assets/js/report.js), [assets/js/tahunan.js](protected/modules/report/assets/js/tahunan.js).

**retribusi**: [assets/css/retribusi.css](protected/modules/retribusi/assets/css/retribusi.css), [assets/js/buku_uji.js](protected/modules/retribusi/assets/js/buku_uji.js), [assets/js/retribusi.js](protected/modules/retribusi/assets/js/retribusi.js), [assets/js/validasi.js](protected/modules/retribusi/assets/js/validasi.js).

