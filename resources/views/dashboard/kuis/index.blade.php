<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kuis - AKSES</title>
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
            color: var(--color-accent);
            font-weight: 800;
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
            gap: 0.5rem;
            justify-content: center;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
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
            padding: 1.5rem 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
        }

        .header-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: #FFFFFF;
        }

        .content-area {
            flex: 1;
            padding: 2rem;
        }


        .page {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .card {
            background: var(--color-white);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0,0,0,0.04);
            margin-bottom: 1rem;
        }
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 0.75rem;
            margin-bottom: 1rem;
        }
        .stat-card {
            background: #FFF7E6;
            border: 1px solid #F2E3C2;
            border-radius: 14px;
            padding: 0.85rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.7rem;
        }
        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: rgba(248, 184, 3, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #A16207;
        }
        .stat-label { font-size: 0.8rem; color: var(--color-text-light); }
        .stat-value { font-size: 1.1rem; font-weight: 700; }

        .title {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .desc {
            color: var(--color-text-light);
            margin-top: 0.35rem;
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
            gap: 0.4rem;
        }

        .btn-primary {
            background: var(--color-accent);
            color: #1F2937;
        }

        .btn-secondary {
            background: var(--color-gray);
            color: var(--color-text);
        }
        .btn-outline {
            background: #FFFDF5;
            color: #92400E;
            border: 2px solid #F8B803;
        }
        .btn-outline:hover {
            background: #FFF5DB;
        }
        .btn-sm {
            padding: 0.4rem 0.7rem;
            border-radius: 8px;
            font-size: 0.85rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            text-align: left;
            padding: 0.75rem;
            border-bottom: 1px solid var(--color-gray);
            font-size: 0.95rem;
        }

        .tag {
            display: inline-block;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.3rem 0.6rem;
            border-radius: 999px;
            background: rgba(248, 184, 3, 0.15);
            color: #B35E00;
        }

        .actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.25rem 0.6rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .badge-active { background: #DCFCE7; color: #166534; }
        .badge-inactive { background: #FEE2E2; color: #991B1B; }
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
                <h1 class="header-title">Manajemen Kuis</h1>
            </header>
            <div class="content-area">
                <div class="page">
                <div class="card">
                    <div class="actions" style="justify-content: space-between; align-items: center;">
                        <div>
                            <div class="title">Manajemen Kuis</div>
                            <div class="desc">Buat dan kelola kuis untuk materi pembelajaran.</div>
                        </div>
                        <div class="actions">
                            <a href="{{ route('kuis.create') }}" class="btn btn-primary">
                                <i data-lucide="plus-circle"></i>
                                Tambah Kuis
                            </a>
                        </div>
                    </div>
                </div>

                <div class="stat-grid">
                    <div class="stat-card">
                        <div class="stat-icon"><i data-lucide="clipboard-list"></i></div>
                        <div>
                            <div class="stat-label">Total Kuis</div>
                            <div class="stat-value">{{ $kuis->total() }}</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i data-lucide="check-circle"></i></div>
                        <div>
                            <div class="stat-label">Kuis Aktif</div>
                            <div class="stat-value">{{ $kuis->where('status_aktif', true)->count() }}</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i data-lucide="pause-circle"></i></div>
                        <div>
                            <div class="stat-label">Nonaktif</div>
                            <div class="stat-value">{{ $kuis->where('status_aktif', false)->count() }}</div>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="card" style="border-left:4px solid #16A34A;">
                        <span class="tag">Sukses</span>
                        <div class="desc" style="margin-top:0.35rem;">{{ session('success') }}</div>
                    </div>
                @endif

                <div class="card">
                    <table>
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Materi</th>
                                <th>Pertanyaan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kuis as $item)
                                <tr>
                                    <td>{{ $item->judul }}</td>
                                    <td>{{ $item->materi->judul ?? '-' }}</td>
                                    <td>{{ $item->pertanyaan_count }}</td>
                                    <td>
                                        @if($item->status_aktif)
                                            <span class="badge badge-active"><i data-lucide="check"></i> Aktif</span>
                                        @else
                                            <span class="badge badge-inactive"><i data-lucide="x"></i> Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <a href="{{ route('kuis.show', $item->id) }}" class="btn btn-outline btn-sm">
                                                <i data-lucide="eye"></i>
                                                Detail
                                            </a>
                                            <a href="{{ route('kuis.edit', $item->id) }}" class="btn btn-outline btn-sm">
                                                <i data-lucide="pencil"></i>
                                                Edit
                                            </a>
                                            <form action="{{ route('kuis.destroy', $item->id) }}" method="post" onsubmit="return confirm('Hapus kuis ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-secondary btn-sm" type="submit">
                                                    <i data-lucide="trash-2"></i>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="desc">Belum ada kuis.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div style="margin-top:1rem;">
                        {{ $kuis->links() }}
                    </div>
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

