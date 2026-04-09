<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Age Verification') }} - ForumEscort</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen flex items-center justify-center bg-[var(--color-bg)]">
    <div class="max-w-md w-full mx-4">
        <div class="glass-card p-8 text-center">
            <div class="mb-6">
                <span class="text-3xl font-bold text-[var(--color-accent)]">Forum</span>
                <span class="text-3xl font-light text-[var(--color-text)]">Escort</span>
            </div>

            <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-[var(--color-surface-hover)] flex items-center justify-center">
                <svg class="w-10 h-10 text-[var(--color-accent)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>

            <h1 class="text-xl font-bold text-[var(--color-text)] mb-2">{{ __('Age Verification') }}</h1>
            <p class="text-sm text-[var(--color-text-secondary)] mb-6">
                {{ __('This website contains content intended for adults only. You must be at least :age years old to continue.', ['age' => 18]) }}
            </p>

            @if($errors->any())
                <div class="mb-4 p-3 rounded-lg bg-[var(--color-danger)]/10 border border-[var(--color-danger)]/30 text-sm text-[var(--color-danger)]">
                    {{ __('Please confirm that you are at least 18 years old.') }}
                </div>
            @endif

            <form method="POST" action="{{ route('age-verify') }}">
                @csrf
                <label class="flex items-start gap-3 mb-6 cursor-pointer text-left">
                    <input type="checkbox" name="confirm" value="1" class="mt-1 rounded border-[var(--color-border)] bg-[var(--color-surface)] text-[var(--color-accent)] focus:ring-[var(--color-accent)]">
                    <span class="text-sm text-[var(--color-text-secondary)]">
                        {{ __('I confirm that I am at least 18 years old and agree to the') }}
                        <a href="{{ route('rules') }}" class="text-[var(--color-accent)] hover:underline">{{ __('forum rules') }}</a>
                        {{ __('and the') }}
                        <a href="{{ route('privacy') }}" class="text-[var(--color-accent)] hover:underline">{{ __('privacy policy') }}</a>{{ __('agree.') }}
                    </span>
                </label>
                <button type="submit" class="w-full py-3 px-6 accent-gradient text-white font-semibold rounded-lg hover:opacity-90 transition-opacity btn-press">
                    {{ __('Enter') }}
                </button>
            </form>

            <p class="mt-6 text-xs text-[var(--color-text-secondary)]">
                {{ __('According to § 5 JuSchG (Youth Protection Act), access to this website is only permitted for persons aged 18 and over.') }}
            </p>

            <div class="mt-4 flex justify-center gap-4 text-xs">
                <a href="{{ route('impressum') }}" class="text-[var(--color-text-secondary)] hover:text-[var(--color-accent)]">{{ __('Imprint') }}</a>
                <a href="{{ route('privacy') }}" class="text-[var(--color-text-secondary)] hover:text-[var(--color-accent)]">{{ __('Privacy Policy') }}</a>
            </div>
        </div>
    </div>
</body>
</html>
