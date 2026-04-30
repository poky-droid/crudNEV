<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Divisi;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AnggotaController extends Controller
{
    public function index()
    {
        return view('anggota.index', [
            'title'    => 'Anggota',
            'anggotas' => Anggota::with(['divisi','jabatan'])->get(),
        ]);
    }

    public function create()
    {
        return view('anggota.form', [
            'title'    => 'Tambah Anggota',
            'anggota'  => null,
            'divisis'  => Divisi::all(),
            'jabatans' => Jabatan::all(),
            'angkatan' => '',
            'email' => '',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:150',
            'nim'      => 'required|string|max:50|unique:anggota,nim',
            'email'    => 'required|email|unique:anggota,email',
            'angkatan' => 'integer|min:1900|max:' . (date('Y') + 1),
            'password' => 'required|string|min:6',
        ]);

        $data = $request->only(['nama','nim','jurusan','email','angkatan','divisi_id','jabatan_id']);
        $data['password'] = Hash::make($request->password);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto', 'minio');
        }

        Anggota::create($data);
        return redirect()->route('anggota.index')->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function edit(Anggota $anggota)
    {
        return view('anggota.form', [
            'title'    => 'Edit Anggota',
            'anggota'  => $anggota,
            'divisis'  => Divisi::all(),
            'jabatans' => Jabatan::all(),
            'angkatan' => $anggota->angkatan ?? '',
            'email' => $anggota->email ?? '',
        ]);
    }

    public function update(Request $request, Anggota $anggota)
    {
        $request->validate([
            'nama'     => 'required|string|max:150',
            'nim'      => 'required|string|max:50|unique:anggota,nim,'.$anggota->id,
            'email'    => 'required|email|unique:anggota,email,'.$anggota->id,
            'angkatan' => 'integer|min:1900|max:' . (date('Y') + 1),
        ]);

        $data = $request->only(['nama','nim','jurusan','email','angkatan','divisi_id','jabatan_id']);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6']);
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto', 'minio');
        }

        $anggota->update($data);
        return redirect()->route('anggota.index')->with('success', 'Anggota berhasil diperbarui.');
    }

    public function destroy(Anggota $anggota)
    {
        $anggota->delete();
        return redirect()->route('anggota.index')->with('success', 'Anggota berhasil dihapus.');
    }
}
