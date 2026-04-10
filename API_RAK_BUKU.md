# API Documentation — Rak Buku (Bookshelf)

Base URL: `http://127.0.0.1:8000`
Prefix for these routes: `/api`
Auth: Required (middleware `auth:sanctum`) — use Sanctum cookie-based SPA (`credentials: 'include'`) or Bearer token `Authorization: Bearer <token>`.

---

## 1) Tambah ke Rak Buku
POST /api/dashboard/rak-buku

Headers:
- `Content-Type: application/json`
- `Accept: application/json`
- `Authorization: Bearer <token>` (atau cookie + `credentials: 'include'`)

Request body (JSON):
```json
{ "materi_id": 42 }
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
- 401 Unauthorized — tidak terautentikasi
- 422 Validation Error — contoh:
```json
{
  "message": "The given data was invalid.",
  "errors": { "materi_id": ["The selected materi_id is invalid."] }
}
```

cURL example (Bearer):
```bash
curl -X POST 'http://127.0.0.1:8000/api/dashboard/rak-buku' \
  -H 'Authorization: Bearer <token>' \
  -H 'Content-Type: application/json' \
  -d '{"materi_id":42}'
```

Fetch example (Sanctum cookie auth):
```javascript
await fetch('/api/dashboard/rak-buku', {
  method: 'POST',
  credentials: 'include',
  headers: { 'Content-Type':'application/json', 'Accept':'application/json' },
  body: JSON.stringify({ materi_id: 42 })
});
```

---

## 2) Hapus dari Rak Buku
DELETE /api/dashboard/rak-buku/{materi}

Path param: `materi` = materi id

Success (200):
```json
{ "message": "Deleted" }
```

Errors:
- 404 Not Found — entry tidak ada
- 401 Unauthorized

cURL example:
```bash
curl -X DELETE 'http://127.0.0.1:8000/api/dashboard/rak-buku/42' \
  -H 'Authorization: Bearer <token>'
```

Fetch example:
```javascript
await fetch(`/api/dashboard/rak-buku/${materiId}`, {
  method: 'DELETE',
  credentials: 'include',
  headers: { 'Accept':'application/json' }
});
```

---

## 3) Cek Status (ada di rak atau tidak)
GET /api/dashboard/rak-buku/{materi}/status

Success (200):
```json
{ "in_rak": true }
```

Fetch example:
```javascript
const res = await fetch(`/api/dashboard/rak-buku/${materiId}/status`, {
  credentials: 'include', headers: { 'Accept':'application/json' }
});
const { in_rak } = await res.json();
```

---

## 4) Daftar Rak Buku Pengguna (paginated)
GET /api/dashboard/rak-buku?page=1&per_page=10

Response: Laravel pagination object. `data` berisi entry rak buku termasuk relation `materi`.

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

## Integration guidance (frontend)
- On material detail page: call `GET /api/dashboard/rak-buku/{materi}/status` to set Add/Remove button state on load.
- On Add click: call POST /api/dashboard/rak-buku; optimistically update UI to show "Di Rak Buku".
- On Remove click: call DELETE and update UI.
- Use `credentials: 'include'` for Sanctum cookie auth and call `GET /sanctum/csrf-cookie` if you will send mutating requests.
- Handle 422 validation responses: show field error messages to user.
- Server enforces `pengguna_id = Auth::id()` — users cannot change others' rak buku.

---

## Notes for backend developers
- Routes added in `routes/api.php`:
  - `POST /dashboard/rak-buku` → `RakBukuController@store`
  - `DELETE /dashboard/rak-buku/{materi}` → `RakBukuController@destroy`
  - `GET /dashboard/rak-buku/{materi}/status` → `RakBukuController@status`
  - `GET /dashboard/rak-buku` → `RakBukuController@index`
- Table: `rak_buku` with unique constraint (`pengguna_id`, `materi_id`).

---

Last updated: 2026-01-04
