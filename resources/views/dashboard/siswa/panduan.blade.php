@extends('dashboard.siswa.layout')

@section('content')
    <h2 class="section-title">Panduan Siswa</h2>
    <p class="section-desc">Ikuti alur penggunaan dan daftar perintah suara Ruma.</p>

    <div class="section-grid">
        <section class="section-card">
            <span class="tag">1</span>
            <h3 class="section-title">Pilih Mata Pelajaran</h3>
            <p class="section-desc">Masuk ke menu Mata Pelajaran, lalu pilih mata pelajaran yang ingin dibaca.</p>
        </section>

        <section class="section-card">
            <span class="tag">2</span>
            <h3 class="section-title">Masuk Sesi Baca</h3>
            <p class="section-desc">Di detail mata pelajaran, klik tombol <strong>Mulai Membaca</strong>.</p>
        </section>

        <section class="section-card">
            <span class="tag">3</span>
            <h3 class="section-title">Aktifkan Suara</h3>
            <p class="section-desc">
                Di halaman Sesi Baca, klik:
                <br>• <strong>ASR: Aktifkan</strong> untuk perintah suara.
                <br>• <strong>TTS: Mulai</strong> untuk membaca teks.
            </p>
        </section>

        <section class="section-card">
            <span class="tag">4</span>
            <h3 class="section-title">Perintah Suara</h3>
            <p class="section-desc">
                Ucapkan:
                <br>• "mulai scroll"
                <br>• "berhenti scroll"
                <br>• "lebih cepat"
                <br>• "lebih pelan"
                <br>• "mulai baca"
                <br>• "stop baca"
            </p>
        </section>
    </div>

    <div class="section-grid" style="margin-top:1.5rem;">
        <section class="section-card">
            <span class="tag">Voice Nav</span>
            <h3 class="section-title">Navigasi dengan Suara</h3>
            <p class="section-desc">
                Aktifkan tombol <strong>Voice Nav</strong> di header, lalu ucapkan:
                <br>• "buka dashboard"
                <br>• "buka mata pelajaran"
                <br>• "buka catatan"
                <br>• "buka riwayat"
                <br>• "buka panduan"
                <br>• "buka rak buku"
            </p>
        </section>

        <section class="section-card">
            <span class="tag">Catatan</span>
            <h3 class="section-title">Mencatat dengan Suara</h3>
            <p class="section-desc">
                Ucapkan:
                <br>• "mulai mencatat"
                <br>• "stop mencatat"
                <br>• "selesai mencatat"
            </p>
        </section>
    </div>

    <div class="section-card" style="margin-top:1.5rem;">
        <span class="tag">Catatan</span>
        <p class="section-desc">
            Browser perlu izin mikrofon dan umumnya harus ada klik pertama untuk mengaktifkan ASR.
            TTS bekerja penuh untuk mata pelajaran teks; untuk PDF tergantung dukungan browser.
        </p>
    </div>
@endsection
