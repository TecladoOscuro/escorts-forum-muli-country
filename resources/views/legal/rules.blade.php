<x-app-layout>
    <x-slot:title>{{ __('Forum Rules') }}</x-slot:title>

    <div class="max-w-3xl mx-auto">
        <div class="glass-card p-8">
            <h1 class="text-2xl font-bold text-[var(--color-text)] mb-6">{{ __('Forum Rules') }}</h1>

            <div class="prose prose-invert max-w-none space-y-4 text-[var(--color-text-secondary)]">
                <div class="p-4 rounded-lg bg-[var(--color-accent)]/10 border border-[var(--color-accent)]/30 text-sm">
                    <strong class="text-[var(--color-accent)]">{{ __('Important:') }}</strong> {{ __('By using this platform, you accept the following rules. Violations may lead to the suspension of your account.') }}
                </div>

                <h2 class="text-lg font-semibold text-[var(--color-text)]">{{ __('1. General Rules') }}</h2>
                <ul class="list-disc list-inside space-y-1">
                    <li>{{ __('All users must be at least 18 years old') }}</li>
                    <li>{{ __('Respectful interaction is mandatory') }}</li>
                    <li>{{ __('No insults, threats, or discrimination') }}</li>
                    <li>{{ __('No spam posts or advertising for external services') }}</li>
                </ul>

                <h2 class="text-lg font-semibold text-[var(--color-text)]">{{ __('2. Content Guidelines') }}</h2>
                <ul class="list-disc list-inside space-y-1">
                    <li>{{ __('No personal data of third parties (real names, addresses, phone numbers)') }}</li>
                    <li>{{ __('No illegal content of any kind') }}</li>
                    <li>{{ __('No content involving minors') }}</li>
                    <li>{{ __('No offers or advertising for unprotected intercourse') }}</li>
                    <li>{{ __('No content that violates the ProstSchG') }}</li>
                </ul>

                <h2 class="text-lg font-semibold text-[var(--color-text)]">{{ __('3. Reviews') }}</h2>
                <ul class="list-disc list-inside space-y-1">
                    <li>{{ __('Reviews must be based on actual experiences') }}</li>
                    <li>{{ __('No fake reviews (neither positive nor negative)') }}</li>
                    <li>{{ __('Objective and respectful wording') }}</li>
                    <li>{{ __('No description of illegal actions') }}</li>
                </ul>

                <h2 class="text-lg font-semibold text-[var(--color-text)]">{{ __('4. Escort Profiles') }}</h2>
                <ul class="list-disc list-inside space-y-1">
                    <li>{{ __('Only use own, authentic photos') }}</li>
                    <li>{{ __('Correct and current information about services and prices') }}</li>
                    <li>{{ __('Escorts are responsible for compliance with the ProstSchG (registration, health certificate)') }}</li>
                </ul>

                <h2 class="text-lg font-semibold text-[var(--color-text)]">{{ __('5. Reports & Moderation') }}</h2>
                <p>
                    {{ __('Use the report function to report violations. Our moderation team reviews reported content within 24 hours (according to NetzDG).') }}
                </p>

                <h2 class="text-lg font-semibold text-[var(--color-text)]">{{ __('6. Consequences for Violations') }}</h2>
                <ul class="list-disc list-inside space-y-1">
                    <li>{{ __('First warning: Admonition') }}</li>
                    <li>{{ __('Second warning: Temporary suspension (7 days)') }}</li>
                    <li>{{ __('Third warning: Permanent suspension') }}</li>
                    <li>{{ __('Severe violations: Immediate permanent suspension') }}</li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
