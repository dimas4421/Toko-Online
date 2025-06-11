<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::orderBy('nama_kategori', 'asc')->get();

        return view('backend.kategori.index', [
            'judul' => 'Kategori',
            'index' => $kategori,
        ]);
    }

    public function create()
    {
        return view('backend.kategori.create', [
            'judul' => 'Kategori',
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_kategori' => 'required|max:255|unique:kategori',
        ]);

        Kategori::create($validatedData);

        return redirect()
            ->route('backend.kategori.index')
            ->with('success', 'Data berhasil tersimpan');
    }

    public function edit(string $id)
    {
        $kategori = Kategori::findOrFail($id);

        return view('backend.kategori.edit', [
            'judul' => 'Kategori',
            'edit' => $kategori,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'nama_kategori' => 'required|max:255|unique:kategori,nama_kategori,' . $id,
        ]);

        Kategori::where('id', $id)->update($validatedData);

        return redirect()
            ->route('backend.kategori.index')
            ->with('success', 'Data berhasil diperbaharui');
    }

    public function destroy(string $id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        return redirect()
            ->route('backend.kategori.index')
            ->with('success', 'Data berhasil dihapus');
    }
}
