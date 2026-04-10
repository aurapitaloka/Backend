@extends('dashboard.siswa.layout')

@section('content')
    <style>
        .no-stripe::before {
            display: none;
        }

        .history-hero {
            background: linear-gradient(135deg, rgba(244,160,0,0.16), rgba(31,41,55,0.04));
            border-radius: 18px;
            padding: 1.5rem;
            border: 1px solid rgba(0,0,0,0.08);
            box-shadow: 0 10px 24px rgba(0,0,0,0.08);
            margin-bottom: 1.5rem;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .summary-card {
            background: #fff;
            border-radius: 14px;
            padding: 1rem 1.1rem;
            border: 1px solid rgba(0,0,0,0.08);
            box-shadow: 0 8px 18px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .summary-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: rgba(244,160,0,0.16);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #B35E00;
        }

        .history-row {
            display: flex;
            gap: 1rem;
            overflow-x: auto;
            overflow-y: hidden;
            padding-bottom: 0.5rem;
            scroll-behavior: smooth;
            width: 100%;
        }

        .history-row-wrap {
            position: relative;
        }

        .history-row-hint {
            position: absolute;
            right: 0.5rem;
            top: -2.2rem;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background: rgba(0,0,0,0.06);
            color: var(--color-text-light);
            font-size: 0.8rem;
            padding: 0.25rem 0.6rem;
            border-radius: 999px;
        }

        .history-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 34px;
            height: 34px;
            border-radius: 50%;
            border: 1px solid rgba(0,0,0,0.1);
            background: #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 2;
        }

        .history-nav.left {
            left: -8px;
        }

        .history-nav.right {
            right: -8px;
        }

        .filter-row {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-top: 0.75rem;
        }

        .filter-btn {
            padding: 0.45rem 0.9rem;
            border-radius: 999px;
            border: 1px solid rgba(0,0,0,0.12);
            background: #fff;
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--color-text);
            text-decoration: none;
        }

        .filter-btn.active {
            background: rgba(244,160,0,0.18);
            border-color: rgba(244,160,0,0.35);
            color: #B35E00;
        }

        .history-card {
            background: #fff;
            border-radius: 16px;
            padding: 1rem;
            border: 1px solid rgba(0,0,0,0.08);
            box-shadow: 0 8px 18px rgba(bantu supaya 0,0,0,0.08);
            min-width: 320px;   
            flex-shrink: 0;    
        }

        .history-meta {
            color: var(--color-text-light);
            font-size: 0.85rem;
            margin-bottom: 0.35rem;
        }

        .score-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.25rem 0.6rem;
            border-radius: 999px;
            background: rgba(22,163,74,0.12);
            color: #166534;
            font-weight: 700;
            font-size: 0.85rem;
        }
    </style>

    <div class="history-hero">
        <h2 class="section-title">Riwayat</h2>
        <p class="section-desc">Pantau perkembangan belajar dari materi dan kuis.</p>
        <div class="summary-grid">
            <div class="summary-card">
                <span class="summary-icon"><i data-lucide="book-open"></i></span>
                <div>
                    <div class="section-title" style="font-size:1.1rem;">Materi Dibaca</div>
                    <div class="section-desc" style="font-size:0.9rem;">{{ $riwayat->total() }} materi tercatat.</div>
                </div>
            </div>
            <div class="summary-card">
                <span class="summary-icon"><i data-lucide="check-square"></i></span>
                <div>
                    <div class="section-title" style="font-size:1.1rem;">Kuis Diselesaikan</div>
                    <div class="section-desc" style="font-size:0.9rem;">{{ $riwayatKuis->total() }} kuis tercatat.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="section-card no-stripe" style="margin-top:1.5rem;">
        <span class="tag">Materi</span>
        <h3 class="section-title">Riwayat Baca</h3>
        <p class="section-desc">Materi terakhir yang kamu baca.</p>

        @if($riwayat->count() === 0)
            <p class="section-desc">Belum ada riwayat materi.</p>
            <a href="{{ route('dashboard.siswa.materi') }}" class="btn btn-primary">Lihat Materi</a>
        @else
            <div class="history-row" style="margin-top:1rem;">
                @foreach($riwayat as $item)
                    <article class="history-card">
                        <span class="tag">Terakhir Dibaca</span>
                        <h3 class="section-title">{{ $item->judul }}</h3>
                        <p class="section-desc">{{ $item->deskripsi ?? 'Materi ini belum memiliki deskripsi.' }}</p>
                        <p class="history-meta">Terakhir dibaca: {{ \Carbon\Carbon::parse($item->last_access)->format('d M Y, H:i') }}</p>
                        <p class="history-meta">Total dibaca: {{ $item->total_baca }} kali</p>
                        <p class="history-meta">Halaman terakhir: {{ $item->halaman_terakhir ?? '-' }} | Progres: {{ $item->progres_persen ?? '-' }}%</p>
                        <p class="history-meta">Durasi terakhir: {{ $item->durasi_detik ? ceil($item->durasi_detik / 60) . ' menit' : '-' }}</p>
                        <a href="{{ route('dashboard.siswa.materi.show', $item->materi_id) }}" class="btn btn-secondary">Buka Lagi</a>
                    </article>
                @endforeach
            </div>

            <div style="margin-top:1.5rem;">
                {{ $riwayat->links() }}
            </div>
        @endif
    </div>

    <div class="section-card no-stripe" style="margin-top:1.5rem;">
        <span class="tag">Kuis</span>
        <h3 class="section-title">Riwayat Kuis</h3>
        <p class="section-desc">Skor dan progres hasil kuis.</p>
        <div class="filter-row">
            <a href="{{ request()->fullUrlWithQuery(['kuis_sort' => 'latest', 'kuis_page' => null]) }}" class="filter-btn {{ ($kuisSort ?? 'latest') === 'latest' ? 'active' : '' }}">Terbaru</a>
            <a href="{{ request()->fullUrlWithQuery(['kuis_sort' => 'score', 'kuis_page' => null]) }}" class="filter-btn {{ ($kuisSort ?? 'latest') === 'score' ? 'active' : '' }}">Skor Tertinggi</a>
        </div>

        @if($riwayatKuis->count() === 0)
            <p class="section-desc">Belum ada riwayat kuis.</p>
            <a href="{{ route('dashboard.siswa.materi') }}" class="btn btn-primary">Kerjakan Kuis</a>
        @else
            <div class="history-row-wrap" style="margin-top:1rem;">
                <div class="history-row-hint"><i data-lucide="move-horizontal"></i> Geser</div>
                <button class="history-nav right" type="button" data-scroll="right"><i data-lucide="chevron-right"></i></button>
                <div class="history-row" id="kuisHistoryRow">
                    @foreach($riwayatKuis as $item)
                        <article class="history-card">
                            <span class="tag">Selesai Kuis</span>
                            <h3 class="section-title">{{ $item->kuis_judul }}</h3>
                            <p class="section-desc">Materi: {{ $item->materi_judul ?? '-' }}</p>
                            <div style="margin-top:0.5rem;">
                                <div class="score-badge"><i data-lucide="award"></i> {{ $item->skor }}%</div>
                                <p class="history-meta">Benar {{ $item->total_benar }} dari {{ $item->total_pertanyaan }} soal</p>
                            </div>
                            <p class="history-meta">Selesai: {{ $item->selesai_at ? \Carbon\Carbon::parse($item->selesai_at)->format('d M Y, H:i') : '-' }}</p>
                            <p class="history-meta">Status essay: {{ ($item->has_pending ?? 0) ? 'Menunggu Koreksi' : 'Disetujui' }}</p>
                            @if($item->materi_id)
                                <div style="display:flex; gap:0.5rem; flex-wrap:wrap; margin-top:0.5rem;">
                                    <a href="{{ route('dashboard.siswa.materi.show', $item->materi_id) }}" class="btn btn-secondary">Lihat Materi</a>
                                    <a href="{{ route('dashboard.siswa.riwayat.kuis.show', $item->hasil_id) }}" class="btn btn-primary">Detail Jawaban</a>
                                </div>
                            @else
                                <div style="display:flex; gap:0.5rem; flex-wrap:wrap; margin-top:0.5rem;">
                                    <a href="{{ route('dashboard.siswa.materi') }}" class="btn btn-secondary">Lihat Materi</a>
                                    <a href="{{ route('dashboard.siswa.riwayat.kuis.show', $item->hasil_id) }}" class="btn btn-primary">Detail Jawaban</a>
                                </div>
                            @endif
                        </article>
                    @endforeach
                </div>
            </div>

            <div style="margin-top:1.5rem;">
                {{ $riwayatKuis->links() }}
            </div>
        @endif
    </div>

    <script>
        (function() {
            const row = document.getElementById('kuisHistoryRow');
            if (!row) return;
            document.querySelectorAll('.history-nav').forEach(btn => {
                btn.addEventListener('click', () => {
                    const dir = btn.dataset.scroll === 'right' ? 1 : -1;
                    const jump = Math.max(260, Math.floor(row.clientWidth * 0.8));
                    row.scrollBy({ left: dir * jump, behavior: 'smooth' });
                });
            });

           
    </script>
@endsection
