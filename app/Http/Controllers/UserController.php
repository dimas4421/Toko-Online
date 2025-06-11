<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ImageHelper;

class UserController extends Controller
{
    public function index()
    {
        $user = User::orderBy('updated_at', 'desc')->get();
        return view('backend.User.index', [
            'judul' => 'Data User',
            'index' => $user
        ]);
    }

    public function create()
    {
        return view('backend.User.create', [
            'judul' => 'Tambah User',
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|max:255',
            'email' => 'required|email|max:255|unique:user', // FIXED: 'users' table
            'role' => 'required',
            'hp' => 'required|min:10|max:13',
            'password' => 'required|min:4|confirmed',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:1024',
        ], [
            'foto.image' => 'Format gambar harus berupa jpeg, jpg, png, atau gif.',
            'foto.max' => 'Ukuran file gambar maksimal adalah 1024 KB.'
        ]);

        $validatedData['status'] = 0;

        // Upload foto jika ada
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $extension = $file->getClientOriginalExtension();
            $originalFileName = date('YmdHis') . '_' . uniqid() . '.' . $extension;
            $directory = 'storage/img-user/';
            ImageHelper::uploadAndResize($file, $directory, $originalFileName, 385, 400);
            $validatedData['foto'] = $originalFileName;
        }

        // Validasi password kompleks
        $password = $request->input('password');
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/';
        if (!preg_match($pattern, $password)) {
            return redirect()->back()->withErrors([
                'password' => 'Password harus terdiri dari kombinasi huruf besar, huruf kecil, angka, dan simbol.'
            ])->withInput();
        }

        $validatedData['password'] = Hash::make($password);
        User::create($validatedData);

        return redirect()->route('backend.user.index')->with('success', 'Data berhasil tersimpan');
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('backend.User.edit', [
            'judul' => 'Edit User',
            'user' => $user
        ]);
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'nama' => 'required|max:255',
            'role' => 'required',
            'status' => 'required',
            'hp' => 'required|min:10|max:13',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:1024',
        ];

        $messages = [
            'foto.image' => 'Format gambar harus berupa jpeg, jpg, png, atau gif.',
            'foto.max' => 'Ukuran file gambar maksimal adalah 1024 KB.'
        ];

        if ($request->email != $user->email) {
            $rules['email'] = 'required|max:255|email|unique:users'; // FIXED: 'users' table
        }

        $validatedData = $request->validate($rules, $messages);

        // Upload foto baru jika ada
        if ($request->file('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto) {
                $oldImagePath = public_path('storage/img-user/') . $user->foto;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $file = $request->file('foto');
            $extension = $file->getClientOriginalExtension();
            $originalFileName = date('YmdHis') . '_' . uniqid() . '.' . $extension;
            $directory = 'storage/img-user/';
            ImageHelper::uploadAndResize($file, $directory, $originalFileName, 385, 400);
            $validatedData['foto'] = $originalFileName;
        }

        $user->update($validatedData);

        return redirect()->route('backend.user.index')->with('success', 'Data berhasil diperbaharui');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->foto) {
            $oldImagePath = public_path('storage/img-user/') . $user->foto;
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        $user->delete();
        return redirect()->route('backend.user.index')->with('success', 'Data berhasil dihapus');
    }
    // Menampilkan Form Filter Laporan User
public function formUser()
{
    return view('backend.user.form', [
        'judul' => 'Laporan Data User',
    ]);
}

// Mencetak Laporan User berdasarkan rentang tanggal
public function cetakUser(Request $request)
{
    // Validasi input tanggal
    $request->validate([
        'tanggal_awal'   => 'required|date',
        'tanggal_akhir'  => 'required|date|after_or_equal:tanggal_awal',
    ], [
        'tanggal_awal.required'   => 'Tanggal Awal harus diisi.',
        'tanggal_akhir.required'  => 'Tanggal Akhir harus diisi.',
        'tanggal_akhir.after_or_equal' => 'Tanggal Akhir harus lebih besar atau sama dengan Tanggal Awal.',
    ]);

    // Ambil input tanggal dari form
    $tanggalAwal  = $request->input('tanggal_awal');
    $tanggalAkhir = $request->input('tanggal_akhir');

    // Query data user berdasarkan tanggal pendaftaran
    $user = User::whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
                ->orderBy('id', 'desc')
                ->get();

    // Tampilkan view cetak laporan
    return view('backend.user.cetak', [
        'judul'        => 'Laporan User',
        'tanggalAwal'  => $tanggalAwal,
        'tanggalAkhir' => $tanggalAkhir,
        'cetak'        => $user,
    ]);
}

}
