@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
<style>
    .kategori-page {
        color: #e5e5e5;
    }
    .kategori-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }
    .kategori-header h2 {
        font-size: 1.6rem;
        font-weight: 700;
        color: #fff;
        margin: 0;
    }
    .kategori-header h2 i {
        margin-right: 10px;
        color: #8b7ff0;
    }
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #8b7ff0;
        text-decoration: none;
        font-size: 0.9rem;
        margin-bottom: 20px;
    }
    .back-link:hover {
        color: #a89cff;
        text-decoration: underline;
    }
    .kategori-card {
        background: #1a1a22;
        border: 1px solid #2c2c38;
        border-radius: 12px;
        padding: 28px;
        max-width: 640px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        font-size: 0.9rem;
        font-weight: 600;
        color: #d4d4d8;
        margin-bottom: 8px;
    }
    .form-group label .required {
        color: #f87171;
    }
    .form-control-custom {
        width: 100%;
        background: #0f0f14;
        border: 1px solid #33333f;
        border-radius: 8px;
        padding: 10px 14px;
        color: #f4f4f5;
        font-size: 0.95rem;
        transition: border-color 0.15s ease;
    }
    .form-control-custom::placeholder {
        color: #6b6b76;
    }
    .form-control-custom:focus {
        outline: none;
        border-color: #7c6ef2;
        box-shadow: 0 0 0 3px rgba(124, 110, 242, 0.2);
    }
    .form-control-custom:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    textarea.form-control-custom {
        resize: vertical;
        min-height: 110px;
        font-family: inherit;
    }
    .form-control-custom.is-invalid {
        border-color: #f87171;
    }
    .invalid-feedback-custom {
        color: #f87171;
        font-size: 0.82rem;
        margin-top: 6px;
    }
    .form-hint {
        color: #8a8a94;
        font-size: 0.82rem;
        margin-top: 6px;
    }
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 28px;
    }
    .btn-cancel {
        display: inline-flex;
        align-items: center;
        padding: 10px 20px;
        color: #a1a1aa;
        text-decoration: none;
        font-size: 0.9rem;
        border-radius: 8px;
        transition: background 0.15s ease;
    }
    .btn-cancel:hover {
        background: #232330;
        color: #d4d4d8;
    }
    .btn-save {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #7c6ef2;
        color: #fff;
        border: none;
        padding: 10px 22px;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.15s ease;
    }
    .btn-save:hover {
        background: #6d5ee8;
    }
    .alert-custom {
        border-radius: 8px;
        padding: 12px 16px;
        margin-bottom: 20px;
        font-size: 0.9rem;
    }
    .alert-danger-custom {
        background: rgba(248, 113, 113, 0.12);
        border: 1px solid rgba(248, 113, 113, 0.3);
        color: #fca5a5;
    }
</style>

<div class="kategori-page">
    <a href="{{ route('kategori.index') }}" class="back-link">
        <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>

    <div class="kategori-header">
        <h2><i class="fa-solid fa-tags"></i>Edit Kategori</h2>
    </div>

    @if ($errors->any())
        <div class="alert-custom alert-danger-custom">
            Terdapat kesalahan pada input, silakan periksa kembali form di bawah.
        </div>
    @endif

    <div class="kategori-card">
        <form action="{{ route('kategori.update', $kategori->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nama_kategori">Nama Kategori <span class="required">*</span></label>
                <input type="text" name="nama_kategori" id="nama_kategori"
                       class="form-control-custom @error('nama_kategori') is-invalid @enderror"
                       value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required>
                @error('nama_kategori')
                    <div class="invalid-feedback-custom">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" rows="4"
                          class="form-control-custom @error('deskripsi') is-invalid @enderror"
                          placeholder="Deskripsi singkat mengenai kategori ini (opsional)">{{ old('deskripsi', $kategori->deskripsi) }}</textarea>
                @error('deskripsi')
                    <div class="invalid-feedback-custom">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Slug saat ini</label>
                <input type="text" class="form-control-custom" value="{{ $kategori->slug }}" disabled>
                <div class="form-hint">Slug akan diperbarui otomatis mengikuti nama kategori.</div>
            </div>

            <div class="form-actions">
                <a href="{{ route('kategori.index') }}" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-save">
                    <i class="fa-solid fa-save"></i> Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection