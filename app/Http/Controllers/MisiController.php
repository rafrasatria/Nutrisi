<?php

namespace App\Http\Controllers;

use App\Models\Attempt;
use App\Models\MisiResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MisiController extends Controller
{
    public const ORGAN_MATERI = [
        ['nama' => 'Mulut', 'singkat' => 'Gigi mengunyah + air liur. Air liur mulai memecah karbohidrat jadi potongan kecil yang mudah ditelan.'],
        ['nama' => 'Kerongkongan', 'singkat' => 'Saluran yang meremas-remas (gerak peristaltik) untuk mendorong makanan turun ke lambung, seperti memeras odol.'],
        ['nama' => 'Lambung', 'singkat' => 'Kantong berisi asam + enzim yang mengaduk makanan jadi bubur (kim). Protein mulai dicerna di sini.'],
        ['nama' => 'Usus Halus', 'singkat' => 'Tempat utama penyerapan: sari gula, protein, dan lemak masuk ke darah untuk energi dan pertumbuhan.'],
        ['nama' => 'Usus Besar', 'singkat' => 'Menyerap sisa air dan memadatkan sisa makanan. Dibantu bakteri baik.'],
        ['nama' => 'Anus', 'singkat' => 'Pintu keluar sisa makanan yang tidak terpakai (feses).'],
    ];

    public const MATCH_PASANGAN = [
        'Karbohidrat' => 'Sumber energi utama',
        'Protein' => 'Pertumbuhan & perbaikan jaringan',
        'Lemak' => 'Cadangan energi tubuh',
        'Natrium' => 'Menjaga keseimbangan cairan tubuh',
    ];

    public const ORGAN_KUIS = [
        ['tanya' => 'Sari-sari makanan (gula, asam amino, asam lemak) diserap ke aliran darah terutama di...', 'opsi' => ['Lambung', 'Usus halus', 'Usus besar', 'Kerongkongan'], 'benar' => 1],
        ['tanya' => 'Gerakan otot meremas yang mendorong makanan dari kerongkongan ke lambung disebut...', 'opsi' => ['Gerak peristaltik', 'Gerak refleks', 'Gerak pasif', 'Gerak sendi'], 'benar' => 0],
        ['tanya' => 'Protein mulai dicerna oleh asam lambung dan enzim di...', 'opsi' => ['Mulut', 'Usus besar', 'Lambung', 'Anus'], 'benar' => 2],
        ['tanya' => 'Sisa air diserap kembali dan sisa makanan dipadatkan di...', 'opsi' => ['Usus halus', 'Kerongkongan', 'Lambung', 'Usus besar'], 'benar' => 3],
    ];

    public const URUTAN_BENAR = ['Mulut', 'Kerongkongan', 'Lambung', 'Usus Halus', 'Usus Besar', 'Anus'];

    private function activeAttempt(): Attempt
    {
        $user = Auth::user();
        $attempt = Attempt::where('user_id', $user->id)->where('status', '!=', 'selesai')->latest()->first();
        if (! $attempt) {
            if (Attempt::where('user_id', $user->id)->where('status', 'selesai')->exists()) {
                abort(403, 'Kuis sudah selesai dan tidak dapat diulang.');
            }
            $attempt = Attempt::create(['user_id' => $user->id, 'status' => 'mengerjakan']);
        }

        return $attempt;
    }

    private function statusMap(Attempt $attempt): array
    {
        $done = $attempt->misiResults()->pluck('skor', 'misi')->toArray();

        return collect(MisiResult::DAFTAR)->mapWithKeys(fn ($m) => [$m => $done[$m] ?? null])->toArray();
    }

    public function hub()
    {
        $attempt = $this->activeAttempt();

        return view('siswa.misi.hub', ['status' => $this->statusMap($attempt)]);
    }

    // ---------- 1. LAB MI ----------
    public function labMi()
    {
        $attempt = $this->activeAttempt();
        $saved = $attempt->misiResults()->where('misi', 'lab-mi')->first();

        return view('siswa.misi.lab-mi', ['saved' => $saved]);
    }

    public function labMiStore(Request $request)
    {
        $v = $request->validate([
            'mi_a_energi' => 'required|numeric|min:0', 'mi_b_energi' => 'required|numeric|min:0',
            'mi_a_karbo' => 'required|numeric|min:0', 'mi_b_karbo' => 'required|numeric|min:0',
            'mi_a_protein' => 'required|numeric|min:0', 'mi_b_protein' => 'required|numeric|min:0',
            'mi_a_lemak' => 'required|numeric|min:0', 'mi_b_lemak' => 'required|numeric|min:0',
            'mi_a_natrium' => 'required|numeric|min:0', 'mi_b_natrium' => 'required|numeric|min:0',
            'q_protein' => 'required|in:A,B', 'q_natrium' => 'required|in:A,B', 'q_energi' => 'required|in:A,B',
        ]);

        $uji = [
            'q_protein' => [$v['mi_a_protein'], $v['mi_b_protein']],
            'q_natrium' => [$v['mi_a_natrium'], $v['mi_b_natrium']],
            'q_energi' => [$v['mi_a_energi'], $v['mi_b_energi']],
        ];
        $benar = 0;
        $rincian = [];
        foreach ($uji as $k => [$a, $b]) {
            // Seri (sama besar) → jawaban A/B dua-duanya diterima
            $kunci = $a == $b ? [$v[$k]] : [($a > $b ? 'A' : 'B')];
            $ok = in_array($v[$k], $kunci, true);
            $benar += $ok ? 1 : 0;
            $rincian[$k] = ['jawab' => $v[$k], 'benar' => $ok, 'mi_a' => $a, 'mi_b' => $b];
        }

        $attempt = $this->activeAttempt();
        MisiResult::updateOrCreate(
            ['attempt_id' => $attempt->id, 'misi' => 'lab-mi'],
            ['benar' => $benar, 'total' => 3, 'skor' => (int) round($benar / 3 * 100), 'data' => ['input' => $v, 'rincian' => $rincian]]
        );

        return redirect()->route('siswa.misi.hub')->with('success', "Lab Bukti dinilai: {$benar}/3 benar.");
    }

    // ---------- 2. MATCH ----------
    public function match()
    {
        $attempt = $this->activeAttempt();
        $saved = $attempt->misiResults()->where('misi', 'match')->first();
        $fungsiAcak = collect(self::MATCH_PASANGAN)->values()->shuffle();

        return view('siswa.misi.match', ['saved' => $saved, 'fungsiAcak' => $fungsiAcak]);
    }

    public function matchStore(Request $request)
    {
        $nutrien = array_keys(self::MATCH_PASANGAN);
        $rules = [];
        foreach ($nutrien as $n) {
            $rules['jawab_'.md5($n)] = 'required|string';
        }
        $v = $request->validate($rules);

        $benar = 0;
        $rincian = [];
        foreach ($nutrien as $n) {
            $ok = trim($v['jawab_'.md5($n)]) === self::MATCH_PASANGAN[$n];
            $benar += $ok ? 1 : 0;
            $rincian[$n] = ['jawab' => $v['jawab_'.md5($n)], 'kunci' => self::MATCH_PASANGAN[$n], 'benar' => $ok];
        }

        $attempt = $this->activeAttempt();
        MisiResult::updateOrCreate(
            ['attempt_id' => $attempt->id, 'misi' => 'match'],
            ['benar' => $benar, 'total' => 4, 'skor' => $benar * 25, 'data' => $rincian]
        );

        return redirect()->route('siswa.misi.hub')->with('success', "Mencocokkan dinilai: {$benar}/4 benar.");
    }

    // ---------- 3. MATERI ORGAN ----------
    public function organ()
    {
        $attempt = $this->activeAttempt();
        $saved = $attempt->misiResults()->where('misi', 'organ-materi')->first();

        return view('siswa.misi.organ', ['materi' => self::ORGAN_MATERI, 'saved' => $saved]);
    }

    public function organStore()
    {
        $attempt = $this->activeAttempt();
        MisiResult::updateOrCreate(
            ['attempt_id' => $attempt->id, 'misi' => 'organ-materi'],
            ['benar' => 6, 'total' => 6, 'skor' => 100, 'data' => ['dibaca' => self::URUTAN_BENAR]]
        );

        return redirect()->route('siswa.misi.hub')->with('success', 'Materi Perjalanan Mi selesai dibaca.');
    }

    // ---------- 4. KUIS ORGAN ----------
    public function organKuis()
    {
        $attempt = $this->activeAttempt();
        $saved = $attempt->misiResults()->where('misi', 'organ-kuis')->first();

        return view('siswa.misi.kuis-organ', ['soal' => self::ORGAN_KUIS, 'saved' => $saved]);
    }

    public function organKuisStore(Request $request)
    {
        $v = $request->validate([
            'q0' => 'required|integer|min:0|max:3', 'q1' => 'required|integer|min:0|max:3',
            'q2' => 'required|integer|min:0|max:3', 'q3' => 'required|integer|min:0|max:3',
        ]);

        $benar = 0;
        $rincian = [];
        foreach (self::ORGAN_KUIS as $i => $s) {
            $ok = (int) $v['q'.$i] === $s['benar'];
            $benar += $ok ? 1 : 0;
            $rincian[] = ['jawab' => $s['opsi'][(int) $v['q'.$i]], 'kunci' => $s['opsi'][$s['benar']], 'benar' => $ok];
        }

        $attempt = $this->activeAttempt();
        MisiResult::updateOrCreate(
            ['attempt_id' => $attempt->id, 'misi' => 'organ-kuis'],
            ['benar' => $benar, 'total' => 4, 'skor' => $benar * 25, 'data' => $rincian]
        );

        return redirect()->route('siswa.misi.hub')->with('success', "Kuis Organ dinilai: {$benar}/4 benar.");
    }

    // ---------- 5. JALUR ----------
    public function jalur()
    {
        $attempt = $this->activeAttempt();
        $saved = $attempt->misiResults()->where('misi', 'jalur')->first();
        $acak = collect(self::URUTAN_BENAR)->shuffle();

        return view('siswa.misi.jalur', ['saved' => $saved, 'acak' => $acak]);
    }

    public function jalurStore(Request $request)
    {
        $v = $request->validate([
            'urutan' => 'required|array|size:6',
            'urutan.*' => 'required|string',
        ]);

        $benar = 0;
        $rincian = [];
        foreach (self::URUTAN_BENAR as $i => $nama) {
            $ok = ($v['urutan'][$i] ?? null) === $nama;
            $benar += $ok ? 1 : 0;
            $rincian[] = ['posisi' => $i + 1, 'jawab' => $v['urutan'][$i] ?? '-', 'kunci' => $nama, 'benar' => $ok];
        }

        $attempt = $this->activeAttempt();
        MisiResult::updateOrCreate(
            ['attempt_id' => $attempt->id, 'misi' => 'jalur'],
            ['benar' => $benar, 'total' => 6, 'skor' => (int) round($benar / 6 * 100), 'data' => $rincian]
        );

        return redirect()->route('siswa.misi.hub')->with('success', "Susun Jalur dinilai: {$benar}/6 posisi tepat.");
    }
}
