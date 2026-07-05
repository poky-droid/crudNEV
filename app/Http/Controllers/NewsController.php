<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsKonten;
use App\Models\Anggota;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        return view('news.index', [
            'title'    => 'News',
            'newsList' => News::with(['anggota', 'konten'])->latest()->get(),
        ]);
    }

    public function create()
    {
        return view('news.form', [
            'title'    => 'Tambah News',
            'anggotas' => Anggota::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(['judul' => 'required|string|max:255']);

        $data = $request->only(['judul', 'deskripsi', 'anggota_id']);
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('news', 'minio');
        }

        $news = News::create($data);
        $this->syncKonten($news, $request->konten ?? [], $request);

        return redirect()->route('news.index')->with('success', 'News berhasil ditambahkan.');
    }

    public function edit(News $news)
    {
        return view('news.form', [
            'title'    => 'Edit News',
            'news'     => $news->load('konten'),
            'anggotas' => Anggota::all(),
        ]);
    }

    public function update(Request $request, News $news)
    {
        $request->validate(['judul' => 'required|string|max:255']);

        $data = $request->only(['judul', 'deskripsi', 'anggota_id']);
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('news', 'minio');
        }

        $news->update($data);
        $this->syncKonten($news, $request->konten ?? [], $request);

        return redirect()->route('news.index')->with('success', 'News berhasil diperbarui.');
    }

    public function destroy(News $news)
    {
        $news->delete();
        return redirect()->route('news.index')->with('success', 'News berhasil dihapus.');
    }

    /**
     * Sync konten blocks (create/update/delete) untuk sebuah News,
     * termasuk handle file upload per-blok (image/file).
     */
    private function syncKonten(News $news, array $kontens, Request $request)
    {
        $existingIds = [];

        // Ambil semua file yang di-upload untuk field konten[idx][isi_file]
        $fileInputs = $request->file('konten', []);

        foreach ($kontens as $idx => $k) {
            $isText = ($k['tipe'] ?? 'text') === 'text';

            $payload = [
                'tipe'     => $k['tipe'],
                'isi_text' => $isText ? ($k['isi_text'] ?? null) : null,
                'urutan'   => $k['urutan'] ?? ($idx + 1),
            ];

            // Cek apakah ada file baru untuk index ini
            $uploadedFile = $fileInputs[$idx]['isi_file'] ?? null;
            if (!$isText && $uploadedFile) {
                $payload['isi_file'] = $uploadedFile->store('news/konten', 'minio');
            }

            if (!empty($k['id'])) {
                // ---- Update blok yang sudah ada ----
                $konten = NewsKonten::find($k['id']);
                if ($konten) {
                    // Kalau bukan text dan gak ada file baru, pertahankan file lama
                    if (!$isText && !isset($payload['isi_file'])) {
                        $payload['isi_file'] = $konten->isi_file;
                    }
                    // Kalau tipe di-switch ke text, kosongin isi_file lama
                    if ($isText) {
                        $payload['isi_file'] = null;
                    }

                    $konten->update($payload);
                    $existingIds[] = $konten->id;
                }
            } else {
                // ---- Buat blok baru ----
                $newKonten = NewsKonten::create(array_merge($payload, [
                    'news_id' => $news->id,
                ]));
                $existingIds[] = $newKonten->id;
            }
        }

        // Hapus blok yang sudah tidak ada di request (di-remove dari form)
        NewsKonten::where('news_id', $news->id)
            ->whereNotIn('id', $existingIds)
            ->delete();
    }
}