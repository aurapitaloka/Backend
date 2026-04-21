<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Konten Landing - Ruma Dashboard</title>
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
        * { margin: 0; padding: 0; box-sizing: border-box; }
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
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
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
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .nav-item.active {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
        }
        .nav-item.active a {
            color: #FFFFFF;
            font-weight: 600;
            border-left: 4px solid var(--color-accent);
        }
        .nav-item:not(.active):hover {
            background: rgba(255, 255, 255, 0.1);
        }
        .nav-icon {
            font-size: 1.5rem;
            width: 24px;
            text-align: center;
            color: #CBD5E1;
        }
        .logout-btn {
            margin: 1rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 12px;
            color: var(--color-white);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .header-bar {
            background: linear-gradient(135deg, #1F2937 0%, #111827 100%);
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
        }
        .header-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #FFFFFF;
        }
        .content-area {
            flex: 1;
            padding: 2rem;
        }
        .detail-container {
            background: var(--color-white);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        .detail-header {
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid var(--color-gray);
        }
        .detail-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--color-text);
            margin-bottom: 0.5rem;
        }
        .detail-meta {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        .meta-badge {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
        }
        .badge-success {
            background: #d4edda;
            color: #155724;
        }
        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .info-card {
            background: #FFF9E6;
            padding: 1.25rem 1.5rem;
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
            color: #B45309;
        }

        .info-icon i {
            width: 18px;
            height: 18px;
        }
        .info-label {
            font-size: 0.875rem;
            color: var(--color-text-light);
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        .info-value {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--color-text);
            word-break: break-word;
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
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }
        .btn-primary {
            background: var(--color-primary);
            color: #ffffff;
        }
        .btn-primary:hover {
            background: var(--color-primary-dark);
        }
        .btn-secondary {
            background: var(--color-gray);
            color: var(--color-text);
        }
        .btn-secondary:hover {
            background: #d1d5db;
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
            background: var(--color-primary-light);
        }
        .preview-image {
            max-width: 240px;
            border-radius: 12px;
            border: 1px solid var(--color-gray);
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
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

        <main class="main-content">
            <header class="header-bar">
                <h1 class="header-title">Detail Konten Landing</h1>
            </header>

            <div class="content-area">
                <div style="margin-bottom: 1.5rem;">
                    <a href="{{ route('landing.index') }}" class="back-link">
                        <i data-lucide="arrow-left"></i>
                        Kembali ke Daftar Landing
                    </a>
                </div>

                <div class="detail-container">
                    <div class="detail-header">
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <div style="width: 80px; height: 80px; background: var(--color-primary-light); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i data-lucide="layout-dashboard" style="width: 34px; height: 34px; color: var(--color-accent);"></i>
                            </div>
                            <div>
                                <h2 class="detail-title">{{ $landingItem->title }}</h2>
                                <div class="detail-meta">
                                    <span class="meta-badge {{ $landingItem->is_active ? 'badge-success' : 'badge-danger' }}">
                                        {{ $landingItem->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                    <span class="meta-badge badge-success">{{ \App\Models\LandingItem::sectionLabel($landingItem->section) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="info-grid">
                        <div class="info-card">
                            <div class="info-icon"><i data-lucide="layout-dashboard"></i></div>
                            <div>
                                <div class="info-label">Bagian</div>
                                <div class="info-value">{{ \App\Models\LandingItem::sectionLabel($landingItem->section) }}</div>
                            </div>
                        </div>
                        <div class="info-card">
                            <div class="info-icon"><i data-lucide="type"></i></div>
                            <div>
                                <div class="info-label">Judul</div>
                                <div class="info-value">{{ $landingItem->title }}</div>
                            </div>
                        </div>
                        <div class="info-card">
                            <div class="info-icon"><i data-lucide="calendar-plus"></i></div>
                            <div>
                                <div class="info-label">Tanggal Dibuat</div>
                                <div class="info-value">{{ $landingItem->created_at->format('d M Y H:i') }}</div>
                            </div>
                        </div>
                        <div class="info-card">
                            <div class="info-icon"><i data-lucide="refresh-cw"></i></div>
                            <div>
                                <div class="info-label">Terakhir Diupdate</div>
                                <div class="info-value">{{ $landingItem->updated_at->format('d M Y H:i') }}</div>
                            </div>
                        </div>
                        @if($landingItem->image_path)
                            <div class="info-card" style="grid-column: 1 / -1;">
                                <div class="info-icon"><i data-lucide="image"></i></div>
                                <div>
                                    <div class="info-label">Gambar</div>
                                    <div class="info-value">
                                        <img src="{{ Storage::url($landingItem->image_path) }}" alt="Gambar" class="preview-image">
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="action-buttons">
                        <a href="{{ route('landing.edit', $landingItem->id) }}" class="btn btn-primary">
                            <i data-lucide="pencil"></i>
                            Edit Konten
                        </a>
                        <a href="{{ route('landing.index') }}" class="btn btn-secondary">
                            <i data-lucide="arrow-left"></i>
                            Kembali
                        </a>
                    </div>
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
</body>
</html>


