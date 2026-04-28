@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">
        <h3>Daftar Sponsor</h3>
        <a href="{{ route('sponsor.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah Sponsor</a>
    </div>
    <table>
        <thead>
            <tr><th>#</th><th>Nama</th><th>Website</th><th>Kontak</th><th>Status</th><th>Urutan</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($sponsors as $i => $s)
            <tr>
                <td style="color:var(--text-muted);width:40px">{{ $i+1 }}</td>
                <td>{{ $s->nama }}</td>
                <td style="font-size:12px">{{ $s->website ?? '-' }}</td>
                <td style="font-size:12px">{{ $s->kontak ?? '-' }}</td>
                <td>
                    <span class="badge {{ $s->status=='aktif' ? 'badge-success' : 'badge-muted' }}">
                        {{ $s->status }}
                    </span>
                </td>
                <td style="color:var(--text-muted)">{{ $s->urutan }}</td>
                <td>
                    <div style="display:flex;gap:6px">
                        <a href="{{ route('sponsor.edit', $s) }}" class="btn btn-sm btn-edit"><i class="fa-solid fa-pen"></i></a>
                        <form method="POST" action="{{ route('sponsor.destroy', $s) }}" onsubmit="return confirm('Hapus sponsor ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7"><div class="empty-state"><i class="fa-solid fa-handshake"></i><p>Belum ada sponsor</p></div></td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
