@extends('backend.layouts.app')

@section('content')
<!-- contentAwal -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $judul }}</h4>
                    <div class="row">
                        <!-- Kolom Kiri -->
                        <div class="col-md-6">
                            <!-- Kategori -->
                            <div class="form-group">
                                <label>Kategori</label>
                                <select name="kategori_id" class="form-control" disabled>
                                    <option value=""> - Pilih Kategori - </option>
                                    @foreach ($kategori as $row)
                                        <option value="{{ $row->id }}" {{ old('kategori_id', $show->kategori_id) == $row->id ? 'selected' : '' }}>
                                            {{ $row->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Nama Produk -->
                            <div class="form-group">
                                <label>Nama Produk</label>
                                <input type="text" name="nama_produk" value="{{ old('nama_produk', $show->nama_produk) }}" class="form-control" disabled>
                            </div>

                            <!-- Detail -->
                            <div class="form-group">
                                <label>Detail</label>
                                <textarea name="detail" class="form-control" id="ckeditor" disabled>{{ old('detail', $show->detail) }}</textarea>
                            </div>

                            <!-- Foto Utama -->
                            <div class="form-group">
                                <label>Foto Utama</label><br>
                                <img src="{{ asset('storage/img-produk/' . $show->foto) }}" class="img-fluid rounded" width="100%">
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-md-6">
                            <label>Foto Tambahan</label>
                            <div id="foto-container">
                                <div class="row">
                                    @foreach($show->fotoProduk as $gambar)
                                        <div class="col-md-8 mb-2">
                                            <img src="{{ asset('storage/img-produk/' . $gambar->foto) }}" class="img-fluid rounded">
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <form action="{{ route('backend.foto_produk.destroy', $gambar->id) }}" method="post">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Tombol Tambah Foto -->
                            <button
                                type="button"
                                class="btn btn-primary mt-3 add-foto"
                                data-route="{{ route('backend.foto_produk.store') }}"
                                data-token="{{ csrf_token() }}"
                                data-id="{{ $show->id }}"
                            >
                                Tambah Foto
                            </button>

                            <!-- Form Tambah Foto Dinamis -->
                            <div id="formTambahFoto" class="mt-3"></div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Kembali -->
                <div class="border-top">
                    <div class="card-body">
                        <a href="{{ route('backend.produk.index') }}">
                            <button type="button" class="btn btn-secondary">Kembali</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- contentAkhir -->
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.querySelector('.add-foto');
    const container = document.getElementById('formTambahFoto');

    btn.addEventListener('click', function () {
        // Cegah duplikasi form
        if (document.getElementById('dynamicFotoForm')) return;

        const route = btn.getAttribute('data-route');
        const token = btn.getAttribute('data-token');
        const id = btn.getAttribute('data-id');

        const form = document.createElement('form');
        form.id = 'dynamicFotoForm';
        form.action = route;
        form.method = 'POST';
        form.enctype = 'multipart/form-data';
        form.classList.add('d-flex', 'gap-2', 'align-items-center');

        form.innerHTML = `
            <input type="hidden" name="_token" value="${token}">
            <input type="hidden" name="produk_id" value="${id}">
            <input type="file" name="foto_produk[]" class="form-control" required>
            <button type="submit" class="btn btn-success">Simpan</button>
        `;

        container.appendChild(form);
    });
});
</script>
@endpush
