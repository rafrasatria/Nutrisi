<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Attempt;
use App\Models\MisiResult;
use App\Models\Option;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    private function activeAttempt(): Attempt
    {
        $user = Auth::user();

        $attempt = Attempt::where('user_id', $user->id)
            ->where('status', '!=', 'selesai')
            ->latest()
            ->first();

        if (! $attempt) {
            // Jika sudah pernah selesai, kunci (tidak boleh retake)
            $finished = Attempt::where('user_id', $user->id)->where('status', 'selesai')->first();
            if ($finished) {
                abort(403, 'Kuis sudah selesai dan tidak dapat diulang.');
            }
            $attempt = Attempt::create(['user_id' => $user->id, 'status' => 'mengerjakan']);
        }

        return $attempt;
    }

    private function orderedQuestions()
    {
        return Question::with('options')->orderBy('urutan')->get();
    }

    public function index()
    {
        $attempt = $this->activeAttempt();
        $questions = $this->orderedQuestions();
        $answeredIds = $attempt->answers()->pluck('question_id')->toArray();

        $current = $questions->first(fn ($q) => ! in_array($q->id, $answeredIds));

        // Semua soal terjawab -> ke refleksi
        if (! $current) {
            return redirect()->route('siswa.refleksi');
        }

        $answeredCount = count($answeredIds);
        $total = $questions->count();
        $progress = $total > 0 ? round($answeredCount / $total * 100) : 0;
        $misiStatus = $attempt->misiResults()->pluck('skor', 'misi')->toArray();

        return view('siswa.kuis', [
            'question' => $current,
            'answeredCount' => $answeredCount,
            'total' => $total,
            'progress' => $progress,
            'attempt' => $attempt,
            'misiStatus' => $misiStatus,
        ]);
    }

    public function answer(Request $request)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'option_id' => 'required|exists:options,id',
        ], [
            'option_id.required' => 'Pilih salah satu jawaban terlebih dahulu.',
        ]);

        $attempt = $this->activeAttempt();
        $question = Question::with('options')->findOrFail($request->question_id);
        $option = Option::findOrFail($request->option_id);

        if ($option->question_id !== $question->id) {
            return back()->withErrors(['option_id' => 'Opsi tidak valid untuk soal ini.']);
        }

        // Cegah loncat: pastikan soal ini adalah soal aktif
        $questions = $this->orderedQuestions();
        $answeredIds = $attempt->answers()->pluck('question_id')->toArray();
        $current = $questions->first(fn ($q) => ! in_array($q->id, $answeredIds));
        if ($current && (int) $current->id !== (int) $question->id) {
            return redirect()->route('siswa.kuis')->withErrors(['soal' => 'Kerjakan soal secara berurutan.']);
        }

        $isCorrect = (bool) $option->is_correct;

        // Gate Tahap C: harus benar baru boleh lanjut
        if ($question->tahap === 'C' && ! $isCorrect) {
            $attempt->increment('pengulangan');
            return back()->withErrors(['option_id' => 'Jawaban belum tepat, coba lagi sampai benar untuk lanjut.'])
                ->with('wrong', true);
        }

        Answer::updateOrCreate(
            ['attempt_id' => $attempt->id, 'question_id' => $question->id],
            ['option_id' => $option->id, 'is_correct' => $isCorrect, 'percobaan_ke' => $question->tahap === 'C' ? $attempt->pengulangan + 1 : 1]
        );

        // Jika tahap C benar dan sebelumnya ada pengulangan salah, pastikan counter minimal 1
        if ($question->tahap === 'C' && $attempt->pengulangan === 0) {
            $attempt->increment('pengulangan');
        }

        return redirect()->route('siswa.kuis')->with('success', $isCorrect ? 'Jawaban benar!' : 'Jawaban tersimpan.');
    }

    public static function misiKurang(Attempt $attempt): array
    {
        $sudah = $attempt->misiResults()->pluck('misi')->toArray();

        return array_values(array_filter(
            MisiResult::DAFTAR,
            fn ($m) => ! in_array($m, $sudah, true)
        ));
    }

    public function showReflection()
    {
        $attempt = $this->activeAttempt();
        $questions = $this->orderedQuestions();
        $answeredCount = $attempt->answers()->count();

        if ($answeredCount < $questions->count()) {
            return redirect()->route('siswa.kuis')->withErrors(['soal' => 'Selesaikan semua soal terlebih dahulu.']);
        }

        if ($kurang = self::misiKurang($attempt)) {
            $nama = implode(', ', array_map(fn ($m) => MisiResult::label($m), $kurang));

            return redirect()->route('siswa.misi.hub')
                ->withErrors(['misi' => 'Selesaikan dulu misi ini sebelum refleksi: '.$nama.'.']);
        }

        if ($attempt->status === 'selesai') {
            return redirect()->route('siswa.dashboard');
        }

        return view('siswa.refleksi', compact('attempt'));
    }

    public function storeReflection(Request $request)
    {
        $validated = $request->validate([
            'kesimpulan' => 'required|min:20',
            'refleksi' => 'required|min:20',
        ], [
            'kesimpulan.required' => 'Kesimpulan wajib diisi.',
            'kesimpulan.min' => 'Kesimpulan minimal 20 karakter.',
            'refleksi.required' => 'Refleksi wajib diisi.',
            'refleksi.min' => 'Refleksi minimal 20 karakter.',
        ]);

        $attempt = $this->activeAttempt();
        $questions = $this->orderedQuestions();

        if ($attempt->answers()->count() < $questions->count()) {
            return redirect()->route('siswa.kuis')->withErrors(['soal' => 'Selesaikan semua soal terlebih dahulu.']);
        }

        if ($kurang = self::misiKurang($attempt)) {
            $nama = implode(', ', array_map(fn ($m) => MisiResult::label($m), $kurang));

            return redirect()->route('siswa.misi.hub')
                ->withErrors(['misi' => 'Selesaikan dulu misi ini sebelum submit refleksi: '.$nama.'.']);
        }

        $benar = $attempt->answers()->where('is_correct', true)->count();
        $total = $questions->count();
        $salah = $total - $benar;
        $skor = $total > 0 ? (int) round($benar / $total * 100) : 0;

        $attempt->update([
            'kesimpulan' => $validated['kesimpulan'],
            'refleksi' => $validated['refleksi'],
            'benar' => $benar,
            'salah' => $salah,
            'skor' => $skor,
            'status' => 'selesai',
            'finished_at' => now(),
        ]);

        return redirect()->route('siswa.dashboard')->with('success', 'Kuis selesai! Hasil dan kesimpulan tersimpan.');
    }
}
