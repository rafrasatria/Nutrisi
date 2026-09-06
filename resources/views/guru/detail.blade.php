@extends('layouts.app')
@section('title', 'Detail Siswa')
@section('content')
<div class="bg-white rounded shadow p-6 mb-4">
<a href="{{ route('guru.dashboard') }}" class="text-sm text-blue-700 underline">← Kembali</a>
<h2 class="text-xl font-bold mt-2">{{ $siswa->username }} — {{ $kelas->nama }} / {{ $siswa->kelompok }}</h2>
@if(!$attempt)
<p class="text-sm mt-2">Belum mulai kuis.</p>
@else
<p class="text-sm mt-2">Status: <b>{{ $attempt->status }}</b> | Benar: {{ $attempt->benar }} | Salah: {{ $attempt->salah }} | Skor: {{ $attempt->skor }} | Pengulangan: {{ $attempt->pengulangan }}</p>
@endif
</div>
@if($attempt)
<div class="bg-white rounded shadow p-6 mb-4">
<h3 class="font-bold mb-2">Nilai Misi Detektif</h3>
<table class="w-full text-sm"><thead><tr class="bg-gray-100"><th class="p-2 text-left">Berkas</th><th class="p-2">Benar</th><th class="p-2">Skor</th></tr></thead>
<tbody>
@foreach(\App\Models\MisiResult::DAFTAR as $m)
<tr class="border-t"><td class="p-2">{{ \App\Models\MisiResult::label($m) }}</td><td class="p-2">@if(isset($misi[$m])){{ $misi[$m]->benar }}/{{ $misi[$m]->total }}@else - @endif</td><td class="p-2">@if(isset($misi[$m])){{ $misi[$m]->skor }}@else - @endif</td></tr>
@endforeach
</tbody></table>
</div>
<div class="bg-white rounded shadow p-6 mb-4">
<h3 class="font-bold mb-2">Jawaban</h3>
@foreach($detail as $i => $d)
<div class="border-b py-2 text-sm">
<p><b>{{ $i+1 }}.</b> {{ $d['question']->teks_soal }}</p>
<p>Jawaban: {{ $d['answer']->option->teks_opsi ?? '-' }} {!! $d['answer'] ? ($d['answer']->is_correct ? '<b class="text-green-700">✔</b>' : '<b class="text-red-600">✘</b>') : '' !!}</p>
</div>
@endforeach
</div>
<div class="bg-white rounded shadow p-6">
<p class="text-sm"><b>Kesimpulan:</b> {{ $attempt->kesimpulan ?? '-' }}</p>
<p class="text-sm mt-2"><b>Refleksi:</b> {{ $attempt->refleksi ?? '-' }}</p>
</div>
@endif
@endsection
