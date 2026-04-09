<x-app-layout :title="$category->name">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-[var(--color-text-secondary)] mb-6">
        <a href="{{ route('forum.index') }}" class="hover:text-[var(--color-accent)]">{{ __('Forum') }}</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-[var(--color-text)]">{{ $category->name }}</span>
    </nav>

    <div class="flex items-center justify-between mb-5">
        <div>
            <h1 class="text-2xl font-bold text-[var(--color-text)]">{{ $category->name }}</h1>
            @if($category->description)
                <p class="text-sm text-[var(--color-text-secondary)] mt-1">{{ $category->description }}</p>
            @endif
        </div>
        @auth
            <a href="{{ route('forum.create', $category) }}" class="px-4 py-2 accent-gradient text-white text-sm font-medium rounded-lg hover:opacity-90 btn-press">
                {{ __('New Topic') }}
            </a>
        @endauth
    </div>

    <div class="glass-card divide-y divide-[var(--color-border)]">
        @forelse($threads as $thread)
            <a href="{{ route('forum.thread', [$category, $thread]) }}" class="flex items-start gap-3 p-4 hover:bg-[var(--color-surface-hover)] transition-colors">
                <img src="{{ $thread->user->avatar_url }}" alt="" class="w-10 h-10 rounded-full flex-shrink-0">
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2">
                        @if($thread->is_pinned)
                            <span class="text-xs text-[var(--color-gold)]">📌 {{ __('Pinned') }}</span>
                        @endif
                        @if($thread->is_locked)
                            <span class="text-xs text-[var(--color-danger)]">🔒</span>
                        @endif
                    </div>
                    <h3 class="text-sm font-medium text-[var(--color-text)]">{{ $thread->title }}</h3>
                    <p class="text-xs text-[var(--color-text-secondary)] mt-1 line-clamp-1">{{ Str::limit($thread->body, 120) }}</p>
                    <div class="flex items-center gap-3 mt-2 text-xs text-[var(--color-text-secondary)]">
                        <span>{{ $thread->user->username }}</span>
                        <span>{{ $thread->created_at->diffForHumans() }}</span>
                        @if($thread->lastReplyBy)
                            <span>{{ __('Last reply: :username', ['username' => $thread->lastReplyBy->username]) }}</span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-4 text-xs text-[var(--color-text-secondary)] flex-shrink-0">
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        {{ $thread->views_count }}
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        {{ $thread->replies_count }}
                    </span>
                </div>
            </a>
        @empty
            <div class="p-8 text-center">
                <p class="text-sm text-[var(--color-text-secondary)]">{{ __('No topics in this category yet.') }}</p>
                @auth
                    <a href="{{ route('forum.create', $category) }}" class="mt-3 inline-block text-sm text-[var(--color-accent)] hover:underline">{{ __('Create first topic') }}</a>
                @endauth
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $threads->links() }}
    </div>
</x-app-layout>
