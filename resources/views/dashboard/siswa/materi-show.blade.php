@extends('dashboard.siswa.layout')

@section('content')
    <style>
        .detail-hero {
            display: grid;
            grid-template-columns: minmax(180px, 240px) minmax(0, 1fr);
            gap: 1.5rem;
            background: #fff;
            border-radius: 18px;
            padding: 1.5rem;
            border: 1px solid rgba(0,0,0,0.08);
            box-shadow: 0 12px 28px rgba(0,0,0,0.08);
            margin-bottom: 1.5rem;
        }

        .detail-cover {
            aspect-ratio: 3 / 4;
            border-radius: 16px;
            background: linear-gradient(145deg, rgba(244,160,0,0.25), rgba(31,41,55,0.12));
            display: flex;
            align-items: flex-end;
            padding: 0.9rem;
            color: #fff;
            font-weight: 700;
        }

        .detail-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 0.75rem;
            margin: 1rem 0;
        }

        .meta-item {
            background: var(--color-primary-light);
            border-radius: 12px;
            padding: 0.6rem 0.75rem;
            font-size: 0.85rem;
            color: var(--color-text-light);
        }

        .meta-item strong {
            display: block;
            color: var(--color-text);
            font-size: 0.95rem;
        }

        .action-bar {
            display: flex;
            gap: 0.6rem;
            flex-wrap: wrap;
            margin-top: 0.75rem;
        }

        .pill-btn {
            border-radius: 999px;
            padding: 0.65rem 1.2rem;
        }

        .content-section {
            background: #fff;
            border-radius: 16px;
            padding: 1.25rem 1.5rem;
            border: 1px solid rgba(0,0,0,0.06);
            box-shadow: 0 6px 18px rgba(0,0,0,0.06);
            margin-bottom: 1.25rem;
        }

        @media (max-width: 900px) {
            .detail-hero {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="detail-hero">
        <div class="detail-cover" style="{{ $materi->cover_url ? "background-image:url('" . $materi->cover_url . "'); background-size:cover; background-position:center;" : 'background:#fff; color:#111827; border:1px solid rgba(0,0,0,0.08);' }}">
            @if(!$materi->cover_url)
                <div style="font-size:0.95rem; line-height:1.4; font-weight:700;">{{ $materi->judul }}</div>
            @else
                {{ $materi->level->nama ?? 'Materi' }}
            @endif
        </div>
        <div>
            <span class="tag">{{ $materi->mataPelajaran->nama ?? 'Umum' }}</span>
            <h2 class="section-title" style="margin-top:0.35rem;">{{ $materi->judul }}</h2>
            <p class="section-desc">{{ $materi->deskripsi ?? 'Materi ini belum memiliki deskripsi.' }}</p>

            <div class="detail-meta">
                <div class="meta-item">
                    Mapel
                    <strong>{{ $materi->mataPelajaran->nama ?? 'Umum' }}</strong>
                </div>
                <div class="meta-item">
                    Level
                    <strong>{{ $materi->level->nama ?? 'Semua' }}</strong>
                </div>
                <div class="meta-item">
                    Status
                    <strong>{{ $hasKuis ? 'Kuis Tersedia' : 'Tanpa Kuis' }}</strong>
                </div>
            </div>

            <div class="action-bar">
                <a href="{{ route('dashboard.siswa.materi.read', $materi->id) }}" class="btn btn-primary pill-btn">Mulai Membaca</a>
                @if($inRak)
                    <form action="{{ route('dashboard.siswa.rak-buku.remove', $materi->id) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-secondary pill-btn" type="submit">Hapus dari Rak</button>
                    </form>
                @else
                    <form action="{{ route('dashboard.siswa.rak-buku.add') }}" method="post">
                        @csrf
                        <input type="hidden" name="materi_id" value="{{ $materi->id }}">
                        <button class="btn btn-secondary pill-btn" type="submit">Tambah ke Rak</button>
                    </form>
                @endif
                @if($hasKuis)
                    @if(($materiKuisList ?? collect())->count() === 1)
                        <a href="{{ route('dashboard.siswa.materi.kuis.show', ['materi' => $materi->id, 'kuis' => $materiKuisList->first()->id]) }}" class="btn btn-primary pill-btn">Mulai Kuis</a>
                    @else
                        <a href="#daftar-kuis-materi" class="btn btn-primary pill-btn">Lihat Daftar Kuis</a>
                    @endif
                @endif
                <a href="{{ route('dashboard.siswa.materi') }}" class="btn btn-secondary pill-btn">Kembali ke Daftar</a>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="content-section" style="border-left:4px solid #B91C1C;">
            <span class="tag" style="background:rgba(185,28,28,0.12); color:#991B1B;">Perhatian</span>
            <p class="section-desc">{{ session('error') }}</p>
        </div>
    @endif

    @if($materi->tipe_konten === 'teks')
        <div class="content-section">
            <h3 class="section-title">Materi</h3>
            <p class="section-desc">{!! nl2br(e($materi->konten_teks)) !!}</p>
        </div>
    @else
        <div class="content-section">
            <h3 class="section-title">File Materi</h3>
            @if($materi->file_url)
                <a href="{{ $materi->file_url }}" class="btn btn-primary" target="_blank" rel="noopener">Unduh / Buka File</a>
            @else
                <p class="section-desc">File tidak tersedia.</p>
            @endif
        </div>
    @endif

    @if(!$hasKuis)
        <p class="section-desc" style="margin-top:0.75rem;">Kuis untuk materi ini belum tersedia.</p>
    @endif

    @if($hasKuis)
        <div class="content-section" id="daftar-kuis-materi">
            <span class="tag">Kuis Materi</span>
            <h3 class="section-title">Daftar Kuis</h3>
            <p class="section-desc">Setiap kuis di bawah ini berdiri sendiri, jadi nilai masing-masing tidak akan tercampur walau materinya sama.</p>

            <div style="display:grid; gap:0.75rem; margin-top:1rem;">
                @foreach(($materiKuisList ?? collect()) as $item)
                    <div style="display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; padding:0.9rem 1rem; border:1px solid rgba(0,0,0,0.08); border-radius:14px; background:#F9FAFB;">
                        <div>
                            <div style="font-weight:700;">{{ $item->judul }}</div>
                            <div class="section-desc" style="margin-top:0.2rem;">{{ $item->deskripsi ?? 'Tanpa deskripsi.' }}</div>
                            <div class="section-desc" style="margin-top:0.2rem;">{{ $item->pertanyaan_count ?? 0 }} soal</div>
                        </div>
                        <a href="{{ route('dashboard.siswa.materi.kuis.show', ['materi' => $materi->id, 'kuis' => $item->id]) }}" class="btn btn-primary">Mulai Kuis</a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="content-section">
        <span class="tag">Catatan Materi</span>
        <h3 class="section-title">Catat lewat Suara</h3>
        <p class="section-desc">Ucapkan "mulai mencatat" untuk mulai, dan "stop mencatat" untuk selesai.</p>

        <div style="display:flex; gap:0.75rem; flex-wrap:wrap; margin-bottom:0.75rem;">
            <button class="btn btn-primary" type="button" id="activateSpeech">Aktifkan Perintah Suara</button>
            <button class="btn btn-secondary" type="button" id="stopSpeech" disabled>Berhenti</button>
            <button class="btn btn-secondary" type="button" id="clearSpeech">Ulangi</button>
        </div>

        <form action="{{ route('dashboard.siswa.catatan.store') }}" method="post">
            @csrf
            <input type="hidden" name="materi_id" value="{{ $materi->id }}">
            <textarea name="isi" id="speechOutput" rows="4" class="form-textarea" style="width:100%; padding:0.75rem; border-radius:12px; border:1px solid var(--color-gray);" placeholder="Ucapkan: 'mulai mencatat' untuk mulai..." readonly></textarea>
            @error('isi')
                <p class="section-desc" style="color:#B91C1C; margin-top:0.5rem;">{{ $message }}</p>
            @enderror
            <div style="margin-top:0.75rem;">
                <button class="btn btn-primary" type="submit">Simpan Catatan Materi</button>
            </div>
        </form>

        <p class="section-desc" id="speechHint" style="margin-top:0.75rem;"></p>
    </div>

    <script>
        (function() {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            const activateBtn = document.getElementById('activateSpeech');
            const stopBtn = document.getElementById('stopSpeech');
            const clearBtn = document.getElementById('clearSpeech');
            const output = document.getElementById('speechOutput');
            const hint = document.getElementById('speechHint');

            let recognition = null;
            let isRecording = false;
            let dictationMode = false;

            function setHint(message, color) {
                hint.textContent = message;
                hint.style.color = color || 'var(--color-text-light)';
            }

            if (!SpeechRecognition) {
                activateBtn.disabled = true;
                stopBtn.disabled = true;
                setHint('Browser belum mendukung speech-to-text. Coba gunakan Chrome.', '#B45309');
                output.removeAttribute('readonly');
                output.placeholder = 'Browser belum mendukung speech-to-text. Kamu bisa ketik manual.';
                return;
            }

            recognition = new SpeechRecognition();
            recognition.lang = localStorage.getItem('aks_asr_lang') || 'id-ID';
            recognition.interimResults = true;
            recognition.continuous = true;

            recognition.onresult = function(event) {
                let transcript = '';
                for (let i = event.resultIndex; i < event.results.length; i += 1) {
                    transcript += event.results[i][0].transcript;
                }
                const normalized = transcript.toLowerCase();

                if (!dictationMode) {
                    if (normalized.includes('mulai mencatat') || normalized.includes('mulai catat')) {
                        dictationMode = true;
                        output.value = '';
                        setHint('Mode catatan aktif. Silakan ucapkan isi catatan.', '#B45309');
                    }
                    return;
                }

                if (normalized.includes('stop mencatat') || normalized.includes('selesai mencatat')) {
                    recognition.stop();
                    dictationMode = false;
                    setHint('Catatan selesai. Kamu bisa simpan.', 'var(--color-text-light)');
                    return;
                }

                const cleaned = transcript
                    .replace(/mulai mencatat|mulai catat/gi, '')
                    .replace(/stop mencatat|selesai mencatat/gi, '')
                    .trim();

                output.value = cleaned;
            };

            recognition.onstart = function() {
                isRecording = true;
                activateBtn.disabled = true;
                stopBtn.disabled = false;
                setHint('Perintah suara aktif. Ucapkan: "mulai mencatat".', '#B45309');
            };

            recognition.onend = function() {
                isRecording = false;
                activateBtn.disabled = false;
                stopBtn.disabled = true;
                if (!dictationMode) {
                    setHint('Perintah suara berhenti. Klik aktifkan untuk mulai lagi.', 'var(--color-text-light)');
                }
            };

            recognition.onerror = function() {
                setHint('Terjadi masalah saat merekam suara. Coba ulangi.', '#B91C1C');
            };

            activateBtn.addEventListener('click', function() {
                if (!isRecording) {
                    recognition.start();
                }
            });

            stopBtn.addEventListener('click', function() {
                if (isRecording) {
                    recognition.stop();
                }
            });

            clearBtn.addEventListener('click', function() {
                output.value = '';
                dictationMode = false;
                setHint('Catatan dikosongkan.', 'var(--color-text-light)');
            });
        })();
    </script>
@endsection
