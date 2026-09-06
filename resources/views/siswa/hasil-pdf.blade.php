<!DOCTYPE html>
<html><head><meta charset="utf-8"><style>body{font-family:sans-serif;font-size:12px}h1{font-size:18px}table{width:100%;border-collapse:collapse;margin-top:10px}th,td{border:1px solid #000;padding:5px;text-align:left}</style></head>
<body>
<h1>Hasil Kuis Nutrisi Makanan dan Minuman</h1>
<p>Username: {{ $user->username }} | Kelas: {{ $user->kelas->nama ?? '-' }} | Kelompok: {{ $user->kelompok }} | Selesai: {{ $attempt->finished_at }}</p>
<p>Total soal: {{ count($detail) }} | Benar: {{ $attempt->benar }} | Salah: {{ $attempt->salah }} | Skor: {{ $attempt->skor }}</p>
<h2>Nilai Misi Detektif</h2>
<table><thead><tr><th>Berkas Misi</th><th>Benar</th><th>Skor</th></tr></thead>
<tbody>
@foreach(\App\Models\MisiResult::DAFTAR as $m)
<tr><td>{{ \App\Models\MisiResult::label($m) }}</td><td>@if(isset($misi[$m])){{ $misi[$m]->benar }}/{{ $misi[$m]->total }}@else - @endif</td><td>@if(isset($misi[$m])){{ $misi[$m]->skor }}@else - @endif</td></tr>
@endforeach
</tbody></table>
<table><thead><tr><th>No</th><th>Soal</th><th>Jawaban Siswa</th><th>Status</th><th>Kunci</th></tr></thead>
<tbody>
@foreach($detail as $i => $d)
<tr><td>{{ $i+1 }}</td><td>{{ $d['question']->teks_soal }}</td><td>{{ $d['answer']->option->teks_opsi ?? '-' }}</td><td>{{ $d['answer'] && $d['answer']->is_correct ? 'Benar' : 'Salah' }}</td><td>{{ $d['correctOption']->teks_opsi ?? '-' }}</td></tr>
@endforeach
</tbody></table>
<p><b>Kesimpulan:</b> {{ $attempt->kesimpulan }}</p>
<p><b>Refleksi metode pembelajaran:</b> {{ $attempt->refleksi }}</p>
</body></html>
