@extends('layouts.app')
@section('content')
<div class="card" style="max-width:500px">
    <div class="card-header">
        <h3>{{ isset($jabatan) ? 'Edit Jabatan' : 'Tambah Jabatan' }}</h3>
    </div>
    <form method="POST" action="{{ isset($jabatan) ? route('jabatan.update', $jabatan) : route('jabatan.store') }}">
        @csrf
        @if(isset($jabatan)) @method('PUT') @endif
        <div class="form-group">
            <label>Nama Jabatan</label>
            <input type="text" name="nama_jabatan" value="{{ old('nama_jabatan', $jabatan->nama_jabatan ?? '') }}" placeholder="Contoh: Ketua, Sekretaris..." required>
        </div>
        <div class="form-actions">
            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            <a href="{{ route('jabatan.index') }}" class="btn btn-ghost">Batal</a>
        </div>
    </form>
</div>
@endsection
