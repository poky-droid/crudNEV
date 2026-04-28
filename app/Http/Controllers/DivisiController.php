<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use Illuminate\Http\Request;

class DivisiController extends Controller
{
    public function index()
    {
        return view('divisi.index', ['title' => 'Divisi', 'divisis' => Divisi::all()]);
    }

    public function create()
    {
        return view('divisi.form', ['title' => 'Tambah Divisi']);
    }

    public function store(Request $request)
    {
        $request->validate(['nama_divisi' => 'required|string|max:100']);
        Divisi::create($request->only('nama_divisi'));
        return redirect()->route('divisi.index')->with('success', 'Divisi berhasil ditambahkan.');
    }

    public function edit(Divisi $divisi)
    {
        return view('divisi.form', ['title' => 'Edit Divisi', 'divisi' => $divisi]);
    }

    public function update(Request $request, Divisi $divisi)
    {
        $request->validate(['nama_divisi' => 'required|string|max:100']);
        $divisi->update($request->only('nama_divisi'));
        return redirect()->route('divisi.index')->with('success', 'Divisi berhasil diperbarui.');
    }

    public function destroy(Divisi $divisi)
    {
        $divisi->delete();
        return redirect()->route('divisi.index')->with('success', 'Divisi berhasil dihapus.');
    }
}
