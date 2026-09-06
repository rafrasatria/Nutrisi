@extends('layouts.app')
@section('title','Papan Misi — Detektif Nutrisi')
@section('content')
<p class="stamp">PAPAN MISI • 5 BERKAS WAJIB SEBELUM REFLEKSI</p>
<div class="paper-card torn" style="margin-top:.6rem">
<div style="display:flex;gap:.7rem;align-items:center">
<div class="avatar"><img src="{{ asset('images/detektif/maskot-nutri.svg') }}" alt="Maskot Nutri"></div>
<p style="font-size:.88rem;margin:0"><b>NUTRI:</b> "Selesaikan kelima berkas ini berurutan. Tiap berkas <b>dinilai server</b> dan nilainya masuk rekap + PDF. Refleksi terkunci sampai semua selesai."</p>
</div>
</div>
@php
$kartu = [
  ['rute'=>'siswa.misi.lab-mi','judul'=>'1) Lab Bukti: Perbandingan Mi','desc'=>'Catat 10 angka label gizi Mi A vs Mi B, jawab 3 pertanyaan bandingan. Dinilai 0–100.','img'=>'mi-a.svg','misi'=>'lab-mi'],
  ['rute'=>'siswa.misi.match','judul'=>'2) Kuis Mencocokkan Nutrien','desc'=>'Pasangkan 4 nutrien dengan fungsinya lewat dropdown. Dinilai per pasangan (25 poin).','img'=>'sayur.svg','misi'=>'match'],
  ['rute'=>'siswa.misi.organ','judul'=>'3) Materi: Perjalanan Mi di Dalam Tubuh','desc'=>'1 gambar manusia utuh interaktif: klik 6 titik organ, baca penjelasan singkat, tandai selesai.','img'=>'tubuh-manusia.svg','misi'=>'organ-materi'],
  ['rute'=>'siswa.misi.organ-kuis','judul'=>'4) Kuis Organ Pencernaan','desc'=>'4 pilihan ganda tentang fungsi tiap organ. Terbuka baik setelah baca materi.','img'=>'buah.svg','misi'=>'organ-kuis'],
  ['rute'=>'siswa.misi.jalur','judul'=>'5) Kuis Susun Jalur Pencernaan','desc'=>'Susun 6 organ sesuai perjalanan makanan. Dinilai per posisi tepat.','img'=>'minuman.svg','misi'=>'jalur'],
];
@endphp
<div style="display:grid;gap:.8rem;margin-top:.8rem" class="cards">
@foreach($kartu as $k)
<div class="paper-card">
<img src="{{ asset('images/detektif/'.$k['img']) }}" alt="Ilustrasi {{ $k['judul'] }}" style="width:100%;height:120px;object-fit:contain;background:#f4ecd8;border-radius:8px">
<h4 class="serif" style="margin:.5rem 0 .2rem">{{ $k['judul'] }}</h4>
<p style="font-size:.85rem;margin:0 0 .5rem">{{ $k['desc'] }}</p>
<p style="font-size:.82rem">Status: @if(is_null($status[$k['misi']]))<b style="color:#A71D2A">Belum dikerjakan</b>@else<b style="color:#2E7D32">Selesai — skor {{ $status[$k['misi']] }}</b>@endif</p>
<a class="btn-pin @if(is_null($status[$k['misi']])) btn-crimson @endif" href="{{ route($k['rute']) }}">@if(is_null($status[$k['misi']])) Kerjakan @else Ulangi / Lihat @endif</a>
</div>
@endforeach
</div>
<div style="margin-top:.8rem"><a class="btn-pin btn-gold" href="{{ route('siswa.refleksi') }}">Lanjut ke Refleksi →</a></div>
@endsection
