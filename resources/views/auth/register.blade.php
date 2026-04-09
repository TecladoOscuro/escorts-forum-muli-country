<x-app-layout>
    <x-slot:title>{{ __('Register') }}</x-slot:title>

    <div class="max-w-md mx-auto">
        <div class="glass-card p-8">
            <h1 class="text-2xl font-bold text-[var(--color-text)] mb-6 text-center">{{ __('Register') }}</h1>

            @if($errors->any())
                <div class="mb-4 p-3 rounded-lg bg-[var(--color-danger)]/10 border border-[var(--color-danger)]/30 text-sm text-[var(--color-danger)]">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">{{ __('Username') }}</label>
                    <input type="text" name="username" value="{{ old('username') }}" required autofocus
                        class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-lg px-4 py-2.5 text-[var(--color-text)] placeholder-[var(--color-text-secondary)] focus:outline-none focus:border-[var(--color-accent)] transition-colors"
                        placeholder="{{ __('Min. :count characters', ['count' => 3]) }}">
                </div>

                <div>
                    <label class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">{{ __('E-Mail') }}</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-lg px-4 py-2.5 text-[var(--color-text)] placeholder-[var(--color-text-secondary)] focus:outline-none focus:border-[var(--color-accent)] transition-colors">
                </div>

                <div>
                    <label class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">{{ __('I am...') }}</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center gap-2 p-3 rounded-lg border border-[var(--color-border)] cursor-pointer hover:border-[var(--color-accent)] transition-colors has-[:checked]:border-[var(--color-accent)] has-[:checked]:bg-[var(--color-accent)]/10">
                            <input type="radio" name="role" value="user" {{ old('role', 'user') === 'user' ? 'checked' : '' }} class="text-[var(--color-accent)] focus:ring-[var(--color-accent)]">
                            <div>
                                <span class="text-sm font-medium text-[var(--color-text)]">{{ __('User') }}</span>
                                <p class="text-xs text-[var(--color-text-secondary)]">{{ __('Reviews & Forum') }}</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-2 p-3 rounded-lg border border-[var(--color-border)] cursor-pointer hover:border-[var(--color-accent)] transition-colors has-[:checked]:border-[var(--color-accent)] has-[:checked]:bg-[var(--color-accent)]/10">
                            <input type="radio" name="role" value="escort" {{ old('role') === 'escort' ? 'checked' : '' }} class="text-[var(--color-accent)] focus:ring-[var(--color-accent)]">
                            <div>
                                <span class="text-sm font-medium text-[var(--color-text)]">{{ __('Escort') }}</span>
                                <p class="text-xs text-[var(--color-text-secondary)]">{{ __('Profile & Blog') }}</p>
                            </div>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">{{ __('Password') }}</label>
                    <input type="password" name="password" required
                        class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-lg px-4 py-2.5 text-[var(--color-text)] focus:outline-none focus:border-[var(--color-accent)] transition-colors"
                        placeholder="{{ __('Min. :count characters', ['count' => 8]) }}">
                </div>

                <div>
                    <label class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">{{ __('Confirm Password') }}</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-lg px-4 py-2.5 text-[var(--color-text)] focus:outline-none focus:border-[var(--color-accent)] transition-colors">
                </div>

                <button type="submit" class="w-full py-3 accent-gradient text-white font-semibold rounded-lg hover:opacity-90 transition-opacity btn-press">
                    {{ __('Register') }}
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-[var(--color-text-secondary)]">
                {{ __('Already registered?') }}
                <a href="{{ route('login') }}" class="text-[var(--color-accent)] hover:underline">{{ __('Log In') }}</a>
            </p>
        </div>
    </div>
</x-app-layout>
