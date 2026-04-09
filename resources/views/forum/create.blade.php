<x-app-layout :title="__('New Topic')">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-[var(--color-text-secondary)] mb-6">
        <a href="{{ route('forum.index') }}" class="hover:text-[var(--color-accent)]">{{ __('Forum') }}</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('forum.category', $category) }}" class="hover:text-[var(--color-accent)]">{{ $category->name }}</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-[var(--color-text)]">{{ __('New Topic') }}</span>
    </nav>

    <div class="max-w-2xl mx-auto">
        <div class="glass-card p-6">
            <h1 class="text-xl font-bold text-[var(--color-text)] mb-6">{{ __('New Topic in ":category"', ['category' => $category->name]) }}</h1>

            @if($errors->any())
                <div class="mb-4 p-3 rounded-lg bg-[var(--color-danger)]/10 border border-[var(--color-danger)]/30 text-sm text-[var(--color-danger)]">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('forum.store', $category) }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">{{ __('Title') }}</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                        class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-lg px-4 py-2.5 text-[var(--color-text)] focus:outline-none focus:border-[var(--color-accent)] transition-colors"
                        placeholder="{{ __('What is it about?') }}">
                </div>

                <div>
                    <label class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">{{ __('Content') }}</label>
                    <textarea name="body" rows="8" required
                        class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-lg px-4 py-3 text-sm text-[var(--color-text)] focus:outline-none focus:border-[var(--color-accent)] transition-colors"
                        placeholder="{{ __('Your post...') }}">{{ old('body') }}</textarea>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="px-6 py-2.5 accent-gradient text-white font-medium rounded-lg hover:opacity-90 btn-press">
                        {{ __('Create Topic') }}
                    </button>
                    <a href="{{ route('forum.category', $category) }}" class="px-6 py-2.5 text-sm text-[var(--color-text-secondary)] hover:text-[var(--color-text)] transition-colors">
                        {{ __('Cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
