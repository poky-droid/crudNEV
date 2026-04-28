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
            'newsList' => News::with(['anggota','konten'])->latest()->get(),
        ]);
    }

    public function create()
    {
        return view('news.form', ['title' => 'Tambah News', 'anggotas' => Anggota::all()]);
    }

    public function store(Request $request)
    {
        $request->validate(['judul' => 'required|string|max:255']);

        $data = $request->only(['judul','deskripsi','anggota_id']);
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('news', 'minio');
        }

        $news = News::create($data);
        $this->syncKonten($news, $request->konten ?? []);

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

        $data = $request->only(['judul','deskripsi','anggota_id']);
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('news', 'minio');
        }

        $news->update($data);
        $this->syncKonten($news, $request->konten ?? []);

        return redirect()->route('news.index')->with('success', 'News berhasil diperbarui.');
    }

    public function destroy(News $news)
    {
        $news->delete();
        return redirect()->route('news.index')->with('success', 'News berhasil dihapus.');
    }

    private function syncKonten(News $news, array $kontens)
    {
        $existingIds = [];

        foreach ($kontens as $k) {
            $fileVal = null;
            if (isset($k['isi_file']) && is_file($k['isi_file'] ?? null)) {
                // handled below
            }

            if (!empty($k['id'])) {
                $konten = NewsKonten::find($k['id']);
                if ($konten) {
                    $update = [
                        'tipe'     => $k['tipe'],
                        'isi_text' => $k['tipe'] === 'text' ? ($k['isi_text'] ?? null) : null,
                        'urutan'   => $k['urutan'] ?? 1,
                    ];
                    $konten->update($update);
                    $existingIds[] = $konten->id;
                }
            } else {
                $newKonten = NewsKonten::create([
                    'news_id'  => $news->id,
                    'tipe'     => $k['tipe'],
                    'isi_text' => $k['tipe'] === 'text' ? ($k['isi_text'] ?? null) : null,
                    'urutan'   => $k['urutan'] ?? 1,
                ]);
                $existingIds[] = $newKonten->id;
            }
        }

        // Delete removed konten
        NewsKonten::where('news_id', $news->id)
            ->whereNotIn('id', $existingIds)
            ->delete();
    }
}
