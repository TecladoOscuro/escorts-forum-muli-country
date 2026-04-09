<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EscortPhoto extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'escort_profile_id',
        'path',
        'is_primary',
        'is_approved',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'is_approved' => 'boolean',
            'created_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($photo) {
            $photo->created_at = now();
        });
    }

    public function escortProfile(): BelongsTo
    {
        return $this->belongsTo(EscortProfile::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }
}
