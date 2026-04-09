<?php

namespace App\Http\Controllers;

use App\Models\EscortProfile;
use App\Models\Thread;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q', '');
        $type = $request->get('type', 'escorts');
        $escorts = collect();
        $threads = collect();

        if (strlen($query) >= 2) {
            if ($type === 'escorts' || $type === 'all') {
                $escorts = EscortProfile::with('photos')
                    ->where('is_active', true)
                    ->where(function ($q) use ($query) {
                        $q->where('display_name', 'like', "%{$query}%")
                            ->orWhere('city', 'like', "%{$query}%")
                            ->orWhere('description', 'like', "%{$query}%")
                            ->orWhere('services', 'like', "%{$query}%");
                    })
                    ->orderByDesc('avg_rating')
                    ->take(20)
                    ->get();
            }

            if ($type === 'forum' || $type === 'all') {
                $threads = Thread::with(['user', 'category'])
                    ->where(function ($q) use ($query) {
                        $q->where('title', 'like', "%{$query}%")
                            ->orWhere('body', 'like', "%{$query}%");
                    })
                    ->orderByDesc('created_at')
                    ->take(20)
                    ->get();
            }
        }

        return view('search.index', compact('query', 'type', 'escorts', 'threads'));
    }
}
