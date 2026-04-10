# API Documentation - AKSES Backend

## Base URL
```
http://127.0.0.1:8000
```

## Authentication

Aplikasi ini menggunakan **Session-based Authentication** dengan Laravel. Untuk frontend, disarankan menggunakan **API Token Authentication** atau **Sanctum**.

### Current Authentication Flow (Web)
1. Login melalui `POST /login`
2. Session akan dibuat dan disimpan di cookie
3. Semua request yang memerlukan auth harus menyertakan session cookie

### Recommended: API Token Authentication
Untuk integrasi frontend, disarankan menggunakan Laravel Sanctum atau API tokens.

---

## Endpoints

### 1. Authentication

#### Login
```http
POST /login
Content-Type: application/x-www-form-urlencoded
```

**Request Body:**
```
email: string (required)
kata_sandi: string (required)
ingat_sandi: boolean (optional) - untuk "Remember Me"
```
 
## Rak Buku (Bookshelf)

Dokumentasi endpoint untuk fitur "Rak Buku" — menambahkan/ menghapus/ mengecek materi di rak buku pengguna.

Semua endpoint berada di bawah middleware `auth:sanctum` (butuh session cookie atau Bearer token).

### 1) Tambah ke Rak Buku
```http
POST /api/dashboard/rak-buku
Content-Type: application/json
Authorization: Required
```

Request body (JSON):
```json
{
  "materi_id": 42
}
```

Success (201 Created):
```json
{
  "id": 7,
  "pengguna_id": 3,
  "materi_id": 42,
  "created_at": "2026-01-04T08:00:00Z"
}
```

Errors:
- 401 Unauthorized (jika tidak terautentikasi)
- 422 Unprocessable Entity (validasi gagal):

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "materi_id": ["The selected materi_id is invalid."]
  }
}
```

Example (fetch, Sanctum cookie auth):
```javascript
await fetch('/api/dashboard/rak-buku', {
  method: 'POST',
  credentials: 'include',
  headers: { 'Content-Type':'application/json', 'Accept':'application/json' },
  body: JSON.stringify({ materi_id: 42 })
});
```

curl example (Bearer token):
```bash
curl -X POST 'http://127.0.0.1:8000/api/dashboard/rak-buku' \
  -H 'Authorization: Bearer <token>' \
  -H 'Content-Type: application/json' \
  -d '{"materi_id":42}'
```

---

### 2) Hapus dari Rak Buku
```http
DELETE /api/dashboard/rak-buku/{materi}
Authorization: Required
```

Path parameter:
- `materi` — ID materi yang ingin dihapus dari rak buku.

Success (200):
```json
{ "message": "Deleted" }
```

Errors:
- 404 Not Found — jika entry tidak ada
- 401 Unauthorized — jika tidak terautentikasi

Example (fetch):
```javascript
await fetch(`/api/dashboard/rak-buku/${materiId}`, {
  method: 'DELETE',
  credentials: 'include',
  headers: { 'Accept':'application/json' }
});
```

curl example:
```bash
curl -X DELETE 'http://127.0.0.1:8000/api/dashboard/rak-buku/42' \
  -H 'Authorization: Bearer <token>'
```

---

### 3) Cek Status (apakah materi ada di rak buku)
```http
GET /api/dashboard/rak-buku/{materi}/status
Authorization: Required
```

Success (200):
```json
{ "in_rak": true }
```

Example:
```javascript
const res = await fetch(`/api/dashboard/rak-buku/${materiId}/status`, {
  credentials: 'include', headers: { 'Accept':'application/json' }
});
const { in_rak } = await res.json();
```

---

### 4) Daftar Rak Buku Pengguna (paginated)
```http
GET /api/dashboard/rak-buku?page=1&per_page=10
Authorization: Required
```

Response: standard Laravel pagination object, `data` berisi entry rak buku termasuk relation `materi`.

Example response snippet:
```json
{
  "current_page": 1,
  "data": [
    { "id": 7, "pengguna_id": 3, "materi_id": 42, "materi": { "id": 42, "judul": "Contoh" } }
  ],
  "per_page": 10,
  "total": 5
}
```

---

## Integration Notes
- All endpoints use `pengguna_id = Auth::id()` on server-side — frontend cannot manipulate other users' rak buku.
- For SPA using Sanctum: ensure you `GET /sanctum/csrf-cookie` and send `credentials: 'include'` on mutating requests.
- Handle 422 validation responses and show user-friendly messages.

Add these examples to frontend detail-materi page:
- On load — call `/api/dashboard/rak-buku/{materi}/status` to set the Add/Remove button state.
- On Add button click — call POST `/api/dashboard/rak-buku` and optimistically update UI.
- On Remove click — call DELETE and update UI.


---

### 2. Dashboard

#### Get Dashboard Data
```http
GET /dashboard
Authorization: Required (Session)
```

**Success Response:**
```json
{
  "user": {
    "id": 1,
    "nama": "Super Admin",
    "email": "superadmin@akses.com",
    "peran": "guru",
    "status_aktif": true
  },
  "totalMateri": 10,
  "totalPenggunaAktif": 5,
  "totalSesiBaca": 25,
  "greeting": "Selamat Pagi"
}
```

---

### 3. Materi (Materials)

#### Get All Materi
```http
GET /dashboard/materi
Authorization: Required
```

**Query Parameters:**
- `page` (optional): Page number for pagination (default: 1)
- `per_page` (optional): Items per page (default: 10)

**Success Response:**
```json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "judul": "Matematika Dasar",
      "deskripsi": "Materi pembelajaran matematika dasar",
      "mata_pelajaran_id": 1,
      "level_id": 1,
      "tipe_konten": "file",
      "konten_teks": null,
      "file_path": "materi/1234567890_file.pdf",
      "jumlah_halaman": 10,
      "status_aktif": true,
      "dibuat_oleh": 1,
      "created_at": "2025-12-06T10:00:00.000000Z",
      "updated_at": "2025-12-06T10:00:00.000000Z",
      "pengguna": {
        "id": 1,
        "nama": "Super Admin",
        "email": "superadmin@akses.com"
      },
      "level": {
        "id": 1,
        "nama": "Kelas 4 SD"
      },
      "mata_pelajaran": {
        "id": 1,
        "nama": "Matematika"
      }
    }
  ],
  "first_page_url": "http://127.0.0.1:8000/dashboard/materi?page=1",
  "last_page_url": "http://127.0.0.1:8000/dashboard/materi?page=1",
  "per_page": 10,
  "total": 1
}
```

#### Get Single Materi
```http
GET /dashboard/materi/{id}
Authorization: Required
```

**Success Response:**
```json
{
  "id": 1,
  "judul": "Matematika Dasar",
  "deskripsi": "Materi pembelajaran matematika dasar",
  "mata_pelajaran_id": 1,
  "level_id": 1,
  "tipe_konten": "file",
  "konten_teks": null,
  "file_path": "materi/1234567890_file.pdf",
  "jumlah_halaman": 10,
  "status_aktif": true,
  "dibuat_oleh": 1,
  "created_at": "2025-12-06T10:00:00.000000Z",
  "updated_at": "2025-12-06T10:00:00.000000Z",
  "pengguna": {
    "id": 1,
    "nama": "Super Admin"
  },
  "level": {
    "id": 1,
    "nama": "Kelas 4 SD"
  },
  "mata_pelajaran": {
    "id": 1,
    "nama": "Matematika"
  }
}
```

#### Create Materi
```http
POST /dashboard/materi
Content-Type: multipart/form-data
Authorization: Required
```

**Request Body (Form Data):**
```
judul: string (required, max:200)
deskripsi: string (optional)
mata_pelajaran_id: integer (optional, must exist in mata_pelajaran table)
level_id: integer (optional, must exist in level table)
tipe_konten: string (required, enum: "teks" | "file")
konten_teks: string (required if tipe_konten = "teks")
file_path: file (required if tipe_konten = "file", mimes: pdf,doc,docx, max:10240KB)
jumlah_halaman: integer (optional, min:1)
status_aktif: boolean (optional, default: true)
```

**Success Response (302 Redirect):**
```
Location: /dashboard/materi
```

**Error Response:**
```json
{
  "errors": {
    "judul": ["Judul wajib diisi"],
    "tipe_konten": ["Tipe konten wajib dipilih"],
    "file_path": ["File wajib diupload jika tipe konten adalah file"]
  }
}
```

**Example:**
```javascript
const formData = new FormData();
formData.append('judul', 'Matematika Dasar');
formData.append('deskripsi', 'Materi pembelajaran matematika');
formData.append('mata_pelajaran_id', '1');
formData.append('level_id', '1');
formData.append('tipe_konten', 'file');
formData.append('file_path', fileInput.files[0]);
formData.append('jumlah_halaman', '10');
formData.append('status_aktif', '1');
formData.append('_token', csrfToken);

fetch('http://127.0.0.1:8000/dashboard/materi', {
  method: 'POST',
  body: formData,
  credentials: 'include'
});
```

#### Update Materi
```http
PUT /dashboard/materi/{id}
Content-Type: multipart/form-data
Authorization: Required
```

**Request Body:** Same as Create, but all fields are optional

**Success Response (302 Redirect):**
```
Location: /dashboard/materi
```

#### Delete Materi
```http
DELETE /dashboard/materi/{id}
Authorization: Required
```

**Request Body:**
```
_token: string (CSRF token)
```

**Success Response (302 Redirect):**
```
Location: /dashboard/materi
```

---

### 4. Fiksi (Fiction)

#### Get All Fiksi
```http
GET /dashboard/fiksi
Authorization: Required
```

**Query Parameters:**
- `page` (optional): Page number
- `per_page` (optional): Items per page

**Success Response:** Similar to Materi response

#### Get Single Fiksi
```http
GET /dashboard/fiksi/{id}
Authorization: Required
```

**Success Response:**
```json
{
  "id": 1,
  "judul_buku": "Petualangan Si Kancil",
  "penulis": "Anonim",
  "kategori": "Fabel",
  "tahun_terbit": 2020,
  "deskripsi": "Cerita fabel tentang kancil",
  "file_path": "fiksi/1234567890_buku.pdf",
  "jumlah_halaman": 50,
  "status_aktif": true,
  "dibuat_oleh": 1,
  "created_at": "2025-12-06T10:00:00.000000Z",
  "updated_at": "2025-12-06T10:00:00.000000Z",
  "pengguna": {
    "id": 1,
    "nama": "Super Admin"
  }
}
```

#### Create Fiksi
```http
POST /dashboard/fiksi
Content-Type: multipart/form-data
Authorization: Required
```

**Request Body (Form Data):**
```
judul_buku: string (required, max:200)
penulis: string (required, max:150)
kategori: string (optional, max:100)
tahun_terbit: integer (optional, year format)
deskripsi: string (optional)
file_path: file (optional, mimes: pdf,doc,docx, max:10240KB)
jumlah_halaman: integer (optional, min:1)
status_aktif: boolean (optional, default: true)
```

#### Update Fiksi
```http
PUT /dashboard/fiksi/{id}
Content-Type: multipart/form-data
Authorization: Required
```

#### Delete Fiksi
```http
DELETE /dashboard/fiksi/{id}
Authorization: Required
```

---

### 4.1. AAC (Augmentative & Alternative Communication)

Fitur AAC menyimpan **ungkapan/kata** dan **gambar**. Audio **tidak disimpan di backend** karena suara dihasilkan di frontend memakai Web Speech API (TTS).

#### Get All AAC (Public API)
```http
GET /api/aac
```

**Query Parameters:**
- `page` (optional): Page number
- `per_page` (optional): Items per page

**Success Response (JSON jika Accept: application/json):**
```json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "judul": "Saya ingin minum",
      "kategori": "Kebutuhan",
      "deskripsi": "Ungkapan untuk meminta minum",
      "gambar_path": "aac/gambar/1710000000_minum.png",
      "urutan": 1,
      "status_aktif": true,
      "dibuat_oleh": 1,
      "created_at": "2026-04-07T10:00:00.000000Z",
      "updated_at": "2026-04-07T10:00:00.000000Z",
      "pengguna": {
        "id": 1,
        "nama": "Super Admin"
      }
    }
  ],
  "per_page": 10,
  "total": 1
}
```

#### Get Single AAC (Public API)
```http
GET /api/aac/{id}
```

**Success Response (JSON):**
```json
{
  "id": 1,
  "judul": "Saya ingin minum",
  "kategori": "Kebutuhan",
  "deskripsi": "Ungkapan untuk meminta minum",
  "gambar_path": "aac/gambar/1710000000_minum.png",
  "urutan": 1,
  "status_aktif": true,
  "dibuat_oleh": 1,
  "created_at": "2026-04-07T10:00:00.000000Z",
  "updated_at": "2026-04-07T10:00:00.000000Z",
  "pengguna": {
    "id": 1,
    "nama": "Super Admin"
  }
}
```

#### Create AAC
```http
POST /dashboard/aac
Content-Type: multipart/form-data
Authorization: Required
```

**Request Body (Form Data):**
```
judul: string (required, max:150)
kategori: string (optional, max:100)
deskripsi: string (optional)
gambar_path: file (optional, mimes: jpg,jpeg,png,webp,svg, max:5120KB)
urutan: integer (optional, min:1)
status_aktif: boolean (optional, default: true)
```

**Success Response (302 Redirect):**
```
Location: /dashboard/aac
```

#### Update AAC
```http
PUT /dashboard/aac/{id}
Content-Type: multipart/form-data
Authorization: Required
```

**Request Body:** Sama seperti Create, semua field optional.

#### Delete AAC
```http
DELETE /dashboard/aac/{id}
Authorization: Required
```

#### Contoh Implementasi Frontend (TTS di Browser)

**1) Ambil data AAC (JSON)**
```javascript
const res = await fetch('/dashboard/aac', {
  headers: { 'Accept': 'application/json' },
  credentials: 'include'
});
const data = await res.json();
const items = data.data || [];
```

**2) Render kartu AAC + klik gambar untuk bicara**
```javascript
function speakText(text) {
  if (!('speechSynthesis' in window)) {
    alert('Browser tidak mendukung TTS.');
    return;
  }
  window.speechSynthesis.cancel();
  const utter = new SpeechSynthesisUtterance(text);
  utter.lang = 'id-ID';
  utter.rate = 1;
  window.speechSynthesis.speak(utter);
}

const container = document.getElementById('aacGrid');
container.innerHTML = items.map(item => `
  <button class="aac-card" data-text="${item.judul}">
    <img src="/storage/${item.gambar_path}" alt="${item.judul}" />
    <div class="aac-label">${item.judul}</div>
  </button>
`).join('');

container.querySelectorAll('.aac-card').forEach(btn => {
  btn.addEventListener('click', () => speakText(btn.dataset.text));
});
```

**3) HTML container**
```html
<div id="aacGrid" class="aac-grid"></div>
```

**Catatan:**
- Pastikan sudah `php artisan storage:link` agar `gambar_path` bisa diakses via `/storage/...`.
- Kalau browser tidak support TTS, tampilkan fallback (misal teks saja).

---

### 5. Pengguna (Users)

#### Get All Pengguna
```http
GET /dashboard/pengguna
Authorization: Required
```

**Success Response:**
```json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "nama": "Super Admin",
      "email": "superadmin@akses.com",
      "peran": "guru",
      "status_aktif": true,
      "created_at": "2025-12-06T10:00:00.000000Z",
      "updated_at": "2025-12-06T10:00:00.000000Z",
      "siswa": null,
      "guru": {
        "id": 1,
        "pengguna_id": 1,
        "nama_sekolah": "Sekolah AKSES",
        "created_at": "2025-12-06T10:00:00.000000Z",
        "updated_at": "2025-12-06T10:00:00.000000Z"
      }
    }
  ]
}
```

#### Get Single Pengguna
```http
GET /dashboard/pengguna/{id}
Authorization: Required
```

#### Create Pengguna
```http
POST /dashboard/pengguna
Content-Type: application/x-www-form-urlencoded
Authorization: Required
```

**Request Body:**
```
nama: string (required, max:100)
email: string (required, email, unique)
kata_sandi: string (required, min:6)
peran: string (required, enum: "siswa" | "guru")
status_aktif: boolean (optional, default: true)
nama_sekolah: string (optional, max:150)
jenjang: string (optional, max:50) - required if peran = "siswa"
catatan: string (optional) - only for siswa
```

**Example:**
```javascript
const formData = new FormData();
formData.append('nama', 'John Doe');
formData.append('email', 'john@example.com');
formData.append('kata_sandi', 'password123');
formData.append('peran', 'siswa');
formData.append('nama_sekolah', 'SD Negeri 1');
formData.append('jenjang', 'Kelas 4 SD');
formData.append('catatan', 'Siswa baru');
formData.append('status_aktif', '1');
formData.append('_token', csrfToken);

fetch('http://127.0.0.1:8000/dashboard/pengguna', {
  method: 'POST',
  body: formData,
  credentials: 'include'
});
```

#### Update Pengguna
```http
PUT /dashboard/pengguna/{id}
Content-Type: application/x-www-form-urlencoded
Authorization: Required
```

**Request Body:** Same as Create, but `kata_sandi` is optional

#### Delete Pengguna
```http
DELETE /dashboard/pengguna/{id}
Authorization: Required
```

---

### 6. Level

#### Get All Level
```http
GET /dashboard/level
Authorization: Required
```

**Response:**
- Web: HTML view `dashboard.level.index` (data dipaginasi `10` per halaman, urut `nama` ASC).
- Jika dipakai via API (`Accept: application/json`), response pagination Laravel standar.

**Contoh Response (JSON):**
```json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "nama": "Kelas 4 SD",
      "deskripsi": "Level untuk kelas 4 SD",
      "status_aktif": true,
      "created_at": "2025-12-06T10:00:00.000000Z",
      "updated_at": "2025-12-06T10:00:00.000000Z"
    }
  ],
  "per_page": 10,
  "total": 1
}
```

#### Get Single Level
```http
GET /dashboard/level/{id}
Authorization: Required
```

**Response:**
- Web: HTML view `dashboard.level.show`.
- Jika dipakai via API (`Accept: application/json`), response data level.

**Contoh Response (JSON):**
```json
{
  "id": 1,
  "nama": "Kelas 4 SD",
  "deskripsi": "Level untuk kelas 4 SD",
  "status_aktif": true,
  "created_at": "2025-12-06T10:00:00.000000Z",
  "updated_at": "2025-12-06T10:00:00.000000Z"
}
```

#### Create Level
```http
POST /dashboard/level
Content-Type: application/x-www-form-urlencoded
Authorization: Required
```

**Request Body:**
```
nama: string (required, max:100, unique)
deskripsi: string (optional)
status_aktif: boolean (optional, default: false jika checkbox tidak dikirim)
```

**Validasi:**
- `nama` wajib diisi dan harus unik pada tabel `level`.

**Success Response (Web):**
```
302 Redirect -> /dashboard/level
```

**Error Response (JSON, 422):**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "nama": ["Nama level wajib diisi", "Nama level sudah ada"]
  }
}
```

#### Update Level
```http
PUT /dashboard/level/{id}
Content-Type: application/x-www-form-urlencoded
Authorization: Required
```

**Request Body:** sama seperti Create, semua field wajib sesuai validasi.

**Success Response (Web):**
```
302 Redirect -> /dashboard/level
```

#### Delete Level
```http
DELETE /dashboard/level/{id}
Authorization: Required
```

**Success Response (Web):**
```
302 Redirect -> /dashboard/level
```

---

### 7. Mata Pelajaran

#### Get All Mata Pelajaran
```http
GET /dashboard/mata-pelajaran
Authorization: Required
```

**Success Response:**
```json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "nama": "Matematika",
      "deskripsi": "Mata pelajaran matematika",
      "status_aktif": true,
      "created_at": "2025-12-06T10:00:00.000000Z",
      "updated_at": "2025-12-06T10:00:00.000000Z"
    }
  ]
}
```

#### Get Single Mata Pelajaran
```http
GET /dashboard/mata-pelajaran/{id}
Authorization: Required
```

#### Create Mata Pelajaran
```http
POST /dashboard/mata-pelajaran
Content-Type: application/x-www-form-urlencoded
Authorization: Required
```

**Request Body:**
```
nama: string (required, max:100, unique)
deskripsi: string (optional)
status_aktif: boolean (optional, default: true)
```

#### Update Mata Pelajaran
```http
PUT /dashboard/mata-pelajaran/{id}
Content-Type: application/x-www-form-urlencoded
Authorization: Required
```

#### Delete Mata Pelajaran
```http
DELETE /dashboard/mata-pelajaran/{id}
Authorization: Required
```

---

### 8. Profile (User Profile)

#### Get Profile
```http
GET /dashboard/profile
Authorization: Required
```

**Success Response:**
```json
{
  "user": {
    "id": 1,
    "nama": "Super Admin",
    "email": "superadmin@akses.com",
    "peran": "guru",
    "status_aktif": true,
    "created_at": "2025-12-06T10:00:00.000000Z",
    "updated_at": "2025-12-06T10:00:00.000000Z",
    "siswa": null,
    "guru": {
      "id": 1,
      "pengguna_id": 1,
      "nama_sekolah": "Sekolah AKSES",
      "created_at": "2025-12-06T10:00:00.000000Z",
      "updated_at": "2025-12-06T10:00:00.000000Z"
    }
  }
}
```

**Note:** Response includes related `siswa` or `guru` data based on user's role.

#### Update Profile
```http
PUT /dashboard/profile
Content-Type: application/x-www-form-urlencoded
Authorization: Required
```

**Request Body:**
```
_method: PUT (required for method spoofing)
nama: string (required, max:100)
email: string (required, email, unique - except current user)
_token: string (required, CSRF token)
```

**Success Response (302 Redirect):**
```
Location: /dashboard/profile
```

**Error Response:**
```json
{
  "errors": {
    "nama": ["Nama wajib diisi"],
    "email": ["Email sudah digunakan oleh pengguna lain"]
  }
}
```

**Example:**
```javascript
const formData = new FormData();
formData.append('_method', 'PUT');
formData.append('nama', 'John Doe');
formData.append('email', 'john@example.com');
formData.append('_token', csrfToken);

fetch('http://127.0.0.1:8000/dashboard/profile', {
  method: 'POST', // Laravel uses POST with _method=PUT
  body: formData,
  credentials: 'include'
});
```

#### Update Password
```http
PUT /dashboard/profile/password
Content-Type: application/x-www-form-urlencoded
Authorization: Required
```

**Request Body:**
```
_method: PUT (required for method spoofing)
kata_sandi_lama: string (required) - current password
kata_sandi_baru: string (required, min:6) - new password
kata_sandi_konfirmasi: string (required, must match kata_sandi_baru)
_token: string (required, CSRF token)
```

**Success Response (302 Redirect):**
```
Location: /dashboard/profile
```

**Error Response:**
```json
{
  "errors": {
    "kata_sandi_lama": ["Kata sandi lama tidak benar."],
    "kata_sandi_baru": ["Kata sandi baru minimal 6 karakter"],
    "kata_sandi_konfirmasi": ["Konfirmasi kata sandi tidak cocok"]
  }
}
```

**Example:**
```javascript
const formData = new FormData();
formData.append('_method', 'PUT');
formData.append('kata_sandi_lama', 'oldpassword');
formData.append('kata_sandi_baru', 'newpassword123');
formData.append('kata_sandi_konfirmasi', 'newpassword123');
formData.append('_token', csrfToken);

fetch('http://127.0.0.1:8000/dashboard/profile/password', {
  method: 'POST', // Laravel uses POST with _method=PUT
  body: formData,
  credentials: 'include'
});
```

---

## Error Handling

### Common Error Responses

#### 401 Unauthorized
```json
{
  "message": "Unauthenticated."
}
```

#### 422 Validation Error
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "field_name": ["Error message"]
  }
}
```

#### 404 Not Found
```json
{
  "message": "No query results for model [App\\Models\\Materi] 1"
}
```

#### 500 Internal Server Error
```json
{
  "message": "Server Error"
}
```

---

## CSRF Protection

Laravel menggunakan CSRF protection untuk semua POST, PUT, DELETE requests.

### Getting CSRF Token

**For Web Forms:**
```html
<meta name="csrf-token" content="{{ csrf_token() }}">
```

**For JavaScript:**
```javascript
// Get from meta tag
const token = document.querySelector('meta[name="csrf-token"]').content;

// Or from cookie
const token = getCookie('XSRF-TOKEN');
```

### Including CSRF Token

**In Form Data:**
```
_token: string (CSRF token)
```

**In Headers (if using API):**
```
X-CSRF-TOKEN: your-csrf-token
X-XSRF-TOKEN: your-xsrf-token (from cookie)
```

---

## File Upload

### Supported File Types
- **Materi**: PDF, DOC, DOCX (max: 10MB)
- **Fiksi**: PDF, DOC, DOCX (max: 10MB)

### File Storage
Files are stored in: `storage/app/public/materi/` or `storage/app/public/fiksi/`

### Accessing Files
```
http://127.0.0.1:8000/storage/materi/filename.pdf
```

**Note:** Make sure to run `php artisan storage:link` to create symbolic link.

---

## Pagination

All list endpoints support pagination:

**Query Parameters:**
- `page`: Page number (default: 1)
- `per_page`: Items per page (default: 10)

**Response Format:**
```json
{
  "current_page": 1,
  "data": [...],
  "first_page_url": "...",
  "last_page_url": "...",
  "next_page_url": null,
  "prev_page_url": null,
  "per_page": 10,
  "total": 100,
  "last_page": 10
}
```

---

## Recommended: Creating API Routes

Untuk integrasi frontend yang lebih baik, disarankan membuat API routes terpisah:

### 1. Install Laravel Sanctum
```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

### 2. Create API Routes
Create `routes/api.php`:
```php
Route::post('/login', [AuthController::class, 'apiLogin']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'apiLogout']);
    
    Route::apiResource('materi', MateriController::class);
    Route::apiResource('fiksi', FiksiController::class);
    Route::apiResource('pengguna', PenggunaController::class);
    Route::apiResource('level', LevelController::class);
    Route::apiResource('mata-pelajaran', MataPelajaranController::class);
});
```

### 3. API Authentication Flow
```javascript
// Login
const response = await fetch('http://127.0.0.1:8000/api/login', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  body: JSON.stringify({
    email: 'superadmin@akses.com',
    kata_sandi: 'password'
  })
});

const { token } = await response.json();

// Use token in subsequent requests
fetch('http://127.0.0.1:8000/api/materi', {
  headers: {
    'Authorization': `Bearer ${token}`,
    'Accept': 'application/json'
  }
});
```

---

## Testing API

### Using cURL

**Login:**
```bash
curl -X POST http://127.0.0.1:8000/login \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "email=superadmin@akses.com&kata_sandi=password" \
  -c cookies.txt
```

**Get Materi:**
```bash
curl -X GET http://127.0.0.1:8000/dashboard/materi \
  -b cookies.txt
```

### Using Postman

1. Import collection
2. Set base URL: `http://127.0.0.1:8000`
3. For web routes, enable "Send cookies"
4. For API routes, use Bearer token authentication

---

## Rate Limiting

Currently no rate limiting is implemented. Consider adding:
```php
Route::middleware(['throttle:60,1'])->group(function () {
    // Your routes
});
```

---

## CORS Configuration

If frontend is on different domain, configure CORS in `config/cors.php`:
```php
'paths' => ['api/*'],
'allowed_origins' => ['http://localhost:3000'],
'allowed_methods' => ['*'],
'allowed_headers' => ['*'],
```

---

## Notes

1. **Current Implementation**: Uses web routes with session authentication
2. **Recommended**: Create separate API routes with token authentication for better frontend integration
3. **File Uploads**: Use `multipart/form-data` for file uploads
4. **CSRF**: All state-changing requests require CSRF token
5. **Pagination**: All list endpoints support pagination
6. **Validation**: All inputs are validated server-side

---

## Support

For issues or questions, contact the development team.

**Last Updated:** December 6, 2025

