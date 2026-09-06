<!DOCTYPE html>
<html><head><meta charset="utf-8"><style>body{font-family:sans-serif;font-size:12px}h1{font-size:18px}h2{font-size:14px;margin-top:16px}table{width:100%;border-collapse:collapse;margin-top:10px}th,td{border:1px solid #000;padding:5px;text-align:left}</style></head>
<body>
<h1>Rekap Hasil Kuis Nutrisi — Kelas {{ $kelas->nama }}</h1>
<p>Dicetak: {{ now()->format('d-m-Y H:i') }} | Jumlah siswa: {{ $siswas->count() }}</p>
<table><thead><tr><th>No</th><th>Username</th><th>Kelompok</th><th>Status</th><th>Benar</th><th>Salah</th><th>Skor</th><th>Pengulangan</th><th>Selesai</th></tr></thead>
<tbody>
@foreach($siswas as $i => $s)
@php($a = $s->latest_attempt)
<tr><td>{{ $i+1 }}</td><td>{{ $s->username }}</td><td>{{ $s->kelompok }}</td><td>{{ $a->status ?? 'belum mulai' }}</td><td>{{ $a->benar ?? '-' }}</td><td>{{ $a->salah ?? '-' }}</td><td>{{ $a->skor ?? '-' }}</td><td>{{ $a->pengulangan ?? '-' }}</td><td>{{ $a->finished_at ?? '-' }}</td></tr>
@endforeach
</tbody></table>
<h2>Nilai Misi per Siswa</h2>
<table><thead><tr><th>Username</th>@foreach(\App\Models\MisiResult::DAFTAR as $m)<th>{{ \App\Models\MisiResult::label($m) }}</th>@endforeach</tr></thead>
<tbody>
@foreach($siswas as $s)
@php($a = $s->latest_attempt)
@php($mm = $a ? $a->misiResults->keyBy('misi') : collect())
<tr><td>{{ $s->username }}</td>@foreach(\App\Models\MisiResult::DAFTAR as $m)<td>@if(isset($mm[$m])){{ $mm[$m]->benar }}/{{ $mm[$m]->total }} ({{ $mm[$m]->skor }})@else - @endif</td>@endforeach</tr>
@endforeach
</tbody></table>
<h2>Kesimpulan & Refleksi Siswa (yang sudah selesai)</h2>
@foreach($siswas as $s)
@php($a = $s->latest_attempt)
@if($a && $a->status === 'selesai')
<p><b>{{ $s->username }} ({{ $s->kelompok }})</b><br>Kesimpulan: {{ $a->kesimpulan }}<br>Refleksi: {{ $a->refleksi }}</p>
@endif
@endforeach
</body></html>
