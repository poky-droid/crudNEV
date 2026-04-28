<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Belajar' }} — Admin Panel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --bg: #0d0d0f;
            --surface: #16161a;
            --surface2: #1e1e24;
            --border: #2a2a35;
            --accent: #7c6af7;
            --accent2: #f7c66a;
            --text: #e8e8f0;
            --text-muted: #7a7a95;
            --danger: #f76a6a;
            --success: #6af7a8;
            --sidebar-w: 260px;
            --radius: 12px;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
        }
        .sidebar-logo {
            padding: 28px 24px 20px;
            border-bottom: 1px solid var(--border);
        }
        .sidebar-logo h1 {
            font-family: 'Syne', sans-serif;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .sidebar-logo h1 span { color: var(--accent); }
        .sidebar-logo p {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 3px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
        .sidebar-user {
            padding: 16px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .sidebar-user .avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: var(--accent);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 14px;
            color: #fff;
            flex-shrink: 0;
        }
        .sidebar-user .info { flex: 1; min-width: 0; }
        .sidebar-user .info strong {
            display: block;
            font-size: 13px;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .sidebar-user .info span {
            font-size: 11px;
            color: var(--text-muted);
        }

        nav.sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 12px 0;
        }
        .nav-group-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--text-muted);
            padding: 12px 24px 6px;
            font-weight: 500;
        }
        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 24px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 14px;
            font-weight: 400;
            transition: all .15s;
            border-left: 3px solid transparent;
        }
        .nav-item:hover {
            color: var(--text);
            background: var(--surface2);
        }
        .nav-item.active {
            color: var(--accent);
            background: rgba(124,106,247,.08);
            border-left-color: var(--accent);
            font-weight: 500;
        }
        .nav-item i { width: 16px; text-align: center; font-size: 13px; }

        .sidebar-footer {
            padding: 16px 24px;
            border-top: 1px solid var(--border);
        }
        .logout-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background: transparent;
            color: var(--text-muted);
            font-size: 13px;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: all .15s;
            text-decoration: none;
        }
        .logout-btn:hover {
            border-color: var(--danger);
            color: var(--danger);
        }

        /* ── MAIN ── */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0 32px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .topbar h2 {
            font-family: 'Syne', sans-serif;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: -0.3px;
        }
        .content {
            padding: 32px;
            flex: 1;
        }

        /* ── CARDS & TABLES ── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 24px;
            margin-bottom: 24px;
        }
        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .card-header h3 {
            font-family: 'Syne', sans-serif;
            font-size: 15px;
            font-weight: 700;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
        }
        thead th {
            text-align: left;
            padding: 10px 14px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            font-weight: 500;
            border-bottom: 1px solid var(--border);
        }
        tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background .12s;
        }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: var(--surface2); }
        tbody td { padding: 12px 14px; vertical-align: middle; }

        /* ── BUTTONS ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 18px;
            border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: all .15s;
            white-space: nowrap;
        }
        .btn-primary { background: var(--accent); color: #fff; }
        .btn-primary:hover { opacity: .85; }
        .btn-sm { padding: 6px 12px; font-size: 12px; }
        .btn-danger { background: rgba(247,106,106,.12); color: var(--danger); border: 1px solid rgba(247,106,106,.2); }
        .btn-danger:hover { background: var(--danger); color: #fff; }
        .btn-edit { background: rgba(247,198,106,.1); color: var(--accent2); border: 1px solid rgba(247,198,106,.2); }
        .btn-edit:hover { background: var(--accent2); color: #000; }
        .btn-ghost { background: transparent; color: var(--text-muted); border: 1px solid var(--border); }
        .btn-ghost:hover { color: var(--text); border-color: var(--text-muted); }

        /* ── FORMS ── */
        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            margin-bottom: 8px;
            font-weight: 500;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 11px 14px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            outline: none;
            transition: border .15s;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: var(--accent);
        }
        .form-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 8px;
        }

        /* ── ALERTS ── */
        .alert {
            padding: 13px 18px;
            border-radius: 8px;
            font-size: 13.5px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-success { background: rgba(106,247,168,.08); border: 1px solid rgba(106,247,168,.2); color: var(--success); }
        .alert-danger { background: rgba(247,106,106,.08); border: 1px solid rgba(247,106,106,.2); color: var(--danger); }

        /* ── BADGE ── */
        .badge {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 500;
        }
        .badge-success { background: rgba(106,247,168,.12); color: var(--success); }
        .badge-muted { background: var(--surface2); color: var(--text-muted); }

        /* ── EMPTY STATE ── */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-muted);
        }
        .empty-state i { font-size: 40px; margin-bottom: 16px; display: block; opacity: .3; }
        .empty-state p { font-size: 14px; }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-logo">
        <h1>NEV <span>.</span></h1>
        <p>Admin Panel</p>
    </div>
    <div class="sidebar-user">
        <div class="avatar">{{ strtoupper(substr(auth()->user()->nama ?? 'A', 0, 1)) }}</div>
        <div class="info">
            <strong>{{ auth()->user()->nama ?? 'Admin' }}</strong>
            <span>{{ auth()->user()->nim ?? '-' }}</span>
        </div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-group-label">Utama</div>
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-gauge-high"></i> Dashboard
        </a>

        <div class="nav-group-label">Master Data</div>
        <a href="{{ route('jabatan.index') }}" class="nav-item {{ request()->routeIs('jabatan.*') ? 'active' : '' }}">
            <i class="fa-solid fa-id-badge"></i> Jabatan
        </a>
        <a href="{{ route('divisi.index') }}" class="nav-item {{ request()->routeIs('divisi.*') ? 'active' : '' }}">
            <i class="fa-solid fa-layer-group"></i> Divisi
        </a>
        <a href="{{ route('anggota.index') }}" class="nav-item {{ request()->routeIs('anggota.*') ? 'active' : '' }}">
            <i class="fa-solid fa-users"></i> Anggota
        </a>

        <div class="nav-group-label">Konten</div>
        <a href="{{ route('news.index') }}" class="nav-item {{ request()->routeIs('news.*') ? 'active' : '' }}">
            <i class="fa-solid fa-newspaper"></i> News
        </a>
        <a href="{{ route('modul.index') }}" class="nav-item {{ request()->routeIs('modul.*') ? 'active' : '' }}">
            <i class="fa-solid fa-book-open"></i> Modul
        </a>

        <div class="nav-group-label">Lainnya</div>
        <a href="{{ route('sponsor.index') }}" class="nav-item {{ request()->routeIs('sponsor.*') ? 'active' : '' }}">
            <i class="fa-solid fa-handshake"></i> Sponsor
        </a>
    </nav>
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </button>
        </form>
    </div>
</aside>

<div class="main">
    <header class="topbar">
        <h2>{{ $title ?? 'Dashboard' }}</h2>
    </header>
    <div class="content">
        @if(session('success'))
            <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger"><i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}</div>
        @endif
        @yield('content')
    </div>
</div>

</body>
</html>
