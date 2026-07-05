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
        margin-bottom: 14px;
        display: flex;
        gap: 8px;
        align-items: center;
    }
    .preview-img {
        width: 100%;
        max-height: 260px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 14px;
        display: block;
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
        <div class="card">
            <div class="card-header">
                <h3>{{ isset($news) ? 'Edit News' : 'Tambah News' }}</h3>
            </div>
            <form id="news-form" method="POST" action="{{ isset($news) ? route('news.update', $news) : route('news.store') }}" enctype="multipart/form-data">
                @csrf
                @if(isset($news)) @method('PUT') @endif

                <div class="form-group">
                    <label>Judul</label>
                    <input type="text" name="judul" id="input-judul" value="{{ old('judul', $news->judul ?? '') }}" required>
                </div>

                <div class="form-group">
                    <label>Deskripsi Singkat</label>
                    <textarea name="deskripsi" id="input-deskripsi" rows="3">{{ old('deskripsi', $news->deskripsi ?? '') }}</textarea>
                </div>
                <div class="form-group">
                    <label>Gambar Utama</label>
                    <input type="file" name="gambar" id="input-gambar" accept="image/*">
                    @if(isset($news) && $news->gambar)
                        <img
                            src="{{ Storage::disk('s3')->url($news->gambar) }}"
                            alt="{{ $news->judul }}"
                            style="width:150px;height:150px;object-fit:cover;"
                        >
                        <p style="font-size:12px;color:var(--text-muted);margin-top:6px">
                            File saat ini: {{ $news->gambar }}
                        </p>
                    @endif
                </div>
                <div class="form-group">
                    <label>Penulis</label>
                    <select name="anggota_id" id="input-penulis">
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
                                    <div class="quill-editor" id="editor-{{ $idx }}" style="margin-top:8px;height:180px">
                                        {!! $k->isi_text !!}
                                    </div>
                                    <input type="hidden" name="konten[{{ $idx }}][isi_text]" id="input-editor-{{ $idx }}">
                                </div>

                                <div class="field-file"
                                    style="{{ $k->tipe=='text'?'display:none':'' }}"
                                    data-existing-url="{{ $k->isi_file ? Storage::disk('s3')->url($k->isi_file) : '' }}">
                                    <label>Upload File/Gambar</label>
                                    <input type="file" name="konten[{{ $idx }}][isi_file]">
                                    @if(isset($news) && $k->isi_file)
                                        <img
                                            src="{{ Storage::disk('s3')->url($k->isi_file) }}"
                                            alt="{{ $news->judul }}"
                                            style="width:150px;height:150px;object-fit:cover;"
                                        >
                                    @endif
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
let blockIdx = {{ isset($news) ? $news->konten->count() : 0 }};
const quillEditors = {};

// daftar nama penulis buat lookup cepat di preview
const penulisMap = {
    @foreach($anggotas as $a)
        "{{ $a->id }}": "{{ $a->nama }}",
    @endforeach
};

const existingGambarUrl = @json(isset($news) && $news->gambar ? Storage::disk('s3')->url($news->gambar) : null);

function initQuill(id) {
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
            <div class="quill-editor" id="editor-${blockIdx}" style="margin-top:8px;height:180px"></div>
            <input type="hidden" name="konten[${blockIdx}][isi_text]" id="input-editor-${blockIdx}">
        </div>

        <div class="field-file" style="display:none">
            <label>Upload File/Gambar</label>
            <input type="file" name="konten[${blockIdx}][isi_file]">
        </div>`;
    list.appendChild(div);
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
    const judul = document.getElementById('input-judul').value.trim();
    const deskripsi = document.getElementById('input-deskripsi').value.trim();
    const penulisId = document.getElementById('input-penulis').value;
    const penulisNama = penulisMap[penulisId] || null;
    const gambarInput = document.getElementById('input-gambar');
    const gambarFile = gambarInput.files[0];

    let gambarSrc = existingGambarUrl;
    if (gambarFile) {
        gambarSrc = await new Promise(res => readFileAsDataUrl(gambarFile, res));
    }

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
                // Ada file baru dipilih -> preview file itu
                readFileAsDataUrl(file, dataUrl => {
                    resolve({ tipe, isi: dataUrl, filename: file.name });
                });
            } else if (existingUrl) {
                // Belum ganti file -> pakai URL existing dari DB
                resolve({ tipe, isi: existingUrl, filename: existingFilename });
            } else {
                // Belum ada file sama sekali
                resolve({ tipe, isi: null, filename: existingFilename });
            }
        }
    });
});

    const blockData = await Promise.all(blockDataPromises);

    const container = document.getElementById('preview-content');

    if (!judul && !deskripsi && !gambarSrc && blockData.length === 0) {
        container.innerHTML = `<div class="preview-empty"><i class="fa-solid fa-file-lines" style="font-size:24px;display:block;margin-bottom:8px"></i>Isi form di kiri untuk lihat preview</div>`;
        return;
    }

    let html = '';
    html += `<h1 class="preview-title">${judul ? escapeHtml(judul) : '<span style="color:var(--text-muted)">Judul belum diisi</span>'}</h1>`;
    html += `<div class="preview-meta">
        <i class="fa-solid fa-user"></i> ${penulisNama ? escapeHtml(penulisNama) : 'Penulis belum dipilih'}
        <span>•</span>
        <i class="fa-solid fa-calendar"></i> ${new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}
    </div>`;

    if (gambarSrc) {
        html += `<img class="preview-img" src="${gambarSrc}" alt="Gambar utama">`;
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

// listen ke semua perubahan input/textarea/select di dalam form (event delegation)
document.getElementById('news-form').addEventListener('input', updatePreview);
document.getElementById('news-form').addEventListener('change', updatePreview);

// init Quill buat blok yang udah ada (mode edit)
document.addEventListener('DOMContentLoaded', function() {
    const editors = document.querySelectorAll('.quill-editor');
    editors.forEach((editor) => {
        const id = editor.id.replace('editor-', '');
        initQuill(id);
    });
    updatePreview();
});
</script>
@endsection