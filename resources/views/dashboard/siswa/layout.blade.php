<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Dashboard Siswa Ruma">
    <title>{{ $pageTitle ?? 'Dashboard Siswa' }} - Ruma</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        :root {
            --color-primary: #1F2937;
            --color-primary-dark: #111827;
            --color-primary-light: #F9FAFB;
            --color-accent: #F4A000;
            --color-accent-soft: rgba(244, 160, 0, 0.12);
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
            scroll-behavior: smooth;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

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

        .sidebar.closed {
            transform: translateX(-100%);
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
            scrollbar-width: none;
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 0;
            height: 0;
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
            background: rgba(255, 255, 255, 0.15);
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

        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .main-content.full {
            margin-left: 0;
        }

        .header-bar {
            background: linear-gradient(135deg, #1F2937 0%, #111827 100%);
            padding: 0.9rem 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
            position: sticky;
            top: 0;
            z-index: 100;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .header-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #FFFFFF;
            letter-spacing: 0.5px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.2);
            padding: 0.35rem 0.75rem;
            border-radius: 10px;
            backdrop-filter: blur(10px);
        }

        .user-icon {
            width: 28px;
            height: 28px;
            background: var(--color-white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            color: var(--color-accent);
            font-weight: 700;
        }

        .user-name {
            color: #FFFFFF;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .user-icon-mobile {
            display: none;
            width: 28px;
            height: 28px;
            align-items: center;
            justify-content: center;
            color: #FFFFFF;
        }

        .voice-nav-btn {
            gap: 0.5rem;
        }

        .voice-nav-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .content-area {
            flex: 1;
            padding: 2rem;
        }

        .section-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1.25rem;
        }

        .section-card {
            background: var(--color-white);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0,0,0,0.04);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            position: relative;
            overflow: hidden;
        }

        .section-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--color-accent);
        }

        .section-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0.4rem;
        }

        .section-desc {
            color: var(--color-text-light);
            margin-bottom: 1rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.7rem 1.1rem;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: var(--color-accent);
            color: #1F2937;
        }

        .btn-secondary {
            background: var(--color-gray);
            color: var(--color-text);
        }

        .btn-primary:hover {
            background: #E69300;
        }

        .btn-secondary:hover {
            background: #D1D5DB;
        }

        .tag {
            display: inline-block;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.3rem 0.6rem;
            border-radius: 999px;
            background: var(--color-accent-soft);
            color: #B35E00;
            margin-bottom: 0.7rem;
        }

        .table-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1.25rem;
        }

        .mobile-menu-toggle {
            display: inline-flex;
            background: transparent;
            color: var(--color-white);
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 0;
            font-size: 1.2rem;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .sidebar {
                top: 56px;
                height: calc(100vh - 56px);
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .header-bar {
                padding: 0.6rem 0.9rem;
            }

            .content-area {
                padding: 1.25rem 0.9rem;
            }
            .header-title {
                font-size: 1.05rem;
            }
            .user-info {
                padding: 0.3rem 0.6rem;
            }
            .voice-nav-btn {
                padding: 0.45rem 0.6rem;
                min-width: 40px;
            }
            .voice-nav-text {
                display: none;
            }
            .user-name {
                display: none;
            }
            .user-icon {
                display: none;
            }
            .user-icon-mobile {
                display: inline-flex;
            }
            .sidebar-overlay {
                top: 56px;
                height: calc(100vh - 56px);
            }
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

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .sidebar-overlay.active {
            display: block;
        }
    </style>
</head>
<body class="siswa-layout">

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <div class="dashboard-container">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo-container">
                    <div class="logo-circle"><img src="{{ asset('images/image.png') }}" alt="Ruma Logo"></div>
                    <div class="logo-text">Ruma</div>
                </div>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-item" data-route="dashboard">
                    <a href="{{ route('dashboard.siswa') }}">
                        <span class="nav-icon">
                            <i data-lucide="layout-dashboard"></i>
                        </span>
                        <span>Dashboard</span>
                    </a>
                </div>

                <div class="nav-item" data-route="materi">
                    <a href="{{ route('dashboard.siswa.materi') }}">
                        <span class="nav-icon">
                            <i data-lucide="book-open"></i>
                        </span>
                        <span>Materi</span>
                    </a>
                </div>

                <div class="nav-item" data-route="kuis">
                    <a href="{{ route('dashboard.siswa.kuis') }}">
                        <span class="nav-icon">
                            <i data-lucide="check-square"></i>
                        </span>
                        <span>Kuis</span>
                    </a>
                </div>

                <div class="nav-item" data-route="catatan">
                    <a href="{{ route('dashboard.siswa.catatan') }}">
                        <span class="nav-icon">
                            <i data-lucide="sticky-note"></i>
                        </span>
                        <span>Catatan</span>
                    </a>
                </div>

                <div class="nav-item" data-route="riwayat">
                    <a href="{{ route('dashboard.siswa.riwayat') }}">
                        <span class="nav-icon">
                            <i data-lucide="history"></i>
                        </span>
                        <span>Riwayat</span>
                    </a>
                </div>

                <div class="nav-item" data-route="pengaturan">
                    <a href="{{ route('dashboard.siswa.pengaturan') }}">
                        <span class="nav-icon">
                            <i data-lucide="settings"></i>
                        </span>
                        <span>Pengaturan</span>
                    </a>
                </div>

                <div class="nav-item" data-route="panduan">
                    <a href="{{ route('dashboard.siswa.panduan') }}">
                        <span class="nav-icon">
                            <i data-lucide="book"></i>
                        </span>
                        <span>Panduan</span>
                    </a>
                </div>

                <div class="nav-item" data-route="rak-buku">
                    <a href="{{ route('dashboard.siswa.rak-buku') }}">
                        <span class="nav-icon">
                            <i data-lucide="library"></i>
                        </span>
                        <span>Rak Buku</span>
                    </a>
                </div>
            </nav>

            <div class="logout-btn" onclick="handleLogout()" style="display:flex; align-items:center; gap:8px; justify-content:center;">
                <i data-lucide="log-out"></i>
                <span>Keluar</span>
            </div>
        </aside>

        <main class="main-content">
            <header class="header-bar">
                <div class="header-left">
                    <button class="mobile-menu-toggle" id="mobileMenuToggle" onclick="toggleSidebar()">
                        <span aria-hidden="true">&#9776;</span>
                    </button>
                    <h1 class="header-title">{{ $pageTitle ?? 'Dashboard Siswa' }}</h1>
                </div>
                <div style="display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap;">
                    <button class="btn btn-secondary voice-nav-btn" type="button" id="voiceNavToggle">
                        <span class="voice-nav-icon" id="voiceNavIcon"><i data-lucide="mic-off"></i></span>
                        <span class="voice-nav-text" id="voiceNavText">Voice Nav: Off</span>
                    </button>
                    <div class="user-info">
                        <div class="user-icon">{{ strtoupper(substr($user->nama ?? 'S', 0, 1)) }}</div>
                        <div class="user-icon-mobile"><i data-lucide="user"></i></div>
                        <div class="user-name">{{ $user->nama ?? 'Siswa' }}</div>
                    </div>
                </div>
            </header>

            <div class="content-area">
                @yield('content')
            </div>
        </main>
    </div>

    @include('components.modal')

    @if(($user->peran ?? null) === 'siswa' && (($user->siswa->level_id ?? null) === null))
        <div id="levelSetupOverlay" style="position:fixed; inset:0; background:rgba(17,24,39,0.65); z-index:2000; display:flex; align-items:center; justify-content:center; padding:1rem;">
            <div style="width:100%; max-width:520px; background:#fff; border-radius:16px; padding:1.5rem; box-shadow:0 20px 40px rgba(0,0,0,0.25);">
                <span class="tag">Profil Siswa</span>
                <h3 class="section-title">Pilih Kelas</h3>
                <p class="section-desc">Lengkapi kelas untuk menampilkan materi yang sesuai.</p>

                <form action="{{ route('dashboard.siswa.kelas.update') }}" method="post" style="margin-top:1rem;">
                    @csrf
                    <div style="display:grid; grid-template-columns: 1fr; gap:1rem;">
                        <div>
                            <label class="section-desc" for="levelSetupSelect">Kelas</label>
                            <select id="levelSetupSelect" name="level_id" class="form-select" style="width:100%; padding:0.75rem; border-radius:12px; border:1px solid var(--color-gray);" required>
                                <option value="">Pilih Kelas</option>
                                @foreach($levels ?? [] as $level)
                                    <option value="{{ $level->id }}">{{ $level->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div style="margin-top:1rem; display:flex; gap:0.5rem; flex-wrap:wrap;">
                        <button class="btn btn-primary" type="submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const main = document.querySelector('.main-content');
            const isMobile = window.matchMedia('(max-width: 768px)').matches;

            if (isMobile) {
                sidebar.classList.toggle('open');
                overlay.classList.toggle('active');
                return;
            }

            sidebar.classList.toggle('closed');
            if (main) {
                main.classList.toggle('full');
            }
        }

        function handleLogout() {
            showModal({
                type: 'logout',
                title: 'Konfirmasi Logout',
                message: 'Apakah Anda yakin ingin keluar dari akun Anda?',
                icon: 'log-out',
                confirmText: 'Ya, Keluar',
                isDanger: false,
                onConfirm: function() {
                    showInfoToast('Logout', 'Sedang keluar...');
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 1000);
                }
            });
        }

        function setActiveNavItem() {
            const currentPath = window.location.pathname;
            const navItems = document.querySelectorAll('.nav-item');

            navItems.forEach(item => item.classList.remove('active'));

            let activeRoute = null;
            if (currentPath.includes('/dashboard-siswa/materi')) {
                activeRoute = 'materi';
            } else if (currentPath.includes('/dashboard-siswa/kuis')) {
                activeRoute = 'kuis';
            } else if (currentPath === '/dashboard-siswa' || currentPath === '/dashboard-siswa/') {
                activeRoute = 'dashboard';
            } else if (currentPath.includes('/dashboard-siswa/catatan')) {
                activeRoute = 'catatan';
            } else if (currentPath.includes('/dashboard-siswa/riwayat')) {
                activeRoute = 'riwayat';
            } else if (currentPath.includes('/dashboard-siswa/pengaturan')) {
                activeRoute = 'pengaturan';
            } else if (currentPath.includes('/dashboard-siswa/panduan')) {
                activeRoute = 'panduan';
            } else if (currentPath.includes('/dashboard-siswa/rak-buku')) {
                activeRoute = 'rak-buku';
            }

            if (activeRoute) {
                const activeItem = document.querySelector(`.nav-item[data-route="${activeRoute}"]`);
                if (activeItem) {
                    activeItem.classList.add('active');
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            setActiveNavItem();
            lucide.createIcons();
        });
    </script>

    <script>
        (function() {
            if (window.__disableGlobalAsr) {
                return;
            }

            try {
                localStorage.setItem('aks_asr_lang', '{{ $user->asr_lang ?? "id-ID" }}');
                localStorage.setItem('aks_tts_lang', '{{ $user->tts_lang ?? "id-ID" }}');
                localStorage.setItem('aks_tts_rate', '{{ $user->tts_rate ?? 1.0 }}');
                localStorage.setItem('voiceNavEnabled', '{{ ($user->auto_voice_nav ?? false) ? "1" : "0" }}');
            } catch (err) {
                // ignore storage errors
            }

            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            const toggleBtn = document.getElementById('voiceNavToggle');
            const toggleText = document.getElementById('voiceNavText');
            const toggleIcon = document.getElementById('voiceNavIcon');
            let recognition = null;
            let isOn = false;
            let lastCommand = '';
            let lastCommandAt = 0;
            let shouldStayOn = false;

            const routes = {
                dashboard: '{{ route('dashboard.siswa') }}',
                materi: '{{ route('dashboard.siswa.materi') }}',
                kuis: '{{ route('dashboard.siswa.kuis') }}',
                catatan: '{{ route('dashboard.siswa.catatan') }}',
                riwayat: '{{ route('dashboard.siswa.riwayat') }}',
                perintahsuara: '{{ route('dashboard.siswa.perintah-suara') }}',
                panduan: '{{ route('dashboard.siswa.panduan') }}',
                pengaturan: '{{ route('dashboard.siswa.pengaturan') }}',
                rakbuku: '{{ route('dashboard.siswa.rak-buku') }}',
            };

            function normalize(text) {
                return (text || '')
                    .toLowerCase()
                    .replace(/[^\w\s-]/g, ' ')
                    .replace(/\s+/g, ' ')
                    .trim();
            }

            function cleanMateriQuery(text) {
                return normalize(text)
                    .replace(/^buka materi\b/, '')
                    .replace(/\btentang\b/g, '')
                    .replace(/\bjudul\b/g, '')
                    .replace(/\byang\b/g, '')
                    .replace(/\bini\b/g, '')
                    .replace(/\bitu\b/g, '')
                    .replace(/\bke\b/g, '')
                    .replace(/\bmateri\b/g, '')
                    .replace(/\s+/g, ' ')
                    .trim();
            }

            function navigate(targetKey) {
                const url = routes[targetKey];
                if (url) {
                    window.location.href = url;
                }
            }

            function handleCommand(text) {
                const now = Date.now();
                if (text === lastCommand && now - lastCommandAt < 2000) {
                    return;
                }
                lastCommand = text;
                lastCommandAt = now;

                if (text.includes('buka materi')) {
                    const target = cleanMateriQuery(text);
                    if (target && Array.isArray(window.__materiList) && window.__materiList.length > 0) {
                        const normalizedTarget = normalize(target);
                        let best = null;
                        let bestLen = 0;
                        window.__materiList.forEach(item => {
                            const title = normalize(item.title);
                            if (normalizedTarget.includes(title) || title.includes(normalizedTarget)) {
                                if (title.length > bestLen) {
                                    best = item;
                                    bestLen = title.length;
                                }
                            }
                        });
                        if (best) {
                            window.location.href = best.url;
                            return;
                        }
                    }
                    navigate('materi');
                } else if (text.includes('dashboard')) {
                    navigate('dashboard');
                } else if (text.includes('materi')) {
                    navigate('materi');
                } else if (text.includes('kuis')) {
                    navigate('kuis');
                } else if (text.includes('catatan')) {
                    navigate('catatan');
                } else if (text.includes('riwayat')) {
                    navigate('riwayat');
                } else if (text.includes('perintah suara')) {
                    navigate('perintahsuara');
                } else if (text.includes('panduan')) {
                    navigate('panduan');
                } else if (text.includes('pengaturan')) {
                    navigate('pengaturan');
                } else if (text.includes('rak buku') || text.includes('rakbuku')) {
                    navigate('rakbuku');
                }
            }

            if (!SpeechRecognition) {
                toggleBtn.disabled = true;
                toggleBtn.textContent = 'Voice Nav: Tidak Didukung';
                return;
            }

            recognition = new SpeechRecognition();
            recognition.lang = localStorage.getItem('aks_asr_lang') || 'id-ID';
            recognition.continuous = true;
            recognition.interimResults = true;

            if (toggleText) toggleText.textContent = 'Voice Nav: Off';
            if (toggleIcon) {
                toggleIcon.innerHTML = '<i data-lucide="mic-off"></i>';
            }

            recognition.onresult = function(event) {
                let transcript = '';
                for (let i = event.resultIndex; i < event.results.length; i += 1) {
                    transcript += event.results[i][0].transcript;
                }
                const normalized = normalize(transcript);
                const hasKeyword = [
                    'buka ',
                    'ke ',
                    'dashboard',
                    'materi',
                    'kuis',
                    'catatan',
                    'riwayat',
                    'perintah suara',
                    'panduan',
                    'pengaturan',
                    'rak buku',
                    'rakbuku',
                ].some(keyword => normalized.includes(keyword));
                if (hasKeyword) {
                    handleCommand(normalized);
                }
            };

            recognition.onstart = function() {
                isOn = true;
                if (toggleText) toggleText.textContent = 'Voice Nav: On';
                if (toggleIcon) {
                    toggleIcon.innerHTML = '<i data-lucide="mic"></i>';
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
            };

            recognition.onend = function() {
                isOn = false;
                if (toggleText) toggleText.textContent = 'Voice Nav: Off';
                if (toggleIcon) {
                    toggleIcon.innerHTML = '<i data-lucide="mic-off"></i>';
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
                if (shouldStayOn) {
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
                if (toggleText) toggleText.textContent = 'Voice Nav: Off';
                if (toggleIcon) {
                    toggleIcon.innerHTML = '<i data-lucide="mic-off"></i>';
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
            };

            toggleBtn.addEventListener('click', function() {
                if (!isOn) {
                    shouldStayOn = true;
                    localStorage.setItem('voiceNavEnabled', '1');
                    recognition.start();
                } else {
                    shouldStayOn = false;
                    localStorage.setItem('voiceNavEnabled', '0');
                    recognition.stop();
                }
            });

            try {
                const saved = localStorage.getItem('voiceNavEnabled');
                if (saved === '1') {
                    shouldStayOn = true;
                    setTimeout(() => {
                        try {
                            recognition.start();
                        } catch (err) {
                            // ignore - browser may block without user gesture
                        }
                    }, 500);
                }
            } catch (err) {
                // ignore
            }
        })();
    </script>

</body>
</html>

