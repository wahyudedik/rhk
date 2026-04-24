<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rhk extends Model
{
    protected $fillable = ['nama', 'urutan'];

    public function jenisRhks(): HasMany
    {
        return $this->hasMany(JenisRhk::class)->orderBy('urutan');
    }

    public function laporans(): HasMany
    {
        return $this->hasMany(Laporan::class);
    }
}
