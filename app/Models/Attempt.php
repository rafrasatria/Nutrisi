<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attempt extends Model
{
    protected $fillable = [
        'user_id', 'status', 'benar', 'salah', 'skor',
        'pengulangan', 'kesimpulan', 'refleksi', 'finished_at',
    ];

    protected function casts(): array
    {
        return ['finished_at' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class, 'attempt_id');
    }

    public function misiResults(): HasMany
    {
        return $this->hasMany(MisiResult::class, 'attempt_id');
    }

    public function misiSelesai(string $misi): bool
    {
        return $this->misiResults()->where('misi', $misi)->exists();
    }

    public function isFinished(): bool
    {
        return $this->status === 'selesai';
    }
}
