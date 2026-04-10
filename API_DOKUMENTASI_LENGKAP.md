# API Dokumentasi Lengkap - AKSES Backend

Dokumentasi ini merangkum seluruh endpoint di `routes/api.php` beserta contoh request dan contoh respons sesuai implementasi controller saat ini.

**Base URL**
```
http://127.0.0.1:8000
```

**Auth**
- Semua endpoint dalam grup `auth:sanctum` butuh header `Authorization: Bearer <token>`.
- Token didapat dari `POST /api/login`.
- Gunakan `Accept: application/json` untuk respons JSON.

**Header umum**
```
Accept: application/json
Authorization: Bearer <token>
```

**Catatan penting**
- Beberapa controller masih mengembalikan HTML view, meskipun diakses via `/api/*`. Endpoint tersebut diberi catatan khusus di bawah.
- Endpoint `/api/dashboard` dan `/api/level/aktif` serta `/api/mata-pelajaran/aktif` saat ini belum ada method pada controller, sehingga akan error jika dipanggil.

---

**1) Auth**

`POST /api/login`
```
Content-Type: application/json
```
Body:
```json
{
  "email": "superadmin@akses.com",
  "kata_sandi": "password"
}
```
Response 200:
```json
{
  "message": "Login berhasil",
  "token": "1|abcdef...",
  "user": {
    "id": 1,
    "nama": "Super Admin",
    "email": "superadmin@akses.com",
    "peran": "guru"
  }
}
```
Response 401:
```json
{ "message": "Email atau kata sandi salah." }
```

`POST /api/register`
```
Content-Type: application/json
```
Body:
```json
{
  "nama": "Budi",
  "email": "budi@mail.com",
  "kata_sandi": "password",
  "kata_sandi_konfirmasi": "password"
}
```
Response 201:
```json
{
  "message": "Registrasi berhasil",
  "user": {
    "id": 12,
    "nama": "Budi",
    "email": "budi@mail.com",
    "peran": "siswa"
  }
}
```

`GET /api/user`
Response 200:
```json
{
  "user": {
    "id": 12,
    "nama": "Budi",
    "email": "budi@mail.com",
    "peran": "siswa",
    "foto_profil": "uploads/profiles/1700000000_12_xxx.jpg",
    "asr_lang": null,
    "tts_lang": null,
    "tts_rate": null,
    "auto_voice_nav": false,
    "siswa": {
      "pengguna_id": 12,
      "nama_sekolah": "SMP 1",
      "jenjang": "SMP",
      "level_id": 2,
      "catatan": null
    },
    "guru": null
  }
}
```

`POST /api/logout`
Response 200:
```json
{ "message": "Logout berhasil" }
```

---

**2) Dashboard**

`GET /api/dashboard`
- Catatan: `DashboardController::apiIndex` belum ada. Endpoint ini akan error jika dipanggil.

---

**3) Materi**

`GET /api/materi?page=1&per_page=10`
Response 200 (paginasi):
```json
{
  "current_page": 1,
  "data": [
    {
      "id": 42,
      "judul": "Pengantar Matematika",
      "deskripsi": "Materi dasar",
      "mata_pelajaran_id": 3,
      "level_id": 2,
      "tipe_konten": "file",
      "konten_teks": null,
      "file_path": "materi/1700000000_matematika.pdf",
      "cover_path": "materi/covers/1700000000_cover.jpg",
      "jumlah_halaman": 30,
      "status_aktif": true,
      "dibuat_oleh": 1,
      "created_at": "2026-01-01T10:00:00.000000Z",
      "updated_at": "2026-01-01T10:00:00.000000Z",
      "pengguna": {
        "id": 1,
        "nama": "Super Admin",
        "email": "superadmin@akses.com"
      },
      "level": {
        "id": 2,
        "nama": "Kelas 7",
        "deskripsi": null,
        "status_aktif": true
      },
      "mata_pelajaran": {
        "id": 3,
        "nama": "Matematika",
        "deskripsi": null,
        "status_aktif": true
      }
    }
  ],
  "per_page": 10,
  "total": 1
}
```

`GET /api/materi/{id}`
Response 200:
```json
{
  "id": 42,
  "judul": "Pengantar Matematika",
  "deskripsi": "Materi dasar",
  "mata_pelajaran_id": 3,
  "level_id": 2,
  "tipe_konten": "file",
  "konten_teks": null,
  "file_path": "materi/1700000000_matematika.pdf",
  "cover_path": "materi/covers/1700000000_cover.jpg",
  "jumlah_halaman": 30,
  "status_aktif": true,
  "dibuat_oleh": 1,
  "created_at": "2026-01-01T10:00:00.000000Z",
  "updated_at": "2026-01-01T10:00:00.000000Z",
  "pengguna": { "id": 1, "nama": "Super Admin", "email": "superadmin@akses.com" },
  "level": { "id": 2, "nama": "Kelas 7", "deskripsi": null, "status_aktif": true },
  "mata_pelajaran": { "id": 3, "nama": "Matematika", "deskripsi": null, "status_aktif": true }
}
```

`POST /api/materi`
```
Content-Type: multipart/form-data
```
Body (form-data):
- `judul` (required)
- `deskripsi`
- `mata_pelajaran_id`
- `level_id`
- `tipe_konten` (`teks` atau `file`)
- `konten_teks` (required jika `tipe_konten=teks`)
- `file_path` (file, required jika `tipe_konten=file`)
- `cover_path` (file gambar, optional)
- `jumlah_halaman`
- `status_aktif` (boolean)

Response 201:
```json
{ "message": "Materi berhasil ditambahkan!" }
```

`PUT /api/materi/{id}`
```
Content-Type: multipart/form-data
```
Response 200:
```json
{
  "message": "Materi berhasil diperbarui!",
  "data": {
    "id": 42,
    "judul": "Pengantar Matematika Revisi",
    "deskripsi": "Materi dasar",
    "mata_pelajaran_id": 3,
    "level_id": 2,
    "tipe_konten": "file",
    "konten_teks": null,
    "file_path": "materi/1700009999_matematika.pdf",
    "cover_path": "materi/covers/1700009999_cover.jpg",
    "jumlah_halaman": 32,
    "status_aktif": true,
    "dibuat_oleh": 1,
    "created_at": "2026-01-01T10:00:00.000000Z",
    "updated_at": "2026-01-02T10:00:00.000000Z"
  }
}
```

`DELETE /api/materi/{id}`
Response 200:
```json
{ "message": "Materi berhasil dihapus!" }
```

---

**4) Materi (alias dashboard)**

`GET /api/dashboard/materi`
`POST /api/dashboard/materi`
`GET /api/dashboard/materi/{id}`
`PUT /api/dashboard/materi/{id}`
`DELETE /api/dashboard/materi/{id}`

Semua respons dan request sama dengan endpoint `materi` di atas.

---

**5) Fiksi**

`GET /api/fiksi?page=1&per_page=10`
Response 200 (paginasi):
```json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "judul_buku": "Petualangan Si Kancil",
      "penulis": "Anonim",
      "kategori": "Fabel",
      "tahun_terbit": 2020,
      "deskripsi": "Cerita fabel",
      "file_path": "fiksi/1700000000_buku.pdf",
      "jumlah_halaman": 50,
      "status_aktif": true,
      "dibuat_oleh": 1,
      "created_at": "2025-12-06T10:00:00.000000Z",
      "updated_at": "2025-12-06T10:00:00.000000Z",
      "pengguna": { "id": 1, "nama": "Super Admin", "email": "superadmin@akses.com" }
    }
  ],
  "per_page": 10,
  "total": 1
}
```

`GET /api/fiksi/{id}`
Response 200:
```json
{
  "id": 1,
  "judul_buku": "Petualangan Si Kancil",
  "penulis": "Anonim",
  "kategori": "Fabel",
  "tahun_terbit": 2020,
  "deskripsi": "Cerita fabel",
  "file_path": "fiksi/1700000000_buku.pdf",
  "jumlah_halaman": 50,
  "status_aktif": true,
  "dibuat_oleh": 1,
  "created_at": "2025-12-06T10:00:00.000000Z",
  "updated_at": "2025-12-06T10:00:00.000000Z",
  "pengguna": { "id": 1, "nama": "Super Admin", "email": "superadmin@akses.com" }
}
```

`POST /api/fiksi`
```
Content-Type: multipart/form-data
```
Body (form-data):
- `judul_buku` (required)
- `penulis` (required)
- `kategori`
- `tahun_terbit`
- `deskripsi`
- `file_path` (file pdf/doc/docx, optional)
- `jumlah_halaman`
- `status_aktif`

Response 201:
```json
{
  "id": 1,
  "judul_buku": "Petualangan Si Kancil",
  "penulis": "Anonim",
  "kategori": "Fabel",
  "tahun_terbit": 2020,
  "deskripsi": "Cerita fabel",
  "file_path": "fiksi/1700000000_buku.pdf",
  "jumlah_halaman": 50,
  "status_aktif": true,
  "dibuat_oleh": 1,
  "created_at": "2025-12-06T10:00:00.000000Z",
  "updated_at": "2025-12-06T10:00:00.000000Z",
  "pengguna": { "id": 1, "nama": "Super Admin", "email": "superadmin@akses.com" }
}
```

`PUT /api/fiksi/{id}`
Response 200:
```json
{
  "id": 1,
  "judul_buku": "Petualangan Si Kancil (Revisi)",
  "penulis": "Anonim",
  "kategori": "Fabel",
  "tahun_terbit": 2020,
  "deskripsi": "Cerita fabel",
  "file_path": "fiksi/1700009999_buku.pdf",
  "jumlah_halaman": 55,
  "status_aktif": true,
  "dibuat_oleh": 1,
  "created_at": "2025-12-06T10:00:00.000000Z",
  "updated_at": "2025-12-06T11:00:00.000000Z",
  "pengguna": { "id": 1, "nama": "Super Admin", "email": "superadmin@akses.com" }
}
```

`DELETE /api/fiksi/{id}`
Response 204 (no content)

---

**6) Level**

`GET /api/level?page=1&per_page=10`
Response 200 (paginasi):
```json
{
  "current_page": 1,
  "data": [
    { "id": 2, "nama": "Kelas 7", "deskripsi": null, "status_aktif": true }
  ],
  "per_page": 10,
  "total": 1
}
```

`GET /api/level/{id}`
Response 200:
```json
{ "id": 2, "nama": "Kelas 7", "deskripsi": null, "status_aktif": true }
```

`POST /api/level`
```
Content-Type: application/json
```
Body:
```json
{ "nama": "Kelas 8", "deskripsi": "Level untuk kelas 8", "status_aktif": true }
```
Response 201:
```json
{ "id": 3, "nama": "Kelas 8", "deskripsi": "Level untuk kelas 8", "status_aktif": true }
```

`PUT /api/level/{id}`
Response 200:
```json
{ "id": 3, "nama": "Kelas 8", "deskripsi": "Updated", "status_aktif": true }
```

`DELETE /api/level/{id}`
Response 204 (no content)

`GET /api/level/aktif`
- Catatan: method `aktif` belum ada di `LevelController`.

---

**7) Mata Pelajaran**

`GET /api/mata-pelajaran`
`POST /api/mata-pelajaran`
`GET /api/mata-pelajaran/{id}`
`PUT /api/mata-pelajaran/{id}`
`DELETE /api/mata-pelajaran/{id}`

Catatan: `MataPelajaranController` saat ini selalu merender HTML view dan tidak mengembalikan JSON. Endpoint `/api/mata-pelajaran` akan menghasilkan HTML, bukan JSON.

`GET /api/mata-pelajaran/aktif`
- Catatan: method `aktif` belum ada di `MataPelajaranController`.

---

**8) Pengguna**

`GET /api/pengguna`
`POST /api/pengguna`
`GET /api/pengguna/{id}`
`PUT /api/pengguna/{id}`
`DELETE /api/pengguna/{id}`

Catatan: `PenggunaController` saat ini selalu merender HTML view dan tidak mengembalikan JSON. Endpoint `/api/pengguna` akan menghasilkan HTML, bukan JSON.

---

**9) Profile**

`GET /api/dashboard/profile`
Response 200:
```json
{
  "user": {
    "id": 12,
    "nama": "Budi",
    "email": "budi@mail.com",
    "foto_profil": "uploads/profiles/1700000000_12_xxx.jpg",
    "peran": "siswa",
    "status_aktif": true,
    "siswa": { "pengguna_id": 12, "nama_sekolah": "SMP 1", "jenjang": "SMP", "level_id": 2, "catatan": null },
    "guru": null
  }
}
```

`PUT /api/dashboard/profile`
```
Content-Type: multipart/form-data
```
Body (form-data):
- `nama` (required)
- `email` (required)
- `foto_profil` (file gambar, optional)

Response 200:
```json
{
  "message": "Profile berhasil diperbarui!",
  "user": {
    "id": 12,
    "nama": "Budi",
    "email": "budi@mail.com",
    "foto_profil": "uploads/profiles/1700001234_12_xxx.jpg"
  }
}
```

`POST /api/dashboard/profile/upload-foto`
```
Content-Type: multipart/form-data
```
Body:
- `foto_profil` (required)

Response 200:
```json
{
  "message": "Foto profil berhasil diupload!",
  "foto_profil": "uploads/profiles/1700001234_12_xxx.jpg"
}
```

`PUT /api/dashboard/profile/password`
```
Content-Type: application/json
```
Body:
```json
{
  "kata_sandi_lama": "password",
  "kata_sandi_baru": "password123",
  "kata_sandi_konfirmasi": "password123"
}
```
Response 200:
```json
{ "message": "Kata sandi berhasil diubah!" }
```

---

**10) Sesi Baca**

`GET /api/dashboard/sesi-baca?page=1&per_page=10`
Response 200 (paginasi):
```json
{
  "current_page": 1,
  "data": [
    {
      "id": 8,
      "pengguna_id": 12,
      "materi_id": 42,
      "mulai": "2026-02-01 08:00:00",
      "selesai": "2026-02-01 08:20:00",
      "durasi_detik": 1200,
      "halaman_terakhir": 10,
      "progres_persen": 20,
      "gunakan_gaze": true,
      "gunakan_suara": false,
      "created_at": "2026-02-01T08:20:00.000000Z",
      "updated_at": "2026-02-01T08:20:00.000000Z",
      "materi": {
        "id": 42,
        "judul": "Pengantar Matematika",
        "file_path": "materi/1700000000_matematika.pdf"
      }
    }
  ],
  "per_page": 10,
  "total": 1
}
```

`GET /api/dashboard/sesi-baca/{materi}/last`
Response 200:
```json
{
  "id": 8,
  "pengguna_id": 12,
  "materi_id": 42,
  "mulai": "2026-02-01 08:00:00",
  "selesai": "2026-02-01 08:20:00",
  "durasi_detik": 1200,
  "halaman_terakhir": 10,
  "progres_persen": 20,
  "gunakan_gaze": true,
  "gunakan_suara": false,
  "created_at": "2026-02-01T08:20:00.000000Z",
  "updated_at": "2026-02-01T08:20:00.000000Z"
}
```
Response 404:
```json
{ "message": "Not found" }
```

`POST /api/dashboard/sesi-baca`
```
Content-Type: application/json
```
Body:
```json
{
  "materi_id": 42,
  "mulai": "2026-02-01 08:00:00",
  "selesai": "2026-02-01 08:20:00",
  "durasi_detik": 1200,
  "halaman_terakhir": 10,
  "progres_persen": 20,
  "gunakan_gaze": true,
  "gunakan_suara": false
}
```
Response 201:
```json
{
  "id": 8,
  "pengguna_id": 12,
  "materi_id": 42,
  "mulai": "2026-02-01 08:00:00",
  "selesai": "2026-02-01 08:20:00",
  "durasi_detik": 1200,
  "halaman_terakhir": 10,
  "progres_persen": 20,
  "gunakan_gaze": true,
  "gunakan_suara": false,
  "created_at": "2026-02-01T08:20:00.000000Z",
  "updated_at": "2026-02-01T08:20:00.000000Z"
}
```

`POST /api/dashboard/sesi-baca/upsert`
Response 200:
```json
{
  "id": 8,
  "pengguna_id": 12,
  "materi_id": 42,
  "mulai": "2026-02-01 08:00:00",
  "selesai": "2026-02-01 08:20:00",
  "durasi_detik": 1200,
  "halaman_terakhir": 12,
  "progres_persen": 24,
  "gunakan_gaze": true,
  "gunakan_suara": false,
  "created_at": "2026-02-01T08:20:00.000000Z",
  "updated_at": "2026-02-01T08:25:00.000000Z"
}
```

`PUT /api/dashboard/sesi-baca/{id}`
Response 200:
```json
{
  "id": 8,
  "pengguna_id": 12,
  "materi_id": 42,
  "mulai": "2026-02-01 08:00:00",
  "selesai": "2026-02-01 08:30:00",
  "durasi_detik": 1800,
  "halaman_terakhir": 15,
  "progres_persen": 30,
  "gunakan_gaze": true,
  "gunakan_suara": false,
  "created_at": "2026-02-01T08:20:00.000000Z",
  "updated_at": "2026-02-01T08:30:00.000000Z"
}
```

`DELETE /api/dashboard/sesi-baca/{id}`
Response 200:
```json
{ "message": "Deleted" }
```

---

**11) Rak Buku**

`GET /api/dashboard/rak-buku?page=1&per_page=10`
Response 200 (paginasi):
```json
{
  "current_page": 1,
  "data": [
    {
      "id": 7,
      "pengguna_id": 12,
      "materi_id": 42,
      "created_at": "2026-01-04T08:00:00.000000Z",
      "updated_at": "2026-01-04T08:00:00.000000Z",
      "materi": {
        "id": 42,
        "judul": "Pengantar Matematika",
        "file_path": "materi/1700000000_matematika.pdf"
      }
    }
  ],
  "per_page": 10,
  "total": 1
}
```

`POST /api/dashboard/rak-buku`
```
Content-Type: application/json
```
Body:
```json
{ "materi_id": 42 }
```
Response 201 (baru):
```json
{
  "id": 7,
  "pengguna_id": 12,
  "materi_id": 42,
  "created_at": "2026-01-04T08:00:00.000000Z",
  "updated_at": "2026-01-04T08:00:00.000000Z",
  "materi": {
    "id": 42,
    "judul": "Pengantar Matematika",
    "file_path": "materi/1700000000_matematika.pdf"
  }
}
```
Response 200 (sudah ada):
```json
{
  "message": "Already in rak buku",
  "data": {
    "id": 7,
    "pengguna_id": 12,
    "materi_id": 42,
    "created_at": "2026-01-04T08:00:00.000000Z",
    "updated_at": "2026-01-04T08:00:00.000000Z"
  }
}
```

`DELETE /api/dashboard/rak-buku/{materi}`
Response 200:
```json
{ "message": "Deleted" }
```

`GET /api/dashboard/rak-buku/{materi}/status`
Response 200:
```json
{ "in_rak": true }
```

---

**12) Error Response Umum (Validasi)**

Jika validasi gagal, Laravel mengembalikan:
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "field": ["Pesan error validasi"]
  }
}
```

