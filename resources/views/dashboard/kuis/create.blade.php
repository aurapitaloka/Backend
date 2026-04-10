<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Kuis - AKSES</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --color-primary: #1F2937;
            --color-primary-dark: #111827;
            --color-primary-light: #F9FAFB;
            --color-accent: #F8B803;
            --color-white: #FFFFFF;
            --color-gray-light: #F3F4F6;
            --color-gray: #E5E7EB;
            --color-text: #111827;
            --color-text-light: #6B7280;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--color-gray-light); color: var(--color-text); }
        .dashboard-container { display: flex; min-height: 100vh; }
        .sidebar { width: 280px; background: linear-gradient(180deg, #1F2937 0%, #111827 100%); position: fixed; height: 100vh; left: 0; top: 0; z-index: 1000; display: flex; flex-direction: column; box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15); }
        .sidebar-header { padding: 2rem 1.5rem; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
        .logo-container { display: flex; align-items: center; gap: 1rem; }
        .logo-circle { width: 50px; height: 50px; background: var(--color-white); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); color: var(--color-accent); font-weight: 800; }
        .logo-text { font-size: 1.5rem; font-weight: 800; color: var(--color-white); letter-spacing: 1px; }
        .sidebar-nav { flex: 1; padding: 1.5rem 0; overflow-y: auto; }
        .nav-item { margin: 0.5rem 1rem; border-radius: 12px; transition: all 0.3s ease; }
        .nav-item a { display: flex; align-items: center; gap: 1rem; padding: 1rem 1.25rem; color: var(--color-white); text-decoration: none; font-weight: 500; border-radius: 12px; transition: all 0.3s ease; }
        .nav-item.active { background: rgba(255, 255, 255, 0.08); }
        .nav-item.active a { background: transparent; color: #FFFFFF; font-weight: 600; border-left: 4px solid var(--color-accent); }
        .nav-item:not(.active):hover { background: rgba(255, 255, 255, 0.15); }
        .nav-icon { width: 22px; height: 22px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; color: #CBD5E1; }
        .nav-item.active .nav-icon { color: var(--color-accent); }
        .logout-btn { margin: 1rem; padding: 0.75rem 1.5rem; background: rgba(255, 255, 255, 0.2); border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 12px; color: var(--color-white); font-weight: 500; cursor: pointer; transition: all 0.3s ease; text-align: center; display: flex; align-items: center; gap: 0.5rem; justify-content: center; }
        .logout-btn:hover { background: rgba(255, 255, 255, 0.3); }
        .main-content { flex: 1; margin-left: 280px; min-height: 100vh; display: flex; flex-direction: column; }
        .header-bar { background: linear-gradient(135deg, #1F2937 0%, #111827 100%); padding: 1.5rem 2rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15); }
        .header-title { font-size: 1.6rem; font-weight: 700; color: #FFFFFF; }
        .content-area { flex: 1; padding: 2rem; }
        .page { max-width: 1000px; margin: 0 auto; padding: 0 1rem; }
        .card { background: var(--color-white); border-radius: 16px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); border: 1px solid rgba(0,0,0,0.04); margin-bottom: 1rem; }
        .card-header { display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1rem; }
        .card-title { font-size: 1.25rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem; }
        .card-desc { color: var(--color-text-light); font-size: 0.95rem; margin-top: 0.25rem; }
        .title { font-size: 1.5rem; font-weight: 700; }
        .desc { color: var(--color-text-light); margin-top: 0.35rem; }
        .btn { display: inline-flex; align-items: center; justify-content: center; padding: 0.7rem 1.1rem; border-radius: 12px; font-weight: 600; text-decoration: none; border: none; cursor: pointer; gap: 0.4rem; }
        .btn-primary { background: var(--color-accent); color: #1F2937; }
        .btn-secondary { background: var(--color-gray); color: var(--color-text); }
        .btn-outline { background: #FFFDF5; color: #92400E; border: 2px solid #F8B803; }
        .btn-outline:hover { background: #FFF5DB; }
        .btn-sm { padding: 0.4rem 0.7rem; border-radius: 8px; font-size: 0.85rem; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; font-weight: 600; margin-bottom: 0.4rem; }
        input[type="text"], textarea, select { width: 100%; padding: 0.75rem 0.9rem; border-radius: 10px; border: 1px solid var(--color-gray); background: #fff; }
        input[type="text"]:focus, textarea:focus, select:focus { outline: none; border-color: #F8B803; box-shadow: 0 0 0 3px rgba(248, 184, 3, 0.15); }
        .question { border: 1px solid var(--color-gray); border-radius: 14px; padding: 1.1rem; margin-top: 1rem; background: #FFFEF7; border-left: 4px solid var(--color-accent); }
        .option-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 0.75rem; }
        .actions { display: flex; gap: 0.5rem; flex-wrap: wrap; }
        .error { color: #B91C1C; font-size: 0.9rem; margin-top: 0.35rem; }
        .section-title { font-size: 1.15rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem; }
        .section-subtitle { color: var(--color-text-light); font-size: 0.95rem; margin-top: 0.25rem; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1rem; }
        .field-hint { font-size: 0.8rem; color: var(--color-text-light); margin-top: 0.25rem; }
        .divider { height: 1px; background: var(--color-gray); margin: 1rem 0; }
        .question-header { display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; margin-bottom: 0.75rem; }
        .question-title { font-weight: 700; }
        .question-badge { font-size: 0.75rem; background: rgba(248, 184, 3, 0.15); color: #B35E00; padding: 0.2rem 0.5rem; border-radius: 999px; }
        .section-note { background: #F9FAFB; border: 1px dashed #D1D5DB; border-radius: 12px; padding: 0.75rem 1rem; font-size: 0.9rem; color: var(--color-text-light); }
        .form-actions { display: flex; gap: 0.75rem; flex-wrap: wrap; justify-content: flex-start; }
        .btn-ghost { background: transparent; border: 1px solid var(--color-gray); color: var(--color-text); }
        .btn-ghost:hover { background: #F9FAFB; }
        .inline-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.75rem; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo-container">
                    <div class="logo-circle"><img src="{{ asset('images/image.png') }}" alt="AKSES Logo"></div>
                    <div class="logo-text">AKSES</div>
                </div>
            </div>
            @include('components.sidebar')
            <div class="logout-btn" onclick="handleLogout()">
                <i data-lucide="log-out"></i>
                <span>Keluar</span>
            </div>
        </aside>

        <main class="main-content">
            <header class="header-bar">
                <h1 class="header-title">Buat Kuis</h1>
            </header>
            <div class="content-area">
            <div class="page">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <div class="title"><i data-lucide="clipboard-list"></i> Buat Kuis</div>
                            <div class="desc">Lengkapi informasi kuis dan tambahkan pertanyaan.</div>
                        </div>
                    </div>
                </div>

                @if($errors->any())
                    <div class="card" style="border-left:4px solid #B91C1C;">
                        <div class="desc">Periksa kembali input berikut.</div>
                        <ul style="margin-top:0.5rem; padding-left:1.2rem;">
                            @foreach($errors->all() as $error)
                                <li class="error">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

        <form action="{{ route('kuis.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <div class="section-title"><i data-lucide="file-text"></i> Informasi Kuis</div>
                                <div class="section-subtitle">Atur judul, materi, dan status kuis.</div>
                            </div>
                            <span class="question-badge">Wajib</span>
                        </div>
                        <div class="divider"></div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="judul">Judul Kuis</label>
                                <input type="text" id="judul" name="judul" value="{{ old('judul') }}" required>
                                <div class="field-hint">Contoh: Kuis Bahasa Indonesia Bab 1</div>
                            </div>
                            <div class="form-group">
                                <label for="materi_id">Materi (Opsional)</label>
                                <select id="materi_id" name="materi_id">
                                    <option value="">-- Tanpa Materi --</option>
                                    @foreach($materiList as $materi)
                                        <option value="{{ $materi->id }}" {{ old('materi_id') == $materi->id ? 'selected' : '' }}>{{ $materi->judul }}</option>
                                    @endforeach
                                </select>
                                <div class="field-hint">Pilih materi jika kuis terkait materi tertentu.</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Status Kuis</label>
                            <div class="inline-row">
                                <label style="display:flex; align-items:center; gap:0.5rem; font-weight:600;">
                                    <input type="checkbox" name="status_aktif" {{ old('status_aktif', true) ? 'checked' : '' }}>
                                    Aktifkan Kuis
                                </label>
                                <div class="field-hint">Jika nonaktif, kuis tidak tampil ke siswa.</div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div>
                                <div class="section-title"><i data-lucide="help-circle"></i> Pertanyaan</div>
                                <div class="section-subtitle">Minimal 1 pertanyaan dengan opsi A-D.</div>
                            </div>
                            <button type="button" class="btn btn-outline btn-sm" id="addQuestion">
                                <i data-lucide="plus-circle"></i>
                                Tambah
                            </button>
                        </div>
                        <div class="section-note">Tips: Gunakan tipe soal yang sesuai. Pilihan ganda untuk evaluasi cepat, essay untuk jawaban mendalam, listening/speaking untuk latihan audio.</div>
                        <div id="questionContainer"></div>
                    </div>

                    <div class="card">
                        <div class="form-actions">
                            <button class="btn btn-primary" type="submit">
                                <i data-lucide="save"></i>
                                Simpan Kuis
                            </button>
                            <a href="{{ route('kuis.index') }}" class="btn btn-ghost">
                                <i data-lucide="arrow-left"></i>
                                Batal
                            </a>
                        </div>
                </div>
                </form>
            </div>
            </div>
        </main>
    </div>
    @include('components.modal')
    <script>
        function handleLogout() {
            showModal({
                type: 'logout',
                title: 'Konfirmasi Logout',
                message: 'Apakah Anda yakin ingin keluar dari akun Anda?',
                icon: 'log-out',
                confirmText: 'Ya, Keluar',
                isDanger: false,
                onConfirm: function() {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("logout", [], false) }}';
                    form.innerHTML = '@csrf';
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
        lucide.createIcons();
    </script>

    <script>
        (function() {
            const container = document.getElementById('questionContainer');
            const addBtn = document.getElementById('addQuestion');

            function buildQuestion(index) {
                const wrapper = document.createElement('div');
                wrapper.className = 'question';
                wrapper.dataset.index = index;
                wrapper.innerHTML = `
                    <div class="question-header">
                        <div class="question-title"><span class="question-number">Pertanyaan ${index + 1}</span> <span class="question-badge">Wajib</span></div>
                        <button type="button" class="btn btn-secondary btn-sm remove-question">
                            <i data-lucide="trash-2"></i>
                            Hapus
                        </button>
                    </div>
                    <div class="form-group">
                        <label>Pertanyaan</label>
                        <input type="text" name="pertanyaan[${index}][teks]" required>
                    </div>
                    <div class="inline-row">
                        <div class="form-group">
                            <label>Tipe Soal</label>
                            <select name="pertanyaan[${index}][tipe]" class="q-type" required>
                                <option value="pilihan">Pilihan Ganda</option>
                                <option value="essay">Essay</option>
                                <option value="listening">Listening</option>
                                <option value="speaking">Speaking</option>
                            </select>
                        </div>
                        <div class="form-group q-answer">
                            <label>Jawaban Benar</label>
                            <select name="pertanyaan[${index}][benar]" required>
                                <option value="">-- Pilih --</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                            </select>
                        </div>
                    </div>
                    <div class="option-grid q-choices">
                        <div>
                            <label>Opsi A</label>
                            <input type="text" name="pertanyaan[${index}][opsi][A]" required>
                        </div>
                        <div>
                            <label>Opsi B</label>
                            <input type="text" name="pertanyaan[${index}][opsi][B]" required>
                        </div>
                        <div>
                            <label>Opsi C</label>
                            <input type="text" name="pertanyaan[${index}][opsi][C]" required>
                        </div>
                        <div>
                            <label>Opsi D</label>
                            <input type="text" name="pertanyaan[${index}][opsi][D]" required>
                        </div>
                    </div>
                    <div class="q-essay" style="margin-top:0.75rem; display:none;">
                        <div class="form-group">
                            <label>Jawaban Contoh</label>
                            <textarea name="pertanyaan[${index}][jawaban_teks]" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Keyword (pisah dengan koma)</label>
                            <input type="text" name="pertanyaan[${index}][keyword]" placeholder="contoh: subject, verb, object">
                        </div>
                        <div class="form-group">
                            <label>Bahasa ASR</label>
                            <select name="pertanyaan[${index}][bahasa]">
                                <option value="id-ID">id-ID</option>
                                <option value="en-US">en-US</option>
                            </select>
                        </div>
                    </div>
                    <div class="q-listening" style="margin-top:0.75rem; display:none;">
                        <div class="form-group">
                            <label>Audio File (mp3/wav/ogg)</label>
                            <input type="file" name="pertanyaan_audio[${index}]">
                        </div>
                        <div class="form-group">
                            <label>Teks untuk TTS (opsional)</label>
                            <textarea name="pertanyaan[${index}][audio_text]" rows="2" placeholder="Jika tidak upload audio, isi teks ini"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Bahasa TTS</label>
                            <select name="pertanyaan[${index}][bahasa]">
                                <option value="en-US">en-US</option>
                                <option value="id-ID">id-ID</option>
                            </select>
                        </div>
                    </div>
                    <div class="q-speaking" style="margin-top:0.75rem; display:none;">
                        <div class="form-group">
                            <label>Jawaban Target (English)</label>
                            <input type="text" name="pertanyaan[${index}][jawaban_teks]" placeholder="Contoh: I go to school">
                        </div>
                        <div class="form-group">
                            <label>Audio Contoh (mp3/wav/ogg)</label>
                            <input type="file" name="pertanyaan_audio[${index}]">
                        </div>
                        <div class="form-group">
                            <label>Teks untuk TTS (opsional)</label>
                            <textarea name="pertanyaan[${index}][audio_text]" rows="2" placeholder="Jika tidak upload audio, isi teks ini"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Bahasa ASR/TTS</label>
                            <select name="pertanyaan[${index}][bahasa]">
                                <option value="en-US">en-US</option>
                                <option value="id-ID">id-ID</option>
                            </select>
                        </div>
                    </div>
                `;
                wrapper.querySelector('.remove-question').addEventListener('click', () => {
                    wrapper.remove();
                    renumber();
                });
                const typeSelect = wrapper.querySelector('.q-type');
                const choices = wrapper.querySelector('.q-choices');
                const answerWrap = wrapper.querySelector('.q-answer');
                const answerSelect = answerWrap.querySelector('select');
                const choiceInputs = Array.from(choices.querySelectorAll('input'));
                const essay = wrapper.querySelector('.q-essay');
                const essayFields = Array.from(essay.querySelectorAll('input, textarea, select'));
                const essayJawaban = essay.querySelector('textarea[name$="[jawaban_teks]"]');
                const essayKeyword = essay.querySelector('input[name$="[keyword]"]');
                const listening = wrapper.querySelector('.q-listening');
                const listeningFields = Array.from(listening.querySelectorAll('input, textarea, select'));
                const speaking = wrapper.querySelector('.q-speaking');
                const speakingFields = Array.from(speaking.querySelectorAll('input, textarea, select'));
                const speakingJawaban = speaking.querySelector('input[name$="[jawaban_teks]"]');

                function setRequired(elements, required) {
                    elements.forEach(el => {
                        if (required) {
                            el.setAttribute('required', 'required');
                        } else {
                            el.removeAttribute('required');
                        }
                    });
                }

                function setDisabled(elements, disabled) {
                    elements.forEach(el => {
                        el.disabled = disabled;
                    });
                }

                function toggleByType() {
                    const val = typeSelect.value;
                    if (val === 'essay') {
                        choices.style.display = 'none';
                        answerWrap.style.display = 'none';
                        essay.style.display = 'block';
                        listening.style.display = 'none';
                        speaking.style.display = 'none';
                        setRequired(choiceInputs, false);
                        setRequired([answerSelect], false);
                        setRequired([essayJawaban, essayKeyword], true);
                        setRequired([speakingJawaban], false);
                        setDisabled([...choiceInputs, answerSelect], true);
                        setDisabled(essayFields, false);
                        setDisabled(listeningFields, true);
                        setDisabled(speakingFields, true);
                    } else if (val === 'listening') {
                        choices.style.display = 'grid';
                        answerWrap.style.display = 'block';
                        essay.style.display = 'none';
                        listening.style.display = 'block';
                        speaking.style.display = 'none';
                        setRequired(choiceInputs, true);
                        setRequired([answerSelect], true);
                        setRequired([essayJawaban, essayKeyword], false);
                        setRequired([speakingJawaban], false);
                        setDisabled([...choiceInputs, answerSelect], false);
                        setDisabled(essayFields, true);
                        setDisabled(listeningFields, false);
                        setDisabled(speakingFields, true);
                    } else if (val === 'speaking') {
                        choices.style.display = 'none';
                        answerWrap.style.display = 'none';
                        essay.style.display = 'none';
                        listening.style.display = 'none';
                        speaking.style.display = 'block';
                        setRequired(choiceInputs, false);
                        setRequired([answerSelect], false);
                        setRequired([essayJawaban, essayKeyword], false);
                        setRequired([speakingJawaban], true);
                        setDisabled([...choiceInputs, answerSelect], true);
                        setDisabled(essayFields, true);
                        setDisabled(listeningFields, true);
                        setDisabled(speakingFields, false);
                    } else {
                        choices.style.display = 'grid';
                        answerWrap.style.display = 'block';
                        essay.style.display = 'none';
                        listening.style.display = 'none';
                        speaking.style.display = 'none';
                        setRequired(choiceInputs, true);
                        setRequired([answerSelect], true);
                        setRequired([essayJawaban, essayKeyword], false);
                        setRequired([speakingJawaban], false);
                        setDisabled([...choiceInputs, answerSelect], false);
                        setDisabled(essayFields, true);
                        setDisabled(listeningFields, true);
                        setDisabled(speakingFields, true);
                    }
                }
                typeSelect.addEventListener('change', toggleByType);
                toggleByType();
                return wrapper;
            }

            function renumber() {
                const items = container.querySelectorAll('.question');
                items.forEach((item, idx) => {
                    item.dataset.index = idx;
                    const label = item.querySelector('.question-number');
                    if (label) {
                        label.textContent = `Pertanyaan ${idx + 1}`;
                    }
                });
            }

            addBtn.addEventListener('click', () => {
                const index = container.querySelectorAll('.question').length;
                container.appendChild(buildQuestion(index));
            });

            // default 1 question
            container.appendChild(buildQuestion(0));
        })();
    </script>
</body>
</html>

