@extends('dashboard.siswa.layout')

@section('content')
    <style>
        .settings-page .section-card::before {
            display: none;
        }

        .settings-hero {
            background: linear-gradient(135deg, rgba(244,160,0,0.12), rgba(31,41,55,0.04));
            border-radius: 18px;
            padding: 1.5rem;
            border: 1px solid rgba(0,0,0,0.08);
            box-shadow: 0 10px 24px rgba(0,0,0,0.08);
            margin-bottom: 1.25rem;
        }

        .settings-card {
            border-radius: 16px;
            border: 1px solid rgba(0,0,0,0.08);
            box-shadow: 0 8px 18px rgba(0,0,0,0.08);
        }

        .settings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .settings-field label {
            display: inline-block;
            margin-bottom: 0.35rem;
            font-weight: 600;
            color: var(--color-text);
        }

        .settings-input {
            width: 100%;
            padding: 0.65rem 0.75rem;
            border-radius: 12px;
            border: 1px solid var(--color-gray);
            background: #fff;
        }

        .settings-actions {
            margin-top: 1rem;
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
    </style>

    <div class="settings-page">
        <div class="settings-hero">
            <h2 class="section-title">Pengaturan Aksesibilitas</h2>
            <p class="section-desc">Atur bahasa ASR/TTS dan preferensi suara.</p>
        </div>

        @if(session('success'))
            <div class="section-card settings-card" style="margin-top:1rem;">
                <span class="tag" style="background:rgba(22,163,74,0.12); color:#166534;">Sukses</span>
                <p class="section-desc">{{ session('success') }}</p>
            </div>
        @endif

        <div class="section-card settings-card" style="margin-top:1rem;">
            <span class="tag">Kelas</span>
            <h3 class="section-title">Pilih Kelas</h3>
            <p class="section-desc">Digunakan untuk menampilkan mata pelajaran yang relevan.</p>

            <form action="{{ route('dashboard.siswa.kelas.update') }}" method="post" style="margin-top:0.75rem;">
                @csrf
                <div class="settings-grid">
                    <div class="settings-field">
                        <label for="levelId">Kelas</label>
                        <select id="levelId" name="level_id" class="form-select settings-input" required>
                            <option value="">Pilih Kelas</option>
                            @foreach($levels ?? [] as $level)
                                <option value="{{ $level->id }}" {{ ($user->siswa->level_id ?? null) == $level->id ? 'selected' : '' }}>
                                    {{ $level->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="settings-actions">
                    <button class="btn btn-primary" type="submit">Simpan Kelas</button>
                </div>
            </form>
        </div>

        <form action="{{ route('dashboard.siswa.pengaturan.update') }}" method="post" style="margin-top:1.5rem;">
            @csrf
            <div class="section-card settings-card" style="margin-top:1rem;">
                <span class="tag">Suara</span>
                <h3 class="section-title">ASR & TTS</h3>

                <div class="settings-grid">
                    <div class="settings-field">
                        <label for="asrLang">Bahasa ASR</label>
                        <select id="asrLang" name="asr_lang" class="form-select settings-input">
                            <option value="id-ID" {{ ($user->asr_lang ?? 'id-ID') === 'id-ID' ? 'selected' : '' }}>id-ID (Indonesia)</option>
                            <option value="en-US" {{ ($user->asr_lang ?? '') === 'en-US' ? 'selected' : '' }}>en-US (English)</option>
                        </select>
                    </div>
                    <div class="settings-field">
                        <label for="ttsLang">Bahasa TTS</label>
                        <select id="ttsLang" name="tts_lang" class="form-select settings-input">
                            <option value="id-ID" {{ ($user->tts_lang ?? 'id-ID') === 'id-ID' ? 'selected' : '' }}>id-ID (Indonesia)</option>
                            <option value="en-US" {{ ($user->tts_lang ?? '') === 'en-US' ? 'selected' : '' }}>en-US (English)</option>
                        </select>
                    </div>
                    <div class="settings-field">
                        <label for="ttsRate">Kecepatan TTS</label>
                        <input id="ttsRate" name="tts_rate" type="range" min="0.6" max="1.4" step="0.1" value="{{ $user->tts_rate ?? 1.0 }}" style="width:100%;">
                        <div class="section-desc" id="ttsRateLabel">{{ $user->tts_rate ?? 1.0 }}x</div>
                    </div>
                </div>

                <div class="settings-actions">
                    <button class="btn btn-primary" type="submit">Simpan Pengaturan</button>
                    <button class="btn btn-secondary" type="button" id="testTts">Tes TTS</button>
                </div>
            </div>

            <div class="section-card settings-card" style="margin-top:1.5rem;">
                <span class="tag">Perilaku</span>
                <h3 class="section-title">Preferensi</h3>
                <div style="margin-top:0.75rem;">
                    <label class="section-desc">
                        <input type="checkbox" id="autoVoiceNav" name="auto_voice_nav" {{ ($user->auto_voice_nav ?? false) ? 'checked' : '' }}>
                        Otomatis aktifkan Voice Nav jika pernah diaktifkan
                    </label>
                </div>
            </div>
        </form>
    </div>

    <script>
        (function() {
            const ttsLang = document.getElementById('ttsLang');
            const ttsRate = document.getElementById('ttsRate');
            const ttsRateLabel = document.getElementById('ttsRateLabel');
            const testTts = document.getElementById('testTts');

            ttsRate.addEventListener('input', () => {
                ttsRateLabel.textContent = `${ttsRate.value}x`;
            });

            testTts.addEventListener('click', () => {
                if (!('speechSynthesis' in window)) {
                    return;
                }
                window.speechSynthesis.cancel();
                const utterance = new SpeechSynthesisUtterance(
                    ttsLang.value === 'en-US'
                        ? 'This is a T T S test.'
                        : 'Ini adalah tes TTS.'
                );
                utterance.lang = ttsLang.value;
                utterance.rate = parseFloat(ttsRate.value || '1.0');
                window.speechSynthesis.speak(utterance);
            });
        })();
    </script>
@endsection
