<x-app-layout :title="__('Homepage')" :metaDescription="__('Escort Forum Germany - Reviews, Blogs and Community')">

    {{-- Hero Section --}}
    <section class="text-center py-8 md:py-12">
        <h1 class="text-3xl md:text-4xl font-bold text-[var(--color-text)] mb-3">
            {{ __('Welcome to') }} <span class="text-[var(--color-accent)]">ForumEscort</span>
        </h1>
        <p class="text-[var(--color-text-secondary)] text-lg max-w-2xl mx-auto">
            {{ __('The community for honest reviews, escort blogs and discussions in Germany.') }}
        </p>
    </section>

    {{-- Featured Escorts --}}
    @if($featuredEscorts->isNotEmpty())
        <section class="mb-10">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-xl font-bold text-[var(--color-text)]">{{ __('Top Escorts') }}</h2>
                <a href="{{ route('escorts.index') }}" class="text-sm text-[var(--color-accent)] hover:underline">{{ __('Show all') }} &rarr;</a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($featuredEscorts as $escort)
                    <x-escort-card :escort="$escort" />
                @endforeach
            </div>
        </section>
    @endif

    <div class="grid md:grid-cols-2 gap-8">
        {{-- Recent Forum Threads --}}
        <section>
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-xl font-bold text-[var(--color-text)]">{{ __('Latest Discussions') }}</h2>
                <a href="{{ route('forum.index') }}" class="text-sm text-[var(--color-accent)] hover:underline">{{ __('Go to Forum') }} &rarr;</a>
            </div>
            <div class="glass-card divide-y divide-[var(--color-border)]">
                @forelse($recentThreads as $thread)
                    <a href="{{ route('forum.thread', [$thread->category, $thread]) }}" class="flex items-start gap-3 p-4 hover:bg-[var(--color-surface-hover)] transition-colors">
                        <img src="{{ $thread->user->avatar_url }}" alt="" class="w-10 h-10 rounded-full flex-shrink-0">
                        <div class="min-w-0 flex-1">
                            <h3 class="text-sm font-medium text-[var(--color-text)] truncate">{{ $thread->title }}</h3>
                            <div class="flex items-center gap-3 mt-1 text-xs text-[var(--color-text-secondary)]">
                                <span>{{ $thread->user->username }}</span>
                                <span>{{ $thread->category->name }}</span>
                                <span>{{ $thread->last_reply_at?->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="text-xs text-[var(--color-text-secondary)] flex items-center gap-1 flex-shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            {{ $thread->replies_count }}
                        </div>
                    </a>
                @empty
                    <p class="p-4 text-sm text-[var(--color-text-secondary)]">{{ __('No discussions yet.') }}</p>
                @endforelse
            </div>
        </section>

        {{-- Recent Reviews --}}
        <section>
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-xl font-bold text-[var(--color-text)]">{{ __('Latest Reviews') }}</h2>
                <a href="{{ route('reviews.index') }}" class="text-sm text-[var(--color-accent)] hover:underline">{{ __('Show all') }} &rarr;</a>
            </div>
            <div class="glass-card divide-y divide-[var(--color-border)]">
                @forelse($recentReviews as $review)
                    <div class="p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="flex items-center gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'star-filled' : 'star-empty' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-xs text-[var(--color-text-secondary)]">{{ __('by') }} {{ $review->user->username }}</span>
                        </div>
                        <h3 class="text-sm font-medium text-[var(--color-text)]">{{ $review->title }}</h3>
                        <p class="text-xs text-[var(--color-text-secondary)] mt-1 line-clamp-2">{{ $review->body }}</p>
                        @if($review->escortProfile)
                            <a href="{{ route('escorts.show', $review->escortProfile) }}" class="inline-block mt-2 text-xs text-[var(--color-accent)] hover:underline">
                                {{ $review->escortProfile->display_name }} &rarr;
                            </a>
                        @endif
                    </div>
                @empty
                    <p class="p-4 text-sm text-[var(--color-text-secondary)]">{{ __('No reviews yet.') }}</p>
                @endforelse
            </div>
        </section>
    </div>

    {{-- Stats --}}
    <section class="mt-10 grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="glass-card p-4 text-center">
            <p class="text-2xl font-bold text-[var(--color-accent)]">{{ \App\Models\EscortProfile::where('is_active', true)->count() }}</p>
            <p class="text-xs text-[var(--color-text-secondary)] mt-1">{{ __('Active Escorts') }}</p>
        </div>
        <div class="glass-card p-4 text-center">
            <p class="text-2xl font-bold text-[var(--color-gold)]">{{ \App\Models\Review::count() }}</p>
            <p class="text-xs text-[var(--color-text-secondary)] mt-1">{{ __('Reviews') }}</p>
        </div>
        <div class="glass-card p-4 text-center">
            <p class="text-2xl font-bold text-[var(--color-success)]">{{ \App\Models\Thread::count() }}</p>
            <p class="text-xs text-[var(--color-text-secondary)] mt-1">{{ __('Forum Posts') }}</p>
        </div>
        <div class="glass-card p-4 text-center">
            <p class="text-2xl font-bold text-[var(--color-verified)]">{{ \App\Models\User::count() }}</p>
            <p class="text-xs text-[var(--color-text-secondary)] mt-1">{{ __('Members') }}</p>
        </div>
    </section>
</x-app-layout>
