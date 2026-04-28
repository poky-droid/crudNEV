@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">
        <h3>Daftar News</h3>
        <a href="{{ route('news.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah News</a>
    </div>
    <table>
        <thead>
            <tr><th>#</th><th>Judul</th><th>Penulis</th><th>Konten</th><th>Tgl Dibuat</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($newsList as $i => $n)
            <tr>
                <td style="color:var(--text-muted);width:40px">{{ $i+1 }}</td>
                <td>{{ $n->judul }}</td>
                <td>{{ $n->anggota->nama ?? '-' }}</td>
                <td><span class="badge badge-muted">{{ $n->konten->count() }} blok</span></td>
                <td style="color:var(--text-muted);font-size:12px">{{ $n->created_at->format('d M Y') }}</td>
                <td>
                    <div style="display:flex;gap:6px">
                        <a href="{{ route('news.edit', $n) }}" class="btn btn-sm btn-edit"><i class="fa-solid fa-pen"></i></a>
                        <form method="POST" action="{{ route('news.destroy', $n) }}" onsubmit="return confirm('Hapus news ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6"><div class="empty-state"><i class="fa-solid fa-newspaper"></i><p>Belum ada news</p></div></td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
