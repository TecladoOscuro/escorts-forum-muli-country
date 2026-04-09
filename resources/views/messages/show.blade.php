<x-app-layout :title="__('Message')">

    <div class="max-w-3xl mx-auto">
        {{-- Header --}}
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('messages.index') }}" class="text-[var(--color-text-secondary)] hover:text-[var(--color-accent)]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <img src="{{ $otherUser?->avatar_url ?? '' }}" alt="" class="w-10 h-10 rounded-full">
            <div>
                <h1 class="text-lg font-bold text-[var(--color-text)]">{{ $otherUser?->username ?? __('Deleted User') }}</h1>
                <p class="text-xs text-[var(--color-text-secondary)]">{{ $conversation->subject }}</p>
            </div>
        </div>

        {{-- Messages --}}
        <div class="space-y-3 mb-6">
            @foreach($messages as $message)
                <div class="flex {{ $message->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[80%] {{ $message->user_id === auth()->id() ? 'bg-[var(--color-accent)]/20 border-[var(--color-accent)]/30' : 'bg-[var(--color-surface)] border-[var(--color-border)]' }} border rounded-2xl px-4 py-3">
                        <p class="text-sm text-[var(--color-text)]">{{ $message->body }}</p>
                        <p class="text-xs text-[var(--color-text-secondary)] mt-1">{{ $message->created_at->format('d.m. H:i') }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        {{ $messages->links() }}

        {{-- Reply Form --}}
        <form method="POST" action="{{ route('messages.reply', $conversation) }}" class="glass-card p-4 mt-4">
            @csrf
            <div class="flex gap-3">
                <textarea name="body" rows="2" required placeholder="{{ __('Write message...') }}"
                    class="flex-1 bg-[var(--color-surface)] border border-[var(--color-border)] rounded-lg px-4 py-2.5 text-sm text-[var(--color-text)] placeholder-[var(--color-text-secondary)] focus:outline-none focus:border-[var(--color-accent)] transition-colors resize-none"></textarea>
                <button type="submit" class="px-4 py-2 accent-gradient text-white rounded-lg hover:opacity-90 btn-press self-end">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
