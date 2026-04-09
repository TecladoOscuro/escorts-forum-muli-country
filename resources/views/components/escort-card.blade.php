@props(['escort'])

<a href="{{ route('escorts.show', $escort) }}" class="group block glass-card overflow-hidden hover:border-[var(--color-accent)]/50 transition-all duration-200">
    {{-- Photo --}}
    <div class="relative aspect-[3/4] overflow-hidden bg-[var(--color-surface-hover)]">
        <img src="{{ $escort->primary_photo_url }}" alt="{{ $escort->display_name }}"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
            loading="lazy">

        {{-- Gradient overlay --}}
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>

        {{-- Badges --}}
        <div class="absolute top-3 left-3 flex flex-col gap-1.5">
            @if($escort->isFeatured())
                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-[var(--color-gold)] text-black">{{ __('Featured') }}</span>
            @endif
            @if($escort->is_verified)
                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-[var(--color-verified)] text-white">{{ __('Verified') }}</span>
            @endif
            @if($escort->isTop())
                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-[var(--color-accent)] text-white">Top</span>
            @endif
        </div>

        {{-- Info overlay --}}
        <div class="absolute bottom-0 left-0 right-0 p-4">
            <h3 class="text-lg font-bold text-white">{{ $escort->display_name }}@if($escort->age), {{ $escort->age }}@endif</h3>
            <div class="flex items-center gap-1.5 mt-1">
                @for($i = 1; $i <= 5; $i++)
                    <svg class="w-4 h-4 {{ $i <= round($escort->avg_rating) ? 'star-filled' : 'star-empty' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                @endfor
                <span class="text-xs text-white/80 ml-1">({{ $escort->reviews_count }})</span>
            </div>
            <p class="text-sm text-white/70 mt-1 flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                {{ $escort->city }}@if($escort->neighborhood), {{ $escort->neighborhood }}@endif
            </p>
        </div>
    </div>

    {{-- Services pills --}}
    <div class="p-3">
        <div class="flex flex-wrap gap-1.5">
            @foreach(array_slice($escort->services ?? [], 0, 3) as $service)
                @if($currentTenant->feature('show_service_tags'))
                    <a href="{{ route('escorts.index', ['service' => $service]) }}" onclick="event.stopPropagation()" class="px-2 py-0.5 text-xs rounded-full bg-[var(--color-surface-hover)] text-[var(--color-text-secondary)] hover:text-[var(--color-accent)] hover:bg-[var(--color-accent)]/10 transition-colors relative z-10">{{ $service }}</a>
                @else
                    <span class="px-2 py-0.5 text-xs rounded-full bg-[var(--color-surface-hover)] text-[var(--color-text-secondary)]">{{ $service }}</span>
                @endif
            @endforeach
            @if(count($escort->services ?? []) > 3)
                <span class="px-2 py-0.5 text-xs rounded-full bg-[var(--color-surface-hover)] text-[var(--color-text-secondary)]">+{{ count($escort->services) - 3 }}</span>
            @endif
        </div>
    </div>
</a>
