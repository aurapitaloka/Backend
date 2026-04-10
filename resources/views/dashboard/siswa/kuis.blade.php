@extends('dashboard.siswa.layout')

@section('content')
    <style>
        .no-stripe::before {
            display: none;
        }

        .quiz-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 1.25rem;
        }

        .quiz-hero-row {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .quiz-hero-icon {
            width: 52px;
            height: 52px;
            border-radius: 16px;
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

        .quiz-panel {
            background: #fff;
            border: 1px solid rgba(0,0,0,0.08);
            border-radius: 16px;
            padding: 1.25rem;
            box-shadow: 0 10px 22px rgba(0,0,0,0.08);
        }

        .quiz-panel::before {
            display: none;
        }

        .seat {
            background: #F8FAFC;
        }

        .seat.active {
            background: rgba(244,160,0,0.25);
            border-color: #F59E0B;
            font-weight: 700;
        }

        .quiz-controls .btn,
        .quiz-actions .btn {
            border-radius: 999px;
            padding: 0.6rem 1.2rem;
            box-shadow: 0 8px 18px rgba(0,0,0,0.08);
            transition: transform 0.2s ease, box-shadow 0.2s ease, opacity 0.2s ease;
        }

        .quiz-controls .btn:hover,
        .quiz-actions .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 22px rgba(0,0,0,0.12);
        }

        .quiz-controls .btn:disabled,
        .quiz-actions .btn:disabled {
            opacity: 0.55;
            cursor: not-allowed;
            box-shadow: none;
            transform: none;
        }

        .btn-ghost {
            background: #F9FAFB;
            color: var(--color-text);
            border: 1px solid rgba(0,0,0,0.08);
        }
    </style>

    <div class="quiz-header">
        <div class="quiz-hero-row">
            <div class="quiz-hero-icon"><i data-lucide="clipboard-check"></i></div>
            <div class="quiz-hero-text">
                <h2 class="section-title">{{ $displayTitle ?? ($kuis->judul ?? 'Kuis') }}</h2>
                <p class="section-desc">Satu soal per halaman. Kamu bisa jawab dengan suara.</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="section-card no-stripe" style="margin-top:1rem; border-left-color:#16A34A;">
            <span class="tag" style="background:rgba(22,163,74,0.12); color:#166534;">Sukses</span>
            <p class="section-desc">{{ session('success') }}</p>
        </div>
    @endif

    @if(!$kuis)
        <div class="section-card no-stripe" style="margin-top:1.5rem;">
            <p class="section-desc">Kuis belum tersedia.</p>
            <a href="{{ $backUrl ?? route('dashboard.siswa.kuis') }}" class="btn btn-secondary">Kembali</a>
        </div>
    @else
        <style>
            .quiz-wrap {
                display: grid;
                grid-template-columns: minmax(0, 1fr) 240px;
                gap: 1.25rem;
                margin-top: 1.5rem;
            }
            .quiz-panel {
                background: #fff;
                border: 1px solid var(--color-gray);
                border-radius: 16px;
                padding: 1.25rem;
            }
            .seat-grid {
                display: grid;
                grid-template-columns: repeat(5, 1fr);
                gap: 0.5rem;
            }
            .seat {
                height: 34px;
                border-radius: 8px;
                border: 1px solid var(--color-gray);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.85rem;
                cursor: pointer;
                background: #F9FAFB;
            }
            .seat.active {
                background: #FDE68A;
                border-color: #F59E0B;
                font-weight: 700;
            }
            .seat.answered {
                background: #DCFCE7;
                border-color: #16A34A;
            }
            .seat.skipped {
                background: #FEE2E2;
                border-color: #EF4444;
            }
            .quiz-controls {
                display: flex;
                gap: 0.5rem;
                flex-wrap: wrap;
                margin-top: 1rem;
            }
            .asr-hint {
                margin-top: 0.75rem;
                font-size: 0.9rem;
                color: var(--color-text-light);
            }
            .asr-status {
                margin-top: 0.5rem;
                font-size: 0.9rem;
                color: #B45309;
            }
            @media (max-width: 900px) {
                .quiz-wrap {
                    grid-template-columns: 1fr;
                }
            }
        </style>

        <form action="{{ $submitRoute ?? route('dashboard.siswa.kuis.submit', $kuis->id) }}" method="post" id="quizForm">
            @csrf
            <div class="quiz-wrap">
                <div class="quiz-panel">
                    @foreach($kuis->pertanyaan as $index => $pertanyaan)
                        <div class="quiz-question" data-index="{{ $index }}" data-id="{{ $pertanyaan->id }}" data-type="{{ $pertanyaan->tipe }}" style="{{ $index === 0 ? '' : 'display:none;' }}">
                            <span class="tag">Pertanyaan {{ $index + 1 }}</span>
                            <h3 class="section-title" style="margin-bottom:0.75rem;">{{ $pertanyaan->pertanyaan }}</h3>

                            @if($pertanyaan->tipe === 'listening' || $pertanyaan->tipe === 'speaking')
                                <div style="margin-bottom:0.75rem;">
                                    @if($pertanyaan->audio_path)
                                        <audio controls src="{{ asset('storage/' . $pertanyaan->audio_path) }}"></audio>
                                    @elseif($pertanyaan->audio_text)
                                        <button class="btn btn-secondary tts-btn" type="button" data-text="{{ $pertanyaan->audio_text }}" data-lang="{{ $pertanyaan->bahasa ?? 'en-US' }}">Putar Audio (TTS)</button>
                                    @else
                                        <p class="section-desc">Audio belum tersedia.</p>
                                    @endif
                                </div>
                            @endif

                            @if($pertanyaan->tipe === 'essay')
                                <textarea name="jawaban_teks[{{ $pertanyaan->id }}]" rows="4" class="form-textarea" style="width:100%; padding:0.75rem; border-radius:12px; border:1px solid var(--color-gray);" placeholder="Jawab dengan suara atau ketik..." data-lang="{{ $pertanyaan->bahasa ?? 'id-ID' }}"></textarea>
                                <p class="section-desc" style="margin-top:0.5rem;">Keyword penilaian otomatis: {{ $pertanyaan->keyword }}</p>
                            @elseif($pertanyaan->tipe === 'speaking')
                                <div style="display:flex; gap:0.5rem; flex-wrap:wrap; margin-bottom:0.5rem;">
                                    <button class="btn btn-secondary speak-record" type="button" data-lang="{{ $pertanyaan->bahasa ?? 'en-US' }}">Mulai Rekam</button>
                                    <button class="btn btn-secondary speak-stop" type="button">Stop</button>
                                </div>
                                <textarea name="jawaban_teks[{{ $pertanyaan->id }}]" rows="3" class="form-textarea speak-output" style="width:100%; padding:0.75rem; border-radius:12px; border:1px solid var(--color-gray);" placeholder="Hasil ucapan akan muncul di sini..." data-lang="{{ $pertanyaan->bahasa ?? 'en-US' }}"></textarea>
                                <input type="hidden" name="skor_auto[{{ $pertanyaan->id }}]" class="speak-score">
                                <p class="section-desc speak-feedback" style="margin-top:0.5rem;"></p>
                            @else
                                @foreach($pertanyaan->opsi as $opsi)
                                    <label style="display:flex; gap:0.6rem; align-items:flex-start; margin-bottom:0.5rem;">
                                        <input type="radio" name="jawaban[{{ $pertanyaan->id }}]" value="{{ $opsi->id }}" data-label="{{ strtoupper($opsi->label) }}">
                                        <span class="section-desc" style="margin:0;">
                                            <strong>{{ $opsi->label }}.</strong> {{ $opsi->teks }}
                                        </span>
                                    </label>
                                @endforeach
                            @endif
                        </div>
                    @endforeach

                    <div class="quiz-controls">
                        <button class="btn btn-ghost" type="button" id="prevBtn">Sebelumnya</button>
                        <button class="btn btn-secondary" type="button" id="skipBtn">Lewati</button>
                        <button class="btn btn-primary" type="button" id="nextBtn">Selanjutnya</button>
                    </div>

                    <p class="asr-status" id="asrStatus"></p>
                    <p class="asr-hint" id="asrHint">
                        Perintah suara: "jawab A/B/C/D", "selanjutnya", "sebelumnya", "lewati", "nomor 3".
                    </p>

                    <div class="quiz-actions" style="margin-top:1.5rem; display:flex; gap:0.5rem; flex-wrap:wrap;">
                        <button class="btn btn-primary" type="submit">Kirim Jawaban</button>
                        <a href="{{ $backUrl ?? route('dashboard.siswa.kuis') }}" class="btn btn-ghost">Kembali</a>
                    </div>
                </div>

                <div class="quiz-panel">
                    <div class="section-title" style="margin-bottom:0.5rem;">Peta Soal</div>
                    <p class="section-desc" style="margin-bottom:0.75rem;">Klik nomor untuk lompat.</p>
                    <div class="seat-grid" id="seatGrid">
                        @foreach($kuis->pertanyaan as $index => $pertanyaan)
                            <div class="seat {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}">#{{ $index + 1 }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </form>
        <script>
            (function() {
                const questions = Array.from(document.querySelectorAll('.quiz-question'));
                const seatGrid = document.getElementById('seatGrid');
                const seats = Array.from(document.querySelectorAll('.seat'));
                const prevBtn = document.getElementById('prevBtn');
                const nextBtn = document.getElementById('nextBtn');
                const skipBtn = document.getElementById('skipBtn');
                const asrStatus = document.getElementById('asrStatus');
                const asrHint = document.getElementById('asrHint');

                let currentIndex = 0;
                const skipped = new Set();

                function updateNavButtons() {
                    prevBtn.disabled = currentIndex <= 0;
                    const atLast = currentIndex >= questions.length - 1;
                    nextBtn.disabled = atLast;
                    skipBtn.disabled = atLast;
                }

                function showQuestion(index) {
                    if (index < 0 || index >= questions.length) return;
                    questions[currentIndex].style.display = 'none';
                    currentIndex = index;
                    questions[currentIndex].style.display = 'block';
                    seats.forEach(seat => seat.classList.remove('active'));
                    const activeSeat = seats[currentIndex];
                    if (activeSeat) activeSeat.classList.add('active');
                    const q = questions[currentIndex];
                    const textarea = q.querySelector('textarea');
                    if (recognition) {
                        recognition.lang = textarea ? (textarea.dataset.lang || 'id-ID') : 'id-ID';
                    }
                    updateNavButtons();
                }

                function updateSeatState(index) {
                    const q = questions[index];
                    const seat = seats[index];
                    if (!q || !seat) return;
                    const checked = q.querySelector('input[type="radio"]:checked');
                    const textarea = q.querySelector('textarea');
                    const hasEssay = textarea && textarea.value.trim().length > 0;
                    seat.classList.remove('answered', 'skipped');
                    if (checked || hasEssay) {
                        seat.classList.add('answered');
                        skipped.delete(index);
                    } else if (skipped.has(index)) {
                        seat.classList.add('skipped');
                    }
                }

                questions.forEach((q, idx) => {
                    q.querySelectorAll('input[type="radio"]').forEach(radio => {
                        radio.addEventListener('change', () => updateSeatState(idx));
                    });
                    const textarea = q.querySelector('textarea');
                    if (textarea) {
                        textarea.addEventListener('input', () => updateSeatState(idx));
                    }
                });

                seats.forEach(seat => {
                    seat.addEventListener('click', () => {
                        const idx = parseInt(seat.dataset.index, 10);
                        showQuestion(idx);
                    });
                });

                prevBtn.addEventListener('click', () => showQuestion(currentIndex - 1));
                nextBtn.addEventListener('click', () => showQuestion(currentIndex + 1));
                skipBtn.addEventListener('click', () => {
                    skipped.add(currentIndex);
                    updateSeatState(currentIndex);
                    showQuestion(currentIndex + 1);
                });

                // ASR
                const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                let recognition = null;
                let isOn = false;
                let shouldStayOn = false;
                let commandSuspended = false;
                let hasUserGesture = false;

                function normalize(text) {
                    return (text || '')
                        .toLowerCase()
                        .replace(/[^\w\s-]/g, ' ')
                        .replace(/\s+/g, ' ')
                        .trim();
                }

                function pickAnswer(label) {
                    const q = questions[currentIndex];
                    if (!q) return;
                    const input = q.querySelector(`input[data-label="${label}"]`);
                    if (input) {
                        input.checked = true;
                        updateSeatState(currentIndex);
                    }
                }

                function appendEssay(text) {
                    const q = questions[currentIndex];
                    if (!q) return;
                    const textarea = q.querySelector('textarea');
                    if (textarea) {
                        textarea.value = text;
                    }
                }

                function gotoNumber(num) {
                    const idx = num - 1;
                    if (idx >= 0 && idx < questions.length) {
                        showQuestion(idx);
                    }
                }

                function handleCommand(text) {
                    if (text.includes('selanjutnya') || text.includes('lanjut')) {
                        showQuestion(currentIndex + 1);
                        return;
                    }
                    if (text.includes('sebelumnya') || text.includes('kembali')) {
                        showQuestion(currentIndex - 1);
                        return;
                    }
                    if (text.includes('lewati')) {
                        skipped.add(currentIndex);
                        updateSeatState(currentIndex);
                        showQuestion(currentIndex + 1);
                        return;
                    }
                    if (text.includes('jawab')) {
                        const q = questions[currentIndex];
                        const type = q ? q.dataset.type : '';
                        const match = text.match(/jawab\s+([a-d])/);
                        if (match && type !== 'essay') {
                            pickAnswer(match[1].toUpperCase());
                            return;
                        }
                        if (type === 'essay') {
                            const cleaned = text.replace(/jawab/gi, '').trim();
                            if (cleaned) {
                                appendEssay(cleaned);
                                return;
                            }
                        }
                    }
                    const numMatch = text.match(/nomor\s+(\d+)/);
                    if (numMatch) {
                        gotoNumber(parseInt(numMatch[1], 10));
                    }
                }

                function setupAsr() {
                    if (!SpeechRecognition) {
                        asrStatus.textContent = 'ASR tidak didukung di browser ini.';
                        return;
                    }
                    recognition = new SpeechRecognition();
                    recognition.lang = localStorage.getItem('aks_asr_lang') || 'id-ID';
                    recognition.continuous = true;
                    recognition.interimResults = true;

                    recognition.onresult = function(event) {
                        let transcript = '';
                        for (let i = event.resultIndex; i < event.results.length; i += 1) {
                            transcript += event.results[i][0].transcript;
                        }
                        const command = normalize(transcript);
                        if (command) {
                            handleCommand(command);
                        }
                        const q = questions[currentIndex];
                        if (q && q.dataset.type === 'essay') {
                            const textarea = q.querySelector('textarea');
                            if (textarea) {
                                textarea.value = transcript;
                            }
                        }
                    };

                    recognition.onstart = function() {
                        isOn = true;
                        asrStatus.textContent = 'ASR aktif. Ucapkan perintah.';
                    };

                    recognition.onend = function() {
                        isOn = false;
                        if (commandSuspended) {
                            return;
                        }
                        if (shouldStayOn && hasUserGesture) {
                            setTimeout(() => {
                                try {
                                    recognition.start();
                                } catch (err) {
                                    // ignore
                                }
                            }, 500);
                        }
                    };

                    recognition.onerror = function() {
                        isOn = false;
                        asrStatus.textContent = 'ASR bermasalah. Coba aktifkan ulang.';
                    };
                }

                function tryStartAsr() {
                    if (!recognition) {
                        setupAsr();
                    }
                    if (recognition && !isOn) {
                        try {
                            recognition.start();
                        } catch (err) {
                            // ignore
                        }
                    }
                }

                // Auto-enable ASR if user already enabled voice nav earlier.
                try {
                    const saved = localStorage.getItem('voiceNavEnabled');
                    if (saved === '1') {
                        shouldStayOn = true;
                    }
                } catch (err) {
                    // ignore
                }

                // Start ASR after first user gesture (browser policy).
                document.addEventListener('click', function onFirstClick() {
                    hasUserGesture = true;
                    if (shouldStayOn) {
                        tryStartAsr();
                    }
                    document.removeEventListener('click', onFirstClick);
                });

                // TTS button for listening/speaking
                document.querySelectorAll('.tts-btn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const text = btn.dataset.text || '';
                        const lang = btn.dataset.lang || 'en-US';
                        if (!('speechSynthesis' in window)) {
                            return;
                        }
                        window.speechSynthesis.cancel();
                        const utterance = new SpeechSynthesisUtterance(text);
                        utterance.lang = lang;
                        window.speechSynthesis.speak(utterance);
                    });
                });

                // Speaking ASR (separate from command ASR)
                const speakRecognition = SpeechRecognition ? new SpeechRecognition() : null;
                let speakingQuestion = null;
                if (speakRecognition) {
                    speakRecognition.continuous = false;
                    speakRecognition.interimResults = false;
                }

                function similarity(a, b) {
                    const norm = (s) => (s || '').toLowerCase().replace(/[^\w\s-]/g, ' ').replace(/\s+/g, ' ').trim();
                    a = norm(a);
                    b = norm(b);
                    if (!a || !b) return 0;
                    const maxLen = Math.max(a.length, b.length);
                    if (maxLen === 0) return 0;
                    const dist = levenshtein(a, b);
                    return Math.max(0, Math.min(100, Math.round((1 - dist / maxLen) * 100)));
                }

                function levenshtein(a, b) {
                    const matrix = Array.from({ length: b.length + 1 }, () => []);
                    for (let i = 0; i <= b.length; i += 1) matrix[i][0] = i;
                    for (let j = 0; j <= a.length; j += 1) matrix[0][j] = j;
                    for (let i = 1; i <= b.length; i += 1) {
                        for (let j = 1; j <= a.length; j += 1) {
                            if (b.charAt(i - 1) === a.charAt(j - 1)) {
                                matrix[i][j] = matrix[i - 1][j - 1];
                            } else {
                                matrix[i][j] = Math.min(
                                    matrix[i - 1][j - 1] + 1,
                                    matrix[i][j - 1] + 1,
                                    matrix[i - 1][j] + 1
                                );
                            }
                        }
                    }
                    return matrix[b.length][a.length];
                }

                function speakFeedback(text, lang) {
                    if (!('speechSynthesis' in window)) return;
                    window.speechSynthesis.cancel();
                    const utterance = new SpeechSynthesisUtterance(text);
                    utterance.lang = lang || 'id-ID';
                    window.speechSynthesis.speak(utterance);
                }

                const targetTextMap = @json($kuis->pertanyaan->pluck('jawaban_teks', 'id'));

                document.querySelectorAll('.quiz-question').forEach(q => {
                    if (q.dataset.type !== 'speaking') return;
                    const recordBtn = q.querySelector('.speak-record');
                    const stopBtn = q.querySelector('.speak-stop');
                    const output = q.querySelector('.speak-output');
                    const scoreInput = q.querySelector('.speak-score');
                    const feedback = q.querySelector('.speak-feedback');
                    const lang = output ? (output.dataset.lang || 'en-US') : 'en-US';
                    const pid = q.dataset.id;

                    function computeAndFeedback() {
                        const targetStr = (targetTextMap && targetTextMap[pid]) ? targetTextMap[pid] : '';
                        const score = similarity(output.value, targetStr);
                        scoreInput.value = score;
                        if (score >= 80) {
                            feedback.textContent = `Bagus! Skor ${score}%.`;
                            speakFeedback('Bagus, pelafalanmu benar.', 'id-ID');
                        } else {
                            feedback.textContent = `Masih kurang tepat (skor ${score}%). Coba ulangi.`;
                            speakFeedback('Masih kurang tepat, coba ulangi.', 'id-ID');
                            // play example audio if available
                            const audio = q.querySelector('audio');
                            const ttsBtn = q.querySelector('.tts-btn');
                            if (audio) {
                                audio.currentTime = 0;
                                audio.play();
                            } else if (ttsBtn) {
                                ttsBtn.click();
                            }
                        }
                        updateSeatState(parseInt(q.dataset.index, 10));
                    }

                    if (recordBtn && speakRecognition) {
                        recordBtn.addEventListener('click', () => {
                            speakRecognition.lang = lang;
                            speakingQuestion = q;
                            if (recognition && isOn) {
                                commandSuspended = true;
                                try { recognition.stop(); } catch (e) {}
                            }
                            try { speakRecognition.start(); } catch (e) {}
                        });
                    }

                    if (stopBtn && speakRecognition) {
                        stopBtn.addEventListener('click', () => {
                            try { speakRecognition.stop(); } catch (e) {}
                        });
                    }
                });

                if (speakRecognition) {
                    speakRecognition.onresult = function(event) {
                        const transcript = event.results[0][0].transcript || '';
                        if (!speakingQuestion) return;
                        const output = speakingQuestion.querySelector('.speak-output');
                        const scoreInput = speakingQuestion.querySelector('.speak-score');
                        const feedback = speakingQuestion.querySelector('.speak-feedback');
                        const pid = speakingQuestion.dataset.id;
                        if (output) {
                            output.value = transcript;
                        }
                        const targetStr = (targetTextMap && targetTextMap[pid]) ? targetTextMap[pid] : '';
                        const score = similarity(transcript, targetStr);
                        if (scoreInput) {
                            scoreInput.value = score;
                        }
                        if (feedback) {
                            if (score >= 80) {
                                feedback.textContent = `Bagus! Skor ${score}%.`;
                                speakFeedback('Bagus, pelafalanmu benar.', 'id-ID');
                            } else {
                                feedback.textContent = `Masih kurang tepat (skor ${score}%). Coba ulangi.`;
                                speakFeedback('Masih kurang tepat, coba ulangi.', 'id-ID');
                            }
                        }
                        const audio = speakingQuestion.querySelector('audio');
                        const ttsBtn = speakingQuestion.querySelector('.tts-btn');
                        if (score < 80) {
                            if (audio) {
                                audio.currentTime = 0;
                                audio.play();
                            } else if (ttsBtn) {
                                ttsBtn.click();
                            }
                        }
                        updateSeatState(parseInt(speakingQuestion.dataset.index, 10));
                    };

                    speakRecognition.onend = function() {
                        commandSuspended = false;
                        if (shouldStayOn && hasUserGesture) {
                            try { recognition.start(); } catch (e) {}
                        }
                    };
                }

                // init seat states
                questions.forEach((_, idx) => updateSeatState(idx));
                updateNavButtons();
                asrHint.textContent = 'Perintah suara: "jawab A/B/C/D", "selanjutnya", "sebelumnya", "lewati", "nomor 3".';
            })();
        </script>
    @endif
@endsection
