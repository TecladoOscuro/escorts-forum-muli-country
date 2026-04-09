<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\EscortProfile;
use App\Models\Post;
use App\Models\Review;
use App\Models\Thread;
use App\Models\User;
use Tests\TestCase;

class EscortShowTest extends TestCase
{
    private function createEscortWithUser(array $attributes = []): EscortProfile
    {
        $user = User::factory()->create(['tenant_id' => $this->tenant->id]);
        return EscortProfile::factory()->create(array_merge([
            'tenant_id' => $this->tenant->id,
            'user_id' => $user->id,
        ], $attributes));
    }

    public function test_escort_profile_page_loads(): void
    {
        $escort = $this->createEscortWithUser(['display_name' => 'TestEscort']);

        $response = $this->withAgeVerification()->get(route('escorts.show', $escort));

        $response->assertOk();
        $response->assertSee('TestEscort');
    }

    public function test_increments_view_count(): void
    {
        $escort = $this->createEscortWithUser(['views_count' => 10]);

        $this->withAgeVerification()->get(route('escorts.show', $escort));

        $this->assertEquals(11, $escort->fresh()->views_count);
    }

    public function test_reviews_are_paginated(): void
    {
        $escort = $this->createEscortWithUser();

        for ($i = 0; $i < 15; $i++) {
            $reviewer = User::factory()->create(['tenant_id' => $this->tenant->id]);
            Review::factory()->create([
                'tenant_id' => $this->tenant->id,
                'user_id' => $reviewer->id,
                'escort_profile_id' => $escort->id,
            ]);
        }

        $response = $this->withAgeVerification()->get(route('escorts.show', $escort));

        $reviews = $response->viewData('reviews');
        $this->assertEquals(15, $reviews->total());
        $this->assertEquals(10, $reviews->perPage());
        $this->assertTrue($reviews->hasMorePages());
    }

    public function test_reviews_second_page(): void
    {
        $escort = $this->createEscortWithUser();

        for ($i = 0; $i < 15; $i++) {
            $reviewer = User::factory()->create(['tenant_id' => $this->tenant->id]);
            Review::factory()->create([
                'tenant_id' => $this->tenant->id,
                'user_id' => $reviewer->id,
                'escort_profile_id' => $escort->id,
            ]);
        }

        $response = $this->withAgeVerification()->get(route('escorts.show', $escort, ['reviews_page' => 2]));

        $response->assertOk();
    }

    public function test_blog_posts_are_paginated(): void
    {
        $escort = $this->createEscortWithUser();

        $category = Category::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Blogs',
            'slug' => 'blogs',
            'type' => 'blog',
        ]);

        $blogThread = Thread::create([
            'tenant_id' => $this->tenant->id,
            'category_id' => $category->id,
            'user_id' => $escort->user_id,
            'escort_profile_id' => $escort->id,
            'title' => 'My Blog',
            'slug' => 'my-blog',
            'body' => 'Blog content here',
            'type' => 'blog',
        ]);

        for ($i = 0; $i < 15; $i++) {
            $commenter = User::factory()->create(['tenant_id' => $this->tenant->id]);
            Post::create([
                'tenant_id' => $this->tenant->id,
                'thread_id' => $blogThread->id,
                'user_id' => $commenter->id,
                'body' => "Comment $i",
            ]);
        }

        $response = $this->withAgeVerification()->get(route('escorts.show', $escort));

        $blogPosts = $response->viewData('blogPosts');
        $this->assertNotNull($blogPosts);
        $this->assertEquals(15, $blogPosts->total());
        $this->assertTrue($blogPosts->hasMorePages());
    }

    public function test_shows_services_and_languages(): void
    {
        $escort = $this->createEscortWithUser([
            'services' => ['Massage', 'GFE'],
            'languages' => ['de', 'en'],
        ]);

        $response = $this->withAgeVerification()->get(route('escorts.show', $escort));

        $response->assertOk();
        $response->assertSee('Massage');
        $response->assertSee('GFE');
    }

    public function test_shows_verified_badge(): void
    {
        $escort = $this->createEscortWithUser(['is_verified' => true]);

        $response = $this->withAgeVerification()->get(route('escorts.show', $escort));

        $response->assertSee(__('Verified'));
    }

    public function test_no_blog_tab_without_blog(): void
    {
        $escort = $this->createEscortWithUser();

        $response = $this->withAgeVerification()->get(route('escorts.show', $escort));

        $blogPosts = $response->viewData('blogPosts');
        $this->assertNull($blogPosts);
    }

    public function test_service_tags_are_clickable_links(): void
    {
        $this->tenant->update(['settings' => ['features' => ['show_service_tags' => true]]]);
        $escort = $this->createEscortWithUser(['services' => ['Massage', 'GFE']]);

        $response = $this->withAgeVerification()->get(route('escorts.show', $escort));

        $response->assertOk();
        $response->assertSee(route('escorts.index', ['service' => 'Massage']));
        $response->assertSee(route('escorts.index', ['service' => 'GFE']));
    }

    public function test_service_tags_not_clickable_when_disabled(): void
    {
        $this->tenant->update(['settings' => ['features' => ['show_service_tags' => false]]]);
        $escort = $this->createEscortWithUser(['services' => ['Massage']]);

        $response = $this->withAgeVerification()->get(route('escorts.show', $escort));

        $response->assertOk();
        $response->assertSee('Massage');
        $response->assertDontSee(route('escorts.index', ['service' => 'Massage']));
    }

    public function test_whatsapp_button_shown_when_feature_enabled(): void
    {
        $this->tenant->update(['settings' => ['features' => ['show_contact_buttons' => true]]]);
        $escort = $this->createEscortWithUser(['contact_phone' => '+4917612345678']);

        $response = $this->withAgeVerification()->get(route('escorts.show', $escort));

        $response->assertOk();
        $response->assertSee('wa.me/4917612345678');
        $response->assertSee('WhatsApp');
    }

    public function test_telegram_button_shown_when_feature_enabled(): void
    {
        $this->tenant->update(['settings' => ['features' => ['show_contact_buttons' => true]]]);
        $escort = $this->createEscortWithUser(['contact_telegram' => '@testuser']);

        $response = $this->withAgeVerification()->get(route('escorts.show', $escort));

        $response->assertOk();
        $response->assertSee('t.me/testuser');
        $response->assertSee('Telegram');
    }

    public function test_contact_buttons_hidden_when_feature_disabled(): void
    {
        $this->tenant->update(['settings' => ['features' => ['show_contact_buttons' => false]]]);
        $escort = $this->createEscortWithUser([
            'contact_phone' => '+4917612345678',
            'contact_telegram' => '@testuser',
        ]);

        $response = $this->withAgeVerification()->get(route('escorts.show', $escort));

        $response->assertOk();
        $response->assertDontSee('wa.me/');
        $response->assertDontSee('t.me/');
    }

    public function test_prices_shown_when_feature_enabled(): void
    {
        $this->tenant->update(['settings' => ['features' => ['show_prices' => true]]]);
        $escort = $this->createEscortWithUser(['rates' => ['30 min' => 100, '1 hour' => 200]]);

        $response = $this->withAgeVerification()->get(route('escorts.show', $escort));

        $response->assertOk();
        $response->assertSee(__('Prices'));
        $response->assertSee('100');
        $response->assertSee('200');
    }

    public function test_prices_hidden_when_feature_disabled(): void
    {
        $this->tenant->update(['settings' => ['features' => ['show_prices' => false]]]);
        $escort = $this->createEscortWithUser(['rates' => ['30 min' => 100]]);

        $response = $this->withAgeVerification()->get(route('escorts.show', $escort));

        $response->assertOk();
        $response->assertDontSee(__('Prices'));
    }
}
