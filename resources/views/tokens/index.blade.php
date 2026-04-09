<x-app-layout :title="__('Tokens')">

    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold text-[var(--color-text)] mb-6">{{ __('Tokens') }}</h1>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-lg bg-[var(--color-success)]/10 border border-[var(--color-success)]/30 text-sm text-[var(--color-success)] toast-enter">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 rounded-lg bg-[var(--color-danger)]/10 border border-[var(--color-danger)]/30 text-sm text-[var(--color-danger)] toast-enter">
                {{ session('error') }}
            </div>
        @endif

        {{-- Balance --}}
        <div class="glass-card p-6 mb-8 text-center">
            <p class="text-sm text-[var(--color-text-secondary)] mb-1">{{ __('Your Balance') }}</p>
            <p class="text-4xl font-bold text-[var(--color-gold)]">{{ auth()->user()->token_balance }}</p>
            <p class="text-sm text-[var(--color-text-secondary)] mt-1">{{ __('Tokens') }}</p>
        </div>

        {{-- Packages --}}
        <h2 class="text-lg font-bold text-[var(--color-text)] mb-4">{{ __('Buy Tokens') }}</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
            @foreach($packages as $package)
                <div class="glass-card p-5 text-center relative overflow-hidden {{ $package->slug === 'premium' ? 'border-[var(--color-gold)]' : '' }}">
                    @if($package->slug === 'premium')
                        <span class="absolute top-0 right-0 px-2 py-0.5 text-xs font-bold bg-[var(--color-gold)] text-black rounded-bl-lg">{{ __('Popular') }}</span>
                    @endif
                    <h3 class="text-lg font-bold text-[var(--color-text)]">{{ $package->name }}</h3>
                    <p class="text-3xl font-bold mt-2" style="color: {{ $package->badge_color }}">{{ $package->tokens }}</p>
                    <p class="text-xs text-[var(--color-text-secondary)] mt-1">{{ __('Tokens') }}</p>
                    <p class="text-lg font-semibold text-[var(--color-text)] mt-3">{{ $package->formatted_price }}</p>
                    <form method="POST" action="{{ route('tokens.purchase', $package) }}" class="mt-4">
                        @csrf
                        <button type="submit" class="w-full py-2 accent-gradient text-white text-sm font-medium rounded-lg hover:opacity-90 btn-press">
                            {{ __('Buy') }}
                        </button>
                    </form>
                </div>
            @endforeach
        </div>

        {{-- Spend Tokens (for escorts) --}}
        @if(auth()->user()->isEscort() && auth()->user()->escortProfile)
            <h2 class="text-lg font-bold text-[var(--color-text)] mb-4">{{ __('Spend Tokens') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-10">
                @foreach([
                    ['action' => 'blog_renewal', 'name' => __('Blog Renewal'), 'desc' => __('30 days blog visible'), 'amount' => 80, 'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z'],
                    ['action' => 'featured', 'name' => __('Featured'), 'desc' => __('7 days on the homepage'), 'amount' => 50, 'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
                    ['action' => 'top', 'name' => __('Top Placement'), 'desc' => __('24 hours at the top'), 'amount' => 20, 'icon' => 'M5 10l7-7m0 0l7 7m-7-7v18'],
                ] as $option)
                    <form method="POST" action="{{ route('tokens.spend') }}" class="glass-card p-4 flex items-center gap-4">
                        @csrf
                        <input type="hidden" name="action" value="{{ $option['action'] }}">
                        <input type="hidden" name="amount" value="{{ $option['amount'] }}">
                        <div class="w-10 h-10 rounded-full bg-[var(--color-accent)]/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-[var(--color-accent)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $option['icon'] }}"/></svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-medium text-[var(--color-text)]">{{ $option['name'] }}</h3>
                            <p class="text-xs text-[var(--color-text-secondary)]">{{ $option['desc'] }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-[var(--color-gold)]">{{ __(':amount Tokens', ['amount' => $option['amount']]) }}</p>
                            <button type="submit" class="mt-1 px-3 py-1 text-xs accent-gradient text-white rounded-full hover:opacity-90 btn-press"
                                {{ auth()->user()->token_balance < $option['amount'] ? 'disabled' : '' }}>
                                {{ __('Activate') }}
                            </button>
                        </div>
                    </form>
                @endforeach
            </div>
        @endif

        {{-- Transaction History --}}
        <h2 class="text-lg font-bold text-[var(--color-text)] mb-4">{{ __('History') }}</h2>
        <div class="glass-card divide-y divide-[var(--color-border)]">
            @forelse($transactions as $tx)
                <div class="flex items-center justify-between p-4">
                    <div>
                        <p class="text-sm text-[var(--color-text)]">{{ $tx->description }}</p>
                        <p class="text-xs text-[var(--color-text-secondary)]">{{ $tx->created_at->format('d.m.Y H:i') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold {{ $tx->amount > 0 ? 'text-[var(--color-success)]' : 'text-[var(--color-danger)]' }}">
                            {{ $tx->amount > 0 ? '+' : '' }}{{ $tx->amount }}
                        </p>
                        <p class="text-xs text-[var(--color-text-secondary)]">{{ __('Balance: :amount', ['amount' => $tx->balance_after]) }}</p>
                    </div>
                </div>
            @empty
                <div class="p-4 text-center text-sm text-[var(--color-text-secondary)]">
                    {{ __('No transactions yet.') }}
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
