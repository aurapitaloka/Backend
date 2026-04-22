<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Materi - Ruma Dashboard</title>
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

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--color-text);
            text-decoration: none;
            font-weight: 600;
            background: var(--color-white);
            border: 1px solid var(--color-gray);
            border-radius: 10px;
            padding: 0.45rem 0.75rem;
            box-shadow: 0 2px 6px rgba(0,0,0,0.06);
            transition: all 0.2s ease;
        }

        .back-link:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.12);
            background: var(--color-accent-light);
        }
        
        .detail-container {
            background: var(--color-white);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0,0,0,0.04);
        }
        
        .detail-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid var(--color-gray);
        }
        
        .detail-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--color-text);
            margin-bottom: 0.5rem;
        }
        
        .detail-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--color-muted);
            font-size: 0.9rem;
        }

        .meta-item i {
            width: 16px;
            height: 16px;
        }
        
        .meta-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge-primary {
            background: var(--color-accent-light);
            color: var(--color-accent-dark);
        }
        
        .badge-success {
            background: #E8F5E9;
            color: #2E7D32;
        }
        
        .badge-danger {
            background: #FFEBEE;
            color: #C62828;
        }
        
        .detail-content {
            margin-top: 2rem;
        }
        
        .content-section {
            margin-bottom: 2rem;
        }
        
        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--color-text);
            margin-bottom: 1rem;
        }
        
        .section-content {
            color: var(--color-text);
            line-height: 1.8;
            white-space: pre-wrap;
        }
        
        .pdf-viewer {
            width: 100%;
            height: 80vh;
            border: 2px solid var(--color-gray);
            border-radius: 12px;
            margin-top: 1rem;
        }
        
        .text-content {
            background: var(--color-gray-light);
            padding: 1.5rem;
            border-radius: 12px;
            line-height: 1.8;
            white-space: pre-wrap;
            font-size: 1rem;
            color: var(--color-text);
        }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid var(--color-gray);
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
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
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .info-card {
            background: var(--color-accent-light);
            padding: 1rem 1.25rem;
            border-radius: 14px;
            border: 1px solid rgba(248, 184, 3, 0.2);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .info-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #FFF2C7;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--color-accent-dark);
        }

        .info-icon i {
            width: 18px;
            height: 18px;
        }
        
        .info-label {
            font-size: 0.85rem;
            color: var(--color-muted);
            margin-bottom: 0.25rem;
        }
        
        .info-value {
            font-size: 1rem;
            font-weight: 600;
            color: var(--color-text);
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo-container">
                    <div class="logo-circle"><img src="{{ asset('images/image.png') }}" alt="Ruma Logo"></div>
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
                <h1 class="header-title">Detail Materi</h1>
            </header>
            
            <!-- Content Area -->
            <div class="content-area">
                <div style="margin-bottom: 1.5rem;">
                    <a href="{{ route('materi.index') }}" class="back-link">
                        <i data-lucide="arrow-left"></i>
                        Kembali ke Daftar Materi
                    </a>
                </div>

                <div class="detail-container">
                    <!-- Header -->
                    <div class="detail-header">
                        <div style="flex: 1;">
                            <h2 class="detail-title">{{ $materi->judul }}</h2>
                            <div class="detail-meta">
                                <span class="meta-badge badge-primary">{{ ucfirst($materi->tipe_konten) }}</span>
                                <span class="meta-badge {{ $materi->status_aktif ? 'badge-success' : 'badge-danger' }}">
                                    {{ $materi->status_aktif ? 'Aktif' : 'Nonaktif' }}
                                </span>
                                @if($materi->mataPelajaran)
                                    <span class="meta-item"><i data-lucide="book-open"></i> {{ $materi->mataPelajaran->nama }}</span>
                                @endif
                                @if($materi->level)
                                    <span class="meta-item"><i data-lucide="layers"></i> {{ $materi->level->nama }}</span>
                                @endif
                                @if($materi->jumlah_halaman)
                                    <span class="meta-item"><i data-lucide="file-text"></i> {{ $materi->jumlah_halaman }} halaman</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Info Grid -->
                    <div class="info-grid">
                        <div class="info-card">
                            <div class="info-icon"><i data-lucide="user"></i></div>
                            <div>
                                <div class="info-label">Dibuat Oleh</div>
                                <div class="info-value">{{ $materi->pengguna->nama ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="info-card">
                            <div class="info-icon"><i data-lucide="calendar-plus"></i></div>
                            <div>
                                <div class="info-label">Tanggal Dibuat</div>
                                <div class="info-value">{{ $materi->created_at->format('d M Y H:i') }}</div>
                            </div>
                        </div>
                        <div class="info-card">
                            <div class="info-icon"><i data-lucide="refresh-cw"></i></div>
                            <div>
                                <div class="info-label">Terakhir Diupdate</div>
                                <div class="info-value">{{ $materi->updated_at->format('d M Y H:i') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    @if($materi->deskripsi)
                        <div class="content-section">
                            <h3 class="section-title">Deskripsi</h3>
                            <div class="section-content">{{ $materi->deskripsi }}</div>
                        </div>
                    @endif

                    <!-- Konten -->
                    <div class="content-section">
                        <h3 class="section-title">Konten Materi</h3>
                        
                        @if($materi->tipe_konten == 'file' && $materi->file_url)
                            <div>
                                <p style="margin-bottom: 1rem; color: var(--color-muted);">
                                    File: <strong>{{ basename($materi->file_path) }}</strong>
                                </p>
                                <iframe 
                                    src="{{ $materi->file_url }}" 
                                    class="pdf-viewer"
                                    type="application/pdf">
                                    <p style="padding: 2rem; text-align: center; color: var(--color-muted);">
                                        Browser Anda tidak mendukung preview PDF. 
                                        <a href="{{ $materi->file_url }}" target="_blank" style="color: var(--color-accent-dark); text-decoration: underline;">
                                            Klik di sini untuk membuka file
                                        </a>
                                    </p>
                                </iframe>
                                <div style="margin-top: 1rem;">
                                    <a href="{{ $materi->file_url }}" target="_blank" class="btn btn-primary">
                                        <i data-lucide="download"></i>
                                        Download File
                                    </a>
                                </div>
                            </div>
                        @elseif($materi->tipe_konten == 'teks' && $materi->konten_teks)
                            <div class="text-content">{{ $materi->konten_teks }}</div>
                        @else
                            <div style="padding: 2rem; text-align: center; color: var(--color-muted); background: var(--color-gray-light); border-radius: 12px;">
                                <p>Konten belum tersedia</p>
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <a href="{{ route('materi.edit', $materi->id) }}" class="btn btn-primary">
                            <i data-lucide="edit-3"></i>
                            Edit Materi
                        </a>
                        <a href="{{ route('materi.index') }}" class="btn btn-secondary">
                            <i data-lucide="arrow-left"></i>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
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
    </script>
    <script>
    lucide.createIcons();
</script>
</body>
</html>



