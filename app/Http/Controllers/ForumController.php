<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Thread;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    public function index()
    {
        $categories = Category::whereNull('parent_id')
            ->where('type', 'forum')
            ->withCount('threads')
            ->orderBy('sort_order')
            ->get();

        $recentThreads = Thread::with(['user', 'category'])
            ->whereHas('category', fn ($q) => $q->where('type', 'forum'))
            ->orderByDesc('is_pinned')
            ->orderByDesc('last_reply_at')
            ->take(10)
            ->get();

        return view('forum.index', compact('categories', 'recentThreads'));
    }

    public function category(Category $category)
    {
        $threads = $category->threads()
            ->with(['user', 'lastReplyBy'])
            ->orderByDesc('is_pinned')
            ->orderByDesc('last_reply_at')
            ->paginate(20);

        return view('forum.category', compact('category', 'threads'));
    }

    public function thread(Category $category, Thread $thread)
    {
        $thread->load(['user', 'escortProfile']);
        $thread->increment('views_count');

        $posts = $thread->posts()
            ->with('user')
            ->orderBy('created_at')
            ->paginate(20);

        return view('forum.thread', compact('category', 'thread', 'posts'));
    }

    public function create(Category $category)
    {
        return view('forum.create', compact('category'));
    }

    public function store(Request $request, Category $category)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:5|max:255',
            'body' => 'required|string|min:10',
        ]);

        $thread = Thread::create([
            'tenant_id' => app('currentTenant')->id,
            'category_id' => $category->id,
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'body' => $validated['body'],
            'type' => 'discussion',
            'last_reply_at' => now(),
        ]);

        return redirect()->route('forum.thread', [$category, $thread]);
    }

    public function reply(Request $request, Category $category, Thread $thread)
    {
        $validated = $request->validate([
            'body' => 'required|string|min:3',
        ]);

        Post::create([
            'tenant_id' => app('currentTenant')->id,
            'thread_id' => $thread->id,
            'user_id' => auth()->id(),
            'body' => $validated['body'],
        ]);

        $thread->update([
            'last_reply_at' => now(),
            'last_reply_by' => auth()->id(),
        ]);
        $thread->increment('replies_count');

        return redirect()->route('forum.thread', [$category, $thread]);
    }
}
