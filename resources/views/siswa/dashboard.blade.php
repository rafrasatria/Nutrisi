@extends('layouts.app')
@section('title','Berkas Kasus — Dashboard Siswa')
@section('content')
<p class="stamp">BERKAS KASUS • {{ $user->username }}</p>
<div class="paper-card torn" style="margin-top:.6rem">
<p style="font-size:.88rem;margin:0">Kelas: <b>{{ $user->kelas->nama ?? '-' }}</b> | Kelompok: <b>{{ $user->kelompok }}</b></p>
<div style="margin-top:.6rem">
@if(!$attempt)<p>Belum ada penyelidikan. Buka kasus pertamamu!</p><a class="btn-pin btn-crimson" href="{{ route('siswa.kuis') }}">Mulai Penyelidikan</a>
@elseif($attempt->status!=='selesai')<p>Status: <b>Sedang mengerjakan</b> ({{ $attempt->answers()->count() }}/{{ $questions->count() }} soal)</p><a class="btn-pin btn-crimson" href="{{ route('siswa.kuis') }}">Lanjutkan Interogasi</a>
@else
<div class="cert" style="text-align:left"><span class="stamp">KASUS SELESAI</span>
<p style="font-size:.85rem;margin:.3rem 0">Status: Selesai</p>
<p style="font-size:1.4rem" class="serif"><b>Sertifikat Detektif Nutrisi</b> — {{ $user->username }}</p>
<p>Benar: <b style="color:#2E7D32">{{ $attempt->benar }}</b> | Salah: <b style="color:#A71D2A">{{ $attempt->salah }}</b> | Skor: <b>{{ $attempt->skor }}</b> | Pengulangan soal gambar: <b>{{ $attempt->pengulangan }}x</b></p>
<a class="btn-pin" href="{{ route('siswa.pdf') }}">Download PDF Hasil</a></div>
@endif
</div>
</div>
@if($attempt)
<div class="desk" style="margin-top:1rem"><p class="stamp">PAPAN MISI • NILAI BERKAS DETEKTIF</p>
<table class="table-evidence" style="background:#FBF6E8;color:#241a12;margin-top:.6rem">
<tr><th>Berkas Misi</th><th>Benar</th><th>Skor</th></tr>
@foreach(\App\Models\MisiResult::DAFTAR as $m)
<tr><td style="text-align:left">{{ \App\Models\MisiResult::label($m) }}</td>
<td>@if(isset($misi[$m])){{ $misi[$m]->benar }}/{{ $misi[$m]->total }}@else — @endif</td>
<td>@if(isset($misi[$m])){{ $misi[$m]->skor }}@else — @endif</td></tr>
@endforeach
</table>
<div style="margin-top:.5rem"><a class="btn-pin" href="{{ route('siswa.misi.hub') }}">Buka Papan Misi</a></div>
</div>
<div class="desk" style="margin-top:1rem"><p class="stamp">REKAP HASIL • BENAR / SALAH PER SOAL</p>
@foreach($detail as $i=>$d)
<div class="bubble" style="max-width:none;margin-top:.6rem"><b>{{ $i+1 }}.</b> {{ $d['question']->teks_soal }}<br>
Jawabanmu: <b>{{ $d['answer']->option->teks_opsi ?? '—' }}</b>
@if($d['answer']) @if($d['answer']->is_correct)<span style="color:#2E7D32;font-weight:800">BENAR</span>@else<span style="color:#A71D2A;font-weight:800">SALAH</span> • Kunci: {{ $d['correctOption']->teks_opsi ?? '-' }}@endif
@else <span style="color:#888">Belum dijawab</span>@endif</div>
@endforeach
</div>
@if($attempt->status==='selesai')
<div class="paper-card" style="margin-top:1rem"><h4 class="serif" style="margin:0">Kesimpulan</h4><p style="font-size:.9rem">{{ $attempt->kesimpulan }}</p><h4 class="serif" style="margin:.6rem 0 0">Refleksi</h4><p style="font-size:.9rem">{{ $attempt->refleksi }}</p></div>
@endif
@endif
@endsection
