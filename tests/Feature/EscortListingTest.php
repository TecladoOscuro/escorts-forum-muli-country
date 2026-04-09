<?php

namespace Tests\Feature;

use App\Models\EscortProfile;
use App\Models\User;
use Tests\TestCase;

class EscortListingTest extends TestCase
{
    private function createEscort(array $attributes = []): EscortProfile
    {
        $user = User::factory()->create(['tenant_id' => $this->tenant->id]);
        return EscortProfile::factory()->create(array_merge([
            'tenant_id' => $this->tenant->id,
            'user_id' => $user->id,
        ], $attributes));
    }

    public function test_escorts_page_loads(): void
    {
        $this->createEscort();

        $response = $this->withAgeVerification()->get(route('escorts.index'));

        $response->assertOk();
        $response->assertSee(__('Escorts'));
    }

    public function test_redirects_without_age_verification(): void
    {
        $response = $this->get(route('escorts.index'));

        $response->assertRedirect(route('age-verification'));
    }

    public function test_filter_by_city(): void
    {
        $this->createEscort(['city' => 'Berlin']);
        $this->createEscort(['city' => 'München']);

        $response = $this->withAgeVerification()->get(route('escorts.index', ['city' => 'Berlin']));

        $response->assertOk();
        $escorts = $response->viewData('escorts');
        $this->assertEquals(1, $escorts->total());
        $this->assertEquals('Berlin', $escorts->first()->city);
    }

    public function test_filter_by_nationality(): void
    {
        $this->createEscort(['nationality' => 'German']);
        $this->createEscort(['nationality' => 'French']);

        $response = $this->withAgeVerification()->get(route('escorts.index', ['nationality' => 'German']));

        $escorts = $response->viewData('escorts');
        $this->assertEquals(1, $escorts->total());
        $this->assertEquals('German', $escorts->first()->nationality);
    }

    public function test_filter_by_language(): void
    {
        $this->createEscort(['languages' => ['de', 'en']]);
        $this->createEscort(['languages' => ['fr']]);

        $response = $this->withAgeVerification()->get(route('escorts.index', ['language' => 'en']));

        $escorts = $response->viewData('escorts');
        $this->assertEquals(1, $escorts->total());
        $this->assertContains('en', $escorts->first()->languages);
    }

    public function test_filter_by_service(): void
    {
        $this->createEscort(['services' => ['Massage', 'GFE']]);
        $this->createEscort(['services' => ['Dinner Date']]);

        $response = $this->withAgeVerification()->get(route('escorts.index', ['service' => 'Massage']));

        $escorts = $response->viewData('escorts');
        $this->assertEquals(1, $escorts->total());
    }

    public function test_filter_by_age_range(): void
    {
        $this->createEscort(['age' => 22]);
        $this->createEscort(['age' => 35]);
        $this->createEscort(['age' => 40]);

        $response = $this->withAgeVerification()->get(route('escorts.index', ['age_min' => 20, 'age_max' => 30]));

        $escorts = $response->viewData('escorts');
        $this->assertEquals(1, $escorts->total());
        $this->assertEquals(22, $escorts->first()->age);
    }

    public function test_filter_verified_only(): void
    {
        $this->createEscort(['is_verified' => true]);
        $this->createEscort(['is_verified' => false]);

        $response = $this->withAgeVerification()->get(route('escorts.index', ['verified' => '1']));

        $escorts = $response->viewData('escorts');
        $this->assertEquals(1, $escorts->total());
        $this->assertTrue($escorts->first()->is_verified);
    }

    public function test_excludes_inactive_profiles(): void
    {
        $this->createEscort(['is_active' => true]);
        $this->createEscort(['is_active' => false]);

        $response = $this->withAgeVerification()->get(route('escorts.index'));

        $escorts = $response->viewData('escorts');
        $this->assertEquals(1, $escorts->total());
    }

    public function test_sort_by_newest(): void
    {
        $old = $this->createEscort(['created_at' => now()->subDays(5)]);
        $new = $this->createEscort(['created_at' => now()]);

        $response = $this->withAgeVerification()->get(route('escorts.index', ['sort' => 'newest']));

        $escorts = $response->viewData('escorts');
        $this->assertEquals($new->id, $escorts->first()->id);
    }

    public function test_sort_by_rating(): void
    {
        $low = $this->createEscort(['avg_rating' => 2.5]);
        $high = $this->createEscort(['avg_rating' => 4.8]);

        $response = $this->withAgeVerification()->get(route('escorts.index', ['sort' => 'rating']));

        $escorts = $response->viewData('escorts');
        $this->assertEquals($high->id, $escorts->first()->id);
    }

    public function test_sort_by_reviews(): void
    {
        $few = $this->createEscort(['reviews_count' => 2]);
        $many = $this->createEscort(['reviews_count' => 50]);

        $response = $this->withAgeVerification()->get(route('escorts.index', ['sort' => 'reviews']));

        $escorts = $response->viewData('escorts');
        $this->assertEquals($many->id, $escorts->first()->id);
    }

    public function test_sort_by_views(): void
    {
        $few = $this->createEscort(['views_count' => 10]);
        $many = $this->createEscort(['views_count' => 500]);

        $response = $this->withAgeVerification()->get(route('escorts.index', ['sort' => 'views']));

        $escorts = $response->viewData('escorts');
        $this->assertEquals($many->id, $escorts->first()->id);
    }

    public function test_pagination_displays(): void
    {
        for ($i = 0; $i < 15; $i++) {
            $this->createEscort();
        }

        $response = $this->withAgeVerification()->get(route('escorts.index'));

        $escorts = $response->viewData('escorts');
        $this->assertEquals(15, $escorts->total());
        $this->assertEquals(12, $escorts->perPage());
        $this->assertTrue($escorts->hasMorePages());
    }

    public function test_filter_options_are_populated(): void
    {
        $this->createEscort(['city' => 'Berlin', 'nationality' => 'German', 'languages' => ['de'], 'services' => ['Massage']]);

        $response = $this->withAgeVerification()->get(route('escorts.index'));

        $this->assertTrue($response->viewData('cities')->contains('Berlin'));
        $this->assertTrue($response->viewData('nationalities')->contains('German'));
        $this->assertTrue($response->viewData('languages')->contains('de'));
        $this->assertTrue($response->viewData('services')->contains('Massage'));
    }

    public function test_combined_filters(): void
    {
        $this->createEscort(['city' => 'Berlin', 'nationality' => 'German', 'age' => 25, 'is_verified' => true]);
        $this->createEscort(['city' => 'Berlin', 'nationality' => 'French', 'age' => 30, 'is_verified' => false]);
        $this->createEscort(['city' => 'München', 'nationality' => 'German', 'age' => 28, 'is_verified' => true]);

        $response = $this->withAgeVerification()->get(route('escorts.index', [
            'city' => 'Berlin',
            'nationality' => 'German',
            'verified' => '1',
        ]));

        $escorts = $response->viewData('escorts');
        $this->assertEquals(1, $escorts->total());
    }

    public function test_price_filter_shown_when_feature_enabled(): void
    {
        $this->tenant->update(['settings' => ['features' => ['show_price_filter' => true]]]);

        $response = $this->withAgeVerification()->get(route('escorts.index'));

        $response->assertOk();
        $response->assertSee(__('Price'));
    }

    public function test_price_filter_hidden_when_feature_disabled(): void
    {
        $this->tenant->update(['settings' => ['features' => ['show_price_filter' => false]]]);

        $response = $this->withAgeVerification()->get(route('escorts.index'));

        $response->assertOk();
        $response->assertDontSee('Price (€)');
    }

    public function test_filter_by_price_min(): void
    {
        $this->tenant->update(['settings' => ['features' => ['show_price_filter' => true]]]);

        $this->createEscort(['rates' => ['30 min' => 80, '1 hour' => 150]]);
        $this->createEscort(['rates' => ['30 min' => 200, '1 hour' => 350]]);

        $response = $this->withAgeVerification()->get(route('escorts.index', ['price_min' => 200]));

        $escorts = $response->viewData('escorts');
        $this->assertEquals(1, $escorts->total());
    }

    public function test_filter_by_price_max(): void
    {
        $this->tenant->update(['settings' => ['features' => ['show_price_filter' => true]]]);

        $this->createEscort(['rates' => ['30 min' => 80, '1 hour' => 150]]);
        $this->createEscort(['rates' => ['30 min' => 200, '1 hour' => 350]]);

        $response = $this->withAgeVerification()->get(route('escorts.index', ['price_max' => 100]));

        $escorts = $response->viewData('escorts');
        $this->assertEquals(1, $escorts->total());
    }

    public function test_price_filter_ignored_when_feature_disabled(): void
    {
        $this->tenant->update(['settings' => ['features' => ['show_price_filter' => false]]]);

        $this->createEscort(['rates' => ['30 min' => 80]]);
        $this->createEscort(['rates' => ['30 min' => 300]]);

        $response = $this->withAgeVerification()->get(route('escorts.index', ['price_min' => 200]));

        $escorts = $response->viewData('escorts');
        $this->assertEquals(2, $escorts->total());
    }

    public function test_service_tags_are_clickable_when_feature_enabled(): void
    {
        $this->tenant->update(['settings' => ['features' => ['show_service_tags' => true]]]);
        $this->createEscort(['services' => ['Massage']]);

        $response = $this->withAgeVerification()->get(route('escorts.index'));

        $response->assertOk();
        $response->assertSee(route('escorts.index', ['service' => 'Massage']));
    }
}
