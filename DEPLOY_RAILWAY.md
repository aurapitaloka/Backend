# Deploy Laravel ke Railway

## 1. Hubungkan repo

- Buat project baru di Railway.
- Pilih `Deploy from GitHub Repo`.
- Pilih repo backend ini.

## 2. Tambahkan database

- Di project Railway, tambahkan service MySQL atau PostgreSQL.
- Setelah database aktif, buka service backend lalu pastikan backend mendapat environment variables database dari Railway.

## 3. Set environment variables backend

Minimal isi:

- `APP_NAME=Ruma`
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://domain-backend-kamu.up.railway.app`
- `APP_KEY=base64:...`
- `LOG_CHANNEL=stack`
- `LOG_LEVEL=error`

Yang disarankan untuk awal deploy:

- `SESSION_DRIVER=file`
- `CACHE_STORE=file`
- `QUEUE_CONNECTION=sync`
- `FILESYSTEM_DISK=public`

Jika pakai database Railway:

- `DB_CONNECTION=mysql` atau `pgsql`
- `DB_HOST=...`
- `DB_PORT=...`
- `DB_DATABASE=...`
- `DB_USERNAME=...`
- `DB_PASSWORD=...`

## 4. Build dan start command

Railway bisa membaca `Procfile`:

- Start: `php artisan serve --host=0.0.0.0 --port=$PORT`

Jika Railway meminta build command manual, pakai:

- `composer install --no-dev --optimize-autoloader`
- `php artisan config:clear`
- `php artisan route:clear`
- `php artisan view:clear`
- `php artisan migrate --force`
- `php artisan storage:link`
- `php artisan config:cache`
- `php artisan route:cache`
- `php artisan view:cache`

## 5. Kalau deploy sukses tapi HTTP 500

Cek urutan ini:

1. `APP_KEY` ada.
2. `APP_URL` sesuai domain Railway.
3. Database variable sudah masuk semua.
4. `php artisan migrate --force` sudah dijalankan.
5. Untuk troubleshooting awal, pakai:
   - `SESSION_DRIVER=file`
   - `CACHE_STORE=file`
   - `QUEUE_CONNECTION=sync`
6. Lihat log runtime Railway, bukan cuma build log.

## 6. Gejala umum

- `No application encryption key has been specified.`  
  `APP_KEY` belum ada.

- `SQLSTATE...`  
  Database belum terhubung atau migration belum dijalankan.

- `table sessions/cache/jobs doesn't exist`  
  Migration belum jalan, atau driver masih `database`.

- `route:cache` gagal  
  Ada bentrok nama route.
