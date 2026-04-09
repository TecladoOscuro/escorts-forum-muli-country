<x-app-layout :title="__('Escorts')" :metaDescription="__('Escort Directory Germany - Profiles, Reviews and Contact')">

    <div class="flex flex-col md:flex-row gap-6">
        {{-- Filters Sidebar --}}
        <aside class="w-full md:w-64 flex-shrink-0" x-data="{ filtersOpen: false }">
            <button @click="filtersOpen = !filtersOpen" class="md:hidden w-full flex items-center justify-between p-3 glass-card mb-3">
                <span class="text-sm font-medium text-[var(--color-text)]">{{ __('Filter') }}</span>
                <svg class="w-5 h-5 text-[var(--color-text-secondary)]" :class="filtersOpen && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>

            <div :class="filtersOpen ? 'block' : 'hidden md:block'">
                <form action="{{ route('escorts.index') }}" method="GET" class="glass-card p-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">{{ __('City') }}</label>
                        <select name="city" class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-lg px-3 py-2 text-sm text-[var(--color-text)] focus:outline-none focus:border-[var(--color-accent)]">
                            <option value="">{{ __('All Cities') }}</option>
                            @foreach($cities as $city)
                                <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">{{ __('Nationality') }}</label>
                        <select name="nationality" class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-lg px-3 py-2 text-sm text-[var(--color-text)] focus:outline-none focus:border-[var(--color-accent)]">
                            <option value="">{{ __('All Nationalities') }}</option>
                            @foreach($nationalities as $nat)
                                <option value="{{ $nat }}" {{ request('nationality') == $nat ? 'selected' : '' }}>{{ $nat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">{{ __('Language') }}</label>
                        <select name="language" class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-lg px-3 py-2 text-sm text-[var(--color-text)] focus:outline-none focus:border-[var(--color-accent)]">
                            <option value="">{{ __('All Languages') }}</option>
                            @foreach($languages as $lang)
                                <option value="{{ $lang }}" {{ request('language') == $lang ? 'selected' : '' }}>{{ strtoupper($lang) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">{{ __('Service') }}</label>
                        <select name="service" class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-lg px-3 py-2 text-sm text-[var(--color-text)] focus:outline-none focus:border-[var(--color-accent)]">
                            <option value="">{{ __('All Services') }}</option>
                            @foreach($services as $service)
                                <option value="{{ $service }}" {{ request('service') == $service ? 'selected' : '' }}>{{ $service }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">{{ __('Age') }}</label>
                        <div class="flex items-center gap-2">
                            <input type="number" name="age_min" value="{{ request('age_min') }}" placeholder="{{ __('from') }}" min="18" max="99"
                                class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-lg px-3 py-2 text-sm text-[var(--color-text)] focus:outline-none focus:border-[var(--color-accent)]">
                            <span class="text-[var(--color-text-secondary)] text-sm">–</span>
                            <input type="number" name="age_max" value="{{ request('age_max') }}" placeholder="{{ __('to') }}" min="18" max="99"
                                class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-lg px-3 py-2 text-sm text-[var(--color-text)] focus:outline-none focus:border-[var(--color-accent)]">
                        </div>
                    </div>

                    @if($currentTenant->feature('show_price_filter'))
                        <div>
                            <label class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">{{ __('Price') }} (€)</label>
                            <div class="flex items-center gap-2">
                                <input type="number" name="price_min" value="{{ request('price_min') }}" placeholder="{{ __('from') }}" min="0"
                                    class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-lg px-3 py-2 text-sm text-[var(--color-text)] focus:outline-none focus:border-[var(--color-accent)]">
                                <span class="text-[var(--color-text-secondary)] text-sm">–</span>
                                <input type="number" name="price_max" value="{{ request('price_max') }}" placeholder="{{ __('to') }}" min="0"
                                    class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-lg px-3 py-2 text-sm text-[var(--color-text)] focus:outline-none focus:border-[var(--color-accent)]">
                            </div>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">{{ __('Sort By') }}</label>
                        <select name="sort" class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-lg px-3 py-2 text-sm text-[var(--color-text)] focus:outline-none focus:border-[var(--color-accent)]">
                            <option value="top" {{ request('sort', 'top') == 'top' ? 'selected' : '' }}>{{ __('Top & Featured') }}</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>{{ __('Best Rating') }}</option>
                            <option value="reviews" {{ request('sort') == 'reviews' ? 'selected' : '' }}>{{ __('Most Reviews') }}</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('Newest') }}</option>
                            <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>{{ __('Most Visits') }}</option>
                        </select>
                    </div>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="verified" value="1" {{ request('verified') ? 'checked' : '' }}
                            class="rounded border-[var(--color-border)] bg-[var(--color-surface)] text-[var(--color-accent)] focus:ring-[var(--color-accent)]">
                        <span class="text-sm text-[var(--color-text-secondary)]">{{ __('Verified only') }}</span>
                    </label>

                    <button type="submit" class="w-full py-2 accent-gradient text-white text-sm font-medium rounded-lg hover:opacity-90 transition-opacity btn-press">
                        {{ __('Apply Filter') }}
                    </button>

                    @if(request()->hasAny(['city', 'sort', 'service', 'verified', 'nationality', 'language', 'age_min', 'age_max', 'price_min', 'price_max']))
                        <a href="{{ route('escorts.index') }}" class="block text-center text-xs text-[var(--color-text-secondary)] hover:text-[var(--color-accent)]">{{ __('Reset filters') }}</a>
                    @endif
                </form>
            </div>
        </aside>

        {{-- Escorts Grid --}}
        <div class="flex-1">
            <div class="flex items-center justify-between mb-5">
                <h1 class="text-2xl font-bold text-[var(--color-text)]">{{ __('Escorts') }}</h1>
                <p class="text-sm text-[var(--color-text-secondary)]">{{ __(':count results', ['count' => $escorts->total()]) }}</p>
            </div>

            @if($escorts->isNotEmpty())
                <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($escorts as $escort)
                        <x-escort-card :escort="$escort" />
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $escorts->withQueryString()->links() }}
                </div>
            @else
                <div class="glass-card p-8 text-center">
                    <p class="text-[var(--color-text-secondary)]">{{ __('No escorts found.') }}</p>
                    <a href="{{ route('escorts.index') }}" class="mt-3 inline-block text-sm text-[var(--color-accent)] hover:underline">{{ __('Reset filters') }}</a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
