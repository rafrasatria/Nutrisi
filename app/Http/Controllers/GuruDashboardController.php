<?php

namespace App\Http\Controllers;

use App\Models\Attempt;
use App\Models\Kelas;
use App\Models\Question;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class GuruDashboardController extends Controller
{
    private function kelasId(Request $request): int
    {
        return (int) $request->session()->get('guru_kelas_id');
    }

    public function index(Request $request)
    {
        $kelasId = $this->kelasId($request);
        $kelas = Kelas::findOrFail($kelasId);

        $query = User::with(['attempts' => fn ($q) => $q->latest()])
            ->where('role', 'siswa')
            ->where('kelas_id', $kelasId)
            ->orderBy('username');

        if ($request->filled('q')) {
            $query->where('username', 'like', '%'.$request->q.'%');
        }
        if ($request->filled('kelompok')) {
            $query->where('kelompok', 'like', '%'.$request->kelompok.'%');
        }
        if ($request->filled('status')) {
            $status = $request->status;
            $query->whereHas('attempts', fn ($q) => $q->where('status', $status));
        }

        $siswas = $query->paginate(20)->withQueryString();

        // Lampirkan attempt terbaru tiap siswa
        $siswas->getCollection()->each(function ($s) {
            $s->latest_attempt = $s->attempts->first();
        });

        return view('guru.dashboard', compact('kelas', 'siswas'));
    }

    public function show(Request $request, int $id)
    {
        $kelasId = $this->kelasId($request);
        $kelas = Kelas::findOrFail($kelasId);

        $siswa = User::where('role', 'siswa')->where('kelas_id', $kelasId)->findOrFail($id);
        $attempt = Attempt::with(['answers.question', 'answers.option', 'misiResults'])
            ->where('user_id', $siswa->id)->latest()->first();
        $questions = Question::with('options')->orderBy('urutan')->get();
        $misi = $attempt ? $attempt->misiResults->keyBy('misi') : collect();

        $detail = [];
        if ($attempt) {
            $answersByQ = $attempt->answers->keyBy('question_id');
            foreach ($questions as $q) {
                $detail[] = [
                    'question' => $q,
                    'answer' => $answersByQ->get($q->id),
                    'correctOption' => $q->options->firstWhere('is_correct', true),
                ];
            }
        }

        return view('guru.detail', compact('kelas', 'siswa', 'attempt', 'detail', 'misi'));
    }

    public function pdf(Request $request)
    {
        $kelasId = $this->kelasId($request);
        $kelas = Kelas::findOrFail($kelasId);

        $siswas = User::with(['attempts' => fn ($q) => $q->latest(), 'attempts.misiResults'])
            ->where('role', 'siswa')
            ->where('kelas_id', $kelasId)
            ->orderBy('username')
            ->get();

        $siswas->each(function ($s) {
            $s->latest_attempt = $s->attempts->first();
        });

        $pdf = Pdf::loadView('guru.rekap-pdf', compact('kelas', 'siswas'));
        $filename = 'rekap-kelas-'.str_replace(' ', '', $kelas->nama).'-'.now()->format('Ymd-His').'.pdf';

        return $pdf->download($filename);
    }
}
