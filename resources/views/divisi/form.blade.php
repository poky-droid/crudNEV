@extends('layouts.app')
@section('content')
<div class="card" style="max-width:500px">
    <div class="card-header">
        <h3>{{ isset($divisi) ? 'Edit Divisi' : 'Tambah Divisi' }}</h3>
    </div>
    <form method="POST" action="{{ isset($divisi) ? route('divisi.update', $divisi) : route('divisi.store') }}">
        @csrf
        @if(isset($divisi)) @method('PUT') @endif
        <div class="form-group">
            <label>Nama Divisi</label>
            <input type="text" name="nama_divisi" value="{{ old('nama_divisi', $divisi->nama_divisi ?? '') }}" placeholder="Contoh: IT, Desain, Humas..." required>
        </div>
        <div class="form-actions">
            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            <a href="{{ route('divisi.index') }}" class="btn btn-ghost">Batal</a>
        </div>
    </form>
</div>
@endsection
