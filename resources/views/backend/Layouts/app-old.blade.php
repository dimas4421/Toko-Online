<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>tokoonline</title>
<!-- SweetAlert2 CDN -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
<a href="{{ route('backend.beranda') }}">Beranda</a> |
<a href="#">User</a> |
<a href="" onclick="event.preventDefault(); document.getElementById('keluar- app').submit();">Keluar</a>
<p></p>

<!-- @yieldAwal --> @yield('content')
<!-- @yieldAkhir-->

<!-- keluarApp -->
<form id="keluar-app" action="{{ route('backend.logout') }}" method="POST" class="d- none">
@csrf
</form>
<!-- keluarAppEnd -->
</body>
</html>
