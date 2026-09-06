@extends('layouts.app')
@section('title','Daftar — Detektif Nutrisi')
@section('content')
<div style="max-width:560px;margin:0 auto">
<div class="paper-card torn">
<p class="stamp">KARTU IDENTITAS • PENDAFTARAN</p>
<div style="display:flex;gap:.8rem;align-items:center;margin:.6rem 0">
<div class="avatar" style="width:64px;height:64px"><img src="{{ asset('images/detektif/maskot-nutri.svg') }}" alt="Maskot detektif"></div>
<p style="font-size:.9rem;margin:0"><b>NUTRI:</b> "Isi kartu identitasmu. Kelas diambil dari arsip yang sama dengan Ruang Guru — tidak bisa dipalsukan."</p>
</div>
<form method="POST" action="{{ route('siswa.register') }}">
@csrf
<label class="lbl">Nama Detektif (username, min 3 karakter)<input name="username" value="{{ old('username') }}" required></label>
<label class="lbl">Kata Sandi (min 6 karakter)<input type="password" name="password" required></label>
<label class="lbl">Kelas (arsip resmi)<select name="kelas_id" required><option value="">-- Pilih Kelas --</option>@foreach($kelas as $k)<option value="{{ $k->id }}" @selected(old('kelas_id')==$k->id)>{{ $k->nama }}</option>@endforeach</select></label>
<label class="lbl">Kelompok<input name="kelompok" value="{{ old('kelompok') }}" placeholder="cth: Kelompok 1" required></label>
<button class="btn-pin btn-crimson" style="width:100%;margin-top:.8rem">Stempel & Buat Akun</button>
</form>
<p style="font-size:.85rem">Sudah terdaftar? <a href="{{ route('siswa.login') }}" style="color:#8B0000">Masuk di sini</a></p>
</div>
</div>
@endsection
