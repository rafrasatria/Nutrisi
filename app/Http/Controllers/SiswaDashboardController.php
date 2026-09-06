<?php

namespace App\Http\Controllers;

use App\Models\Attempt;
use App\Models\Question;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class SiswaDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('kelas');
        $attempt = Attempt::with(['answers.question', 'answers.option', 'misiResults'])
            ->where('user_id', $user->id)
            ->latest()
            ->first();
        $questions = Question::with('options')->orderBy('urutan')->get();

        $detail = [];
        if ($attempt) {
            $answersByQ = $attempt->answers->keyBy('question_id');
            foreach ($questions as $q) {
                $ans = $answersByQ->get($q->id);
                $detail[] = [
                    'question' => $q,
                    'answer' => $ans,
                    'correctOption' => $q->options->firstWhere('is_correct', true),
                ];
            }
        }

        $misi = $attempt ? $attempt->misiResults->keyBy('misi') : collect();

        return view('siswa.dashboard', compact('user', 'attempt', 'detail', 'questions', 'misi'));
    }

    public function pdf()
    {
        $user = Auth::user()->load('kelas');
        $attempt = Attempt::with(['answers.question', 'answers.option', 'misiResults'])
            ->where('user_id', $user->id)
            ->latest()
            ->firstOrFail();

        if ($attempt->status !== 'selesai') {
            return redirect()->route('siswa.dashboard')->withErrors(['pdf' => 'Selesaikan kuis dan refleksi terlebih dahulu untuk download PDF.']);
        }

        $questions = Question::with('options')->orderBy('urutan')->get();
        $answersByQ = $attempt->answers->keyBy('question_id');
        $detail = [];
        foreach ($questions as $q) {
            $detail[] = [
                'question' => $q,
                'answer' => $answersByQ->get($q->id),
                'correctOption' => $q->options->firstWhere('is_correct', true),
            ];
        }

        $pdf = Pdf::loadView('siswa.hasil-pdf', array_merge(compact('user', 'attempt', 'detail'), ['misi' => $attempt->misiResults->keyBy('misi')]));
        $filename = 'hasil-kuis-'.$user->username.'-'.now()->format('Ymd-His').'.pdf';

        return $pdf->download($filename);
    }
}
