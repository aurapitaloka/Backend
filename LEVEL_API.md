# API Documentation - Level (Kelas)

Dokumentasi ini ditujukan untuk integrasi frontend Flutter.

Base URL:
```
http://127.0.0.1:8000
```

Catatan penting:
- Di backend saat ini, `LevelController` mengembalikan HTML view (web) dan redirect.
- Route API tetap tersedia di `routes/api.php`, tetapi response JSON hanya akan konsisten jika controller menangani request `Accept: application/json`.
- Jika Flutter memanggil endpoint ini tanpa penyesuaian backend, kemungkinan mendapatkan HTML.

## Authentication
Semua endpoint berada di bawah middleware `auth:sanctum`.

Header contoh:
```
Authorization: Bearer <token>
Accept: application/json
```

## Endpoints

### 1) Get All Level
```http
GET /api/level?page=1&per_page=10
Authorization: Required
Accept: application/json
```

Response (JSON, paginasi standar Laravel):
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

### 2) Get Single Level
```http
GET /api/level/{id}
Authorization: Required
Accept: application/json
```

Response (JSON):
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

### 3) Create Level
```http
POST /api/level
Authorization: Required
Accept: application/json
Content-Type: application/x-www-form-urlencoded
```

Request body:
```
nama: string (required, max:100, unique)
deskripsi: string (optional)
status_aktif: boolean (optional, default: false jika tidak dikirim)
```

Validasi:
- `nama` wajib diisi dan harus unik di tabel `level`.

Error (422):
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "nama": ["Nama level wajib diisi", "Nama level sudah ada"]
  }
}
```

### 4) Update Level
```http
PUT /api/level/{id}
Authorization: Required
Accept: application/json
Content-Type: application/x-www-form-urlencoded
```

Request body:
```
nama: string (required, max:100, unique except current id)
deskripsi: string (optional)
status_aktif: boolean (optional, default: false jika tidak dikirim)
```

### 5) Delete Level
```http
DELETE /api/level/{id}
Authorization: Required
Accept: application/json
```

Response:
```
204 No Content
```

## Flutter Notes
- Set `Accept: application/json` pada semua request.
- Jika response masih HTML, backend perlu menambahkan method API di `LevelController`
  (mis. `index`, `store`, dst mengembalikan JSON saat `request()->expectsJson()`).

## Contoh Flutter (Dio)
```dart
import 'package:dio/dio.dart';

final dio = Dio(BaseOptions(
  baseUrl: 'http://127.0.0.1:8000/api',
  headers: {
    'Accept': 'application/json',
    'Authorization': 'Bearer <token>',
  },
));

Future<Response> getLevels({int page = 1}) {
  return dio.get('/level', queryParameters: {'page': page, 'per_page': 10});
}

Future<Response> getLevel(int id) {
  return dio.get('/level/$id');
}

Future<Response> createLevel({
  required String nama,
  String? deskripsi,
  bool statusAktif = false,
}) {
  return dio.post(
    '/level',
    data: {
      'nama': nama,
      'deskripsi': deskripsi,
      'status_aktif': statusAktif ? '1' : '0',
    },
    options: Options(contentType: Headers.formUrlEncodedContentType),
  );
}

Future<Response> updateLevel({
  required int id,
  required String nama,
  String? deskripsi,
  bool statusAktif = false,
}) {
  return dio.put(
    '/level/$id',
    data: {
      'nama': nama,
      'deskripsi': deskripsi,
      'status_aktif': statusAktif ? '1' : '0',
    },
    options: Options(contentType: Headers.formUrlEncodedContentType),
  );
}

Future<Response> deleteLevel(int id) {
  return dio.delete('/level/$id');
}
```

## Contoh Flutter (http)
```dart
import 'dart:convert';
import 'package:http/http.dart' as http;

const baseUrl = 'http://127.0.0.1:8000/api';
const token = '<token>';

Map<String, String> headers() => {
  'Accept': 'application/json',
  'Authorization': 'Bearer $token',
  'Content-Type': 'application/x-www-form-urlencoded',
};

Future<http.Response> getLevels({int page = 1}) {
  final uri = Uri.parse('$baseUrl/level?page=$page&per_page=10');
  return http.get(uri, headers: headers());
}

Future<http.Response> getLevel(int id) {
  final uri = Uri.parse('$baseUrl/level/$id');
  return http.get(uri, headers: headers());
}

Future<http.Response> createLevel({
  required String nama,
  String? deskripsi,
  bool statusAktif = false,
}) {
  final uri = Uri.parse('$baseUrl/level');
  return http.post(
    uri,
    headers: headers(),
    body: {
      'nama': nama,
      'deskripsi': deskripsi ?? '',
      'status_aktif': statusAktif ? '1' : '0',
    },
  );
}

Future<http.Response> updateLevel({
  required int id,
  required String nama,
  String? deskripsi,
  bool statusAktif = false,
}) {
  final uri = Uri.parse('$baseUrl/level/$id');
  return http.put(
    uri,
    headers: headers(),
    body: {
      'nama': nama,
      'deskripsi': deskripsi ?? '',
      'status_aktif': statusAktif ? '1' : '0',
    },
  );
}

Future<http.Response> deleteLevel(int id) {
  final uri = Uri.parse('$baseUrl/level/$id');
  return http.delete(uri, headers: headers());
}
```
