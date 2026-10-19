# app-031-uji-kir

Aplikasi pengujian kendaraan bermotor berbasis Yii, PHP, dan PostgreSQL.

## Panduan deployment Portainer

Panduan ini menggunakan **Portainer dengan Docker Standalone**. Jalankan staging terlebih dahulu, lalu production setelah pengujian berhasil. Stack hanya menjalankan aplikasi; PostgreSQL disiapkan terpisah.

File yang digunakan:

- [Stack staging](deploy/stack.staging.yml)
- [Stack production](deploy/stack.production.yml)
- [Referensi environment variable](.env.example)
- [Detail deployment, batas verifikasi, dan migrasi](deploy/README.md)

### 1. Pastikan image staging tersedia

Pada pemeriksaan terakhir saat branch disiapkan, branch `staging` dan `production` baru dibuat lokal dari commit `f35f1ff`. Push belum berhasil karena autentikasi GitHub HTTPS belum tersedia. Periksa kembali kondisi remote sebelum melanjutkan.

Setelah autentikasi Git siap, jalankan dari repository lokal:

```bash
git push -u origin staging
```

Buka **GitHub → repository → Actions → Staging**. Pastikan job `test` dan `build` berhasil dan image berikut tersedia:

```text
ghcr.io/kominfo-jombang/app-031-uji-kir:staging
```

Portainer mengambil image tersebut, bukan membangun source aplikasi. Alur image repository ini adalah `main → latest`, `staging → staging`, dan `production → production`.

### 2. Tambahkan registry GHCR

Untuk image private, buka **Registries → Add registry** di Portainer dan isi:

| Kolom | Nilai |
| --- | --- |
| Name | `GHCR KOMINFO` |
| Registry URL | `ghcr.io` |
| Authentication | Aktif |
| Username | Username GitHub yang memiliki akses package |
| Password / Token | Personal Access Token classic dengan scope `read:packages` |

Akun pemilik token harus memiliki akses ke package. Jika organisasi menggunakan SSO, otorisasi token sesuai kebijakan organisasi. Simpan token pada konfigurasi registry Portainer, bukan di repository.

Referensi: [Autentikasi GitHub Container Registry](https://docs.github.com/en/packages/working-with-a-github-packages-registry/working-with-the-container-registry).

### 3. Siapkan network dan direktori staging

Pilih environment `server-dev-stg-63` sesuai rencana deployment repository; sesuaikan jika nama environment aktual berbeda.

Pada menu **Networks**, pastikan network `app-network` tersedia. Jika belum, buat dengan nama `app-network` dan driver `bridge`.

Melalui SSH ke **host Docker staging**, siapkan direktori:

```bash
sudo mkdir -p \
  /home/kominfo2025/data_app/app-031-uji-kir/staging/uploads \
  /home/kominfo2025/data_app/app-031-uji-kir/staging/downloadsfile \
  /home/kominfo2025/data_app/app-031-uji-kir/staging/template_laporan/hasil \
  /home/kominfo2025/data_app/app-031-uji-kir/staging/runtime
```

Salin data aplikasi lama sesuai pemetaan berikut:

| Data lama | Direktori tujuan di bawah path staging |
| --- | --- |
| Berkas upload | `uploads` |
| Berkas unduhan | `downloadsfile` |
| Template laporan, termasuk RTF | `template_laporan` |

Setelah penyalinan, berikan ownership direktori yang ditulis aplikasi kepada UID/GID `www-data` image (`33:33`):

```bash
sudo chown -R 33:33 \
  /home/kominfo2025/data_app/app-031-uji-kir/staging/uploads \
  /home/kominfo2025/data_app/app-031-uji-kir/staging/downloadsfile \
  /home/kominfo2025/data_app/app-031-uji-kir/staging/template_laporan/hasil \
  /home/kominfo2025/data_app/app-031-uji-kir/staging/runtime
```

Pastikan template lainnya dapat dibaca UID `33`, termasuk izin menelusuri direktori induknya. Path tersebut berada pada host Docker target, bukan komputer lokal atau host Portainer jika berbeda. Volume template kosong tidak otomatis berisi template laporan lama.

### 4. Siapkan database staging

Siapkan PostgreSQL dan database staging terpisah. Restore dump aplikasi lengkap, termasuk tabel, view, function, sequence, data pengguna, role/menu, dan permission. SQL scaffold repository tidak cukup untuk menggantikan dump operasional.

- `DB_HOST` harus dapat dijangkau dari container aplikasi.
- Gunakan IP/DNS server PostgreSQL atau nama container PostgreSQL pada network Docker yang sama.
- Jangan memakai `localhost` untuk PostgreSQL di luar container aplikasi.
- Pastikan firewall dan pengaturan akses PostgreSQL mengizinkan koneksi aplikasi.
- Tabel `YiiSession` beserta hak akses runtime harus tersedia karena stack menetapkan `SESSION_AUTO_CREATE=false`.

### 5. Buat stack staging

1. Pilih environment staging di Portainer.
2. Buka **Stacks → Add stack**.
3. Isi nama `app-031-uji-kir-staging`.
4. Pilih **Web editor**.
5. Salin seluruh isi [deploy/stack.staging.yml](deploy/stack.staging.yml) ke editor.
6. Pilih registry GHCR yang telah ditambahkan jika tersedia pilihan registry.

Portainer mendukung pengisian environment variable secara langsung atau melalui **Load variables from .env file**. Nama menu dapat berbeda antarversi. Referensi: [Menambahkan stack Portainer](https://docs.portainer.io/sts/user/docker/stacks/add).

### 6. Isi environment variables

Pada bagian **Environment variables**, isi nilai berikut:

| Variable | Nilai atau contoh |
| --- | --- |
| `IMAGE_REPOSITORY` | `ghcr.io/kominfo-jombang/app-031-uji-kir` |
| `APP_HOST_PORT` | `8031`, jika belum digunakan |
| `APP_DATA_PATH` | `/home/kominfo2025/data_app/app-031-uji-kir/staging` |
| `APP_NAME` | `PKB SAMPANG` |
| `APP_TIMEZONE` | `Asia/Jakarta` |
| `DB_HOST` | IP/DNS PostgreSQL yang sebenarnya |
| `DB_PORT` | `5432` |
| `DB_DATABASE` | Nama database staging hasil restore |
| `DB_USERNAME` | User PostgreSQL aplikasi |
| `DB_PASSWORD` | Password PostgreSQL aplikasi |
| `SESSION_LIFETIME` | `7200` |
| `DB_SECOND_HOST` | Kosong jika koneksi kedua tidak dipakai |

Isi `IMAGE_REPOSITORY` **tanpa tag** karena YAML sudah menambahkan `:staging`.

Jika koneksi kedua digunakan, lengkapi `DB_SECOND_PORT`, `DB_SECOND_DATABASE`, `DB_SECOND_USERNAME`, dan `DB_SECOND_PASSWORD`.

Isi konfigurasi integrasi hanya jika digunakan:

- `KEMENHUB_URL` dan `KEMENHUB_TOKEN`.
- `CAMERA_FRONT_URL`, `CAMERA_REAR_URL`, `CAMERA_RIGHT_URL`, `CAMERA_LEFT_URL` untuk kamera CIS.
- `CAMERA_URL` untuk layar capture terpisah.
- `CAMERA_USERNAME` dan `CAMERA_PASSWORD`, satu pasangan untuk semua kamera.
- `FONNTE_TOKEN` dan `QONTAK_TOKEN`.

URL kamera harus berupa URL HTTP(S) lengkap tanpa credential di URL, dan server harus memiliki akses jaringan ke kamera. Untuk staging, gunakan konfigurasi sandbox atau kosong.

Referensi lengkap tersedia pada [.env.example](.env.example). File `.env` tidak otomatis dibaca Yii; stack meneruskan variabel yang didefinisikan pada bagian `environment` ke container. `APP_ENV`, `APP_DEBUG`, dan `SESSION_AUTO_CREATE` sudah ditetapkan langsung dalam YAML sehingga menambahkan variabel dengan nama tersebut di UI tidak mengganti nilai literal YAML.

### 7. Deploy stack

Klik **Deploy the stack**, lalu buka container aplikasi dari halaman stack. Pastikan container `running`, health berubah menjadi `healthy`, dan logs tidak menunjukkan error startup atau permission.

Stack menyertakan label Watchtower aktif. Untuk pengujian awal dengan pembaruan manual, ubah label di editor menjadi:

```yaml
labels:
  com.centurylinklabs.watchtower.enable: "false"
```

Stack tidak memasang Watchtower. Perilaku pembaruan mengikuti konfigurasi Watchtower yang sudah ada di server. Jika nantinya digunakan, pastikan Watchtower juga memiliki credential untuk menarik image private GHCR; credential Portainer tidak otomatis menjadi credential Watchtower.

### 8. Periksa HTTP dan database

Buka endpoint berikut dengan mengganti `IP_SERVER_STAGING`:

```text
http://IP_SERVER_STAGING:8031/site/health
```

Respons yang diharapkan:

```json
{"status":"ok"}
```

Kemudian buka aplikasi:

```text
http://IP_SERVER_STAGING:8031/
```

Health hanya memeriksa PHP/Yii, bukan koneksi database. Untuk memeriksa database staging, buka **container aplikasi → Console**, pilih `/bin/sh`, lalu jalankan:

```bash
cd /var/www/html
php tests/smoke/database.php
```

Hasil yang diharapkan:

```text
PASS: DB connection and core tables (not a full business-schema or UAT check)
```

Script tersebut bersifat read-only, khusus `APP_ENV=staging`, dan tidak membuat tabel otomatis. Kelulusannya belum membuktikan seluruh schema maupun proses bisnis benar.

### 9. Pasang domain dan HTTPS

Pada reverse proxy yang digunakan:

1. Arahkan domain staging ke aplikasi.
2. Jika reverse proxy berada di luar network Docker, gunakan `IP_SERVER_STAGING:8031` sebagai upstream yang dapat dijangkaunya.
3. Pasang sertifikat TLS untuk domain.
4. Pastikan DNS domain mengarah ke reverse proxy.
5. Ulangi pengecekan health dan login melalui HTTPS.

### 10. Uji aplikasi staging

Verifikasi alur berikut dengan data pengujian:

- Login, logout, session, dan hak akses menu.
- Pencarian dan pendaftaran kendaraan.
- Pemeriksaan, hasil uji, dan cetak laporan.
- Impor/ekspor Excel dan PDF.
- Foto kamera, Android, dan integrasi yang digunakan.
- Upload dan unduhan berkas.

Recreate container melalui Portainer, lalu pastikan berkas upload dan hasil laporan tetap tersedia untuk memverifikasi volume persisten. Backup database dan berkas secara konsisten, lalu uji restore sebelum promosi production.

### 11. Deploy production

Setelah staging lulus, pastikan GitHub environment `production` memiliki **Required reviewers** sesuai kebijakan organisasi. Deklarasi `environment: production` dalam workflow tidak mengaktifkan reviewer dengan sendirinya.

Promosikan commit staging yang telah diuji melalui PR `staging → production`, lalu merge sesuai aturan repository. Jika branch remote production belum ada, pastikan pengaturan approval sudah selesai sebelum push pertama branch tersebut. Tunggu approval dan workflow production berhasil sehingga image berikut tersedia:

```text
ghcr.io/kominfo-jombang/app-031-uji-kir:production
```

Pada environment Portainer `server-prod-9`, ulangi persiapan registry, network, direktori, dan database. Sesuaikan nama environment jika berbeda pada instalasi aktual.

| Pengaturan | Nilai production |
| --- | --- |
| Nama stack | `app-031-uji-kir-production` |
| File stack | [deploy/stack.production.yml](deploy/stack.production.yml) |
| `IMAGE_REPOSITORY` | `ghcr.io/kominfo-jombang/app-031-uji-kir` |
| `APP_DATA_PATH` | `/home/kominfo2025/data_app/app-031-uji-kir/production` |
| Database | Database production |
| Port host | Port yang belum digunakan pada host production |
| Domain | Domain production |

Gunakan direktori dan database terpisah dari staging. Buat subdirektori dan permission seperti langkah staging dengan mengganti bagian path `staging` menjadi `production`.

Sebelum perpindahan layanan:

1. Backup database dan berkas aplikasi lama.
2. Hentikan sementara penulisan pada aplikasi lama saat sinkronisasi terakhir agar data tidak berbeda.
3. Selesaikan sinkronisasi database dan berkas ke target.
4. Deploy stack production menggunakan YAML production.
5. Periksa health, login, hak akses, cetak, dan integrasi. Script `tests/smoke/database.php` tidak dijalankan pada production karena khusus staging.
6. Arahkan layanan/domain ke aplikasi baru dan verifikasi kembali melalui HTTPS.
7. Pantau logs, penggunaan resource, dan keberhasilan transaksi aplikasi.

Jangan aktifkan cron notifikasi lama sebelum penerima, token, dan jadwal diverifikasi. Jika menggunakan Watchtower production, aktifkan setelah mekanisme approval dan akses registry terbukti bekerja.

### 12. Pembaruan dan rollback

Alur pembaruan adalah `main → staging → pengujian → production → approval → publikasi image`. Untuk pembaruan manual di Portainer, buka stack pada tab **Editor**, pilih **Update the stack**, dan aktifkan opsi pull ulang image jika tersedia. Referensi: [Mengedit stack Portainer](https://docs.portainer.io/user/docker/stacks/edit).

Catat digest image setiap rilis yang berhasil. Untuk rollback, ganti baris image dengan digest rilis stabil:

```yaml
image: ghcr.io/kominfo-jombang/app-031-uji-kir@sha256:DIGEST_RILIS_STABIL
```

Ganti `DIGEST_RILIS_STABIL` dengan digest yang sebenarnya, redeploy, lalu ulangi pemeriksaan aplikasi. Tag SHA tujuh karakter juga tersedia dari workflow, tetapi digest lebih pasti karena tag dapat tertimpa ketika workflow commit yang sama dijalankan ulang.

Rollback image tidak mengembalikan mutasi database. Pemulihan data membutuhkan backup dan prosedur restore tersendiri.

### Pemecahan masalah

| Gejala | Pemeriksaan |
| --- | --- |
| `manifest unknown` | Tag image belum tersedia; periksa workflow dan nama repository image |
| `unauthorized` / `denied` | Credential registry, scope token, dan akses akun ke package |
| `network app-network ... not found` | Buat network pada environment Docker target |
| `port is already allocated` | Gunakan `APP_HOST_PORT` yang belum terpakai |
| Koneksi database gagal | Host, port, credential, firewall, dan izin PostgreSQL |
| `YiiSession` tidak ditemukan | Kelengkapan restore, schema, dan hak akses tabel |
| `Permission denied` | Ownership dan permission direktori volume beserta direktori induk |
| Template cetak hilang | Isi volume `template_laporan` dengan template aplikasi lama |
| Kamera gagal / respons 503 | URL kamera, credential, serta rute jaringan/VPN server ke kamera |
| Container `unhealthy` | Logs startup dan respons `/site/health` dari dalam container |

Deployment selesai setelah aplikasi dapat diakses melalui HTTPS, alur bisnis utama lulus, data bertahan setelah recreate, serta backup dan prosedur rollback tersedia. Status health saja belum membuktikan kesiapan operasional.
