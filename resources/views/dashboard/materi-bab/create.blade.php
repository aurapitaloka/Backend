<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Bab - Ruma Dashboard</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
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

        .dashboard-container { display: flex; min-height: 100vh; }

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

        .logo-container { display: flex; align-items: center; gap: 1rem; }
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
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .logout-btn:hover { background: rgba(255, 255, 255, 0.3); }

        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

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

        .content-area { flex: 1; padding: 2rem; }

        .back-link-clean {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--color-primary);
            text-decoration: none;
            transition: 0.2s ease;
            margin-bottom: 1rem;
        }

        .back-link-clean:hover {
            color: var(--color-accent);
            transform: translateX(-3px);
        }

        .form-container {
            background: var(--color-white);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0,0,0,0.04);
            max-width: 1080px;
        }

        .hero-panel {
            display: grid;
            grid-template-columns: 1.4fr 0.8fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .hero-card,
        .info-card {
            border-radius: 18px;
            padding: 1.25rem 1.35rem;
            background: linear-gradient(180deg, #FFFCF2 0%, #FFFFFF 100%);
            border: 1px solid rgba(248, 184, 3, 0.18);
        }

        .hero-title {
            font-size: 1.85rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            color: var(--color-text);
        }

        .hero-subtitle {
            color: var(--color-text-light);
            line-height: 1.7;
            font-size: 0.96rem;
        }

        .hero-book-name {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            margin-top: 0.9rem;
            padding: 0.7rem 0.9rem;
            border-radius: 12px;
            background: #FFFFFF;
            border: 1px solid rgba(17, 24, 39, 0.08);
            font-weight: 700;
        }

        .mini-label {
            font-size: 0.82rem;
            color: var(--color-text-light);
            margin-bottom: 0.35rem;
        }

        .mini-value {
            font-size: 0.96rem;
            font-weight: 700;
            color: var(--color-text);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1rem 1.25rem;
        }

        .form-group { margin-bottom: 1.25rem; }
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

        .form-textarea { min-height: 180px; resize: vertical; }

        .section-title {
            font-size: 1.12rem;
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

        .required { color: #DC2626; }

        .form-checkbox {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            font-weight: 600;
        }

        .form-checkbox input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .hint {
            color: var(--color-text-light);
            font-size: 0.85rem;
            margin-top: 0.35rem;
            display: block;
        }

        .note-box {
            padding: 1rem 1.1rem;
            border-radius: 14px;
            background: #F8FAFC;
            color: var(--color-text-light);
            font-size: 0.9rem;
            line-height: 1.65;
            margin-bottom: 1.25rem;
        }

        .alert {
            padding: 1rem 1.1rem;
            border-radius: 12px;
            margin-bottom: 1.25rem;
        }

        .alert-error {
            background: #FEF2F2;
            color: #991B1B;
            border: 1px solid #FCA5A5;
        }

        .btn-row {
            display: flex;
            gap: 0.85rem;
            flex-wrap: wrap;
            margin-top: 1.75rem;
        }

        .btn {
            padding: 0.95rem 1.2rem;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
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

        .btn-secondary:hover { background: #D1D5DB; }

        .pdf-selection-panel { margin-top: 1rem; padding: 1rem; border: 1px solid #E5E7EB; border-radius: 14px; background: linear-gradient(180deg, #FFFCF2 0%, #FFFFFF 100%); }
        .pdf-selection-toolbar, .pdf-selection-actions { display:flex; gap:.75rem; flex-wrap:wrap; justify-content:space-between; align-items:center; }
        .pdf-selection-range { display:grid; grid-template-columns:repeat(2,minmax(0,180px)); gap:.9rem; margin-bottom:1rem; }
        .pdf-action-btn { border:1px solid rgba(31,41,55,.12); background:#fff; color:#111827; border-radius:999px; padding:.55rem .9rem; font-size:.85rem; font-weight:600; cursor:pointer; }
        .pdf-pages-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(150px,1fr)); gap:1rem; }
        .pdf-page-card { border:1px solid rgba(17,24,39,.08); border-radius:16px; background:#fff; overflow:hidden; box-shadow:0 6px 18px rgba(17,24,39,.06); }
        .pdf-page-card.selected { border-color: rgba(248,184,3,.95); background: linear-gradient(180deg, #FFF5CC 0%, #FFF0B3 100%); }
        .pdf-page-preview { position:relative; aspect-ratio:3/4; background:#F8FAFC; display:flex; align-items:center; justify-content:center; overflow:hidden; }
        .pdf-page-preview canvas { width:100%; height:100%; object-fit:contain; display:block; }
        .pdf-page-check { position:absolute; top:.6rem; right:.6rem; width:24px; height:24px; accent-color:#F8B803; }
        .pdf-page-meta { padding:.85rem .9rem 1rem; }
        .pdf-page-title { font-size:.9rem; font-weight:700; }
        .pdf-page-subtitle, .pdf-selection-empty, .pdf-selection-loading, .current-file { color:#6B7280; font-size:.9rem; }
        .current-file { margin-top:.5rem; }

        @media (max-width: 960px) {
            .hero-panel { grid-template-columns: 1fr; }
        }

        @media (max-width: 768px) {
            .sidebar { display: none; }
            .main-content { margin-left: 0; }
            .content-area { padding: 1rem; }
            .header-bar { padding: 1.1rem 1rem; }
            .form-container { padding: 1.25rem; }
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

            <div class="logout-btn" onclick="handleLogout()">
                <i data-lucide="log-out"></i>
                <span>Keluar</span>
            </div>
        </aside>

        <main class="main-content">
            <header class="header-bar">
                <h1 class="header-title">Tambah Bab</h1>
            </header>

            <div class="content-area">
                <a href="{{ route('materi.show', $materi->id) }}" class="back-link-clean">
                    <i data-lucide="arrow-left"></i>
                    Kembali ke detail buku
                </a>

                <div class="form-container">
                    <div class="hero-panel">
                        <div class="hero-card">
                            <div class="hero-title">Tambah Bab Baru</div>
                            <div class="hero-subtitle">
                                Tambahkan chapter baru agar struktur materi tetap rapi sebagai satu buku utuh. Bab ini akan menjadi bagian dari alur baca dan bisa dihubungkan ke kuis setelah disimpan.
                            </div>
                            <div class="hero-book-name">
                                <i data-lucide="book-open"></i>
                                <span>{{ $materi->judul }}</span>
                            </div>
                        </div>
                        <div class="info-card">
                            <div class="mini-label">Posisi Bab Berikutnya</div>
                            <div class="mini-value">Bab {{ $nextUrutan ?? 1 }}</div>
                            <div class="mini-label" style="margin-top:0.85rem;">Pola Pengelolaan</div>
                            <div class="hero-subtitle" style="font-size:0.9rem;">
                                Satu buku dapat memiliki banyak bab. Setiap bab bisa memakai teks langsung atau file terpisah sesuai kebutuhan guru.
                            </div>
                        </div>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-error">
                            <ul style="margin:0; padding-left:1.2rem;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="section-title"><i data-lucide="layout-template"></i> Form Bab</div>
                    <div class="section-subtitle">Isi data bab dengan jelas agar materi mudah dibaca dan siap dilanjutkan ke kuis per bab.</div>

                    <div class="note-box">
                        Bab disimpan sebagai bagian dari buku ini, bukan sebagai materi terpisah. Setelah bab berhasil dibuat, kamu bisa memakai tombol cepat di halaman detail buku untuk membuat kuis yang menempel di akhir bab.
                    </div>

                    <form method="POST" action="{{ route('materi.bab.store', $materi->id) }}" enctype="multipart/form-data" id="babForm">
                        @csrf
                        @include('dashboard.materi-bab._form')

                        <div class="btn-row">
                            <button type="submit" class="btn btn-primary">
                                <i data-lucide="save"></i>
                                Simpan Bab
                            </button>
                            <a href="{{ route('materi.show', $materi->id) }}" class="btn btn-secondary">
                                <i data-lucide="arrow-left"></i>
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    @include('dashboard.materi-bab.partials.form-script', ['bab' => null])
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
    @include('components.modal')
</body>
</html>
