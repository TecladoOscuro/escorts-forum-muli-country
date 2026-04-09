<x-app-layout :title="__('Messages')">

    <h1 class="text-2xl font-bold text-[var(--color-text)] mb-6">{{ __('Messages') }}</h1>

    <div class="glass-card divide-y divide-[var(--color-border)]">
        @forelse($conversations as $conversation)
            @php
                $otherUser = $conversation->participants->where('id', '!=', auth()->id())->first();
                $hasUnread = $conversation->hasUnreadFor(auth()->user());
            @endphp
            <a href="{{ route('messages.show', $conversation) }}" class="flex items-center gap-3 p-4 hover:bg-[var(--color-surface-hover)] transition-colors {{ $hasUnread ? 'bg-[var(--color-accent)]/5' : '' }}">
                <div class="relative flex-shrink-0">
                    <img src="{{ $otherUser?->avatar_url ?? '' }}" alt="" class="w-12 h-12 rounded-full">
                    @if($hasUnread)
                        <span class="absolute -top-0.5 -right-0.5 w-3 h-3 bg-[var(--color-accent)] rounded-full border-2 border-[var(--color-surface)]"></span>
                    @endif
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-[var(--color-text)] {{ $hasUnread ? 'font-bold' : '' }}">{{ $otherUser?->username ?? __('Deleted User') }}</span>
                        <span class="text-xs text-[var(--color-text-secondary)]">{{ $conversation->last_message_at?->diffForHumans() }}</span>
                    </div>
                    @if($conversation->lastMessage)
                        <p class="text-sm text-[var(--color-text-secondary)] truncate mt-0.5">{{ Str::limit($conversation->lastMessage->body, 80) }}</p>
                    @endif
                </div>
            </a>
        @empty
            <div class="p-8 text-center">
                <p class="text-[var(--color-text-secondary)]">{{ __('No messages yet.') }}</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $conversations->links() }}
    </div>
</x-app-layout>
