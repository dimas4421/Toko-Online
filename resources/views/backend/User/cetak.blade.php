<style>
    table {
        border-collapse: collapse;
        width: 100%;
        border: 1px solid #ccc;
        font-family: Arial, sans-serif;
        font-size: 14px;
    }

    table th,
    table td {
        border: 1px solid #ccc;
        padding: 6px;
        text-align: left;
    }

    table th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    .info {
        margin-bottom: 15px;
        font-family: Arial, sans-serif;
        font-size: 14px;
    }

    .info strong {
        display: inline-block;
        min-width: 100px;
    }
</style>

{{-- Header informasi --}}
<div class="info">
    {{-- Jika ingin pakai header gambar, buka komentar di bawah --}}
    {{-- <div style="text-align:center;">
        <img src="{{ asset('images/header.png') }}" width="50%">
    </div> --}}

    <p><strong>Perihal:</strong> {{ $judul }}</p>
    <p><strong>Periode:</strong> {{ $tanggalAwal }} s/d {{ $tanggalAkhir }}</p>
</div>

{{-- Tabel Data User --}}
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($cetak as $row)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $row->nama }}</td>
            <td>{{ $row->email }}</td>
            <td>
                @if ($row->role == 1)
                    Super Admin
                @elseif ($row->role == 0)
                    Admin
                @else
                    Tidak Diketahui
                @endif
            </td>
            <td>
                @if ($row->status == 1)
                    Aktif
                @elseif ($row->status == 0)
                    NonAktif
                @else
                    Tidak Diketahui
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- Script Otomatis Print --}}
<script>
    window.onload = function () {
        window.print();
    };
</script>
