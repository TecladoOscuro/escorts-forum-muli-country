<?php

namespace Database\Factories;

use App\Models\EscortProfile;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EscortProfile>
 */
class EscortProfileFactory extends Factory
{
    protected $model = EscortProfile::class;

    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'user_id' => User::factory(),
            'display_name' => fake()->firstName() . ' ' . fake()->lastName(),
            'slug' => fake()->unique()->slug(2),
            'city' => fake()->city(),
            'age' => fake()->numberBetween(18, 45),
            'nationality' => fake()->country(),
            'languages' => fake()->randomElements(['de', 'en', 'fr', 'es', 'it', 'pt', 'ru'], rand(1, 3)),
            'description' => fake()->paragraphs(2, true),
            'services' => fake()->randomElements(['Massage', 'Dinner Date', 'Overnight', 'Travel', 'GFE', 'PSE'], rand(2, 4)),
            'is_verified' => false,
            'is_active' => true,
            'views_count' => fake()->numberBetween(0, 500),
            'reviews_count' => 0,
            'avg_rating' => 0,
        ];
    }

    public function verified(): static
    {
        return $this->state(fn () => ['is_verified' => true]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }

    public function featured(): static
    {
        return $this->state(fn () => ['featured_until' => now()->addDays(7)]);
    }

    public function top(): static
    {
        return $this->state(fn () => ['top_until' => now()->addDay()]);
    }
}
