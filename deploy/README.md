# Deployment app-031-uji-kir

Implementasi mengikuti pedoman internal versi 1.0 (2026): main → latest; staging → test → staging; production → approval environment production → production. Semua workflow juga menghasilkan tag SHA 7 karakter. Runtime disesuaikan menjadi PHP 8.4 + Apache, Yii 1.1.32, PostgreSQL, PhpSpreadsheet dan mPDF melalui composer.lock. Tidak menggunakan Octane, Redis, queue, atau Supervisor.

## Status dan batas verifikasi

- Source deployment dan workflow tersedia. Belum ada push, perubahan branch remote, konfigurasi environment GitHub, publikasi GHCR atau deploy Portainer dari workspace ini.
- PHP CLI lokal 8.4: syntax source aktif, bootstrap Yii, roundtrip XLS/XLSX, dan rendering PDF diuji melalui `tests/smoke/runtime.php`.
- HTTP health 200 dan error 404 diuji melalui PHP development server. Ini bukan verifikasi Apache/container.
- Docker CLI/daemon tidak tersedia di workspace: image build, konfigurasi Apache, dan tes container harus dijalankan di mesin Docker/GitHub Actions.
- Tidak tersedia akses/dump database operasional, template laporan server lama, kamera, dan printer. Login berbasis DB, seluruh laporan, alur uji kendaraan, Android, dan restore belum tervalidasi.
- Tes pipeline staging adalah pemeriksaan runtime/HTTP dasar. UAT database dan proses bisnis tetap wajib sebelum merge production.

## Konfigurasi GitHub yang wajib

Repository terdeteksi: `KOMINFO-JOMBANG/app-031-uji-kir`; image: `ghcr.io/kominfo-jombang/app-031-uji-kir`.

1. Review perubahan, commit, lalu push ke `main`. Buat `staging` dan `production` dari commit yang ditinjau. Jangan push production sebelum langkah 2 selesai.
2. Settings → Environments → buat `production`, aktifkan Required reviewers sesuai organisasi. Tentukan reviewer; bila tersedia, aktifkan prevent self-review dan batasi deployment branch menjadi `production`. Pastikan plan/visibilitas repo mendukung required reviewers. Jika fitur tidak tersedia, production belum memenuhi pedoman dan jangan dipromosikan.
3. Verifikasi Actions diizinkan menggunakan checkout v4, setup-buildx v3 dan `GITHUB_TOKEN` dengan `contents: read`, `packages: write`. Pastikan paket GHCR memberi repository akses publish.
4. Branch protection/ruleset sesuai kebijakan organisasi: promosi melalui PR main → staging → production. Wajibkan hasil job `test` sebelum menyatakan staging lulus.
5. Jalankan main dan staging; pastikan kegagalan test menghentikan job build staging. Sebelum mengaktifkan Watchtower production, buktikan job production menunggu reviewer dan belum melakukan build/push.

`environment: production` pada YAML tidak mengaktifkan required reviewers dengan sendirinya. Pengaturan ini harus diselesaikan oleh administrator repository. Tidak ada secret aplikasi yang diberikan ke docker build.

## Menjalankan lokal dan memeriksa image

```sh
composer install --no-interaction --prefer-dist --no-plugins --no-scripts
php tests/smoke/runtime.php
docker build -t app-031-uji-kir:test .
docker run --rm app-031-uji-kir:test php tests/smoke/runtime.php
bash tests/smoke/container.sh app-031-uji-kir:test
```

`.env.example` adalah daftar environment, bukan file yang otomatis dibaca Yii. Untuk menjalankan container gunakan `--env-file`; Portainer memakai Environment variables. Jangan mengisi secret pada file tracked atau memasukkannya ke image. Nilai credential lama sudah dihapus dari konfigurasi aktif, tetapi riwayat Git tidak ditulis ulang: rotasi credential lama tetap diperlukan oleh pemilik layanan.

Framework dan library lama yang masih berada di repository (`yii/`, `protected/extensions/PHPExcel*`, `protected/vendors/mpdf`) tidak disertakan dalam image. Entry point web/console memakai Composer. Seluruh pemakai PHPExcel pada kode aplikasi dimigrasikan ke PhpSpreadsheet, termasuk perubahan indeks kolom menjadi 1-based dan nama properti border. mPDF modern diuji sebagai library; ini tidak membuktikan semua template cetak bisnis menghasilkan layout yang identik.

## Data dan environment

- Isi DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD. `localhost` bukan server DB eksternal dari dalam container.
- Koneksi `dbcoba` hanya diaktifkan jika DB_SECOND_HOST diisi. Tentukan kebutuhan aktual sebelum menonaktifkannya di production. Model SMS yang membutuhkan `db_sms` belum memiliki konfigurasi dan tidak diaktifkan oleh deployment ini.
- Restore dump PostgreSQL lengkap beserta view, function, sequence, permission, role/menu, dan master ke DB staging terpisah. SQL scaffold pada repo tidak cukup.
- Session tetap menggunakan CDbHttpSession. SESSION_AUTO_CREATE=false: administrator harus memastikan tabel `YiiSession` beserta hak akses runtime tersedia sebelum login diuji. Tidak ada migration pada startup.
- Jalankan `php tests/smoke/database.php` dalam container staging dengan environment DB yang benar; pemeriksaan bersifat read-only dan hanya mencakup koneksi/tabel inti.
- Isi KEMENHUB_TOKEN, endpoint kamera, CAMERA_USERNAME/PASSWORD, FONNTE_TOKEN dan QONTAK_TOKEN hanya jika layanan digunakan. Staging memakai konfigurasi sandbox atau kosong. Endpoint snapshot kamera harus URL lengkap HTTP(S) tanpa credential di URL. Empat kamera CIS memakai CAMERA_FRONT_URL, CAMERA_REAR_URL, CAMERA_RIGHT_URL, CAMERA_LEFT_URL. CAMERA_URL digunakan layar capture terpisah.
- Kredensial kamera saat ini satu pasangan untuk semua kamera. Kegagalan/missing konfigurasi kamera menghasilkan 503 sebelum menyimpan hasil pengambilan gambar. Jaringan server harus memiliki rute/VPN ke LAN kamera.
- Password alternatif universal sudah dihapus. Login masih memverifikasi hash MD5 akun lama untuk mempertahankan akses; migrasi hash akun merupakan pekerjaan lanjutan yang perlu mengubah semua jalur pembuatan/perubahan password.
- Gii dinonaktifkan. Debug selalu false pada APP_ENV=production. Error handler mengembalikan pesan generik.

## First deploy Portainer

1. Staging: environment `server-dev-stg-63`, gunakan `stack.staging.yml`. Production: `server-prod-9`, gunakan `stack.production.yml` setelah UAT dan approval.
2. Pastikan registry credential GHCR untuk private image tersedia bagi Portainer **dan Watchtower**. Pastikan `app-network` sudah ada pada target server.
3. Isi IMAGE_REPOSITORY, APP_HOST_PORT yang belum dipakai, dan APP_DATA_PATH terpisah per environment. Contoh staging: `/home/kominfo2025/data_app/app-031-uji-kir/staging`; production gunakan direktori berbeda.
4. Siapkan subdirektori `uploads`, `downloadsfile`, `template_laporan/hasil`, dan `runtime`. Salin data dari server lama; salin template RTF ke `template_laporan`. Volume kosong tidak berisi template laporan.
5. Owner file/direktori writable harus UID/GID `www-data` image (Debian: 33:33). Entrypoint menyiapkan ownership direktori teratas, bukan mengubah semua file lama secara rekursif. Siapkan izin isi volume saat migrasi. Source dan template cukup readable; folder hasil writable.
6. Isi environment menggunakan `.env.example` sebagai acuan; deploy stack. Periksa log, health, reverse proxy/domain dan TLS. Port container 80; port host disesuaikan. Resource awal 512 MB/0.5 CPU perlu dievaluasi untuk laporan besar.
7. UAT: login/logout/session/role, cari kendaraan, daftar → pemeriksaan → hasil → cetak, XLS/XLSX impor-ekspor, foto, Android, integrasi, dan izin endpoint. Cek gambar/file bertahan setelah container direcreate.
8. Backup DB + file secara konsisten dan uji restore. Jangan aktifkan cron notifikasi lama sebelum penerima, token, dan jadwal diverifikasi.

Health `/site/health` hanya membuktikan PHP/Yii hidup. Sengaja tidak mengakses DB agar status liveness tidak bergantung pada layanan eksternal. Gangguan DB memerlukan pemantauan terpisah. Log Yii diputar oleh CFileLogRoute dalam volume runtime; log Apache/PHP diarahkan ke stdout/stderr dengan rotasi Docker.

Apache menolak akses langsung ke protected/vendor/config/dotfiles, mematikan directory listing, dan melarang PHP pada folder data. Pengetesan aturan ini tersedia dalam smoke test container. Audit otorisasi endpoint, SQL interpolasi, dan CSRF aplikasi lama tetap pekerjaan tersendiri; jangan menafsirkan lolos health check sebagai kelulusan audit keamanan.

## Rilis dan rollback

Merge main → staging, tunggu test dan publikasi image, lalu Watchtower memperbarui staging. Setelah UAT lulus, merge staging → production. Reviewer menyetujui environment sebelum image production dibangun/dipublikasikan, kemudian Watchtower memperbarui production.

Untuk rollback, ubah image stack ke tag SHA stabil lalu redeploy dan smoke test. Catat digest image setiap rilis: tag SHA pendek mengikuti pedoman tetapi dapat tertimpa jika workflow commit yang sama dijalankan ulang. Digest memberi identitas image yang pasti. Pertahankan image lama. Rollback aplikasi tidak mengembalikan mutasi DB; pemulihan data membutuhkan prosedur tersendiri.
