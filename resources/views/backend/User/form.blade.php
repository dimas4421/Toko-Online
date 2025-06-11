@extends('backend.layouts.app')

@section('content')
<!-- Template Form Laporan User -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <form class="form-horizontal" action="{{ route('backend.laporan.cetakuser') }}" method="POST" target="_blank">
                @csrf
                <div class="card-body">
                    <h4 class="card-title">{{ $judul }}</h4>

                    <!-- Input Tanggal Awal -->
                    <div class="form-group">
                        <label for="tanggal_awal">Tanggal Awal</label>
                        <input type="date"
                               name="tanggal_awal"
                               id="tanggal_awal"
                               value="{{ old('tanggal_awal') }}"
                               class="form-control @error('tanggal_awal') is-invalid @enderror"
                               placeholder="Masukkan Tanggal Awal">
                        @error('tanggal_awal')
                            <span class="invalid-feedback d-block" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <!-- Input Tanggal Akhir -->
                    <div class="form-group">
                        <label for="tanggal_akhir">Tanggal Akhir</label>
                        <input type="date"
                               name="tanggal_akhir"
                               id="tanggal_akhir"
                               value="{{ old('tanggal_akhir') }}"
                               class="form-control @error('tanggal_akhir') is-invalid @enderror"
                               placeholder="Masukkan Tanggal Akhir">
                        @error('tanggal_akhir')
                            <span class="invalid-feedback d-block" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <!-- Tombol Submit -->
                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-printer"></i> Cetak
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Template -->
@endsection
