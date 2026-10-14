# API dan Web Endpoint Contract

> Tanggal analisis: 14 Oktober 2026. Metode: pemeriksaan statis repository; bukan hasil pengujian terhadap database/server produksi. **Terbukti** berarti ada implementasi di kode; **inferensi** berarti disimpulkan dari pemakaian; **rekomendasi** berarti perubahan yang belum diimplementasikan. Contoh data bersifat sintetis. Nilai kredensial sengaja tidak disalin.

Dokumen terkait: [PRD](PRD.md) · [Arsitektur](TECH_SPEC.md) · [Database](DATABASE_SCHEMA.md) · [Kontrak endpoint](API_CONTRACT.md) · [UI/UX](UI_UX_GUIDELINES.md).

**Daftar isi**

- [1. Cakupan dan base URL](#1-cakupan-dan-base-url)
- [2. Autentikasi, header dan encoding](#2-autentikasi-header-dan-encoding)
- [3. Konvensi respons aktual](#3-konvensi-respons-aktual)
- [4. Alias route eksplisit](#4-alias-route-eksplisit)
- [5. Kontrak backend Android](#5-kontrak-backend-android)
- [6. API agregat dan sinkronisasi master](#6-api-agregat-dan-sinkronisasi-master)
- [7. Contoh pemanggilan aman untuk staging](#7-contoh-pemanggilan-aman-untuk-staging)
- [8. Error handling dan status HTTP](#8-error-handling-dan-status-http)
- [9. Inventaris lengkap action aplikasi](#9-inventaris-lengkap-action-aplikasi)

## 1. Cakupan dan base URL

Kontrak ini merekam implementasi **legacy as-is**: route halaman, AJAX web, Android, sinkronisasi master, dan route paket nonaktif. Domain deployment tidak tersedia. Gunakan placeholder `BASE_URL=https://pkb.example.invalid` (tambahkan subpath deployment bila ada). Semua path di dokumen relatif terhadap base tersebut.

`urlManager` memakai path format, `showScriptName=false`. Bentuk normal `/module/controller/action`; controller utama `/controller/action`. Fallback front controller dapat memakai `/index.php/module/controller/action` bila server meneruskan PATH_INFO. Alias module/controller yang menghilangkan `index` hanya berfungsi bila action default tersebut benar-benar tersedia. Jangan menganggap seluruh root module mempunyai `index`.

Tidak ada konvensi REST resource atau pembatasan HTTP verb global. **Metode pada katalog adalah cara pemanggilan yang diinferensikan dari pembacaan input, bukan jaminan verb lain ditolak dengan 405.** Route dengan JSON body biasanya dapat dipanggil POST, tetapi tidak memiliki filter verb eksplisit. Action baca tanpa input lazim dipanggil GET; GET bukan jaminan bebas efek samping pada kode ini.

## 2. Autentikasi, header dan encoding

| Kode header katalog | Request | Authentication |
|---|---|---|
| H-HTML | GET/query atau POST form sesuai action; browser menerima HTML | Sesi bila filter Rights/accessControl memerlukannya |
| H-FORM | `Content-Type: application/x-www-form-urlencoded`; POST field | Cookie session untuk endpoint terlindungi |
| H-JSON | `Content-Type: application/json`; raw UTF-8 JSON | Android dan ApiController tidak memiliki pemeriksaan token eksplisit |
| H-UPLOAD | `Content-Type: multipart/form-data; boundary=...` | Ikuti filter controller; library membaca input file tambahan |
| H-GET | GET dan query parameters | Ikuti filter controller; respons dapat HTML/JSON/file |

Header `Accept: application/json` tidak mengubah renderer secara otomatis. `X-Requested-With: XMLHttpRequest` hanya memengaruhi action yang memeriksa AJAX. Tidak ditemukan CORS policy, API version negotiation, rate limit atau refresh-token middleware yang dikonfigurasi. Cookie tidak boleh dipaksakan namanya; gunakan cookie yang diberikan login/server.

`tokenApi` pada config hanya untuk HTTP **keluar** ke Kemenhub. Jangan mengirimkan secret tersebut sebagai token client Android.

## 3. Konvensi respons aktual

Tidak ada satu envelope global. Pola berikut harus ditangani berbeda:

| Pola | Contoh ilustratif | Catatan |
|---|---|---|
| Objek pencarian Android | `{"no_uji":"UJI-CONTOH","success":true}` | Field lengkap berbeda per action; sebagian tidak punya success |
| Array Android | `[{"keterangan":"Dokumen contoh"}]` | Empty result umumnya `[]` pada endpoint array |
| Grid EasyUI | `{"total":0,"rows":[]}` | Beberapa grid menambahkan HTML dalam sel/field lain |
| Agregat retribusi | `{"status":"sukses","tahun":2026,"total":"0","data":[]}` | Nominal adalah string format Indonesia; `data` bisa tidak terinisialisasi ketika tidak ada baris |
| Hasil mutasi web | `{"ada":"true","message":"..."}` atau body kosong | String boolean bukan boolean JSON; tidak selalu berarti HTTP error |
| HTML | Dokumen halaman atau fragmen renderPartial | Bukan API JSON |
| Ekspor | Excel/PDF atau HTML cetak | Header download sesuai controller; gunakan client binary |
| Sinkronisasi Kemenhub | `<pre>array (...)</pre>` atau pesan teks | Bukan response JSON stabil |

Tanggal Android banyak memakai `d F Y` (contoh `14 October 2026`), input pendaftaran memakai `d/m/Y`, sedangkan query memakai representasi berbeda. Nilai boolean dapat berupa bool, string `true/false`, atau flag `0/1`. Field gambar dapat berupa base64 atau placeholder `-`; `hasilUji` bahkan menginisialisasi `img_depan` dengan `A`. Jangan decode placeholder sebagai gambar valid.

## 4. Alias route eksplisit

| Path | Target | Status |
|---|---|---|
| `/` | `site/home` | Key kosong terakhir menggantikan `site/index` |
| `/pkb` | `site/pkb` | Login atau redirect retribusi/validasi |
| `/dashboard` | `site/home` | Dashboard HTML |
| `/api/v1/retribusiperbulan` | `api/retribusiPerBulan` | Aktif di kode |
| `/api/v1/retribusiperhari` | `api/retribusiPerHari` | Aktif di kode |
| `/posting` | `site/posting` | Action tidak ditemukan |
| `/paymentQr` | `site/paymentQr` | Action tidak ditemukan |
| `/inquiry` | `site/inquiry` | Action tidak ditemukan |
| `/payment` | `site/payment` | Action tidak ditemukan |
| `/reversal` | `site/reversal` | Action tidak ditemukan |
| `/<controller>/<id angka>` | `<controller>/view` | Hanya bekerja bila controller menyediakan action view |
| `/<controller>/<action>/<id angka>` | `<controller>/<action>` | id menjadi parameter route |

`site/captcha` dan `site/page` adalah class-based actions dari `SiteController::actions()`, bukan method `action*`. `site/page` memakai parameter `view` dan render halaman statis; `site/captcha` menghasilkan gambar challenge, dengan parameter kontrol framework `refresh`/`v` sesuai CCaptchaAction. Gii adalah modul framework bersyarat konfigurasi dan tidak dimasukkan sebagai API produk; aksesnya perlu ditutup saat produksi.

## 5. Kontrak backend Android

Sumber seluruh subbagian: [DefaultController Android](protected/modules/android/controllers/DefaultController.php). Sembilan endpoint ini tidak mendeklarasikan filter Rights/token. Semua contoh adalah bentuk respons berdasarkan kode, bukan rekaman server hidup. Field yang langsung diakses harus disuplai agar tidak menimbulkan undefined-index; validasi schema/tipe/range yang memadai belum ada.

### 5.1 `POST /android/default/riwayatKendaraan`

H-JSON. Body `{"noUji":"UJI-CONTOH"}`. Melakukan uppercase lalu pencarian partial nomor uji **atau** nomor kendaraan tanpa memperhitungkan spasi, mengambil `VRiwayat` terbaru menurut `id_riwayat DESC`.

```json
{"tempat_uji":"Pengujian Kendaraan Bermotor Sampang, Jawa Timur","no_uji":"UJI-CONTOH","no_kendaraan":"M 1234 XX","mati_uji":"14 October 2026","merk":"CONTOH","tipe":"TIPE A","no_chasis":"RANGKA-CONTOH","no_mesin":"MESIN-CONTOH","pemilik":"PEMILIK CONTOH","kondisi":"hidup","success":true}
```

Tidak ditemukan: seluruh field deskriptif `-`, `kondisi:"-"`, `success:false`. `kondisi` membandingkan tanggal mati terhadap hari ini. Content-Type JSON diset eksplisit; tidak ada 404 khusus untuk hasil kosong.

### 5.2 `POST /android/default/detailPersyaratan`

H-JSON. Body `{"kategori":"KODE_KATEGORI"}`; uppercase lalu filter `VPersyaratan.category_code`. Daftar kode kategori harus diambil dari master, tidak disertakan sebagai enum statis.

```json
[{"keterangan":"Persyaratan contoh pertama"},{"keterangan":"Persyaratan contoh kedua"}]
```

Kosong: `[]`. Content-Type response tidak diset eksplisit di action.

### 5.3 `POST /android/default/detailKendaraan`

H-FORM, field `noUji=UJI-CONTOH`. Mengambil `VKendaraan` dan foto dari hasil uji terbaru menurut `jdatang DESC`. Nomor uji dicari partial; nomor kendaraan dicocokkan pola tanpa wildcard tambahan. Field respons lengkap:

`id_kendaraan`, `no_uji`, `no_kendaraan`, `merk`, `tipe`, `no_chasis`, `no_mesin`, `pemilik`, `jns_kend`, `mati_uji`, `nama_komersil`, `jenis_karoseri`, `bahan_utama`, `panjang`, `lebar`, `tinggi`, `dimpanjang`, `dimlebar`, `dimtinggi`, `jbb`, `orang`, `barang`, `sb1`, `sb2`, `sb3`, `sb4`, `sb5`, `total_sb`, `jbi`, `mst`, `kondisi`, `success`, `tujuan`, `img_depan`, `img_belakang`, `img_kanan`, `img_kiri`.

```json
{"id_kendaraan":0,"no_uji":"-","no_kendaraan":"-","merk":"-","tipe":"-","no_chasis":"-","no_mesin":"-","pemilik":"-","jns_kend":"-","mati_uji":"-","nama_komersil":"-","jenis_karoseri":"-","bahan_utama":"-","panjang":"-","lebar":"-","tinggi":"-","dimpanjang":"-","dimlebar":"-","dimtinggi":"-","jbb":"-","orang":"-","barang":"-","sb1":"-","sb2":"-","sb3":"-","sb4":"-","sb5":"-","total_sb":"-","jbi":"-","mst":"-","kondisi":"mati","success":false,"tujuan":"-","img_depan":"-","img_belakang":"-","img_kanan":"-","img_kiri":"-"}
```

Contoh adalah hasil kosong. Pada hasil ditemukan, atribut DB menggantikan nilai default; jangan menganggap semua ukuran bertipe JSON number karena tipe PDO/format dapat berbeda. Kode masih membutuhkan pemeriksaan null pada record turunan.

### 5.4 `POST /android/default/hasilUji`

H-FORM, field `noUji`. Mengambil `VStatusProses` terbaru (`id_hasil_uji DESC`) dan daftar ketidaklulusan `VDetailTl`.

```json
{"no_uji":"UJI-CONTOH","no_kendaraan":"M 1234 XX","pemilik":"PEMILIK CONTOH","hasil_tgl_terakhir_uji":"14 October 2026","hasil_tgl_mati_uji":"14 April 2027","hasil_prauji":"LULUS","hasil_emisi":"LULUS","hasil_pitlift":"LULUS","hasil_lampu":"LULUS","hasil_break":"LULUS","ltl":"LULUS","keterangan":"","kondisi":"hidup","img_depan":"-","img_belakang":"-","img_kanan":"-","img_kiri":"-"}
```

Tahap yang belum selesai bernilai `PROSES`; selesai bernilai LULUS/TIDAK LULUS. `ltl` memakai hasil akhir hanya setelah kelima tahap selesai. Tidak ditemukan: field teks hasil `-`, kondisi `mati`, img_depan `A`, gambar lain `-`. Tidak ada field `success` di endpoint ini.

### 5.5 `POST /android/default/statusRekom`

H-JSON, body `{"noUji":"UJI-CONTOH"}`. Query `VRekom` tidak menetapkan urutan terbaru secara eksplisit; jangan menjanjikan rekomendasi paling baru. Flag prioritas jenis: mutke → numke → ubhsifat.

```json
{"no_uji":"UJI-CONTOH","no_kendaraan":"M 1234 XX","tgl_rekom":"14 October 2026","rekom":"Mutasi Keluar","kasubag":0,"kaupt":0,"kadis":0,"tglKasubag":"-","tglKaupt":"-","tglKadis":"-","lokasiRekom":"UPTD PKB Wiyung Surabaya"}
```

Contoh saat rekomendasi ditemukan tetapi record status belum ada. Jika seluruh rekomendasi tidak ditemukan, data identitas/tanggal/jenis `-`, flag 0, lokasi `UPTD PKB Wiyung Tandes`. Jika status ada, tipe flag mengikuti DB dan tanggal di-format tanpa normalisasi null yang konsisten.

### 5.6 `POST /android/default/listKendaraan`

Kode membaca `noUji` dari JSON raw, tetapi `index` dari `$_POST`. Pagination: page default 0, size tetap **7**, offset `index*7`, urut `id_hasil_uji DESC`, sumber `VVerifikasi`.

```json
[{"no_kendaraan":"M 1234 XX","no_uji":"UJI-CONTOH","nama_pemilik":"PEMILIK CONTOH","status_kelulusan":"LULUS"}]
```

Kosong: `[]`. Request JSON `{"noUji":""}` dapat mengambil halaman awal. **Pagination campuran ini cacat kontrak**: request JSON biasa tidak mengisi `$_POST[index]`; body form tidak menjadi JSON valid. Tidak ada contoh request yang menjanjikan pagination halaman berikutnya berfungsi dengan dua encoding sekaligus. Rekomendasi perbaikan adalah satu JSON schema `{noUji,index}` atau konsisten form; belum diimplementasikan.

### 5.7 `GET /android/default/totalListKendaraan`

H-GET, tanpa input. Metode tidak dibatasi; agregasi hari ini menggunakan PostgreSQL `current_date` dan `tbl_daftar`/`tbl_hasil_uji`/`tbl_kuota`.

```json
{"mobil_barang_tandes":"0","mobil_bis_tandes":"0","mobil_penumpang_tandes":"0","mobil_khusus_tandes":"0","mobil_gandengan_tandes":"0","mobil_tempelan_tandes":"0","jumlah_tandes":"0","mobil_datang_tandes":"0","mobil_tidak_datang_tandes":"0","lulus_uji_tandes":"0","tidak_lulus_uji_tandes":"0"}
```

Angka dapat dikembalikan PDO sebagai string/number; contoh memakai string. Nama suffix `tandes` adalah kontrak legacy. Subquery kuota mengasumsikan cardinality cocok; data kuota ganda dapat memicu error. Header JSON diset eksplisit.

### 5.8 `POST /android/default/listRiwayatKendaraan`

H-FORM, field `noUji`. Pencarian partial di nomor uji/kendaraan, `VRiwayat`, urut `id_riwayat DESC`, tanpa pagination.

```json
[{"no_uji":"UJI-CONTOH","no_kendaraan":"M 1234 XX","tgl_uji":"14 October 2026","tglmati":"14 April 2027","merk":"CONTOH","tipe":"TIPE A","no_chasis":"RANGKA-CONTOH","no_mesin":"MESIN-CONTOH","nm_penguji":"PENGUJI CONTOH","nrp":"NRP-CONTOH","no_seri":"SERI-CONTOH","nm_uji":"BERKALA","catatan":"-"}]
```

`jenis_uji=MK` menghasilkan MUTASI KELUAR, `NK` NUMPANG UJI KELUAR, selain itu BERKALA. Jika `noUji` tepat `-`, response adalah satu item placeholder seluruh field `-`. Pencarian lain tanpa hasil menghasilkan `[]`. Tidak mengirim `noUji` bukan cara yang valid meminta placeholder.

### 5.9 `POST /android/default/saveRetribusi`

H-FORM. Input `idKendaraan` (ID kendaraan existing) dan `tglUji` berformat `d/m/Y`, contoh `14/10/2026`. Ini endpoint mutasi, bukan sekadar kalkulasi. Memeriksa `VValidasi` lalu memanggil fungsi `insert_retribusi` bila belum ada.

```json
{"cekTerdaftar":"1","statusResponse":"1","va":"0","b_retribusi":"Rp. 100.000,-","b_rekom":"Rp. 0,-","b_denda":"Rp. 0,-","b_kartu_rusak":"Rp. 0,-","total_bayar":"Rp. 100.000,-"}
```

Nominal ilustratif, bukan tarif aplikasi. `cekTerdaftar=1` berarti data sebelumnya ditemukan; `0` berarti mencoba insert baru. `statusResponse=0` hanya ketika lookup sesudah insert gagal; kegagalan SQL/tanggal dapat menjadi exception di luar envelope. Default komponen biaya berupa number 0, lalu menjadi string rupiah jika ada data. `va` tetap string `0`; blok QRIS dikomentari. Tidak ada idempotency key, transaksi eksplisit atau validasi ID/tanggal yang memadai pada action.

## 6. API agregat dan sinkronisasi master

### 6.1 Retribusi per bulan dan hari

`POST /api/v1/retribusiperbulan`, H-JSON, body `{"tahun":2026}`. `POST /api/v1/retribusiperhari`, H-JSON, body `{"tahun":2026,"bulan":10}`. Alias langsung `/api/retribusiPerBulan` dan `/api/retribusiPerHari` juga mengikuti routing controller.

Kode membuat opsi stream method GET ketika membaca `php://input`; itu **bukan** enforcement metode request masuk dan tidak mengubah endpoint menjadi GET-only. Gunakan POST JSON untuk body; jangan mengandalkan body GET didukung semua proxy.

```json
{"status":"sukses","tahun":2026,"total":"100.000","data":[{"bulan":"Januari","total_retribusi":"100.000"}]}
```

```json
{"status":"sukses","bulan":"Oktober","tahun":2026,"total":"100.000","data":[{"tanggal":1,"total_retribusi":"100.000"}]}
```

Contoh memperlihatkan struktur, bukan hasil agregasi aktual. Nominal format Indonesia. Input wajib dikirim; validasi range tahun/bulan tidak dilakukan. Harian menghilangkan hari terakhir akibat kondisi `<`, menghapus Sabtu/Minggu dari item tetapi total dijumlah sebelum filter. `$totalRetribusi` tidak diinisialisasi sebelum loop; error/warning atau `null` bisa muncul jika tidak ada data. Keduanya secara eksplisit menetapkan HTTP 200 dan Content-Type JSON pada jalur normal.

### 6.2 Action integrasi Kemenhub

Semua route `/api/<action>` berikut membaca raw body tetapi tidak menggunakan parameter client sebagai kontrak utama; token/prefix diisi server. Tidak ada filter Rights/HTTP verb. Output normal berupa HTML `<pre>` hasil `var_export`, atau teks message upstream. Action yang menulis harus diperlakukan sebagai operasi administratif meskipun GET dapat menjalankannya.

| Action | Prefix outbound | Efek lokal aktif |
|---|---|---|
| apiStatusPenerbitan | statuspenerbitan | TRUNCATE/INSERT master_dataissuance |
| apiKelasJalan | kelasjalan | TRUNCATE/INSERT master_dataklsjln |
| apiBahanBakar | fuel | TRUNCATE/INSERT master_datafuel |
| apiMerk | merk | TRUNCATE/INSERT master_databrand |
| apiVarianType | variantype | Hanya tampilkan; blok penyimpanan dikomentari |
| apiVarian | varian | Hanya tampilkan; blok penyimpanan dikomentari |
| apiVehicleType | vehicletype | TRUNCATE/INSERT master_datavehicletype |
| apiSubVehicleType | subvehicletype | TRUNCATE/INSERT master_datasubvehicle |
| apiPegawai | pegawai | TRUNCATE/INSERT master_dataemployee |
| apiArea | area | TRUNCATE/INSERT master_dataarea |
| apiVta | vta | Hanya tampilkan |
| updateData | — | Isi action dikomentari; tidak ada operasi aktif |

Body **outbound** ilustratif: `{"token":"<SECRET_SERVER>","prefix":"kelasjalan"}` ke URL config Kemenhub. Tidak ada jaminan response/error upstream atau transaksi atomik pada codepath; kontrak layanan eksternal tidak diverifikasi dari internet/server.

## 7. Contoh pemanggilan aman untuk staging

Contoh ini tidak dieksekusi. Gunakan hanya lingkungan dengan data sintetis dan kontrol akses yang sesuai.

```bash
curl 'https://pkb.example.invalid/android/default/riwayatKendaraan' \
  -H 'Content-Type: application/json' \
  --data '{"noUji":"UJI-CONTOH"}'

curl 'https://pkb.example.invalid/android/default/hasilUji' \
  --data-urlencode 'noUji=UJI-CONTOH'

curl 'https://pkb.example.invalid/api/v1/retribusiperbulan' \
  -H 'Content-Type: application/json' \
  --data '{"tahun":2026}'
```

Login web memakai form model bersarang dan menghasilkan HTML/redirect, bukan bearer token JSON. Browser/session client harus mengikuti cookie serta redirect; jangan menafsirkan halaman login HTTP 200 sebagai data API sukses.

## 8. Error handling dan status HTTP

| Situasi | Perilaku as-is | Target yang direkomendasikan |
|---|---|---|
| Respons normal agregat | 200 eksplisit | 200 dengan schema tetap |
| Pencarian Android kosong | Placeholder/[] dan biasanya 200 | Konsistenkan empty collection vs resource tidak ditemukan |
| JSON malformed/field hilang/tanggal invalid | Undefined index, null dereference atau exception; tidak ada envelope baku | 400/422, kode error stabil dan detail field aman |
| Guest pada route berfilter | Rights memanggil loginRequired; dapat redirect/login atau respons khusus AJAX | API 401 JSON, web redirect |
| Login tetapi izin kurang | Rights `CHttpException(403)` | 403 tanpa detail internal |
| Controller/action tidak ada | Penanganan error route Yii (umumnya 404) | 404 konsisten |
| Parameter action wajib hilang | Binding parameter Yii dapat menolak sebagai bad request | 400 terstruktur |
| DB/upstream gagal | Exception, warning, HTML atau pesan upstream | 500/502/503 terkontrol dan request ID |
| Metode salah | Tidak ada kebijakan 405 menyeluruh | Verb filter dan 405 |

Debug aktif dapat menampilkan informasi teknis; non-debug dikonfigurasi ke `site/error` yang belum memiliki action. Karena itu tidak ada contoh error JSON global yang sah untuk diklaim sebagai implementasi.

Usulan envelope **belum diimplementasikan**:

```json
{"success":false,"error":{"code":"VALIDATION_ERROR","message":"Input tidak valid","fields":{"tglUji":["Gunakan dd/mm/yyyy"]}},"request_id":"contoh-request-id"}
```

## 9. Inventaris lengkap action aplikasi

Inventaris berikut mencakup **419 inline actions dalam 74 controller**, termasuk **21 action paket `user` yang tidak terdaftar**. Ditambah dua class-based action Site dan alias di atas. Komentar PHP dibuang sebelum ekstraksi, sehingga fungsi yang hanya ada di komentar tidak dihitung. Tooling Gii dan demo framework tidak menjadi API produk.

Untuk setiap controller dicantumkan semua route, metode pemanggilan, input langsung/nested, indikasi keluaran, dan sumber. **Input yang tertulis adalah field yang dibaca kode, bukan otomatis optional/required tervalidasi**. Parameter dalam signature wajib bila tidak punya default; field yang dibaca langsung tanpa guard harus dikirim. Kode helper/library atau form model dapat membaca input tambahan. `—` berarti tidak ada pembacaan langsung yang ditemukan, bukan larangan parameter.

Header: JSON → H-JSON; form POST → H-FORM; `$_FILES`/upload → H-UPLOAD; GET/query → H-GET. Filter Rights/accessControl memerlukan cookie sesi sesuai policy DB. Respons yang berupa ekspresi PHP tidak boleh dibaca sebagai contoh JSON literal: ekspresi dicantumkan agar struktur asli dapat ditelusuri tanpa menciptakan payload fiktif. Contoh JSON konkret untuk mobile/agregat ada pada bagian 5–6; pola grid/HTML/download ada pada bagian 3. Untuk action web, validasi dan model tambahan mengikuti view/controller yang ditautkan.

### Indeks controller

| Controller | Action inline | Status modul |
|---|---|---|
| [/api/](#controller-1) | 14 | Terdaftar/utama |
| [/other/](#controller-2) | 4 | Terdaftar/utama |
| [/site/](#controller-3) | 8 | Terdaftar/utama |
| [/android/default/](#controller-4) | 9 | Terdaftar/utama |
| [/capture/default/](#controller-5) | 2 | Terdaftar/utama |
| [/cis/default/](#controller-6) | 6 | Terdaftar/utama |
| [/cis/emisi/](#controller-7) | 3 | Terdaftar/utama |
| [/cis/lampu/](#controller-8) | 3 | Terdaftar/utama |
| [/cis/pengujian/](#controller-9) | 3 | Terdaftar/utama |
| [/cis/pitlift/](#controller-10) | 3 | Terdaftar/utama |
| [/cis/posisi/](#controller-11) | 7 | Terdaftar/utama |
| [/cis/prauji/](#controller-12) | 5 | Terdaftar/utama |
| [/cis/praujiGandengan/](#controller-13) | 4 | Terdaftar/utama |
| [/cis/rem/](#controller-14) | 3 | Terdaftar/utama |
| [/cis/remGandengan/](#controller-15) | 3 | Terdaftar/utama |
| [/display/cisdua/](#controller-16) | 7 | Terdaftar/utama |
| [/display/cissatu/](#controller-17) | 8 | Terdaftar/utama |
| [/display/default/](#controller-18) | 2 | Terdaftar/utama |
| [/display/hasil/](#controller-19) | 2 | Terdaftar/utama |
| [/display/iyasaka/](#controller-20) | 6 | Terdaftar/utama |
| [/display/muller/](#controller-21) | 6 | Terdaftar/utama |
| [/display/prosesuji/](#controller-22) | 7 | Terdaftar/utama |
| [/ikm/default/](#controller-23) | 2 | Terdaftar/utama |
| [/loket/cekTerdaftar/](#controller-24) | 2 | Terdaftar/utama |
| [/loket/default/](#controller-25) | 12 | Terdaftar/utama |
| [/loket/editbuku/](#controller-26) | 14 | Terdaftar/utama |
| [/loket/kedatangan/](#controller-27) | 10 | Terdaftar/utama |
| [/loket/rollingalat/](#controller-28) | 6 | Terdaftar/utama |
| [/loket/terdaftar/](#controller-29) | 4 | Terdaftar/utama |
| [/loket/ujiulang/](#controller-30) | 4 | Terdaftar/utama |
| [/loket/upload/](#controller-31) | 3 | Terdaftar/utama |
| [/master/default/](#controller-32) | 24 | Terdaftar/utama |
| [/master/riwayat/](#controller-33) | 7 | Terdaftar/utama |
| [/pengujian/bap/](#controller-34) | 1 | Terdaftar/utama |
| [/pengujian/bapTl/](#controller-35) | 1 | Terdaftar/utama |
| [/pengujian/blokir/](#controller-36) | 3 | Terdaftar/utama |
| [/pengujian/default/](#controller-37) | 4 | Terdaftar/utama |
| [/pengujian/printhasil/](#controller-38) | 12 | Terdaftar/utama |
| [/pengujian/riwayat/](#controller-39) | 8 | Terdaftar/utama |
| [/pengujian/riwayatOri/](#controller-40) | 7 | Terdaftar/utama |
| [/pengujian/verifikasi/](#controller-41) | 10 | Terdaftar/utama |
| [/pengukuran/pengukuran/](#controller-42) | 8 | Terdaftar/utama |
| [/pengukuran/prauji/](#controller-43) | 7 | Terdaftar/utama |
| [/recommendation/bukuuji/](#controller-44) | 7 | Terdaftar/utama |
| [/recommendation/default/](#controller-45) | 0 | Terdaftar/utama |
| [/recommendation/rekom/](#controller-46) | 7 | Terdaftar/utama |
| [/recommendation/report/](#controller-47) | 1 | Terdaftar/utama |
| [/report/bukuuji/](#controller-48) | 2 | Terdaftar/utama |
| [/report/default/](#controller-49) | 1 | Terdaftar/utama |
| [/report/kementrian/](#controller-50) | 18 | Terdaftar/utama |
| [/report/kendaraanKecamatan/](#controller-51) | 3 | Terdaftar/utama |
| [/report/pendapatan/](#controller-52) | 2 | Terdaftar/utama |
| [/report/pengujian/](#controller-53) | 7 | Terdaftar/utama |
| [/report/rekomendasi/](#controller-54) | 2 | Terdaftar/utama |
| [/report/retribusi/](#controller-55) | 5 | Terdaftar/utama |
| [/report/tahunan/](#controller-56) | 12 | Terdaftar/utama |
| [/report/tl/](#controller-57) | 2 | Terdaftar/utama |
| [/report/ujikendaraan/](#controller-58) | 2 | Terdaftar/utama |
| [/retribusi/bukuuji/](#controller-59) | 10 | Terdaftar/utama |
| [/retribusi/default/](#controller-60) | 19 | Terdaftar/utama |
| [/retribusi/validasi/](#controller-61) | 15 | Terdaftar/utama |
| [/rights/assignment/](#controller-62) | 3 | Terdaftar/utama |
| [/rights/authItem/](#controller-63) | 12 | Terdaftar/utama |
| [/rights/install/](#controller-64) | 4 | Terdaftar/utama |
| [/user/activation/](#controller-65) | 1 | Nonaktif |
| [/user/admin/](#controller-66) | 5 | Nonaktif |
| [/user/default/](#controller-67) | 1 | Nonaktif |
| [/user/login/](#controller-68) | 1 | Nonaktif |
| [/user/logout/](#controller-69) | 1 | Nonaktif |
| [/user/profile/](#controller-70) | 3 | Nonaktif |
| [/user/profileField/](#controller-71) | 5 | Nonaktif |
| [/user/recovery/](#controller-72) | 1 | Nonaktif |
| [/user/registration/](#controller-73) | 1 | Nonaktif |
| [/user/user/](#controller-74) | 2 | Nonaktif |

<a id="controller-1"></a>

### /api/ — ApiController

Sumber: [protected/controllers/ApiController.php](protected/controllers/ApiController.php).

Akses: **tidak ada filter otorisasi aktif yang dideklarasikan di controller ini**; pemeriksaan inline/module masih berlaku bila ada. Pewarisan Controller/RController saja tidak memasang filter.

#### `/api/retribusiPerBulan`

- Pemanggilan: **POST / H-JSON**; enforcement verb hanya bila terlihat di action/filter.
- Input: JSON raw: `tahun`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `json_encode( array( 'status' => "sukses", 'tahun' => $tahun, 'total' => number_format($total, 0, ",", "."), 'data' => $totalRetribusi, ) )`.
- Model yang dipakai: `VLapPad`.

#### `/api/retribusiPerHari`

- Pemanggilan: **POST / H-JSON**; enforcement verb hanya bila terlihat di action/filter.
- Input: JSON raw: `bulan`, `tahun`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `json_encode( array( 'status' => "sukses", 'bulan' => Yii::app()->params['bulanArrayInd'][$bulan - 1], 'tahun' => $tahun, 'total' => number_format($total, 0, ",", "."), 'data' => $totalRetribusi, ) )`.
- Model yang dipakai: `VLapPad`.

#### `/api/apiStatusPenerbitan`

- Pemanggilan: **POST / H-JSON**; enforcement verb hanya bila terlihat di action/filter.
- Input: JSON raw: body dibaca; tidak ada field client yang dipakai secara langsung.
- Respons: HTML `<pre>`/var_export data upstream atau teks pesan.
- Catatan: menulis atau memanggil operasi bisnis DB; HTTP outbound.

#### `/api/apiKelasJalan`

- Pemanggilan: **POST / H-JSON**; enforcement verb hanya bila terlihat di action/filter.
- Input: JSON raw: body dibaca; tidak ada field client yang dipakai secara langsung.
- Respons: HTML `<pre>`/var_export data upstream atau teks pesan.
- Catatan: menulis atau memanggil operasi bisnis DB; HTTP outbound.

#### `/api/apiBahanBakar`

- Pemanggilan: **POST / H-JSON**; enforcement verb hanya bila terlihat di action/filter.
- Input: JSON raw: body dibaca; tidak ada field client yang dipakai secara langsung.
- Respons: HTML `<pre>`/var_export data upstream atau teks pesan.
- Catatan: menulis atau memanggil operasi bisnis DB; HTTP outbound.

#### `/api/apiMerk`

- Pemanggilan: **POST / H-JSON**; enforcement verb hanya bila terlihat di action/filter.
- Input: JSON raw: body dibaca; tidak ada field client yang dipakai secara langsung.
- Respons: HTML `<pre>`/var_export data upstream atau teks pesan.
- Catatan: menulis atau memanggil operasi bisnis DB; HTTP outbound.

#### `/api/apiVarianType`

- Pemanggilan: **POST / H-JSON**; enforcement verb hanya bila terlihat di action/filter.
- Input: JSON raw: body dibaca; tidak ada field client yang dipakai secara langsung.
- Respons: HTML `<pre>`/var_export data upstream atau teks pesan.
- Catatan: HTTP outbound.

#### `/api/apiVarian`

- Pemanggilan: **POST / H-JSON**; enforcement verb hanya bila terlihat di action/filter.
- Input: JSON raw: body dibaca; tidak ada field client yang dipakai secara langsung.
- Respons: HTML `<pre>`/var_export data upstream atau teks pesan.
- Catatan: HTTP outbound.

#### `/api/apiVehicleType`

- Pemanggilan: **POST / H-JSON**; enforcement verb hanya bila terlihat di action/filter.
- Input: JSON raw: body dibaca; tidak ada field client yang dipakai secara langsung.
- Respons: HTML `<pre>`/var_export data upstream atau teks pesan.
- Catatan: menulis atau memanggil operasi bisnis DB; HTTP outbound.

#### `/api/apiSubVehicleType`

- Pemanggilan: **POST / H-JSON**; enforcement verb hanya bila terlihat di action/filter.
- Input: JSON raw: body dibaca; tidak ada field client yang dipakai secara langsung.
- Respons: HTML `<pre>`/var_export data upstream atau teks pesan.
- Catatan: menulis atau memanggil operasi bisnis DB; HTTP outbound.

#### `/api/apiPegawai`

- Pemanggilan: **POST / H-JSON**; enforcement verb hanya bila terlihat di action/filter.
- Input: JSON raw: body dibaca; tidak ada field client yang dipakai secara langsung.
- Respons: HTML `<pre>`/var_export data upstream atau teks pesan.
- Catatan: menulis atau memanggil operasi bisnis DB; HTTP outbound.

#### `/api/apiArea`

- Pemanggilan: **POST / H-JSON**; enforcement verb hanya bila terlihat di action/filter.
- Input: JSON raw: body dibaca; tidak ada field client yang dipakai secara langsung.
- Respons: HTML `<pre>`/var_export data upstream atau teks pesan.
- Catatan: menulis atau memanggil operasi bisnis DB; HTTP outbound.

#### `/api/apiVta`

- Pemanggilan: **POST / H-JSON**; enforcement verb hanya bila terlihat di action/filter.
- Input: JSON raw: body dibaca; tidak ada field client yang dipakai secara langsung.
- Respons: HTML `<pre>`/var_export data upstream atau teks pesan.
- Catatan: HTTP outbound.

#### `/api/updateData`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.

<a id="controller-2"></a>

### /other/ — OtherController

Sumber: [protected/controllers/OtherController.php](protected/controllers/OtherController.php).

Akses: **tidak ada filter otorisasi aktif yang dideklarasikan di controller ini**; pemeriksaan inline/module masih berlaku bila ada. Pewarisan Controller/RController saja tidak memasang filter.

#### `/other/grafik`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$param`.
- Respons: HTML `grafik_bar`.

#### `/other/autoComplete`

- Pemanggilan: **GET/POST / H-GET atau H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: REQUEST: `['term']`.
- Respons: JSON; kunci array/record: `id`, `value`; ekspresi output `CJSON::encode($return_array)`.
- Model yang dipakai: `TblUser`.

#### `/other/upload`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: Pemrosesan Excel di server; bukan bukti response file download.
- Model yang dipakai: `TblKendaraan`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/other/readExcel`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: Pemrosesan Excel di server; bukan bukti response file download.
- Catatan: menulis atau memanggil operasi bisnis DB.

<a id="controller-3"></a>

### /site/ — SiteController

Sumber: [protected/controllers/SiteController.php](protected/controllers/SiteController.php).

Akses: **tidak ada filter otorisasi aktif yang dideklarasikan di controller ini**; pemeriksaan inline/module masih berlaku bila ada. Pewarisan Controller/RController saja tidak memasang filter.

#### `/site/pkb`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: redirect pada cabang terkait.
- Catatan: ada pemeriksaan isGuest inline.

#### `/site/login`

- Pemanggilan: **GET+POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['ajax']`, `['LoginForm']`; nested `LoginForm[username]`, `LoginForm[password]`, optional `LoginForm[rememberMe]`.
- Respons: HTML `main_login`; redirect pada cabang terkait.

#### `/site/logout`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: redirect pada cabang terkait.

#### `/site/home`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `index`.
- Model yang dipakai: `VLapPad`, `TblDaftar`.

#### `/site/formChangePassword`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `form_change_password`.

#### `/site/changePassword`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['employee_id']`, `['new_password']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/site/runcron`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: Teks/HTML output langsung; lihat cabang pada sumber.
- Catatan: HTTP outbound.

#### `/site/runcronOri`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: Teks/HTML output langsung; lihat cabang pada sumber.
- Catatan: HTTP outbound.

<a id="controller-4"></a>

### /android/default/ — DefaultController

Sumber: [protected/modules/android/controllers/DefaultController.php](protected/modules/android/controllers/DefaultController.php).

Akses: **tidak ada filter otorisasi aktif yang dideklarasikan di controller ini**; pemeriksaan inline/module masih berlaku bila ada. Pewarisan Controller/RController saja tidak memasang filter.

#### `/android/default/riwayatKendaraan`

- Pemanggilan: **POST / H-JSON**; enforcement verb hanya bila terlihat di action/filter.
- Input: JSON raw: `noUji`, `tempat_uji`, `no_uji`, `no_kendaraan`, `mati_uji`, `merk`, `tipe`, `no_chasis`, `no_mesin`, `pemilik`, `kondisi`, `success`.
- Respons: JSON; field envelope: `tempat_uji`, `no_uji`, `no_kendaraan`, `mati_uji`, `merk`, `tipe`, `no_chasis`, `no_mesin`, `pemilik`, `kondisi`, `success`; ekspresi output `json_encode($data)`.
- Model yang dipakai: `VRiwayat`.

#### `/android/default/detailPersyaratan`

- Pemanggilan: **POST / H-JSON**; enforcement verb hanya bila terlihat di action/filter.
- Input: JSON raw: `kategori`.
- Respons: JSON; kunci array/record: `keterangan`; ekspresi output `json_encode($data)`.
- Model yang dipakai: `VPersyaratan`.

#### `/android/default/detailKendaraan`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['noUji']`.
- Respons: JSON; field envelope: `id_kendaraan`, `no_uji`, `no_kendaraan`, `merk`, `tipe`, `no_chasis`, `no_mesin`, `pemilik`, `jns_kend`, `mati_uji`, `nama_komersil`, `jenis_karoseri`, `bahan_utama`, `panjang`, `lebar`, `tinggi`, `dimpanjang`, `dimlebar`, `dimtinggi`, `jbb`, `orang`, `barang`, `sb1`, `sb2`, `sb3`, `sb4`, `sb5`, `total_sb`, `jbi`, `mst`, `kondisi`, `success`, `tujuan`, `img_depan`, `img_belakang`, `img_kanan`, `img_kiri`; ekspresi output `json_encode($data)`.
- Model yang dipakai: `VKendaraan`, `TblHasilUji`.

#### `/android/default/hasilUji`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['noUji']`.
- Respons: JSON; field envelope: `no_uji`, `no_kendaraan`, `pemilik`, `hasil_tgl_terakhir_uji`, `hasil_tgl_mati_uji`, `hasil_prauji`, `hasil_emisi`, `hasil_pitlift`, `hasil_lampu`, `hasil_break`, `ltl`, `keterangan`, `kondisi`, `img_depan`, `img_belakang`, `img_kanan`, `img_kiri`; ekspresi output `json_encode($data)`.
- Model yang dipakai: `VStatusProses`, `VDetailTl`.

#### `/android/default/statusRekom`

- Pemanggilan: **POST / H-JSON**; enforcement verb hanya bila terlihat di action/filter.
- Input: JSON raw: `noUji`, `no_uji`, `no_kendaraan`, `tgl_rekom`, `rekom`, `kasubag`, `kaupt`, `kadis`, `tglKasubag`, `tglKaupt`, `tglKadis`, `lokasiRekom`.
- Respons: JSON; field envelope: `no_uji`, `no_kendaraan`, `tgl_rekom`, `rekom`, `kasubag`, `kaupt`, `kadis`, `tglKasubag`, `tglKaupt`, `tglKadis`, `lokasiRekom`; ekspresi output `json_encode($data)`.
- Model yang dipakai: `VRekom`, `TblRekomStatus`.

#### `/android/default/listKendaraan`

- Pemanggilan: **POST / H-JSON**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['index']`; JSON raw: `noUji`.
- Respons: JSON; kunci array/record: `no_kendaraan`, `no_uji`, `nama_pemilik`, `status_kelulusan`; ekspresi output `json_encode($response)`.
- Model yang dipakai: `VVerifikasi`.

#### `/android/default/totalListKendaraan`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: JSON; field envelope: `mobil_barang_tandes`, `mobil_bis_tandes`, `mobil_penumpang_tandes`, `mobil_khusus_tandes`, `mobil_gandengan_tandes`, `mobil_tempelan_tandes`, `jumlah_tandes`, `mobil_datang_tandes`, `mobil_tidak_datang_tandes`, `lulus_uji_tandes`, `tidak_lulus_uji_tandes`; ekspresi output `json_encode($data)`.

#### `/android/default/listRiwayatKendaraan`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['noUji']`.
- Respons: JSON; kunci array/record: `no_uji`, `no_kendaraan`, `tgl_uji`, `tglmati`, `merk`, `tipe`, `no_chasis`, `no_mesin`, `nm_penguji`, `nrp`, `no_seri`, `nm_uji`, `catatan`; ekspresi output `json_encode($data)`.
- Model yang dipakai: `VRiwayat`.

#### `/android/default/saveRetribusi`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['idKendaraan']`, `['tglUji']`.
- Respons: JSON; field envelope: `cekTerdaftar`, `statusResponse`, `va`, `b_retribusi`, `b_rekom`, `b_denda`, `b_kartu_rusak`, `total_bayar`; ekspresi output `json_encode($result)`.
- Model yang dipakai: `VValidasi`, `VKendaraan`.
- Fungsi DB: `insert_retribusi`.
- Catatan: menulis atau memanggil operasi bisnis DB.

<a id="controller-5"></a>

### /capture/default/ — DefaultController

Sumber: [protected/modules/capture/controllers/DefaultController.php](protected/modules/capture/controllers/DefaultController.php).

Akses: **tidak ada filter otorisasi aktif yang dideklarasikan di controller ini**; pemeriksaan inline/module masih berlaku bila ada. Pewarisan Controller/RController saja tidak memasang filter.

#### `/capture/default/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `index`.
- Catatan: HTTP outbound; menghasilkan nilai acak.

#### `/capture/default/saveCaptures`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: JSON; field envelope: `img_belakang`; ekspresi output `json_encode($data)`.
- Catatan: HTTP outbound.

<a id="controller-6"></a>

### /cis/default/ — DefaultController

Sumber: [protected/modules/cis/controllers/DefaultController.php](protected/modules/cis/controllers/DefaultController.php).

Akses: **tidak ada filter otorisasi aktif yang dideklarasikan di controller ini**; pemeriksaan inline/module masih berlaku bila ada. Pewarisan Controller/RController saja tidak memasang filter.

#### `/cis/default/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.

#### `/cis/default/formChangePassword`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `form_change_password`.

#### `/cis/default/changePassword`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['employee_id']`, `['new_password']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/cis/default/login`

- Pemanggilan: **GET+POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['ajax']`, `['LoginForm']`; nested `LoginForm[username]`, `LoginForm[password]`, optional `LoginForm[rememberMe]`.
- Respons: HTML `main_login`; redirect pada cabang terkait.

#### `/cis/default/logout`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: redirect pada cabang terkait.

#### `/cis/default/home`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: redirect pada cabang terkait.
- Catatan: ada pemeriksaan isGuest inline.

<a id="controller-7"></a>

### /cis/emisi/ — EmisiController

Sumber: [protected/modules/cis/controllers/EmisiController.php](protected/modules/cis/controllers/EmisiController.php).

Akses: **Rights** (cookie sesi + permission menurut database).

#### `/cis/emisi/reloadData`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['tahun_kendaraan']`.
- Respons: JSON; field envelope: `solar`, `ems_co`, `ems_hc`; ekspresi output `json_encode($data)`.
- Catatan: menghasilkan nilai acak.

#### `/cis/emisi/listGrid`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => VCisEmisi::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `VCisEmisi`.

#### `/cis/emisi/proses`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_hasil_uji']`, `['variabel']`, `['cis']`, `['username']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Fungsi DB: `update_cis_smoke`.
- Catatan: menulis atau memanggil operasi bisnis DB.

<a id="controller-8"></a>

### /cis/lampu/ — LampuController

Sumber: [protected/modules/cis/controllers/LampuController.php](protected/modules/cis/controllers/LampuController.php).

Akses: **Rights** (cookie sesi + permission menurut database).

#### `/cis/lampu/reloadData`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['tahun_kendaraan']`.
- Respons: JSON; field envelope: `lampu_kanan`, `lampu_kiri`, `dev_kanan`, `dev_kiri`; ekspresi output `json_encode($data)`.
- Catatan: menghasilkan nilai acak.

#### `/cis/lampu/listGrid`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => VCisLampu::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `VCisLampu`.

#### `/cis/lampu/proses`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_hasil_uji']`, `['variabel']`, `['username']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Fungsi DB: `update_cis_lampu`.
- Catatan: menulis atau memanggil operasi bisnis DB.

<a id="controller-9"></a>

### /cis/pengujian/ — PengujianController

Sumber: [protected/modules/cis/controllers/PengujianController.php](protected/modules/cis/controllers/PengujianController.php).

Akses: **Rights** (cookie sesi + permission menurut database).

#### `/cis/pengujian/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `pengujian`.

#### `/cis/pengujian/formChangePassword`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `form_change_password`.

#### `/cis/pengujian/changePassword`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['employee_id']`, `['new_password']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Catatan: menulis atau memanggil operasi bisnis DB.

<a id="controller-10"></a>

### /cis/pitlift/ — PitliftController

Sumber: [protected/modules/cis/controllers/PitliftController.php](protected/modules/cis/controllers/PitliftController.php).

Akses: **Rights** (cookie sesi + permission menurut database).

#### `/cis/pitlift/listGrid`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => VCisPitlift::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `VCisPitlift`.

#### `/cis/pitlift/proses`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_hasil_uji']`, `['variabel']`, `['username']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Fungsi DB: `update_cis_pitlift`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/cis/pitlift/lainListGrid`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['page']`, `['rows']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => TblKelulusan::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `TblKelulusan`.

<a id="controller-11"></a>

### /cis/posisi/ — PosisiController

Sumber: [protected/modules/cis/controllers/PosisiController.php](protected/modules/cis/controllers/PosisiController.php).

Akses: **Rights** (cookie sesi + permission menurut database).

#### `/cis/posisi/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `index`.

#### `/cis/posisi/posisiListGrid`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['textCategory']`, `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => VStatusProsesNow::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `VStatusProsesNow`.

#### `/cis/posisi/prosesBandingPrauji`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_hasil_uji']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `TblHasilUji`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/cis/posisi/prosesBandingEmisi`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_hasil_uji']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `TblHasilUji`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/cis/posisi/prosesBandingPitlift`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_hasil_uji']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `TblHasilUji`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/cis/posisi/prosesBandingLampu`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_hasil_uji']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `TblHasilUji`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/cis/posisi/prosesBandingRem`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_hasil_uji']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `TblHasilUji`.
- Catatan: menulis atau memanggil operasi bisnis DB.

<a id="controller-12"></a>

### /cis/prauji/ — PraujiController

Sumber: [protected/modules/cis/controllers/PraujiController.php](protected/modules/cis/controllers/PraujiController.php).

Akses: **Rights** (cookie sesi + permission menurut database).

#### `/cis/prauji/listGrid`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['textCategory']`, `['textSearch']`, `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => VCisPrauji::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `VCisPrauji`.

#### `/cis/prauji/proses`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['idhasil']`, `['variabel']`, `['posisi']`, `['username']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Fungsi DB: `update_cis_prauji`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/cis/prauji/loadImage`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['idkendaraan']`.
- Respons: JSON; field envelope: `image1`, `image2`; ekspresi output `json_encode($data)`.

#### `/cis/prauji/lainListGrid`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['page']`, `['rows']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => TblKelulusan::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `TblKelulusan`.

#### `/cis/prauji/saveCapture`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_hasil_uji']`.
- Respons: JSON; field envelope: `img_depan`, `img_belakang`, `img_kanan`, `img_kiri`; ekspresi output `json_encode($data)`.
- Catatan: menulis atau memanggil operasi bisnis DB; HTTP outbound.

<a id="controller-13"></a>

### /cis/praujiGandengan/ — PraujiGandenganController

Sumber: [protected/modules/cis/controllers/PraujiGandenganController.php](protected/modules/cis/controllers/PraujiGandenganController.php).

Akses: **Rights** (cookie sesi + permission menurut database).

#### `/cis/praujiGandengan/listGrid`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['textCategory']`, `['textSearch']`, `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => VCisPraujiGandengan::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `VCisPraujiGandengan`.

#### `/cis/praujiGandengan/proses`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['idhasil']`, `['variabel']`, `['posisi']`, `['username']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Fungsi DB: `update_cis_prauji`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/cis/praujiGandengan/loadImage`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['idkendaraan']`.
- Respons: JSON; kunci array/record: `image1`, `image2`; ekspresi output `json_encode($data)`.

#### `/cis/praujiGandengan/lainListGrid`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['page']`, `['rows']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => TblKelulusan::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `TblKelulusan`.

<a id="controller-14"></a>

### /cis/rem/ — RemController

Sumber: [protected/modules/cis/controllers/RemController.php](protected/modules/cis/controllers/RemController.php).

Akses: **Rights** (cookie sesi + permission menurut database).

#### `/cis/rem/reloadData`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['tahun_kendaraan']`, `['jbb']`.
- Respons: JSON; field envelope: `bsb1`, `bsb2`, `bsb3`, `bsb4`, `bsel1`, `bsel2`, `bsel3`, `bsel4`, `kiri1`, `kiri2`, `kiri3`, `kiri4`, `kanan1`, `kanan2`, `kanan3`, `kanan4`, `parkir_kiri_tangan`, `parkir_kanan_tangan`, `parkir_kiri_kaki`, `parkir_kanan_kaki`; ekspresi output `json_encode($data)`.
- Catatan: menghasilkan nilai acak.

#### `/cis/rem/listGrid`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['textCategory']`, `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => VCisBrake::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `VCisBrake`.

#### `/cis/rem/proses`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_hasil_uji']`, `['variabel']`, `['username']`, `['kategori_rem']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Fungsi DB: `update_cis_brake`.
- Catatan: menulis atau memanggil operasi bisnis DB.

<a id="controller-15"></a>

### /cis/remGandengan/ — RemGandenganController

Sumber: [protected/modules/cis/controllers/RemGandenganController.php](protected/modules/cis/controllers/RemGandenganController.php).

Akses: **Rights** (cookie sesi + permission menurut database).

#### `/cis/remGandengan/reloadData`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['tahun_kendaraan']`.
- Respons: JSON; field envelope: `bsb1`, `bsb2`, `bsb3`, `bsb4`, `bsel1`, `bsel2`, `bsel3`, `bsel4`; ekspresi output `json_encode($data)`.
- Catatan: menghasilkan nilai acak.

#### `/cis/remGandengan/listGrid`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['textCategory']`, `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => VCisBrakeGandengan::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `VCisBrakeGandengan`.

#### `/cis/remGandengan/proses`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_hasil_uji']`, `['variabel']`, `['username']`, `['kategori_rem']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Fungsi DB: `update_cis_brake`.
- Catatan: menulis atau memanggil operasi bisnis DB.

<a id="controller-16"></a>

### /display/cisdua/ — CisduaController

Sumber: [protected/modules/display/controllers/CisduaController.php](protected/modules/display/controllers/CisduaController.php).

Akses: **tidak ada filter otorisasi aktif yang dideklarasikan di controller ini**; pemeriksaan inline/module masih berlaku bila ada. Pewarisan Controller/RController saja tidak memasang filter.

#### `/display/cisdua/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `cis_dua`.

#### `/display/cisdua/rem`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `rem`.

#### `/display/cisdua/lampu`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `lampu`.

#### `/display/cisdua/emisi`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `emisi`.

#### `/display/cisdua/pitlift`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `pitlift`.

#### `/display/cisdua/prauji`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `prauji`.

#### `/display/cisdua/all`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `all`.

<a id="controller-17"></a>

### /display/cissatu/ — CissatuController

Sumber: [protected/modules/display/controllers/CissatuController.php](protected/modules/display/controllers/CissatuController.php).

Akses: **tidak ada filter otorisasi aktif yang dideklarasikan di controller ini**; pemeriksaan inline/module masih berlaku bila ada. Pewarisan Controller/RController saja tidak memasang filter.

#### `/display/cissatu/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `cis_satu`.

#### `/display/cissatu/rem`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `rem`.

#### `/display/cissatu/lampu`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `lampu`.

#### `/display/cissatu/emisi`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `emisi`.

#### `/display/cissatu/pitlift`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `pitlift`.

#### `/display/cissatu/prauji`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `prauji`.

#### `/display/cissatu/praujis`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `prauji_1`.

#### `/display/cissatu/all`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `all`.

<a id="controller-18"></a>

### /display/default/ — DefaultController

Sumber: [protected/modules/display/controllers/DefaultController.php](protected/modules/display/controllers/DefaultController.php).

Akses: **tidak ada filter otorisasi aktif yang dideklarasikan di controller ini**; pemeriksaan inline/module masih berlaku bila ada. Pewarisan Controller/RController saja tidak memasang filter.

#### `/display/default/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `cis`.

#### `/display/default/statusProsesListGrid`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => VStatusProses::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `VStatusProses`.

<a id="controller-19"></a>

### /display/hasil/ — HasilController

Sumber: [protected/modules/display/controllers/HasilController.php](protected/modules/display/controllers/HasilController.php).

Akses: **tidak ada filter otorisasi aktif yang dideklarasikan di controller ini**; pemeriksaan inline/module masih berlaku bila ada. Pewarisan Controller/RController saja tidak memasang filter.

#### `/display/hasil/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `hasil`.

#### `/display/hasil/hasilListGrid`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => VStatusProsesNow::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `VStatusProsesNow`.

<a id="controller-20"></a>

### /display/iyasaka/ — IyasakaController

Sumber: [protected/modules/display/controllers/IyasakaController.php](protected/modules/display/controllers/IyasakaController.php).

Akses: **tidak ada filter otorisasi aktif yang dideklarasikan di controller ini**; pemeriksaan inline/module masih berlaku bila ada. Pewarisan Controller/RController saja tidak memasang filter.

#### `/display/iyasaka/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `cis_iyasaka`.

#### `/display/iyasaka/rem`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `rem`.

#### `/display/iyasaka/lampu`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `lampu`.

#### `/display/iyasaka/emisi`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `emisi`.

#### `/display/iyasaka/pitlift`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `pitlift`.

#### `/display/iyasaka/all`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `all`.

<a id="controller-21"></a>

### /display/muller/ — MullerController

Sumber: [protected/modules/display/controllers/MullerController.php](protected/modules/display/controllers/MullerController.php).

Akses: **tidak ada filter otorisasi aktif yang dideklarasikan di controller ini**; pemeriksaan inline/module masih berlaku bila ada. Pewarisan Controller/RController saja tidak memasang filter.

#### `/display/muller/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `cis_muller`.

#### `/display/muller/rem`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `rem`.

#### `/display/muller/lampu`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `lampu`.

#### `/display/muller/emisi`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `emisi`.

#### `/display/muller/pitlift`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `pitlift`.

#### `/display/muller/all`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `all`.

<a id="controller-22"></a>

### /display/prosesuji/ — ProsesujiController

Sumber: [protected/modules/display/controllers/ProsesujiController.php](protected/modules/display/controllers/ProsesujiController.php).

Akses: **tidak ada filter otorisasi aktif yang dideklarasikan di controller ini**; pemeriksaan inline/module masih berlaku bila ada. Pewarisan Controller/RController saja tidak memasang filter.

#### `/display/prosesuji/statusProsesListGrid`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['chooseProses']`, `['page']`, `['rows']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => VStatusProses::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `VStatusProses`.

#### `/display/prosesuji/getKelulusanPrauji`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['cis']`.
- Respons: JSON; field envelope: `no_uji`, `no_kendaraan`, `kelulusan`, `nilai_kelulusan`, `list_kelulusan`; ekspresi output `json_encode($data)`.
- Model yang dipakai: `TblTempPrauji`, `VDetailTl`.

#### `/display/prosesuji/getKelulusanBrake`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['cis']`.
- Respons: JSON; field envelope: `no_uji`, `no_kendaraan`, `kelulusan`, `grem_sb1`, `grem_sb2`, `grem_sb12`, `selrem_sb1`, `selrem_sb2`, `nilai_kelulusan`, `list_kelulusan`; ekspresi output `json_encode($data)`.
- Model yang dipakai: `TblTempBrake`, `VDetailTl`.

#### `/display/prosesuji/getKelulusanHeadlight`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['cis']`.
- Respons: JSON; field envelope: `no_uji`, `no_kendaraan`, `kelulusan`, `dev_kanan`, `dev_kiri`, `lamp_kt_kanan`, `lamp_kt_kiri`, `nilai_kelulusan`, `list_kelulusan`; ekspresi output `json_encode($data)`.
- Model yang dipakai: `TblTempHeadlight`, `VDetailTl`.

#### `/display/prosesuji/getKelulusanEmisi`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['cis']`.
- Respons: JSON; field envelope: `no_uji`, `no_kendaraan`, `kelulusan`, `diesel`, `mesin_co`, `mesin_hc`, `nilai_kelulusan`, `list_kelulusan`; ekspresi output `json_encode($data)`.
- Model yang dipakai: `TblTempSmoke`, `VDetailTl`.

#### `/display/prosesuji/getKelulusanPitlift`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['cis']`.
- Respons: JSON; field envelope: `no_uji`, `no_kendaraan`, `kelulusan`, `nilai_kelulusan`, `list_kelulusan`; ekspresi output `json_encode($data)`.
- Model yang dipakai: `TblTempPitlift`, `VDetailTl`.

#### `/display/prosesuji/getKelulusan`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['cis']`.
- Respons: JSON; field envelope: `no_uji`, `no_kendaraan`, `kelulusan`, `nilai_kelulusan`, `list_kelulusan`; ekspresi output `json_encode($data)`.
- Model yang dipakai: `TblTempKelulusan`, `VDetailTl`.

<a id="controller-23"></a>

### /ikm/default/ — DefaultController

Sumber: [protected/modules/ikm/controllers/DefaultController.php](protected/modules/ikm/controllers/DefaultController.php).

Akses: **tidak ada filter otorisasi aktif yang dideklarasikan di controller ini**; pemeriksaan inline/module masih berlaku bila ada. Pewarisan Controller/RController saja tidak memasang filter.

#### `/ikm/default/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `index`.
- Model yang dipakai: `TblIkmQuestion`.

#### `/ikm/default/pertanyaan`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['arr_jawaban']`, `['id_question']`, `['answer_question']`, `['urutan']`.
- Respons: JSON; field envelope: `arrJawaban`, `pertanyaan`, `tombol`; ekspresi output `CJSON::encode($arrdata)`.
- Model yang dipakai: `TblIkmQuestion`.
- Catatan: menulis atau memanggil operasi bisnis DB.

<a id="controller-24"></a>

### /loket/cekTerdaftar/ — CekTerdaftarController

Sumber: [protected/modules/loket/controllers/CekTerdaftarController.php](protected/modules/loket/controllers/CekTerdaftarController.php).

Akses: **Rights** (cookie sesi + permission menurut database).

#### `/loket/cekTerdaftar/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `cekterdaftar`.

#### `/loket/cekTerdaftar/getListDataTerdaftar`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['textCategory']`, `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => VDaftar::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `VDaftar`.

<a id="controller-25"></a>

### /loket/default/ — DefaultController

Sumber: [protected/modules/loket/controllers/DefaultController.php](protected/modules/loket/controllers/DefaultController.php).

Akses: **tidak ada filter otorisasi aktif yang dideklarasikan di controller ini**; pemeriksaan inline/module masih berlaku bila ada. Pewarisan Controller/RController saja tidak memasang filter.

#### `/loket/default/munculinLagi`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `munculin_kembali`.

#### `/loket/default/prosesMunculinKembali`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['sb']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `TblKendaraan`, `TblRetribusi`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/loket/default/mancing`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `mancing`.

#### `/loket/default/prosesMancing`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['sb']`, `['numerator']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `TblKendaraan`, `TblRetribusi`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/loket/default/riwayatDouble`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `riwayat_double`.

#### `/loket/default/deleteDoubleRiwayat`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_riwayat']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `TblRiwayat`.

#### `/loket/default/getListDataRiwayat`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['selectCategory']`, `['textCategory']`, `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => VRiwayat::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `VRiwayat`.

#### `/loket/default/daftarTlManual`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `form_tl`.

#### `/loket/default/prosesDaftarTlManual`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['sb']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `TblKendaraan`, `TblDaftar`, `TblHasilUji`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/loket/default/cekScan`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `cek_scan`.

#### `/loket/default/getListRetribusi`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['selectCategory']`, `['textCategory']`, `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => VRetribusiAll::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `VRetribusiAll`.

#### `/loket/default/viewCekScan`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_retribusi']`.
- Respons: JSON; ekspresi output `CJSON::encode( array( 'img1' => $data->img_scan, ) )`.
- Model yang dipakai: `TblRetribusi`.

<a id="controller-26"></a>

### /loket/editbuku/ — EditbukuController

Sumber: [protected/modules/loket/controllers/EditbukuController.php](protected/modules/loket/controllers/EditbukuController.php).

Akses: **Rights** (cookie sesi + permission menurut database).

#### `/loket/editbuku/index`

- Pemanggilan: **GET+POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['textCategory']`.
- Respons: HTML `index`.
- Model yang dipakai: `TblKendaraan`, `VKendaraan`.

#### `/loket/editbuku/index2`

- Pemanggilan: **GET+POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['textCategory']`.
- Respons: HTML `index_2`.
- Model yang dipakai: `TblKendaraan`, `VKendaraan`.

#### `/loket/editbuku/proses`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['idKendaraan']`.
- Respons: JSON; field envelope: `count_kendaraan`, `nomer_sertifikasi_registrasi`, `diterbitkan_nomer_sertifikasi_registrasi`, `tgl_nomer_sertifikasi_registrasi`, `nomer_sertifikasi_uji`, `diterbitkan_nomer_sertifikasi_uji`, `tgl_nomer_sertifikasi_uji`, `nomer_sertifikasi_rancang`, `diterbitkan_nomer_sertifikasi_rancang`, `tgl_nomer_sertifikasi_rancang`, `id_kendaraan`, `nomer_uji`, `nomer_kendaraan`, `identitas`, `nomer_identitas`, `nama_pemilik`, `alamat_pemilik`, `tempat_lahir`, `tgl_lahir`, `kelamin`, `rt`, `rw`, `kelurahan`, `kecamatan`, `kota`, `propinsi`, `awal_pemakaian`, `tahun`, `nomer_mesin`, `nomer_chasis`, `jenis_kendaraan`, `status_kendaraan`, `merk`, `tipe`, `pengimport`, `isi_silinder`, `daya_motor`, `bahan_bakar`, `ukuran_panjang`, `ukuran_lebar`, `ukuran_tinggi`, `dimpanjang`, `dimlebar`, `dimtinggi`, `nm_komersil`, `warna_cabin`, `warna_bak`, `bagian_belakang`, `bagian_depan`, `bagian_jterendah`, `karoseri_jenis`, `karoseri_bahan`, `karoseri_duduk`, `karoseri_berdiri`, `guna_khusus`, `konsumbu`, `jsumbu1`, `jsumbu2`, `jsumbu3`, `jsumbu4`, `bsumbu1`, `bsumbu2`, `bsumbu3`, `bsumbu4`, `bsumbu5`, `psumbu1`, `psumbu2`, `psumbu3`, `psumbu4`, `psumbu5`, `dydukpab1`, `dydukpab2`, `dydukpab3`, `dydukpab4`, `dydukpab5`, `kemjbb`, `kemjbkb`, `kemorang`, `kembarang`, `kelas_jalan`, `ukq`, `ukp`, `ukp2`, `mst`; ekspresi output `json_encode($data)`.
- Model yang dipakai: `TblKendaraan`, `VKendaraan`.

#### `/loket/editbuku/prosesSearch`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['textCategory']`.
- Respons: JSON; field envelope: `count_kendaraan`, `nomer_sertifikasi_registrasi`, `diterbitkan_nomer_sertifikasi_registrasi`, `tgl_nomer_sertifikasi_registrasi`, `nomer_sertifikasi_uji`, `diterbitkan_nomer_sertifikasi_uji`, `tgl_nomer_sertifikasi_uji`, `nomer_sertifikasi_rancang`, `diterbitkan_nomer_sertifikasi_rancang`, `tgl_nomer_sertifikasi_rancang`, `id_kendaraan`, `nomer_uji`, `nomer_kendaraan`, `identitas`, `nomer_identitas`, `nama_pemilik`, `alamat_pemilik`, `tempat_lahir`, `tgl_lahir`, `kelamin`, `rt`, `rw`, `propinsi`, `kota`, `kecamatan`, `kelurahan`, `awal_pemakaian`, `tahun`, `no_telepon`, `nomer_mesin`, `nomer_chasis`, `jenis_kendaraan`, `status_kendaraan`, `merk`, `tipe`, `pengimport`, `isi_silinder`, `daya_motor`, `bahan_bakar`, `ukuran_panjang`, `ukuran_lebar`, `ukuran_tinggi`, `dimpanjang`, `dimlebar`, `dimtinggi`, `nm_komersil`, `nm_komersil_lama`, `warna_cabin`, `warna_bak`, `bagian_belakang`, `bagian_depan`, `bagian_jterendah`, `karoseri_jenis`, `karoseri_jenis_lama`, `karoseri_bahan`, `karoseri_duduk`, `karoseri_berdiri`, `guna_khusus`, `konsumbu`, `jsumbu1`, `jsumbu2`, `jsumbu3`, `jsumbu4`, `bsumbu1`, `bsumbu2`, `bsumbu3`, `bsumbu4`, `bsumbu5`, `psumbu1`, `psumbu2`, `psumbu3`, `psumbu4`, `psumbu5`, `dydukpab1`, `dydukpab2`, `dydukpab3`, `dydukpab4`, `dydukpab5`, `kemjbb`, `kemjbkb`, `kemorang`, `kembarang`, `kelas_jalan`, `ukq`, `ukp`, `ukp2`, `mst`, `kelasjalan_id`, `fuel_id`, `vehicle_brand_id`, `vehicle_varian_id`, `vehicle_varian_type_id`, `vehicle_type_id`, `vehicle_type_sub_id`; ekspresi output `json_encode($data)`.
- Model yang dipakai: `TblKendaraan`, `VKendaraan`.

#### `/loket/editbuku/riwayatListGrid`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['select_category_acuan']`, `['text_category_acuan']`, `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => VRiwayat::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `VRiwayat`.

#### `/loket/editbuku/cekRiwayat`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['text_category']`, `['select_category_acuan']`.
- Respons: JSON; field envelope: `hasil`, `id_kendaraan`; ekspresi output `json_encode($data)`.
- Model yang dipakai: `VRiwayat`, `VKendaraan`.

#### `/loket/editbuku/saveForm`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['nomer_sertifikasi_registrasi']`, `['diterbitkan_nomer_sertifikasi_registrasi']`, `['tgl_nomer_sertifikasi_registrasi']`, `['nomer_sertifikasi_uji']`, `['diterbitkan_nomer_sertifikasi_uji']`, `['tgl_nomer_sertifikasi_uji']`, `['nomer_sertifikasi_rancang']`, `['diterbitkan_nomer_sertifikasi_rancang']`, `['tgl_nomer_sertifikasi_rancang']`, `['id_kendaraan']`, `['nomer_uji']`, `['nomer_kendaraan']`, `['identitas']`, `['nomer_identitas']`, `['nama_pemilik']`, `['rt']`, `['rw']`, `['alamat_pemilik']`, `['propinsi_select']`, `['kota_select']`, `['kecamatan_select']`, `['kelurahan_select']`, `['tempat_lahir']`, `['tgl_lahir']`, `['kelamin']`, `['awal_pemakaian']`, `['tahun']`, `['nomer_mesin']`, `['nomer_chasis']`, `['status_kendaraan']`, `['merk']`, `['tipe_nonfull']`, `['tipe']`, `['pengimport']`, `['isi_silinder']`, `['daya_motor']`, `['bahan_bakar']`, `['panjang_utama']`, `['lebar_utama']`, `['tinggi_utama']`, `['panjang_muatan']`, `['lebar_muatan']`, `['tinggi_muatan']`, `['nama_komersil']`, `['warna_cabin']`, `['warna_bak']`, `['kebelakang']`, `['kedepan']`, `['jarak_terendah']`, `['karoseri_jenis']`, `['karoseri_bahan']`, `['tempat_duduk']`, `['berdiri']`, `['peng_khusus']`, `['konfigurasi_sumbu']`, `['jarak_sumbu_1']`, `['jarak_sumbu_2']`, `['jarak_sumbu_3']`, `['jarak_sumbu_4']`, `['berat_kendaraan_sumbu_1']`, `['berat_kendaraan_sumbu_2']`, `['berat_kendaraan_sumbu_3']`, `['berat_kendaraan_sumbu_4']`, `['berat_kendaraan_sumbu_5']`, `['pemakaian_sumbu_ban_1']`, `['pemakaian_sumbu_ban_2']`, `['pemakaian_sumbu_ban_3']`, `['pemakaian_sumbu_ban_4']`, `['daya_dukung_pabrik_1']`, `['daya_dukung_pabrik_2']`, `['daya_dukung_pabrik_3']`, `['daya_dukung_pabrik_4']`, `['daya_dukung_pabrik_5']`, `['jbb']`, `['jbkb']`, `['daya_angkut_orang']`, `['daya_angkut_barang']`, `['kelas_jalan']`, `['q_r']`, `['p1']`, `['p2']`, `['mst']`, `['no_telepon']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `MProvinsi`, `MKota`, `MKecamatan`, `MKelurahan`, `MasterMerk`, `MasterMerkTipe`, `MasterMerkTipeSub`, `MasterBahanBakar`, `MasterVehicleTypeSub`, `MasterVehicleType`, `MasterKelasJalan`.
- Fungsi DB: `in_up_data_kendaraan`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/loket/editbuku/saveBaruForm`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['nomer_sertifikasi_registrasi_baru']`, `['diterbitkan_nomer_sertifikasi_registrasi_baru']`, `['tgl_nomer_sertifikasi_registrasi_baru']`, `['nomer_sertifikasi_uji_baru']`, `['diterbitkan_nomer_sertifikasi_uji_baru']`, `['tgl_nomer_sertifikasi_uji_baru']`, `['nomer_sertifikasi_rancang_baru']`, `['diterbitkan_nomer_sertifikasi_rancang_baru']`, `['tgl_nomer_sertifikasi_rancang_baru']`, `['nomer_uji_baru']`, `['nomer_kendaraan_baru']`, `['identitas_baru']`, `['nomer_identitas_baru']`, `['nama_pemilik_baru']`, `['rt_baru']`, `['rw_baru']`, `['propinsi_select_baru']`, `['kota_select_baru']`, `['kecamatan_select_baru']`, `['kelurahan_select_baru']`, `['alamat_pemilik_baru']`, `['tempat_lahir_baru']`, `['tgl_lahir_baru']`, `['kelamin_baru']`, `['awal_pemakaian_baru']`, `['tahun_baru']`, `['nomer_mesin_baru']`, `['nomer_chasis_baru']`, `['jenis_kendaraan_baru']`, `['status_kendaraan_baru']`, `['merk_baru']`, `['tipe_baru']`, `['pengimport_baru']`, `['isi_silinder_baru']`, `['daya_motor_baru']`, `['bahan_bakar_baru']`, `['panjang_utama_baru']`, `['lebar_utama_baru']`, `['tinggi_utama_baru']`, `['panjang_muatan_baru']`, `['lebar_muatan_baru']`, `['tinggi_muatan_baru']`, `['nama_komersil_baru']`, `['warna_cabin_baru']`, `['warna_bak_baru']`, `['kebelakang_baru']`, `['kedepan_baru']`, `['jarak_terendah_baru']`, `['karoseri_jenis_baru']`, `['karoseri_bahan_baru']`, `['tempat_duduk_baru']`, `['berdiri_baru']`, `['peng_khusus_baru']`, `['konfigurasi_sumbu_baru']`, `['jarak_sumbu_1_baru']`, `['jarak_sumbu_2_baru']`, `['jarak_sumbu_3_baru']`, `['jarak_sumbu_4_baru']`, `['berat_kendaraan_sumbu_1_baru']`, `['berat_kendaraan_sumbu_2_baru']`, `['berat_kendaraan_sumbu_3_baru']`, `['berat_kendaraan_sumbu_4_baru']`, `['berat_kendaraan_sumbu_5_baru']`, `['pemakaian_sumbu_ban_1_baru']`, `['pemakaian_sumbu_ban_2_baru']`, `['pemakaian_sumbu_ban_3_baru']`, `['pemakaian_sumbu_ban_4_baru']`, `['daya_dukung_pabrik_1_baru']`, `['daya_dukung_pabrik_2_baru']`, `['daya_dukung_pabrik_3_baru']`, `['daya_dukung_pabrik_4_baru']`, `['daya_dukung_pabrik_5_baru']`, `['jbb_baru']`, `['jbkb_baru']`, `['daya_angkut_orang_baru']`, `['daya_angkut_barang_baru']`, `['kelas_jalan_baru']`, `['q_r_baru']`, `['p1_baru']`, `['p2_baru']`, `['mst_baru']`, `['no_telepon_baru']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `TblKendaraan`, `MProvinsi`, `MKota`, `MKecamatan`, `MKelurahan`, `TblJnsKend`.
- Fungsi DB: `in_up_data_kendaraan`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/loket/editbuku/detailBukuUji`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id']`.
- Respons: JSON; field envelope: `no_uji`, `no_kendaraan`, `nama_pemilik`, `alamat`, `identitas`, `merk`, `tipe`, `nm_komersil`, `isi_silinder`, `daya_motor`, `bahan_bakar`, `tahun`, `no_chasis`, `no_mesin`, `no_regis`, `tgl_sertifikasi`, `petugas`, `ukuran_panjang`, `ukuran_lebar`, `ukuran_tinggi`, `bagian_belakang`, `bagian_depan`, `jsumbu1`, `ukq`, `dimpanjang`, `dimlebar`, `dimtinggi`, `karoseri_bahan`, `psumbu1`, `psumbu2`, `kemjbb`, `bsumbu1`, `bsumbu2`, `jumlah_sumbu`, `karoseri_duduk`, `kembarang`, `jbi`, `mst`; ekspresi output `json_encode($result)`.
- Model yang dipakai: `VKendaraan`.

#### `/loket/editbuku/selectProvinsi`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['provinsi']`.
- Respons: Teks/HTML output langsung; lihat cabang pada sumber.
- Model yang dipakai: `MKota`.

#### `/loket/editbuku/selectKota`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['kota']`.
- Respons: Teks/HTML output langsung; lihat cabang pada sumber.
- Model yang dipakai: `MKecamatan`.

#### `/loket/editbuku/selectKecamatan`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['kecamatan']`.
- Respons: Teks/HTML output langsung; lihat cabang pada sumber.
- Model yang dipakai: `MKelurahan`.

#### `/loket/editbuku/selectVehicleType`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id']`.
- Respons: Teks/HTML output langsung; lihat cabang pada sumber.
- Model yang dipakai: `MasterVehicleTypeSub`.

#### `/loket/editbuku/selectBrandType`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id']`.
- Respons: Teks/HTML output langsung; lihat cabang pada sumber.
- Model yang dipakai: `MasterMerkTipe`.

<a id="controller-27"></a>

### /loket/kedatangan/ — KedatanganController

Sumber: [protected/modules/loket/controllers/KedatanganController.php](protected/modules/loket/controllers/KedatanganController.php).

Akses: **Rights** (cookie sesi + permission menurut database).

#### `/loket/kedatangan/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `kedatangan`.

#### `/loket/kedatangan/prosesDatang`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `TblHasilUji`.
- Fungsi DB: `insert_hasil`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/loket/kedatangan/getListDataDatang`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['textCategory']`, `['selectDate']`, `['chooseProses']`, `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => VDatang::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `VDatang`.

#### `/loket/kedatangan/reportKedatanganExcel`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: GET: `['tgl']`.
- Respons: Ekspor Excel/PHPExcel (binary/attachment).
- Model yang dipakai: `VDatang`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/loket/kedatangan/prosesDatangChecked`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['idArray']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Fungsi DB: `insert_hasil`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/loket/kedatangan/prosesBelumDatang`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `TblDaftar`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/loket/kedatangan/prosesBelumDatangChecked`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['idChecked']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `TblDaftar`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/loket/kedatangan/kedatangan`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `kedatangan`.

#### `/loket/kedatangan/getListKedatangan`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['selectCategory']`, `['textCategory']`, `['chooseProses']`, `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => VDatang::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `VDatang`.

#### `/loket/kedatangan/prosesKedatangan`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Fungsi DB: `insert_hasil`.
- Catatan: menulis atau memanggil operasi bisnis DB.

<a id="controller-28"></a>

### /loket/rollingalat/ — RollingalatController

Sumber: [protected/modules/loket/controllers/RollingalatController.php](protected/modules/loket/controllers/RollingalatController.php).

Akses: **Rights** (cookie sesi + permission menurut database).

#### `/loket/rollingalat/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `index`.

#### `/loket/rollingalat/displayPengujiCis1`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `cis1`.

#### `/loket/rollingalat/displayPengujiCis2`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `cis2`.

#### `/loket/rollingalat/prosesRolling`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['pitlift1']`, `['pitlift2']`, `['brake1']`, `['brake2']`, `['headlight1']`, `['headlight2']`, `['prauji1']`, `['prauji2']`, `['emisi1']`, `['emisi2']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/loket/rollingalat/prosesSwitch`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['cis']`, `['checkbox2']`, `['checkbox1']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/loket/rollingalat/prosesSwitchAll`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Catatan: menulis atau memanggil operasi bisnis DB.

<a id="controller-29"></a>

### /loket/terdaftar/ — TerdaftarController

Sumber: [protected/modules/loket/controllers/TerdaftarController.php](protected/modules/loket/controllers/TerdaftarController.php).

Akses: **Rights** (cookie sesi + permission menurut database).

#### `/loket/terdaftar/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `terdaftar`.

#### `/loket/terdaftar/prosesGantiTglUji`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['dlg_id_retribusi']`, `['ganti_tgl_uji']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Fungsi DB: `edit_retribusi`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/loket/terdaftar/getListDataTerdaftar`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['textCategory']`, `['selectDate']`, `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => VDaftar::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `VDaftar`.

#### `/loket/terdaftar/reportTerdaftarExcel`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: GET: `['tgl']`.
- Respons: Ekspor Excel/PHPExcel (binary/attachment).
- Model yang dipakai: `VDaftar`.
- Catatan: menulis atau memanggil operasi bisnis DB.

<a id="controller-30"></a>

### /loket/ujiulang/ — UjiulangController

Sumber: [protected/modules/loket/controllers/UjiulangController.php](protected/modules/loket/controllers/UjiulangController.php).

Akses: **Rights** (cookie sesi + permission menurut database).

#### `/loket/ujiulang/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `uji_ulang`.

#### `/loket/ujiulang/cekProsesUjiUlang`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_hasil_uji']`.
- Respons: JSON; field envelope: `terdaftar`, `message`; ekspresi output `json_encode($result)`.
- Model yang dipakai: `TblHasilUji`, `VDatang`.

#### `/loket/ujiulang/prosesUjiUlang`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_hasil_uji']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `TblHasilUji`, `TblDaftar`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/loket/ujiulang/getListDataUjiUlang`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['textCategory']`, `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => count($result), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `VNotLulus`.

<a id="controller-31"></a>

### /loket/upload/ — UploadController

Sumber: [protected/modules/loket/controllers/UploadController.php](protected/modules/loket/controllers/UploadController.php).

Akses: **Rights** (cookie sesi + permission menurut database).

#### `/loket/upload/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `upload`.

#### `/loket/upload/readUpload`

- Pemanggilan: **POST / H-UPLOAD**; enforcement verb hanya bila terlihat di action/filter. FileUploader pada action pendaftaran/prauji membaca field `files` (dapat berupa `files[]` dari widget).
- Input: FILES: `['myfile']['tmp_name']`.
- Respons: Pemrosesan Excel di server; bukan bukti response file download.

#### `/loket/upload/readUpload2`

- Pemanggilan: **POST / H-UPLOAD**; enforcement verb hanya bila terlihat di action/filter. FileUploader pada action pendaftaran/prauji membaca field `files` (dapat berupa `files[]` dari widget).
- Input: FILES: `['myfile']['tmp_name']`.
- Respons: Pemrosesan Excel di server; bukan bukti response file download.

<a id="controller-32"></a>

### /master/default/ — DefaultController

Sumber: [protected/modules/master/controllers/DefaultController.php](protected/modules/master/controllers/DefaultController.php).

Akses: **Rights** (cookie sesi + permission menurut database).

#### `/master/default/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `index`.

#### `/master/default/pengujiListGrid`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['penguji']`, `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => TblNamaPenguji::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `TblNamaPenguji`.

#### `/master/default/getDetailEditPenguji`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id']`.
- Respons: JSON; field envelope: `id`, `nama`, `nrp`, `status_penguji`; ekspresi output `json_encode($data)`.
- Model yang dipakai: `TblNamaPenguji`.

#### `/master/default/savePenguji`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_penguji']`, `['nrp']`, `['penguji']`, `['ttd']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `TblNamaPenguji`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/master/default/deletePenguji`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/master/default/userListGrid`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['user']`, `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => TblUser::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `TblUser`.

#### `/master/default/getDetailEditUser`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id']`.
- Respons: JSON; field envelope: `id`, `username`, `password`, `itemname`; ekspresi output `json_encode($data)`.
- Model yang dipakai: `TblUser`.

#### `/master/default/deleteUser`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id']`, `['itemname']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `Authassignment`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/master/default/saveUser`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['username']`, `['password']`, `['hak_akses']`, `['kondisi']`, `['id_user']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `TblUser`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/master/default/komersilListGrid`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['komersil']`, `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => TblNmKomersil::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `TblNmKomersil`.

#### `/master/default/getDetailEditKomersil`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id']`.
- Respons: JSON; field envelope: `id`, `nama`; ekspresi output `json_encode($data)`.
- Model yang dipakai: `TblNmKomersil`.

#### `/master/default/saveKomersil`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_komersil']`, `['komersil']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `TblNmKomersil`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/master/default/karoseriListGrid`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['karoseri']`, `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => TblKarJenis::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `TblKarJenis`.

#### `/master/default/getDetailEditKaroseri`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id']`.
- Respons: JSON; field envelope: `id`, `nama`; ekspresi output `json_encode($data)`.
- Model yang dipakai: `TblKarJenis`.

#### `/master/default/saveKaroseri`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_karoseri']`, `['karoseri']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `TblKarJenis`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/master/default/bahanListGrid`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['bahan']`, `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => TblKarBahan::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `TblKarBahan`.

#### `/master/default/getDetailEditBahan`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id']`.
- Respons: JSON; field envelope: `id`, `nama`; ekspresi output `json_encode($data)`.
- Model yang dipakai: `TblKarBahan`.

#### `/master/default/saveBahan`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_bahan']`, `['bahan']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `TblKarBahan`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/master/default/merkListGrid`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['merk']`, `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `json_encode( array( 'total' => TblMerk::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `TblMerk`.

#### `/master/default/getDetailEditMerk`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id']`.
- Respons: JSON; field envelope: `id`, `nama`; ekspresi output `json_encode($data)`.
- Model yang dipakai: `TblMerk`.

#### `/master/default/saveMerk`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_merk']`, `['merk']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `TblMerk`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/master/default/tipeListGrid`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['tipe']`, `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `json_encode( array( 'total' => MasterMerkTipe::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `MasterMerkTipe`, `MasterMerk`.

#### `/master/default/getDetailEditTipe`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id']`.
- Respons: JSON; field envelope: `id`, `nama`; ekspresi output `json_encode($data)`.
- Model yang dipakai: `MasterMerkTipe`.

#### `/master/default/saveTipe`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_tipe']`, `['tipe']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `MasterMerkTipe`.
- Catatan: menulis atau memanggil operasi bisnis DB.

<a id="controller-33"></a>

### /master/riwayat/ — RiwayatController

Sumber: [protected/modules/master/controllers/RiwayatController.php](protected/modules/master/controllers/RiwayatController.php).

Akses: **Rights** (cookie sesi + permission menurut database).

#### `/master/riwayat/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `index`.

#### `/master/riwayat/deleteDoubleRiwayat`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_riwayat']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `TblRiwayat`.

#### `/master/riwayat/prosesDeleteKendaraan`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_kendaraan']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/master/riwayat/getListDataRiwayat`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['textCategory']`, `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => VRiwayat::model()->count($criteria), 'rows' => $dataJson, 'id_kendaraan' => $idKendaraan, ) )`.
- Model yang dipakai: `TblKendaraan`, `VRiwayat`.

#### `/master/riwayat/detailRiwayat`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id']`.
- Respons: JSON; field envelope: `tempat`, `catatan`, `nrp`, `tgl_uji`, `id_hasil_uji`; ekspresi output `json_encode($data)`.
- Model yang dipakai: `TblRiwayat`.

#### `/master/riwayat/prosesUpdateRiwayat`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_riwayat']`, `['id_hasil_uji']`, `['tempat']`, `['nrp']`, `['catatan']`, `['tgl_uji']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `Penguji`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/master/riwayat/prosesInsertRiwayat`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['tempat']`, `['nrp']`, `['catatan']`, `['id_kendaraan']`, `['tgl_uji']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `Penguji`.
- Catatan: menulis atau memanggil operasi bisnis DB.

<a id="controller-34"></a>

### /pengujian/bap/ — BapController

Sumber: [protected/modules/pengujian/controllers/BapController.php](protected/modules/pengujian/controllers/BapController.php).

Akses: **Rights** (cookie sesi + permission menurut database).

#### `/pengujian/bap/reportRiwayatPemeriksaan`

- Pemanggilan: **GET/POST / H-GET atau H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: REQUEST: `['id_hasil_uji']`.
- Respons: Ekspor Excel/PHPExcel (binary/attachment).
- Model yang dipakai: `VRiwayatHasil`.
- Catatan: menulis atau memanggil operasi bisnis DB.

<a id="controller-35"></a>

### /pengujian/bapTl/ — BapTlController

Sumber: [protected/modules/pengujian/controllers/BapTlController.php](protected/modules/pengujian/controllers/BapTlController.php).

Akses: **Rights** (cookie sesi + permission menurut database).

#### `/pengujian/bapTl/reportRiwayatPemeriksaan`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: GET: `['id_hasil_uji']`.
- Respons: Ekspor Excel/PHPExcel (binary/attachment).
- Model yang dipakai: `VVerifikasi`, `VPrintHasil`, `VDetailTl`.
- Catatan: menulis atau memanggil operasi bisnis DB.

<a id="controller-36"></a>

### /pengujian/blokir/ — BlokirController

Sumber: [protected/modules/pengujian/controllers/BlokirController.php](protected/modules/pengujian/controllers/BlokirController.php).

Akses: **Rights** (cookie sesi + permission menurut database).

#### `/pengujian/blokir/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `blokir`.

#### `/pengujian/blokir/prosesUnBlokir`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_kendaraan']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `TblKendaraan`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/pengujian/blokir/getListDataBlokir`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['textCategory']`, `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => TblKendaraan::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `TblKendaraan`.

<a id="controller-37"></a>

### /pengujian/default/ — DefaultController

Sumber: [protected/modules/pengujian/controllers/DefaultController.php](protected/modules/pengujian/controllers/DefaultController.php).

Akses: **tidak ada filter otorisasi aktif yang dideklarasikan di controller ini**; pemeriksaan inline/module masih berlaku bila ada. Pewarisan Controller/RController saja tidak memasang filter.

#### `/pengujian/default/statusProses`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `status_proses`.

#### `/pengujian/default/statusProsesListGrid`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['chooseProses']`, `['selectCategory']`, `['textCategory']`, `['page']`, `['rows']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => VStatusProses::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `VStatusProses`.

#### `/pengujian/default/prosesBandingPrauji`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_hasil_uji']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `TblHasilUji`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/pengujian/default/prosesBandingPengukuran`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_hasil_uji']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `TblHasilUji`.
- Catatan: menulis atau memanggil operasi bisnis DB.

<a id="controller-38"></a>

### /pengujian/printhasil/ — PrinthasilController

Sumber: [protected/modules/pengujian/controllers/PrinthasilController.php](protected/modules/pengujian/controllers/PrinthasilController.php).

Akses: **Rights** (cookie sesi + permission menurut database).

#### `/pengujian/printhasil/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `index`.

#### `/pengujian/printhasil/printHasilListGrid`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['tanggal']`, `['chooseKelulusan']`, `['chooseCetak']`, `['textCategory']`, `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => VStatusProses::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `VStatusProses`, `VDetailTl`.

#### `/pengujian/printhasil/cetakTidakLulus`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$id, $nosurat, $nrp`.
- Respons: HTML `cetak_tl`.
- Model yang dipakai: `MasterEmployee`, `TblHasilUji`.
- Fungsi DB: `get_no_tl`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/pengujian/printhasil/saveCetakLulusSementara`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id']`, `['no_surat']`, `['penguji']`, `['catatan']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `Penguji`, `TblHasilUji`, `TblRiwayat`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/pengujian/printhasil/cetakLulusSementara`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$id, $nosurat, $nrp`.
- Respons: HTML `cetak_l_sementara`.

#### `/pengujian/printhasil/saveCetakLulus`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id']`, `['penguji']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `VPrintHasil`, `TblProses`, `MasterEmployee`, `TblHasilUji`, `TblRiwayat`, `TblRetribusi`, `MasterArea`, `MasterMerkTipeSub`, `Datapengujian`.
- Catatan: menulis atau memanggil operasi bisnis DB; menghasilkan nilai acak.

#### `/pengujian/printhasil/saveCetakLulusNonFull`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id']`, `['penguji']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `VPrintHasil`, `TblProses`, `Penguji`, `TblHasilUji`, `TblRiwayat`, `TblRetribusi`, `Direktur`, `Kepaladinas`, `MasterArea`, `MasterMerkTipeSub`, `Datapengujian`.
- Catatan: menulis atau memanggil operasi bisnis DB; menghasilkan nilai acak.

#### `/pengujian/printhasil/cetakLulus`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$id, $posisi, $nrp`.
- Respons: HTML `cetak_l`.

#### `/pengujian/printhasil/cetaktldimensi`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$id, $nrp`.
- Respons: HTML `cetak_tl_dimensi`.
- Model yang dipakai: `TblHasilUji`.
- Fungsi DB: `get_no_tl`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/pengujian/printhasil/prosesBandingPrauji`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_hasil_uji']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `TblHasilUji`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/pengujian/printhasil/prosesBandingPengukuran`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_hasil_uji']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `TblHasilUji`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/pengujian/printhasil/viewImage`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['idHasilUji']`.
- Respons: JSON; ekspresi output `json_encode( array( 'img_depan' => $data->img_depan, 'img_belakang' => $data->img_belakang, 'img_kanan' => $data->img_kanan, 'img_kiri' => $data->img_kiri, ) )`.
- Model yang dipakai: `TblHasilUji`.

<a id="controller-39"></a>

### /pengujian/riwayat/ — RiwayatController

Sumber: [protected/modules/pengujian/controllers/RiwayatController.php](protected/modules/pengujian/controllers/RiwayatController.php).

Akses: **Rights** (cookie sesi + permission menurut database).

#### `/pengujian/riwayat/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `riwayat`.

#### `/pengujian/riwayat/riwayatListGrid`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['selectCategory']`, `['textCategory']`, `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => VRiwayat::model()->count($criteria), 'rows' => $dataJsonBerkala, 'id_kendaraan' => $id_kendaraan, ) )`.
- Model yang dipakai: `VRiwayat`, `TblKendaraan`, `TblRekom`, `VValidasi`, `MasterArea`.

#### `/pengujian/riwayat/riwayatTlListGrid`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['selectCategory']`, `['textCategory']`, `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => VVerifikasi::model()->count($criteria), 'rows' => $dataJson ) )`.
- Model yang dipakai: `VVerifikasi`, `VDetailTl`.

#### `/pengujian/riwayat/viewImage`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['idHasilUji']`.
- Respons: JSON; ekspresi output `CJSON::encode( array( 'img_depan' => $data->img_depan, 'img_belakang' => $data->img_belakang, 'img_kanan' => $data->img_kanan, 'img_kiri' => $data->img_kiri, ) )`.
- Model yang dipakai: `TblHasilUji`.

#### `/pengujian/riwayat/detailKendaraan`

- Pemanggilan: **GET+POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_kendaraan']`.
- Respons: HTML `detail_kendaraan`.
- Model yang dipakai: `VKendaraan`.

#### `/pengujian/riwayat/detailPengujian`

- Pemanggilan: **GET+POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_hasil_uji']`.
- Respons: HTML `detail_pengujian`.
- Model yang dipakai: `VRiwayatHasil`.

#### `/pengujian/riwayat/cetakKartuKuning`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$id_kendaraan`.
- Respons: HTML `cetak_kartu_kuning`.

#### `/pengujian/riwayat/saveEditNumkeMutke`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_rekom']`, `['tgl_uji']`, `['nama_penguji']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Catatan: menulis atau memanggil operasi bisnis DB.

<a id="controller-40"></a>

### /pengujian/riwayatOri/ — RiwayatOriController

Sumber: [protected/modules/pengujian/controllers/RiwayatOriController.php](protected/modules/pengujian/controllers/RiwayatOriController.php).

Akses: **Rights** (cookie sesi + permission menurut database).

#### `/pengujian/riwayatOri/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `riwayat`.

#### `/pengujian/riwayatOri/riwayatListGrid`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['selectCategory']`, `['textCategory']`, `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => VRiwayat::model()->count($criteria), 'rows' => $dataJson, 'id_kendaraan' => $id_kendaraan, ) )`.
- Model yang dipakai: `VRiwayat`.

#### `/pengujian/riwayatOri/viewImage`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['idHasilUji']`.
- Respons: JSON; field envelope: `img_depan`, `img_kanan`, `img_kiri`, `img_belakang`; ekspresi output `json_encode($data)`.
- Model yang dipakai: `TblHasilUji`.

#### `/pengujian/riwayatOri/detailKendaraan`

- Pemanggilan: **GET+POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_kendaraan']`.
- Respons: HTML `detail_kendaraan`.
- Model yang dipakai: `VKendaraan`.

#### `/pengujian/riwayatOri/detailPengujian`

- Pemanggilan: **GET+POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_hasil_uji']`.
- Respons: HTML `detail_pengujian`.
- Model yang dipakai: `VRiwayatHasil`.

#### `/pengujian/riwayatOri/reportRiwayatPemeriksaan`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: GET: `['id_hasil_uji']`.
- Respons: Ekspor Excel/PHPExcel (binary/attachment).
- Model yang dipakai: `VRiwayatHasil`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/pengujian/riwayatOri/cetakKartuKuning`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$id_kendaraan`.
- Respons: HTML `cetak_kartu_kuning`.

<a id="controller-41"></a>

### /pengujian/verifikasi/ — VerifikasiController

Sumber: [protected/modules/pengujian/controllers/VerifikasiController.php](protected/modules/pengujian/controllers/VerifikasiController.php).

Akses: **Rights** (cookie sesi + permission menurut database).

#### `/pengujian/verifikasi/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `verifikasi`.

#### `/pengujian/verifikasi/verifikasiListGrid`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['tanggal']`, `['chooseProses']`, `['selectCategory']`, `['textCategory']`, `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => VVerifikasi::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `VVerifikasi`, `TblKendaraan`.

#### `/pengujian/verifikasi/prosesBandingPrauji`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_hasil_uji']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `TblHasilUji`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/pengujian/verifikasi/prosesBandingEmisi`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_hasil_uji']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `TblHasilUji`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/pengujian/verifikasi/prosesBandingPitlift`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_hasil_uji']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `TblHasilUji`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/pengujian/verifikasi/prosesBandingLampu`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_hasil_uji']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `TblHasilUji`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/pengujian/verifikasi/prosesBandingRem`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_hasil_uji']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `TblHasilUji`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/pengujian/verifikasi/cetakl`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$id, $posisi, $nrp`.
- Respons: HTML `cetak_l`.
- Model yang dipakai: `Penguji`, `TblHasilUji`, `TblDaftar`, `TblRiwayat`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/pengujian/verifikasi/saveCetakLulusSementara`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id']`, `['no_surat']`, `['penguji']`, `['catatan']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `Penguji`, `TblHasilUji`, `TblRiwayat`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/pengujian/verifikasi/cetakLulusSementara`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$id, $nosurat, $nrp`.
- Respons: HTML `cetak_l_sementara`.

<a id="controller-42"></a>

### /pengukuran/pengukuran/ — PengukuranController

Sumber: [protected/modules/pengukuran/controllers/PengukuranController.php](protected/modules/pengukuran/controllers/PengukuranController.php).

Akses: **Rights** (cookie sesi + permission menurut database).

#### `/pengukuran/pengukuran/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `index`.

#### `/pengukuran/pengukuran/listGrid`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['selectCategory']`, `['textCategory']`, `['tgl_search']`, `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => VUjiMekanisAll::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `VUjiMekanisAll`.

#### `/pengukuran/pengukuran/detailPengukuran`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_hasil_uji']`.
- Respons: JSON; field envelope: `id_daftar`, `id_hasil_uji`, `id_kendaraan`, `id_jns_kend`, `konsumbu`, `tahun`, `bahan_bakar`, `bsumbu1`, `bsumbu2`, `bsumbu3`, `bsumbu4`, `ems_diesel`, `ems_mesin_co`, `ems_mesin_hc`, `ktlamp_kanan`, `ktlamp_kiri`, `dev_kanan`, `dev_kiri`, `selgaya1`, `selgaya2`, `selgaya3`, `selgaya4`, `selrem_sb1`, `selrem_sb2`, `selrem_sb3`, `selrem_sb4`; ekspresi output `json_encode($data)`.
- Model yang dipakai: `VUjiMekanisAll`.

#### `/pengukuran/pengukuran/saveForm`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_hasil_uji']`, `['id_kendaraan']`, `['id_jns_kend']`, `['id_daftar']`, `['b_sumbu_1']`, `['b_sumbu_2']`, `['b_sumbu_3']`, `['b_sumbu_4']`, `['diesel']`, `['mesin_co']`, `['mesin_hc']`, `['kuat_kanan']`, `['kuat_kiri']`, `['deviasi_kanan']`, `['deviasi_kiri']`, `['sg_sb_1']`, `['sg_sb_2']`, `['sg_sb_3']`, `['sg_sb_4']`, `['sumbu_1']`, `['sumbu_2']`, `['sumbu_3']`, `['sumbu_4']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Fungsi DB: `update_cis_smoke`, `update_cis_lampu`, `update_cis_brake`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/pengukuran/pengukuran/saveFormEmisi`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_hasil_uji']`, `['id_kendaraan']`, `['id_jns_kend']`, `['id_daftar']`, `['diesel']`, `['mesin_co']`, `['mesin_hc']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Fungsi DB: `update_cis_smoke`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/pengukuran/pengukuran/saveFormLampu`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_hasil_uji']`, `['id_kendaraan']`, `['id_jns_kend']`, `['id_daftar']`, `['kuat_kanan']`, `['kuat_kiri']`, `['deviasi_kanan']`, `['deviasi_kiri']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Fungsi DB: `update_cis_lampu`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/pengukuran/pengukuran/saveFormRem`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_hasil_uji']`, `['id_kendaraan']`, `['id_jns_kend']`, `['id_daftar']`, `['sg_sb_1']`, `['sg_sb_2']`, `['sg_sb_3']`, `['sg_sb_4']`, `['sumbu_1']`, `['sumbu_2']`, `['sumbu_3']`, `['sumbu_4']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Fungsi DB: `update_cis_brake`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/pengukuran/pengukuran/reloadData`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['tahun_kendaraan']`.
- Respons: JSON; field envelope: `bsb1`, `bsb2`, `bsb3`, `bsb4`, `bsel1`, `bsel2`, `bsel3`, `bsel4`, `lampu_kanan`, `lampu_kiri`, `dev_kanan`, `dev_kiri`, `solar`, `ems_co`, `ems_hc`; ekspresi output `json_encode($data)`.
- Catatan: menghasilkan nilai acak.

<a id="controller-43"></a>

### /pengukuran/prauji/ — PraujiController

Sumber: [protected/modules/pengukuran/controllers/PraujiController.php](protected/modules/pengukuran/controllers/PraujiController.php).

Akses: **Rights** (cookie sesi + permission menurut database).

#### `/pengukuran/prauji/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `index_prauji`.

#### `/pengukuran/prauji/listGrid`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['selectCategory']`, `['textCategory']`, `['tgl_search']`, `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => VCisPraujiAll::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `VCisPraujiAll`.

#### `/pengukuran/prauji/proses`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['idhasil']`, `['variabel']`, `['username']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Fungsi DB: `update_cis_prauji`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/pengukuran/prauji/loadImage`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['idkendaraan']`.
- Respons: JSON; kunci array/record: `image1`, `image2`; ekspresi output `json_encode($data)`.

#### `/pengukuran/prauji/lainListGrid`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['page']`, `['rows']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => TblKelulusan::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `TblKelulusan`.

#### `/pengukuran/prauji/uploadImage`

- Pemanggilan: **POST / H-UPLOAD**; enforcement verb hanya bila terlihat di action/filter. FileUploader pada action pendaftaran/prauji membaca field `files` (dapat berupa `files[]` dari widget).
- Input: POST: `['id_hasil_uji_prauji']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/pengukuran/prauji/replaceFile`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `replace_file`.

<a id="controller-44"></a>

### /recommendation/bukuuji/ — BukuujiController

Sumber: [protected/modules/recommendation/controllers/BukuujiController.php](protected/modules/recommendation/controllers/BukuujiController.php).

Akses: **tidak ada filter otorisasi aktif yang dideklarasikan di controller ini**; pemeriksaan inline/module masih berlaku bila ada. Pewarisan Controller/RController saja tidak memasang filter.

#### `/recommendation/bukuuji/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `index`.

#### `/recommendation/bukuuji/pilihStatusRecommendation`

- Pemanggilan: **GET+POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['step']`.
- Respons: HTML `proses`.

#### `/recommendation/bukuuji/listGridBukuUjiKasubag`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['tgl_pengajuan']`, `['page']`, `['rows']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => TblReqBukuuji::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `TblReqBukuuji`.

#### `/recommendation/bukuuji/listGridBukuUjiKaDis`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['tgl_pengajuan']`, `['page']`, `['rows']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => TblReqBukuuji::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `TblReqBukuuji`.

#### `/recommendation/bukuuji/prosesnoseri`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['kepala_seri']`, `['no_seri_awal']`, `['jumlah']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/recommendation/bukuuji/approveRejectRekom`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['pilihan']`, `['id']`.
- Respons: JSON; ekspresi output `json_encode(array('success' => $status))`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/recommendation/bukuuji/sendSms`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['tgl']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `TblReqBukuuji`.
- Catatan: menulis atau memanggil operasi bisnis DB.

<a id="controller-45"></a>

### /recommendation/default/ — DefaultController

Sumber: [protected/modules/recommendation/controllers/DefaultController.php](protected/modules/recommendation/controllers/DefaultController.php).

Akses: **Rights** (cookie sesi + permission menurut database).

Tidak ada inline action aktif; root controller tidak menyediakan action index.

<a id="controller-46"></a>

### /recommendation/rekom/ — RekomController

Sumber: [protected/modules/recommendation/controllers/RekomController.php](protected/modules/recommendation/controllers/RekomController.php).

Akses: **Rights** (cookie sesi + permission menurut database).

#### `/recommendation/rekom/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `status_rekomendasi`.

#### `/recommendation/rekom/listGridRekom`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['tgl_pengajuan_rekom']`, `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => TblRekom::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `TblRekom`, `TblKendaraan`, `VValidasi`.

#### `/recommendation/rekom/printRekom`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$id_kendaraan, $id_rekom, $ttd, $id_uji`.
- Respons: HTML `print_mutke_numke`, `print_rekom`.
- Model yang dipakai: `TblRekom`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/recommendation/rekom/selectProvinsi`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['provinsi']`.
- Respons: Teks/HTML output langsung; lihat cabang pada sumber.
- Model yang dipakai: `MKota`.

#### `/recommendation/rekom/getDetailDataRekom`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_rekom']`.
- Respons: JSON; field envelope: `id_rekom`, `no_uji`, `no_kendaraan`, `mutasi_keluar`, `nik_baru`, `pemilik_baru`, `alamat_baru`, `id_provinsi_mutke`, `id_kota_mutke`, `numpang_keluar`, `id_provinsi_numke`, `id_kota_numke`, `ubah_bentuk`, `ket_ubah_bentuk`; ekspresi output `json_encode($data)`.
- Model yang dipakai: `TblRekom`, `TblKendaraan`.

#### `/recommendation/rekom/saveRekom`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['checkbox_mutke']`, `['checkbox_numke']`, `['checkbox_ubah_bentuk']`, `['id_rekom']`, `['nik_baru']`, `['pemilik_baru']`, `['alamat_baru']`, `['kota_mutke']`, `['kota_numke']`, `['keterangan_ubah_bentuk']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `MasterArea`, `TblRekom`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/recommendation/rekom/sinkronDataRekom`

- Pemanggilan: **POST / H-JSON**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_rekom']`; JSON raw: body dibaca; tidak ada field client yang dipakai secara langsung.
- Respons: HTML `<pre>`/var_export data upstream atau teks pesan.
- Model yang dipakai: `TblRekom`, `TblKendaraan`.

<a id="controller-47"></a>

### /recommendation/report/ — ReportController

Sumber: [protected/modules/recommendation/controllers/ReportController.php](protected/modules/recommendation/controllers/ReportController.php).

Akses: **tidak ada filter otorisasi aktif yang dideklarasikan di controller ini**; pemeriksaan inline/module masih berlaku bila ada. Pewarisan Controller/RController saja tidak memasang filter.

#### `/recommendation/report/downloadLaporan`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$rekom`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `TblRekom`, `TblKendaraan`, `TblRiwayat`, `TblType`, `TblKota`.

<a id="controller-48"></a>

### /report/bukuuji/ — BukuujiController

Sumber: [protected/modules/report/controllers/BukuujiController.php](protected/modules/report/controllers/BukuujiController.php).

Akses: **Rights** (cookie sesi + permission menurut database).

#### `/report/bukuuji/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `index`.

#### `/report/bukuuji/rekap`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$tgl`.
- Respons: Ekspor Excel/PHPExcel (binary/attachment).
- Model yang dipakai: `Datapengujian`.
- Catatan: menulis atau memanggil operasi bisnis DB.

<a id="controller-49"></a>

### /report/default/ — DefaultController

Sumber: [protected/modules/report/controllers/DefaultController.php](protected/modules/report/controllers/DefaultController.php).

Akses: **tidak ada filter otorisasi aktif yang dideklarasikan di controller ini**; pemeriksaan inline/module masih berlaku bila ada. Pewarisan Controller/RController saja tidak memasang filter.

#### `/report/default/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `index`.

<a id="controller-50"></a>

### /report/kementrian/ — KementrianController

Sumber: [protected/modules/report/controllers/KementrianController.php](protected/modules/report/controllers/KementrianController.php).

Akses: **Rights** (cookie sesi + permission menurut database).

#### `/report/kementrian/berkala`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `berkala`.

#### `/report/kementrian/berkalaLtl`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `berkala_ltl`.

#### `/report/kementrian/berkala3500`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `berkala_3500`.

#### `/report/kementrian/berkala35016000`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `berkala_3501_6000`.

#### `/report/kementrian/berkala60019000`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `berkala_6001_9000`.

#### `/report/kementrian/berkala900112000`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `berkala_9001_12000`.

#### `/report/kementrian/berkala12000`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `berkala_12000`.

#### `/report/kementrian/reportBerkala3500`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$tgl`.
- Respons: Ekspor Excel/PHPExcel (binary/attachment).
- Model yang dipakai: `VKementrianBerkala3500`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/report/kementrian/reportBerkalaLTl`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$tgl`.
- Respons: Ekspor Excel/PHPExcel (binary/attachment).
- Model yang dipakai: `VKementrianBerkalaLtl`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/report/kementrian/reportBerkala35016000`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$tgl`.
- Respons: Ekspor Excel/PHPExcel (binary/attachment).
- Model yang dipakai: `VKementrianBerkala35016000`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/report/kementrian/reportBerkala60019000`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$tgl`.
- Respons: Ekspor Excel/PHPExcel (binary/attachment).
- Model yang dipakai: `VKementrianBerkala60019000`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/report/kementrian/reportBerkala900112000`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$tgl`.
- Respons: Ekspor Excel/PHPExcel (binary/attachment).
- Model yang dipakai: `VKementrianBerkala900112000`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/report/kementrian/reportBerkala12000`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$tgl`.
- Respons: Ekspor Excel/PHPExcel (binary/attachment).
- Model yang dipakai: `VKementrianBerkala12000`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/report/kementrian/ujiPertama`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `pertama`.

#### `/report/kementrian/reportUjiPertama`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$tgl`.
- Respons: Ekspor Excel/PHPExcel (binary/attachment).
- Model yang dipakai: `VKementrianBaru`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/report/kementrian/kbwu`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `kbwu`.

#### `/report/kementrian/reportKbwu`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$thn`.
- Respons: Ekspor Excel/PHPExcel (binary/attachment).
- Model yang dipakai: `VKementrianKbwu`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/report/kementrian/reportBerkala`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$tgl`.
- Respons: Ekspor Excel/PHPExcel (binary/attachment).
- Model yang dipakai: `VKementrianBerkala3500`, `VKementrianBerkala35016000`, `VKementrianBerkala60019000`, `VKementrianBerkala900112000`, `VKementrianBerkala12000`, `VKementrianBerkalaLtl`.
- Catatan: menulis atau memanggil operasi bisnis DB.

<a id="controller-51"></a>

### /report/kendaraanKecamatan/ — KendaraanKecamatanController

Sumber: [protected/modules/report/controllers/KendaraanKecamatanController.php](protected/modules/report/controllers/KendaraanKecamatanController.php).

Akses: **Rights** (cookie sesi + permission menurut database).

#### `/report/kendaraanKecamatan/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `index`.

#### `/report/kendaraanKecamatan/selectKecamatan`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['kecamatan']`.
- Respons: Teks/HTML output langsung; lihat cabang pada sumber.
- Model yang dipakai: `MKelurahan`.

#### `/report/kendaraanKecamatan/rekap`

- Pemanggilan: **GET/POST / H-GET atau H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: REQUEST: `['kecamatan']`, `['kelurahan']`.
- Respons: Ekspor Excel/PHPExcel (binary/attachment).
- Model yang dipakai: `MKecamatan`, `MKelurahan`, `VKendaraan`.
- Catatan: menulis atau memanggil operasi bisnis DB.

<a id="controller-52"></a>

### /report/pendapatan/ — PendapatanController

Sumber: [protected/modules/report/controllers/PendapatanController.php](protected/modules/report/controllers/PendapatanController.php).

Akses: **tidak ada filter otorisasi aktif yang dideklarasikan di controller ini**; pemeriksaan inline/module masih berlaku bila ada. Pewarisan Controller/RController saja tidak memasang filter.

#### `/report/pendapatan/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `index`.

#### `/report/pendapatan/rekap`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$tgl`.
- Respons: Ekspor Excel/PHPExcel (binary/attachment).
- Model yang dipakai: `VValidasi`.
- Catatan: menulis atau memanggil operasi bisnis DB.

<a id="controller-53"></a>

### /report/pengujian/ — PengujianController

Sumber: [protected/modules/report/controllers/PengujianController.php](protected/modules/report/controllers/PengujianController.php).

Akses: **tidak ada filter otorisasi aktif yang dideklarasikan di controller ini**; pemeriksaan inline/module masih berlaku bila ada. Pewarisan Controller/RController saja tidak memasang filter.

#### `/report/pengujian/pageReportUjiPertama`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `index_uji_pertama`.

#### `/report/pengujian/exportExcelUjiPertama`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$tgl`.
- Respons: Ekspor Excel/PHPExcel (binary/attachment).
- Model yang dipakai: `VValidasi`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/report/pengujian/ujiPertamaListGrid`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['tanggal']`, `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => VValidasi::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `VValidasi`.

#### `/report/pengujian/pageReportBukuUji`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `index_buku_uji`.

#### `/report/pengujian/exportExcelBukuUji`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: GET: `['tgl_report']`.
- Respons: Ekspor Excel/PHPExcel (binary/attachment).
- Model yang dipakai: `TblBuku`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/report/pengujian/tableBukuUji`

- Pemanggilan: **GET+POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['tanggal']`.
- Respons: HTML `table_buku_uji`.

#### `/report/pengujian/grafikBukuUji`

- Pemanggilan: **GET+POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['tanggal']`.
- Respons: HTML `grafik_buku_uji`.

<a id="controller-54"></a>

### /report/rekomendasi/ — RekomendasiController

Sumber: [protected/modules/report/controllers/RekomendasiController.php](protected/modules/report/controllers/RekomendasiController.php).

Akses: **tidak ada filter otorisasi aktif yang dideklarasikan di controller ini**; pemeriksaan inline/module masih berlaku bila ada. Pewarisan Controller/RController saja tidak memasang filter.

#### `/report/rekomendasi/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `index`.

#### `/report/rekomendasi/rekap`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$tgl, $pilihan`.
- Respons: Ekspor Excel/PHPExcel (binary/attachment).
- Model yang dipakai: `VRekom`, `MProvinsi`, `MKota`.
- Catatan: menulis atau memanggil operasi bisnis DB.

<a id="controller-55"></a>

### /report/retribusi/ — RetribusiController

Sumber: [protected/modules/report/controllers/RetribusiController.php](protected/modules/report/controllers/RetribusiController.php).

Akses: **tidak ada filter otorisasi aktif yang dideklarasikan di controller ini**; pemeriksaan inline/module masih berlaku bila ada. Pewarisan Controller/RController saja tidak memasang filter.

#### `/report/retribusi/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `index`.
- Model yang dipakai: `TblRetribusi`, `TblDaftar`.

#### `/report/retribusi/dataKendaraan`

- Pemanggilan: **GET+POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['tanggal']`.
- Respons: HTML `data_kendaraan`.
- Model yang dipakai: `TblRetribusi`, `TblDaftar`.

#### `/report/retribusi/dataKendaraanPerBulan`

- Pemanggilan: **GET+POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['blnThn']`.
- Respons: HTML `data_kendaraan_per_bln`.
- Model yang dipakai: `TblRetribusi`, `TblDaftar`.

#### `/report/retribusi/dataForHeaderTgl`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['tanggal']`.
- Respons: JSON; field envelope: `totalRetribusi`, `totalKendaraan`; ekspresi output `json_encode($data)`.
- Model yang dipakai: `TblRetribusi`, `TblDaftar`.

#### `/report/retribusi/dataForHeaderBln`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['blnThn']`.
- Respons: JSON; field envelope: `totalRetribusi`, `totalKendaraan`; ekspresi output `json_encode($data)`.
- Model yang dipakai: `TblRetribusi`, `TblDaftar`.

<a id="controller-56"></a>

### /report/tahunan/ — TahunanController

Sumber: [protected/modules/report/controllers/TahunanController.php](protected/modules/report/controllers/TahunanController.php).

Akses: **Rights** (cookie sesi + permission menurut database).

#### `/report/tahunan/indexReportPendapatan`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `index_report_pendapatan`.

#### `/report/tahunan/reportPendapatan`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$thn`.
- Respons: Ekspor Excel/PHPExcel (binary/attachment).
- Model yang dipakai: `TblIndikator`, `TblLapPad`, `VRetribusiAll`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/report/tahunan/indexReportKelulusan`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `index_report_kelulusan`.

#### `/report/tahunan/reportKelulusan`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$thn`.
- Respons: Ekspor Excel/PHPExcel (binary/attachment).
- Model yang dipakai: `VLapKendaraanBaru`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/report/tahunan/indexReportUjiKendaraan`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `index_report_uji_kendaraan`.

#### `/report/tahunan/reportUjiKendaraan`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$thn`.
- Respons: Ekspor Excel/PHPExcel (binary/attachment).
- Model yang dipakai: `VLapKendaraanBaru`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/report/tahunan/indexReportMutasi`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `index_report_mutasi`.

#### `/report/tahunan/reportMutasi`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$thn`.
- Respons: Ekspor Excel/PHPExcel (binary/attachment).
- Model yang dipakai: `VLapKendaraanBaru`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/report/tahunan/indexReportNumpangUji`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `index_report_numpang_uji`.

#### `/report/tahunan/reportNumpangUji`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$thn`.
- Respons: Ekspor Excel/PHPExcel (binary/attachment).
- Model yang dipakai: `VLapKendaraanBaru`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/report/tahunan/indexReportTerdaftar`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `index_report_terdaftar`.

#### `/report/tahunan/reportTerdaftar`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$thn`.
- Respons: Ekspor Excel/PHPExcel (binary/attachment).
- Model yang dipakai: `VLapKendaraanBaru`.
- Catatan: menulis atau memanggil operasi bisnis DB.

<a id="controller-57"></a>

### /report/tl/ — TlController

Sumber: [protected/modules/report/controllers/TlController.php](protected/modules/report/controllers/TlController.php).

Akses: **tidak ada filter otorisasi aktif yang dideklarasikan di controller ini**; pemeriksaan inline/module masih berlaku bila ada. Pewarisan Controller/RController saja tidak memasang filter.

#### `/report/tl/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `index`.

#### `/report/tl/rekap`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$tgl`.
- Respons: Ekspor Excel/PHPExcel (binary/attachment).
- Model yang dipakai: `VTl`.
- Catatan: menulis atau memanggil operasi bisnis DB.

<a id="controller-58"></a>

### /report/ujikendaraan/ — UjikendaraanController

Sumber: [protected/modules/report/controllers/UjikendaraanController.php](protected/modules/report/controllers/UjikendaraanController.php).

Akses: **Rights** (cookie sesi + permission menurut database).

#### `/report/ujikendaraan/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `index`.

#### `/report/ujikendaraan/rekap`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$tgl`.
- Respons: Ekspor Excel/PHPExcel (binary/attachment).
- Model yang dipakai: `TblIndikator`, `VLapKendUji`.
- Catatan: menulis atau memanggil operasi bisnis DB.

<a id="controller-59"></a>

### /retribusi/bukuuji/ — BukuujiController

Sumber: [protected/modules/retribusi/controllers/BukuujiController.php](protected/modules/retribusi/controllers/BukuujiController.php).

Akses: **Rights** (cookie sesi + permission menurut database).

#### `/retribusi/bukuuji/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `list_buku_uji`.

#### `/retribusi/bukuuji/bukulistgrid`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['chooseProses']`, `['textCategory']`, `['selectDate']`, `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => VGantibuku::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `VGantibuku`.

#### `/retribusi/bukuuji/bukuPerKendaraanlistgrid`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['textCategory']`, `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => VGantibuku::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `VGantibuku`.

#### `/retribusi/bukuuji/riwayatbukulistgrid`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['idKendaraan']`, `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => VDetBuku::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `VDetBuku`.

#### `/retribusi/bukuuji/saveBukuUji`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['dlg_text_id_retribusi']`, `['dlg_text_no_seri']`, `['ganti_tgl_cetak']`.
- Respons: JSON; field envelope: `id_buku`; ekspresi output `json_encode($result)`.
- Model yang dipakai: `TblBuku`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/retribusi/bukuuji/cetakBukuUji`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$id`.
- Respons: HTML `cetak_buku_uji`.

#### `/retribusi/bukuuji/saveCetakl`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id']`, `['posisi']`, `['penguji']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `VPrintHasil`, `MasterEmployee`, `MasterArea`, `MasterMerkTipeSub`, `Datapengujian`.
- Catatan: menulis atau memanggil operasi bisnis DB; menghasilkan nilai acak.

#### `/retribusi/bukuuji/cetakl`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$id, $posisi`.
- Respons: HTML `cetak_l`.

#### `/retribusi/bukuuji/detailBukuUji`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id']`.
- Respons: JSON; field envelope: `tanggal_cetak`, `no_uji`, `no_kendaraan`, `nama_pemilik`, `alamat`, `identitas`, `merk`, `tipe`, `nm_komersil`, `isi_silinder`, `daya_motor`, `bahan_bakar`, `tahun`, `status_penggunaan`, `no_chasis`, `no_mesin`, `no_regis`, `tgl_sertifikasi`, `petugas`, `ukuran_panjang`, `ukuran_lebar`, `ukuran_tinggi`, `bagian_belakang`, `bagian_depan`, `jsumbu1`, `ukq`, `dimpanjang`, `dimlebar`, `dimtinggi`, `karoseri_bahan`, `psumbu1`, `psumbu2`, `kemjbb`, `bsumbu1`, `bsumbu2`, `bsumbu3`, `bsumbu4`, `jumlah_sumbu`, `karoseri_duduk`, `kembarang`, `jbi`, `mst`; ekspresi output `json_encode($result)`.
- Model yang dipakai: `TblRetribusi`, `VKendaraan`, `TblBuku`.

#### `/retribusi/bukuuji/rekapBukuUji`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$tgl`.
- Respons: Ekspor Excel/PHPExcel (binary/attachment).
- Model yang dipakai: `VGantibuku`.
- Catatan: menulis atau memanggil operasi bisnis DB.

<a id="controller-60"></a>

### /retribusi/default/ — DefaultController

Sumber: [protected/modules/retribusi/controllers/DefaultController.php](protected/modules/retribusi/controllers/DefaultController.php).

Akses: **Rights** (cookie sesi + permission menurut database).

#### `/retribusi/default/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `form_retribusi`.
- Model yang dipakai: `TblUji`, `TblJnsKend`.

#### `/retribusi/default/print`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `print`.

#### `/retribusi/default/indexCoba`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `form_retribusi_coba`.
- Model yang dipakai: `TblUji`, `TblJnsKend`.

#### `/retribusi/default/detailNoSb`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['no_sb']`, `['pilihan']`.
- Respons: JSON; kunci array/record: `no_uji`, `id_kendaraan`, `no_kendaraan`, `id_jns_kend`, `jns_kend`, `nama_pemilik`, `alamat`, `no_identitas`, `no_telp`, `no_mesin`, `no_chasis`, `jbb`, `identitas`, `tgl_mati`, `img_scan`; ekspresi output `json_encode($data)`.
- Model yang dipakai: `VKendaraan`, `TblJnsKend`, `VRiwayat`.

#### `/retribusi/default/penjumlahanTanggal`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['tanggal_pengujian']`.
- Respons: JSON; field envelope: `tgl_mati_uji`; ekspresi output `json_encode($data)`.

#### `/retribusi/default/getListSelect`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['pilih']`.
- Respons: Teks/HTML output langsung; lihat cabang pada sumber.
- Model yang dipakai: `TblJnsKend`, `TblUji`, `TblBkMasuk`.

#### `/retribusi/default/updateRetribusi`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['dlg_idret_tglmati']`, `['dlg_jns_kend']`, `['pilih_kategori']`, `['ganti_tgl_uji']`, `['ganti_tgl_mati']`, `['ganti_replace']`, `['ganti_jbb']`, `['wilayah_asal']`, `['kategori']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `TblKendaraan`.
- Fungsi DB: `edit_retribusi`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/retribusi/default/saveform`

- Pemanggilan: **POST / H-UPLOAD**; enforcement verb hanya bila terlihat di action/filter. FileUploader pada action pendaftaran/prauji membaca field `files` (dapat berupa `files[]` dari widget).
- Input: POST: `['FORM']`; POST[FORM] nested: `JENIS_PENGENAL`, `USER_LOGIN`, `JENIS_PENGUJIAN`, `WILAYAH_ASAL`, `BUKU_UJI`, `NO_STUK`, `ID_KEND`, `ID_KENDARAAN`, `TGL_PENGUJIAN`, `TGL_MATI_UJI`, `NO_KENDARAAN`, `NAMA_PEMILIK`, `ALAMAT`, `NO_KTP`, `NO_TELP`, `NO_MESIN`, `NO_CHASIS`, `KARTU_HILANG`.
- Respons: JSON; field envelope: `ada`, `message`, `buku_uji`, `tgluji`, `tglmati`; ekspresi output `json_encode($result)`.
- Model yang dipakai: `TblKendaraan`, `TblRetribusi`.
- Fungsi DB: `insert_retribusi`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/retribusi/default/validasilistgridPetugas`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['selectCategory']`, `['textCategory']`, `['selectDate']`, `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => VValidasi::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `VValidasi`, `TblRetribusi`.

#### `/retribusi/default/detailRetribusi`

- Pemanggilan: **GET+POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id_retribusi']`.
- Respons: HTML `detail_retribusi`.
- Model yang dipakai: `VValidasi`.

#### `/retribusi/default/cetakRetribusi`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$id`.
- Respons: HTML `cetak_retribusi`.
- Model yang dipakai: `VValidasi`.

#### `/retribusi/default/cetakCheckedRetribusi`

- Pemanggilan: **GET/POST / H-GET atau H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: REQUEST: `['idArray']`.
- Respons: HTML `cetak_checked_retribusi`.

#### `/retribusi/default/detailPengurus`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.

#### `/retribusi/default/cetakStiker`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$id, $penguji`.
- Respons: HTML `cetak_stiker`.

#### `/retribusi/default/deleteRetribusi`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/retribusi/default/getListCalculator`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['idArray']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => count($idArray), 'rows' => $dataJson, 'totalcalculator' => number_format($jmlTotal, 0, ',', '.'), ) )`.
- Model yang dipakai: `VValidasi`.

#### `/retribusi/default/cetakBap`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$id_retribusi, $id_kendaraan, $id_uji`.
- Respons: HTML `cetak_bap`.

#### `/retribusi/default/cetakGesekan`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$id_kendaraan, $id_retribusi`.
- Respons: HTML `cetak_gesekan`.

#### `/retribusi/default/replaceFile`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `replace_file`.

<a id="controller-61"></a>

### /retribusi/validasi/ — ValidasiController

Sumber: [protected/modules/retribusi/controllers/ValidasiController.php](protected/modules/retribusi/controllers/ValidasiController.php).

Akses: **Rights** (cookie sesi + permission menurut database).

#### `/retribusi/validasi/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `list_validasi`.

#### `/retribusi/validasi/validasilistgrid`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['chooseValidasi']`, `['selectCategory']`, `['textCategory']`, `['selectDate']`, `['page']`, `['rows']`, `['sort']`, `['order']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => VValidasi::model()->count($criteria), 'rows' => $dataJson, ) )`.
- Model yang dipakai: `VValidasi`, `TblRetribusi`.

#### `/retribusi/validasi/deleteRetribusi`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['id']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/retribusi/validasi/prosesValidChecked`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['idArray']`, `['kondisi']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/retribusi/validasi/prosesValid`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['idRetribusi']`, `['kondisi']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/retribusi/validasi/rekapValidasi`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$tgl`.
- Respons: Ekspor Excel/PHPExcel (binary/attachment).
- Model yang dipakai: `VValidasi`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/retribusi/validasi/cetakKuitansiSkrd`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$id`.
- Respons: HTML `cetak_retribusi_skrd`.
- Model yang dipakai: `VValidasi`.

#### `/retribusi/validasi/cetakRetribusi`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$id`.
- Respons: HTML `cetak_retribusi`.
- Model yang dipakai: `VValidasi`.

#### `/retribusi/validasi/cetakSkrd`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$id`.
- Respons: HTML `cetak_skrd`.
- Model yang dipakai: `VValidasi`.

#### `/retribusi/validasi/cetakCheckedRetribusi`

- Pemanggilan: **GET/POST / H-GET atau H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: REQUEST: `['idArray']`.
- Respons: HTML `cetak_checked_retribusi`.

#### `/retribusi/validasi/getListSelect`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['pilih']`.
- Respons: Teks/HTML output langsung; lihat cabang pada sumber.
- Model yang dipakai: `TblJnsKend`, `TblUji`, `TblBkMasuk`.

#### `/retribusi/validasi/updateRetribusi`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['dlg_idret_tglmati']`, `['dlg_jns_kend']`, `['pilih_kategori']`, `['ganti_tgl_uji']`, `['ganti_tgl_mati']`, `['ganti_replace']`, `['ganti_jbb']`, `['wilayah_asal']`, `['kategori']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Model yang dipakai: `TblKendaraan`.
- Fungsi DB: `edit_retribusi`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/retribusi/validasi/getListCalculator`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['idArray']`.
- Respons: JSON grid; contoh envelope kosong `{"total":0,"rows":[]}` (tambahan field sesuai kode); ekspresi output `CJSON::encode( array( 'total' => count($idArray), 'rows' => $dataJson, 'totalcalculator' => number_format($jmlTotal, 0, ',', '.'), ) )`.
- Model yang dipakai: `VValidasi`.

#### `/retribusi/validasi/cetakCheckedSkrd`

- Pemanggilan: **GET/POST / H-GET atau H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: REQUEST: `['idArray']`.
- Respons: HTML `cetak_checked_skrd`.

#### `/retribusi/validasi/rekap`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$tgl`.
- Respons: Ekspor Excel/PHPExcel (binary/attachment).
- Model yang dipakai: `VValidasi`.
- Catatan: menulis atau memanggil operasi bisnis DB.

<a id="controller-62"></a>

### /rights/assignment/ — AssignmentController

Sumber: [protected/modules/rights/controllers/AssignmentController.php](protected/modules/rights/controllers/AssignmentController.php).

Akses: **accessControl**; `return array('accessControl');`. Lihat `accessRules()` dan kondisi installer pada sumber.

#### `/rights/assignment/view`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: Parameter action/query: `$name = '', $company = ''`.
- Respons: HTML `view`.

#### `/rights/assignment/user`

- Pemanggilan: **GET+POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['AssignmentForm']`; GET: `['id']`.
- Respons: HTML `user`; redirect pada cabang terkait.
- Model yang dipakai: `CActiveRecord`.

#### `/rights/assignment/revoke`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['ajax']`; GET: `['id']`.
- Respons: redirect pada cabang terkait.
- Catatan: ada pemeriksaan isPostRequest; lihat kondisi cabang.

<a id="controller-63"></a>

### /rights/authItem/ — AuthItemController

Sumber: [protected/modules/rights/controllers/AuthItemController.php](protected/modules/rights/controllers/AuthItemController.php).

Akses: **tidak ada filter otorisasi aktif yang dideklarasikan di controller ini**; pemeriksaan inline/module masih berlaku bila ada. Pewarisan Controller/RController saja tidak memasang filter.

Catatan: `accessRules()` tersedia tetapi `filters()` dikomentari. Jangan menganggap action manajemen permission terlindungi hanya karena rules tersebut.

#### `/rights/authItem/permissions`

- Pemanggilan: **GET+POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['ajax']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.

#### `/rights/authItem/operations`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `operations`.

#### `/rights/authItem/tasks`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `tasks`.

#### `/rights/authItem/roles`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `roles`.

#### `/rights/authItem/generate`

- Pemanggilan: **GET+POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['GenerateForm']`.
- Respons: HTML `generate`; redirect pada cabang terkait.

#### `/rights/authItem/create`

- Pemanggilan: **GET+POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['AuthItemForm']`.
- Respons: HTML `create`; redirect pada cabang terkait.

#### `/rights/authItem/update`

- Pemanggilan: **GET+POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['AuthItemForm']`, `['AuthChildForm']`.
- Respons: HTML `update`; redirect pada cabang terkait.

#### `/rights/authItem/delete`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['ajax']`.
- Respons: redirect pada cabang terkait.
- Catatan: ada pemeriksaan isPostRequest; lihat kondisi cabang.

#### `/rights/authItem/removeChild`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['ajax']`.
- Respons: redirect pada cabang terkait.
- Catatan: ada pemeriksaan isPostRequest; lihat kondisi cabang.

#### `/rights/authItem/assign`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['ajax']`.
- Respons: redirect pada cabang terkait.
- Catatan: ada pemeriksaan isPostRequest; lihat kondisi cabang.

#### `/rights/authItem/revoke`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['ajax']`.
- Respons: redirect pada cabang terkait.
- Catatan: ada pemeriksaan isPostRequest; lihat kondisi cabang.

#### `/rights/authItem/sortable`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['result']`.
- Respons: Tidak ada body eksplisit pada action; efek helper/framework dapat berlaku.
- Catatan: ada pemeriksaan isPostRequest; lihat kondisi cabang.

<a id="controller-64"></a>

### /rights/install/ — InstallController

Sumber: [protected/modules/rights/controllers/InstallController.php](protected/modules/rights/controllers/InstallController.php).

Akses: **accessControl**; `return $this->_installer->installed===true ? array('accessControl') : array();`. Lihat `accessRules()` dan kondisi installer pada sumber.

#### `/rights/install/confirm`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `confirm`.

#### `/rights/install/run`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: GET: `['confirm']`.
- Respons: redirect pada cabang terkait.
- Catatan: ada pemeriksaan isGuest inline.

#### `/rights/install/ready`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `ready`.

#### `/rights/install/error`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `error`.

<a id="controller-65"></a>

### /user/activation/ — ActivationController

Sumber: [protected/modules/user/controllers/ActivationController.php](protected/modules/user/controllers/ActivationController.php).

**Tidak terdaftar dalam konfigurasi aktif**; route di bawah adalah mapping potensial bila modul diaktifkan.

Akses: **tidak ada filter otorisasi aktif yang dideklarasikan di controller ini**; pemeriksaan inline/module masih berlaku bila ada. Pewarisan Controller/RController saja tidak memasang filter.

#### `/user/activation/activation`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: GET: `['email']`, `['activkey']`.
- Respons: HTML `/user/message`.
- Model yang dipakai: `User`.
- Catatan: menulis atau memanggil operasi bisnis DB.

<a id="controller-66"></a>

### /user/admin/ — AdminController

Sumber: [protected/modules/user/controllers/AdminController.php](protected/modules/user/controllers/AdminController.php).

**Tidak terdaftar dalam konfigurasi aktif**; route di bawah adalah mapping potensial bila modul diaktifkan.

Akses: **accessControl**; `return CMap::mergeArray(parent::filters(),array( 'accessControl', ));`. Lihat `accessRules()` dan kondisi installer pada sumber.

#### `/user/admin/admin`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: GET: `['User']`.
- Respons: HTML `index`.

#### `/user/admin/view`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `view`.

#### `/user/admin/create`

- Pemanggilan: **GET+POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['User']`, `['Profile']`.
- Respons: HTML `create`; redirect pada cabang terkait.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/user/admin/update`

- Pemanggilan: **GET+POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['User']`, `['Profile']`.
- Respons: HTML `update`; redirect pada cabang terkait.
- Model yang dipakai: `User`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/user/admin/delete`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['ajax']`.
- Respons: redirect pada cabang terkait.
- Model yang dipakai: `Profile`.
- Catatan: menulis atau memanggil operasi bisnis DB; ada pemeriksaan isPostRequest; lihat kondisi cabang.

<a id="controller-67"></a>

### /user/default/ — DefaultController

Sumber: [protected/modules/user/controllers/DefaultController.php](protected/modules/user/controllers/DefaultController.php).

**Tidak terdaftar dalam konfigurasi aktif**; route di bawah adalah mapping potensial bila modul diaktifkan.

Akses: **tidak ada filter otorisasi aktif yang dideklarasikan di controller ini**; pemeriksaan inline/module masih berlaku bila ada. Pewarisan Controller/RController saja tidak memasang filter.

#### `/user/default/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `/user/index`.

<a id="controller-68"></a>

### /user/login/ — LoginController

Sumber: [protected/modules/user/controllers/LoginController.php](protected/modules/user/controllers/LoginController.php).

**Tidak terdaftar dalam konfigurasi aktif**; route di bawah adalah mapping potensial bila modul diaktifkan.

Akses: **tidak ada filter otorisasi aktif yang dideklarasikan di controller ini**; pemeriksaan inline/module masih berlaku bila ada. Pewarisan Controller/RController saja tidak memasang filter.

#### `/user/login/login`

- Pemanggilan: **GET+POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['UserLogin']`.
- Respons: HTML `/user/login`; redirect pada cabang terkait.
- Catatan: ada pemeriksaan isGuest inline.

<a id="controller-69"></a>

### /user/logout/ — LogoutController

Sumber: [protected/modules/user/controllers/LogoutController.php](protected/modules/user/controllers/LogoutController.php).

**Tidak terdaftar dalam konfigurasi aktif**; route di bawah adalah mapping potensial bila modul diaktifkan.

Akses: **tidak ada filter otorisasi aktif yang dideklarasikan di controller ini**; pemeriksaan inline/module masih berlaku bila ada. Pewarisan Controller/RController saja tidak memasang filter.

#### `/user/logout/logout`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: redirect pada cabang terkait.

<a id="controller-70"></a>

### /user/profile/ — ProfileController

Sumber: [protected/modules/user/controllers/ProfileController.php](protected/modules/user/controllers/ProfileController.php).

**Tidak terdaftar dalam konfigurasi aktif**; route di bawah adalah mapping potensial bila modul diaktifkan.

Akses: **tidak ada filter otorisasi aktif yang dideklarasikan di controller ini**; pemeriksaan inline/module masih berlaku bila ada. Pewarisan Controller/RController saja tidak memasang filter.

#### `/user/profile/profile`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `profile`.

#### `/user/profile/edit`

- Pemanggilan: **GET+POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['ajax']`, `['User']`, `['Profile']`.
- Respons: HTML `edit`; redirect pada cabang terkait.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/user/profile/changepassword`

- Pemanggilan: **GET+POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['ajax']`, `['UserChangePassword']`.
- Respons: HTML `changepassword`; redirect pada cabang terkait.
- Model yang dipakai: `User`.
- Catatan: menulis atau memanggil operasi bisnis DB.

<a id="controller-71"></a>

### /user/profileField/ — ProfileFieldController

Sumber: [protected/modules/user/controllers/ProfileFieldController.php](protected/modules/user/controllers/ProfileFieldController.php).

**Tidak terdaftar dalam konfigurasi aktif**; route di bawah adalah mapping potensial bila modul diaktifkan.

Akses: **accessControl**; `return CMap::mergeArray(parent::filters(),array( 'accessControl', ));`. Lihat `accessRules()` dan kondisi installer pada sumber.

#### `/user/profileField/view`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `view`.

#### `/user/profileField/create`

- Pemanggilan: **GET+POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['ProfileField']`.
- Respons: HTML `create`; redirect pada cabang terkait.
- Model yang dipakai: `Profile`.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/user/profileField/update`

- Pemanggilan: **GET+POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['ProfileField']`.
- Respons: HTML `update`; redirect pada cabang terkait.
- Catatan: menulis atau memanggil operasi bisnis DB.

#### `/user/profileField/delete`

- Pemanggilan: **POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['ajax']`.
- Respons: redirect pada cabang terkait.
- Model yang dipakai: `Profile`.
- Catatan: menulis atau memanggil operasi bisnis DB; ada pemeriksaan isPostRequest; lihat kondisi cabang.

#### `/user/profileField/admin`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: GET: `['ProfileField']`.
- Respons: HTML `admin`.

<a id="controller-72"></a>

### /user/recovery/ — RecoveryController

Sumber: [protected/modules/user/controllers/RecoveryController.php](protected/modules/user/controllers/RecoveryController.php).

**Tidak terdaftar dalam konfigurasi aktif**; route di bawah adalah mapping potensial bila modul diaktifkan.

Akses: **tidak ada filter otorisasi aktif yang dideklarasikan di controller ini**; pemeriksaan inline/module masih berlaku bila ada. Pewarisan Controller/RController saja tidak memasang filter.

#### `/user/recovery/recovery`

- Pemanggilan: **GET+POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['UserChangePassword']`, `['UserRecoveryForm']`; GET: `['email']`, `['activkey']`.
- Respons: HTML `changepassword`, `recovery`; redirect pada cabang terkait.
- Model yang dipakai: `User`.
- Catatan: menulis atau memanggil operasi bisnis DB.

<a id="controller-73"></a>

### /user/registration/ — RegistrationController

Sumber: [protected/modules/user/controllers/RegistrationController.php](protected/modules/user/controllers/RegistrationController.php).

**Tidak terdaftar dalam konfigurasi aktif**; route di bawah adalah mapping potensial bila modul diaktifkan.

Akses: **tidak ada filter otorisasi aktif yang dideklarasikan di controller ini**; pemeriksaan inline/module masih berlaku bila ada. Pewarisan Controller/RController saja tidak memasang filter.

#### `/user/registration/registration`

- Pemanggilan: **GET+POST / H-FORM**; enforcement verb hanya bila terlihat di action/filter.
- Input: POST: `['ajax']`, `['RegistrationForm']`, `['Profile']`.
- Respons: HTML `/user/registration`; redirect pada cabang terkait.
- Catatan: menulis atau memanggil operasi bisnis DB.

<a id="controller-74"></a>

### /user/user/ — UserController

Sumber: [protected/modules/user/controllers/UserController.php](protected/modules/user/controllers/UserController.php).

**Tidak terdaftar dalam konfigurasi aktif**; route di bawah adalah mapping potensial bila modul diaktifkan.

Akses: **accessControl**; `return CMap::mergeArray(parent::filters(),array( 'accessControl', ));`. Lihat `accessRules()` dan kondisi installer pada sumber.

#### `/user/user/view`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `view`.

#### `/user/user/index`

- Pemanggilan: **GET / H-GET**; enforcement verb hanya bila terlihat di action/filter.
- Input: — (tidak ada input langsung yang terdeteksi).
- Respons: HTML `index`.

