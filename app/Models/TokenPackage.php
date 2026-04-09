<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class TokenPackage extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'tokens',
        'price_cents',
        'badge_color',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'tokens' => 'integer',
            'price_cents' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function getPriceAttribute(): float
    {
        return $this->price_cents / 100;
    }

    public function getFormattedPriceAttribute(): string
    {
        $tenant = app()->has('currentTenant') ? app('currentTenant') : $this->tenant;
        $currency = $tenant?->currency ?? 'EUR';

        return number_format($this->price, 2) . ' ' . $currency;
    }
}
