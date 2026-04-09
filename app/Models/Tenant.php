<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'domain',
        'locale',
        'currency',
        'timezone',
        'token_price_cents',
        'legal_notice',
        'is_active',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'settings' => 'array',
            'token_price_cents' => 'integer',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function tokenPackages(): HasMany
    {
        return $this->hasMany(TokenPackage::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function feature(string $key, mixed $default = false): mixed
    {
        return data_get($this->settings, "features.$key", $default);
    }

    public static function defaultSettings(): array
    {
        return [
            'features' => [
                'show_prices' => true,
                'show_price_filter' => true,
                'show_contact_buttons' => true,
                'show_service_tags' => true,
            ],
        ];
    }
}
