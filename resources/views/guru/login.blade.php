@extends('layouts.app')
@section('title','Ruang Guru — Detektif Nutrisi')
@section('content')
<div style="max-width:520px;margin:0 auto">
<div class="paper-card torn">
<p class="stamp">AKSES TERBATAS • RUANG GURU</p>
<p style="font-size:.9rem"><b>Penjaga Arsip:</b> "Hanya pemegang kunci kelas yang boleh masuk. Pilih kelasmu dan ucapkan kata sandi."</p>
<form method="POST" action="{{ route('guru.login') }}">
@csrf
<label class="lbl">Kelas<select name="kelas_id" required><option value="">-- Pilih Kelas --</option>@foreach($kelas as $k)<option value="{{ $k->id }}" @selected(old('kelas_id')==$k->id)>{{ $k->nama }}</option>@endforeach</select></label>
<label class="lbl">Kata Sandi Kelas<input type="password" name="password" required></label>
<button class="btn-pin" style="width:100%;margin-top:.8rem;background:#10141c">Buka Meja Guru</button>
</form>
<p class="type" style="font-size:.75rem">Default tiap kelas: guru123</p>
</div>
</div>
@endsection
