<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index()
    {
        return view('jabatan.index', ['title' => 'Jabatan', 'jabatans' => Jabatan::all()]);
    }

    public function create()
    {
        return view('jabatan.form', ['title' => 'Tambah Jabatan']);
    }

    public function store(Request $request)
    {
        $request->validate(['nama_jabatan' => 'required|string|max:100']);
        Jabatan::create($request->only('nama_jabatan'));
        return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function edit(Jabatan $jabatan)
    {
        return view('jabatan.form', ['title' => 'Edit Jabatan', 'jabatan' => $jabatan]);
    }

    public function update(Request $request, Jabatan $jabatan)
    {
        $request->validate(['nama_jabatan' => 'required|string|max:100']);
        $jabatan->update($request->only('nama_jabatan'));
        return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function destroy(Jabatan $jabatan)
    {
        $jabatan->delete();
        return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil dihapus.');
    }
}
