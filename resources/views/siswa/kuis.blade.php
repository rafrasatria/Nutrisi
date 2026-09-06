@extends('layouts.app')
@section('title','Interogasi Bukti — Soal '.($answeredCount+1))
@section('content')
<p class="stamp">INTEROGASI BUKTI • TAHAP {{ $question->tahap }} • SOAL {{ $answeredCount+1 }}/{{ $total }}</p>
<div class="prog" style="margin:.6rem 0"><div style="width:{{ $progress }}%"></div></div>

<div class="paper-card torn">
<div style="display:flex;gap:.7rem;align-items:center">
<div class="avatar"><img src="{{ asset('images/detektif/maskot-nutri.svg') }}" alt="Maskot Nutri"></div>
<p style="font-size:.88rem;margin:0">@if($question->tahap==='C')<b>NUTRI:</b> "Soal kunci! Jawaban harus benar baru boleh lanjut. Pengulangan: {{ $attempt->pengulangan }}x. Perhatikan poster Isi Piringku."@elseif($question->tahap==='B')<b>NUTRI:</b> "Soal cerita saksi. Baca kronologi Andi baik-baik."@else<b>NUTRI:</b> "Kumpulkan bukti satu per satu, Detektif. Tidak boleh loncat!"@endif</p>
</div>
<hr style="border:none;border-top:1px dashed #C9B48A;margin:.7rem 0">
<p class="serif" style="font-size:1.05rem;font-weight:700">{{ $question->teks_soal }}</p>
@if($question->gambar_path)
<div style="background:#eef4f6;border:1px solid #3B6E75;border-radius:8px;padding:.6rem;margin:.6rem 0">
<p style="font-size:.8rem;margin:0 0 .4rem"><b>Gambar petunjuk (poster betulan):</b></p>
<img src="{{ asset($question->gambar_path) }}" alt="Poster Isi Piringku Kemenkes" style="max-width:420px;width:100%;display:block;margin:0 auto;border:4px solid #fff;box-shadow:2px 2px 0 #000">
<p class="type" style="font-size:.68rem;text-align:center">Sumber: Isi Piringku, Kemenkes RI</p>
</div>
@endif
<form method="POST" action="{{ route('siswa.kuis.jawab') }}">
@csrf
<input type="hidden" name="question_id" value="{{ $question->id }}">
@foreach($question->options as $opt)
<label class="qopt"><input type="radio" name="option_id" value="{{ $opt->id }}" required> {{ $opt->teks_opsi }}</label>
@endforeach
<button class="btn-pin btn-crimson" style="margin-top:.5rem">Kunci Jawaban</button>
</form>
</div>

{{-- MISI BELAJAR (metode Detektif — halaman resmi yang dinilai server) --}}
<div class="desk" style="margin-top:1rem">
<p class="stamp">MISI BELAJAR • 5 BERKAS WAJIB DINILAI (SEBELUM REFLEKSI)</p>
@php
$kartu = [
  ['rute'=>'siswa.misi.lab-mi','judul'=>'1) Lab Bukti: Perbandingan Mi','misi'=>'lab-mi'],
  ['rute'=>'siswa.misi.match','judul'=>'2) Kuis Mencocokkan Nutrien','misi'=>'match'],
  ['rute'=>'siswa.misi.organ','judul'=>'3) Materi: Perjalanan Mi','misi'=>'organ-materi'],
  ['rute'=>'siswa.misi.organ-kuis','judul'=>'4) Kuis Organ','misi'=>'organ-kuis'],
  ['rute'=>'siswa.misi.jalur','judul'=>'5) Kuis Susun Jalur','misi'=>'jalur'],
];
@endphp
<div style="display:grid;gap:.6rem;margin-top:.7rem">
@foreach($kartu as $k)
<div class="bubble" style="max-width:none;display:flex;gap:.6rem;align-items:center;flex-wrap:wrap">
<b>{{ $k['judul'] }}</b>
<span style="font-size:.8rem">@if(isset($misiStatus[$k['misi']]))<b style="color:#2E7D32">Skor {{ $misiStatus[$k['misi']] }}</b>@else<b style="color:#A71D2A">Belum</b>@endif</span>
<a class="look" href="{{ route($k['rute']) }}">Buka</a>
</div>
@endforeach
</div>
<p class="type" style="font-size:.72rem;margin-top:.6rem">Berkas bisa dibuka kapan saja, tetapi refleksi terkunci sampai kelimanya selesai. Nilai tersimpan per attempt dan bisa diulang untuk memperbaiki.</p>
</div>
@endsection
