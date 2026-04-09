<x-app-layout>
    <x-slot:title>{{ __('Imprint') }}</x-slot:title>

    <div class="max-w-3xl mx-auto">
        <div class="glass-card p-8">
            <h1 class="text-2xl font-bold text-[var(--color-text)] mb-6">{{ __('Imprint') }}</h1>

            <div class="prose prose-invert max-w-none space-y-4 text-[var(--color-text-secondary)]">
                <h2 class="text-lg font-semibold text-[var(--color-text)]">{{ __('Information according to § 5 TMG') }}</h2>
                <p>
                    ForumEscort<br>
                    [Vollständiger Name des Betreibers]<br>
                    [Straße und Hausnummer]<br>
                    [PLZ und Stadt]<br>
                    [Land]
                </p>

                <h2 class="text-lg font-semibold text-[var(--color-text)]">{{ __('Contact') }}</h2>
                <p>
                    {{ __('E-Mail') }}: kontakt@forumescort.de<br>
                    Telefon: [Telefonnummer]
                </p>

                <h2 class="text-lg font-semibold text-[var(--color-text)]">{{ __('Trade Register') }}</h2>
                <p>
                    [Registergericht]<br>
                    [Registernummer]
                </p>

                <h2 class="text-lg font-semibold text-[var(--color-text)]">{{ __('VAT ID') }}</h2>
                <p>
                    {{ __('VAT identification number according to § 27a of the Value Added Tax Act:') }}<br>
                    [USt-IdNr.]
                </p>

                <h2 class="text-lg font-semibold text-[var(--color-text)]">{{ __('Responsible for content according to § 55 para. 2 RStV') }}</h2>
                <p>
                    [Name]<br>
                    [Adresse]
                </p>

                <h2 class="text-lg font-semibold text-[var(--color-text)]">{{ __('Disclaimer') }}</h2>
                <p>
                    {{ __('The contents of our pages were created with the greatest care. However, we cannot guarantee the accuracy, completeness, and timeliness of the content.') }}
                </p>
                <p>
                    {{ __('As a service provider, we are responsible for our own content on these pages in accordance with § 7 para. 1 TMG under general laws. According to §§ 8 to 10 TMG, however, we are not obligated to monitor transmitted or stored third-party information.') }}
                </p>

                <h2 class="text-lg font-semibold text-[var(--color-text)]">{{ __('Dispute Resolution') }}</h2>
                <p>
                    {{ __('The European Commission provides a platform for online dispute resolution (OS). We are neither obligated nor willing to participate in a dispute resolution procedure before a consumer arbitration board.') }}
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
