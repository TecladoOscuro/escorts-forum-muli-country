<x-app-layout :title="__('Search')">

    <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold text-[var(--color-text)] mb-6">{{ __('Search') }}</h1>

        {{-- Search Form --}}
        <form action="{{ route('search') }}" method="GET" class="glass-card p-4 mb-6">
            <div class="flex gap-3">
                <input type="text" name="q" value="{{ $query }}" autofocus
                    class="flex-1 bg-[var(--color-surface)] border border-[var(--color-border)] rounded-lg px-4 py-2.5 text-[var(--color-text)] placeholder-[var(--color-text-secondary)] focus:outline-none focus:border-[var(--color-accent)] transition-colors"
                    placeholder="{{ __('Search for escorts, cities, topics...') }}">
                <button type="submit" class="px-6 py-2.5 accent-gradient text-white font-medium rounded-lg hover:opacity-90 btn-press">
                    {{ __('Search') }}
                </button>
            </div>
            <div class="flex gap-3 mt-3">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="type" value="escorts" {{ $type === 'escorts' ? 'checked' : '' }} class="text-[var(--color-accent)] focus:ring-[var(--color-accent)]">
                    <span class="text-sm text-[var(--color-text-secondary)]">{{ __('Escorts') }}</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="type" value="forum" {{ $type === 'forum' ? 'checked' : '' }} class="text-[var(--color-accent)] focus:ring-[var(--color-accent)]">
                    <span class="text-sm text-[var(--color-text-secondary)]">{{ __('Forum') }}</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="type" value="all" {{ $type === 'all' ? 'checked' : '' }} class="text-[var(--color-accent)] focus:ring-[var(--color-accent)]">
                    <span class="text-sm text-[var(--color-text-secondary)]">{{ __('Everything') }}</span>
                </label>
            </div>
        </form>

        @if(strlen($query) >= 2)
            {{-- Escort Results --}}
            @if($escorts->isNotEmpty())
                <h2 class="text-sm font-semibold text-[var(--color-text-secondary)] uppercase tracking-wider mb-3">{{ __('Escorts') }} ({{ $escorts->count() }})</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
                    @foreach($escorts as $escort)
                        <x-escort-card :escort="$escort" />
                    @endforeach
                </div>
            @endif

            {{-- Thread Results --}}
            @if($threads->isNotEmpty())
                <h2 class="text-sm font-semibold text-[var(--color-text-secondary)] uppercase tracking-wider mb-3">{{ __('Forum Posts') }} ({{ $threads->count() }})</h2>
                <div class="glass-card divide-y divide-[var(--color-border)]">
                    @foreach($threads as $thread)
                        <a href="{{ route('forum.thread', [$thread->category, $thread]) }}" class="flex items-start gap-3 p-4 hover:bg-[var(--color-surface-hover)] transition-colors">
                            <img src="{{ $thread->user->avatar_url }}" alt="" class="w-10 h-10 rounded-full flex-shrink-0">
                            <div class="min-w-0">
                                <h3 class="text-sm font-medium text-[var(--color-text)]">{{ $thread->title }}</h3>
                                <p class="text-xs text-[var(--color-text-secondary)] mt-1 line-clamp-2">{{ Str::limit($thread->body, 150) }}</p>
                                <div class="flex items-center gap-2 mt-1 text-xs text-[var(--color-text-secondary)]">
                                    <span>{{ $thread->user->username }}</span>
                                    <span>{{ $thread->category->name }}</span>
                                    <span>{{ $thread->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            @if($escorts->isEmpty() && $threads->isEmpty())
                <div class="glass-card p-8 text-center">
                    <p class="text-[var(--color-text-secondary)]">{{ __('No results found for ":query".', ['query' => $query]) }}</p>
                </div>
            @endif
        @elseif(strlen($query) > 0)
            <div class="glass-card p-8 text-center">
                <p class="text-[var(--color-text-secondary)]">{{ __('Please enter at least 2 characters.') }}</p>
            </div>
        @endif
    </div>
</x-app-layout>
