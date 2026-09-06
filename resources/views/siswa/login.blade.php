@extends('layouts.app')
@section('title','Login Siswa — Detektif Nutrisi')
@section('content')
<div style="max-width:520px;margin:0 auto">
<div class="paper-card torn">
<p class="stamp">BUKA BRANKAS • LOGIN DETEKTIF</p>
<div style="display:flex;gap:.8rem;align-items:center;margin:.6rem 0">
<div class="avatar" style="width:64px;height:64px"><img src="{{ asset('images/detektif/maskot-nutri.svg') }}" alt="Maskot detektif Nutri ilustrasi 2D"></div>
<p style="font-size:.9rem;margin:0"><b>NUTRI:</b> "Tunjukkan lencanamu, Detektif. Masukkan username dan password untuk membuka berkas kasus."</p>
</div>
<form method="POST" action="{{ route('siswa.login') }}">
@csrf
<label class="lbl">Nama Detektif (username)<input name="username" value="{{ old('username') }}" required></label>
<label class="lbl">Kata Sandi<input type="password" name="password" required></label>
<button class="btn-pin btn-crimson" style="width:100%;margin-top:.8rem">Buka Berkas Kasus</button>
</form>
<p style="font-size:.85rem">Belum punya identitas? <a href="{{ route('siswa.register') }}" style="color:#8B0000">Buat identitas baru</a></p>
</div>
</div>
@endsection
