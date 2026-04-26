@extends('dashboard.siswa.layout')

@section('content')
    <style>
        .no-stripe::before {
            display: none;
        }

        .quiz-hero {
            background: linear-gradient(135deg, rgba(244,160,0,0.16), rgba(31,41,55,0.04));
            border-radius: 18px;
            padding: 1.5rem;
            border: 1px solid rgba(0,0,0,0.08);
            box-shadow: 0 10px 24px rgba(0,0,0,0.08);
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .quiz-hero-row {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .quiz-hero-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: rgba(244,160,0,0.18);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #B35E00;
        }

        .quiz-hero-text {
            display: flex;
            align-items: baseline;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .quiz-hero-text .section-title {
            margin: 0;
        }

        .quiz-hero-text .section-desc {
            margin: 0;
        }

        .quiz-hero::after {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top right, rgba(244,160,0,0.2), transparent 60%);
            pointer-events: none;
        }

        .quiz-row {
            display: grid;
            grid-auto-flow: column;
            grid-auto-columns: minmax(260px, 320px);
            gap: 1rem;
            overflow-x: auto;
            padding-bottom: 0.5rem;
            scrollbar-width: none;
        }

        .quiz-row::-webkit-scrollbar {
            height: 0;
        }

        .quiz-card {
            background: #fff;
            border-radius: 16px;
            padding: 1rem;
            border: 1px solid rgba(0,0,0,0.08);
            box-shadow: 0 6px 16px rgba(0,0,0,0.08);
            display: grid;
            grid-template-columns: 90px 1fr;
            gap: 0.9rem;
            align-items: start;
            position: relative;
            overflow: hidden;
        }

        .quiz-card::after {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 16px;
            border: 1px solid rgba(244,160,0,0.18);
            pointer-events: none;
        }

        .quiz-cover {
            aspect-ratio: 3 / 4;
            border-radius: 12px;
            background: linear-gradient(145deg, rgba(244,160,0,0.35), rgba(31,41,55,0.15));
            display: flex;
            align-items: flex-end;
            padding: 0.5rem;
            color: #fff;
            font-weight: 700;
            font-size: 0.8rem;
        }

        .quiz-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.2rem 0.55rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 700;
            background: rgba(244,160,0,0.12);
            color: #B35E00;
        }

        .quiz-title {
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 0.35rem;
        }

        .quiz-meta {
            color: var(--color-text-light);
            font-size: 0.85rem;
            margin-bottom: 0.6rem;
        }

        .quiz-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
    </style>

    <div class="quiz-hero">
        <div class="quiz-hero-row">
            <div class="quiz-hero-icon"><i data-lucide="list-check"></i></div>
            <div class="quiz-hero-text">
                <h2 class="section-title">Daftar Kuis</h2>
                <p class="section-desc">Kuis umum maupun kuis materi bisa langsung dikerjakan.</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="section-card no-stripe" style="margin-top:1rem; border-left-color:#16A34A;">
            <span class="tag" style="background:rgba(22,163,74,0.12); color:#166534;">Sukses</span>
            <p class="section-desc">{{ session('success') }}</p>
        </div>
    @endif

    <div class="section-card no-stripe" style="margin-top:1.5rem;">
        <div style="display:flex; align-items:center; justify-content:space-between; gap:0.75rem; flex-wrap:wrap;">
            <div>
                <div class="section-title">Kuis Umum</div>
                <div class="section-desc">Tidak terikat materi. Bisa langsung dikerjakan.</div>
            </div>
        </div>

        @if($kuisUmum->count() === 0)
            <p class="section-desc" style="margin-top:0.75rem;">Belum ada kuis umum.</p>
        @else
            <div class="quiz-row" style="margin-top:1rem;">
                @foreach($kuisUmum as $item)
                    <div class="quiz-card">
                        <div class="quiz-cover">Umum</div>
                        <div>
                            <div class="quiz-title">{{ $item->judul }}</div>
                            <div class="quiz-meta">{{ $item->deskripsi ?? 'Tanpa deskripsi.' }}</div>
                            <div class="quiz-meta"><span class="quiz-pill"><i data-lucide="list-check"></i> {{ $item->pertanyaan_count ?? 0 }} Soal</span></div>
                            <div class="quiz-actions">
                                <a href="{{ route('dashboard.siswa.kuis.show', $item->id) }}" class="btn btn-primary">Mulai Kuis</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="section-card no-stripe" style="margin-top:1.5rem;">
        <div style="display:flex; align-items:center; justify-content:space-between; gap:0.75rem; flex-wrap:wrap;">
            <div>
                <div class="section-title">Kuis Materi</div>
                <div class="section-desc">Semua kuis yang terhubung ke materi tersedia langsung.</div>
            </div>
        </div>

        @if($kuisMateri->count() === 0)
            <p class="section-desc" style="margin-top:0.75rem;">Belum ada kuis materi.</p>
        @else
            <div class="quiz-row" style="margin-top:1rem;">
                @foreach($kuisMateri as $item)
                    @php
                        $materi = $item->materi;
                        $progress = $materi ? ($progressMap[$materi->id]['progres'] ?? 0) : 0;
                    @endphp
                    <div class="quiz-card">
                        <div class="quiz-cover">Materi</div>
                        <div>
                            <div class="quiz-title">{{ $item->judul }}</div>
                            <div class="quiz-meta"><i data-lucide="book-open"></i> Materi: {{ $materi->judul ?? '-' }}</div>
                            <div class="quiz-meta"><span class="quiz-pill"><i data-lucide="gauge"></i> {{ $progress }}% Progress</span></div>
                            <div class="quiz-meta"><span class="quiz-pill"><i data-lucide="list-check"></i> {{ $item->pertanyaan_count ?? 0 }} Soal</span></div>
                            <div class="quiz-actions">
                                @if($materi)
                                    <a href="{{ route('dashboard.siswa.materi.show', $materi->id) }}" class="btn btn-secondary">Buka Materi</a>
                                    <a href="{{ route('dashboard.siswa.materi.kuis.show', ['materi' => $materi->id, 'kuis' => $item->id]) }}" class="btn btn-primary">Mulai Kuis</a>
                                @endif
                            </div>
                            <div class="quiz-meta">Progress materi tetap tercatat, tetapi kuis sudah bisa langsung diakses.</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
