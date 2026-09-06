@extends('layouts.app')
@section('title','Kuis Organ Pencernaan')
@section('content')
<p class="stamp">BERKAS 4/5 • KUIS ORGAN PENCERNAAN</p>
<div class="paper-card torn" style="margin-top:.6rem">
<p style="font-size:.88rem"><b>NUTRI:</b> "Buktikan kamu paham materi perjalanan mi. 4 soal, tiap benar 25 poin. Boleh mengulang untuk memperbaiki nilai."</p>
@if($saved)<p style="font-size:.85rem">Nilai tersimpan: <b>{{ $saved->benar }}/{{ $saved->total }}</b> (skor {{ $saved->skor }})</p>@endif
<form method="POST" action="{{ route('siswa.misi.organ-kuis.simpan') }}">
@csrf
@foreach($soal as $i => $s)
<p class="serif" style="font-weight:700">{{ $i+1 }}. {{ $s['tanya'] }}</p>
@foreach($s['opsi'] as $oi => $o)
<label class="qopt"><input type="radio" name="q{{ $i }}" value="{{ $oi }}" required> {{ $o }}</label>
@endforeach
@endforeach
<button class="btn-pin btn-crimson">Kumpulkan & Nilai</button>
<a class="btn-pin" href="{{ route('siswa.misi.organ') }}">Baca Materi Lagi</a>
</form>
</div>
@endsection
