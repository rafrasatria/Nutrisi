@extends('layouts.app')
@section('title','Kuis Mencocokkan Nutrien')
@section('content')
<p class="stamp">BERKAS 2/5 • KUIS MENCOCOKKAN NUTRIEN–FUNGSI</p>
<div class="paper-card torn" style="margin-top:.6rem">
<div style="display:flex;gap:.7rem;align-items:center"><div class="avatar"><img src="{{ asset('images/detektif/sayur.svg') }}" alt="Ilustrasi sayur"></div>
<p style="font-size:.88rem;margin:0"><b>NUTRI:</b> "Pilih fungsi yang tepat untuk tiap nutrien. Tiap pasangan benar = 25 poin."</p></div>
@if($saved)<p style="font-size:.85rem">Nilai tersimpan: <b>{{ $saved->benar }}/{{ $saved->total }}</b> (skor {{ $saved->skor }})</p>@endif
<form method="POST" action="{{ route('siswa.misi.match.simpan') }}">
@csrf
@php $nutrien = ['Karbohidrat','Protein','Lemak','Natrium']; @endphp
@foreach($nutrien as $n)
<label class="lbl">{{ $n }} →<select name="jawab_{{ md5($n) }}" required><option value="">-- pilih fungsi --</option>@foreach($fungsiAcak as $f)<option value="{{ $f }}">{{ $f }}</option>@endforeach</select></label>
@endforeach
<button class="btn-pin btn-crimson">Kumpulkan & Nilai</button>
<a class="btn-pin" href="{{ route('siswa.misi.hub') }}">Kembali</a>
</form>
</div>
@endsection
