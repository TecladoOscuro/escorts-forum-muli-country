<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'escort_profile_id',
        'thread_id',
        'rating',
        'title',
        'body',
        'visit_date',
        'is_verified',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'visit_date' => 'date',
            'is_verified' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function escortProfile(): BelongsTo
    {
        return $this->belongsTo(EscortProfile::class);
    }

    public function thread(): BelongsTo
    {
        return $this->belongsTo(Thread::class);
    }
}
