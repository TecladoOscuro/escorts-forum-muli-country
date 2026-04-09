<?php

namespace Tests\Feature;

use App\Models\EscortProfile;
use App\Models\Review;
use App\Models\User;
use Tests\TestCase;

class ReviewListingTest extends TestCase
{
    private function createReview(array $escortAttrs = [], array $reviewAttrs = []): Review
    {
        $user = User::factory()->create(['tenant_id' => $this->tenant->id]);
        $escortUser = User::factory()->create(['tenant_id' => $this->tenant->id]);
        $escort = EscortProfile::factory()->create(array_merge([
            'tenant_id' => $this->tenant->id,
            'user_id' => $escortUser->id,
        ], $escortAttrs));

        return Review::factory()->create(array_merge([
            'tenant_id' => $this->tenant->id,
            'user_id' => $user->id,
            'escort_profile_id' => $escort->id,
        ], $reviewAttrs));
    }

    public function test_reviews_page_loads(): void
    {
        $response = $this->withAgeVerification()->get(route('reviews.index'));

        $response->assertOk();
        $response->assertSee(__('All Reviews'));
    }

    public function test_displays_reviews_with_escort_link(): void
    {
        $review = $this->createReview(
            ['display_name' => 'EscortName'],
            ['title' => 'Great experience']
        );

        $response = $this->withAgeVerification()->get(route('reviews.index'));

        $response->assertSee('Great experience');
        $response->assertSee('EscortName');
    }

    public function test_filter_by_rating(): void
    {
        $this->createReview([], ['rating' => 5]);
        $this->createReview([], ['rating' => 2]);
        $this->createReview([], ['rating' => 5]);

        $response = $this->withAgeVerification()->get(route('reviews.index', ['rating' => 5]));

        $reviews = $response->viewData('reviews');
        $this->assertEquals(2, $reviews->total());
        foreach ($reviews as $review) {
            $this->assertEquals(5, $review->rating);
        }
    }

    public function test_filter_by_city(): void
    {
        $this->createReview(['city' => 'Berlin'], ['rating' => 4]);
        $this->createReview(['city' => 'Hamburg'], ['rating' => 3]);

        $response = $this->withAgeVerification()->get(route('reviews.index', ['city' => 'Berlin']));

        $reviews = $response->viewData('reviews');
        $this->assertEquals(1, $reviews->total());
    }

    public function test_sort_newest_first(): void
    {
        $old = $this->createReview([], ['created_at' => now()->subDays(5)]);
        $new = $this->createReview([], ['created_at' => now()]);

        $response = $this->withAgeVerification()->get(route('reviews.index', ['sort' => 'newest']));

        $reviews = $response->viewData('reviews');
        $this->assertEquals($new->id, $reviews->first()->id);
    }

    public function test_sort_oldest_first(): void
    {
        $old = $this->createReview([], ['created_at' => now()->subDays(5)]);
        $new = $this->createReview([], ['created_at' => now()]);

        $response = $this->withAgeVerification()->get(route('reviews.index', ['sort' => 'oldest']));

        $reviews = $response->viewData('reviews');
        $this->assertEquals($old->id, $reviews->first()->id);
    }

    public function test_sort_highest_rating(): void
    {
        $low = $this->createReview([], ['rating' => 1]);
        $high = $this->createReview([], ['rating' => 5]);

        $response = $this->withAgeVerification()->get(route('reviews.index', ['sort' => 'rating_high']));

        $reviews = $response->viewData('reviews');
        $this->assertEquals($high->id, $reviews->first()->id);
    }

    public function test_sort_lowest_rating(): void
    {
        $low = $this->createReview([], ['rating' => 1]);
        $high = $this->createReview([], ['rating' => 5]);

        $response = $this->withAgeVerification()->get(route('reviews.index', ['sort' => 'rating_low']));

        $reviews = $response->viewData('reviews');
        $this->assertEquals($low->id, $reviews->first()->id);
    }

    public function test_rating_stats_are_computed(): void
    {
        $this->createReview([], ['rating' => 5]);
        $this->createReview([], ['rating' => 5]);
        $this->createReview([], ['rating' => 3]);
        $this->createReview([], ['rating' => 1]);

        $response = $this->withAgeVerification()->get(route('reviews.index'));

        $ratingStats = $response->viewData('ratingStats');
        $totalReviews = $response->viewData('totalReviews');
        $avgRating = $response->viewData('avgRating');

        $this->assertEquals(4, $totalReviews);
        $this->assertEquals(2, $ratingStats[5] ?? 0);
        $this->assertEquals(1, $ratingStats[3] ?? 0);
        $this->assertEquals(1, $ratingStats[1] ?? 0);
        $this->assertEquals(3.5, $avgRating);
    }

    public function test_cities_filter_options_populated(): void
    {
        $this->createReview(['city' => 'Berlin']);
        $this->createReview(['city' => 'Hamburg']);

        $response = $this->withAgeVerification()->get(route('reviews.index'));

        $cities = $response->viewData('cities');
        $this->assertTrue($cities->contains('Berlin'));
        $this->assertTrue($cities->contains('Hamburg'));
    }

    public function test_pagination(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $this->createReview();
        }

        $response = $this->withAgeVerification()->get(route('reviews.index'));

        $reviews = $response->viewData('reviews');
        $this->assertEquals(20, $reviews->total());
        $this->assertEquals(15, $reviews->perPage());
        $this->assertTrue($reviews->hasMorePages());
    }

    public function test_empty_state(): void
    {
        $response = $this->withAgeVerification()->get(route('reviews.index'));

        $response->assertOk();
        $reviews = $response->viewData('reviews');
        $this->assertEquals(0, $reviews->total());
    }
}
