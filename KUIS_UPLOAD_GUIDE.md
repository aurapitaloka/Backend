# Panduan Upload Soal Kuis (Semua Tipe)

Dokumen ini menjelaskan cara membuat dan mengunggah soal kuis di halaman admin, termasuk kolom yang wajib/opsional, serta penyebab gagal simpan.

## A. Akses Halaman
1. Login sebagai admin.
2. Buka menu `Kuis` di sidebar.
3. Klik `Tambah Kuis`.

## B. Informasi Umum Kuis
Wajib diisi:
- `Judul Kuis`

Opsional:
- `Materi` (bisa dikosongkan)
- `Deskripsi`
- `Aktifkan Kuis` (boleh tidak dicentang)

Jika gagal simpan:
- Judul kuis kosong.

## C. Tipe Soal yang Didukung
1. `Pilihan Ganda`
2. `Essay`
3. `Listening`
4. `Speaking`

Setiap soal **wajib** memiliki:
- `Pertanyaan`
- `Tipe Soal`

## D. Detail Tiap Tipe Soal

### 1) Pilihan Ganda
Wajib diisi:
- `Opsi A`
- `Opsi B`
- `Opsi C`
- `Opsi D`
- `Jawaban Benar` (A/B/C/D)

Opsional:
- Tidak ada

Jika gagal simpan:
- Ada opsi kosong.
- Jawaban benar belum dipilih.

### 2) Essay
Wajib diisi:
- `Jawaban Contoh`
- `Keyword` (pisahkan dengan koma)
- `Bahasa ASR` (pilih `id-ID` atau `en-US`)

Opsional:
- Tidak ada

Jika gagal simpan:
- Jawaban contoh kosong.
- Keyword kosong.

Catatan:
- Essay dinilai **otomatis** dengan keyword, tetapi **tetap menunggu koreksi guru**.

### 3) Listening
Wajib diisi:
- `Opsi A`–`D`
- `Jawaban Benar`
- **Salah satu** dari berikut:
  - `Audio File` (mp3/wav/ogg), **atau**
  - `Teks untuk TTS`

Opsional:
- `Bahasa TTS` (disarankan `en-US` untuk materi Inggris)

Jika gagal simpan:
- Opsi kosong / jawaban benar belum dipilih.
- Audio file **dan** teks TTS kosong.

Catatan:
- Jika audio file diisi, sistem memutar file.
- Jika audio file kosong tapi TTS ada, sistem membacakan teks TTS.

### 4) Speaking
Wajib diisi:
- `Jawaban Target (English)` (kalimat target)
- **Salah satu** dari berikut:
  - `Audio Contoh` (mp3/wav/ogg), **atau**
  - `Teks untuk TTS`
- `Bahasa ASR/TTS` (disarankan `en-US`)

Opsional:
- Tidak ada

Jika gagal simpan:
- Jawaban target kosong.
- Audio contoh **dan** teks TTS kosong.

Catatan:
- Siswa akan rekam suara → ASR en-US → skor kemiripan → feedback suara.
- Hasil tetap menunggu koreksi guru.

## E. Batasan File Audio
- Format: `mp3`, `wav`, `ogg`
- Maksimal: `10 MB`

Jika gagal upload:
- Format file tidak sesuai.
- File terlalu besar.

## F. Penyebab Gagal Simpan (Ringkas)
1. `Judul kuis` kosong.
2. `Pertanyaan` kosong.
3. Tipe pilihan/listening tanpa `Opsi A-D` dan `Jawaban Benar`.
4. Essay tanpa `Jawaban Contoh` dan `Keyword`.
5. Listening tanpa audio dan tanpa teks TTS.
6. Speaking tanpa `Jawaban Target` dan tanpa audio/TTS.

## G. Tips Agar Tidak Error
- Isi semua field wajib sebelum klik Simpan.
- Pastikan file audio tidak melebihi 10 MB.
- Gunakan bahasa yang sesuai (`id-ID` untuk Indonesia, `en-US` untuk Inggris).

