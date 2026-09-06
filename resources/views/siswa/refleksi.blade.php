@extends('layouts.app')
@section('title','Tutup Kasus — Kesimpulan & Refleksi')
@section('content')
<p class="stamp">TUTUP KASUS • KESIMPULAN & REFLEKSI (WAJIB)</p>
<div class="paper-card torn" style="margin-top:.6rem">
<div style="display:flex;gap:.7rem;align-items:center"><div class="avatar"><img src="{{ asset('images/detektif/maskot-nutri.svg') }}" alt="Maskot"></div>
<p style="font-size:.88rem;margin:0"><b>NUTRI:</b> "Semua 7 soal + 5 berkas misi sudah beres — tinggalkan jejak akhirmu di sini. Sistem menggabungkan 4 bagian kesimpulan + 3 refleksi ke 2 kolom nilai agar fitur lama tetap jalan."</p></div>
<form method="POST" action="{{ route('siswa.refleksi.simpan') }}" id="fRef">
@csrf
<h4 class="serif">A. Kesimpulan Kasus (4 petunjuk)</h4>
<label class="lbl">1. Mi yang kami amati mengandung...<textarea id="c1" rows="2" placeholder="zat gizi yang ditemukan"></textarea></label>
<label class="lbl">2. Nutrien itu berfungsi...<textarea id="c2" rows="2" placeholder="fungsi bagi tubuh"></textarea></label>
<label class="lbl">3. Setelah dimakan, makanan melewati...<textarea id="c3" rows="2" placeholder="urutan organ"></textarea></label>
<label class="lbl">4. Jadi, menurut kelompok kami...<textarea id="c4" rows="2" placeholder="simpulan: apakah mi cukup gizinya?"></textarea></label>
<h4 class="serif">B. Jurnal Refleksi (3 pertanyaan)</h4>
<label class="lbl">1. Hal baru yang kupelajari hari ini...<textarea id="r1" rows="2"></textarea></label>
<label class="lbl">2. Yang sekarang kupahami tentang nutrien & pencernaan...<textarea id="r2" rows="2"></textarea></label>
<label class="lbl">3. Yang masih membuatku penasaran...<textarea id="r3" rows="2"></textarea></label>
<input type="hidden" name="kesimpulan" id="hKes"><input type="hidden" name="refleksi" id="hRef">
<button class="btn-pin btn-crimson">Selesaikan Misi</button>
</form>
</div>
@push('scripts')
<script>
document.getElementById('fRef').addEventListener('submit',e=>{
 const c=[1,2,3,4].map(i=>document.getElementById('c'+i).value.trim());
 const r=[1,2,3].map(i=>document.getElementById('r'+i).value.trim());
 const hk='[1] Kandungan: '+(c[0]||'-')+' | [2] Fungsi: '+(c[1]||'-')+' | [3] Jalur: '+(c[2]||'-')+' | [4] Simpulan: '+(c[3]||'-');
 const hr='[1] Hal baru: '+(r[0]||'-')+' | [2] Pemahaman: '+(r[1]||'-')+' | [3] Penasaran: '+(r[2]||'-');
 if(hk.length<20||hr.length<20){e.preventDefault();alert('Isi semua bagian dengan lengkap (min. 20 karakter total tiap kolom).');return;}
 document.getElementById('hKes').value=hk;document.getElementById('hRef').value=hr;
});
</script>
@endpush
@endsection
