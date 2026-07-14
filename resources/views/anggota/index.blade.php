@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">
        <h3>Daftar Anggota</h3>
        <a href="{{ route('anggota.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah Anggota</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>#</th><th>Nama</th><th>NIM</th><th>Jurusan</th><th>Divisi</th><th>Jabatan</th><th>Email / Gmail</th><th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($anggotas as $i => $a)
            <tr>
                <td style="color:var(--text-muted);width:40px">{{ $i+1 }}</td>
                <td>{{ $a->nama }}</td>
                <td><code style="font-size:12px;color:var(--text-muted)">{{ $a->nim }}</code></td>
                <td>{{ $a->jurusan ?? '-' }}</td>
                <td>{{ $a->divisi->nama_divisi ?? '-' }}</td>
                <td>{{ $a->jabatan->nama_jabatan ?? '-' }}</td>
                <td>{{ $a->email ?? '-' }}</td>
                <td>
                    <div style="display:flex;gap:6px">
                        <a href="{{ route('anggota.edit', $a) }}" class="btn btn-sm btn-edit"><i class="fa-solid fa-pen"></i></a>
                        <form method="POST" action="{{ route('anggota.destroy', $a) }}" onsubmit="return confirm('Hapus anggota ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7"><div class="empty-state"><i class="fa-solid fa-users"></i><p>Belum ada anggota</p></div></td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
