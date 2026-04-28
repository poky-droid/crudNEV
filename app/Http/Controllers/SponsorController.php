<?php

namespace App\Http\Controllers;

use App\Models\Sponsor;
use Illuminate\Http\Request;

class SponsorController extends Controller
{
    public function index()
    {
        return view('sponsor.index', [
            'title'    => 'Sponsor',
            'sponsors' => Sponsor::orderBy('urutan')->get(),
        ]);
    }

    public function create()
    {
        return view('sponsor.form', ['title' => 'Tambah Sponsor']);
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required|string|max:150']);

        $data = $request->only(['nama','website','kontak','status','urutan']);
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('sponsor', 'minio');
        }

        Sponsor::create($data);
        return redirect()->route('sponsor.index')->with('success', 'Sponsor berhasil ditambahkan.');
    }

    public function edit(Sponsor $sponsor)
    {
        return view('sponsor.form', ['title' => 'Edit Sponsor', 'sponsor' => $sponsor]);
    }

    public function update(Request $request, Sponsor $sponsor)
    {
        $request->validate(['nama' => 'required|string|max:150']);

        $data = $request->only(['nama','website','kontak','status','urutan']);
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('sponsor', 'minio');
        }

        $sponsor->update($data);
        return redirect()->route('sponsor.index')->with('success', 'Sponsor berhasil diperbarui.');
    }

    public function destroy(Sponsor $sponsor)
    {
        $sponsor->delete();
        return redirect()->route('sponsor.index')->with('success', 'Sponsor berhasil dihapus.');
    }
}
