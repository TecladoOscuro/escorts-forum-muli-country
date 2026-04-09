<x-app-layout>
    <x-slot:title>{{ __('Log In') }}</x-slot:title>

    <div class="max-w-md mx-auto">
        <div class="glass-card p-8">
            <h1 class="text-2xl font-bold text-[var(--color-text)] mb-6 text-center">{{ __('Log In') }}</h1>

            @if($errors->any())
                <div class="mb-4 p-3 rounded-lg bg-[var(--color-danger)]/10 border border-[var(--color-danger)]/30 text-sm text-[var(--color-danger)]">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">{{ __('E-Mail') }}</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-lg px-4 py-2.5 text-[var(--color-text)] placeholder-[var(--color-text-secondary)] focus:outline-none focus:border-[var(--color-accent)] transition-colors">
                </div>

                <div>
                    <label class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">{{ __('Password') }}</label>
                    <input type="password" name="password" required
                        class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-lg px-4 py-2.5 text-[var(--color-text)] focus:outline-none focus:border-[var(--color-accent)] transition-colors">
                </div>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-[var(--color-border)] bg-[var(--color-surface)] text-[var(--color-accent)] focus:ring-[var(--color-accent)]">
                    <span class="text-sm text-[var(--color-text-secondary)]">{{ __('Stay logged in') }}</span>
                </label>

                <button type="submit" class="w-full py-3 accent-gradient text-white font-semibold rounded-lg hover:opacity-90 transition-opacity btn-press">
                    {{ __('Log In') }}
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-[var(--color-text-secondary)]">
                {{ __('No account yet?') }}
                <a href="{{ route('register') }}" class="text-[var(--color-accent)] hover:underline">{{ __('Register') }}</a>
            </p>
        </div>
    </div>
</x-app-layout>
