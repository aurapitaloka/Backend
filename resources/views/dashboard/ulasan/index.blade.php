<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulasan - AKSES Dashboard</title>
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
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
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
            color: #FFFFFF;
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
        }
        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        .logout-btn svg {
            width: 16px;
            height: 16px;
        }
        .header-bar {
            background: linear-gradient(135deg, #1F2937 0%, #111827 100%);
            padding: 1.25rem 2rem;
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
        }
        .content-area {
            flex: 1;
            padding: 2rem;
        }
        .section-card {
            background: var(--color-white);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0,0,0,0.04);
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            text-align: left;
            padding: 0.85rem 0.75rem;
            border-bottom: 1px solid var(--color-gray);
            vertical-align: top;
        }
        .table th {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--color-text-light);
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.55rem 0.9rem;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            gap: 0.4rem;
        }
        .btn-danger {
            background: #DC2626;
            color: #fff;
        }
        .btn-danger:hover {
            background: #B91C1C;
        }
        .btn-export {
            background: #E9F9EF;
            color: #166534;
            border: 2px solid #22C55E;
            box-shadow: 0 6px 14px rgba(34, 197, 94, 0.18);
        }
        .btn-export:hover {
            background: #DFF5E7;
        }
        .action-btn {
            border-radius: 8px;
            padding: 0.45rem 0.75rem;
            font-size: 0.85rem;
        }
        .badge {
            display: inline-block;
            padding: 0.25rem 0.6rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 600;
            background: rgba(248, 184, 3, 0.15);
            color: #B35E00;
        }
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
                <h1 class="header-title">Ulasan</h1>
            </header>

            <div class="content-area">
                @if(session('success'))
                    <div class="section-card" style="margin-bottom:1rem; border-left:4px solid #16A34A;">
                        <span class="badge">Sukses</span>
                        <p style="margin-top:0.5rem; color:var(--color-text-light);">{{ session('success') }}</p>
                    </div>
                @endif

                <div style="display:flex; justify-content:flex-start; margin-bottom:1rem;">
                    <a href="{{ route('ulasan.export', [], false) }}" class="btn btn-export">
                        <i data-lucide="download"></i>
                        Export CSV
                    </a>
                </div>

                <div class="section-card">
                    @if($ulasan->count() === 0)
                        <p style="color:var(--color-text-light);">Belum ada ulasan masuk.</p>
                    @else
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Rating</th>
                                    <th>Ulasan</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ulasan as $item)
                                    <tr>
                                        <td>{{ $ulasan->firstItem() + $loop->index }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->rating ?? '-' }}</td>
                                        <td style="max-width:520px;">{{ $item->isi }}</td>
                                        <td>{{ $item->created_at->format('d M Y H:i') }}</td>
                                        <td>
                                            <button class="btn btn-danger action-btn" type="button" onclick="handleDeleteUlasan({{ $item->id }}, '{{ addslashes($item->nama) }}')">
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div style="margin-top:1rem;">
                            {{ $ulasan->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    <form id="deleteUlasanForm" method="post" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    </script>

    @include('components.modal')

    <script>
        function handleDeleteUlasan(id, nama) {
            const form = document.getElementById('deleteUlasanForm');
            showModal({
                type: 'delete',
                title: 'Hapus Ulasan',
                message: `Apakah Anda yakin ingin menghapus ulasan dari "${nama}"?`,
                icon: 'trash-2',
                confirmText: 'Ya, Hapus',
                isDanger: true,
                onConfirm: function() {
                    form.action = `/dashboard/ulasan/${id}`;
                    form.submit();
                }
            });
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
    </script>
</body>
</html>

