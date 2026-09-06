<?php

namespace Database\Seeders;

use App\Models\GuruAccount;
use App\Models\Kelas;
use App\Models\Option;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $namaKelas = ['VII-A', 'VII-B', 'VIII-A', 'VIII-B', 'IX-A', 'IX-B'];
        $kelasMap = [];
        foreach ($namaKelas as $nama) {
            $kelasMap[$nama] = Kelas::firstOrCreate(['nama' => $nama]);
        }

        // 1 akun guru per kelas, password default: guru123
        foreach ($kelasMap as $kelas) {
            GuruAccount::firstOrCreate(
                ['kelas_id' => $kelas->id],
                ['password' => Hash::make('guru123')]
            );
        }

        // Soal kuis nutrisi
        if (Question::count() === 0) {
            $this->seedQuestions();
        }

        // Contoh siswa: username siswa1 / password siswa123, kelas VII-A
        $kelasA = $kelasMap['VII-A'];
        User::firstOrCreate(
            ['username' => 'siswa1'],
            [
                'name' => 'siswa1',
                'email' => null,
                'password' => Hash::make('siswa123'),
                'role' => 'siswa',
                'kelas_id' => $kelasA->id,
                'kelompok' => 'Kelompok 1',
            ]
        );
    }

    private function seedQuestions(): void
    {
        $data = [
            // Tahap A: 5 PG, 4 opsi
            ['tahap' => 'A', 'urutan' => 1, 'teks' => 'Zat gizi yang berfungsi utama sebagai sumber energi bagi tubuh adalah...', 'opsi' => ['Protein', 'Karbohidrat', 'Vitamin', 'Mineral'], 'benar' => 1],
            ['tahap' => 'A', 'urutan' => 2, 'teks' => 'Berikut ini yang merupakan contoh makanan sumber protein hewani adalah...', 'opsi' => ['Tempe', 'Tahu', 'Telur', 'Kacang hijau'], 'benar' => 2],
            ['tahap' => 'A', 'urutan' => 3, 'teks' => 'Vitamin yang membantu penyerapan kalsium untuk kesehatan tulang adalah vitamin...', 'opsi' => ['Vitamin A', 'Vitamin C', 'Vitamin D', 'Vitamin E'], 'benar' => 2],
            ['tahap' => 'A', 'urutan' => 4, 'teks' => 'Minuman bersoda jika dikonsumsi berlebihan dapat menyebabkan...', 'opsi' => ['Tulang kuat', 'Obesitas dan kerusakan gigi', 'Mata sehat', 'Otot besar'], 'benar' => 1],
            ['tahap' => 'A', 'urutan' => 5, 'teks' => 'Serat pangan banyak ditemukan pada makanan...', 'opsi' => ['Permen', 'Buah dan sayur', 'Kerupuk', 'Minuman bersoda'], 'benar' => 1],
            // Tahap B: 1 soal cerita, 2 opsi
            ['tahap' => 'B', 'urutan' => 6, 'teks' => 'Cerita: Andi sering jajan gorengan dan minuman bersoda sepulang sekolah. Sebulan terakhir berat badannya naik cepat dan giginya berlubang. Menurutmu, kebiasaan Andi termasuk pola makan...', 'opsi' => ['Sehat dan bergizi seimbang', 'Tidak sehat, perlu dikurangi gorengan dan minuman bersoda'], 'benar' => 1],
            // Tahap C: 1 soal cerita + gambar, 4 opsi
            ['tahap' => 'C', 'urutan' => 7, 'teks' => 'Cerita + gambar: Perhatikan gambar "Isi Piringku" dari Kemenkes di bawah. Jika bekalmu hanya nasi goreng + kerupuk + teh manis, apa perbaikan yang paling tepat agar sesuai Isi Piringku?', 'gambar' => 'soal/isi-piringku.jpg', 'opsi' => ['Tambah buah dan sayur, kurangi teh manis', 'Tambah kerupuk lagi', 'Ganti air putih dengan soda', 'Tidak perlu diubah'], 'benar' => 0],
        ];

        foreach ($data as $row) {
            $q = Question::create([
                'tahap' => $row['tahap'],
                'teks_soal' => $row['teks'],
                'gambar_path' => $row['gambar'] ?? null,
                'urutan' => $row['urutan'],
            ]);
            foreach ($row['opsi'] as $i => $teksOpsi) {
                Option::create([
                    'question_id' => $q->id,
                    'teks_opsi' => $teksOpsi,
                    'is_correct' => $i === $row['benar'],
                ]);
            }
        }
    }
}
