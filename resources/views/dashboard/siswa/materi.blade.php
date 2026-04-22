@extends('dashboard.siswa.layout')

@section('content')
    <style>
        .book-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
            gap: 1.25rem;
        }

        .book-card {
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }

        .book-cover {
            position: relative;
            aspect-ratio: 3 / 4;
            border-radius: 16px;
            background: linear-gradient(145deg, rgba(244,160,0,0.25), rgba(31,41,55,0.08));
            border: 1px solid rgba(0,0,0,0.08);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            display: flex;
            align-items: flex-end;
            padding: 0.9rem;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .book-cover.has-image {
            background-size: cover;
            background-position: center;
            color: #fff;
        }

        .book-cover.placeholder {
            background: #fff;
            color: #111827;
        }

        .book-cover::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, transparent, rgba(0,0,0,0.2));
        }

        .book-cover.placeholder::after {
            background: linear-gradient(180deg, rgba(244,160,0,0.08), transparent);
        }

        .book-cover:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 28px rgba(0,0,0,0.16);
        }

        .book-badge {
            position: absolute;
            top: 0.75rem;
            left: 0.75rem;
            background: rgba(255,255,255,0.92);
            padding: 0.3rem 0.6rem;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 700;
            color: #B35E00;
            z-index: 1;
        }

        .book-rak {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            background: rgba(31,41,55,0.85);
            color: #fff;
            padding: 0.3rem 0.55rem;
            border-radius: 10px;
            font-size: 0.7rem;
            z-index: 1;
        }

        .book-meta {
            position: relative;
            z-index: 1;
            color: #fff;
            font-weight: 700;
            line-height: 1.2;
            max-width: 100%;
        }

        .book-placeholder-text {
            position: relative;
            z-index: 1;
            font-weight: 700;
            line-height: 1.3;
            font-size: 0.9rem;
            color: #111827;
        }

        .book-title {
            font-size: 1rem;
            font-weight: 700;
            margin-top: 0.15rem;
        }

        .book-desc {
            color: var(--color-text-light);
            font-size: 0.85rem;
        }

        .featured-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.25rem;
            margin-bottom: 1.75rem;
        }

        .featured-card {
            display: grid;
            grid-template-columns: 120px 1fr;
            gap: 1rem;
            background: #fff;
            border-radius: 18px;
            padding: 1rem;
            border: 1px solid rgba(0,0,0,0.08);
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            text-decoration: none;
            color: inherit;
        }

        .featured-cover {
            aspect-ratio: 3 / 4;
            border-radius: 14px;
            background: linear-gradient(145deg, rgba(31,41,55,0.25), rgba(244,160,0,0.25));
            display: flex;
            align-items: flex-end;
            padding: 0.6rem;
            color: #fff;
            font-weight: 700;
            background-size: cover;
            background-position: center;
        }

        .featured-cover.placeholder {
            background: #fff;
            color: #111827;
            border: 1px solid rgba(0,0,0,0.08);
        }

        .featured-body h4 {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0.4rem;
        }

        .featured-body p {
            color: var(--color-text-light);
            margin-bottom: 0.6rem;
        }

        .featured-meta {
            font-size: 0.85rem;
            color: var(--color-text-light);
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .mapel-section {
            margin-bottom: 1.75rem;
        }

        .mapel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.75rem;
        }

        .mapel-title {
            font-size: 1.1rem;
            font-weight: 700;
        }

        .mapel-row {
            display: grid;
            grid-auto-flow: column;
            grid-auto-columns: minmax(150px, 180px);
            gap: 1rem;
            overflow-x: auto;
            padding-bottom: 0.5rem;
            scrollbar-width: none;
        }

        .mapel-row::-webkit-scrollbar {
            height: 0;
        }
    </style>

    <h2 class="section-title">Daftar Materi</h2>
    <p class="section-desc">Berikut materi yang sudah ditambahkan oleh admin dan siap dipelajari.</p>

    @if($materi->count() === 0)
        <div class="section-card">
            <span class="tag">Info</span>
            <h3 class="section-title">Belum ada materi</h3>
            @if($user->siswa && $user->siswa->level)
                <p class="section-desc">Belum ada materi untuk kelas {{ $user->siswa->level->nama }}.</p>
            @else
                <p class="section-desc">Saat ini belum ada materi aktif. Silakan cek kembali nanti.</p>
            @endif
        </div>
    @else
        @php
            $materiCollection = $materi instanceof \Illuminate\Pagination\AbstractPaginator ? $materi->getCollection() : $materi;
            $featured = $materiCollection->take(2);
            $rest = $materiCollection->skip(2);
            $grouped = $rest->groupBy(function ($item) {
                return $item->mataPelajaran->nama ?? 'Umum';
            });
        @endphp

        @if($featured->count() > 0)
            <div class="featured-row">
                @foreach($featured as $item)
                    <a href="{{ route('dashboard.siswa.materi.show', $item->id) }}" class="featured-card" data-title="{{ $item->judul }}" data-url="{{ route('dashboard.siswa.materi.show', $item->id) }}">
                        <div class="featured-cover {{ $item->cover_url ? '' : 'placeholder' }}" style="{{ $item->cover_url ? "background-image:url('" . $item->cover_url . "');" : '' }}">
                            @if(!$item->cover_url)
                                {{ $item->level->nama ?? 'Materi' }}
                            @endif
                        </div>
                        <div class="featured-body">
                            <h4>{{ $item->judul }}</h4>
                            <p>{{ $item->deskripsi ?? 'Materi ini belum memiliki deskripsi.' }}</p>
                            <div class="featured-meta">
                                <i data-lucide="book-open"></i>
                                {{ $item->mataPelajaran->nama ?? 'Umum' }}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

        @foreach($grouped as $mapel => $items)
            <div class="mapel-section">
                <div class="mapel-header">
                    <div class="mapel-title">{{ $mapel }}</div>
                </div>
                <div class="mapel-row">
                    @foreach($items as $item)
                        <a href="{{ route('dashboard.siswa.materi.show', $item->id) }}" class="book-card" data-title="{{ $item->judul }}" data-url="{{ route('dashboard.siswa.materi.show', $item->id) }}">
                            <div class="book-cover {{ $item->cover_url ? 'has-image' : 'placeholder' }}" style="{{ $item->cover_url ? "background-image:url('" . $item->cover_url . "');" : '' }}">
                                <span class="book-badge">{{ $item->mataPelajaran->nama ?? 'Umum' }}</span>
                                @if(in_array($item->id, $rakMateriIds ?? []))
                                    <span class="book-rak"><i data-lucide="bookmark"></i></span>
                                @endif
                                @if(!$item->cover_url)
                                    <div class="book-placeholder-text">{{ $item->judul }}</div>
                                @else
                                    <div class="book-meta">
                                        {{ $item->level->nama ?? 'Materi' }}
                                    </div>
                                @endif
                            </div>
                            <div class="book-title">{{ $item->judul }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div style="margin-top:1.5rem;">
            {{ $materi->links() }}
        </div>
    @endif

    <script>
        (function() {
            const items = Array.from(document.querySelectorAll('.section-card[data-title][data-url]'));
            window.__materiList = items.map(item => ({
                title: item.getAttribute('data-title') || '',
                url: item.getAttribute('data-url') || ''
            }));
        })();
    </script>
@endsection
