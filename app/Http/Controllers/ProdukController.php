<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\FotoProduk;
use App\Helpers\ImageHelper;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::orderBy('updated_at', 'desc')->get();
        return view('backend.produk.index', [
            'judul' => 'Data Produk',
            'index' => $produk
        ]);
    }

    public function create()
    {
        $kategori = Kategori::orderBy('nama_kategori', 'asc')->get();
        return view('backend.produk.create', [
            'judul' => 'Tambah Produk',
            'kategori' => $kategori
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kategori_id' => 'required',
            'nama_produk' => 'required|max:255|unique:produk',
            'detail' => 'required',
            'harga' => 'required',
            'berat' => 'required',
            'stok' => 'required',
            'foto' => 'required|image|mimes:jpeg,jpg,png,gif|max:1024'
        ], [
            'foto.image' => 'Format gambar harus jpeg, jpg, png, atau gif.',
            'foto.max' => 'Ukuran file gambar maksimal adalah 1024 KB.'
        ]);

        $validatedData['user_id'] = auth()->id();
        $validatedData['status'] = 0;

        if ($request->file('foto')) {
            $file = $request->file('foto');
            $extension = $file->getClientOriginalExtension();
            $originalFileName = date('YmdHis') . '_' . uniqid() . '.' . $extension;
            $directory = 'storage/img-produk/';

            ImageHelper::uploadAndResize($file, $directory, $originalFileName);
            ImageHelper::uploadAndResize($file, $directory, 'thumb_lg_' . $originalFileName, 800);
            ImageHelper::uploadAndResize($file, $directory, 'thumb_md_' . $originalFileName, 500, 519);
            ImageHelper::uploadAndResize($file, $directory, 'thumb_sm_' . $originalFileName, 100, 110);

            $validatedData['foto'] = $originalFileName;
        }

        Produk::create($validatedData);

        return redirect()->route('backend.produk.index')->with('success', 'Data berhasil tersimpan');
    }

    public function show(string $id)
    {
        $produk = Produk::with('fotoProduk')->findOrFail($id);
        $kategori = Kategori::orderBy('nama_kategori', 'asc')->get();
        return view('backend.produk.show', [
            'judul' => 'Detail Produk',
            'show' => $produk,
            'kategori' => $kategori
        ]);
    }

    public function edit(string $id)
    {
        $produk = Produk::findOrFail($id);
        $kategori = Kategori::orderBy('nama_kategori', 'asc')->get();
        return view('backend.produk.edit', [
            'judul' => 'Ubah Produk',
            'edit' => $produk,
            'kategori' => $kategori
        ]);
    }

    public function update(Request $request, string $id)
    {
        $produk = Produk::findOrFail($id);

        $rules = [
            'nama_produk' => 'required|max:255|unique:produk,nama_produk,' . $id,
            'kategori_id' => 'required',
            'status' => 'required',
            'detail' => 'required',
            'harga' => 'required',
            'berat' => 'required',
            'stok' => 'required',
            'foto' => 'image|mimes:jpeg,jpg,png,gif|max:1024'
        ];

        $messages = [
            'foto.image' => 'Format gambar harus jpeg, jpg, png, atau gif.',
            'foto.max' => 'Ukuran file gambar maksimal adalah 1024 KB.'
        ];

        $validatedData = $request->validate($rules, $messages);
        $validatedData['user_id'] = auth()->id();

        if ($request->file('foto')) {
            $this->hapusGambarProduk($produk);

            $file = $request->file('foto');
            $extension = $file->getClientOriginalExtension();
            $originalFileName = date('YmdHis') . '_' . uniqid() . '.' . $extension;
            $directory = 'storage/img-produk/';

            ImageHelper::uploadAndResize($file, $directory, $originalFileName);
            ImageHelper::uploadAndResize($file, $directory, 'thumb_lg_' . $originalFileName, 800);
            ImageHelper::uploadAndResize($file, $directory, 'thumb_md_' . $originalFileName, 500, 519);
            ImageHelper::uploadAndResize($file, $directory, 'thumb_sm_' . $originalFileName, 100, 110);

            $validatedData['foto'] = $originalFileName;
        }

        $produk->update($validatedData);

        return redirect()->route('backend.produk.index')->with('success', 'Data berhasil diperbaharui');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        $this->hapusGambarProduk($produk);

        $fotoProduks = FotoProduk::where('produk_id', $id)->get();
        foreach ($fotoProduks as $fotoProduk) {
            $fotoPath = public_path('storage/img-produk/') . $fotoProduk->foto;
            if (file_exists($fotoPath)) {
                unlink($fotoPath);
            }
            $fotoProduk->delete();
        }

        $produk->delete();

        return redirect()->route('backend.produk.index')->with('success', 'Data berhasil dihapus');
    }

    public function storeFoto(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'foto_produk.*' => 'image|mimes:jpeg,jpg,png,gif|max:1024'
        ]);

        if ($request->hasFile('foto_produk')) {
            foreach ($request->file('foto_produk') as $file) {
                $extension = $file->getClientOriginalExtension();
                $filename = date('YmdHis') . '_' . uniqid() . '.' . $extension;
                $directory = 'storage/img-produk/';

                ImageHelper::uploadAndResize($file, $directory, $filename, 800);

                FotoProduk::create([
                    'produk_id' => $request->produk_id,
                    'foto' => $filename
                ]);
            }
        }

        return redirect()->route('backend.produk.show', $request->produk_id)
            ->with('success', 'Foto berhasil ditambahkan.');
    }

    public function destroyFoto($id)
    {
        $foto = FotoProduk::findOrFail($id);
        $produkId = $foto->produk_id;

        $imagePath = public_path('storage/img-produk/') . $foto->foto;
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        $foto->delete();

        return redirect()->route('backend.produk.show', $produkId)
            ->with('success', 'Foto berhasil dihapus.');
    }

    private function hapusGambarProduk($produk)
    {
        $directory = public_path('storage/img-produk/');

        $files = [
            $produk->foto,
            'thumb_lg_' . $produk->foto,
            'thumb_md_' . $produk->foto,
            'thumb_sm_' . $produk->foto
        ];

        foreach ($files as $file) {
            $filePath = $directory . $file;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    }
    public function formProduk()
{
    return view('backend.produk.form', [
        'judul' => 'Laporan Data Produk',
    ]);
}

// Method untuk Cetak Laporan Produk
public function cetakProduk(Request $request)
{
    // Validasi input tanggal
    $request->validate([
        'tanggal_awal' => 'required|date',
        'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
    ], [
        'tanggal_awal.required' => 'Tanggal Awal harus diisi.',
        'tanggal_akhir.required' => 'Tanggal Akhir harus diisi.',
        'tanggal_akhir.after_or_equal' => 'Tanggal Akhir harus lebih besar atau sama dengan Tanggal Awal.',
    ]);

    $tanggalAwal = $request->input('tanggal_awal');
    $tanggalAkhir = $request->input('tanggal_akhir');

    // Ambil data produk berdasarkan rentang tanggal
    $produk = Produk::whereBetween('updated_at', [$tanggalAwal, $tanggalAkhir])
        ->orderBy('id', 'desc')
        ->get();

    return view('backend.produk.cetak', [
        'judul' => 'Laporan Produk',
        'tanggalAwal' => $tanggalAwal,
        'tanggalAkhir' => $tanggalAkhir,
        'cetak' => $produk,
    ]);
}

}
