# API Documentation - Fiksi

Dokumentasi ini ditujukan untuk integrasi frontend Flutter.

Base URL:
```
http://127.0.0.1:8000
```

Catatan penting:
- Endpoint API tersedia di `/api/fiksi`.
- Controller sudah mendukung JSON saat `Accept: application/json`.
- Upload file disimpan di disk `public` (path tersimpan pada `file_path`).

## Authentication
Semua endpoint berada di bawah middleware `auth:sanctum`.

Header contoh:
```
Authorization: Bearer <token>
Accept: application/json
```

## Endpoints

### 1) Get All Fiksi
```http
GET /api/fiksi?page=1&per_page=10
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
        "nama": "Super Admin",
        "email": "superadmin@ruma.com"
      }
    }
  ],
  "per_page": 10,
  "total": 1
}
```

### 2) Get Single Fiksi
```http
GET /api/fiksi/{id}
Authorization: Required
Accept: application/json
```

Response (JSON):
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
    "nama": "Super Admin",
    "email": "superadmin@ruma.com"
  }
}
```

### 3) Create Fiksi
```http
POST /api/fiksi
Authorization: Required
Accept: application/json
Content-Type: multipart/form-data
```

Request body (form-data):
```
judul_buku: string (required, max:200)
penulis: string (required, max:150)
kategori: string (optional, max:100)
tahun_terbit: integer (optional, min:1900, max: tahun sekarang)
deskripsi: string (optional)
file_path: file (optional, mimes: pdf,doc,docx, max:10240KB)
jumlah_halaman: integer (optional, min:1)
status_aktif: boolean (optional, default: false jika tidak dikirim)
```

Error (422):
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "judul_buku": ["Judul buku wajib diisi"],
    "penulis": ["Penulis wajib diisi"],
    "file_path": ["File harus berupa PDF, DOC, atau DOCX"]
  }
}
```

### 4) Update Fiksi
```http
PUT /api/fiksi/{id}
Authorization: Required
Accept: application/json
Content-Type: multipart/form-data
```

Request body: sama seperti Create.

Catatan:
- Jika `file_path` tidak diupload, file lama akan dipertahankan.

### 5) Delete Fiksi
```http
DELETE /api/fiksi/{id}
Authorization: Required
Accept: application/json
```

Response:
```
204 No Content
```

## Flutter Notes
- Set `Accept: application/json` pada semua request.
- Gunakan `multipart/form-data` untuk upload file.

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

Future<Response> getFiksi({int page = 1}) {
  return dio.get('/fiksi', queryParameters: {'page': page, 'per_page': 10});
}

Future<Response> getFiksiById(int id) {
  return dio.get('/fiksi/$id');
}

Future<Response> createFiksi({
  required String judulBuku,
  required String penulis,
  String? kategori,
  int? tahunTerbit,
  String? deskripsi,
  String? filePath,
  int? jumlahHalaman,
  bool statusAktif = false,
}) async {
  final formData = FormData.fromMap({
    'judul_buku': judulBuku,
    'penulis': penulis,
    'kategori': kategori,
    'tahun_terbit': tahunTerbit,
    'deskripsi': deskripsi,
    'jumlah_halaman': jumlahHalaman,
    'status_aktif': statusAktif ? '1' : '0',
    if (filePath != null)
      'file_path': await MultipartFile.fromFile(filePath),
  });
  return dio.post('/fiksi', data: formData);
}

Future<Response> updateFiksi({
  required int id,
  required String judulBuku,
  required String penulis,
  String? kategori,
  int? tahunTerbit,
  String? deskripsi,
  String? filePath,
  int? jumlahHalaman,
  bool statusAktif = false,
}) async {
  final formData = FormData.fromMap({
    'judul_buku': judulBuku,
    'penulis': penulis,
    'kategori': kategori,
    'tahun_terbit': tahunTerbit,
    'deskripsi': deskripsi,
    'jumlah_halaman': jumlahHalaman,
    'status_aktif': statusAktif ? '1' : '0',
    if (filePath != null)
      'file_path': await MultipartFile.fromFile(filePath),
  });
  return dio.put('/fiksi/$id', data: formData);
}

Future<Response> deleteFiksi(int id) {
  return dio.delete('/fiksi/$id');
}
```

## Contoh Flutter (http)
```dart
import 'dart:convert';
import 'dart:io';
import 'package:http/http.dart' as http;

const baseUrl = 'http://127.0.0.1:8000/api';
const token = '<token>';

Map<String, String> headers() => {
  'Accept': 'application/json',
  'Authorization': 'Bearer $token',
};

Future<http.Response> getFiksi({int page = 1}) {
  final uri = Uri.parse('$baseUrl/fiksi?page=$page&per_page=10');
  return http.get(uri, headers: headers());
}

Future<http.Response> getFiksiById(int id) {
  final uri = Uri.parse('$baseUrl/fiksi/$id');
  return http.get(uri, headers: headers());
}

Future<http.StreamedResponse> createFiksi({
  required String judulBuku,
  required String penulis,
  String? kategori,
  int? tahunTerbit,
  String? deskripsi,
  File? file,
  int? jumlahHalaman,
  bool statusAktif = false,
}) async {
  final uri = Uri.parse('$baseUrl/fiksi');
  final request = http.MultipartRequest('POST', uri);
  request.headers.addAll(headers());
  request.fields.addAll({
    'judul_buku': judulBuku,
    'penulis': penulis,
    'kategori': kategori ?? '',
    'tahun_terbit': tahunTerbit?.toString() ?? '',
    'deskripsi': deskripsi ?? '',
    'jumlah_halaman': jumlahHalaman?.toString() ?? '',
    'status_aktif': statusAktif ? '1' : '0',
  });
  if (file != null) {
    request.files.add(await http.MultipartFile.fromPath('file_path', file.path));
  }
  return request.send();
}

Future<http.StreamedResponse> updateFiksi({
  required int id,
  required String judulBuku,
  required String penulis,
  String? kategori,
  int? tahunTerbit,
  String? deskripsi,
  File? file,
  int? jumlahHalaman,
  bool statusAktif = false,
}) async {
  final uri = Uri.parse('$baseUrl/fiksi/$id');
  final request = http.MultipartRequest('PUT', uri);
  request.headers.addAll(headers());
  request.fields.addAll({
    'judul_buku': judulBuku,
    'penulis': penulis,
    'kategori': kategori ?? '',
    'tahun_terbit': tahunTerbit?.toString() ?? '',
    'deskripsi': deskripsi ?? '',
    'jumlah_halaman': jumlahHalaman?.toString() ?? '',
    'status_aktif': statusAktif ? '1' : '0',
  });
  if (file != null) {
    request.files.add(await http.MultipartFile.fromPath('file_path', file.path));
  }
  return request.send();
}

Future<http.Response> deleteFiksi(int id) {
  final uri = Uri.parse('$baseUrl/fiksi/$id');
  return http.delete(uri, headers: headers());
}
```
