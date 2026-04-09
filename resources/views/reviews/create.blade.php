<x-app-layout :title="__('Write a Review')">

    <div class="max-w-2xl mx-auto">
        <a href="{{ route('escorts.show', $escortProfile) }}" class="inline-flex items-center gap-1 text-sm text-[var(--color-text-secondary)] hover:text-[var(--color-accent)] mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            {{ __('Back to :name', ['name' => $escortProfile->display_name]) }}
        </a>

        <div class="glass-card p-6">
            <h1 class="text-xl font-bold text-[var(--color-text)] mb-1">{{ __('Write a Review') }}</h1>
            <p class="text-sm text-[var(--color-text-secondary)] mb-6">{{ __('for') }} <span class="text-[var(--color-accent)]">{{ $escortProfile->display_name }}</span></p>

            @if($errors->any())
                <div class="mb-4 p-3 rounded-lg bg-[var(--color-danger)]/10 border border-[var(--color-danger)]/30 text-sm text-[var(--color-danger)]">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('reviews.store', $escortProfile) }}" class="space-y-4">
                @csrf

                {{-- Star Rating --}}
                <div x-data="{ rating: {{ old('rating', 0) }} }">
                    <label class="block text-sm font-medium text-[var(--color-text-secondary)] mb-2">{{ __('Rating') }}</label>
                    <div class="flex gap-1">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" @click="rating = {{ $i }}" class="focus:outline-none">
                                <svg class="w-8 h-8 transition-colors" :class="rating >= {{ $i }} ? 'star-filled' : 'star-empty'" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" :value="rating">
                </div>

                <div>
                    <label class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">{{ __('Title') }}</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                        class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-lg px-4 py-2.5 text-[var(--color-text)] focus:outline-none focus:border-[var(--color-accent)] transition-colors"
                        placeholder="{{ __('Brief summary of your experience') }}">
                </div>

                <div>
                    <label class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">{{ __('Experience Report') }}</label>
                    <textarea name="body" rows="6" required
                        class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-lg px-4 py-3 text-sm text-[var(--color-text)] focus:outline-none focus:border-[var(--color-accent)] transition-colors"
                        placeholder="{{ __('Describe your experience... (min. :count characters)', ['count' => 20]) }}">{{ old('body') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">{{ __('Visit Date') }}</label>
                    <input type="date" name="visit_date" value="{{ old('visit_date') }}" required max="{{ date('Y-m-d') }}"
                        class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-lg px-4 py-2.5 text-[var(--color-text)] focus:outline-none focus:border-[var(--color-accent)] transition-colors">
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="px-6 py-2.5 accent-gradient text-white font-medium rounded-lg hover:opacity-90 btn-press">
                        {{ __('Publish Review') }}
                    </button>
                    <a href="{{ route('escorts.show', $escortProfile) }}" class="px-6 py-2.5 text-sm text-[var(--color-text-secondary)] hover:text-[var(--color-text)]">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
