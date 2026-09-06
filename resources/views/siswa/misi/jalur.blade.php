@extends('layouts.app')
@section('title','Kuis Susun Jalur Pencernaan')
@section('content')
<p class="stamp">BERKAS 5/5 • KUIS SUSUN JALUR PENCERNAAN</p>
<div class="paper-card torn" style="margin-top:.6rem">
<p style="font-size:.88rem"><b>NUTRI:</b> "Klik kartu organ sesuai urutan perjalanan makanan (mulut dulu!). Tiap posisi tepat dinilai. Boleh ulangi dengan tombol Ulangi."</p>
@if($saved)<p style="font-size:.85rem">Nilai tersimpan: <b>{{ $saved->benar }}/{{ $saved->total }}</b> posisi tepat (skor {{ $saved->skor }})</p>@endif
<form method="POST" action="{{ route('siswa.misi.jalur.simpan') }}" id="fJalur">
@csrf
<div class="chips" id="chipPool"></div>
<div class="seqline" id="seqLine" style="margin-top:.5rem"></div>
<div id="hiddenUrutan"></div>
<div style="margin-top:.6rem;display:flex;gap:.5rem;flex-wrap:wrap">
<button type="button" class="btn-pin" onclick="resetSeq()">Ulangi</button>
<button class="btn-pin btn-crimson">Kumpulkan & Nilai</button>
<a class="btn-pin" href="{{ route('siswa.misi.hub') }}">Kembali</a>
</div>
</form>
</div>
@push('scripts')
<script>
const acak = @json($acak);
let built = [];
function renderSeq(){
 document.getElementById('chipPool').innerHTML = acak.filter(n=>!built.includes(n)).map(n=>`<div class="chip" onclick="addSeq('${n}')">${n}</div>`).join('');
 document.getElementById('seqLine').innerHTML = built.map(n=>`<div class="chip">${n}</div>`).join('') || '<span style="font-size:.8rem">Klik organ di atas sesuai urutan…</span>';
 document.getElementById('hiddenUrutan').innerHTML = built.map(n=>`<input type="hidden" name="urutan[]" value="${n}">`).join('');
}
function addSeq(n){ if(built.length>=6) return; built.push(n); renderSeq(); }
function resetSeq(){ built=[]; renderSeq(); }
document.getElementById('fJalur').addEventListener('submit',e=>{ if(built.length!==6){ e.preventDefault(); alert('Susun keenam organ dulu sebelum dikumpulkan.'); } });
renderSeq();
</script>
@endpush
@endsection
