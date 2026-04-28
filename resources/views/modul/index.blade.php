@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">
        <h3>Daftar Modul</h3>
        <a href="{{ route('modul.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah Modul</a>
    </div>
    <table>
        <thead>
            <tr><th>#</th><th>Nama Modul</th><th>Pembuat</th><th>Akses Anggota</th><th>Konten</th><th>Tgl Dibuat</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($moduls as $i => $m)
            <tr>
                <td style="color:var(--text-muted);width:40px">{{ $i+1 }}</td>
                <td>{{ $m->nama_modul }}</td>
                <td>{{ $m->anggota->nama ?? '-' }}</td>
                <td><span class="badge badge-muted">{{ $m->anggotaAkses->count() }} anggota</span></td>
                <td><span class="badge badge-muted">{{ $m->konten->count() }} blok</span></td>
                <td style="color:var(--text-muted);font-size:12px">{{ $m->created_at->format('d M Y') }}</td>
                <td>
                    <div style="display:flex;gap:6px">
                        <a href="{{ route('modul.edit', $m) }}" class="btn btn-sm btn-edit"><i class="fa-solid fa-pen"></i></a>
                        <form method="POST" action="{{ route('modul.destroy', $m) }}" onsubmit="return confirm('Hapus modul ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7"><div class="empty-state"><i class="fa-solid fa-book-open"></i><p>Belum ada modul</p></div></td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
