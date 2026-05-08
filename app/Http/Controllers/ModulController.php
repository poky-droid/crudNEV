<?php

namespace App\Http\Controllers;

use App\Models\Modul;
use App\Models\ModulKonten;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ModulController extends Controller
{
    public function index()
    {
        return view('modul.index', [
            'title'  => 'Modul',
            'moduls' => Modul::with(['anggota','konten','anggotaAkses'])->latest()->get(),
        ]);
    }

    public function create()
    {
        return view('modul.form', ['title' => 'Tambah Modul', 'anggotas' => Anggota::all()]);
    }

    public function store(Request $request)
    {
        $request->validate(['nama_modul' => 'required|string|max:255']);
        $request->validate(['slug' => 'nullable|string|max:255|unique:modul,slug']);

        $modul = Modul::create($request->only(['nama_modul', 'slug', 'deskripsi', 'anggota_id']));
        $modul->anggotaAkses()->sync($request->anggota_akses ?? []);
        $this->syncKonten($modul, $request->konten ?? []);

        return redirect()->route('modul.index')->with('success', 'Modul berhasil ditambahkan.');
    }

    public function edit(Modul $modul)
    {
        return view('modul.form', [
            'title'    => 'Edit Modul',
            'modul'    => $modul->load(['konten','anggotaAkses']),
            'anggotas' => Anggota::all(),
        ]);
    }

    public function update(Request $request, Modul $modul)
    {
        $request->validate(['nama_modul' => 'required|string|max:255']);
        $request->validate(['slug' => 'nullable|string|max:255|unique:modul,slug,' . $modul->id]);

        $updateData = $request->only(['nama_modul','deskripsi','anggota_id']);
        
        // Update slug jika diberikan, atau generate dari nama_modul
        if (!empty($request->slug)) {
            $updateData['slug'] = $request->slug;
        } else {
            $updateData['slug'] = Str::slug($request->nama_modul);
        }
        
        $modul->update($updateData);
        $modul->anggotaAkses()->sync($request->anggota_akses ?? []);
        $this->syncKonten($modul, $request->konten ?? []);

        return redirect()->route('modul.index')->with('success', 'Modul berhasil diperbarui.');
    }

    public function destroy(Modul $modul)
    {
        $modul->delete();
        return redirect()->route('modul.index')->with('success', 'Modul berhasil dihapus.');
    }

    private function syncKonten(Modul $modul, array $kontens)
    {
        $existingIds = [];
        $urutan = 1;

        foreach ($kontens as $k) {
            if (!empty($k['id'])) {
                $konten = ModulKonten::find($k['id']);
                if ($konten) {
                    $konten->update([
                        'tipe'     => $k['tipe'],
                        'isi_text' => $k['tipe'] === 'text' ? ($k['isi_text'] ?? null) : null,
                        'urutan'   => $urutan,
                    ]);
                    $existingIds[] = $konten->id;
                }
            } else {
                $newKonten = ModulKonten::create([
                    'modul_id' => $modul->id,
                    'tipe'     => $k['tipe'],
                    'isi_text' => $k['tipe'] === 'text' ? ($k['isi_text'] ?? null) : null,
                    'urutan'   => $urutan,
                ]);
                $existingIds[] = $newKonten->id;
            }
            $urutan++;
        }

        ModulKonten::where('modul_id', $modul->id)
            ->whereNotIn('id', $existingIds)
            ->delete();
    }
}
