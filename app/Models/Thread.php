<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Thread extends Model
{
    use BelongsToTenant, HasSlug;

    protected $fillable = [
        'tenant_id',
        'category_id',
        'user_id',
        'escort_profile_id',
        'title',
        'slug',
        'body',
        'type',
        'is_pinned',
        'is_locked',
        'is_sponsored',
        'sponsored_until',
    ];

    protected function casts(): array
    {
        return [
            'is_pinned' => 'boolean',
            'is_locked' => 'boolean',
            'is_sponsored' => 'boolean',
            'sponsored_until' => 'datetime',
            'last_reply_at' => 'datetime',
            'views_count' => 'integer',
            'replies_count' => 'integer',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function escortProfile(): BelongsTo
    {
        return $this->belongsTo(EscortProfile::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function lastReplyBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'last_reply_by');
    }

    public function isSponsored(): bool
    {
        return $this->is_sponsored && $this->sponsored_until?->isFuture();
    }
}
