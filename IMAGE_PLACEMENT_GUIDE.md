# Panduan Penempatan Gambar untuk Landing Page Ruma

File-file gambar berikut perlu ditempatkan di folder `public/images/` dengan nama yang sesuai:

## Struktur Folder yang Dibutuhkan

```
public/
├── images/
│   ├── logo-ruma.png              # Logo utama Ruma (disarankan: 200x60px, format PNG dengan transparansi)
│   ├── hero-illustration.png        # Ilustrasi hero section (disarankan: 800x600px, format PNG/JPG)
│   ├── mobile-app-mockup.png        # Mockup aplikasi mobile (disarankan: 400x800px, format PNG)
│   ├── desktop-app-mockup.png       # Mockup aplikasi web/desktop (disarankan: 1200x800px, format PNG)
│   ├── book-shelf-1.png             # Gambar rak buku 1 - Ilmu Pengetahuan Alam (disarankan: 600x400px)
│   ├── book-shelf-2.png            # Gambar rak buku 2 - Matematika (disarankan: 600x400px)
│   ├── book-shelf-3.png            # Gambar rak buku 3 - Bahasa Indonesia (disarankan: 600x400px)
│   └── icons/
│       ├── gaze-scroll.svg          # Icon untuk fitur Gaze Scroll (disarankan: 64x64px, format SVG)
│       ├── voice-command.svg        # Icon untuk fitur Perintah Suara (disarankan: 64x64px, format SVG)
│       └── inclusive-learning.svg   # Icon untuk fitur Mode Belajar Inklusif (disarankan: 64x64px, format SVG)
```

## Detail Spesifikasi Gambar

### 1. Logo Ruma (`logo-ruma.png`)
- **Ukuran**: 200x60px (atau proporsi yang sesuai)
- **Format**: PNG dengan background transparan
- **Konten**: Logo teks "Ruma" atau logo brand Ruma
- **Penggunaan**: Header navigation

### 2. Hero Illustration (`hero-illustration.png`)
- **Ukuran**: 800x600px (atau lebih besar untuk kualitas HD)
- **Format**: PNG atau JPG
- **Konten**: Ilustrasi ruang kelas dengan guru dan siswa (termasuk siswa berkursi roda)
- **Kontras**: Pastikan kontras yang baik dengan background putih/kuning

### 3. Mobile App Mockup (`mobile-app-mockup.png`)
- **Ukuran**: 400x800px (rasio 1:2 untuk smartphone)
- **Format**: PNG dengan transparansi atau JPG
- **Konten**: Screenshot atau mockup aplikasi Ruma di smartphone
- **Tampilan**: Layar login/register aplikasi

### 4. Desktop App Mockup (`desktop-app-mockup.png`)
- **Ukuran**: 1200x800px (rasio 3:2)
- **Format**: PNG atau JPG
- **Konten**: Screenshot atau mockup aplikasi Ruma di desktop/web browser
- **Tampilan**: Halaman web Ruma

### 5. Book Shelf Images (`book-shelf-1.png`, `book-shelf-2.png`, `book-shelf-3.png`)
- **Ukuran**: 600x400px (rasio 3:2)
- **Format**: PNG atau JPG
- **Konten**: 
  - `book-shelf-1.png`: Rak buku dengan label "Ilmu Pengetahuan Alam"
  - `book-shelf-2.png`: Rak buku dengan label "Matematika"
  - `book-shelf-3.png`: Rak buku dengan label "Bahasa Indonesia"
- **Style**: Rak buku coklat dengan buku-buku berwarna

### 6. Icons (Folder `icons/`)
- **Ukuran**: 64x64px
- **Format**: SVG (disarankan) atau PNG dengan transparansi
- **Konten**:
  - `gaze-scroll.svg`: Icon mata atau eye tracking
  - `voice-command.svg`: Icon mikrofon atau suara
  - `inclusive-learning.svg`: Icon aksesibilitas atau inklusivitas

## Catatan Penting

1. **Fallback**: Jika gambar tidak ditemukan, halaman akan menampilkan placeholder atau emoji sebagai fallback
2. **Optimasi**: Pastikan semua gambar dioptimalkan untuk web (kompresi yang baik tanpa kehilangan kualitas visual)
3. **Format SVG**: Untuk icons, gunakan format SVG untuk kualitas yang lebih baik di berbagai ukuran
4. **Naming Convention**: Gunakan nama file yang sesuai dengan yang disebutkan di atas (case-sensitive di beberapa sistem)

## Alternatif: Menggunakan Placeholder

Jika gambar belum tersedia, Anda bisa menggunakan placeholder services seperti:
- `https://via.placeholder.com/800x600` untuk hero illustration
- `https://via.placeholder.com/200x60` untuk logo

Atau gunakan generator placeholder lainnya sesuai kebutuhan.

## Tips Desain

1. **Konsistensi Warna**: Pastikan gambar menggunakan palet warna yang konsisten (kuning, hijau, coklat)
2. **Style**: Gunakan style ilustrasi yang konsisten (flat design, 3D, atau ilustrasi tangan)
3. **Aksesibilitas**: Pastikan kontras warna cukup untuk readability
4. **Responsif**: Pastikan gambar terlihat baik di berbagai ukuran layar

