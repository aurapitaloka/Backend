@extends('dashboard.siswa.layout')

@section('content')


    <style>
        .read-hero {
            background: #fff;
            border-radius: 18px;
            padding: 1.5rem;
            border: 1px solid rgba(0,0,0,0.08);
            box-shadow: 0 10px 24px rgba(0,0,0,0.08);
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .read-hero::before {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 18px;
            border: 2px solid rgba(244,160,0,0.22);
            pointer-events: none;
        }

        .read-hero::after {
            content: "";
            position: absolute;
            inset: 2px;
            border-radius: 16px;
            background: linear-gradient(180deg, rgba(244,160,0,0.12), transparent);
            pointer-events: none;
        }

        .read-toolbar {
            display: flex;
            flex-wrap: nowrap;
            gap: 0.6rem;
            margin-top: 0.75rem;
            position: relative;
            z-index: 1;
            overflow-x: auto;
            scrollbar-width: none;
        }

        .read-toolbar::-webkit-scrollbar {
            height: 0;
        }

        .read-toolbar .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            white-space: nowrap;
            padding: 0.5rem 0.75rem;
            font-size: 0.85rem;
            border-radius: 10px;
        }

        .read-panel {
            background: #fff;
            border-radius: 16px;
            padding: 1.25rem 1.5rem;
            border: 1px solid rgba(0,0,0,0.06);
            box-shadow: 0 6px 18px rgba(0,0,0,0.06);
            position: relative;
            overflow: hidden;
        }

        .read-panel::before {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 16px;
            border: 2px solid rgba(31,41,55,0.08);
            pointer-events: none;
        }

        .read-panel::after {
            content: "";
            position: absolute;
            inset: 2px;
            border-radius: 14px;
            background: linear-gradient(180deg, rgba(31,41,55,0.06), transparent);
            pointer-events: none;
        }

        .read-panel h3 {
            margin-bottom: 0.75rem;
        }

        .read-container {
            max-height: 60vh;
            overflow: auto;
            padding: 1rem;
            border-radius: 14px;
            border: 1px solid var(--color-gray);
            background: #fff;
            line-height: 1.8;
        }

        .read-actions {
            margin-top: 1rem;
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
    </style>

    <div class="read-hero">
        <span class="tag">Sesi Baca</span>
        <h2 class="section-title">{{ $materi->judul }}</h2>
        <p class="section-desc">{{ $materi->deskripsi ?? 'Materi ini belum memiliki deskripsi.' }}</p>

        <div class="read-toolbar">
            <button class="btn btn-primary" type="button" id="ttsStart">
                <i data-lucide="volume-2"></i>
                TTS: Mulai
            </button>
            <button class="btn btn-secondary" type="button" id="ttsStop">
                <i data-lucide="volume-x"></i>
                TTS: Stop
            </button>
            <button class="btn btn-primary" type="button" id="asrStart">
                <i data-lucide="mic"></i>
                ASR: Aktifkan
            </button>
            <button class="btn btn-secondary" type="button" id="asrStop">
                <i data-lucide="mic-off"></i>
                ASR: Stop
            </button>
            <button class="btn btn-secondary" type="button" id="scrollToggle">
                <i data-lucide="move-vertical"></i>
                Auto Scroll: Off
            </button>
            <button class="btn btn-secondary" type="button" id="scrollSpeed">
                <i data-lucide="gauge"></i>
                Kecepatan: Normal
            </button>
        </div>

        <p class="section-desc" id="readHint" style="margin-top:0.75rem;"></p>
    </div>

    @if($materi->tipe_konten === 'teks')
        <div class="read-panel">
            <h3 class="section-title">Materi (Teks)</h3>
            <div id="readingContainer" class="read-container">
                <div id="readingContent">{!! nl2br(e($materi->konten_teks)) !!}</div>
            </div>
        </div>
    @else
        <div class="read-panel">
            <h3 class="section-title">Materi (PDF)</h3>
            @if($materi->file_path)
                <iframe id="pdfFrame" src="{{ asset('storage/' . $materi->file_path) }}" style="width:100%; height:70vh; border:1px solid var(--color-gray); border-radius:14px;"></iframe>
                <p class="section-desc" style="margin-top:0.5rem;">
                    Catatan: Auto-scroll dan TTS untuk PDF tergantung dukungan browser.
                </p>
            @else
                <p class="section-desc">File tidak tersedia.</p>
            @endif
        </div>
    @endif

    <div class="read-actions">
        <a href="{{ route('dashboard.siswa.materi.show', $materi->id) }}" class="btn btn-secondary">Kembali ke Detail</a>
        <a href="{{ route('dashboard.siswa.materi') }}" class="btn btn-secondary">Kembali ke Daftar</a>
    </div>

    <script>
        (function() {
            const ttsStart = document.getElementById('ttsStart');
            const ttsStop = document.getElementById('ttsStop');
            const asrStart = document.getElementById('asrStart');
            const asrStop = document.getElementById('asrStop');
            const scrollToggle = document.getElementById('scrollToggle');
            const scrollSpeedBtn = document.getElementById('scrollSpeed');
            const hint = document.getElementById('readHint');
            const textContent = document.getElementById('readingContent');
            const textContainer = document.getElementById('readingContainer');
            const pdfFrame = document.getElementById('pdfFrame');

            let scrollInterval = null;
            let scrollSpeed = 0.8;
            let isAutoScroll = false;
            let recognition = null;
            let isAsrOn = false;

            function setHint(message, color) {
                hint.textContent = message;
                hint.style.color = color || 'var(--color-text-light)';
            }

            function getScrollTarget() {
                if (textContainer) {
                    return textContainer;
                }
                if (pdfFrame && pdfFrame.contentWindow && pdfFrame.contentWindow.document) {
                    const doc = pdfFrame.contentWindow.document;
                    return doc.scrollingElement || doc.documentElement || doc.body;
                }
                return null;
            }

            function startAutoScroll() {
                if (scrollInterval) {
                    return;
                }
                const target = getScrollTarget();
                if (!target) {
                    setHint('Auto-scroll tidak tersedia untuk file ini.', '#B45309');
                    return;
                }
                scrollInterval = setInterval(() => {
                    target.scrollTop = target.scrollTop + scrollSpeed;
                }, 30);
                isAutoScroll = true;
                scrollToggle.textContent = 'Auto Scroll: On';
            }

            function stopAutoScroll() {
                if (scrollInterval) {
                    clearInterval(scrollInterval);
                    scrollInterval = null;
                }
                isAutoScroll = false;
                scrollToggle.textContent = 'Auto Scroll: Off';
            }

            function cycleSpeed() {
                if (scrollSpeed === 0.8) {
                    scrollSpeed = 1.6;
                    scrollSpeedBtn.textContent = 'Kecepatan: Cepat';
                } else if (scrollSpeed === 1.6) {
                    scrollSpeed = 0.4;
                    scrollSpeedBtn.textContent = 'Kecepatan: Pelan';
                } else {
                    scrollSpeed = 0.8;
                    scrollSpeedBtn.textContent = 'Kecepatan: Normal';
                }
            }

            function startTts() {
                if (!('speechSynthesis' in window)) {
                    setHint('TTS tidak didukung di browser ini.', '#B45309');
                    return;
                }
                const text = textContent ? textContent.textContent.trim() : '';
                if (!text) {
                    setHint('TTS untuk PDF belum tersedia.', '#B45309');
                    return;
                }
                window.speechSynthesis.cancel();
                const utterance = new SpeechSynthesisUtterance(text);
                utterance.lang = localStorage.getItem('aks_tts_lang') || 'id-ID';
                const rate = parseFloat(localStorage.getItem('aks_tts_rate') || '1.0');
                utterance.rate = isNaN(rate) ? 1.0 : rate;
                window.speechSynthesis.speak(utterance);
                setHint('TTS aktif.', '#B45309');
            }

            function stopTts() {
                if ('speechSynthesis' in window) {
                    window.speechSynthesis.cancel();
                }
            }

            function setupAsr() {
                const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                if (!SpeechRecognition) {
                    setHint('ASR tidak didukung di browser ini.', '#B45309');
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
                    const command = transcript.toLowerCase();

                    if (command.includes('mulai') && command.includes('scroll')) {
                        startAutoScroll();
                    } else if (command.includes('berhenti') && command.includes('scroll')) {
                        stopAutoScroll();
                    } else if (command.includes('lebih cepat')) {
                        scrollSpeed = 1.6;
                        scrollSpeedBtn.textContent = 'Kecepatan: Cepat';
                    } else if (command.includes('lebih pelan')) {
                        scrollSpeed = 0.4;
                        scrollSpeedBtn.textContent = 'Kecepatan: Pelan';
                    } else if (command.includes('mulai baca')) {
                        startTts();
                    } else if (command.includes('stop baca')) {
                        stopTts();
                    }
                };

                recognition.onstart = function() {
                    isAsrOn = true;
                    setHint('ASR aktif. Ucapkan: "mulai scroll", "berhenti scroll", "lebih cepat", "lebih pelan".', '#B45309');
                };

                recognition.onend = function() {
                    isAsrOn = false;
                };
            }

            ttsStart.addEventListener('click', startTts);
            ttsStop.addEventListener('click', stopTts);

            scrollToggle.addEventListener('click', function() {
                if (isAutoScroll) {
                    stopAutoScroll();
                } else {
                    startAutoScroll();
                }
            });

            scrollSpeedBtn.addEventListener('click', cycleSpeed);

            asrStart.addEventListener('click', function() {
                if (!recognition) {
                    setupAsr();
                }
                if (recognition && !isAsrOn) {
                    recognition.start();
                }
            });

            asrStop.addEventListener('click', function() {
                if (recognition && isAsrOn) {
                    recognition.stop();
                }
            });

            })();
    </script>
@endsection
