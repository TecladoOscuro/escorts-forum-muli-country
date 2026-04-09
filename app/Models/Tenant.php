<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
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
}
