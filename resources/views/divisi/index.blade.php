@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">
        <h3>Daftar Divisi</h3>
        <a href="{{ route('divisi.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah Divisi</a>
    </div>
    <table>
        <thead><tr><th>#</th><th>Nama Divisi</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($divisis as $i => $d)
            <tr>
                <td style="color:var(--text-muted);width:40px">{{ $i+1 }}</td>
                <td>{{ $d->nama_divisi }}</td>
                <td>
                    <div style="display:flex;gap:6px">
                        <a href="{{ route('divisi.edit', $d) }}" class="btn btn-sm btn-edit"><i class="fa-solid fa-pen"></i></a>
                        <form method="POST" action="{{ route('divisi.destroy', $d) }}" onsubmit="return confirm('Hapus divisi ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="3"><div class="empty-state"><i class="fa-solid fa-inbox"></i><p>Belum ada divisi</p></div></td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
