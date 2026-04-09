<x-app-layout :title="$thread->title">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-[var(--color-text-secondary)] mb-6 flex-wrap">
        <a href="{{ route('forum.index') }}" class="hover:text-[var(--color-accent)]">{{ __('Forum') }}</a>
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('forum.category', $category) }}" class="hover:text-[var(--color-accent)]">{{ $category->name }}</a>
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-[var(--color-text)] truncate">{{ $thread->title }}</span>
    </nav>

    {{-- Thread OP --}}
    <div class="glass-card p-6 mb-6">
        <div class="flex items-start gap-4">
            <img src="{{ $thread->user->avatar_url }}" alt="" class="w-12 h-12 rounded-full flex-shrink-0 hidden sm:block">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    @if($thread->is_pinned)
                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-[var(--color-gold)]/20 text-[var(--color-gold)]">{{ __('Pinned') }}</span>
                    @endif
                    @if($thread->isSponsored())
                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-[var(--color-accent)]/20 text-[var(--color-accent)]">{{ __('Sponsor') }}</span>
                    @endif
                </div>
                <h1 class="text-xl font-bold text-[var(--color-text)] mt-1">{{ $thread->title }}</h1>
                <div class="flex items-center gap-3 mt-2 text-xs text-[var(--color-text-secondary)]">
                    <img src="{{ $thread->user->avatar_url }}" alt="" class="w-5 h-5 rounded-full sm:hidden">
                    <span class="font-medium text-[var(--color-text)]">{{ $thread->user->username }}</span>
                    <span>{{ $thread->created_at->format('d.m.Y H:i') }}</span>
                    <span>{{ __(':count views', ['count' => $thread->views_count]) }}</span>
                </div>
                <div class="mt-4 text-sm text-[var(--color-text-secondary)] whitespace-pre-line">{{ $thread->body }}</div>

                @if($thread->escortProfile)
                    <a href="{{ route('escorts.show', $thread->escortProfile) }}" class="mt-4 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-[var(--color-surface-hover)] text-sm text-[var(--color-accent)] hover:bg-[var(--color-accent)]/10 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        {{ __('Profile of :name', ['name' => $thread->escortProfile->display_name]) }}
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Replies --}}
    @if($posts->isNotEmpty())
        <h2 class="text-sm font-semibold text-[var(--color-text-secondary)] uppercase tracking-wider mb-3">{{ __('Replies') }} ({{ $thread->replies_count }})</h2>
        <div class="space-y-3 mb-6">
            @foreach($posts as $post)
                <div class="glass-card p-5">
                    <div class="flex items-start gap-3">
                        <img src="{{ $post->user->avatar_url }}" alt="" class="w-9 h-9 rounded-full flex-shrink-0">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 text-xs text-[var(--color-text-secondary)]">
                                <span class="font-medium text-[var(--color-text)]">{{ $post->user->username }}</span>
                                <span>{{ $post->created_at->diffForHumans() }}</span>
                                @if($post->is_edited)
                                    <span class="italic">{{ __('(edited)') }}</span>
                                @endif
                            </div>
                            <p class="text-sm text-[var(--color-text-secondary)] mt-1.5 whitespace-pre-line">{{ $post->body }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{ $posts->links() }}
    @endif

    {{-- Reply Form --}}
    @auth
        @unless($thread->is_locked)
            <div class="glass-card p-5 mt-6">
                <h3 class="text-sm font-semibold text-[var(--color-text)] mb-3">{{ __('Write reply') }}</h3>
                <form method="POST" action="{{ route('forum.reply', [$category, $thread]) }}">
                    @csrf
                    <textarea name="body" rows="4" required placeholder="{{ __('Your reply...') }}"
                        class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-lg px-4 py-3 text-sm text-[var(--color-text)] placeholder-[var(--color-text-secondary)] focus:outline-none focus:border-[var(--color-accent)] transition-colors">{{ old('body') }}</textarea>
                    @error('body')
                        <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p>
                    @enderror
                    <button type="submit" class="mt-3 px-6 py-2 accent-gradient text-white text-sm font-medium rounded-lg hover:opacity-90 btn-press">
                        {{ __('Reply') }}
                    </button>
                </form>
            </div>
        @else
            <div class="glass-card p-5 mt-6 text-center text-sm text-[var(--color-text-secondary)]">
                {{ __('This topic is closed. No further replies possible.') }}
            </div>
        @endunless
    @else
        <div class="glass-card p-5 mt-6 text-center">
            <p class="text-sm text-[var(--color-text-secondary)]">
                <a href="{{ route('login') }}" class="text-[var(--color-accent)] hover:underline">{{ __('Log in') }}</a>{{ __('to reply.') }}
            </p>
        </div>
    @endauth
</x-app-layout>
