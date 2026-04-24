<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisRhk extends Model
{
    protected $fillable = ['rhk_id', 'nama', 'urutan'];

    public function rhk(): BelongsTo
    {
        return $this->belongsTo(Rhk::class);
    }

    public function laporans(): HasMany
    {
        return $this->hasMany(Laporan::class);
    }
}
