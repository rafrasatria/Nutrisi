# PRD — Website Nutrisi pada Makanan dan Minuman

> Status: Disetujui | Stack: Laravel (sudah ter-install, tanpa setup ulang) | Bahasa: Indonesia

## 1. Ringkasan Eksekutif

Website edukasi nutrisi makanan dan minuman untuk pembelajaran di kelas. Terdapat 2 kategori pengguna: **Siswa** dan **Guru**.

- **Siswa:** membuat akun & login, mengerjakan kuis wajib secara berurutan, mengisi kesimpulan & refleksi, melihat hasil + download PDF.
- **Guru:** login berbasis kelas, melihat hasil pengerjaan siswa kelasnya langsung di dashboard + download PDF rekap kelas.

## 2. Tujuan

1. Memberikan media pembelajaran interaktif tentang nutrisi makanan dan minuman.
2. Mengukur pemahaman siswa melalui kuis bertahap dengan aturan kelulusan per tahap.
3. Memberikan refleksi pembelajaran (kesimpulan + refleksi metode) sebagai bagian penilaian.
4. Memudahkan guru memantau hasil per kelas secara real-time di dashboard.

## 3. Ruang Lingkup

### In-Scope
- Auth siswa (register + login) dengan field username, password, kelas, kelompok.
- Auth guru (login saja) dengan field kelas (dropdown) + password per kelas.
- Kuis wajib 7 soal dalam 3 tahap + form kesimpulan/refleksi.
- Dashboard siswa (hasil benar/salah, kesimpulan, refleksi, download PDF).
- Dashboard guru (tabel hasil siswa satu kelas + download PDF rekap kelas).
- Notifikasi sukses/gagal untuk auth dan pengerjaan kuis.
- Data kelas tunggal yang dipakai bersama oleh siswa & guru (sama persis).

### Out-of-Scope (tidak dikerjakan sekarang)
- CMS materi / upload soal oleh guru via UI (soal di-seed oleh developer).
- Register mandiri untuk guru.
- Nilai otomatis masuk rapor / integrasi LMS.
- Multi-bahasa, forum, chat.

## 4. Persona & Hak Akses

| Aspek | Siswa | Guru |
|---|---|---|
| Akun | Register mandiri + login | Login saja, data sudah tersedia di DB |
| Cakupan data | Data milik sendiri | Hanya siswa di kelas yang sedang login |
| Kuis | Wajib kerjakan berurutan | Hanya melihat hasil |
| Kesimpulan/Refleksi | Wajib isi | Hanya membaca |
| PDF | Bisa lihat & download milik sendiri | Download PDF rekap satu kelas |
| Dashboard | Hasil pribadi | Tabel hasil seluruh siswa di kelasnya |

## 5. Kebutuhan Fungsional — Siswa

### F-S1. Register & Login Siswa

**Field register:**
- `username` — wajib, unik, min. 3 karakter (alfanumerik + underscore/titik disarankan).
- `password` — wajib, min. 6 karakter, disimpan sebagai hash.
- `kelas` — wajib, bentuk **dropdown**. Sumber data = tabel/database `kelas` yang **sama persis** dengan yang digunakan guru saat login. Tidak boleh hardcode berbeda.
- `kelompok` — wajib, field text biasa (mis. "Kelompok 1", "A", bebas).

**Field login:**
- `username` + `password`.

**Aturan & notifikasi:**
- Berhasil buat akun → redirect ke login/dashboard + toast/alert sukses "Akun berhasil dibuat, silakan login."
- Berhasil login → redirect ke dashboard siswa + notif "Login berhasil, selamat datang {username}."
- Username tidak ditemukan atau password salah → pesan error generik "Username atau password salah." (jangan bocorkan mana yang salah).
- Username duplikat saat register → error "Username sudah digunakan."
- Field kosong / tidak valid → validasi inline per field + pesan umum "Mohon lengkapi semua kolom."
- Sesi login dipertahankan (remember/session Laravel). Logout tersedia di dashboard.

### F-S2. Kuis Wajib Berurutan

Siswa **wajib** mengerjakan kuis secara sekuensial, tidak bisa loncat. Total **7 soal + 2 isian**.

**Tahap A — Pilihan ganda (5 soal):**
- Setiap soal: 4 opsi jawaban, tepat 1 jawaban benar.
- Navigasi soal 1→5 berurutan. Boleh mundur untuk ubah jawaban sebelum submit tahap (atau langsung per soal — diputuskan saat implementasi, default: satu soal per halaman dengan tombol Lanjut/Kembali).
- Nilai tahap A dihitung setelah 5 soal dijawab semua.

**Tahap B — Soal cerita opsi pilihan (1 soal):**
- Bentuk soal cerita (teks narasi), 2 opsi pilihan jawaban.
- Wajib dijawab untuk lanjut.

**Tahap C — Soal cerita + gambar (1 soal, gate ketat):**
- Bentuk gabungan: teks cerita + gambar poster **"Isi Piringku" Kemenkes RI** (gambar betulan, bukan tulisan/placeholder) sebagai clue/instruksi. File: `public/soal/isi-piringku.jpg`, kolom `questions.gambar_path`.
- 4 opsi jawaban, 1 benar.
- **Aturan gate: jawaban harus benar baru boleh lanjut ke tahap berikutnya. Jika salah → tampilkan notif "Jawaban belum tepat, coba lagi" dan siswa mengulang soal yang sama sampai benar. Tidak ada tombol lewati.**
- Jumlah pengulangan dicatat (kolom `attempts.pengulangan`, ditampilkan di dashboard guru sebagai "Pengulangan" = berapa kali siswa mengulang soal gambar sampai benar).

**Tahap D — Kesimpulan & Refleksi (wajib, setelah semua kuis):**
- Field `kesimpulan_kuis` (textarea, wajib, min. 20 karakter) — kesimpulan siswa atas materi/kuis nutrisi.
- Field `refleksi_metode` (textarea, wajib, min. 20 karakter) — refleksi apakah metode pembelajaran dipahami/bermanfaat.
- Tidak bisa submit kosong. Setelah submit → attempt dinyatakan **selesai**.

**Aturan umum kuis:**
- Satu attempt aktif per siswa. Jika keluar di tengah jalan, progres tersimpan (draft jawaban) dan bisa dilanjutkan setelah login kembali.
- Setelah Tahap D disubmit, kuis dikunci selesai. Pengulangan (retake) diputuskan: default **tidak boleh retake** kecuali guru mereset (opsional, backlog).
- Semua submit divalidasi di server (tidak hanya mengandalkan JS).

### F-S3. Dashboard Siswa + Hasil + PDF

**Konten dashboard siswa:**
- Identitas: username, kelas, kelompok.
- Status pengerjaan: Belum mulai / Sedang mengerjakan / Selesai.
- Hasil: jumlah benar, jumlah salah, skor (mis. benar/total × 100), rincian per soal (soal, jawaban siswa, kunci, status benar/salah).
- Khusus Tahap C: tampilkan jumlah pengulangan sampai benar.
- Kesimpulan kuis & refleksi yang sudah diisi (read-only + tombol edit hanya jika attempt belum dikunci — default: tidak bisa edit setelah selesai).
- Tombol **Download PDF** (aktif hanya jika status Selesai).

**Isi PDF (wajib):**
- Kop: judul "Hasil Kuis Nutrisi Makanan dan Minuman".
- Identitas: username, kelas, kelompok, tanggal selesai.
- Rekap: total soal, benar, salah, skor.
- Rincian jawaban per soal.
- Kesimpulan & refleksi (verbatim).
- PDF dibuat server-side (mis. Dompdf / Snappy) dengan nama file `hasil-kuis-{username}-{tanggal}.pdf`.

## 6. Kebutuhan Fungsional — Guru

### F-G1. Login Guru

**Field login:**
- `kelas` — wajib, bentuk **dropdown**. Sumber = database ketersediaan kelas (tabel `kelas`). Hanya kelas yang ada di DB yang muncul.
- `password` — wajib, password per kelas, juga tersimpan di database (hash). Satu kelas = satu kredensial guru.

**Aturan & notifikasi:**
- Berhasil → masuk dashboard guru untuk kelas tersebut + notif "Login berhasil."
- Kelas/password salah → error "Kelas atau password salah."
- Guru tidak bisa register sendiri. Data akun guru di-seed oleh developer/admin (atau via seeder + tinker).
- Tombol **Download PDF Rekap Kelas** tersedia di dashboard guru.

### F-G2. Dashboard Guru (per Kelas)

- Menampilkan **hanya siswa dari kelas yang sedang login** (filter otomatis by `kelas_id` dari sesi guru).
- Tabel utama per siswa:
  - Username / nama, Kelompok, Status (belum/sedang/selesai), Benar, Salah, Skor, Pengulangan (jumlah mengulang soal gambar sampai benar), Waktu selesai.
  - Aksi: Lihat detail (jawaban per soal + kesimpulan + refleksi).
- Fitur pendukung: search by username, filter by kelompok/status, sort by skor/waktu.
- Halaman detail siswa: rincian jawaban (seperti dashboard siswa) + kesimpulan + refleksi, read-only.
- Tombol **Download PDF Rekap Kelas** (`/guru/rekap.pdf`): berisi kop kelas + tanggal cetak, tabel seluruh siswa kelas tersebut (username, kelompok, status, benar, salah, skor, pengulangan, waktu selesai), plus kesimpulan & refleksi tiap siswa yang sudah selesai. Nama file `rekap-kelas-{namaKelas}-{tanggal}.pdf`.

## 7. Alur Pengguna (User Flow)

**Siswa:**
1. Buka `/register` → isi username, password, kelas (dropdown), kelompok → submit → notif sukses → ke `/login`.
2. Login → notif sukses → `/siswa/dashboard`.
3. Klik "Mulai/Lanjutkan Kuis" → `/siswa/kuis` → Tahap A (5 soal) → Tahap B (1 soal cerita) → Tahap C (1 soal gambar, loop sampai benar) → Tahap D (form kesimpulan + refleksi) → submit → status Selesai.
4. Kembali ke dashboard → lihat benar/salah + kesimpulan/refleksi → Download PDF.

**Guru:**
1. Buka `/guru/login` → pilih kelas (dropdown dari DB) + password → submit.
2. Masuk `/guru/dashboard` → tabel siswa kelas tersebut → klik detail untuk lihat jawaban + kesimpulan + refleksi, atau klik Download PDF Rekap Kelas.
3. Logout.

## 8. Kebutuhan Data (Usulan Skema)

- `kelas` (id, nama — mis. "VII-A", unique).
- `siswa` / `users` role siswa (id, username unique, password_hash, kelas_id FK, kelompok string, timestamps).
- `guru_accounts` (id, kelas_id FK unique, password_hash) — 1 akun per kelas.
- `questions` (id, tahap ENUM A/B/C, tipe, teks_soal, gambar_path nullable, urutan).
- `options` (id, question_id FK, teks_opsi, is_correct boolean).
- `attempts` (id, siswa_id FK, status, skor, benar, salah, pengulangan, started_at, finished_at).
- `answers` (id, attempt_id FK, question_id FK, option_id FK, is_correct, percobaan_ke).
- `reflections` (id, attempt_id FK unique, kesimpulan text, refleksi_metode text).

Catatan: `kelas` adalah single source of truth untuk dropdown siswa (register) dan guru (login).

## 9. Halaman / Rute

- `/` — landing page NutrisiKu (satu halaman: nav, hero, marquee, tentang, pilihan sehat, cara mulai, galeri, testimoni, CTA, footer; tata letak terinspirasi miegacoanjakarta.id namun dengan identitas sendiri; gambar makanan sehat dari Wikimedia Commons + poster Isi Piringku Kemenkes).
- `/register`, `/login`, `/logout` — siswa.
- `/siswa/dashboard`, `/siswa/kuis`, `/siswa/hasil.pdf` — siswa (auth).
- `/guru/login`, `/guru/dashboard`, `/guru/rekap.pdf`, `/guru/siswa/{id}` — guru (auth session kelas).
- Semua proteksi middleware; siswa tidak bisa akses rute guru dan sebaliknya.

## 10. Notifikasi & Validasi (Ringkas)

- Toast/alert Bahasa Indonesia untuk: sukses register, sukses login, username/password salah, jawaban salah pada Tahap C, wajib isi kesimpulan/refleksi, kuis selesai, PDF berhasil dibuat.
- Validasi server-side untuk semua form (tidak hanya JS). Pesan error inline per field.

## 11. Kebutuhan Non-Fungsional

- Laravel + Blade (tanpa SPA), auth session bawaan, password di-hash (bcrypt).
- Responsive (mobile-first, Tailwind yang sudah tersedia).
- PDF server-side, nama file konsisten.
- Gambar soal Tahap C adalah file betulan di `public/soal/isi-piringku.jpg` (poster Isi Piringku Kemenkes, ditampilkan via `asset()`), bukan placeholder/tulisan.
- Proteksi: CSRF, rate-limit login, otorisasi per peran + per kelas.

## 12. Kriteria Penerimaan (Acceptance Criteria)

1. Register tanpa 4 field lengkap → ditolak dengan pesan validasi.
2. Dropdown kelas di register siswa isinya identik dengan dropdown kelas di login guru (sumber tabel `kelas` yang sama).
3. Login dengan password salah → pesan "Username atau password salah." / "Kelas atau password salah." dan tidak masuk dashboard.
4. Kuis tidak bisa dilompati; URL tahap berikutnya tidak bisa diakses sebelum tahap sebelumnya selesai.
5. Tahap C yang dijawab salah → tertahan di soal yang sama dengan notif, tombol lanjut tidak muncul sampai benar.
6. Kesimpulan/refleksi kosong atau < 20 karakter → ditolak.
7. Dashboard siswa menampilkan benar/salah + kesimpulan/refleksi + tombol PDF hanya saat selesai.
8. File PDF berisi identitas, rekap skor, rincian jawaban, kesimpulan, refleksi.
9. Dashboard guru hanya menampilkan siswa dari kelas yang login; tombol Download PDF Rekap Kelas menghasilkan PDF berisi tabel + kesimpulan/refleksi kelas tersebut.
10. Logout memutus sesi untuk kedua peran.
11. Halaman kuis Tahap C menampilkan gambar Isi Piringku betulan (bukan placeholder).

## 13. Asumsi & Keputusan

- Guru bisa download PDF rekap kelas (revisi: sebelumnya tanpa PDF).
- Database memakai MySQL Laragon (`nutrisi`), bukan SQLite.
- Laravel sudah ter-install — tidak ada langkah instalasi di eksekusi.
- Soal + gambar disediakan terpisah dan di-seed; PRD ini hanya mendefinisikan struktur & aturan.
- Default tanpa retake setelah selesai (bisa diubah jika diminta).

## 14. Langkah Implementasi Berikutnya

1. Migrasi + seeder: `kelas`, `guru_accounts`, `questions` + `options` (7 soal), user contoh.
2. Auth siswa & guru (guard/session terpisah).
3. UI + logika kuis bertahap + gate Tahap C.
4. Dashboard siswa + guru + PDF (Dompdf).
5. Uji manual per acceptance criteria di atas.

## 15. Revisi Desain: Concept "Detektif Nutrisi" (Noir Mystery Board) — 2026-09-06

Status: Diterapkan. Semua fitur §5–§9 dipertahankan (tidak ada yang dihapus).

### 15.1 Visual style
- Theme: Detective Desk / Noir / Investigation Board.
- Palette: kayu gelap `#1C1310`/`#2B1B17`, slate `#0F1115`, kertas sepia `#E2D5C3`/`#FBF6E8`; aksen crimson `#8B0000/#A71D2A`, gold `#C5A059`, teal `#3B6E75`.
- Tipografi: serif vintage (Georgia) untuk judul/stempel, sans + monospace untuk log/chat.
- File tema: `public/css/detektif.css` + `resources/views/layouts/app.blade.php`.

### 15.2 Landing page (4 seksi sesuai concept, isi nutrisi)
1. **Hero Header (Investigation Board):** papan kayu + pin, kertas miring logo, tombol "Mulai Penyelidikan / Ruang Guru", kliping Daily News, foto bukti A/B, mata mengawasi (CSS, bukan gambar).
2. **Story/Chat Log:** daftar chat NUTRI + saksi Mi + jejak tubuh, avatar 2D + bubble kertas + tombol "Lihat/Ikut".
3. **Character Sheet & Evidence Desk:** meja kayu, kiri tabel atribut grid-paper (Energi/Karbo, Tumbuh/Protein, dst), kanan polaroid buah + props dekoratif.
4. **Feature Gallery Carousel:** 3 poster + panah ‹ › (JS `geser()`), kartu tengah = poster Isi Piringku.

### 15.3 Halaman lain
- Login/register/guru-login: kartu kertas torn + stamp + maskot (sama untuk semua peran).
- Kuis (`siswa/kuis`): kartu soal server (tetap POST question_id/option_id, gate Tahap C, progress) + panel **Papan Misi** berisi status + link ke 5 berkas misi resmi (lihat §16).
- Papan Misi (`siswa/misi` + 5 halaman, controller `MisiController`): semua dinilai server, boleh diulang untuk memperbaiki nilai.
- Refleksi (`siswa/refleksi`): **terkunci** sampai 7 soal + 5 misi selesai (redirect ke Papan Misi + daftar kurang); 4 textarea kesimpulan (c1–c4) + 3 refleksi (r1–r3) digabung via JS ke hidden `kesimpulan`/`refleksi` (format `[1].. | [2]..`), tetap lolos validasi min:20 dan skema DB lama.
- Dashboard siswa: sertifikat + rekap benar/salah/skor/pengulangan + **tabel nilai 5 misi** + rincian per soal + kesimpulan/refleksi + PDF (PDF ikut memuat tabel misi).
- Dashboard guru: tabel + filter + PDF rekap (ikut memuat tabel nilai misi per siswa); halaman detail siswa ikut menampilkan tabel misi.

### 15.4 Metode pembelajaran (gabungan D:\laravel\Detektif + fitur lama)
Urutan siswa: Sapaan → (Tahap A: 5 PG) → Cerita Andi (Tahap B) → **5 berkas misi §16 (boleh paralel, wajib selesai)** → (Tahap C gambar, gate ketat) → Kesimpulan 4 bagian → Refleksi 3 bagian → Sertifikat + PDF. Backend: 7 soal (sumber nilai utama) + `misi_results` (nilai berkas) + 2 kolom refleksi.

### 15.5 "Perjalanan Mi di Dalam Tubuh" → 1 gambar interaktif
- Menggantikan 6 gambar organ terpisah dengan **1 file** `public/images/detektif/tubuh-manusia.svg` (manusia utuh 2D berwarna) + 6 hotspot bernomor (1 Mulut, 2 Kerongkongan, 3 Lambung, 4 Usus Halus, 5 Usus Besar, 6 Anus).
- Klik berurutan membuka penjelasan singkat mudah dipahami (1–2 kalimat, disimpan di `siswa/kuis.blade.php` array `organs`).
- Lokasi: panel 3 di halaman kuis + teaser di landing seksi 2.

### 15.6 Penggantian emoji soal → gambar 2D berwarna
- Semua emoji di alur Detektif (maskot, mi, organ, buah, dsb) diganti file lokal:
  `maskot-nutri.svg`, `mi-a.svg`, `mi-b.svg`, `tubuh-manusia.svg`, `sayur.svg`, `buah.svg`, `minuman.svg`, `lauk.svg` di `public/images/detektif/`.
- Penamaan mengikuti **sub-title materi** (mis. "Dua Mi" → mi-a/mi-b; "Sayur & Serat" → sayur.svg), bukan bentuk emoji lama. Alasan lokal-SVG: offline, konsisten, tanpa isu lisensi hotlink; tetap "gambar beneran berwarna 2D".
- Foto real tetap dipakai untuk makanan (Wikimedia) + poster Isi Piringku Kemenkes.

### 15.7 Responsif (smartphone + desktop) — hasil uji
- Teknik: `meta viewport`, grid `1 kolom → 2 kolom` di ≥820/900px (`cards`, `two`, `human-wrap`), `.carousel` jadi 1 kolom + panah disembunyikan di ≤640px, tabel guru `overflow-x:auto`, input `width:100%`, `flex-wrap` chat/nav.
- Uji: `php artisan test` (lihat §16), `php artisan view:clear && view:cache` sukses, inspeksi Chrome DevTools 360px/768px/1280px: tidak ada overflow horizontal, hotspot tetap bisa diklik di layar sentuh (30px).
- Kriteria baru: (12) hotspot tubuh bisa dibuka di HP; (13) semua halaman lolos tanpa scroll horizontal di 360px.

## 16. Berkas Misi Detektif — Materi & Kuis Resmi (2026-09-07)

Latar: 4 aktivitas Detektif sebelumnya hanya panel client-side di halaman kuis (tidak dinilai, mudah terlewat). Sekarang semuanya jadi **halaman resmi + dinilai server + wajib sebelum refleksi**.

### 16.1 Daftar berkas (5) + rute + penilaian
| # | Berkas | Rute | Penilaian server |
|---|---|---|---|
| 1 | Materi+Kuis Perbandingan Mi (Lab Bukti) | `GET/POST siswa/misi/lab-mi` | 10 angka label wajib; 3 pertanyaan bandingan (protein/natrium/energi tertinggi) dikunci dari **angka milik siswa sendiri**; seri (sama besar) → A/B diterima; skor = benar/3×100 |
| 2 | Kuis Mencocokkan Nutrien–Fungsi | `GET/POST siswa/misi/mencocokkan` | 4 dropdown nutrien→fungsi (opsi fungsi diacak tiap buka); tiap benar 25 poin |
| 3 | Materi Perjalanan Mi di Dalam Tubuh | `GET/POST siswa/misi/perjalanan-mi` | 1 gambar manusia utuh interaktif + 6 kartu organ; tombol selesai aktif via JS setelah 6 hotspot dibuka; skor 100 (ketuntasan baca) |
| 4 | Kuis Organ Pencernaan | `GET/POST siswa/misi/kuis-organ` | 4 PG (penyerapan-usus halus, peristaltik, protein-lambung, air-usus besar); tiap benar 25 poin |
| 5 | Kuis Susun Jalur Pencernaan | `GET/POST siswa/misi/susun-jalur` | Chip dinamis → hidden `urutan[]`; dinilai per posisi tepat (benar/6×100) |

Hub: `GET siswa/misi` (Papan Misi) menampilkan status + skor tiap berkas + tombol ke refleksi. Semua boleh **diulang** (updateOrCreate per attempt) untuk memperbaiki nilai.

### 16.2 Data
- Tabel `misi_results` (migration `2026_09_06_220000`): `attempt_id` FK, `misi` enum string (unik per attempt), `benar`, `total`, `skor` 0–100, `data` JSON (input + rincian), timestamps. Model `MisiResult` (`DAFTAR`, `LABEL`); relasi `Attempt::misiResults()`.
- Kunci jawaban: `MisiController::MATCH_PASANGAN`, `ORGAN_KUIS`, `URUTAN_BENAR`; materi organ: `MisiController::ORGAN_MATERI` (sumber tunggal teks organ, dipakai view materi; view kuis memakai SVG yang sama).

### 16.3 Gate refleksi (tidak menghapus fitur lama)
- `QuizController::misiKurang()` + cek di `showReflection`/`storeReflection`: 7 soal selesai TAPI misi kurang → redirect `siswa.misi.hub` + pesan "Selesaikan dulu misi ini…".
- Retake tetap terkunci setelah selesai; misi ikut terkunci (abort 403 via `activeAttempt` yang sama).

### 16.4 Acceptance criteria tambahan
14. `GET /siswa/misi` menampilkan 5 berkas + status.
15. Lab Mi dengan angka sendiri yang konsisten → skor 100; jawaban bandingan yang salah → skor berkurang.
16. `GET /siswa/refleksi` sebelum misi lengkap → redirect ke `/siswa/misi`.
17. Dashboard siswa + detail guru + kedua PDF memuat tabel nilai 5 misi.
18. Test `NutrisiTest::test_full_flow` mencakup seluruh alur misi (57 assertions, lolos).

## 17. Deploy ke Render (2026-09-07)

- File: `Dockerfile` (PHP 8.4-apache + pdo_mysql/pgsql, gd, zip; migrate+seed+cache saat start), `docker-entrypoint.sh`, `.dockerignore`, `render.yaml` (web Docker + Postgres 16 gratis).
- Catatan: proyek tidak memakai `@vite`, jadi tanpa build Node. Di dashboard Render: New → Blueprint → pilih repo (isi `render.yaml`), lalu set manual `APP_URL=https://<service>.onrender.com`. Database gratis Render bertipe Postgres dan punya batas masa/kuota — untuk pemakaian kelas jangka panjang pertimbangkan Postgres berbayar atau hosting PHP+MySQL lokal (Hostinger/Niagahoster).
