<?php

namespace App\Http\Controllers;

use App\Models\EscortProfile;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['user', 'escortProfile'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('reviews.index', compact('reviews'));
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
            ->with('success', 'Bewertung erfolgreich veröffentlicht!');
    }
}
