<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GpsPhoto extends Model
{
    protected $fillable = [
        'user_id',
        'filename',
        'original_filename',
        'latitude',
        'longitude',
        'address',
        'altitude',
        'speed',
        'photo_datetime',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'altitude' => 'decimal:2',
        'speed' => 'decimal:2',
        'photo_datetime' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
