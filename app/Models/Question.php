<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = ['tahap', 'teks_soal', 'gambar_path', 'urutan'];

    public function options(): HasMany
    {
        return $this->hasMany(Option::class, 'question_id');
    }

    public function correctOption(): ?Option
    {
        return $this->options()->where('is_correct', true)->first();
    }
}
