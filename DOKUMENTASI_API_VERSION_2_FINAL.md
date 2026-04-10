# Dokumentasi API Version 2 (Final) - AKSES Backend

Dokumen ini merangkum **API JSON** dan **endpoint Web (HTML)** terbaru, termasuk fitur **Kuis**, **Catatan**, dan **Ulasan**.

**Base URL**
```
http://127.0.0.1:8000
```

**Catatan penting**
1. Endpoint di `routes/api.php` mengembalikan JSON (wajib `Accept: application/json`).
2. Banyak fitur baru (Kuis, Catatan, Ulasan) saat ini masih **Web/HTML** di `routes/web.php`. Response berupa HTML atau redirect, bukan JSON.
3. Jika Anda butuh **API JSON** untuk fitur Kuis/Catatan/Ulasan, perlu menambah route + controller API baru.

**Header umum (API JSON)**
```
Accept: application/json
Authorization: Bearer <token>
```

---

**A) Autentikasi (API JSON)**

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
    "asr_lang": "id-ID",
    "tts_lang": "id-ID",
    "tts_rate": 1.0,
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

**B) Materi (API JSON)**

`GET /api/materi?page=1&per_page=10`
Response 200:
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

`GET /api/dashboard/materi`
`POST /api/dashboard/materi`
`GET /api/dashboard/materi/{id}`
`PUT /api/dashboard/materi/{id}`
`DELETE /api/dashboard/materi/{id}`
Respons sama dengan endpoint `materi` di atas.

---

**C) Fiksi (API JSON)**

`GET /api/fiksi?page=1&per_page=10`
Response 200:
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

**D) Level (API JSON)**

`GET /api/level?page=1&per_page=10`
Response 200:
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

`POST /api/level`
Body:
```json
{ "nama": "Kelas 8", "deskripsi": "Level untuk kelas 8", "status_aktif": true }
```
Response 201:
```json
{ "id": 3, "nama": "Kelas 8", "deskripsi": "Level untuk kelas 8", "status_aktif": true }
```

`GET /api/level/{id}`
Response 200:
```json
{ "id": 2, "nama": "Kelas 7", "deskripsi": null, "status_aktif": true }
```

`PUT /api/level/{id}`
Response 200:
```json
{ "id": 3, "nama": "Kelas 8", "deskripsi": "Updated", "status_aktif": true }
```

`DELETE /api/level/{id}`
Response 204 (no content)

`GET /api/level/aktif`
Catatan: method `aktif` belum tersedia di controller.

---

**E) Mata Pelajaran (API JSON)**

`GET /api/mata-pelajaran`
`POST /api/mata-pelajaran`
`GET /api/mata-pelajaran/{id}`
`PUT /api/mata-pelajaran/{id}`
`DELETE /api/mata-pelajaran/{id}`

Catatan: controller saat ini **mengembalikan HTML**, bukan JSON.

`GET /api/mata-pelajaran/aktif`
Catatan: method `aktif` belum tersedia di controller.

---

**F) Pengguna (API JSON)**

`GET /api/pengguna`
`POST /api/pengguna`
`GET /api/pengguna/{id}`
`PUT /api/pengguna/{id}`
`DELETE /api/pengguna/{id}`

Catatan: controller saat ini **mengembalikan HTML**, bukan JSON.

---

**G) Profile (API JSON)**

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
Body:
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
Response 200:
```json
{
  "message": "Foto profil berhasil diupload!",
  "foto_profil": "uploads/profiles/1700001234_12_xxx.jpg"
}
```

`PUT /api/dashboard/profile/password`
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

**H) Sesi Baca (API JSON)**

`GET /api/dashboard/sesi-baca?page=1&per_page=10`
Response 200:
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

**I) Rak Buku (API JSON)**

`GET /api/dashboard/rak-buku?page=1&per_page=10`
Response 200:
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
Body:
```json
{ "materi_id": 42 }
```
Response 201:
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

**J) Kuis (Web/HTML)**

Endpoint Kuis saat ini ada di `routes/web.php` dan **mengembalikan HTML**, bukan JSON.

Admin:
1. `GET /dashboard/kuis` -> 200 HTML `dashboard.kuis.index`
2. `GET /dashboard/kuis/create` -> 200 HTML `dashboard.kuis.create`
3. `POST /dashboard/kuis` -> 302 redirect ke `/dashboard/kuis` dengan flash `success: "Kuis berhasil dibuat."`
4. `GET /dashboard/kuis/{kuis}` -> 200 HTML `dashboard.kuis.show`
5. `GET /dashboard/kuis/{kuis}/edit` -> 200 HTML `dashboard.kuis.edit`
6. `PUT /dashboard/kuis/{kuis}` -> 302 redirect ke `/dashboard/kuis` dengan flash `success: "Kuis berhasil diperbarui."`
7. `DELETE /dashboard/kuis/{kuis}` -> 302 redirect ke `/dashboard/kuis` dengan flash `success: "Kuis berhasil dihapus."`
8. `GET /dashboard/kuis-hasil` -> 200 HTML `dashboard.kuis.hasil`
9. `GET /dashboard/kuis-hasil/{hasil}` -> 200 HTML `dashboard.kuis.hasil-show`
10. `POST /dashboard/kuis-hasil/{hasil}` -> 302 redirect dengan flash `success: "Koreksi disimpan."`

Payload `POST/PUT /dashboard/kuis` (multipart/form-data):
- `judul` (required)
- `materi_id` (optional)
- `deskripsi` (optional)
- `status_aktif` (checkbox)
- `pertanyaan` (array, min 1)
- `pertanyaan.*.teks` (required)
- `pertanyaan.*.tipe` (required: `pilihan|essay|listening|speaking`)
- `pertanyaan.*.opsi.A`-`D` (required untuk `pilihan` dan `listening`)
- `pertanyaan.*.benar` (A/B/C/D untuk `pilihan` dan `listening`)
- `pertanyaan.*.jawaban_teks` (required untuk `essay` dan `speaking`)
- `pertanyaan.*.keyword` (required untuk `essay`)
- `pertanyaan.*.audio_text` (required untuk `listening` atau `speaking` jika tidak ada audio file)
- `pertanyaan.*.bahasa` (`id-ID` atau `en-US`)
- `pertanyaan_audio[]` (file audio mp3/wav/ogg, optional)

Contoh response implementasi (Web):
```
HTTP/1.1 302 Found
Location: /dashboard/kuis
Set-Cookie: laravel_session=...
```
Flash message di session:
```
success: "Kuis berhasil dibuat."
```

Siswa:
1. `GET /dashboard-siswa/kuis` -> 200 HTML `dashboard.siswa.kuis-index`
2. `GET /dashboard-siswa/kuis/{kuis}` -> 200 HTML `dashboard.siswa.kuis`
3. `POST /dashboard-siswa/kuis/{kuis}` -> 302 redirect dengan flash `success: "Kuis selesai. Skor kamu: <angka>"`
4. `GET /dashboard-siswa/materi/{materi}/kuis` -> 200 HTML `dashboard.siswa.kuis`
5. `POST /dashboard-siswa/materi/{materi}/kuis` -> 302 redirect dengan flash `success: "Kuis selesai. Skor kamu: <angka>"`
6. `GET /dashboard-siswa/riwayat/kuis/{hasil}` -> 200 HTML `dashboard.siswa.riwayat-kuis-show`

Payload `POST /dashboard-siswa/*/kuis`:
- `jawaban[pertanyaan_id] = opsi_id` (untuk `pilihan` dan `listening`)
- `jawaban_teks[pertanyaan_id] = "..."` (untuk `essay` dan `speaking`)

---

**K) Kuis (API JSON)**

Endpoint Kuis JSON berada di `routes/api.php` dan membutuhkan auth token.

`GET /api/dashboard-siswa/kuis`
Response 200:
```json
{
  "kuis_umum": [
    {
      "id": 5,
      "materi_id": null,
      "judul": "Kuis Umum 1",
      "deskripsi": "Latihan umum",
      "status_aktif": true,
      "dibuat_oleh": 1,
      "created_at": "2026-02-01T10:00:00.000000Z",
      "updated_at": "2026-02-01T10:00:00.000000Z",
      "pertanyaan_count": 10,
      "materi": null
    }
  ],
  "kuis_materi": [
    {
      "id": 7,
      "materi_id": 42,
      "judul": "Kuis Materi 42",
      "deskripsi": "Latihan materi",
      "status_aktif": true,
      "dibuat_oleh": 1,
      "created_at": "2026-02-01T10:00:00.000000Z",
      "updated_at": "2026-02-01T10:00:00.000000Z",
      "pertanyaan_count": 8,
      "materi": { "id": 42, "judul": "Pengantar Matematika" }
    }
  ],
  "progress_map": { "42": { "progres": 80, "selesai": 1 } },
  "completed_materi_ids": [42]
}
```

`GET /api/dashboard-siswa/kuis/{kuis}`
Response 200:
```json
{
  "kuis": {
    "id": 5,
    "materi_id": null,
    "judul": "Kuis Umum 1",
    "deskripsi": "Latihan umum",
    "status_aktif": true,
    "pertanyaan": [
      {
        "id": 91,
        "pertanyaan": "2+2=?",
        "urutan": 1,
        "tipe": "pilihan",
        "audio_path": null,
        "audio_text": null,
        "bahasa": "id-ID",
        "opsi": [
          { "id": 401, "label": "A", "teks": "3" },
          { "id": 402, "label": "B", "teks": "4" }
        ]
      }
    ]
  }
}
```
Catatan: field jawaban benar disembunyikan.

`GET /api/dashboard-siswa/materi/{materi}/kuis`
Response 200:
```json
{
  "materi": { "id": 42, "judul": "Pengantar Matematika", "level_id": 2 },
  "kuis": { "id": 7, "judul": "Kuis Materi 42", "pertanyaan": [/* ... */] }
}
```
Response 403 (materi belum selesai):
```json
{ "message": "Selesaikan materi terlebih dahulu sebelum mengerjakan kuis.", "materi_id": 42 }
```

`POST /api/dashboard-siswa/kuis/{kuis}`
`POST /api/dashboard-siswa/materi/{materi}/kuis`
Body:
```json
{
  "jawaban": { "91": 402 },
  "jawaban_teks": { "93": "jawaban essay" }
}
```
Response 200:
```json
{
  "message": "Kuis selesai.",
  "hasil_id": 15,
  "skor": 80,
  "total_benar": 8,
  "total_pertanyaan": 10
}
```

`GET /api/dashboard-siswa/riwayat/kuis?kuis_sort=latest&per_page=8`
Response 200:
```json
{
  "current_page": 1,
  "data": [
    {
      "hasil_id": 15,
      "kuis_judul": "Kuis Umum 1",
      "materi_id": null,
      "materi_judul": null,
      "skor": 80,
      "total_benar": 8,
      "total_pertanyaan": 10,
      "selesai_at": "2026-02-02T10:00:00.000000Z",
      "has_pending": 0
    }
  ],
  "per_page": 8,
  "total": 1
}
```

`GET /api/dashboard-siswa/riwayat/kuis/{hasil}`
Response 200:
```json
{
  "hasil": {
    "id": 15,
    "kuis_id": 5,
    "pengguna_id": 12,
    "skor": 80,
    "total_benar": 8,
    "total_pertanyaan": 10,
    "selesai_at": "2026-02-02T10:00:00.000000Z",
    "kuis": { "id": 5, "judul": "Kuis Umum 1" },
    "jawaban": [
      {
        "id": 900,
        "pertanyaan_id": 91,
        "opsi_id": 402,
        "benar": true,
        "status_koreksi": null
      }
    ]
  }
}
```

---

**L) Catatan Siswa (API JSON)**

`GET /api/dashboard-siswa/catatan?per_page=8`
Response 200:
```json
{
  "current_page": 1,
  "data": [
    {
      "id": 11,
      "pengguna_id": 12,
      "materi_id": 42,
      "isi": "Catatan penting",
      "created_at": "2026-02-02T10:00:00.000000Z",
      "updated_at": "2026-02-02T10:00:00.000000Z",
      "materi": { "id": 42, "judul": "Pengantar Matematika" }
    }
  ],
  "per_page": 8,
  "total": 1
}
```

`GET /api/dashboard-siswa/catatan?with_materi_list=1`
Response 200:
```json
{
  "catatan": { /* pagination catatan */ },
  "materi_list": [
    { "id": 42, "judul": "Pengantar Matematika", "level_id": 2 }
  ]
}
```

`POST /api/dashboard-siswa/catatan`
Body:
```json
{ "materi_id": 42, "isi": "Catatan penting" }
```
Response 201:
```json
{
  "message": "Catatan berhasil disimpan.",
  "data": {
    "id": 11,
    "pengguna_id": 12,
    "materi_id": 42,
    "isi": "Catatan penting"
  }
}
```

`DELETE /api/dashboard-siswa/catatan/{catatan}`
Response 200:
```json
{ "message": "Catatan dihapus." }
```

---

**M) Catatan Siswa (Web/HTML)**

1. `GET /dashboard-siswa/catatan` -> 200 HTML `dashboard.siswa.catatan`
2. `POST /dashboard-siswa/catatan` -> 302 redirect dengan flash `success: "Catatan berhasil disimpan."`
3. `DELETE /dashboard-siswa/catatan/{catatan}` -> 302 redirect dengan flash `success: "Catatan dihapus."`

Payload `POST /dashboard-siswa/catatan`:
- `materi_id` (optional)
- `isi` (required, max 5000)

Contoh response implementasi (Web):
```
HTTP/1.1 302 Found
Location: /dashboard-siswa/catatan
```
Flash message:
```
success: "Catatan berhasil disimpan."
```

---

**N) Ulasan (Web + JSON)**

`POST /ulasan`
Body:
```json
{
  "nama": "Budi",
  "email": "budi@mail.com",
  "rating": 5,
  "isi": "Aplikasinya membantu sekali."
}
```
Response 200 (jika `Accept: application/json`):
```json
{ "message": "Ulasan berhasil dikirim. Terima kasih!" }
```
Response Web (tanpa JSON):
```
HTTP/1.1 302 Found
Location: /
```
Flash message:
```
review_success: "Ulasan berhasil dikirim. Terima kasih!"
```

Admin Ulasan (Web/HTML):
1. `GET /dashboard/ulasan` -> 200 HTML `dashboard.ulasan.index`
2. `GET /dashboard/ulasan/export` -> 200 CSV download
3. `DELETE /dashboard/ulasan/{ulasan}` -> 302 redirect dengan flash `success: "Ulasan berhasil dihapus."`

---

**O) Pengaturan Suara Siswa (Web/HTML)**

`POST /dashboard-siswa/pengaturan`
Payload:
- `asr_lang` (required: `id-ID|en-US`)
- `tts_lang` (required: `id-ID|en-US`)
- `tts_rate` (required: 0.6 - 1.4)
- `auto_voice_nav` (optional checkbox)
Response:
```
HTTP/1.1 302 Found
Location: /dashboard-siswa/pengaturan
```
Flash message:
```
success: "Pengaturan tersimpan."
```

---

**P) Error Response Umum (API JSON)**

Jika validasi gagal:
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "field": ["Pesan error validasi"]
  }
}
```
