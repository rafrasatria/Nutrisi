<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Detektif Nutrisi — Pecahkan Kasus Makanan & Minuman</title>
<link rel="stylesheet" href="{{ asset('css/detektif.css') }}">
<style>
.hero-grid{display:grid;gap:1rem}@media(min-width:900px){.hero-grid{grid-template-columns:1.2fr .8fr}}
.clip{position:relative}.clip img{width:100%;height:150px;object-fit:cover;display:block;border:6px solid #fff}
.tape{position:absolute;top:-10px;left:50%;transform:translateX(-50%) rotate(-3deg);background:rgba(226,213,195,.9);padding:.1rem 1rem;font-family:Consolas,monospace;font-size:.7rem;color:#5a4a30}
.eye{position:absolute;right:8px;top:40%;width:74px;height:74px;border-radius:50%;background:radial-gradient(circle,#ffd75e 18%,#c96a1e 32%,#1a0d05 55%);box-shadow:0 0 24px #ffb300;animation:blink 5s infinite}
@keyframes blink{0%,92%,100%{transform:scaleY(1)}95%{transform:scaleY(.1)}}
@media(max-width:640px){.eye{display:none}}
.two{display:grid;gap:1rem}@media(min-width:900px){.two{grid-template-columns:1fr 1fr}}
</style>
</head>
<body class="detektif">
<nav class="topnav"><div class="topnav-inner">
<span class="brand-case">DETEKTIF NUTRISI<small>CASE FILE #01 • MIE & GIZI SEIMBANG</small></span>
<div class="navlinks"><a href="#papan">Papan Kasus</a><a href="#catatan">Catatan</a><a href="#dossier">Dossier</a><a href="#galeri">Galeri Bukti</a><a href="{{ route('siswa.login') }}">Masuk</a><a href="{{ route('siswa.register') }}" class="kbd">Daftar</a></div>
</div></nav>
<div class="wrap">

<!-- SEKSI 1: HERO / PAPAN INVESTIGASI -->
<section id="papan" class="board" style="margin-top:1rem">
<span class="pin" style="left:12%;top:12px"></span><span class="pin" style="left:50%;top:8px"></span><span class="pin" style="right:12%;top:12px"></span>
<div class="eye" title="Mata mengawasi"></div>
<p class="stamp">BUKA KASUS • NUTRISI MAKANAN & MINUMAN</p>
<div class="hero-grid" style="margin-top:1rem">
<div class="paper-card torn" style="transform:rotate(-1deg)">
<p class="type" style="font-size:.72rem;letter-spacing:2px;color:#8B0000">DAILY NEWS • EDISI GIZI</p>
<h1 class="serif" style="font-size:2.1rem;margin:.3rem 0">Apakah Mi Instan Memberi Nutrisi yang Dibutuhkan Tubuh?</h1>
<p style="font-size:.95rem">Seorang anak pulang sekolah dalam keadaan lapar. Pilihannya jatuh pada mi instan yang praktis. Tugasmu sebagai detektif: kumpulkan bukti dari <b>label gizi</b>, cocokkan <b>nutrien & fungsinya</b>, dan ikuti <b>perjalanan mi di dalam tubuh manusia</b>.</p>
<div style="display:flex;gap:.6rem;flex-wrap:wrap;margin-top:.8rem">
<a class="btn-pin btn-crimson" href="{{ route('siswa.register') }}">Mulai Penyelidikan</a>
<a class="btn-pin" href="{{ route('guru.login') }}">Ruang Guru</a>
</div>
<p class="type" style="font-size:.72rem;margin-top:.7rem">7 soal • 3 tahap • gate soal gambar • kesimpulan + refleksi • PDF</p>
</div>
<div style="display:grid;gap:.8rem">
<div class="clip"><span class="tape">BUKTI A</span><img src="{{ asset('images/landing/hero-salad.jpg') }}" alt="Mangkuk salad sehat"><span class="pin" style="left:50%;top:-6px"></span></div>
<div class="clip"><span class="tape">BUKTI B</span><img src="{{ asset('soal/isi-piringku.jpg') }}" alt="Poster Isi Piringku Kemenkes"><span class="pin" style="left:50%;top:-6px"></span></div>
</div>
</div>
</section>

<!-- SEKSI 2: CATATAN INVESTIGASI / CHAT LOG -->
<section id="catatan" style="margin-top:1.4rem" class="desk">
<p class="stamp">CATATAN INVESTIGASI • REKAMAN PERCAKAPAN</p>
<div class="chat">
<div class="chat-row"><div class="avatar"><img src="{{ asset('images/detektif/maskot-nutri.svg') }}" alt="Nutri maskot detektif"></div>
<div class="bubble"><b>NUTRI:</b> "Halo, calon detektif! Ada kasus makanan yang perlu dipecahkan. Kenyang saja tidak cukup — kita butuh bukti gizinya."<br><span class="type" style="font-size:.72rem">sub-judul: Sapaan Maskot • gambar: ilustrasi 2D detektif Nutri</span></div>
<button class="look" onclick="document.getElementById('more1').scrollIntoView({behavior:'smooth'})">Lihat</button></div>
<div class="chat-row"><div class="avatar"><img src="{{ asset('images/detektif/mi-a.svg') }}" alt="Ilustrasi 2D mi instan mangkuk A"></div>
<div class="bubble"><b>Saksi Mi A vs Mi B:</b> "Kami sama-sama mi instan, tapi label giziku berbeda! Bandingkan energi, karbohidrat, protein, lemak, dan natriumku."<br><span class="type" style="font-size:.72rem">sub-judul: Dua Mi, Apakah Sama • gambar: dua mangkuk mi 2D berwarna</span></div></div>
<div class="chat-row" id="more1"><div class="avatar"><img src="{{ asset('images/detektif/tubuh-manusia.svg') }}" alt="Ilustrasi 2D tubuh manusia utuh"></div>
<div class="bubble"><b>Jejak Tubuh:</b> "Ikuti aku! Klik titik merah pada <b>satu gambar manusia utuh yang interaktif</b> — dari mulut sampai anus — tiap titik memberi penjelasan singkat yang mudah dipahami."<br><span class="type" style="font-size:.72rem">sub-judul: Perjalanan Mi di Dalam Tubuh • gambar: 1 manusia utuh interaktif</span></div>
<a class="look" href="{{ route('siswa.register') }}">Ikut</a></div>
</div>
</section>

<!-- SEKSI 3: DOSSIER KARAKTER & MEJA BUKTI -->
<section id="dossier" style="margin-top:1.4rem" class="desk">
<p class="stamp">DOSSIER DETEKTIF • MEJA BUKTI</p>
<div class="two" style="margin-top:1rem">
<div class="paper-card grid-paper">
<h3 class="serif" style="margin:0">Atribut Penyelidik Gizi</h3>
<table class="table-evidence" style="margin-top:.6rem">
<tr><th>Stat</th><th>Nilai</th><th>Arti di Misi Ini</th></tr>
<tr><td>Energi</td><td>Karbohidrat</td><td>Sumber tenaga utama</td></tr>
<tr><td>Tumbuh</td><td>Protein</td><td>Perbaikan jaringan</td></tr>
<tr><td>Cadangan</td><td>Lemak</td><td>Energi jangka panjang</td></tr>
<tr><td>Cairan</td><td>Natrium</td><td>Keseimbangan, batasi!</td></tr>
<tr><td>Penyerapan</td><td>Usus Halus</td><td>Sari makanan ke darah</td></tr>
</table>
<p class="type" style="font-size:.72rem">Kertas grid usang • cocokkan nutrien-fungsi di Tahap belajar • susun jalur pencernaan</p>
</div>
<div>
<div class="polaroid"><img src="{{ asset('images/landing/buah-segar.jpg') }}" alt="Foto buah segar"><p class="serif" style="text-align:center;margin:.4rem 0 0">Tersangka Sehat: Buah & Sayur — serat + vitamin</p></div>
<p class="type" style="font-size:.75rem;margin-top:.6rem">Props meja (dekoratif): asbak, kompas, klip kertas, penggaris, sticky notes, kaca pembesar. Semua bukti mengarah ke <b>Isi Piringku Kemenkes</b>.</p>
</div>
</div>
</section>

<!-- SEKSI 4: GALERI CAROUSEL -->
<section id="galeri" style="margin-top:1.4rem">
<p class="stamp">GALERI BUKTI • GESER UNTUK MELIHAT</p>
<div class="carousel" style="margin-top:.8rem">
<button class="arrow" onclick="geser(-1)">‹</button>
<div class="cards" id="kartu">
<div class="poster"><img src="{{ asset('images/detektif/sayur.svg') }}" alt="Ilustrasi 2D sayuran hijau"><div class="cap">BUKTI 01 — Sayur & Serat</div></div>
<div class="poster active"><img src="{{ asset('soal/isi-piringku.jpg') }}" alt="Poster Isi Piringku"><div class="cap">BUKTI KUNCI — Isi Piringku: ½ sayur-buah, ¼ lauk, ¼ pokok</div></div>
<div class="poster"><img src="{{ asset('images/detektif/minuman.svg') }}" alt="Ilustrasi 2D minuman sehat"><div class="cap">BUKTI 03 — Air Putih vs Soda</div></div>
</div>
<button class="arrow" onclick="geser(1)">›</button>
</div>
<div class="two" style="margin-top:1rem">
<div class="paper-card"><h3 class="serif" style="margin:0">Cara Mulai</h3><p style="font-size:.9rem">1) Daftar (username, password, kelas, kelompok) → 2) Kerjakan 7 soal berurutan (gambar harus benar) → 3) Ikuti misi belajar: banding mi, cocokkan nutrien, jelajahi tubuh, susun jalur → 4) Isi kesimpulan + refleksi → 5) Download PDF.</p></div>
<div class="paper-card"><h3 class="serif" style="margin:0">Akun Contoh</h3><p style="font-size:.9rem">Siswa: <span class="kbd">siswa1 / siswa123</span><br>Guru: pilih kelas + <span class="kbd">guru123</span></p><div style="display:flex;gap:.6rem;flex-wrap:wrap"><a class="btn-pin btn-crimson" href="{{ route('siswa.register') }}">Daftar Siswa</a><a class="btn-pin" href="{{ route('siswa.login') }}">Login</a></div></div>
</div>
</section>

<footer class="type" style="font-size:.72rem;opacity:.7;margin-top:1.5rem;text-align:center">© 2026 Detektif Nutrisi • Ilustrasi 2D lokal (tanpa emoji) • Foto: Wikimedia Commons • Poster: Kemenkes RI</footer>
</div>
<script>
let idx=1;function geser(d){const c=document.querySelectorAll('#kartu .poster');c[idx].classList.remove('active');idx=(idx+d+c.length)%c.length;c[idx].classList.add('active');}
</script>
</body>
</html>
