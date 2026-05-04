@extends('dashboard.siswa.layout')

@section('content')
    <style>
        .library-tabs {
            display: flex;
            gap: 1.25rem;
            border-bottom: 1px solid rgba(15, 23, 42, 0.12);
            margin-bottom: 1.5rem;
        }

        .library-tab {
            background: transparent;
            border: none;
            padding: 0.6rem 0;
            font-weight: 600;
            color: var(--color-text-light);
            cursor: pointer;
            position: relative;
        }

        .library-tab.active {
            color: var(--color-text);
        }

        .library-tab.active::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: -1px;
            height: 3px;
            background: var(--color-accent);
            border-radius: 999px;
        }

        .library-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, 130px);
            justify-content: start;
            gap: 1rem;
        }

        .library-card {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            text-decoration: none;
            color: inherit;
            width: 130px;
        }

        .library-cover {
            aspect-ratio: 3 / 4;
            border-radius: 12px;
            background: #fff;
            border: 1px solid rgba(0,0,0,0.08);
            box-shadow: 0 8px 16px rgba(0,0,0,0.08);
            overflow: hidden;
            position: relative;
            display: flex;
            align-items: flex-end;
            padding: 0.6rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .library-cover.has-image {
            background-size: cover;
            background-position: center;
            color: #fff;
        }

        .library-cover.placeholder::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(244,160,0,0.12), transparent);
        }

        .library-cover:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 22px rgba(0,0,0,0.14);
        }

        .library-tag {
            position: absolute;
            top: 0.6rem;
            left: 0.6rem;
            background: rgba(255,255,255,0.92);
            color: #B35E00;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.25rem 0.55rem;
            border-radius: 999px;
            z-index: 1;
        }

        .library-title {
            font-weight: 700;
            font-size: 0.9rem;
            line-height: 1.3;
        }

        .library-meta {
            color: var(--color-text-light);
            font-size: 0.8rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .library-actions {
            display: flex;
            gap: 0.4rem;
            flex-wrap: wrap;
        }

        .library-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.45rem 0.7rem;
            border-radius: 10px;
            font-size: 0.78rem;
            font-weight: 600;
            border: 1px solid transparent;
            text-decoration: none;
            cursor: pointer;
            transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.15s ease, color 0.15s ease;
        }

        .library-btn i {
            width: 16px;
            height: 16px;
        }

        .library-btn.primary {
            background: var(--color-accent);
            color: #1F2937;
            box-shadow: 0 6px 14px rgba(244,160,0,0.25);
        }

        .library-btn.primary:hover {
            transform: translateY(-1px);
            background: #E69300;
        }

        .library-btn.ghost {
            background: #fff;
            color: var(--color-text);
            border-color: rgba(0,0,0,0.1);
        }

        .library-btn.ghost:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.08);
        }

        .library-btn.danger {
            color: #B91C1C;
            border-color: rgba(185,28,28,0.25);
            background: rgba(185,28,28,0.08);
        }

        .library-empty {
            padding: 1.5rem;
            background: #fff;
            border-radius: 16px;
            border: 1px solid rgba(0,0,0,0.08);
        }
    </style>

    <h2 class="section-title">Rak Buku</h2>
    <p class="section-desc">Daftar mata pelajaran yang kamu simpan di rak buku.</p>

    @if(session('success'))
        <div class="library-empty">
            <span class="tag" style="background:rgba(22,163,74,0.12); color:#166534;">Sukses</span>
            <p class="section-desc">{{ session('success') }}</p>
        </div>
    @endif

    <div class="library-tabs" role="tablist">
        <button class="library-tab active" type="button" data-tab="reading">Saat ini dibaca</button>
        <button class="library-tab" type="button" data-tab="finished">Selesai</button>
    </div>

    @if($rak->count() === 0)
        <div class="library-empty">
            <span class="tag">Info</span>
            <h3 class="section-title">Rak buku masih kosong</h3>
            <p class="section-desc">Tambahkan mata pelajaran dari menu Mata Pelajaran.</p>
            <a href="{{ route('dashboard.siswa.materi') }}" class="btn btn-primary">Buka Mata Pelajaran</a>
        </div>
    @else
        <div class="library-grid" data-panel="reading">
            @foreach($rak as $item)
                @php
                    $coverPath = $item->materi->cover_path ?? null;
                @endphp
                <article class="library-card">
                    <a href="{{ route('dashboard.siswa.materi.show', $item->materi_id) }}" class="library-card">
                        <div class="library-cover {{ $coverPath ? 'has-image' : 'placeholder' }}" style="{{ $coverPath ? "background-image:url('" . asset('storage/' . $coverPath) . "');" : '' }}">
                            <span class="library-tag">{{ $item->materi->mataPelajaran->nama ?? 'Umum' }}</span>
                        </div>
                        <div class="library-title">{{ $item->materi->judul ?? 'Mata Pelajaran' }}</div>
                        <div class="library-meta">{{ $item->materi->deskripsi ?? 'Mata pelajaran ini belum memiliki deskripsi.' }}</div>
                    </a>
                    <div class="library-actions">
                        <a href="{{ route('dashboard.siswa.materi.show', $item->materi_id) }}" class="library-btn primary">
                            <i data-lucide="play-circle"></i>
                            Buka Mata Pelajaran
                        </a>
                        <form action="{{ route('dashboard.siswa.rak-buku.remove', $item->materi_id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button class="library-btn ghost danger" type="submit">
                                <i data-lucide="trash-2"></i>
                                Hapus
                            </button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="library-empty" data-panel="finished" style="display:none;">
            <span class="tag">Info</span>
            <h3 class="section-title">Belum ada mata pelajaran selesai</h3>
            <p class="section-desc">Mata pelajaran yang sudah selesai akan muncul di tab ini.</p>
        </div>

        <div style="margin-top:1.5rem;">
            {{ $rak->links() }}
        </div>
    @endif

    <script>
        (function() {
            const tabs = Array.from(document.querySelectorAll('.library-tab'));
            const panels = Array.from(document.querySelectorAll('[data-panel]'));
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    tabs.forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');
                    const target = tab.getAttribute('data-tab');
                    panels.forEach(panel => {
                        panel.style.display = panel.getAttribute('data-panel') === target ? '' : 'none';
                    });
                });
            });
        })();
    </script>
@endsection
