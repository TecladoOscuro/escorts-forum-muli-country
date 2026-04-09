<x-app-layout :title="$escortProfile->display_name" :metaDescription="Str::limit($escortProfile->description, 160)">

    <div class="max-w-4xl mx-auto">
        {{-- Back --}}
        <a href="{{ route('escorts.index') }}" class="inline-flex items-center gap-1 text-sm text-[var(--color-text-secondary)] hover:text-[var(--color-accent)] mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            {{ __('Back') }}
        </a>

        <div class="grid md:grid-cols-3 gap-6">
            {{-- Left: Photo + Quick Info --}}
            <div class="md:col-span-1">
                <div class="glass-card overflow-hidden">
                    <div class="aspect-[3/4] bg-[var(--color-surface-hover)]">
                        <img src="{{ $escortProfile->primary_photo_url }}" alt="{{ $escortProfile->display_name }}"
                            class="w-full h-full object-cover">
                    </div>

                    {{-- Photo gallery thumbnails --}}
                    @if($escortProfile->photos->count() > 1)
                        <div class="p-3 flex gap-2 overflow-x-auto">
                            @foreach($escortProfile->photos as $photo)
                                <img src="{{ asset('storage/' . $photo->path) }}" alt=""
                                    class="w-16 h-16 rounded-lg object-cover flex-shrink-0 border-2 {{ $photo->is_primary ? 'border-[var(--color-accent)]' : 'border-[var(--color-border)]' }}">
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Contact Card --}}
                <div class="glass-card p-4 mt-4 space-y-3">
                    <h3 class="text-sm font-semibold text-[var(--color-text)]">{{ __('Contact') }}</h3>
                    @if($escortProfile->contact_telegram)
                        <div class="flex items-center gap-2 text-sm text-[var(--color-text-secondary)]">
                            <svg class="w-4 h-4 text-[var(--color-accent)]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.74-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z"/></svg>
                            {{ $escortProfile->contact_telegram }}
                        </div>
                    @endif
                    @if($escortProfile->contact_phone)
                        <div class="flex items-center gap-2 text-sm text-[var(--color-text-secondary)]">
                            <svg class="w-4 h-4 text-[var(--color-accent)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            {{ $escortProfile->contact_phone }}
                        </div>
                    @endif
                    @if($escortProfile->contact_email)
                        <div class="flex items-center gap-2 text-sm text-[var(--color-text-secondary)]">
                            <svg class="w-4 h-4 text-[var(--color-accent)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            {{ $escortProfile->contact_email }}
                        </div>
                    @endif
                    @auth
                        <a href="{{ route('messages.store', $escortProfile->user) }}" onclick="event.preventDefault(); document.getElementById('quick-msg').classList.toggle('hidden')"
                            class="block w-full text-center py-2 accent-gradient text-white text-sm font-medium rounded-lg hover:opacity-90 btn-press">
                            {{ __('Send Message') }}
                        </a>
                        <form id="quick-msg" method="POST" action="{{ route('messages.store', $escortProfile->user) }}" class="hidden space-y-2">
                            @csrf
                            <textarea name="body" rows="3" required placeholder="{{ __('Your message...') }}"
                                class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-lg px-3 py-2 text-sm text-[var(--color-text)] focus:outline-none focus:border-[var(--color-accent)]"></textarea>
                            <button type="submit" class="w-full py-2 accent-gradient text-white text-sm font-medium rounded-lg hover:opacity-90 btn-press">{{ __('Send') }}</button>
                        </form>
                    @endauth
                </div>
            </div>

            {{-- Right: Details --}}
            <div class="md:col-span-2 space-y-6">
                {{-- Header --}}
                <div class="glass-card p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <h1 class="text-2xl font-bold text-[var(--color-text)]">{{ $escortProfile->display_name }}</h1>
                                @if($escortProfile->is_verified)
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-[var(--color-verified)] text-white">{{ __('Verified') }}</span>
                                @endif
                            </div>
                            <p class="text-[var(--color-text-secondary)] mt-1">
                                {{ $escortProfile->age ? __(':age years', ['age' => $escortProfile->age]) : '' }}
                                {{ $escortProfile->nationality ? '· ' . $escortProfile->nationality : '' }}
                                · {{ $escortProfile->city }}@if($escortProfile->neighborhood), {{ $escortProfile->neighborhood }}@endif
                            </p>
                        </div>
                        <div class="text-right">
                            <div class="flex items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= round($escortProfile->avg_rating) ? 'star-filled' : 'star-empty' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <p class="text-xs text-[var(--color-text-secondary)] mt-1">{{ __(':count reviews · :views visits', ['count' => $escortProfile->reviews_count, 'views' => $escortProfile->views_count]) }}</p>
                        </div>
                    </div>
                </div>

                {{-- Tabs --}}
                <div x-data="{ tab: 'about' }">
                    <div class="flex gap-1 border-b border-[var(--color-border)] mb-4">
                        <button @click="tab = 'about'" :class="tab === 'about' ? 'text-[var(--color-accent)] border-[var(--color-accent)]' : 'text-[var(--color-text-secondary)] border-transparent'" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors">{{ __('About Me') }}</button>
                        <button @click="tab = 'reviews'" :class="tab === 'reviews' ? 'text-[var(--color-accent)] border-[var(--color-accent)]' : 'text-[var(--color-text-secondary)] border-transparent'" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors">{{ __('Reviews') }} ({{ $escortProfile->reviews_count }})</button>
                        @if($escortProfile->blogThread)
                            <button @click="tab = 'blog'" :class="tab === 'blog' ? 'text-[var(--color-accent)] border-[var(--color-accent)]' : 'text-[var(--color-text-secondary)] border-transparent'" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors">Blog</button>
                        @endif
                    </div>

                    {{-- About Tab --}}
                    <div x-show="tab === 'about'" class="space-y-4">
                        <div class="glass-card p-5">
                            <h3 class="text-sm font-semibold text-[var(--color-text)] mb-2">{{ __('Description') }}</h3>
                            <p class="text-sm text-[var(--color-text-secondary)] whitespace-pre-line">{{ $escortProfile->description }}</p>
                        </div>

                        @if($escortProfile->services)
                            <div class="glass-card p-5">
                                <h3 class="text-sm font-semibold text-[var(--color-text)] mb-3">{{ __('Services') }}</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($escortProfile->services as $service)
                                        <span class="px-3 py-1 text-sm rounded-full bg-[var(--color-surface-hover)] text-[var(--color-text-secondary)] border border-[var(--color-border)]">{{ $service }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($escortProfile->rates)
                            <div class="glass-card p-5">
                                <h3 class="text-sm font-semibold text-[var(--color-text)] mb-3">{{ __('Prices') }}</h3>
                                <div class="grid grid-cols-2 gap-3">
                                    @foreach($escortProfile->rates as $duration => $price)
                                        <div class="flex justify-between items-center p-3 rounded-lg bg-[var(--color-surface-hover)]">
                                            <span class="text-sm text-[var(--color-text-secondary)]">{{ $duration }}</span>
                                            <span class="text-sm font-semibold text-[var(--color-accent)]">{{ $price }}&euro;</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($escortProfile->languages)
                            <div class="glass-card p-5">
                                <h3 class="text-sm font-semibold text-[var(--color-text)] mb-2">{{ __('Languages') }}</h3>
                                <div class="flex gap-2">
                                    @foreach($escortProfile->languages as $lang)
                                        <span class="px-2 py-1 text-xs rounded bg-[var(--color-surface-hover)] text-[var(--color-text-secondary)]">{{ strtoupper($lang) }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Reviews Tab --}}
                    <div x-show="tab === 'reviews'" class="space-y-4">
                        @auth
                            <a href="{{ route('reviews.create', $escortProfile) }}" class="inline-flex items-center gap-2 px-4 py-2 accent-gradient text-white text-sm font-medium rounded-lg hover:opacity-90 btn-press">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                {{ __('Write Review') }}
                            </a>
                        @endauth

                        @forelse($reviews as $review)
                            <div class="glass-card p-5">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <img src="{{ $review->user->avatar_url }}" alt="" class="w-8 h-8 rounded-full">
                                        <span class="text-sm font-medium text-[var(--color-text)]">{{ $review->user->username }}</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $review->rating ? 'star-filled' : 'star-empty' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                                <h4 class="text-sm font-medium text-[var(--color-text)]">{{ $review->title }}</h4>
                                <p class="text-sm text-[var(--color-text-secondary)] mt-1">{{ $review->body }}</p>
                                <p class="text-xs text-[var(--color-text-secondary)] mt-2">{{ $review->visit_date?->format('d.m.Y') }} · {{ $review->created_at->diffForHumans() }}</p>
                            </div>
                        @empty
                            <div class="glass-card p-5 text-center text-sm text-[var(--color-text-secondary)]">
                                {{ __('No reviews available yet.') }}
                            </div>
                        @endforelse

                        @if($reviews->hasPages())
                            <div class="mt-4">
                                {{ $reviews->withQueryString()->links() }}
                            </div>
                        @endif
                    </div>

                    {{-- Blog Tab --}}
                    @if($escortProfile->blogThread)
                        <div x-show="tab === 'blog'" class="space-y-4">
                            <div class="glass-card p-5">
                                <p class="text-sm text-[var(--color-text-secondary)] whitespace-pre-line">{{ $escortProfile->blogThread->body }}</p>
                                <p class="text-xs text-[var(--color-text-secondary)] mt-3">{{ $escortProfile->blogThread->created_at->format('d.m.Y') }}</p>
                            </div>

                            @if($blogPosts)
                                @foreach($blogPosts as $post)
                                    <div class="glass-card p-5">
                                        <div class="flex items-center gap-2 mb-2">
                                            <img src="{{ $post->user->avatar_url }}" alt="" class="w-7 h-7 rounded-full">
                                            <span class="text-sm font-medium text-[var(--color-text)]">{{ $post->user->username }}</span>
                                            <span class="text-xs text-[var(--color-text-secondary)]">{{ $post->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-sm text-[var(--color-text-secondary)]">{{ $post->body }}</p>
                                    </div>
                                @endforeach

                                @if($blogPosts->hasPages())
                                    <div class="mt-4">
                                        {{ $blogPosts->withQueryString()->links() }}
                                    </div>
                                @endif
                            @endif

                            @auth
                                <form method="POST" action="{{ route('forum.reply', [$escortProfile->blogThread->category, $escortProfile->blogThread]) }}" class="glass-card p-5">
                                    @csrf
                                    <textarea name="body" rows="3" required placeholder="{{ __('Write comment...') }}"
                                        class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-lg px-3 py-2 text-sm text-[var(--color-text)] focus:outline-none focus:border-[var(--color-accent)]"></textarea>
                                    <button type="submit" class="mt-2 px-4 py-2 accent-gradient text-white text-sm font-medium rounded-lg hover:opacity-90 btn-press">{{ __('Comment') }}</button>
                                </form>
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
