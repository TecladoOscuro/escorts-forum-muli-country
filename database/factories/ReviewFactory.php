<?php

namespace Database\Factories;

use App\Models\EscortProfile;
use App\Models\Review;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'user_id' => User::factory(),
            'escort_profile_id' => EscortProfile::factory(),
            'rating' => fake()->numberBetween(1, 5),
            'title' => fake()->sentence(4),
            'body' => fake()->paragraphs(2, true),
            'visit_date' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }
}
