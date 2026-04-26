@extends('dashboard.siswa.layout')

@section('content')
    <style>
        .dashboard-hero {
            background: linear-gradient(135deg, rgba(244,160,0,0.12), rgba(31,41,55,0.06));
            border: 1px solid rgba(0,0,0,0.06);
            border-radius: 18px;
            padding: 1.5rem;
            display: grid;
            grid-template-columns: minmax(0, 1.2fr) minmax(0, 1fr);
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .hero-title {
            font-size: 1.4rem;
            font-weight: 800;
            margin-bottom: 0.35rem;
        }

        .hero-desc {
            color: var(--color-text-light);
            margin-bottom: 1rem;
        }

        .hero-chips {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .hero-chip {
            background: #fff;
            border: 1px solid rgba(0,0,0,0.08);
            padding: 0.4rem 0.75rem;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .hero-panel {
            background: #fff;
            border: 1px solid rgba(0,0,0,0.08);
            border-radius: 14px;
            padding: 1rem 1.1rem;
            display: grid;
            gap: 0.75rem;
        }

        .hero-panel h4 {
            font-size: 1rem;
            font-weight: 700;
        }

        .hero-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.92rem;
        }

        .hero-row span {
            color: var(--color-text-light);
        }

        .hero-row strong {
            font-weight: 700;
        }

        .action-section {
            margin-top: 1.25rem;
        }

        .action-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1rem;
        }

        .action-item {
            background: #fff;
            border: 1px solid rgba(0,0,0,0.06);
            border-radius: 14px;
            padding: 1rem 1.1rem;
            display: flex;
            gap: 0.9rem;
            align-items: flex-start;
            transition: box-shadow 0.2s ease, transform 0.2s ease;
        }

        .action-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(0,0,0,0.08);
        }

        .action-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: rgba(244,160,0,0.15);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #B35E00;
        }

        .action-content h4 {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .action-content p {
            color: var(--color-text-light);
            margin-bottom: 0.75rem;
        }

        @media (max-width: 900px) {
            .dashboard-hero {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="dashboard-hero">
        <div>
            <div class="hero-title">Halo, {{ $user->nama }}!</div>
            <div class="hero-desc">Mulai belajar dengan langkah yang jelas dan tidak membingungkan.</div>
            <div class="hero-chips">
                <div class="hero-chip"><i data-lucide="layers"></i> {{ $user->siswa->level->nama ?? 'Kelas belum dipilih' }}</div>
                <div class="hero-chip"><i data-lucide="mic"></i> Suara: {{ ($user->auto_voice_nav ?? false) ? 'Aktif' : 'Nonaktif' }}</div>
                <div class="hero-chip"><i data-lucide="sparkles"></i> Fokus hari ini: Materi & Kuis</div>
            </div>
        </div>

        <div class="hero-panel">
            <h4>Ringkasan Cepat</h4>
            <div class="hero-row">
                <span>Kelas aktif</span>
                <strong>{{ $user->siswa->level->nama ?? 'Belum dipilih' }}</strong>
            </div>
            <div class="hero-row">
                <span>Mode suara</span>
                <strong>{{ ($user->auto_voice_nav ?? false) ? 'Aktif' : 'Nonaktif' }}</strong>
            </div>
            <div class="hero-row">
                <span>Langkah berikutnya</span>
                <strong>Buka materi terbaru</strong>
            </div>
            <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
                <a href="{{ route('dashboard.siswa.pengaturan') }}" class="btn btn-secondary">Atur Kelas</a>
                <a href="{{ route('dashboard.siswa.materi') }}" class="btn btn-primary">Mulai Belajar</a>
            </div>
        </div>
    </div>

    <div class="action-section">
        <h3 class="section-title" style="margin-bottom: 0.6rem;">Materi Terakhir Dibuka</h3>
        <div class="action-list">
            <div class="action-item">
                <div class="action-icon"><i data-lucide="bookmark"></i></div>
                <div class="action-content">
                    @if(!empty($lastReadMateri))
                        <h4>{{ $lastReadMateri->judul }}</h4>
                        <p>{{ $lastReadMateri->deskripsi ?? 'Lanjutkan materi terakhir yang kamu buka.' }}</p>
                        <div style="margin-bottom:0.75rem;">
                            <div style="font-size:0.85rem; color: var(--color-text-light); margin-bottom:0.35rem;">
                                Progres belajar: {{ $lastReadProgress ?? 0 }}%
                            </div>
                            <div style="height:8px; background:#E5E7EB; border-radius:999px; overflow:hidden;">
                                <div style="height:100%; width: {{ $lastReadProgress ?? 0 }}%; background: var(--color-accent);"></div>
                            </div>
                        </div>
                        <a href="{{ route('dashboard.siswa.materi.show', $lastReadMateri->id) }}" class="btn btn-primary">Lanjutkan</a>
                    @else
                        <h4>Yuk mulai belajar</h4>
                        <p>Belum ada materi yang dibuka. Pilih materi pertama untuk mulai belajar.</p>
                        <a href="{{ route('dashboard.siswa.materi') }}" class="btn btn-primary">Pilih Materi</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="action-section">
        <h3 class="section-title" style="margin-bottom: 0.6rem;">Aksi Utama</h3>
        <div class="action-list">
            <div class="action-item">
                <div class="action-icon"><i data-lucide="book-open"></i></div>
                <div class="action-content">
                    <h4>Materi Belajar</h4>
                    <p>Cari semua materi aktif dan mulai belajar sekarang.</p>
                    <a href="{{ route('dashboard.siswa.materi') }}" class="btn btn-primary">Lihat Materi</a>
                </div>
            </div>

            <div class="action-item">
                <div class="action-icon"><i data-lucide="check-square"></i></div>
                <div class="action-content">
                    <h4>Mulai Kuis</h4>
                    <p>Uji pemahamanmu setelah membaca materi.</p>
                    <a href="{{ route('dashboard.siswa.kuis') }}" class="btn btn-primary">Mulai Kuis</a>
                </div>
            </div>

            <div class="action-item">
                <div class="action-icon"><i data-lucide="sticky-note"></i></div>
                <div class="action-content">
                    <h4>Catatan Pribadi</h4>
                    <p>Simpan ringkasan belajar dan poin penting.</p>
                    <a href="{{ route('dashboard.siswa.catatan') }}" class="btn btn-secondary">Kelola Catatan</a>
                </div>
            </div>

            <div class="action-item">
                <div class="action-icon"><i data-lucide="history"></i></div>
                <div class="action-content">
                    <h4>Riwayat Belajar</h4>
                    <p>Pantau progres dan hasil kuis kamu.</p>
                    <a href="{{ route('dashboard.siswa.riwayat') }}" class="btn btn-secondary">Lihat Riwayat</a>
                </div>
            </div>

            <div class="action-item">
                <div class="action-icon"><i data-lucide="books"></i></div>
                <div class="action-content">
                    <h4>Rak Buku</h4>
                    <p>Kumpulkan materi penting agar mudah diakses ulang.</p>
                    <a href="{{ route('dashboard.siswa.rak-buku') }}" class="btn btn-secondary">Buka Rak Buku</a>
                </div>
            </div>

            <div class="action-item">
                <div class="action-icon"><i data-lucide="book"></i></div>
                <div class="action-content">
                    <h4>Panduan</h4>
                    <p>Pelajari cara menggunakan menu dan perintah suara.</p>
                    <a href="{{ route('dashboard.siswa.panduan') }}" class="btn btn-secondary">Buka Panduan</a>
                </div>
            </div>
        </div>
    </div>
@endsection
