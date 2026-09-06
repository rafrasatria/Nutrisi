<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Kelas extends Model
{
    protected $table = 'kelas';
    protected $fillable = ['nama'];

    public function siswas(): HasMany
    {
        return $this->hasMany(User::class, 'kelas_id');
    }

    public function guruAccount(): HasOne
    {
        return $this->hasOne(GuruAccount::class, 'kelas_id');
    }
}
