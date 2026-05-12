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
        position: absolute; top: 10px; right: 10px;
        background: rgba(247,106,106,.1); border: none; color: var(--danger);
        width: 28px; height: 28px; border-radius: 6px; cursor: pointer; font-size: 12px;
    }
    .konten-block select, .konten-block input, .konten-block textarea {
        width: 100%; padding: 9px 12px;
        background: var(--surface); border: 1px solid var(--border);
        border-radius: 8px; color: var(--text);
        font-family: 'DM Sans', sans-serif; font-size: 13.5px; margin-top: 8px; outline: none;
    }
    .anggota-check-list {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 8px;
    }
    .anggota-check-item {
        display: flex; align-items: center; gap: 8px;
        padding: 8px 12px; border: 1px solid var(--border); border-radius: 8px;
        font-size: 13px; cursor: pointer; transition: all .12s;
    }
    .anggota-check-item:has(input:checked) {
        border-color: var(--accent); background: rgba(124,106,247,.08);
    }
    .anggota-check-item input { accent-color: var(--accent); }
</style>

<div class="card" style="max-width:780px">
    <div class="card-header">
        <h3>{{ isset($modul) ? 'Edit Modul' : 'Tambah Modul' }}</h3>
    </div>
    <form method="POST" action="{{ isset($modul) ? route('modul.update', $modul) : route('modul.store') }}" enctype="multipart/form-data">
        @csrf
        @if(isset($modul)) @method('PUT') @endif

        <div class="form-group">
            <label>Nama Modul</label>
            <input type="text" name="nama_modul" value="{{ old('nama_modul', $modul->nama_modul ?? '') }}" required>
        </div>
        <div class="form-group">
            <label>Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $modul->slug ?? '') }}" required>
        </div>
        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi" rows="3">{{ old('deskripsi', $modul->deskripsi ?? '') }}</textarea>
        </div>
        <div class="form-group">
            <label>Pembuat <span style="color:red">*</span></label>
            <div class="anggota-check-list" style="margin-top:10px">
                @foreach($anggotas as $a)
                <label class="anggota-check-item">
                    <input type="checkbox" name="creators[]" value="{{ $a->id }}"
                        {{ isset($modul) && $modul->creators->contains($a->id) ? 'checked' : '' }}>
                    {{ $a->nama }}
                </label>
                @endforeach
            </div>
            @error('creators')
                <span style="color: var(--danger); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
            @enderror
        </div>

        <div style="border-top:1px solid var(--border);padding-top:20px;margin-top:8px;margin-bottom:16px">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px">
                <p style="font-size:13px;font-weight:500">Blok Konten</p>
                <button type="button" onclick="addBlock()" class="btn btn-ghost btn-sm"><i class="fa-solid fa-plus"></i> Tambah Blok</button>
            </div>
            <div id="konten-list">
                @if(isset($modul) && $modul->konten->count())
                    @foreach($modul->konten->sortBy('urutan') as $idx => $k)
                    <div class="konten-block">
                        <button type="button" class="remove-btn" onclick="removeBlock(this)"><i class="fa-solid fa-xmark"></i></button>
                        <label style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted)">Tipe</label>
                        <select name="konten[{{ $idx }}][tipe]" onchange="toggleField(this)">
                            <option value="text" {{ $k->tipe=='text'?'selected':'' }}>Teks</option>
                            <option value="image" {{ $k->tipe=='image'?'selected':'' }}>Gambar</option>
                            <option value="file" {{ $k->tipe=='file'?'selected':'' }}>File</option>
                        </select>
                        <input type="hidden" name="konten[{{ $idx }}][id]" value="{{ $k->id }}">
                        <input type="hidden" name="konten[{{ $idx }}][urutan]" value="{{ $k->urutan }}">
                        <div class="field-text" style="{{ $k->tipe!='text'?'display:none':'' }}">
                            <div class="quill-editor" id="editor-{{ $idx }}" style="margin-top:8px;height:180px">
                                {!! $k->isi_text !!}
                            </div>

                            <input type="hidden"
                                name="konten[{{ $idx }}][isi_text]"
                                id="input-editor-{{ $idx }}">
                        </div>
                        <div class="field-file" style="{{ $k->tipe=='text'?'display:none':'' }}">
                            <input type="file" name="konten[{{ $idx }}][isi_file]" style="margin-top:8px">
                            @if($k->isi_file)<p style="font-size:11px;color:var(--text-muted);margin-top:4px">Saat ini: {{ $k->isi_file }}</p>@endif
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="form-actions">
            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            <a href="{{ route('modul.index') }}" class="btn btn-ghost">Batal</a>
        </div>
    </form>
</div>

<script>
let blockIdx = {{ isset($modul) ? $modul->konten->count() : 0 }};

function addBlock() {
    const div = document.createElement('div');
    div.className = 'konten-block';
    div.innerHTML = `
        <button type="button" class="remove-btn" onclick="removeBlock(this)">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <label style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted)">
            Tipe
        </label>

        <select name="konten[${blockIdx}][tipe]" onchange="toggleField(this)">
            <option value="text">Teks</option>
            <option value="image">Gambar</option>
            <option value="file">File</option>
        </select>

        <input type="hidden" name="konten[${blockIdx}][urutan]" value="${blockIdx+1}">

        <div class="field-text">
            <div class="quill-editor"
                 id="editor-${blockIdx}"
                 style="margin-top:8px;height:180px"></div>

            <input type="hidden"
                   name="konten[${blockIdx}][isi_text]"
                   id="input-editor-${blockIdx}">
        </div>

        <div class="field-file" style="display:none">
            <input type="file"
                   name="konten[${blockIdx}][isi_file]"
                   style="margin-top:8px">
        </div>
    `;
    document.getElementById('konten-list').appendChild(div);
    initQuill(blockIdx);
    blockIdx++;
}

function removeBlock(btn) { btn.closest('.konten-block').remove(); }

function toggleField(select) {
    const block = select.closest('.konten-block');
    const isText = select.value === 'text';
    block.querySelector('.field-text').style.display = isText ? '' : 'none';
    block.querySelector('.field-file').style.display = isText ? 'none' : '';
}
</script>

<script>
const quillEditors = {};

function initQuill(id) {
    // Check if editor element exists
    const editorElement = document.getElementById(`editor-${id}`);
    if (!editorElement) return;
    
    const quill = new Quill(`#editor-${id}`, {
        theme: 'snow',
        placeholder: 'Tulis konten...',
        modules: {
            toolbar: [
                [{ header: [1, 2, 3, false] }],
                ['bold', 'italic', 'underline'],
                ['blockquote', 'code-block'],
                [{ list: 'ordered' }, { list: 'bullet' }],
                ['link', 'image'],
                ['clean']
            ]
        }
    });

    const hiddenInput = document.getElementById(`input-editor-${id}`);
    if (!hiddenInput) return;

    hiddenInput.value = quill.root.innerHTML;

    quill.on('text-change', function () {
        hiddenInput.value = quill.root.innerHTML;
    });

    quillEditors[id] = quill;
}

// Initialize Quill editors on page load
document.addEventListener('DOMContentLoaded', function() {
    const editors = document.querySelectorAll('.quill-editor');
    editors.forEach((editor) => {
        const id = editor.id.replace('editor-', '');
        initQuill(id);
    });
});
</script>
@endsection