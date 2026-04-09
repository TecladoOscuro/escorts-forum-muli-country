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

        $sort = $request->get('sort', 'top');
        $query = match ($sort) {
            'rating' => $query->orderByDesc('avg_rating'),
            'newest' => $query->orderByDesc('created_at'),
            'views' => $query->orderByDesc('views_count'),
            default => $query->orderByRaw('CASE WHEN top_until > ? THEN 0 ELSE 1 END', [now()])
                ->orderByRaw('CASE WHEN featured_until > ? THEN 0 ELSE 1 END', [now()])
                ->orderByDesc('avg_rating'),
        };

        $escorts = $query->paginate(12);

        $cities = EscortProfile::where('is_active', true)
            ->select('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        return view('escorts.index', compact('escorts', 'cities'));
    }

    public function show(EscortProfile $escortProfile)
    {
        $escortProfile->load(['photos', 'reviews.user', 'blogThread.posts.user', 'user']);
        $escortProfile->increment('views_count');

        return view('escorts.show', compact('escortProfile'));
    }
}
