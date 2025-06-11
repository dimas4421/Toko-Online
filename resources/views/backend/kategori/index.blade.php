@extends('backend.layouts.app')

@section('content')
<!-- contentAwal -->

<div class="row">
    <div class="col-12">
        <a href="{{ route('backend.kategori.create') }}">
            <button type="button" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah</button>
        </a>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"> {{ $judul }} </h5>
                <div class="table-responsive">
                    <table id="zero_config" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($index as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->nama_kategori }}</td>
                                <td>
                                    <a href="{{ route('backend.kategori.edit', $row->id) }}" title="Ubah Data">
                                        <button type="button" class="btn btn-cyan btn-sm"><i class="far fa-edit"></i> Ubah</button>
                                    </a>
                                    <form method="POST" action="{{ route('backend.kategori.destroy', $row->id) }}" style="display: inline-block;">
                                        @method('delete')
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm show_confirm" data-konf-delete="{{ $row->nama_kategori }}" title="Hapus Data">
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

<!-- contentAkhir -->

<!-- SweetAlert untuk notifikasi berhasil -->
@if (session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK'
    });
</script>
@endif

<!-- jQuery & SweetAlert konfirmasi hapus -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $('.show_confirm').click(function(event) {
        var form = $(this).closest("form");
        var name = $(this).data("konf-delete");

        event.preventDefault();
        Swal.fire({
            title: 'Konfirmasi Hapus Data?',
            text: "Data '" + name + "' akan dihapus dan tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>
@endsection
