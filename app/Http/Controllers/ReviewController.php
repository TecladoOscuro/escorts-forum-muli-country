<?php

namespace App\Http\Controllers;

use App\Models\EscortProfile;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'escortProfile']);

        if ($request->filled('rating')) {
            $query->where('rating', (int) $request->rating);
        }

        if ($request->filled('escort')) {
            $query->where('escort_profile_id', $request->escort);
        }

        if ($request->filled('city')) {
            $query->whereHas('escortProfile', fn ($q) => $q->where('city', $request->city));
        }

        $sort = $request->get('sort', 'newest');
        $query = match ($sort) {
            'oldest' => $query->orderBy('created_at'),
            'rating_high' => $query->orderByDesc('rating')->orderByDesc('created_at'),
            'rating_low' => $query->orderBy('rating')->orderByDesc('created_at'),
            default => $query->orderByDesc('created_at'),
        };

        $reviews = $query->paginate(15);

        $cities = EscortProfile::where('is_active', true)
            ->select('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        $ratingStats = Review::selectRaw('rating, count(*) as count')
            ->groupBy('rating')
            ->orderByDesc('rating')
            ->pluck('count', 'rating');

        $totalReviews = Review::count();
        $avgRating = Review::avg('rating');

        return view('reviews.index', compact('reviews', 'cities', 'ratingStats', 'totalReviews', 'avgRating'));
    }

    public function create(EscortProfile $escortProfile)
    {
        return view('reviews.create', compact('escortProfile'));
    }

    public function store(Request $request, EscortProfile $escortProfile)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'title' => 'required|string|max:255',
            'body' => 'required|string|min:20',
            'visit_date' => 'required|date|before_or_equal:today',
        ]);

        Review::create([
            'tenant_id' => app('currentTenant')->id,
            'user_id' => auth()->id(),
            'escort_profile_id' => $escortProfile->id,
            'rating' => $validated['rating'],
            'title' => $validated['title'],
            'body' => $validated['body'],
            'visit_date' => $validated['visit_date'],
        ]);

        // Update escort avg rating
        $escortProfile->update([
            'avg_rating' => $escortProfile->reviews()->avg('rating'),
            'reviews_count' => $escortProfile->reviews()->count(),
        ]);

        return redirect()->route('escorts.show', $escortProfile)
            ->with('success', __('Review published successfully!'));
    }
}
