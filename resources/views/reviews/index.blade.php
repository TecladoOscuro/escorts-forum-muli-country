<x-app-layout :title="__('Reviews')" :metaDescription="__('Escort Reviews and Experience Reports in Germany')">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-[var(--color-text)]">{{ __('Reviews') }}</h1>
    </div>

    <div class="space-y-4">
        @forelse($reviews as $review)
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
                    @if($review->visit_date)
                        <span>{{ __('Visit: :date', ['date' => $review->visit_date->format('d.m.Y')]) }}</span>
                    @endif
                    <span>{{ $review->created_at->diffForHumans() }}</span>
                </div>
            </div>
        @empty
            <div class="glass-card p-8 text-center">
                <p class="text-[var(--color-text-secondary)]">{{ __('No reviews yet.') }}</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $reviews->links() }}
    </div>
</x-app-layout>
