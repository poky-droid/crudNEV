@extends('layouts.app')
@section('content')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
        margin-bottom: 28px;
    }
    .stat-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 20px 22px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        transition: border-color .15s;
    }
    .stat-card:hover { border-color: var(--accent); }
    .stat-card .icon {
        width: 38px; height: 38px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 15px;
    }
    .stat-card .num {
        font-family: 'Syne', sans-serif;
        font-size: 28px;
        font-weight: 800;
    }
    .stat-card .label { font-size: 12px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; }
    .ic-purple { background: rgba(124,106,247,.15); color: var(--accent); }
    .ic-yellow { background: rgba(247,198,106,.12); color: #f7c66a; }
    .ic-green  { background: rgba(106,247,168,.1); color: #6af7a8; }
    .ic-red    { background: rgba(247,106,106,.1); color: #f76a6a; }
    .ic-blue   { background: rgba(106,168,247,.1); color: #6aa8f7; }
</style>

<div class="stats-grid">
    <div class="stat-card">
        <div class="icon ic-purple"><i class="fa-solid fa-users"></i></div>
        <div class="num">{{ $counts['anggota'] }}</div>
        <div class="label">Anggota</div>
    </div>
    <div class="stat-card">
        <div class="icon ic-yellow"><i class="fa-solid fa-newspaper"></i></div>
        <div class="num">{{ $counts['news'] }}</div>
        <div class="label">News</div>
    </div>
    <div class="stat-card">
        <div class="icon ic-green"><i class="fa-solid fa-book-open"></i></div>
        <div class="num">{{ $counts['modul'] }}</div>
        <div class="label">Modul</div>
    </div>
    <div class="stat-card">
        <div class="icon ic-red"><i class="fa-solid fa-handshake"></i></div>
        <div class="num">{{ $counts['sponsor'] }}</div>
        <div class="label">Sponsor</div>
    </div>
    <div class="stat-card">
        <div class="icon ic-blue"><i class="fa-solid fa-layer-group"></i></div>
        <div class="num">{{ $counts['divisi'] }}</div>
        <div class="label">Divisi</div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h3>Anggota Terbaru</h3></div>
    <table>
        <thead>
            <tr>
                <th>Nama</th><th>NIM</th><th>Jurusan</th><th>Divisi</th><th>Jabatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($latestAnggota as $a)
            <tr>
                <td>{{ $a->nama }}</td>
                <td><code style="font-size:12px;color:var(--text-muted)">{{ $a->nim }}</code></td>
                <td>{{ $a->jurusan ?? '-' }}</td>
                <td>{{ $a->divisi->nama_divisi ?? '-' }}</td>
                <td>{{ $a->jabatan->nama_jabatan ?? '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;color:var(--text-muted);padding:30px">Belum ada anggota</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
