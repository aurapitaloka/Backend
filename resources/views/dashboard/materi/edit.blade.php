<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Materi - Ruma Dashboard</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --color-accent: #F8B803;
            --color-accent-dark: #E6A500;
            --color-accent-light: #FFF9E6;
            --color-sidebar: #111827;
            --color-sidebar-dark: #0b1220;
            --color-white: #FFFFFF;
            --color-gray-light: #F5F5F5;
            --color-gray: #E5E7EB;
            --color-text: #1F2937;
            --color-muted: #6B7280;
            --sidebar-width: 280px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--color-gray-light);
            color: var(--color-text);
            overflow-x: hidden;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--color-sidebar) 0%, var(--color-sidebar-dark) 100%);
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.35);
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo-circle {
            width: 50px;
            height: 50px;
            background: var(--color-white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
        }

        .logo-icon {
            font-size: 1.8rem;
        }

        .logo-text {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--color-white);
            letter-spacing: 1px;
        }

        .sidebar-nav {
            flex: 1;
            padding: 1.5rem 0;
            overflow-y: auto;
        }

        .nav-item {
            margin: 0.5rem 1rem;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .nav-item a {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.25rem;
            color: var(--color-white);
            text-decoration: none;
            font-weight: 500;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .nav-item.active a {
            background: rgba(248, 184, 3, 0.06);
            color: var(--color-white);
            font-weight: 600;
            border-left: 4px solid var(--color-accent);
            padding-left: calc(1.25rem - 4px);
        }

        .nav-item:not(.active):hover a {
            background: rgba(255, 255, 255, 0.03);
        }

        .nav-icon {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .logout-btn {
            margin: 1rem;
            padding: 0.75rem 1.5rem;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 12px;
            color: var(--color-white);
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.08);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .top-header-strip {
            background: var(--color-sidebar);
            padding: 0.75rem 2rem;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .user-info-top {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--color-white);
            font-weight: 600;
            font-size: 0.9rem;
        }

        .user-icon-small {
            width: 24px;
            height: 24px;
            background: rgba(255,255,255,0.06);
            color: var(--color-white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .header-bar {
            background: linear-gradient(135deg, var(--color-sidebar) 0%, var(--color-sidebar-dark) 100%);
            padding: 1.5rem 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.12);
        }

        .header-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--color-white);
        }

        .content-area {
            flex: 1;
            padding: 2rem;
        }

        .form-container {
            background: var(--color-white);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: var(--color-text);
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 0.875rem 1.25rem;
            border: 2px solid var(--color-gray);
            border-radius: 12px;
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
        }

        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--color-accent);
            box-shadow: 0 0 0 4px rgba(248, 184, 3, 0.12);
        }

        .form-textarea {
            resize: vertical;
            min-height: 120px;
        }

        .form-checkbox {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
        }

        .form-checkbox input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            flex: 1;
            padding: 1rem;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-primary {
            background: var(--color-accent);
            color: var(--color-sidebar);
        }

        .btn-primary:hover {
            background: var(--color-accent-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(248, 184, 3, 0.35);
        }

        .btn-secondary {
            background: var(--color-gray);
            color: var(--color-text);
        }

        .btn-secondary:hover {
            background: #D1D5DB;
        }

        .error-message {
            color: #DC2626;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .alert-error {
            background: #FEE2E2;
            border: 1px solid #FCA5A5;
            color: #991B1B;
        }

        .required {
            color: #DC2626;
        }

        .current-file {
            margin-top: 0.5rem;
            padding: 0.75rem;
            background: var(--color-gray-light);
            border-radius: 8px;
            font-size: 0.9rem;
            color: var(--color-text);
        }

        .current-file a {
            color: var(--color-accent-dark);
            text-decoration: none;
            font-weight: 600;
        }

        .current-file a:hover {
            text-decoration: underline;
        }

        .current-cover {
            margin-top: 0.75rem;
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .current-cover img {
            width: 140px;
            height: 190px;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid var(--color-gray);
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo-container">
                    <div class="logo-circle">
                        <span class="logo-icon">👋</span>
                    </div>
                    <div class="logo-text">Ruma</div>
                </div>
            </div>
            
            @include('components.sidebar')
            
            <div class="logout-btn" onclick="handleLogout()" style="display:flex; align-items:center; gap:8px; justify-content:center;">
    <i data-lucide="log-out"></i>
    <span>Keluar</span>
</div>

        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            
            <!-- Header Bar -->
            <header class="header-bar">
                <h1 class="header-title">Edit Materi</h1>
            </header>
            
            <!-- Content Area -->
            <div class="content-area">
                <div style="margin-bottom: 1.5rem;">
                    <a href="{{ route('materi.show', $materi->id) }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--color-text); text-decoration: none; font-weight: 600; margin-right: 1rem;">
                        ← Kembali ke Detail
                    </a>
                    <a href="{{ route('materi.index') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--color-text); text-decoration: none; font-weight: 600;">
                        ← Daftar Materi
                    </a>
                </div>

                @if($errors->any())
                    <div class="alert alert-error">
                        <strong>Terjadi kesalahan:</strong>
                        <ul style="margin: 0.5rem 0 0 1.5rem; padding: 0;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('materi.update', $materi->id) }}" method="POST" enctype="multipart/form-data" class="form-container">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label class="form-label">
                            Judul <span class="required">*</span>
                        </label>
                        <input type="text" name="judul" value="{{ old('judul', $materi->judul) }}" class="form-input" required>
                        @error('judul')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" rows="4" class="form-textarea">{{ old('deskripsi', $materi->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div class="form-group">
                            <label class="form-label">Mata Pelajaran</label>
                            <select name="mata_pelajaran_id" class="form-select">
                                <option value="">Pilih Mata Pelajaran</option>
                                @foreach($mataPelajarans as $mp)
                                    <option value="{{ $mp->id }}" {{ old('mata_pelajaran_id', $materi->mata_pelajaran_id) == $mp->id ? 'selected' : '' }}>
                                        {{ $mp->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('mata_pelajaran_id')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Level</label>
                            <select name="level_id" class="form-select">
                                <option value="">Pilih Level</option>
                                @foreach($levels as $level)
                                    <option value="{{ $level->id }}" {{ old('level_id', $materi->level_id) == $level->id ? 'selected' : '' }}>
                                        {{ $level->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('level_id')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Tipe Konten <span class="required">*</span>
                        </label>
                        <select name="tipe_konten" id="tipe_konten" class="form-select" required>
                            <option value="">Pilih Tipe Konten</option>
                            <option value="teks" {{ old('tipe_konten', $materi->tipe_konten) == 'teks' ? 'selected' : '' }}>Teks</option>
                            <option value="file" {{ old('tipe_konten', $materi->tipe_konten) == 'file' ? 'selected' : '' }}>File</option>
                        </select>
                        @error('tipe_konten')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div id="konten_teks_field" class="form-group" style="display: {{ old('tipe_konten', $materi->tipe_konten) == 'teks' ? 'block' : 'none' }};">
                        <label class="form-label">
                            Konten Teks <span class="required">*</span>
                        </label>
                        <textarea name="konten_teks" rows="8" class="form-textarea" {{ old('tipe_konten', $materi->tipe_konten) == 'teks' ? 'required' : '' }}>{{ old('konten_teks', $materi->konten_teks) }}</textarea>
                        @error('konten_teks')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div id="file_path_field" class="form-group" style="display: {{ old('tipe_konten', $materi->tipe_konten) == 'file' ? 'block' : 'none' }};">
                        <label class="form-label">
                            File (PDF, DOC, DOCX)
                        </label>
                        <input type="file" name="file_path" accept=".pdf,.doc,.docx" class="form-input" {{ old('tipe_konten', $materi->tipe_konten) == 'file' && !$materi->file_path ? 'required' : '' }}>
                        <small style="color: var(--color-text-light); margin-top: 0.5rem; display: block;">Maksimal 10MB. Kosongkan jika tidak ingin mengubah file.</small>
                        @if($materi->file_path)
                            <div class="current-file">
                                File saat ini: <a href="{{ Storage::url($materi->file_path) }}" target="_blank">{{ basename($materi->file_path) }}</a>
                            </div>
                        @endif
                        @error('file_path')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Cover Image (JPG/PNG/WebP)</label>
                        <input type="file" name="cover_path" accept="image/*" class="form-input">
                        <small style="color: var(--color-text-light); margin-top: 0.5rem; display: block;">Kosongkan jika tidak ingin mengubah cover.</small>
                        @if($materi->cover_path)
                            <div class="current-cover">
                                <img src="{{ Storage::url($materi->cover_path) }}" alt="Cover materi">
                                <div style="color: var(--color-text-light); font-size: 0.9rem;">
                                    Cover saat ini ditampilkan di halaman siswa.
                                </div>
                            </div>
                        @endif
                        @error('cover_path')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jumlah Halaman</label>
                        <input type="number" name="jumlah_halaman" value="{{ old('jumlah_halaman', $materi->jumlah_halaman) }}" min="1" class="form-input">
                        @error('jumlah_halaman')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-checkbox">
                            <input type="checkbox" name="status_aktif" value="1" {{ old('status_aktif', $materi->status_aktif) ? 'checked' : '' }}>
                            <span>Status Aktif</span>
                        </label>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('materi.show', $materi->id) }}" class="btn btn-secondary">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>
    
    <script>
        document.getElementById('tipe_konten').addEventListener('change', function() {
            const tipeKonten = this.value;
            const kontenTeksField = document.getElementById('konten_teks_field');
            const filePathField = document.getElementById('file_path_field');
            
            if (tipeKonten === 'teks') {
                kontenTeksField.style.display = 'block';
                filePathField.style.display = 'none';
                kontenTeksField.querySelector('textarea').required = true;
                filePathField.querySelector('input').required = false;
            } else if (tipeKonten === 'file') {
                kontenTeksField.style.display = 'none';
                filePathField.style.display = 'block';
                kontenTeksField.querySelector('textarea').required = false;
                // File tidak required jika sudah ada file sebelumnya
                const currentFile = filePathField.querySelector('.current-file');
                if (!currentFile) {
                    filePathField.querySelector('input').required = true;
                }
            } else {
                kontenTeksField.style.display = 'none';
                filePathField.style.display = 'none';
                kontenTeksField.querySelector('textarea').required = false;
                filePathField.querySelector('input').required = false;
            }
        });

        function handleLogout() {
            if (confirm('Apakah Anda yakin ingin keluar?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("logout", [], false) }}';
                form.innerHTML = '@csrf';
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
    <script>
    lucide.createIcons();
</script>
</body>
</html>

