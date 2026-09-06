<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title','Detektif Nutrisi')</title>
<link rel="stylesheet" href="{{ asset('css/detektif.css') }}">
</head>
<body class="detektif">
<nav class="topnav"><div class="topnav-inner">
<a href="{{ route('home') }}" style="text-decoration:none"><span class="brand-case">DETEKTIF NUTRISI<small>CASE FILE • MAKANAN & MINUMAN</small></span></a>
<div class="navlinks">
@auth
<span class="kbd">{{ auth()->user()->username }}</span>
<a href="{{ route('siswa.dashboard') }}">Berkas Kasus</a>
<form method="POST" action="{{ route('siswa.logout') }}" style="display:inline">@csrf<button class="look" style="margin:0">Logout</button></form>
@else
@if(session()->has('guru_kelas_id'))
<a href="{{ route('guru.dashboard') }}">Meja Guru</a>
<form method="POST" action="{{ route('guru.logout') }}" style="display:inline">@csrf<button class="look" style="margin:0">Logout</button></form>
@else
<a href="{{ route('siswa.login') }}">Masuk Detektif</a>
<a href="{{ route('siswa.register') }}">Daftar</a>
<a href="{{ route('guru.login') }}">Ruang Guru</a>
@endif
@endauth
</div>
</div></nav>
<div class="wrap">
@if(session('success'))<div class="alert-ok">{{ session('success') }}</div>@endif
@if($errors->any())<div class="alert-err"><b>Bukti belum lengkap:</b><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
@yield('content')
</div>
@stack('scripts')
</body>
</html>
