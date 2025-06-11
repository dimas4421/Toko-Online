@extends('backend.layouts.app')

@section('content')
<!-- contentAwal -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <form action="{{ route('backend.user.update', $user->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        <h4 class="card-title">{{ $judul }}</h4>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Foto</label>
                                    {{-- View image --}}
                                    @if ($user->foto)
                                        <img src="{{ asset('storage/img-user/' . $user->foto) }}" class="foto-preview" width="100%">
                                    @else
                                        <img src="{{ asset('storage/img-user/img-default.jpg') }}" class="foto-preview" width="100%">
                                    @endif

                                    <p></p>
                                    {{-- Input file --}}
                                    <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" onchange="previewFoto()">
                                    @error('foto')
                                        <div class="invalid-feedback alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Hak Akses</label>
                                    <select name="role" class="form-control @error('role') is-invalid @enderror">
                                        <option value="">- Pilih Hak Akses -</option>
                                        <option value="1" {{ old('role', $user->role) == '1' ? 'selected' : '' }}>Super Admin</option>
                                        <option value="0" {{ old('role', $user->role) == '0' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                    @error('role')
                                        <span class="invalid-feedback alert-danger d-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" class="form-control @error('status') is-invalid @enderror">
                                        <option value="">- Pilih Status -</option>
                                        <option value="1" {{ old('status', $user->status) == '1' ? 'selected' : '' }}>Aktif</option>
                                        <option value="0" {{ old('status', $user->status) == '0' ? 'selected' : '' }}>Non Aktif</option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback alert-danger d-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Nama</label>
                                    <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" class="form-control @error('nama') is-invalid @enderror" placeholder="Masukkan Nama">
                                    @error('nama')
                                        <span class="invalid-feedback alert-danger d-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan Email">
                                    @error('email')
                                        <span class="invalid-feedback alert-danger d-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>HP</label>
                                    <input type="text" onkeypress="return hanyaAngka(event)" name="hp" value="{{ old('hp', $user->hp) }}" class="form-control @error('hp') is-invalid @enderror" placeholder="Masukkan Nomor HP">
                                    @error('hp')
                                        <span class="invalid-feedback alert-danger d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-top">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary">Perbaharui</button>
                            <a href="{{ route('backend.user.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- contentAkhir -->
@endsection
