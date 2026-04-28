@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">
        <h3>Daftar Jabatan</h3>
        <a href="{{ route('jabatan.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah Jabatan</a>
    </div>
    <table>
        <thead><tr><th>#</th><th>Nama Jabatan</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($jabatans as $i => $j)
            <tr>
                <td style="color:var(--text-muted);width:40px">{{ $i+1 }}</td>
                <td>{{ $j->nama_jabatan }}</td>
                <td>
                    <div style="display:flex;gap:6px">
                        <a href="{{ route('jabatan.edit', $j) }}" class="btn btn-sm btn-edit"><i class="fa-solid fa-pen"></i></a>
                        <form method="POST" action="{{ route('jabatan.destroy', $j) }}" onsubmit="return confirm('Hapus jabatan ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="3"><div class="empty-state"><i class="fa-solid fa-inbox"></i><p>Belum ada jabatan</p></div></td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
