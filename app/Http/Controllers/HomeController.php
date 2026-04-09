<?php

namespace App\Http\Controllers;

use App\Models\EscortProfile;
use App\Models\Review;
use App\Models\Thread;

class HomeController extends Controller
{
    public function index()
    {
        $featuredEscorts = EscortProfile::with('photos')
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNotNull('featured_until')
                    ->where('featured_until', '>', now());
            })
            ->orderByDesc('featured_until')
            ->take(4)
            ->get();

        if ($featuredEscorts->count() < 4) {
            $moreEscorts = EscortProfile::with('photos')
                ->where('is_active', true)
                ->whereNotIn('id', $featuredEscorts->pluck('id'))
                ->orderByDesc('views_count')
                ->take(4 - $featuredEscorts->count())
                ->get();
            $featuredEscorts = $featuredEscorts->merge($moreEscorts);
        }

        $recentThreads = Thread::with(['user', 'category'])
            ->where('type', '!=', 'blog')
            ->orderByDesc('last_reply_at')
            ->take(5)
            ->get();

        $recentReviews = Review::with(['user', 'escortProfile'])
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('home', compact('featuredEscorts', 'recentThreads', 'recentReviews'));
    }
}
