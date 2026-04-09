<?php

namespace Tests\Feature;

use Tests\TestCase;

class LocaleSwitchTest extends TestCase
{
    public function test_can_switch_to_english(): void
    {
        $response = $this->get(route('locale.switch', 'en'));

        $response->assertRedirect('/');
        $this->assertEquals('en', session('locale'));
    }

    public function test_can_switch_to_german(): void
    {
        $response = $this->withSession(['locale' => 'en'])
            ->get(route('locale.switch', 'de'));

        $response->assertRedirect('/');
        $this->assertEquals('de', session('locale'));
    }

    public function test_rejects_invalid_locale(): void
    {
        $response = $this->get(route('locale.switch', 'fr'));

        $response->assertRedirect('/');
        $this->assertNull(session('locale'));
    }

    public function test_locale_persists_across_requests(): void
    {
        $this->get(route('locale.switch', 'en'));

        $response = $this->withAgeVerification()
            ->withSession(['locale' => 'en'])
            ->get(route('escorts.index'));

        $response->assertOk();
        $response->assertSee('Escorts');
    }

    public function test_german_translations_on_escorts_page(): void
    {
        $response = $this->withAgeVerification()
            ->withSession(['locale' => 'de'])
            ->get(route('escorts.index'));

        $response->assertOk();
        $response->assertSee('Alle Städte');
        $response->assertSee('Filtern');
    }

    public function test_english_translations_on_escorts_page(): void
    {
        $response = $this->withAgeVerification()
            ->withSession(['locale' => 'en'])
            ->get(route('escorts.index'));

        $response->assertOk();
        $response->assertSee('All Cities');
        $response->assertSee('Apply Filter');
    }

    public function test_german_translations_on_reviews_page(): void
    {
        $response = $this->withAgeVerification()
            ->withSession(['locale' => 'de'])
            ->get(route('reviews.index'));

        $response->assertOk();
        $response->assertSee('Alle Bewertungen');
        $response->assertSee('Nach Bewertung filtern');
    }

    public function test_english_translations_on_reviews_page(): void
    {
        $response = $this->withAgeVerification()
            ->withSession(['locale' => 'en'])
            ->get(route('reviews.index'));

        $response->assertOk();
        $response->assertSee('All Reviews');
        $response->assertSee('Filter by Rating');
    }

    public function test_price_filter_translated_german(): void
    {
        $this->tenant->update(['settings' => ['features' => ['show_price_filter' => true]]]);

        $response = $this->withAgeVerification()
            ->withSession(['locale' => 'de'])
            ->get(route('escorts.index'));

        $response->assertOk();
        $response->assertSee('Preis');
    }

    public function test_price_filter_translated_english(): void
    {
        $this->tenant->update(['settings' => ['features' => ['show_price_filter' => true]]]);

        $response = $this->withAgeVerification()
            ->withSession(['locale' => 'en'])
            ->get(route('escorts.index'));

        $response->assertOk();
        $response->assertSee('Price');
    }
}
