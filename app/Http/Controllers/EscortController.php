<?php

namespace App\Http\Controllers;

use App\Models\EscortProfile;
use Illuminate\Http\Request;

class EscortController extends Controller
{
    public function index(Request $request)
    {
        $query = EscortProfile::with('photos')->where('is_active', true);

        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        if ($request->filled('service')) {
            $query->where('services', 'like', '%' . $request->service . '%');
        }

        if ($request->filled('verified')) {
            $query->where('is_verified', true);
        }

        if ($request->filled('nationality')) {
            $query->where('nationality', $request->nationality);
        }

        if ($request->filled('language')) {
            $query->whereJsonContains('languages', $request->language);
        }

        if ($request->filled('age_min')) {
            $query->where('age', '>=', (int) $request->age_min);
        }

        if ($request->filled('age_max')) {
            $query->where('age', '<=', (int) $request->age_max);
        }

        $sort = $request->get('sort', 'top');
        $query = match ($sort) {
            'rating' => $query->orderByDesc('avg_rating'),
            'newest' => $query->orderByDesc('created_at'),
            'views' => $query->orderByDesc('views_count'),
            'reviews' => $query->orderByDesc('reviews_count'),
            default => $query->orderByRaw('CASE WHEN top_until > ? THEN 0 ELSE 1 END', [now()])
                ->orderByRaw('CASE WHEN featured_until > ? THEN 0 ELSE 1 END', [now()])
                ->orderByDesc('avg_rating'),
        };

        $escorts = $query->paginate(12);

        $activeProfiles = EscortProfile::where('is_active', true);

        $cities = (clone $activeProfiles)->select('city')->distinct()->orderBy('city')->pluck('city');
        $nationalities = (clone $activeProfiles)->whereNotNull('nationality')->select('nationality')->distinct()->orderBy('nationality')->pluck('nationality');
        $languages = (clone $activeProfiles)->whereNotNull('languages')->pluck('languages')->flatten()->unique()->sort()->values();
        $services = (clone $activeProfiles)->whereNotNull('services')->pluck('services')->flatten()->unique()->sort()->values();

        return view('escorts.index', compact('escorts', 'cities', 'nationalities', 'languages', 'services'));
    }

    public function show(EscortProfile $escortProfile)
    {
        $escortProfile->load(['photos', 'user']);
        $escortProfile->increment('views_count');

        $reviews = $escortProfile->reviews()
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate(10, ['*'], 'reviews_page');

        $blogPosts = null;
        if ($escortProfile->blogThread) {
            $escortProfile->load('blogThread');
            $blogPosts = $escortProfile->blogThread->posts()
                ->with('user')
                ->orderBy('created_at')
                ->paginate(10, ['*'], 'blog_page');
        }

        return view('escorts.show', compact('escortProfile', 'reviews', 'blogPosts'));
    }
}
