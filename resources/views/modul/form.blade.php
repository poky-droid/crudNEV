@extends('layouts.app')
@section('content')

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<style>
    .form-preview-wrapper {
        display: flex;
        gap: 20px;
        align-items: flex-start;
    }
    .form-col { flex: 1 1 55%; min-width: 0; }
    .preview-col {
        flex: 1 1 45%;
        min-width: 0;
        position: sticky;
        top: 20px;
    }
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

    /* checklist khusus kategori (warna hijau biar beda dari pembuat) */
    .kategori-check-item:has(input:checked) {
        border-color: #4ade80; background: rgba(74,222,128,.08);
    }
    .kategori-check-item input { accent-color: #4ade80; }

    /* Quill dark-mode friendly adjustments */
    .quill-editor {
        background: var(--surface);
        border-radius: 8px;
        overflow: hidden;
    }
    .ql-toolbar.ql-snow {
        border-color: var(--border) !important;
        background: var(--bg);
        border-radius: 8px 8px 0 0;
    }
    .ql-container.ql-snow {
        border-color: var(--border) !important;
        border-radius: 0 0 8px 8px;
        font-family: 'DM Sans', sans-serif;
        font-size: 13.5px;
    }
    .ql-editor { color: var(--text); }
    .ql-snow .ql-stroke { stroke: var(--text-muted); }
    .ql-snow .ql-fill { fill: var(--text-muted); }
    .ql-snow .ql-picker { color: var(--text-muted); }

    /* --- Preview styling --- */
    .preview-card {
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--surface);
        padding: 20px;
        max-height: calc(100vh - 40px);
        overflow-y: auto;
    }
    .preview-badge {
        display: inline-block;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--accent);
        background: rgba(90,120,255,.1);
        padding: 3px 8px;
        border-radius: 5px;
        margin-bottom: 10px;
    }
    .preview-title {
        font-size: 22px;
        font-weight: 700;
        line-height: 1.3;
        margin: 0 0 8px;
        color: var(--text);
        word-break: break-word;
    }
    .preview-meta {
        font-size: 12px;
        color: var(--text-muted);
        margin-bottom: 4px;
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
    }
    .preview-slug {
        font-size: 11px;
        color: var(--text-muted);
        margin-bottom: 14px;
        font-family: monospace;
    }
    .preview-creators {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 14px;
    }
    .preview-creator-chip {
        font-size: 11px;
        background: rgba(124,106,247,.1);
        color: var(--accent);
        padding: 3px 8px;
        border-radius: 4px;
    }
    .preview-kategori-chip {
        font-size: 11px;
        background: rgba(74,222,128,.1);
        color: #4ade80;
        padding: 3px 8px;
        border-radius: 4px;
    }
    .preview-desc {
        font-size: 14px;
        color: var(--text-muted);
        font-style: italic;
        border-left: 3px solid var(--accent);
        padding-left: 10px;
        margin-bottom: 18px;
        line-height: 1.5;
    }
    .preview-block { margin-bottom: 16px; }
    .preview-block .preview-rich-text {
        font-size: 14.5px;
        line-height: 1.7;
        color: var(--text);
    }
    .preview-block .preview-rich-text p { margin: 0 0 10px; }
    .preview-block .preview-rich-text img { max-width: 100%; border-radius: 8px; }
    .preview-block .preview-rich-text blockquote {
        border-left: 3px solid var(--accent);
        padding-left: 12px;
        color: var(--text-muted);
        margin: 10px 0;
    }
    .preview-block .preview-rich-text pre {
        background: var(--bg);
        padding: 10px;
        border-radius: 6px;
        overflow-x: auto;
        font-size: 12.5px;
    }
    .preview-block img.preview-standalone-img {
        width: 100%;
        border-radius: 8px;
        display: block;
    }
    .preview-file-chip {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: var(--text);
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 10px 12px;
    }
    .preview-empty {
        text-align: center;
        color: var(--text-muted);
        font-size: 13px;
        padding: 40px 10px;
    }
</style>

<div class="form-preview-wrapper">
    <!-- ============ FORM ============ -->
    <div class="form-col">
        <div class="card" style="max-width:780px">
            <div class="card-header">
                <h3>{{ isset($modul) ? 'Edit Modul' : 'Tambah Modul' }}</h3>
            </div>
            <form id="modul-form" method="POST" action="{{ isset($modul) ? route('modul.update', $modul) : route('modul.store') }}" enctype="multipart/form-data">
                @csrf
                @if(isset($modul)) @method('PUT') @endif

                <div class="form-group">
                    <label>Nama Modul</label>
                    <input type="text" name="nama_modul" id="input-nama-modul" value="{{ old('nama_modul', $modul->nama_modul ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label>Slug</label>
                    <input type="text" name="slug" id="input-slug" value="{{ old('slug', $modul->slug ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" id="input-deskripsi" rows="3">{{ old('deskripsi', $modul->deskripsi ?? '') }}</textarea>
                </div>
                <div class="form-group">
                    <label>Pembuat <span style="color:red">*</span></label>
                    <div class="anggota-check-list" id="input-creators" style="margin-top:10px">
                        @foreach($anggotas as $a)
                        <label class="anggota-check-item">
                            <input type="checkbox" name="creators[]" value="{{ $a->id }}" data-nama="{{ $a->nama }}"
                                {{ isset($modul) && $modul->creators->contains($a->id) ? 'checked' : '' }}>
                            {{ $a->nama }}
                        </label>
                        @endforeach
                    </div>
                    @error('creators')
                        <span style="color: var(--danger); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Kategori <span style="color:red">*</span></label>
                    <div class="anggota-check-list" id="input-kategoris" style="margin-top:10px">
                        @foreach($kategoris as $kat)
                        <label class="anggota-check-item kategori-check-item">
                            <input type="checkbox" name="kategoris[]" value="{{ $kat->id }}" data-nama="{{ $kat->nama_kategori }}"
                                {{ isset($modul) && $modul->kategoris->contains($kat->id) ? 'checked' : '' }}>
                            {{ $kat->nama_kategori }}
                        </label>
                        @endforeach
                    </div>
                    @error('kategoris')
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
                            <div class="konten-block" id="block-{{ $idx }}">
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
                                <div class="field-file" style="{{ $k->tipe=='text'?'display:none':'' }}"
                                     data-existing-url="{{ $k->isi_file ? Storage::disk('s3')->url($k->isi_file) : '' }}">
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
    </div>

    <!-- ============ LIVE PREVIEW ============ -->
    <div class="preview-col">
        <div class="preview-card">
            <span class="preview-badge"><i class="fa-solid fa-eye"></i> Live Preview</span>
            <div id="preview-content">
                <!-- diisi JS -->
            </div>
        </div>
    </div>
</div>

<script>
let blockIdx = {{ isset($modul) ? $modul->konten->count() : 0 }};
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
        updatePreview();
    });

    quillEditors[id] = quill;
}

function addBlock() {
    const div = document.createElement('div');
    div.className = 'konten-block';
    div.id = 'block-' + blockIdx;
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

        <div class="field-file" style="display:none" data-existing-url="">
            <input type="file"
                   name="konten[${blockIdx}][isi_file]"
                   style="margin-top:8px">
        </div>
    `;
    document.getElementById('konten-list').appendChild(div);
    initQuill(blockIdx);
    blockIdx++;
    updatePreview();
}

function removeBlock(btn) {
    const block = btn.closest('.konten-block');
    const editorDiv = block.querySelector('.quill-editor');
    if (editorDiv) {
        const id = editorDiv.id.replace('editor-', '');
        delete quillEditors[id];
    }
    block.remove();
    updatePreview();
}

function toggleField(select) {
    const block = select.closest('.konten-block');
    const isText = select.value === 'text';
    block.querySelector('.field-text').style.display = isText ? '' : 'none';
    block.querySelector('.field-file').style.display = isText ? 'none' : '';
    updatePreview();
}

// Initialize Quill editors on page load
document.addEventListener('DOMContentLoaded', function() {
    const editors = document.querySelectorAll('.quill-editor');
    editors.forEach((editor) => {
        const id = editor.id.replace('editor-', '');
        initQuill(id);
    });
    updatePreview();
});

// ---------- LIVE PREVIEW LOGIC ----------

function escapeHtml(str) {
    const div = document.createElement('div');
    div.textContent = str ?? '';
    return div.innerHTML;
}

function readFileAsDataUrl(file, cb) {
    if (!file) return cb(null);
    const reader = new FileReader();
    reader.onload = e => cb(e.target.result);
    reader.readAsDataURL(file);
}

async function updatePreview() {
    const namaModul = document.getElementById('input-nama-modul').value.trim();
    const slug = document.getElementById('input-slug').value.trim();
    const deskripsi = document.getElementById('input-deskripsi').value.trim();

    const creatorNames = Array.from(
        document.querySelectorAll('#input-creators input[type="checkbox"]:checked')
    ).map(cb => cb.dataset.nama);

    const kategoriNames = Array.from(
        document.querySelectorAll('#input-kategoris input[type="checkbox"]:checked')
    ).map(cb => cb.dataset.nama);

    // kumpulin data blok konten secara berurutan
    const blocks = Array.from(document.querySelectorAll('#konten-list .konten-block'));
    const blockDataPromises = blocks.map(block => {
        return new Promise(resolve => {
            const tipe = block.querySelector('select[name*="[tipe]"]').value;
            if (tipe === 'text') {
                const hiddenInput = block.querySelector('input[type="hidden"][name*="[isi_text]"]');
                const isiHtml = hiddenInput ? hiddenInput.value : '';
                const isEmpty = !isiHtml || isiHtml.replace(/<[^>]*>/g, '').trim() === '';
                resolve({ tipe, isi: isEmpty ? null : isiHtml });
            } else {
                const fileFieldDiv = block.querySelector('.field-file');
                const fileInput = fileFieldDiv.querySelector('input[type="file"][name*="[isi_file]"]');
                const file = fileInput.files[0];
                const existingUrl = fileFieldDiv.dataset.existingUrl || null;
                const existingText = fileFieldDiv.querySelector('p');
                const existingFilename = existingText ? existingText.textContent.replace('Saat ini: ', '') : null;

                if (file) {
                    readFileAsDataUrl(file, dataUrl => {
                        resolve({ tipe, isi: dataUrl, filename: file.name });
                    });
                } else if (existingUrl) {
                    resolve({ tipe, isi: existingUrl, filename: existingFilename });
                } else {
                    resolve({ tipe, isi: null, filename: existingFilename });
                }
            }
        });
    });

    const blockData = await Promise.all(blockDataPromises);

    const container = document.getElementById('preview-content');

    if (!namaModul && !slug && !deskripsi && creatorNames.length === 0 && kategoriNames.length === 0 && blockData.length === 0) {
        container.innerHTML = `<div class="preview-empty"><i class="fa-solid fa-file-lines" style="font-size:24px;display:block;margin-bottom:8px"></i>Isi form di kiri untuk lihat preview</div>`;
        return;
    }

    let html = '';
    html += `<h1 class="preview-title">${namaModul ? escapeHtml(namaModul) : '<span style="color:var(--text-muted)">Nama modul belum diisi</span>'}</h1>`;

    if (slug) {
        html += `<div class="preview-slug"><i class="fa-solid fa-link"></i> /${escapeHtml(slug)}</div>`;
    }

    if (kategoriNames.length) {
        html += `<div class="preview-creators">`;
        kategoriNames.forEach(nama => {
            html += `<span class="preview-kategori-chip">${escapeHtml(nama)}</span>`;
        });
        html += `</div>`;
    } else {
        html += `<div class="preview-meta" style="margin-bottom:14px"><i class="fa-solid fa-tags"></i> Kategori belum dipilih</div>`;
    }

    if (creatorNames.length) {
        html += `<div class="preview-creators">`;
        creatorNames.forEach(nama => {
            html += `<span class="preview-creator-chip">${escapeHtml(nama)}</span>`;
        });
        html += `</div>`;
    } else {
        html += `<div class="preview-meta" style="margin-bottom:14px"><i class="fa-solid fa-user"></i> Pembuat belum dipilih</div>`;
    }

    if (deskripsi) {
        html += `<div class="preview-desc">${escapeHtml(deskripsi)}</div>`;
    }

    blockData.forEach(b => {
        html += `<div class="preview-block">`;
        if (b.tipe === 'text') {
            html += b.isi
                ? `<div class="preview-rich-text">${b.isi}</div>`
                : `<p style="color:var(--text-muted)">(blok teks kosong)</p>`;
        } else if (b.tipe === 'image') {
            html += b.isi
                ? `<img class="preview-standalone-img" src="${b.isi}" alt="konten">`
                : `<div class="preview-file-chip"><i class="fa-solid fa-image"></i> ${b.filename ? escapeHtml(b.filename) : 'Belum ada gambar dipilih'}</div>`;
        } else if (b.tipe === 'file') {
            html += `<div class="preview-file-chip"><i class="fa-solid fa-paperclip"></i> ${b.filename ? escapeHtml(b.filename) : 'Belum ada file dipilih'}</div>`;
        }
        html += `</div>`;
    });

    container.innerHTML = html;
}

// listen ke semua perubahan input/textarea/select/checkbox di dalam form
document.getElementById('modul-form').addEventListener('input', updatePreview);
document.getElementById('modul-form').addEventListener('change', updatePreview);
</script>
@endsection