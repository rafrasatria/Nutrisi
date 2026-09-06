<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MisiResult extends Model
{
    public const DAFTAR = ['lab-mi', 'match', 'organ-materi', 'organ-kuis', 'jalur'];

    public const LABEL = [
        'lab-mi' => 'Lab Bukti: Perbandingan Mi',
        'match' => 'Kuis Mencocokkan Nutrien',
        'organ-materi' => 'Materi: Perjalanan Mi di Dalam Tubuh',
        'organ-kuis' => 'Kuis Organ Pencernaan',
        'jalur' => 'Kuis Susun Jalur Pencernaan',
    ];

    protected $fillable = ['attempt_id', 'misi', 'benar', 'total', 'skor', 'data'];

    protected function casts(): array
    {
        return ['data' => 'array'];
    }

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(Attempt::class, 'attempt_id');
    }

    public static function label(string $misi): string
    {
        return self::LABEL[$misi] ?? $misi;
    }
}
