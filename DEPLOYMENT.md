# Deployment app-031-uji-kir

Panduan Laravel → GitHub Actions → GHCR → Portainer → Watchtower.
Nama stack/container: **app-031-uji-kir**. Port host **8071**, port container **8000**.

## 1. File dan arsitektur

| File | Fungsi |
| --- | --- |
| `Dockerfile` | Build frontend Node 24, PHP 8.4 + FrankenPHP, target `test` dan `production` |
| `entrypoint.sh` | Menyiapkan storage, permission, cache, lalu Supervisor |
| `supervisord.conf` | Menjalankan Octane sebagai www-data, 2 worker secara default |
| `.github/workflows/main.yml` | Publish `latest` |
| `.github/workflows/staging.yml` | Test sebelum publish `staging` |
| `.github/workflows/production.yml` | Test, approval Environment, lalu publish `production` |
| `compose.staging.yaml` | Stack untuk server-dev-stg-63 |
| `compose.production.yaml` | Stack untuk server-prod-9 |

Image: `ghcr.io/kominfo-jombang/app-031-uji-kir`.
Runtime tidak membutuhkan source bind mount atau Node.js. Aset sudah dibangun di image.
PostgreSQL memakai layanan yang sudah tersedia di server. Session/cache memakai file dan queue `sync`; tidak memerlukan tabel queue/session/cache atau Redis untuk konfigurasi ini.
Storage persisten, sedangkan `bootstrap/cache` dibuat ulang di container agar cache versi lama tidak terbawa.
Aplikasi mengakses tabel PostgreSQL lama, termasuk `public.datapengujian`; deployment tidak membuat atau mengimpor database KIR.

## 2. Persiapan GitHub (sebelum push production)

1. Commit file deployment dan lockfile; jangan commit `.env`, credential, atau backup SQL.
2. Pastikan remote menunjuk `KOMINFO-JOMBANG/app-031-uji-kir`.
3. Siapkan branch `main`, `staging`, `production`. Promosikan lewat pull request berurutan.
4. Buka **Settings → Environments → New environment**, beri nama `production`.
5. Aktifkan **Required reviewers**, pilih reviewer organisasi, aktifkan **Prevent self-review** dan larang bypass jika kebijakan mengharuskan.
6. Batasi deployment branch environment tersebut ke `production`, lalu simpan.
7. Pastikan GitHub Actions diizinkan dan `GITHUB_TOKEN` dapat menulis packages. Token login GHCR disediakan otomatis oleh workflow.
8. Push/merge ke `main`, pantau tab **Actions**, lalu pastikan package/tag `latest` tersedia.
9. Merge ke `staging`; test harus lulus sebelum image `staging` dipublikasikan.
10. Sesudah UAT, merge ke `production`; reviewer membuka run Actions dan menyetujui job deployment sebelum image production dibangun/dipush.

**Environment bernama production saja tidak mengaktifkan approval.** Required reviewers harus benar-benar dikonfigurasi. Ketersediaannya bergantung pada plan/visibility repository. Jika tidak tersedia, jangan aktifkan rilis production otomatis sampai mekanisme approval yang memenuhi kebijakan tersedia.

Setiap build juga menghasilkan tag `<branch>-<full-SHA>-<run-id>-<attempt>`. Tag ini membedakan build antarcabang dan rerun; gunakan digest untuk identitas image yang benar-benar immutable. Catat tag dan digest build yang lolos UAT.

## 3. Persiapan server dan registry

1. Masuk ke **https://portainer.jombangkab.go.id**.
2. Untuk staging pilih **server-dev-stg-63**, production pilih **server-prod-9**. Panduan Compose ini ditujukan untuk Docker Standalone.
3. Pastikan host port **8071** belum dipakai. Staging dan production memakai port sama karena server berbeda; jangan jalankan keduanya pada host sama tanpa mengubah port/nama container.
4. Buka **Networks**. Pastikan network bridge bernama **app-network** tersedia. Jika belum, **Add network → Name: app-network → Driver: bridge → Create network**.
5. Pastikan container PostgreSQL/reverse proxy yang perlu berkomunikasi terhubung ke network yang sesuai. DB_HOST adalah nama DNS/container database atau alamat server yang dapat dijangkau, bukan localhost container aplikasi.
6. Untuk image private, buka **Registries → Add registry → Custom registry**:
   - Name: `GHCR KOMINFO`
   - Registry URL: `ghcr.io`
   - Authentication: aktif
   - Username: akun GitHub layanan yang diberi akses package
   - Password/token: PAT dengan `read:packages`, akses package terkait, dan otorisasi SSO organisasi jika diwajibkan.
   - Simpan dan berikan akses registry ke environment/operator yang akan deploy.
7. Siapkan direktori storage melalui terminal server (pilih perintah sesuai target):

```sh
# server-dev-stg-63
sudo mkdir -p /home/kominfo2025/data_app/app-031-uji-kir/staging/storage_data

# server-prod-9
sudo mkdir -p /home/kominfo2025/data_app/app-031-uji-kir/production/storage_data
```

Entrypoint memperbaiki ownership folder writable untuk www-data. Jika memindahkan upload dari deployment lama, salin data publik yang relevan ke `storage_data/app/public`; jangan menyalin cache/framework lama. Database dan data Yii tidak diubah otomatis.

## 4. Buat stack di Portainer

1. Pilih environment target sekali lagi.
2. Buka **Stacks → Add stack**.
3. Isi **Name: app-031-uji-kir**.
4. Pilih **Web editor**.
5. Salin seluruh isi file berikut ke editor:
   - Staging: `compose.staging.yaml`.
   - Production: `compose.production.yaml`.
6. Pastikan image berakhir `:staging` atau `:production`, port `8071:8000`, volume sesuai target, network `app-network`, dan label Watchtower aktif.
7. Di **Environment variables**, tambahkan variabel wajib pada tabel berikut. Masukkan nilai tanpa tanda kutip pembungkus ketika memakai form Portainer.

| Variabel | Nilai/keterangan |
| --- | --- |
| `APP_KEY` | Key Laravel base64 yang permanen; berbeda per environment |
| `APP_URL` | URL publik penuh, misalnya `https://domain-aplikasi` |
| `DB_HOST` | DNS/container PostgreSQL atau alamat database |
| `DB_DATABASE` | Nama database KIR sesuai target |
| `DB_USERNAME` | Akun database aplikasi |
| `DB_PASSWORD` | Password akun tersebut |

Buat APP_KEY sekali pada mesin tepercaya yang memiliki PHP:

```sh
php -r 'echo "base64:".base64_encode(random_bytes(32)).PHP_EOL;'
```

Simpan hasilnya di Portainer/secret manager. Jangan menggantinya setiap rilis karena session/data terenkripsi bergantung pada key. Jangan memakai key contoh dari test CI.

Variabel opsional:

| Variabel | Default / penggunaan |
| --- | --- |
| `DB_PORT` | `5432` |
| `DB_SEARCH_PATH` | `public` |
| `DB_SSLMODE` | `prefer`; sesuaikan kebijakan server PostgreSQL |
| `ASSET_URL` | Kosong; isi hanya jika aset memakai URL terpisah |
| `LOG_LEVEL` | `warning`; ubah sementara bila investigasi |
| `OCTANE_WORKERS` | `2`; sesuaikan RAM/CPU |
| `OCTANE_MAX_REQUESTS` | `500` |
| `OCTANE_HTTPS` | `true` untuk URL publik HTTPS |
| `SESSION_SECURE_COOKIE` | `true` untuk URL publik HTTPS |
| `KIR_TABLE`, `KIR_COL_*` | Default tercantum dalam Compose dan `config/kir.php`; sebagian query aplikasi masih memakai nama tabel/kolom langsung, jadi override bukan pengganti migrasi skema |

`APP_ENV`, `APP_DEBUG=false`, konfigurasi session/cache/queue, dan logging sudah disediakan oleh stack. Jangan menyalin `.env` Yii mentah ke Portainer. Pada file dotenv lokal, nilai mengandung spasi harus dikutip; pada form environment Portainer cukup masukkan teks nilainya.

Untuk pemeriksaan sementara lewat HTTP tanpa reverse proxy: isi APP_URL `http://IP-SERVER:8071`, OCTANE_HTTPS `false`, SESSION_SECURE_COOKIE `false`. Sesudah domain HTTPS tersedia, kembalikan keduanya ke `true`, ubah APP_URL, dan update stack.

8. Klik **Deploy the stack**. Tunggu image ditarik dan container dibuat.
9. Jika gagal pull, periksa tag di GHCR, registry credential, akses package, DNS dan koneksi server.

## 5. Periksa container sampai aplikasi siap

1. Buka **Stacks → app-031-uji-kir → Containers → app-031-uji-kir**.
2. Pastikan status **running**, kemudian health menjadi **healthy** (beri waktu sekitar 60–90 detik).
3. Buka **Logs**. Pastikan cache berhasil dibuat, Octane berjalan, dan tidak ada error permission/config/database.
4. Uji dari server atau jaringan yang diizinkan:

```sh
curl --fail http://127.0.0.1:8071/up
```

Jika memakai terminal di mesin lain, ganti 127.0.0.1 dengan IP server. Endpoint `/up` memeriksa boot aplikasi; bukan bukti bahwa database bisnis dapat diakses.

5. Konfigurasikan reverse proxy/domain dengan upstream `http://app-031-uji-kir:8000` jika proxy satu network Docker, atau `http://IP-SERVER:8071` jika proxy eksternal. TLS ditangani reverse proxy. Jangan membuka port admin Caddy 2019.
6. Akses URL publik `/`, `/cek-kendaraan`, dan `/up`. Pastikan CSS/JS termuat dan tidak ada mixed content.
7. Lakukan pencarian kendaraan menggunakan data uji yang diketahui. Verifikasi detail, foto, status/riwayat uji dan endpoint API yang digunakan aplikasi. Ini juga memverifikasi skema/data PostgreSQL setelah migrasi dari Yii.
8. Pastikan session/form tetap berfungsi dan tidak muncul 419. Verifikasi URL HTTPS, secure cookie, APP_KEY, dan permission session apabila gagal.
9. Uji restart container, lalu pastikan upload/storage tetap ada. Tetapkan backup volume dan database terpisah.
10. Catat tag/digest, tanggal rilis, hasil smoke test, dan penanggung jawab. Deployment selesai setelah health, akses publik, aset, dan fungsi bisnis lolos.

Batas awal adalah RAM 512 MB dan CPU 0.5. Pantau **Stats** dan sesuaikan resource/worker berdasarkan beban, terutama saat pemrosesan gambar.

## 6. Watchtower dan update berikutnya

Label pada stack mengizinkan update, tetapi **tidak memasang Watchtower**. Administrator perlu memastikan Watchtower berjalan di host target, memantau container berlabel, dan memiliki credential sendiri untuk menarik image private GHCR. Credential Portainer tidak otomatis menjadi credential Watchtower.

Alur rilis:

1. Merge ke main → build latest.
2. Merge main ke staging → test → publish staging → Watchtower memperbarui staging.
3. Selesaikan UAT staging.
4. Merge staging ke production → test → approval → publish production → Watchtower memperbarui production.
5. Ulangi smoke test dan catat digest.

Jika Watchtower tidak dipakai/bermasalah: buka stack → **Editor → Update the stack**, aktifkan opsi pull/re-pull image terbaru jika tersedia. Alternatif: Containers → container → **Recreate → Pull latest image**. Nama tombol dapat berbeda antarversi Portainer. Perubahan environment/Compose harus melalui update stack.

Penggantian satu container dapat menyebabkan jeda layanan singkat. Jangan mengasumsikan zero downtime. Docker healthcheck hanya menandai unhealthy; tidak otomatis melakukan rollback atau restart container unhealthy.

## 7. Migration manual dan rollback

Tidak ada migration atau seeder otomatis saat container startup. Untuk database lama Yii, tinjau migration terlebih dahulu, backup database, dan uji di staging. Jangan menjalankan migrate:fresh atau seed demo pada database operasional.

Jika suatu rilis memang membutuhkan migration yang sudah ditinjau, buka container → **Console**, pilih `/bin/sh`, user `www-data`, lalu jalankan:

```sh
php artisan migrate:status
php artisan migrate --force
```

Jalankan hanya setelah prosedur rilis menyatakan migration aman. Tabel migration Laravel dan tabel lama KIR bukan hal yang sama.

Untuk rollback:

1. Pilih digest/tag rilis yang terakhir stabil di GHCR/Actions.
2. Buka **Stacks → app-031-uji-kir → Editor**.
3. Ganti image dengan `ghcr.io/kominfo-jombang/app-031-uji-kir@sha256:DIGEST_STABIL` atau tag release lengkap dari workflow.
4. Ubah label Watchtower menjadi `"false"` selama pemulihan.
5. **Update the stack**, tarik image, tunggu healthy, lalu ulangi smoke test.
6. Rollback image tidak memulihkan database. Evaluasi kompatibilitas skema/data sebelum rollback; gunakan backup hanya melalui prosedur pemulihan yang disetujui.
7. Setelah perbaikan melalui staging dan production disetujui, kembalikan tag `:production`, label Watchtower `"true"`, lalu update stack.

## 8. Validasi di mesin development/CI

```sh
# Docker diperlukan. Build test juga membangun aset Vite.
docker build --target test -t app-031-uji-kir:test .
docker run --rm -e APP_ENV=testing -e APP_KEY=base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA= app-031-uji-kir:test

docker build --target production -t app-031-uji-kir:local .
# Isi variabel wajib dengan nilai uji saat memvalidasi Compose.
docker compose -f compose.staging.yaml config --quiet
```

Jangan jalankan perintah Compose tanpa `--quiet` di log publik ketika environment berisi credential. CI staging dan production juga membangun image production, menjalankan container, dan memeriksa `/up` serta halaman utama sebelum publish. Suite test saat ini bersifat dasar; UAT fungsi KIR dan database tetap wajib.

## 9. Troubleshooting

| Gejala | Pemeriksaan |
| --- | --- |
| `APP_KEY must be configured` | Isi APP_KEY di environment stack |
| `manifest not found` | Periksa keberhasilan stage assets dan `public/build` dalam image |
| Database connection refused | DB_HOST/port/network/firewall/pg_hba.conf; localhost adalah container sendiri |
| Relation/column tidak ditemukan | Cocokkan schema database hasil migrasi Yii dengan query aplikasi |
| Permission denied storage | Volume harus dapat di-chown oleh entrypoint; cek filesystem/NFS host |
| Image pull unauthorized | Registry Portainer dan credential Watchtower diperiksa masing-masing |
| Unhealthy / restart berulang | Baca Logs, periksa resource dan versi FrankenPHP; Octane terpasang memerlukan FrankenPHP minimal 1.13 |
| Production langsung build tanpa approval | Periksa Required reviewers pada Environment production sebelum rilis berikutnya |

Referensi: [Laravel Octane](https://laravel.com/docs/13.x/octane), [GitHub deployment environments](https://docs.github.com/en/actions/how-tos/deploy/configure-and-manage-deployments/manage-environments).

## 10. Hasil validasi implementasi lokal

- Composer validate: lulus.
- Build frontend Vite dengan Node 24: lulus.
- PHPUnit: 2 test lulus menggunakan environment sementara dan key khusus test.
- Cache config, route, dan view: lulus.
- Parser YAML untuk seluruh workflow/Compose dan sintaks shell entrypoint: lulus.
- Build/runtime Docker dan `docker compose config` belum dijalankan lokal karena Docker tidak tersedia. Smoke test container disediakan di CI.
- `.env` lokal lama belum valid untuk parser dotenv Laravel (nilai ber-spasi belum dikutip). File itu tidak diubah dan tidak masuk image. Perbaiki formatnya sebelum menjalankan Artisan lokal tanpa environment pengujian.
- Belum dilakukan push GitHub, konfigurasi reviewer/registry, atau deployment server dari sesi implementasi ini.
