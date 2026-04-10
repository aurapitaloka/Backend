@extends('dashboard.siswa.layout')

@section('content')
    <style>
        .notes-page .no-stripe::before {
            display: none;
        }

        .notes-hero {
            background: linear-gradient(135deg, #f7f4ee 0%, #fff7e2 100%);
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 16px;
            padding: 1.1rem 1.2rem;
            box-shadow: 0 12px 26px rgba(15, 23, 42, 0.08);
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
        }

        .notes-hero h2 {
            margin-bottom: 0.35rem;
        }

        .notes-hero .notes-meta {
            display: inline-flex;
            gap: 0.45rem;
            flex-wrap: wrap;
        }

        .notes-hero .meta-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.35rem 0.75rem;
            border-radius: 999px;
            background: rgba(15, 23, 42, 0.08);
            font-size: 0.8rem;
            font-weight: 600;
            color: #1f2937;
        }

        .notes-layout {
            display: grid;
            grid-template-columns: minmax(260px, 1fr) minmax(280px, 1.1fr);
            gap: 1.2rem;
            margin-top: 1.2rem;
        }

        @media (max-width: 980px) {
            .notes-layout {
                grid-template-columns: 1fr;
            }
        }

        .note-panel {
            background: #ffffff;
            border-radius: 18px;
            border: 1px solid rgba(15, 23, 42, 0.08);
            padding: 1.1rem 1.2rem;
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.08);
        }

        .note-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.3rem 0.7rem;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 600;
            background: rgba(15, 23, 42, 0.08);
            color: #1f2937;
        }

        .note-actions {
            display: flex;
            gap: 0.6rem;
            flex-wrap: wrap;
            margin-top: 0.6rem;
        }

        .note-actions .btn {
            border-radius: 999px;
            padding: 0.55rem 1.1rem;
        }

        .note-editor {
            position: sticky;
            top: 1.2rem;
        }

        .notes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .note-card {
            background: linear-gradient(160deg, #ffffff 0%, #f7f5f0 100%);
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 16px;
            padding: 1rem;
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.08);
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
            position: relative;
            overflow: hidden;
        }

        .note-card::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, rgba(251, 191, 36, 0.9), rgba(250, 204, 21, 0.25));
        }

        .note-card .note-time {
            font-size: 0.82rem;
            color: var(--color-text-light);
        }

        .notes-list-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .notes-count {
            font-size: 0.85rem;
            font-weight: 600;
            color: #4b5563;
            background: rgba(15, 23, 42, 0.06);
            padding: 0.35rem 0.7rem;
            border-radius: 999px;
        }
    </style>

    <div class="notes-page">
        <div class="notes-hero">
            <div>
                <h2 class="section-title">Catatan</h2>
                <p class="section-desc">Tambah catatan lewat suara, tanpa perlu mengetik. Materi bisa dipilih (opsional).</p>
            </div>
            <div class="notes-meta">
                <span class="meta-pill">🎙️ Voice Note</span>
                <span class="meta-pill">📌 Fokus Belajar</span>
            </div>
        </div>

        @if(session('success'))
            <div class="note-panel no-stripe" style="margin-top:1rem; border-left:4px solid #16A34A;">
                <span class="note-chip" style="background:rgba(22,163,74,0.12); color:#166534;">Sukses</span>
                <p class="section-desc" style="margin-top:0.5rem;">{{ session('success') }}</p>
            </div>
        @endif

        <div class="notes-layout">
            <div class="note-panel no-stripe note-editor">
                <span class="note-chip">Catatan Baru</span>
                <h3 class="section-title" style="margin-top:0.6rem;">Rekam Suara</h3>
                <p class="section-desc">Klik tombol mulai, ucapkan catatanmu, lalu simpan.</p>

                <form action="{{ route('dashboard.siswa.catatan.store') }}" method="post">
                    @csrf
                    <label class="section-desc" for="materi_id" style="display:block; margin-bottom:0.4rem;">Pilih Materi (Opsional)</label>
                    <select name="materi_id" id="materi_id" class="form-select" style="width:100%; padding:0.65rem 0.75rem; border-radius:12px; border:1px solid var(--color-gray); margin-bottom:0.75rem;">
                        <option value="">-- Catatan Umum --</option>
                        @foreach($materiList as $materi)
                            <option value="{{ $materi->id }}" {{ old('materi_id') == $materi->id ? 'selected' : '' }}>
                                {{ $materi->judul }}
                            </option>
                        @endforeach
                    </select>
                    @error('materi_id')
                        <p class="section-desc" style="color:#B91C1C; margin-top:0.25rem;">{{ $message }}</p>
                    @enderror

                    <div class="note-actions">
                        <button class="btn btn-primary" type="button" id="activateSpeech">Aktifkan Perintah Suara</button>
                        <button class="btn btn-secondary" type="button" id="stopSpeech" disabled>Berhenti</button>
                        <button class="btn btn-secondary" type="button" id="clearSpeech">Ulangi</button>
                    </div>

                    <textarea name="isi" id="speechOutput" rows="5" class="form-textarea" style="width:100%; padding:0.75rem; border-radius:12px; border:1px solid var(--color-gray);" placeholder="Ucapkan: 'mulai mencatat' untuk mulai..." readonly>{{ old('isi') }}</textarea>
                    @error('isi')
                        <p class="section-desc" style="color:#B91C1C; margin-top:0.5rem;">{{ $message }}</p>
                    @enderror
                    <div class="note-actions">
                        <button class="btn btn-primary" type="submit">Simpan Catatan</button>
                        <a href="{{ route('dashboard.siswa') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
                    </div>
                </form>

                <p class="section-desc" id="speechHint" style="margin-top:0.75rem;"></p>
            </div>

            <div class="note-panel no-stripe">
                <div class="notes-list-header">
                    <h3 class="section-title">Catatan Tersimpan</h3>
                    <span class="notes-count">{{ $catatan->total() }} Catatan</span>
                </div>

                @if($catatan->count() === 0)
                    <p class="section-desc" style="margin-top:0.75rem;">Belum ada catatan tersimpan.</p>
                @else
                    <div class="notes-grid">
                        @foreach($catatan as $item)
                            <article class="note-card no-stripe">
                                <span class="note-chip">{{ $item->materi->judul ?? 'Catatan Umum' }}</span>
                                <p class="section-desc">{{ $item->isi }}</p>
                                <p class="note-time">
                                    {{ $item->created_at->format('d M Y, H:i') }}
                                </p>
                                <form action="{{ route('dashboard.siswa.catatan.destroy', $item->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-secondary" type="submit">Hapus</button>
                                </form>
                            </article>
                        @endforeach
                    </div>

                    <div style="margin-top:1.5rem;">
                        {{ $catatan->links() }}
                    </div>
                @endif
            </div>
        </div>
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
