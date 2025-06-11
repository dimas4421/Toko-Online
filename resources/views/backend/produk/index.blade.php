@extends('backend.layouts.app')

@section('content')
<!-- Bagian Konten Awal -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $judul }}</h4> {{-- Judul halaman --}}

                    <!-- Tombol Tambah Produk -->
                    <div class="mb-3"> {{-- Margin bawah untuk tombol --}}
                        <a href="{{ route('backend.produk.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Produk
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                    <th>Nama Produk</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($index as $row) {{-- Menggunakan $index sesuai nama variabel di controller --}}
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $row->kategori->nama_kategori }}</td>
                                        <td>
                                            @if ($row->status == 1)
                                                <span class="badge badge-success">Publis</span>
                                            @elseif($row->status == 0)
                                                <span class="badge badge-secondary">Blok</span>
                                            @endif
                                        </td>
                                        <td>{{ $row->nama_produk }}</td>
                                        <td>Rp. {{ number_format($row->harga, 0, ',', '.') }}</td>
                                        <td>{{ $row->stok }}</td>
                                        <td>
                                            <!-- Tombol Ubah Data -->
                                            <a href="{{ route('backend.produk.edit', $row->id) }}" class="btn btn-cyan btn-sm" title="Ubah Data">
                                                <i class="far fa-edit"></i> Ubah
                                            </a>

                                            <!-- Tombol Lihat Gambar (Detail Produk) -->
                                            <a href="{{ route('backend.produk.show', $row->id) }}" class="btn btn-warning btn-sm" title="Lihat Gambar">
                                                <i class="fas fa-image"></i> Gambar
                                            </a>

                                            <!-- Form Hapus Data -->
                                            <form method="POST" action="{{ route('backend.produk.destroy', $row->id) }}" style="display:inline-block;">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-danger btn-sm show_confirm" data-konf-delete="{{ $row->nama_produk }}" title="Hapus Data"> {{-- Menggunakan nama_produk untuk konfirmasi --}}
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Bagian Konten Akhir -->
@endsection
