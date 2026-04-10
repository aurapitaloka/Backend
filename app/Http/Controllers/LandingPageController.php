<?php

namespace App\Http\Controllers;

use App\Models\LandingItem;
use App\Models\Ulasan;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        $hero = LandingItem::section('hero')->active()->orderBy('sort_order')->first();
        $featureHeader = LandingItem::section('feature-header')->active()->orderBy('sort_order')->first();
        $features = LandingItem::section('feature')->active()->orderBy('sort_order')->get();
        $flowHeader = LandingItem::section('flow-header')->active()->orderBy('sort_order')->first();
        $steps = LandingItem::section('step')->active()->orderBy('sort_order')->get();
        $collectionHeader = LandingItem::section('collection-header')->active()->orderBy('sort_order')->first();
        $books = LandingItem::section('book')->active()->orderBy('sort_order')->get();
        $app = LandingItem::section('app')->active()->orderBy('sort_order')->first();
        $footer = LandingItem::section('footer')->active()->orderBy('sort_order')->first();

        $hero = $hero ?? (object) [
            'title' => 'Belajar Lebih Mudah,',
            'subtitle' => 'Tanpa Sentuhan.',
            'description' => 'Solusi edukasi modern yang menggabungkan teknologi voice dan path control untuk memberikan pengalaman belajar yang accessible, interaktif, dan efektif bagi semua siswa tanpa terkecuali.',
            'button_label' => 'Jelajahi Sekarang',
            'button_url' => '#fitur',
            'image_path' => null,
        ];

        $featureHeader = $featureHeader ?? (object) [
            'title' => 'Fitur Unggulan',
            'description' => 'AKSES menghadirkan teknologi pembelajaran inklusif yang dirancang untuk memudahkan semua siswa belajar secara mandiri, interaktif, dan tanpa hambatan.',
        ];

        if ($features->isEmpty()) {
            $features = collect([
                (object) [
                    'title' => 'Navigasi Perintah Suara',
                    'description' => 'Pindah menu dan buka materi hanya dengan perintah suara sederhana.',
                    'badge' => 'mic',
                ],
                (object) [
                    'title' => 'TTS & Auto Scroll',
                    'description' => 'Dengarkan materi dengan TTS dan gunakan auto scroll saat membaca.',
                    'badge' => 'volume-2',
                ],
                (object) [
                    'title' => 'Kuis & Catatan',
                    'description' => 'Kerjakan kuis terhubung materi dan simpan catatan belajar.',
                    'badge' => 'check-square',
                ],
            ]);
        }

        $flowHeader = $flowHeader ?? (object) [
            'title' => 'Alur Penggunaan',
            'description' => 'Ikuti langkah-langkah sederhana untuk mulai belajar dengan AKSES secara mudah, inklusif, dan tanpa hambatan.',
        ];

        if ($steps->isEmpty()) {
            $steps = collect([
                (object) [
                    'title' => 'Buat Akun & Login',
                    'description' => 'Daftarkan akun siswa, lalu masuk ke dashboard AKSES.',
                    'sort_order' => 1,
                ],
                (object) [
                    'title' => 'Pilih Kelas',
                    'description' => 'Tentukan kelas agar materi yang tampil sesuai kebutuhan.',
                    'sort_order' => 2,
                ],
                (object) [
                    'title' => 'Pilih Materi',
                    'description' => 'Buka materi sesuai kelas, lalu baca dengan TTS dan auto scroll.',
                    'sort_order' => 3,
                ],
                (object) [
                    'title' => 'Kerjakan Kuis & Catat',
                    'description' => 'Selesaikan kuis dan simpan catatan belajar untuk evaluasi.',
                    'sort_order' => 4,
                ],
            ]);
        }

        $collectionHeader = $collectionHeader ?? (object) [
            'title' => 'Koleksi Materi',
            'description' => 'Materi belajar terstruktur berdasarkan kelas dan mata pelajaran.',
            'button_label' => 'Lihat Semua',
            'button_url' => '#',
        ];

        if ($books->isEmpty()) {
            $books = collect([
                (object) [
                    'title' => 'Bahasa Indonesia',
                    'description' => 'Materi membaca, menulis, dan memahami teks secara interaktif.',
                    'badge' => 'B. Indo',
                    'image_path' => null,
                    'button_label' => 'Lihat Detail',
                    'button_url' => '#',
                ],
                (object) [
                    'title' => 'Matematika',
                    'description' => 'Latihan logika, hitung, dan pemecahan masalah secara bertahap.',
                    'badge' => 'MTK',
                    'image_path' => null,
                    'button_label' => 'Lihat Detail',
                    'button_url' => '#',
                ],
                (object) [
                    'title' => 'IPA',
                    'description' => 'Materi sains dasar untuk memahami alam dan lingkungan sekitar.',
                    'badge' => 'IPA',
                    'image_path' => null,
                    'button_label' => 'Lihat Detail',
                    'button_url' => '#',
                ],
            ]);
        }

        $app = $app ?? (object) [
            'title' => 'Dapatkan Aplikasi AKSES',
            'description' => 'Belajar menjadi lebih mudah dan inklusif dengan teknologi tanpa sentuhan. AKSES dapat digunakan melalui smartphone maupun web.',
            'button_label' => 'Download Sekarang',
            'button_url' => '#',
            'image_path' => null,
        ];

        $footer = $footer ?? (object) [
            'title' => 'AKSES',
            'description' => 'Platform pembelajaran inklusif yang membantu siswa belajar lebih mudah tanpa sentuhan, kapan pun dan di mana pun.',
            'subtitle' => 'Developed by Aura Pitaloka | 22090026',
            'meta_one' => 'Tegal',
            'meta_two' => '+62 111-0000-2222',
        ];

        return view('landing', compact(
            'hero',
            'featureHeader',
            'features',
            'flowHeader',
            'steps',
            'collectionHeader',
            'books',
            'app',
            'footer'
        ));
    }

    public function storeUlasan(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'nullable|email|max:150',
            'rating' => 'nullable|integer|min:1|max:5',
            'isi' => 'required|string|max:1000',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'rating.min' => 'Rating minimal 1.',
            'rating.max' => 'Rating maksimal 5.',
            'isi.required' => 'Ulasan wajib diisi.',
        ]);

        Ulasan::create($validated);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Ulasan berhasil dikirim. Terima kasih!',
            ]);
        }

        return redirect()
            ->route('landing.home')
            ->with('review_success', 'Ulasan berhasil dikirim. Terima kasih!');
    }
}
