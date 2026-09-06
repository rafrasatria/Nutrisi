@extends('layouts.app')
@section('title','Meja Guru — Kelas '.$kelas->nama)
@section('content')
<p class="stamp">MEJA GURU • KELAS {{ $kelas->nama }}</p>
<div class="desk" style="margin-top:.6rem">
<div style="display:flex;gap:.6rem;flex-wrap:wrap;align-items:center">
<a class="btn-pin btn-gold" href="{{ route('guru.rekap-pdf') }}">Download PDF Rekap Kelas</a>
<form method="GET" style="display:flex;gap:.4rem;flex-wrap:wrap">
<input name="q" value="{{ request('q') }}" placeholder="Cari username" style="width:160px">
<input name="kelompok" value="{{ request('kelompok') }}" placeholder="Kelompok" style="width:140px">
<select name="status" style="width:150px"><option value="">Semua status</option><option value="mengerjakan" @selected(request('status')=='mengerjakan')>Mengerjakan</option><option value="selesai" @selected(request('status')=='selesai')>Selesai</option></select>
<button class="btn-pin">Filter</button>
</form>
</div>
<div style="overflow-x:auto;margin-top:.7rem">
<table class="table-evidence" style="background:#FBF6E8;color:#241a12"><thead><tr><th>Username</th><th>Kelompok</th><th>Status</th><th>Benar</th><th>Salah</th><th>Skor</th><th>Pengulangan</th><th>Aksi</th></tr></thead>
<tbody>@forelse($siswas as $s)@php($a=$s->latest_attempt)
<tr><td>{{ $s->username }}</td><td>{{ $s->kelompok }}</td><td>{{ $a->status ?? 'belum mulai' }}</td><td>{{ $a->benar ?? '-' }}</td><td>{{ $a->salah ?? '-' }}</td><td>{{ $a->skor ?? '-' }}</td><td>{{ $a->pengulangan ?? '-' }}</td><td><a href="{{ route('guru.detail',$s->id) }}" style="color:#8B0000">Detail</a></td></tr>
@empty<tr><td colspan="8">Belum ada siswa.</td></tr>@endforelse</tbody></table>
</div>
<div style="margin-top:.6rem">{{ $siswas->links() }}</div>
</div>
@endsection
