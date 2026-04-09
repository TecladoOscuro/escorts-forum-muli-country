<x-app-layout :title="__('All Reviews')" :metaDescription="__('Escort Reviews and Experience Reports in Germany')">

    <div class="flex flex-col md:flex-row gap-6">
        {{-- Filters Sidebar --}}
        <aside class="w-full md:w-72 flex-shrink-0" x-data="{ filtersOpen: false }">
            <button @click="filtersOpen = !filtersOpen" class="md:hidden w-full flex items-center justify-between p-3 glass-card mb-3">
                <span class="text-sm font-medium text-[var(--color-text)]">{{ __('Filter') }}</span>
                <svg class="w-5 h-5 text-[var(--color-text-secondary)]" :class="filtersOpen && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>

            <div :class="filtersOpen ? 'block' : 'hidden md:block'" class="space-y-4">
                {{-- Rating Summary Card --}}
                <div class="glass-card p-4">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="text-3xl font-bold text-[var(--color-accent)]">{{ $avgRating ? number_format($avgRating, 1) : '–' }}</span>
                        <div>
                            <div class="flex items-center gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= round($avgRating ?? 0) ? 'star-filled' : 'star-empty' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <p class="text-xs text-[var(--color-text-secondary)] mt-0.5">{{ $totalReviews }} {{ __('total') }}</p>
                        </div>
                    </div>

                    {{-- Rating Distribution Bars --}}
                    <div class="space-y-1.5">
                        @for($star = 5; $star >= 1; $star--)
                            @php $count = $ratingStats[$star] ?? 0; $pct = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0; @endphp
                            <a href="{{ route('reviews.index', array_merge(request()->except('rating', 'page'), ['rating' => $star])) }}"
                                class="flex items-center gap-2 group {{ request('rating') == $star ? 'opacity-100' : 'opacity-70 hover:opacity-100' }} transition-opacity">
                                <span class="text-xs text-[var(--color-text-secondary)] w-8 text-right">{{ $star }} <svg class="w-3 h-3 inline star-filled" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg></span>
                                <div class="flex-1 h-2 bg-[var(--color-surface-hover)] rounded-full overflow-hidden">
                                    <div class="h-full accent-gradient rounded-full transition-all" style="width: {{ $pct }}%"></div>
                                </div>
                                <span class="text-xs text-[var(--color-text-secondary)] w-6">{{ $count }}</span>
                            </a>
                        @endfor
                    </div>
                </div>

                {{-- Filter Form --}}
                <form action="{{ route('reviews.index') }}" method="GET" class="glass-card p-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">{{ __('Filter by Rating') }}</label>
                        <select name="rating" class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-lg px-3 py-2 text-sm text-[var(--color-text)] focus:outline-none focus:border-[var(--color-accent)]">
                            <option value="">{{ __('All Ratings') }}</option>
                            @for($star = 5; $star >= 1; $star--)
                                <option value="{{ $star }}" {{ request('rating') == $star ? 'selected' : '' }}>{{ __(':count stars', ['count' => $star]) }}</option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">{{ __('Filter by City') }}</label>
                        <select name="city" class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-lg px-3 py-2 text-sm text-[var(--color-text)] focus:outline-none focus:border-[var(--color-accent)]">
                            <option value="">{{ __('All Cities') }}</option>
                            @foreach($cities as $city)
                                <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">{{ __('Sort by') }}</label>
                        <select name="sort" class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-lg px-3 py-2 text-sm text-[var(--color-text)] focus:outline-none focus:border-[var(--color-accent)]">
                            <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>{{ __('Newest first') }}</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>{{ __('Oldest first') }}</option>
                            <option value="rating_high" {{ request('sort') == 'rating_high' ? 'selected' : '' }}>{{ __('Highest rating') }}</option>
                            <option value="rating_low" {{ request('sort') == 'rating_low' ? 'selected' : '' }}>{{ __('Lowest rating') }}</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full py-2 accent-gradient text-white text-sm font-medium rounded-lg hover:opacity-90 transition-opacity btn-press">
                        {{ __('Apply Filter') }}
                    </button>

                    @if(request()->hasAny(['rating', 'city', 'sort', 'escort']))
                        <a href="{{ route('reviews.index') }}" class="block text-center text-xs text-[var(--color-text-secondary)] hover:text-[var(--color-accent)]">{{ __('Clear filters') }}</a>
                    @endif
                </form>
            </div>
        </aside>

        {{-- Reviews List --}}
        <div class="flex-1">
            <div class="flex items-center justify-between mb-5">
                <h1 class="text-2xl font-bold text-[var(--color-text)]">{{ __('All Reviews') }}</h1>
                <p class="text-sm text-[var(--color-text-secondary)]">{{ __(':count results', ['count' => $reviews->total()]) }}</p>
            </div>

            @if($reviews->isNotEmpty())
                <div class="space-y-4">
                    @foreach($reviews as $review)
                        <div class="glass-card p-5">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $review->user->avatar_url }}" alt="" class="w-10 h-10 rounded-full">
                                    <div>
                                        <span class="text-sm font-medium text-[var(--color-text)]">{{ $review->user->username }}</span>
                                        <div class="flex items-center gap-1 mt-0.5">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'star-filled' : 'star-empty' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                @if($review->escortProfile)
                                    <a href="{{ route('escorts.show', $review->escortProfile) }}" class="text-sm text-[var(--color-accent)] hover:underline flex-shrink-0">
                                        {{ $review->escortProfile->display_name }}
                                    </a>
                                @endif
                            </div>
                            <h3 class="text-sm font-medium text-[var(--color-text)] mt-3">{{ $review->title }}</h3>
                            <p class="text-sm text-[var(--color-text-secondary)] mt-1">{{ $review->body }}</p>
                            <div class="flex items-center gap-3 mt-3 text-xs text-[var(--color-text-secondary)]">
                                @if($review->escortProfile)
                                    <span>{{ $review->escortProfile->city }}</span>
                                    <span>&middot;</span>
                                @endif
                                @if($review->visit_date)
                                    <span>{{ __('Visit: :date', ['date' => $review->visit_date->format('d.m.Y')]) }}</span>
                                    <span>&middot;</span>
                                @endif
                                <span>{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $reviews->withQueryString()->links() }}
                </div>
            @else
                <div class="glass-card p-8 text-center">
                    <p class="text-[var(--color-text-secondary)]">{{ __('No reviews found.') }}</p>
                    @if(request()->hasAny(['rating', 'city', 'sort', 'escort']))
                        <a href="{{ route('reviews.index') }}" class="mt-3 inline-block text-sm text-[var(--color-accent)] hover:underline">{{ __('Clear filters') }}</a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
