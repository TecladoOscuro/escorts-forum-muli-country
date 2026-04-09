<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class EscortProfile extends Model
{
    use BelongsToTenant, HasSlug;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'display_name',
        'slug',
        'city',
        'neighborhood',
        'age',
        'nationality',
        'languages',
        'description',
        'services',
        'rates',
        'schedule',
        'contact_phone',
        'contact_telegram',
        'contact_email',
        'is_verified',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'languages' => 'array',
            'services' => 'array',
            'rates' => 'array',
            'schedule' => 'array',
            'is_verified' => 'boolean',
            'is_active' => 'boolean',
            'views_count' => 'integer',
            'reviews_count' => 'integer',
            'avg_rating' => 'float',
            'blog_visible_until' => 'datetime',
            'featured_until' => 'datetime',
            'top_until' => 'datetime',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('display_name')
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(EscortPhoto::class)->orderBy('sort_order');
    }

    public function primaryPhoto()
    {
        return $this->hasOne(EscortPhoto::class)->where('is_primary', true);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class);
    }

    public function blogThread()
    {
        return $this->hasOne(Thread::class)->where('type', 'blog');
    }

    public function isBlogVisible(): bool
    {
        return $this->blog_visible_until && $this->blog_visible_until->isFuture();
    }

    public function isFeatured(): bool
    {
        return $this->featured_until && $this->featured_until->isFuture();
    }

    public function isTop(): bool
    {
        return $this->top_until && $this->top_until->isFuture();
    }

    public function getPrimaryPhotoUrlAttribute(): string
    {
        $photo = $this->photos->where('is_primary', true)->first()
            ?? $this->photos->first();

        if ($photo) {
            return asset('storage/' . $photo->path);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->display_name) . '&size=400&background=1a1a24&color=e84393';
    }
}
