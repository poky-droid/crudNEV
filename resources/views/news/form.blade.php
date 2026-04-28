@extends('layouts.app')
@section('content')
<style>
    .konten-block {
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 16px;
        margin-bottom: 12px;
        background: var(--bg);
        position: relative;
    }
    .konten-block .remove-btn {
        position: absolute;
        top: 10px; right: 10px;
        background: rgba(247,106,106,.1);
        border: none;
        color: var(--danger);
        width: 28px; height: 28px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 12px;
    }
    .konten-block select, .konten-block input, .konten-block textarea {
        width: 100%;
        padding: 9px 12px;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 8px;
        color: var(--text);
        font-family: 'DM Sans', sans-serif;
        font-size: 13.5px;
        margin-top: 8px;
        outline: none;
    }
    .konten-block select:focus, .konten-block input:focus, .konten-block textarea:focus {
        border-color: var(--accent);
    }
    .konten-block label { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); }
</style>

<div class="card" style="max-width:780px">
    <div class="card-header">
        <h3>{{ isset($news) ? 'Edit News' : 'Tambah News' }}</h3>
    </div>
    <form method="POST" action="{{ isset($news) ? route('news.update', $news) : route('news.store') }}" enctype="multipart/form-data">
        @csrf
        @if(isset($news)) @method('PUT') @endif

        <div class="form-group">
            <label>Judul</label>
            <input type="text" name="judul" value="{{ old('judul', $news->judul ?? '') }}" required>
        </div>
        <div class="form-group">
            <label>Deskripsi Singkat</label>
            <textarea name="deskripsi" rows="3">{{ old('deskripsi', $news->deskripsi ?? '') }}</textarea>
        </div>
        <div class="form-group">
            <label>Gambar Utama</label>
            <input type="file" name="gambar" accept="image/*">
        </div>
        <div class="form-group">
            <label>Penulis</label>
            <select name="anggota_id">
                <option value="">— Pilih Penulis —</option>
                @foreach($anggotas as $a)
                    <option value="{{ $a->id }}" {{ old('anggota_id', $news->anggota_id ?? '') == $a->id ? 'selected' : '' }}>{{ $a->nama }}</option>
                @endforeach
            </select>
        </div>

        <div style="border-top:1px solid var(--border);padding-top:20px;margin-top:8px;margin-bottom:16px">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px">
                <p style="font-size:13px;font-weight:500">Blok Konten</p>
                <button type="button" onclick="addBlock()" class="btn btn-ghost btn-sm"><i class="fa-solid fa-plus"></i> Tambah Blok</button>
            </div>
            <div id="konten-list">
                @if(isset($news) && $news->konten->count())
                    @foreach($news->konten->sortBy('urutan') as $idx => $k)
                    <div class="konten-block" id="block-{{ $idx }}">
                        <button type="button" class="remove-btn" onclick="removeBlock(this)"><i class="fa-solid fa-xmark"></i></button>
                        <label>Tipe</label>
                        <select name="konten[{{ $idx }}][tipe]" onchange="toggleField(this)">
                            <option value="text" {{ $k->tipe=='text'?'selected':'' }}>Teks</option>
                            <option value="image" {{ $k->tipe=='image'?'selected':'' }}>Gambar</option>
                            <option value="file" {{ $k->tipe=='file'?'selected':'' }}>File</option>
                        </select>
                        <input type="hidden" name="konten[{{ $idx }}][id]" value="{{ $k->id }}">
                        <input type="hidden" name="konten[{{ $idx }}][urutan]" value="{{ $k->urutan }}">
                        <div class="field-text" style="{{ $k->tipe!='text'?'display:none':'' }}">
                            <label>Isi Teks</label>
                            <textarea name="konten[{{ $idx }}][isi_text]" rows="4">{{ $k->isi_text }}</textarea>
                        </div>
                        <div class="field-file" style="{{ $k->tipe=='text'?'display:none':'' }}">
                            <label>Upload File/Gambar</label>
                            <input type="file" name="konten[{{ $idx }}][isi_file]">
                            @if($k->isi_file)<p style="font-size:11px;color:var(--text-muted);margin-top:4px">Saat ini: {{ $k->isi_file }}</p>@endif
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="form-actions">
            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            <a href="{{ route('news.index') }}" class="btn btn-ghost">Batal</a>
        </div>
    </form>
</div>

<script>
let blockIdx = {{ isset($news) ? $news->konten->count() : 0 }};

function addBlock() {
    const list = document.getElementById('konten-list');
    const div = document.createElement('div');
    div.className = 'konten-block';
    div.id = 'block-' + blockIdx;
    div.innerHTML = `
        <button type="button" class="remove-btn" onclick="removeBlock(this)"><i class="fa-solid fa-xmark"></i></button>
        <label>Tipe</label>
        <select name="konten[${blockIdx}][tipe]" onchange="toggleField(this)">
            <option value="text">Teks</option>
            <option value="image">Gambar</option>
            <option value="file">File</option>
        </select>
        <input type="hidden" name="konten[${blockIdx}][urutan]" value="${blockIdx+1}">
        <div class="field-text">
            <label>Isi Teks</label>
            <textarea name="konten[${blockIdx}][isi_text]" rows="4"></textarea>
        </div>
        <div class="field-file" style="display:none">
            <label>Upload File/Gambar</label>
            <input type="file" name="konten[${blockIdx}][isi_file]">
        </div>`;
    list.appendChild(div);
    blockIdx++;
}

function removeBlock(btn) {
    btn.closest('.konten-block').remove();
}

function toggleField(select) {
    const block = select.closest('.konten-block');
    const isText = select.value === 'text';
    block.querySelector('.field-text').style.display = isText ? '' : 'none';
    block.querySelector('.field-file').style.display = isText ? 'none' : '';
}
</script>
@endsection
