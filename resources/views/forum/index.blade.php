<x-app-layout :title="__('Forum')" :metaDescription="__('Escort Forum Germany - Discussions and Exchange of Experiences')">

    <h1 class="text-2xl font-bold text-[var(--color-text)] mb-6">{{ __('Forum') }}</h1>

    <div class="grid md:grid-cols-3 gap-6">
        {{-- Categories --}}
        <div class="md:col-span-1">
            <h2 class="text-sm font-semibold text-[var(--color-text-secondary)] uppercase tracking-wider mb-3">{{ __('Categories') }}</h2>
            <div class="glass-card divide-y divide-[var(--color-border)]">
                @foreach($categories as $category)
                    <a href="{{ route('forum.category', $category) }}" class="flex items-center justify-between p-4 hover:bg-[var(--color-surface-hover)] transition-colors">
                        <div>
                            <h3 class="text-sm font-medium text-[var(--color-text)]">{{ $category->name }}</h3>
                            @if($category->description)
                                <p class="text-xs text-[var(--color-text-secondary)] mt-0.5">{{ $category->description }}</p>
                            @endif
                        </div>
                        <span class="text-xs text-[var(--color-text-secondary)] bg-[var(--color-surface-hover)] px-2 py-1 rounded-full">{{ $category->threads_count }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Recent Threads --}}
        <div class="md:col-span-2">
            <h2 class="text-sm font-semibold text-[var(--color-text-secondary)] uppercase tracking-wider mb-3">{{ __('Latest Posts') }}</h2>
            <div class="glass-card divide-y divide-[var(--color-border)]">
                @forelse($recentThreads as $thread)
                    <a href="{{ route('forum.thread', [$thread->category, $thread]) }}" class="flex items-start gap-3 p-4 hover:bg-[var(--color-surface-hover)] transition-colors">
                        <img src="{{ $thread->user->avatar_url }}" alt="" class="w-10 h-10 rounded-full flex-shrink-0">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2">
                                @if($thread->is_pinned)
                                    <span class="text-xs text-[var(--color-gold)]">📌</span>
                                @endif
                                <h3 class="text-sm font-medium text-[var(--color-text)] truncate">{{ $thread->title }}</h3>
                            </div>
                            <div class="flex items-center gap-3 mt-1 text-xs text-[var(--color-text-secondary)]">
                                <span>{{ $thread->user->username }}</span>
                                <span class="px-1.5 py-0.5 rounded bg-[var(--color-surface-hover)]">{{ $thread->category->name }}</span>
                                <span>{{ $thread->last_reply_at?->diffForHumans() ?? $thread->created_at->diffForHumans() }}</span>
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
                    <p class="p-4 text-sm text-[var(--color-text-secondary)]">{{ __('No posts yet.') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
