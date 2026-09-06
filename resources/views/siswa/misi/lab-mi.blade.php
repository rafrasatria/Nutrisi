@extends('layouts.app')
@section('title','Lab Bukti: Perbandingan Mi')
@section('content')
<p class="stamp">BERKAS 1/5 • LAB BUKTI: DUA MI, APAKAH SAMA?</p>
<div class="paper-card torn" style="margin-top:.6rem">
<div style="display:grid;gap:.8rem" class="human-wrap">
<div>
<img src="{{ asset('images/detektif/mi-a.svg') }}" alt="Ilustrasi 2D mangkuk Mi A" style="width:100%;border-radius:8px;background:#f4ecd8">
<img src="{{ asset('images/detektif/mi-b.svg') }}" alt="Ilustrasi 2D mangkuk Mi B" style="width:100%;border-radius:8px;background:#f4ecd8;margin-top:.5rem">
</div>
<div>
<p style="font-size:.88rem"><b>NUTRI:</b> "Salin angka label gizi dari dua kemasan kelompokmu, lalu jawab 3 pertanyaan bandingan. Kunci dinilai dari <b>angkamu sendiri</b> — kalau serinya (sama besar), A/B dua-duanya diterima."</p>
@if($saved)<p style="font-size:.85rem">Nilai tersimpan: <b>{{ $saved->benar }}/{{ $saved->total }}</b> (skor {{ $saved->skor }}) — mengulang akan menimpa nilai.</p>@endif
<form method="POST" action="{{ route('siswa.misi.lab-mi.simpan') }}">
@csrf
<table class="table-evidence"><tr><th></th><th>Mi A</th><th>Mi B</th></tr>
<tr><td>Energi (kkal)</td><td><input type="number" step="any" min="0" name="mi_a_energi" value="{{ old('mi_a_energi', $saved->data['input']['mi_a_energi'] ?? '') }}" required></td><td><input type="number" step="any" min="0" name="mi_b_energi" value="{{ old('mi_b_energi', $saved->data['input']['mi_b_energi'] ?? '') }}" required></td></tr>
<tr><td>Karbohidrat (g)</td><td><input type="number" step="any" min="0" name="mi_a_karbo" value="{{ old('mi_a_karbo', $saved->data['input']['mi_a_karbo'] ?? '') }}" required></td><td><input type="number" step="any" min="0" name="mi_b_karbo" value="{{ old('mi_b_karbo', $saved->data['input']['mi_b_karbo'] ?? '') }}" required></td></tr>
<tr><td>Protein (g)</td><td><input type="number" step="any" min="0" name="mi_a_protein" value="{{ old('mi_a_protein', $saved->data['input']['mi_a_protein'] ?? '') }}" required></td><td><input type="number" step="any" min="0" name="mi_b_protein" value="{{ old('mi_b_protein', $saved->data['input']['mi_b_protein'] ?? '') }}" required></td></tr>
<tr><td>Lemak (g)</td><td><input type="number" step="any" min="0" name="mi_a_lemak" value="{{ old('mi_a_lemak', $saved->data['input']['mi_a_lemak'] ?? '') }}" required></td><td><input type="number" step="any" min="0" name="mi_b_lemak" value="{{ old('mi_b_lemak', $saved->data['input']['mi_b_lemak'] ?? '') }}" required></td></tr>
<tr><td>Natrium (mg)</td><td><input type="number" step="any" min="0" name="mi_a_natrium" value="{{ old('mi_a_natrium', $saved->data['input']['mi_a_natrium'] ?? '') }}" required></td><td><input type="number" step="any" min="0" name="mi_b_natrium" value="{{ old('mi_b_natrium', $saved->data['input']['mi_b_natrium'] ?? '') }}" required></td></tr>
</table>
<h4 class="serif">Pertanyaan bandingan (dinilai)</h4>
<label class="lbl">1. Mi mana yang proteinnya lebih tinggi?<select name="q_protein" required><option value="">-- pilih --</option><option value="A">Mi A</option><option value="B">Mi B</option></select></label>
<label class="lbl">2. Mi mana yang natriumnya lebih tinggi (lebih perlu dibatasi)?<select name="q_natrium" required><option value="">-- pilih --</option><option value="A">Mi A</option><option value="B">Mi B</option></select></label>
<label class="lbl">3. Mi mana yang energinya lebih tinggi?<select name="q_energi" required><option value="">-- pilih --</option><option value="A">Mi A</option><option value="B">Mi B</option></select></label>
<button class="btn-pin btn-crimson">Kumpulkan & Nilai</button>
<a class="btn-pin" href="{{ route('siswa.misi.hub') }}">Kembali</a>
</form>
</div>
</div>
</div>
@endsection
