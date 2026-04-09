<x-app-layout>
    <x-slot:title>{{ __('Privacy Policy') }}</x-slot:title>

    <div class="max-w-3xl mx-auto">
        <div class="glass-card p-8">
            <h1 class="text-2xl font-bold text-[var(--color-text)] mb-6">{{ __('Privacy Policy') }}</h1>

            <div class="prose prose-invert max-w-none space-y-4 text-[var(--color-text-secondary)]">
                <h2 class="text-lg font-semibold text-[var(--color-text)]">{{ __('1. Data Protection at a Glance') }}</h2>
                <p>
                    {{ __('The following notes provide a simple overview of what happens to your personal data when you visit this website. Personal data is any data that can be used to personally identify you.') }}
                </p>

                <h2 class="text-lg font-semibold text-[var(--color-text)]">{{ __('2. Responsible Body') }}</h2>
                <p>
                    {{ __('The responsible body for data processing on this website is:') }}<br>
                    ForumEscort<br>
                    [Adresse]<br>
                    {{ __('E-Mail') }}: datenschutz@forumescort.de
                </p>

                <h2 class="text-lg font-semibold text-[var(--color-text)]">{{ __('3. Data Collection on This Website') }}</h2>
                <h3 class="text-base font-medium text-[var(--color-text)]">{{ __('Cookies') }}</h3>
                <p>
                    {{ __('Our website uses cookies. These are small text files that your web browser stores on your device. We only use technically necessary cookies (age verification, session management).') }}
                </p>

                <h3 class="text-base font-medium text-[var(--color-text)]">{{ __('Server Log Files') }}</h3>
                <p>
                    {{ __('The provider of the pages automatically collects and stores information in so-called server log files. IP addresses are stored anonymously (hash).') }}
                </p>

                <h2 class="text-lg font-semibold text-[var(--color-text)]">{{ __('4. Registration on This Website') }}</h2>
                <p>
                    {{ __('You can register on this website. The data entered (username, email) is only used for the purpose of using the service. Legal basis: Art. 6 para. 1 lit. b GDPR.') }}
                </p>

                <h2 class="text-lg font-semibold text-[var(--color-text)]">{{ __('5. Your Rights') }}</h2>
                <p>{{ __('You have the right at any time to:') }}</p>
                <ul class="list-disc list-inside space-y-1">
                    <li>{{ __('Information about your stored data (Art. 15 GDPR)') }}</li>
                    <li>{{ __('Rectification of inaccurate data (Art. 16 GDPR)') }}</li>
                    <li>{{ __('Deletion of your data (Art. 17 GDPR)') }}</li>
                    <li>{{ __('Restriction of processing (Art. 18 GDPR)') }}</li>
                    <li>{{ __('Data portability (Art. 20 GDPR)') }}</li>
                    <li>{{ __('Objection to processing (Art. 21 GDPR)') }}</li>
                </ul>

                <h2 class="text-lg font-semibold text-[var(--color-text)]">{{ __('6. Deletion of Data') }}</h2>
                <p>
                    {{ __('To delete your account and all associated data, contact us at datenschutz@forumescort.de. We will delete your data within 30 days.') }}
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
