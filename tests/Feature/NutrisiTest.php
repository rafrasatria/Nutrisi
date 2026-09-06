<?php

namespace Tests\Feature;

use App\Models\Kelas;
use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NutrisiTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_flow(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);
        $kelas = Kelas::where('nama', 'VII-A')->first();

        // 1. Register siswa
        $res = $this->post('/register', [
            'username' => 'budis', 'password' => 'rahasia123',
            'kelas_id' => $kelas->id, 'kelompok' => 'Kelompok 2',
        ]);
        $res->assertRedirect('/login');
        $this->assertDatabaseHas('users', ['username' => 'budis']);

        // 2. Login salah -> error
        $res = $this->post('/login', ['username' => 'budis', 'password' => 'salah']);
        $res->assertSessionHasErrors();

        // 3. Login benar
        $res = $this->post('/login', ['username' => 'budis', 'password' => 'rahasia123']);
        $res->assertRedirect('/siswa/dashboard');
        $this->assertAuthenticated();

        // 4. Jawab semua soal berurutan, tahap C salah dulu harus tertahan
        $questions = Question::orderBy('urutan')->get();
        foreach ($questions as $q) {
            $correct = $q->options()->where('is_correct', true)->first();
            $wrong = $q->options()->where('is_correct', false)->first();
            if ($q->tahap === 'C') {
                $r = $this->post('/siswa/kuis', ['question_id' => $q->id, 'option_id' => $wrong->id]);
                $r->assertSessionHasErrors(); // gate: tertahan
                $this->assertDatabaseMissing('answers', ['question_id' => $q->id]);
            }
            $r = $this->post('/siswa/kuis', ['question_id' => $q->id, 'option_id' => $correct->id]);
            $r->assertRedirect('/siswa/kuis');
        }

        // 5. Refleksi DITOLAK dulu karena misi belum dikerjakan
        $r = $this->get('/siswa/refleksi');
        $r->assertRedirect('/siswa/misi');

        // 6. Misi 1: Lab Mi (10 angka + 3 bandingan dari angka sendiri)
        $r = $this->post('/siswa/misi/lab-mi', [
            'mi_a_energi' => 400, 'mi_b_energi' => 350,
            'mi_a_karbo' => 55, 'mi_b_karbo' => 50,
            'mi_a_protein' => 8, 'mi_b_protein' => 10,
            'mi_a_lemak' => 15, 'mi_b_lemak' => 12,
            'mi_a_natrium' => 900, 'mi_b_natrium' => 700,
            'q_protein' => 'B', 'q_natrium' => 'A', 'q_energi' => 'A',
        ]);
        $r->assertRedirect('/siswa/misi');
        $this->assertDatabaseHas('misi_results', ['misi' => 'lab-mi', 'benar' => 3, 'skor' => 100]);

        // 7. Misi 2: Mencocokkan (salah satu sengaja salah -> benar 3)
        $r = $this->post('/siswa/misi/mencocokkan', [
            'jawab_'.md5('Karbohidrat') => 'Sumber energi utama',
            'jawab_'.md5('Protein') => 'Cadangan energi tubuh',
            'jawab_'.md5('Lemak') => 'Cadangan energi tubuh',
            'jawab_'.md5('Natrium') => 'Menjaga keseimbangan cairan tubuh',
        ]);
        $r->assertRedirect('/siswa/misi');
        $this->assertDatabaseHas('misi_results', ['misi' => 'match', 'benar' => 3]);

        // 8. Misi 3: Materi organ dibaca
        $this->get('/siswa/misi/perjalanan-mi')->assertOk();
        $r = $this->post('/siswa/misi/perjalanan-mi');
        $r->assertRedirect('/siswa/misi');

        // 9. Misi 4: Kuis organ (semua benar)
        $r = $this->post('/siswa/misi/kuis-organ', ['q0' => 1, 'q1' => 0, 'q2' => 2, 'q3' => 3]);
        $r->assertRedirect('/siswa/misi');
        $this->assertDatabaseHas('misi_results', ['misi' => 'organ-kuis', 'benar' => 4]);

        // 10. Misi 5: Susun jalur (posisi 4-5 ditukar -> benar 4)
        $r = $this->post('/siswa/misi/susun-jalur', ['urutan' => ['Mulut', 'Kerongkongan', 'Lambung', 'Usus Besar', 'Usus Halus', 'Anus']]);
        $r->assertRedirect('/siswa/misi');
        $this->assertDatabaseHas('misi_results', ['misi' => 'jalur', 'benar' => 4]);

        // 11. Refleksi kosong ditolak, valid diterima (misi sudah lengkap)
        $r = $this->post('/siswa/refleksi', ['kesimpulan' => 'pendek', 'refleksi' => 'pendek']);
        $r->assertSessionHasErrors();
        $r = $this->post('/siswa/refleksi', [
            'kesimpulan' => 'Saya belajar pentingnya gizi seimbang dan Isi Piringku.',
            'refleksi' => 'Metode kuis cerita dan gambar sangat membantu pemahaman saya.',
        ]);
        $r->assertRedirect('/siswa/dashboard');

        // 12. Dashboard memuat nilai misi + PDF
        $this->get('/siswa/dashboard')->assertSee('Selesai')->assertSee('Lab Bukti');
        $pdf = $this->get('/siswa/hasil.pdf');
        $pdf->assertOk();
        $this->assertStringContainsString('application/pdf', $pdf->headers->get('Content-Type'));

        // 7. Guru login + dashboard hanya kelasnya + PDF rekap
        $this->post('/siswa/logout');
        $r = $this->post('/guru/login', ['kelas_id' => 999999, 'password' => 'x']);
        $r->assertSessionHasErrors();
        $r = $this->post('/guru/login', ['kelas_id' => $kelas->id, 'password' => 'guru123']);
        $r->assertRedirect('/guru/dashboard');
        $this->get('/guru/dashboard')->assertSee('budis')->assertSee('Download PDF Rekap Kelas');
        $rekap = $this->get('/guru/rekap.pdf');
        $rekap->assertOk();
        $this->assertStringContainsString('application/pdf', $rekap->headers->get('Content-Type'));

        // 8. Halaman kuis Tahap C menampilkan gambar betulan
        $this->assertFileExists(public_path('soal/isi-piringku.jpg'));
    }
}
