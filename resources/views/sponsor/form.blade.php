@extends('layouts.app')
@section('content')
<div class="card" style="max-width:580px">
    <div class="card-header">
        <h3>{{ isset($sponsor) ? 'Edit Sponsor' : 'Tambah Sponsor' }}</h3>
    </div>
    <form method="POST" action="{{ isset($sponsor) ? route('sponsor.update', $sponsor) : route('sponsor.store') }}" enctype="multipart/form-data">
        @csrf
        @if(isset($sponsor)) @method('PUT') @endif

        <div class="form-group">
            <label>Nama Sponsor</label>
            <input type="text" name="nama" value="{{ old('nama', $sponsor->nama ?? '') }}" required>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label>Website</label>
                <input type="url" name="website" value="{{ old('website', $sponsor->website ?? '') }}" placeholder="https://...">
            </div>
            <div class="form-group">
                <label>Kontak</label>
                <input type="text" name="kontak" value="{{ old('kontak', $sponsor->kontak ?? '') }}" placeholder="No. HP / Email">
            </div>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="aktif" {{ old('status', $sponsor->status ?? 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status', $sponsor->status ?? '') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="form-group">
                <label>Urutan Tampil</label>
                <input type="number" name="urutan" value="{{ old('urutan', $sponsor->urutan ?? 0) }}" min="0">
            </div>
        </div>

        <div class="form-group">
            <label>Logo / Gambar</label>
            <input type="file" name="gambar" accept="image/*">
            @if(isset($sponsor) && $sponsor->gambar)
                <p style="font-size:12px;color:var(--text-muted);margin-top:6px">File saat ini: {{ $sponsor->gambar }}</p>
            @endif
        </div>

        <div class="form-actions">
            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            <a href="{{ route('sponsor.index') }}" class="btn btn-ghost">Batal</a>
        </div>
    </form>
</div>
@endsection
