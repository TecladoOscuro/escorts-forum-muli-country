<?php

namespace Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tenant>
 */
class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    public function definition(): array
    {
        return [
            'name' => 'Test Forum',
            'slug' => 'de',
            'domain' => 'localhost',
            'locale' => 'de',
            'currency' => 'EUR',
            'timezone' => 'Europe/Berlin',
            'token_price_cents' => 15,
            'is_active' => true,
            'settings' => Tenant::defaultSettings(),
        ];
    }
}
