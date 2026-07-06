<?php

namespace App\Http\Controllers;

use App\Models\Modul;
use App\Models\ModulKonten;
use App\Models\Anggota;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ModulController extends Controller
{
    public function index()
    {
        return view('modul.index', [
            'title'  => 'Modul',
            'moduls' => Modul::with(['creators', 'konten', 'kategoris'])->latest()->get(),
        ]);
    }

    public function create()
    {
        return view('modul.form', [
            'title'      => 'Tambah Modul',
            'anggotas'   => Anggota::all(),
            'kategoris'  => Kategori::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_modul'   => 'required|string|max:255',
            'slug'         => 'nullable|string|max:255|unique:modul,slug',
            'creators'     => 'required|array|min:1',
            'creators.*'   => 'required|exists:anggota,id',
            'kategoris'    => 'required|array|min:1',
            'kategoris.*'  => 'required|exists:kategori,id',
        ]);

        $modul = Modul::create($request->only(['nama_modul', 'slug', 'deskripsi']));
        $modul->creators()->sync($request->creators);
        $modul->kategoris()->sync($request->kategoris);
        $this->syncKonten($modul, $request);

        return redirect()->route('modul.index')->with('success', 'Modul berhasil ditambahkan.');
    }

    public function edit(Modul $modul)
    {
        return view('modul.form', [
            'title'      => 'Edit Modul',
            'modul'      => $modul->load(['creators', 'konten', 'kategoris']),
            'anggotas'   => Anggota::all(),
            'kategoris'  => Kategori::all(),
        ]);
    }

    public function update(Request $request, Modul $modul)
    {
        $request->validate([
            'nama_modul'   => 'required|string|max:255',
            'slug'         => 'nullable|string|max:255|unique:modul,slug,' . $modul->id,
            'creators'     => 'required|array|min:1',
            'creators.*'   => 'required|exists:anggota,id',
            'kategoris'    => 'required|array|min:1',
            'kategoris.*'  => 'required|exists:kategori,id',
        ]);

        $updateData         = $request->only(['nama_modul', 'deskripsi']);
        $updateData['slug'] = !empty($request->slug)
            ? $request->slug
            : Str::slug($request->nama_modul);

        $modul->update($updateData);
        $modul->creators()->sync($request->creators);
        $modul->kategoris()->sync($request->kategoris);
        $this->syncKonten($modul, $request);

        return redirect()->route('modul.index')->with('success', 'Modul berhasil diperbarui.');
    }

    public function destroy(Modul $modul)
    {
        foreach ($modul->konten as $k) {
            if ($k->isi_file) {
                Storage::disk('s3')->delete($k->isi_file); // hapus dari MinIO
            }
        }

        $modul->delete();
        return redirect()->route('modul.index')->with('success', 'Modul berhasil dihapus.');
    }

    // ─────────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ─────────────────────────────────────────────────────────────

    private function syncKonten(Modul $modul, Request $request): void
    {
        $kontens     = $request->input('konten', []);
        $files       = $request->file('konten', []);
        $existingIds = [];
        $urutan      = 1;

        foreach ($kontens as $idx => $k) {
            $tipe    = $k['tipe'] ?? 'text';
            $isiFile = null;

            if ($tipe !== 'text') {
                if (isset($files[$idx]['isi_file']) && $files[$idx]['isi_file']->isValid()) {
                    $uploadedFile = $files[$idx]['isi_file'];
                    $folder       = $tipe === 'image' ? 'modul/images' : 'modul/files';
                    $filename     = Str::uuid() . '.' . $uploadedFile->getClientOriginalExtension();

                    // Upload ke MinIO via disk 's3'
                    $isiFile = $uploadedFile->storeAs($folder, $filename, 's3');

                    // Hapus file lama dari MinIO jika ada
                    if (!empty($k['id'])) {
                        $old = ModulKonten::find($k['id']);
                        if ($old?->isi_file) {
                            Storage::disk('s3')->delete($old->isi_file);
                        }
                    }
                } else {
                    // Tidak ada file baru → pertahankan path lama
                    $isiFile = !empty($k['id'])
                        ? ModulKonten::find($k['id'])?->isi_file
                        : null;
                }
            }

            $payload = [
                'tipe'     => $tipe,
                'isi_text' => $tipe === 'text' ? ($k['isi_text'] ?? null) : null,
                'isi_file' => $tipe !== 'text' ? $isiFile : null,
                'urutan'   => $urutan,
            ];

            if (!empty($k['id'])) {
                $konten = ModulKonten::find($k['id']);
                if ($konten) {
                    $konten->update($payload);
                    $existingIds[] = $konten->id;
                }
            } else {
                $new           = ModulKonten::create(array_merge($payload, ['modul_id' => $modul->id]));
                $existingIds[] = $new->id;
            }

            $urutan++;
        }

        // Hapus konten yang dihapus dari form + filenya di MinIO
        $toDelete = ModulKonten::where('modul_id', $modul->id)
            ->whereNotIn('id', $existingIds)
            ->get();

        foreach ($toDelete as $d) {
            if ($d->isi_file) {
                Storage::disk('s3')->delete($d->isi_file); // hapus dari MinIO
            }
            $d->delete();
        }
    }

    /**
     * Upload gambar dari Quill image handler → MinIO
     */
    public function uploadImage(Request $request)
    {
        $request->validate(['image' => 'required|image|max:5120']);

        $file     = $request->file('image');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        // Upload ke MinIO
        $path = $file->storeAs('modul/images', $filename, 's3');

        // Generate URL publik dari MinIO (menggunakan AWS_URL di .env)
        $url = Storage::disk('s3')->url($path);

        return response()->json(['success' => true, 'url' => $url]);
    }
}