<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Materi - AKSES Dashboard</title>
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
            background: linear-gradient(180deg, #1F2937 0%, #111827 100%);
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15);
        }
        
        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
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
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .logo-icon {
            width: 26px;
            height: 26px;
            color: var(--color-accent);
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
        
        .nav-item.active {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
        }

        .nav-item.active a {
            background: transparent;
            color: #FFFFFF;
            font-weight: 600;
            border-left: 4px solid var(--color-accent);
        }
        
        .nav-item:not(.active):hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .nav-icon {
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: #CBD5E1;
        }

        .nav-item.active .nav-icon {
            color: var(--color-accent);
        }
        
        .logout-btn {
            margin: 1rem;
            padding: 0.75rem 1.5rem;
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            color: var(--color-white);
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }
        
        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* top header strip removed for unified header */
        
        .header-bar {
            background: linear-gradient(135deg, #1F2937 0%, #111827 100%);
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: #FFFFFF;
            letter-spacing: 0.5px;
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
            border: 1px solid rgba(0,0,0,0.04);
        }

        .section-title {
            font-size: 1.15rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.25rem;
        }

        .section-subtitle {
            color: var(--color-text-light);
            font-size: 0.92rem;
            margin-bottom: 1rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
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
            box-shadow: 0 0 0 4px rgba(248, 184, 3, 0.1);
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
            flex-wrap: wrap;
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
            gap: 0.5rem;
        }
        
        .btn-primary {
            background: var(--color-accent);
            color: #1F2937;
        }
        
        .btn-primary:hover {
            background: #E6A500;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(248, 184, 3, 0.4);
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

        .back-link-clean {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--color-primary);
    text-decoration: none;
    transition: 0.2s ease;
}

.back-link-clean i {
    width: 18px;
    height: 18px;
}

.back-link-clean:hover {
    color: var(--color-accent);
    transform: translateX(-3px);
}

        .hint {
            color: var(--color-text-light);
            font-size: 0.85rem;
            margin-top: 0.35rem;
            display: block;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo-container">
                    <div class="logo-circle"><img src="{{ asset('images/image.png') }}" alt="AKSES Logo"></div>
                    <div class="logo-text">AKSES</div>
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
                <h1 class="header-title">Tambah Materi Baru</h1>
            </header>
            
            <!-- Content Area -->
            <div class="content-area">
                <div style="margin-bottom: 1.5rem;">
                    <a href="{{ route('materi.index') }}" class="back-link-clean">
                    <i data-lucide="arrow-left"></i>
                    Daftar Materi
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

                <form action="{{ route('materi.store') }}" method="POST" enctype="multipart/form-data" class="form-container">
                    @csrf
                    <div class="section-title"><i data-lucide="file-text"></i> Informasi Utama</div>
                    <div class="section-subtitle">Masukkan judul, deskripsi, mata pelajaran, dan level.</div>

                    <div class="form-group">
                        <label class="form-label">
                            Judul <span class="required">*</span>
                        </label>
                        <input type="text" name="judul" value="{{ old('judul') }}" class="form-input" required>
                        <span class="hint">Contoh: Materi Bahasa Indonesia Bab 1</span>
                        @error('judul')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" rows="4" class="form-textarea">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Mata Pelajaran</label>
                            <select name="mata_pelajaran_id" class="form-select">
                                <option value="">Pilih Mata Pelajaran</option>
                                @foreach($mataPelajarans as $mp)
                                    <option value="{{ $mp->id }}" {{ old('mata_pelajaran_id') == $mp->id ? 'selected' : '' }}>
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
                                    <option value="{{ $level->id }}" {{ old('level_id') == $level->id ? 'selected' : '' }}>
                                        {{ $level->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('level_id')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="section-title" style="margin-top: 1.5rem;"><i data-lucide="layers"></i> Konten Materi</div>
                    <div class="section-subtitle">Pilih tipe konten dan unggah file atau isi teks.</div>

                    <div class="form-group">
                        <label class="form-label">
                            Tipe Konten <span class="required">*</span>
                        </label>
                        <select name="tipe_konten" id="tipe_konten" class="form-select" required>
                            <option value="">Pilih Tipe Konten</option>
                            <option value="teks" {{ old('tipe_konten') == 'teks' ? 'selected' : '' }}>Teks</option>
                            <option value="file" {{ old('tipe_konten') == 'file' ? 'selected' : '' }}>File</option>
                        </select>
                        <span class="hint">Pilih Teks untuk isi langsung, atau File untuk upload dokumen.</span>
                        @error('tipe_konten')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div id="konten_teks_field" class="form-group" style="display: none;">
                        <label class="form-label">
                            Konten Teks <span class="required">*</span>
                        </label>
                        <textarea name="konten_teks" rows="8" class="form-textarea">{{ old('konten_teks') }}</textarea>
                        @error('konten_teks')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div id="file_path_field" class="form-group" style="display: none;">
                        <label class="form-label">
                            File (PDF, DOC, DOCX) <span class="required">*</span>
                        </label>
                        <input type="file" name="file_path" accept=".pdf,.doc,.docx" class="form-input">
                        <small class="hint">Maksimal 10MB</small>
                        @error('file_path')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="section-title" style="margin-top: 1.5rem;"><i data-lucide="image"></i> Cover Materi</div>
                    <div class="section-subtitle">Upload cover agar materi tampil seperti buku di halaman siswa.</div>

                    <div class="form-group">
                        <label class="form-label">Cover Image (JPG/PNG/WebP)</label>
                        <input type="file" name="cover_path" accept="image/*" class="form-input">
                        <small class="hint">Opsional. Jika kosong, sistem menampilkan cover putih dengan judul.</small>
                        @error('cover_path')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="section-title" style="margin-top: 1.5rem;"><i data-lucide="settings"></i> Pengaturan</div>
                    <div class="section-subtitle">Atur detail tambahan dan status materi.</div>

                    <div class="form-group">
                        <label class="form-label">Jumlah Halaman</label>
                        <input type="number" name="jumlah_halaman" value="{{ old('jumlah_halaman') }}" min="1" class="form-input">
                        <span class="hint">Isi jika konten berupa dokumen.</span>
                        @error('jumlah_halaman')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-checkbox">
                            <input type="checkbox" name="status_aktif" value="1" {{ old('status_aktif', true) ? 'checked' : '' }}>
                            <span>Status Aktif</span>
                        </label>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save"></i>
                            Simpan Materi
                        </button>
                        <a href="{{ route('materi.index') }}" class="btn btn-secondary">
                            <i data-lucide="x"></i>
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
                filePathField.querySelector('input').required = true;
            } else {
                kontenTeksField.style.display = 'none';
                filePathField.style.display = 'none';
                kontenTeksField.querySelector('textarea').required = false;
                filePathField.querySelector('input').required = false;
            }
        });

        // Trigger on page load if value exists
        if (document.getElementById('tipe_konten').value) {
            document.getElementById('tipe_konten').dispatchEvent(new Event('change'));
        }

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






