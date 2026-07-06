@extends('layouts.app')

@section('title', 'Kategori')

@section('content')
<style>
    .kategori-page {
        color: #e5e5e5;
    }
    .kategori-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
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
    .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #7c6ef2;
        color: #fff;
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 600;
        transition: background 0.15s ease;
    }
    .btn-add:hover {
        background: #6d5ee8;
        color: #fff;
    }
    .kategori-card {
        background: #1a1a22;
        border: 1px solid #2c2c38;
        border-radius: 12px;
        overflow: hidden;
    }
    .kategori-table {
        width: 100%;
        border-collapse: collapse;
    }
    .kategori-table thead th {
        text-align: left;
        padding: 14px 20px;
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: #8a8a94;
        border-bottom: 1px solid #2c2c38;
    }
    .kategori-table tbody td {
        padding: 14px 20px;
        border-bottom: 1px solid #26262f;
        font-size: 0.92rem;
        color: #d4d4d8;
        vertical-align: middle;
    }
    .kategori-table tbody tr:last-child td {
        border-bottom: none;
    }
    .kategori-table tbody tr:hover {
        background: #202028;
    }
    .slug-code {
        background: #0f0f14;
        border: 1px solid #2c2c38;
        padding: 2px 8px;
        border-radius: 5px;
        font-size: 0.82rem;
        color: #a1a1aa;
    }
    .badge-count {
        display: inline-block;
        background: #2c2c38;
        color: #d4d4d8;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    .action-btns {
        display: flex;
        gap: 8px;
    }
    .btn-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        text-decoration: none;
        font-size: 0.85rem;
        transition: opacity 0.15s ease;
    }
    .btn-icon:hover {
        opacity: 0.85;
    }
    .btn-edit {
        background: rgba(251, 191, 36, 0.15);
        color: #fbbf24;
    }
    .btn-delete {
        background: rgba(248, 113, 113, 0.15);
        color: #f87171;
    }
    .empty-state {
        text-align: center;
        padding: 48px 20px;
        color: #6b6b76;
    }
    .alert-custom {
        border-radius: 8px;
        padding: 12px 16px;
        margin-bottom: 20px;
        font-size: 0.9rem;
    }
    .alert-success-custom {
        background: rgba(74, 222, 128, 0.12);
        border: 1px solid rgba(74, 222, 128, 0.3);
        color: #86efac;
    }
    .alert-danger-custom {
        background: rgba(248, 113, 113, 0.12);
        border: 1px solid rgba(248, 113, 113, 0.3);
        color: #fca5a5;
    }
    .pagination-wrap {
        display: flex;
        justify-content: center;
        padding: 20px;
    }
</style>

<div class="kategori-page">
    <div class="kategori-header">
        <h2><i class="fa-solid fa-tags"></i>Kategori</h2>
        <a href="{{ route('kategori.create') }}" class="btn-add">
            <i class="fa-solid fa-plus"></i> Tambah Kategori
        </a>
    </div>

    @if (session('success'))
        <div class="alert-custom alert-success-custom">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert-custom alert-danger-custom">{{ session('error') }}</div>
    @endif

    <div class="kategori-card">
        <table class="kategori-table">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Nama Kategori</th>
                    <th>Slug</th>
                    <th>Deskripsi</th>
                    <th style="width: 110px;">Jumlah Modul</th>
                    <th style="width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kategoris as $index => $kategori)
                    <tr>
                        <td>{{ $kategoris->firstItem() + $index }}</td>
                        <td>{{ $kategori->nama_kategori }}</td>
                        <td><span class="slug-code">{{ $kategori->slug }}</span></td>
                        <td>{{ Str::limit($kategori->deskripsi, 60) ?: '-' }}</td>
                        <td><span class="badge-count">{{ $kategori->moduls_count }}</span></td>
                        <td>
                            <div class="action-btns">
                                <a href="{{ route('kategori.edit', $kategori->id) }}" class="btn-icon btn-edit" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="{{ route('kategori.destroy', $kategori->id) }}" method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon btn-delete" title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">Belum ada kategori. Yuk tambah kategori baru.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($kategoris->hasPages())
            <div class="pagination-wrap">
                {{ $kategoris->links() }}
            </div>
        @endif
    </div>
</div>
@endsection