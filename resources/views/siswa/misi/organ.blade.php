@extends('layouts.app')
@section('title','Materi: Perjalanan Mi di Dalam Tubuh')
@section('content')
<p class="stamp">BERKAS 3/5 • MATERI: PERJALANAN MI DI DALAM TUBUH</p>
<div class="paper-card torn" style="margin-top:.6rem">
<p style="font-size:.88rem"><b>NUTRI:</b> "Satu gambar manusia utuh. Klik 6 titik bernomor berurutan — tiap titik membuka penjelasan singkat. Tombol selesai aktif setelah semua titik dibuka."</p>
<div class="human-wrap">
<div class="human-stage">
<img src="{{ asset('images/detektif/tubuh-manusia.svg') }}" alt="Satu gambar manusia utuh interaktif 2D berwarna" style="width:100%;border-radius:8px">
@foreach($materi as $i => $o)
<button type="button" class="hotspot" style="left:50%;top:{{ [13,28,44,58,72,86][$i] }}%" data-i="{{ $i }}">{{ $i+1 }}</button>
@endforeach
</div>
<div><div id="organInfo" class="paper-card grid-paper" style="box-shadow:none"><b>Klik titik 1 untuk mulai membaca.</b></div></div>
</div>
</div>
<div style="display:grid;gap:.6rem;margin-top:.8rem" class="cards">
@foreach($materi as $i => $o)
<div class="paper-card" id="kartu-{{ $i }}"><h4 class="serif" style="margin:0">{{ $i+1 }}. {{ $o['nama'] }}</h4><p style="font-size:.88rem">{{ $o['singkat'] }}</p></div>
@endforeach
</div>
<form method="POST" action="{{ route('siswa.misi.organ.simpan') }}" style="margin-top:.8rem">
@csrf
<button class="btn-pin btn-crimson" id="btnSelesai" disabled>Tandai Materi Selesai Dibaca</button>
<a class="btn-pin" href="{{ route('siswa.misi.hub') }}">Kembali</a>
</form>
@push('scripts')
<script>
const materi = @json($materi);
let dibuka = new Set();
document.querySelectorAll('.hotspot').forEach(b=>b.addEventListener('click',()=>{
 const i=+b.dataset.i;
 document.getElementById('organInfo').innerHTML=`<h4 class="serif" style="margin:0">${i+1}. ${materi[i].nama}</h4><p style="font-size:.88rem">${materi[i].singkat}</p>`;
 b.classList.add('active'); dibuka.add(i);
 document.getElementById('kartu-'+i).scrollIntoView({behavior:'smooth',block:'nearest'});
 if(dibuka.size>=materi.length){document.getElementById('btnSelesai').disabled=false;}
}));
</script>
@endpush
@endsection
