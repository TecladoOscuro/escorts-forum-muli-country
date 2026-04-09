<?php

namespace Tests\Feature;

use App\Models\EscortProfile;
use App\Models\Review;
use App\Models\User;
use Tests\TestCase;

class ReviewCreationTest extends TestCase
{
    private EscortProfile $escort;

    protected function setUp(): void
    {
        parent::setUp();

        $escortUser = User::factory()->create(['tenant_id' => $this->tenant->id, 'role' => 'escort']);
        $this->escort = EscortProfile::factory()->create([
            'tenant_id' => $this->tenant->id,
            'user_id' => $escortUser->id,
        ]);
    }

    public function test_guest_cannot_access_create_form(): void
    {
        $response = $this->withAgeVerification()->get(route('reviews.create', $this->escort));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_see_create_form(): void
    {
        $user = User::factory()->create(['tenant_id' => $this->tenant->id]);

        $response = $this->withAgeVerification()
            ->actingAs($user)
            ->get(route('reviews.create', $this->escort));

        $response->assertOk();
        $response->assertSee(__('Write a Review'));
    }

    public function test_can_create_review(): void
    {
        $user = User::factory()->create(['tenant_id' => $this->tenant->id]);

        $response = $this->withAgeVerification()
            ->actingAs($user)
            ->post(route('reviews.store', $this->escort), [
                'rating' => 4,
                'title' => 'Wonderful experience',
                'body' => 'This was a truly wonderful experience that I enjoyed very much.',
                'visit_date' => now()->subDay()->format('Y-m-d'),
            ]);

        $response->assertRedirectContains('/escorts/');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'escort_profile_id' => $this->escort->id,
            'rating' => 4,
            'title' => 'Wonderful experience',
        ]);
    }

    public function test_updates_escort_rating_after_review(): void
    {
        $user = User::factory()->create(['tenant_id' => $this->tenant->id]);

        $this->withAgeVerification()
            ->actingAs($user)
            ->post(route('reviews.store', $this->escort), [
                'rating' => 4,
                'title' => 'Good experience',
                'body' => 'This was a good experience that I enjoyed.',
                'visit_date' => now()->subDay()->format('Y-m-d'),
            ]);

        $this->escort->refresh();
        $this->assertEquals(4.0, $this->escort->avg_rating);
        $this->assertEquals(1, $this->escort->reviews_count);
    }

    public function test_validation_requires_rating(): void
    {
        $user = User::factory()->create(['tenant_id' => $this->tenant->id]);

        $response = $this->withAgeVerification()
            ->actingAs($user)
            ->post(route('reviews.store', $this->escort), [
                'title' => 'Test',
                'body' => 'This is a test review body text.',
                'visit_date' => now()->subDay()->format('Y-m-d'),
            ]);

        $response->assertSessionHasErrors('rating');
    }

    public function test_validation_requires_minimum_body_length(): void
    {
        $user = User::factory()->create(['tenant_id' => $this->tenant->id]);

        $response = $this->withAgeVerification()
            ->actingAs($user)
            ->post(route('reviews.store', $this->escort), [
                'rating' => 5,
                'title' => 'Test',
                'body' => 'Too short',
                'visit_date' => now()->subDay()->format('Y-m-d'),
            ]);

        $response->assertSessionHasErrors('body');
    }

    public function test_validation_rejects_future_visit_date(): void
    {
        $user = User::factory()->create(['tenant_id' => $this->tenant->id]);

        $response = $this->withAgeVerification()
            ->actingAs($user)
            ->post(route('reviews.store', $this->escort), [
                'rating' => 5,
                'title' => 'Test review title',
                'body' => 'This is a sufficiently long test review body.',
                'visit_date' => now()->addDays(5)->format('Y-m-d'),
            ]);

        $response->assertSessionHasErrors('visit_date');
    }

    public function test_validation_rejects_rating_out_of_range(): void
    {
        $user = User::factory()->create(['tenant_id' => $this->tenant->id]);

        $response = $this->withAgeVerification()
            ->actingAs($user)
            ->post(route('reviews.store', $this->escort), [
                'rating' => 6,
                'title' => 'Test review',
                'body' => 'This is a sufficiently long test review body.',
                'visit_date' => now()->subDay()->format('Y-m-d'),
            ]);

        $response->assertSessionHasErrors('rating');
    }

    public function test_guest_cannot_create_review(): void
    {
        $response = $this->withAgeVerification()
            ->post(route('reviews.store', $this->escort), [
                'rating' => 4,
                'title' => 'Test review',
                'body' => 'This is a sufficiently long test review body.',
                'visit_date' => now()->subDay()->format('Y-m-d'),
            ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseCount('reviews', 0);
    }
}
