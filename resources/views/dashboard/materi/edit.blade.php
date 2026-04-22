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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
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

        .pdf-selection-panel {
            margin-top: 1rem;
            padding: 1rem;
            border: 1px solid var(--color-gray);
            border-radius: 14px;
            background: linear-gradient(180deg, #FFFCF2 0%, #FFFFFF 100%);
        }

        .pdf-selection-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .pdf-selection-summary {
            font-size: 0.9rem;
            color: var(--color-text);
            font-weight: 600;
        }

        .pdf-selection-range {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 180px));
            gap: 0.9rem;
            margin-bottom: 1rem;
        }

        .pdf-selection-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .pdf-action-btn {
            border: 1px solid rgba(31, 41, 55, 0.12);
            background: var(--color-white);
            color: var(--color-text);
            border-radius: 999px;
            padding: 0.55rem 0.9rem;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
        }

        .pdf-pages-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
        }

        .pdf-page-card {
            border: 1px solid rgba(17, 24, 39, 0.08);
            border-radius: 16px;
            background: var(--color-white);
            overflow: hidden;
            box-shadow: 0 6px 18px rgba(17, 24, 39, 0.06);
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .pdf-page-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 22px rgba(17, 24, 39, 0.1);
        }

        .pdf-page-card.selected {
            border-color: rgba(248, 184, 3, 0.95);
            background: linear-gradient(180deg, #FFF5CC 0%, #FFF0B3 100%);
            box-shadow: 0 14px 28px rgba(248, 184, 3, 0.28);
        }

        .pdf-page-preview {
            position: relative;
            aspect-ratio: 3 / 4;
            background: #F8FAFC;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .pdf-page-preview canvas {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .pdf-page-check {
            position: absolute;
            top: 0.6rem;
            right: 0.6rem;
            width: 24px;
            height: 24px;
            accent-color: var(--color-accent);
            cursor: pointer;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.18));
        }

        .pdf-page-meta {
            padding: 0.85rem 0.9rem 1rem;
        }

        .pdf-page-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--color-text);
        }

        .pdf-page-subtitle {
            margin-top: 0.2rem;
            font-size: 0.8rem;
            color: var(--color-muted);
        }

        .pdf-selection-empty {
            padding: 1rem;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.8);
            color: var(--color-muted);
            font-size: 0.9rem;
        }

        .pdf-selection-loading {
            padding: 1rem;
            border-radius: 12px;
            background: #FFF7D6;
            color: #8A6500;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        @media (max-width: 640px) {
            .pdf-selection-range {
                grid-template-columns: 1fr;
            }

            .pdf-pages-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
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
                        <input type="file" name="file_path" id="file_path" accept=".pdf,.doc,.docx" class="form-input" {{ old('tipe_konten', $materi->tipe_konten) == 'file' && !$materi->file_path ? 'required' : '' }}>
                        <small style="color: var(--color-text-light); margin-top: 0.5rem; display: block;">PDF di atas 10 MB akan dicoba dikompres otomatis sampai 10 MB. DOC/DOCX tetap maksimal 10 MB. Kosongkan jika tidak ingin mengubah file.</small>
                        <input type="hidden" name="pdf_page_selection" id="pdf_page_selection" value="{{ old('pdf_page_selection', $materi->pdf_page_selection) }}">
                        <div id="pdf_selection_panel" class="pdf-selection-panel" style="display: none;">
                            <div id="pdf_selection_loading" class="pdf-selection-loading" style="display: none;">Sedang menyiapkan preview halaman PDF...</div>
                            <div class="pdf-selection-range">
                                <div>
                                    <label class="form-label">Halaman Awal</label>
                                    <input type="number" id="pdf_page_start" class="form-input" min="1" placeholder="Contoh: 3">
                                </div>
                                <div>
                                    <label class="form-label">Halaman Akhir</label>
                                    <input type="number" id="pdf_page_end" class="form-input" min="1" placeholder="Contoh: 12">
                                </div>
                            </div>
                            <div class="pdf-selection-toolbar">
                                <div id="pdf_selection_summary" class="pdf-selection-summary">Belum ada halaman PDF yang dimuat.</div>
                                <div class="pdf-selection-actions">
                                    <button type="button" id="pdf_select_all" class="pdf-action-btn">Pilih Semua</button>
                                    <button type="button" id="pdf_clear_all" class="pdf-action-btn">Reset Pilihan</button>
                                </div>
                            </div>
                            <div id="pdf_pages_grid" class="pdf-pages-grid"></div>
                            <div id="pdf_selection_empty" class="pdf-selection-empty">Upload file PDF baru jika ingin memilih halaman yang disimpan.</div>
                            <small style="color: var(--color-text-light); margin-top: 0.5rem; display: block;">Kalau semua halaman dicentang, sistem menyimpan seluruh PDF baru. Kalau hanya sebagian yang dicentang, sistem menyimpan halaman terpilih saja.</small>
                        </div>
                        @if($materi->file_path)
                            <div class="current-file">
                                File saat ini: <a href="{{ Storage::url($materi->file_path) }}" target="_blank">{{ basename($materi->file_path) }}</a>
                            </div>
                        @endif
                        @error('file_path')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                        @error('pdf_page_selection')
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
        const materiForm = document.querySelector('.form-container');
        const tipeKontenSelect = document.getElementById('tipe_konten');
        const fileInput = document.getElementById('file_path');
        const pdfSelectionPanel = document.getElementById('pdf_selection_panel');
        const pdfSelectionLoading = document.getElementById('pdf_selection_loading');
        const pdfSelectionSummary = document.getElementById('pdf_selection_summary');
        const pdfPagesGrid = document.getElementById('pdf_pages_grid');
        const pdfSelectionEmpty = document.getElementById('pdf_selection_empty');
        const pdfPageSelectionInput = document.getElementById('pdf_page_selection');
        const pdfPageStartInput = document.getElementById('pdf_page_start');
        const pdfPageEndInput = document.getElementById('pdf_page_end');
        const pdfSelectAllButton = document.getElementById('pdf_select_all');
        const pdfClearAllButton = document.getElementById('pdf_clear_all');
        const existingPdfUrl = @json(($materi->tipe_konten === 'file' && $materi->file_path && str_ends_with(strtolower($materi->file_path), '.pdf')) ? Storage::url($materi->file_path) : null);
        const initialSelectedPdfPages = new Set(
            (pdfPageSelectionInput?.value || '')
                .split(',')
                .map((value) => Number.parseInt(value.trim(), 10))
                .filter((value) => Number.isInteger(value) && value > 0)
        );
        let selectedPdfPages = new Set();
        let totalPdfPages = 0;
        let isSyncingPdfInputs = false;

        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        function updatePdfSelectionSummary() {
            if (!pdfSelectionSummary) {
                return;
            }

            if (!totalPdfPages) {
                pdfSelectionSummary.textContent = 'Belum ada halaman PDF yang dimuat.';
                pdfPageSelectionInput.value = '';
                return;
            }

            const selectedCount = selectedPdfPages.size;
            pdfSelectionSummary.textContent = `Terpilih ${selectedCount} dari ${totalPdfPages} halaman.`;
            pdfPageSelectionInput.value = Array.from(selectedPdfPages).sort((a, b) => a - b).join(',');
        }

        function syncPageRangeInputsFromSelection() {
            if (!pdfPageStartInput || !pdfPageEndInput) {
                return;
            }

            isSyncingPdfInputs = true;

            if (selectedPdfPages.size === 0) {
                pdfPageStartInput.value = '';
                pdfPageEndInput.value = '';
            } else {
                const sortedPages = Array.from(selectedPdfPages).sort((a, b) => a - b);
                pdfPageStartInput.value = sortedPages[0];
                pdfPageEndInput.value = sortedPages[sortedPages.length - 1];
            }

            isSyncingPdfInputs = false;
        }

        function updatePageCardVisual(pageNumber, isSelected) {
            const card = pdfPagesGrid.querySelector(`.pdf-page-card[data-page-number="${pageNumber}"]`);
            if (!card) {
                return;
            }

            card.classList.toggle('selected', isSelected);
            const checkbox = card.querySelector('.pdf-page-check');
            if (checkbox) {
                checkbox.checked = isSelected;
            }
        }

        function applyRangeSelection() {
            if (isSyncingPdfInputs || totalPdfPages === 0) {
                return;
            }

            const startValue = Number.parseInt(pdfPageStartInput.value, 10);
            const endValue = Number.parseInt(pdfPageEndInput.value, 10);

            if (!startValue && !endValue) {
                selectedPdfPages = new Set();
                for (let pageNumber = 1; pageNumber <= totalPdfPages; pageNumber++) {
                    updatePageCardVisual(pageNumber, false);
                }
                updatePdfSelectionSummary();
                return;
            }

            if (!startValue || !endValue) {
                return;
            }

            const startPage = Math.max(1, Math.min(startValue, totalPdfPages));
            const endPage = Math.max(1, Math.min(endValue, totalPdfPages));

            if (startPage > endPage) {
                return;
            }

            selectedPdfPages = new Set();

            for (let pageNumber = 1; pageNumber <= totalPdfPages; pageNumber++) {
                const isSelected = pageNumber >= startPage && pageNumber <= endPage;
                if (isSelected) {
                    selectedPdfPages.add(pageNumber);
                }
                updatePageCardVisual(pageNumber, isSelected);
            }

            updatePdfSelectionSummary();
            syncPageRangeInputsFromSelection();
        }

        function renderPdfPageCard(pageNumber, viewport, canvas) {
            const card = document.createElement('label');
            const isInitiallySelected = initialSelectedPdfPages.has(pageNumber);
            card.className = `pdf-page-card${isInitiallySelected ? ' selected' : ''}`;
            card.dataset.pageNumber = String(pageNumber);

            const preview = document.createElement('div');
            preview.className = 'pdf-page-preview';

            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.className = 'pdf-page-check';
            checkbox.checked = isInitiallySelected;
            checkbox.addEventListener('change', () => {
                if (checkbox.checked) {
                    selectedPdfPages.add(pageNumber);
                    card.classList.add('selected');
                } else {
                    selectedPdfPages.delete(pageNumber);
                    card.classList.remove('selected');
                }
                updatePdfSelectionSummary();
                syncPageRangeInputsFromSelection();
            });

            const meta = document.createElement('div');
            meta.className = 'pdf-page-meta';
            meta.innerHTML = `<div class="pdf-page-title">Halaman ${pageNumber}</div><div class="pdf-page-subtitle">${Math.round(viewport.width)} x ${Math.round(viewport.height)} px</div>`;

            preview.appendChild(canvas);
            preview.appendChild(checkbox);
            card.appendChild(preview);
            card.appendChild(meta);

            return card;
        }

        async function loadPdfPreview(source) {
            if (!source || !pdfSelectionPanel) {
                return;
            }

            const isFileObject = typeof File !== 'undefined' && source instanceof File;
            const isPdf = isFileObject
                ? (source.type === 'application/pdf' || source.name.toLowerCase().endsWith('.pdf'))
                : String(source).toLowerCase().includes('.pdf');
            pdfSelectionPanel.style.display = isPdf ? 'block' : 'none';

            if (!isPdf) {
                pdfPagesGrid.innerHTML = '';
                pdfSelectionEmpty.style.display = 'block';
                pdfSelectionEmpty.textContent = 'Preview halaman hanya tersedia untuk file PDF.';
                selectedPdfPages = new Set();
                totalPdfPages = 0;
                updatePdfSelectionSummary();
                return;
            }

            pdfSelectionLoading.style.display = 'block';
            pdfPagesGrid.innerHTML = '';
            pdfSelectionEmpty.style.display = 'none';
            selectedPdfPages = new Set();
            totalPdfPages = 0;
            updatePdfSelectionSummary();

            try {
                const documentSource = isFileObject
                    ? { data: await source.arrayBuffer() }
                    : source;
                const pdf = await pdfjsLib.getDocument(documentSource).promise;
                totalPdfPages = pdf.numPages;
                selectedPdfPages = new Set(
                    Array.from(initialSelectedPdfPages).filter((pageNumber) => pageNumber <= totalPdfPages)
                );

                for (let pageNumber = 1; pageNumber <= pdf.numPages; pageNumber++) {
                    const page = await pdf.getPage(pageNumber);
                    const viewport = page.getViewport({ scale: 0.35 });
                    const canvas = document.createElement('canvas');
                    const context = canvas.getContext('2d');
                    canvas.width = viewport.width;
                    canvas.height = viewport.height;

                    await page.render({
                        canvasContext: context,
                        viewport,
                    }).promise;

                    pdfPagesGrid.appendChild(renderPdfPageCard(pageNumber, viewport, canvas));
                }

                updatePdfSelectionSummary();
                syncPageRangeInputsFromSelection();
            } catch (error) {
                pdfPagesGrid.innerHTML = '';
                pdfSelectionEmpty.style.display = 'block';
                pdfSelectionEmpty.textContent = 'Preview PDF gagal dimuat. Kamu masih bisa upload file, tetapi pilih halaman tidak tersedia untuk file ini.';
                selectedPdfPages = new Set();
                totalPdfPages = 0;
                updatePdfSelectionSummary();
                syncPageRangeInputsFromSelection();
            } finally {
                pdfSelectionLoading.style.display = 'none';
            }
        }

        tipeKontenSelect.addEventListener('change', function() {
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

            const currentFile = fileInput.files && fileInput.files[0] ? fileInput.files[0] : null;
            if (currentFile) {
                loadPdfPreview(currentFile);
            } else if (pdfSelectionPanel) {
                pdfSelectionPanel.style.display = 'none';
                pdfPagesGrid.innerHTML = '';
                pdfSelectionEmpty.style.display = 'block';
                selectedPdfPages = new Set();
                totalPdfPages = 0;
                updatePdfSelectionSummary();
                syncPageRangeInputsFromSelection();
            }
        });

        if (fileInput) {
            fileInput.addEventListener('change', () => {
                const currentFile = fileInput.files && fileInput.files[0] ? fileInput.files[0] : null;
                if (currentFile) {
                    initialSelectedPdfPages.clear();
                    loadPdfPreview(currentFile);
                } else {
                    pdfSelectionPanel.style.display = 'none';
                    pdfPagesGrid.innerHTML = '';
                    selectedPdfPages = new Set();
                    totalPdfPages = 0;
                    updatePdfSelectionSummary();
                    syncPageRangeInputsFromSelection();
                }
            });
        }

        if (tipeKontenSelect.value) {
            tipeKontenSelect.dispatchEvent(new Event('change'));
        }

        if (!fileInput?.files?.length && existingPdfUrl && tipeKontenSelect.value === 'file') {
            loadPdfPreview(existingPdfUrl);
        }

        if (pdfPageStartInput) {
            pdfPageStartInput.addEventListener('input', applyRangeSelection);
        }

        if (pdfPageEndInput) {
            pdfPageEndInput.addEventListener('input', applyRangeSelection);
        }

        if (pdfSelectAllButton) {
            pdfSelectAllButton.addEventListener('click', () => {
                selectedPdfPages = new Set(Array.from({ length: totalPdfPages }, (_, index) => index + 1));
                pdfPagesGrid.querySelectorAll('.pdf-page-card').forEach((card) => {
                    card.classList.add('selected');
                    const checkbox = card.querySelector('.pdf-page-check');
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                });
                updatePdfSelectionSummary();
                syncPageRangeInputsFromSelection();
            });
        }

        if (pdfClearAllButton) {
            pdfClearAllButton.addEventListener('click', () => {
                selectedPdfPages = new Set();
                pdfPagesGrid.querySelectorAll('.pdf-page-card').forEach((card) => {
                    card.classList.remove('selected');
                    const checkbox = card.querySelector('.pdf-page-check');
                    if (checkbox) {
                        checkbox.checked = false;
                    }
                });
                updatePdfSelectionSummary();
                syncPageRangeInputsFromSelection();
            });
        }

        if (materiForm) {
            materiForm.addEventListener('submit', (event) => {
                const currentFile = fileInput.files && fileInput.files[0] ? fileInput.files[0] : null;
                const isPdf = currentFile && (currentFile.type === 'application/pdf' || currentFile.name.toLowerCase().endsWith('.pdf'));

                if (isPdf && totalPdfPages > 0 && selectedPdfPages.size === 0) {
                    event.preventDefault();
                    alert('Pilih minimal satu halaman PDF yang ingin disimpan.');
                }
            });
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

