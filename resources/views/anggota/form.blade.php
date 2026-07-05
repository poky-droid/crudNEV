@extends('layouts.app')
@section('content')
<div class="card" style="max-width:680px">
    <div class="card-header">
        <h3>{{ isset($anggota) ? 'Edit Anggota' : 'Tambah Anggota' }}</h3>
    </div>
    <form method="POST" action="{{ isset($anggota) ? route('anggota.update', $anggota) : route('anggota.store') }}" enctype="multipart/form-data">
        @csrf
        @if(isset($anggota)) @method('PUT') @endif

        <div class="form-grid-2">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" value="{{ old('nama', $anggota->nama ?? '') }}" required>
            </div>
            <div class="form-group">
                <label>NIM</label>
                <input type="text" name="nim" value="{{ old('nim', $anggota->nim ?? '') }}" required>
            </div>
        </div>

        <div class="form-group">
            <label>Jurusan</label>
            <input type="text" name="jurusan" value="{{ old('jurusan', $anggota->jurusan ?? '') }}">
        </div>
        <div class="form-group">
            <label>Email / Gmail</label>
            <input type="email" name="email" value="{{ old('email', $anggota->email ?? '') }}" required>
        </div>
        

        <div class="form-grid-2">
            <div class="form-group">
                <label>Divisi</label>
                <select name="divisi_id">
                    <option value="">— Pilih Divisi —</option>
                    @foreach($divisis as $d)
                        <option value="{{ $d->id }}" {{ old('divisi_id', $anggota->divisi_id ?? '') == $d->id ? 'selected' : '' }}>{{ $d->nama_divisi }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Jabatan</label>
                <select name="jabatan_id">
                    <option value="">— Pilih Jabatan —</option>
                    @foreach($jabatans as $j)
                        <option value="{{ $j->id }}" {{ old('jabatan_id', $anggota->jabatan_id ?? '') == $j->id ? 'selected' : '' }}>{{ $j->nama_jabatan }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Foto</label>
            <input type="file" name="foto" accept="image/*">
            @if(isset($anggota) && $anggota->foto)
                <img
                    src="{{ Storage::disk('s3')->url($anggota->foto) }}"
                    alt="{{ $anggota->nama }}"
                    style="width:150px;height:150px;object-fit:cover;"
                >

                <p style="font-size:12px;color:var(--text-muted);margin-top:6px">
                    File saat ini: {{ $anggota->foto }}
                </p>
            @endif
        </div>

        <div class="form-group">
            <label>Password {{ isset($anggota) ? '(Kosongkan jika tidak diubah)' : '' }}</label>
            <input type="password" name="password" {{ isset($anggota) ? '' : 'required' }} placeholder="Min. 6 karakter">
        </div>

        @if($errors->any())
            <div style="margin-bottom:16px">
                @foreach($errors->all() as $e)
                    <p style="color:var(--danger);font-size:13px;margin-bottom:4px"><i class="fa-solid fa-circle-xmark"></i> {{ $e }}</p>
                @endforeach
            </div>
        @endif

        <div class="form-actions">
            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            <a href="{{ route('anggota.index') }}" class="btn btn-ghost">Batal</a>
        </div>
    </form>
</div>
@endsection
