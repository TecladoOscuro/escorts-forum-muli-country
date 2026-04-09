<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Forum Escort' }} - {{ $currentTenant->name ?? 'Forum' }}</title>
    <meta name="description" content="{{ $metaDescription ?? __('Escort Forum - Reviews, Blogs and Community') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen flex flex-col" x-data="{ mobileMenu: false, userMenu: false }">

    {{-- Header --}}
    <header class="sticky top-0 z-50 border-b border-[var(--color-border)] bg-[var(--color-bg)]/95 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Left: Menu + Logo --}}
                <div class="flex items-center gap-4">
                    <button @click="mobileMenu = !mobileMenu" class="lg:hidden text-[var(--color-text-secondary)] hover:text-[var(--color-text)]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <span class="text-xl font-bold text-[var(--color-accent)]">Forum</span>
                        <span class="text-xl font-light text-[var(--color-text)]">Escort</span>
                        <span class="text-xs text-[var(--color-text-secondary)] hidden sm:inline">.{{ $currentTenant->slug ?? 'de' }}</span>
                    </a>
                </div>

                {{-- Center: Search (desktop) --}}
                <div class="hidden md:flex flex-1 max-w-md mx-8">
                    <form action="{{ route('search') }}" method="GET" class="w-full">
                        <div class="relative">
                            <input type="text" name="q" placeholder="{{ __('Search...') }}" value="{{ request('q') }}"
                                class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-lg px-4 py-2 pl-10 text-sm text-[var(--color-text)] placeholder-[var(--color-text-secondary)] focus:outline-none focus:border-[var(--color-accent)] transition-colors">
                            <svg class="absolute left-3 top-2.5 w-4 h-4 text-[var(--color-text-secondary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                    </form>
                </div>

                {{-- Right: Lang + Auth / User --}}
                <div class="flex items-center gap-3">
                    {{-- Language Switcher --}}
                    <div class="relative" x-data="{ langOpen: false }" @click.away="langOpen = false">
                        <button @click="langOpen = !langOpen" class="flex items-center gap-1 text-sm text-[var(--color-text-secondary)] hover:text-[var(--color-text)] transition-colors">
                            <span class="uppercase font-medium">{{ app()->getLocale() }}</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="langOpen" x-transition class="absolute right-0 mt-2 w-32 bg-[var(--color-surface)] border border-[var(--color-border)] rounded-lg shadow-xl py-1 z-50">
                            <a href="{{ route('locale.switch', 'de') }}" class="block px-4 py-2 text-sm {{ app()->getLocale() === 'de' ? 'text-[var(--color-accent)]' : 'text-[var(--color-text-secondary)]' }} hover:text-[var(--color-text)] hover:bg-[var(--color-surface-hover)]">Deutsch</a>
                            <a href="{{ route('locale.switch', 'en') }}" class="block px-4 py-2 text-sm {{ app()->getLocale() === 'en' ? 'text-[var(--color-accent)]' : 'text-[var(--color-text-secondary)]' }} hover:text-[var(--color-text)] hover:bg-[var(--color-surface-hover)]">English</a>
                        </div>
                    </div>

                    @auth
                        @if(auth()->user()->isEscort())
                            <span class="hidden sm:flex items-center gap-1 text-sm text-[var(--color-gold)]">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="10"/></svg>
                                {{ auth()->user()->token_balance }}
                            </span>
                        @endif

                        <a href="{{ route('messages.index') }}" class="relative text-[var(--color-text-secondary)] hover:text-[var(--color-text)] transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </a>

                        <div class="relative" @click.away="userMenu = false">
                            <button @click="userMenu = !userMenu" class="flex items-center gap-2">
                                <img src="{{ auth()->user()->avatar_url }}" alt="" class="w-8 h-8 rounded-full border-2 border-[var(--color-border)]">
                            </button>
                            <div x-show="userMenu" x-transition class="absolute right-0 mt-2 w-48 bg-[var(--color-surface)] border border-[var(--color-border)] rounded-lg shadow-xl py-1 z-50">
                                <div class="px-4 py-2 border-b border-[var(--color-border)]">
                                    <p class="text-sm font-medium text-[var(--color-text)]">{{ auth()->user()->username }}</p>
                                    <p class="text-xs text-[var(--color-text-secondary)]">{{ auth()->user()->role }}</p>
                                </div>
                                @if(auth()->user()->isEscort() && auth()->user()->escortProfile)
                                    <a href="{{ route('escorts.show', auth()->user()->escortProfile) }}" class="block px-4 py-2 text-sm text-[var(--color-text-secondary)] hover:text-[var(--color-text)] hover:bg-[var(--color-surface-hover)]">{{ __('My Profile') }}</a>
                                @endif
                                @if(auth()->user()->isEscort())
                                    <a href="{{ route('tokens.index') }}" class="block px-4 py-2 text-sm text-[var(--color-text-secondary)] hover:text-[var(--color-text)] hover:bg-[var(--color-surface-hover)]">{{ __('Tokens') }} ({{ auth()->user()->token_balance }})</a>
                                @endif
                                <a href="{{ route('messages.index') }}" class="block px-4 py-2 text-sm text-[var(--color-text-secondary)] hover:text-[var(--color-text)] hover:bg-[var(--color-surface-hover)]">{{ __('Messages') }}</a>
                                @if(auth()->user()->isAdmin())
                                    <a href="/admin" class="block px-4 py-2 text-sm text-[var(--color-accent)] hover:bg-[var(--color-surface-hover)]">{{ __('Admin Panel') }}</a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-[var(--color-danger)] hover:bg-[var(--color-surface-hover)]">{{ __('Log Out') }}</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-[var(--color-text-secondary)] hover:text-[var(--color-text)] transition-colors">{{ __('Log In') }}</a>
                        <a href="{{ route('register') }}" class="text-sm px-4 py-2 bg-[var(--color-accent)] hover:bg-[var(--color-accent-hover)] text-white rounded-lg transition-colors btn-press">{{ __('Register') }}</a>
                    @endauth
                </div>
            </div>
        </div>

        {{-- Mobile search --}}
        <div class="md:hidden px-4 pb-3">
            <form action="{{ route('search') }}" method="GET">
                <input type="text" name="q" placeholder="{{ __('Search...') }}" value="{{ request('q') }}"
                    class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-lg px-4 py-2 text-sm text-[var(--color-text)] placeholder-[var(--color-text-secondary)] focus:outline-none focus:border-[var(--color-accent)]">
            </form>
        </div>
    </header>

    {{-- Mobile Menu Overlay --}}
    <div x-show="mobileMenu" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 bg-black/60 lg:hidden" @click="mobileMenu = false"></div>
    <aside x-show="mobileMenu" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed top-0 left-0 z-50 w-72 h-full bg-[var(--color-surface)] border-r border-[var(--color-border)] lg:hidden overflow-y-auto">
        <div class="p-4 border-b border-[var(--color-border)] flex items-center justify-between">
            <span class="text-lg font-bold text-[var(--color-accent)]">{{ __('Menu') }}</span>
            <button @click="mobileMenu = false" class="text-[var(--color-text-secondary)]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <nav class="p-4 space-y-2">
            <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-[var(--color-text-secondary)] hover:text-[var(--color-text)] hover:bg-[var(--color-surface-hover)] transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                {{ __('Home') }}
            </a>
            <a href="{{ route('escorts.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-[var(--color-text-secondary)] hover:text-[var(--color-text)] hover:bg-[var(--color-surface-hover)] transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                {{ __('Escorts') }}
            </a>
            <a href="{{ route('forum.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-[var(--color-text-secondary)] hover:text-[var(--color-text)] hover:bg-[var(--color-surface-hover)] transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
                {{ __('Forum') }}
            </a>
            <a href="{{ route('reviews.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-[var(--color-text-secondary)] hover:text-[var(--color-text)] hover:bg-[var(--color-surface-hover)] transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                {{ __('Reviews') }}
            </a>
        </nav>
    </aside>

    {{-- Main content --}}
    <main class="flex-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            {{ $slot }}
        </div>
    </main>

    {{-- Footer --}}
    <footer class="border-t border-[var(--color-border)] bg-[var(--color-surface)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-sm font-semibold text-[var(--color-text)] mb-3">{{ __('Navigation') }}</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('escorts.index') }}" class="text-sm text-[var(--color-text-secondary)] hover:text-[var(--color-accent)]">{{ __('Escorts') }}</a></li>
                        <li><a href="{{ route('forum.index') }}" class="text-sm text-[var(--color-text-secondary)] hover:text-[var(--color-accent)]">{{ __('Forum') }}</a></li>
                        <li><a href="{{ route('reviews.index') }}" class="text-sm text-[var(--color-text-secondary)] hover:text-[var(--color-accent)]">{{ __('Reviews') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-[var(--color-text)] mb-3">{{ __('Legal') }}</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('impressum') }}" class="text-sm text-[var(--color-text-secondary)] hover:text-[var(--color-accent)]">{{ __('Imprint') }}</a></li>
                        <li><a href="{{ route('privacy') }}" class="text-sm text-[var(--color-text-secondary)] hover:text-[var(--color-accent)]">{{ __('Privacy Policy') }}</a></li>
                        <li><a href="{{ route('rules') }}" class="text-sm text-[var(--color-text-secondary)] hover:text-[var(--color-accent)]">{{ __('Forum Rules') }}</a></li>
                    </ul>
                </div>
                <div class="col-span-2">
                    <h3 class="text-sm font-semibold text-[var(--color-text)] mb-3">ForumEscort.{{ $currentTenant->slug ?? 'de' }}</h3>
                    <p class="text-sm text-[var(--color-text-secondary)]">{{ __('Community forum for reviews and experience reports. All participants must be 18 years or older.') }}</p>
                </div>
            </div>
            <div class="mt-8 pt-6 border-t border-[var(--color-border)] text-center text-xs text-[var(--color-text-secondary)]">
                &copy; {{ date('Y') }} ForumEscort. {{ __('All rights reserved.') }}
            </div>
        </div>
    </footer>

    {{-- Mobile Bottom Nav --}}
    <nav class="fixed bottom-0 left-0 right-0 z-40 bg-[var(--color-surface)]/95 backdrop-blur-md border-t border-[var(--color-border)] md:hidden">
        <div class="flex items-center justify-around h-14">
            <a href="{{ route('home') }}" class="flex flex-col items-center gap-0.5 text-[var(--color-text-secondary)] hover:text-[var(--color-accent)] {{ request()->routeIs('home') ? 'text-[var(--color-accent)]' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span class="text-[10px]">{{ __('Home') }}</span>
            </a>
            <a href="{{ route('escorts.index') }}" class="flex flex-col items-center gap-0.5 text-[var(--color-text-secondary)] hover:text-[var(--color-accent)] {{ request()->routeIs('escorts.*') ? 'text-[var(--color-accent)]' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                <span class="text-[10px]">{{ __('Escorts') }}</span>
            </a>
            <a href="{{ route('forum.index') }}" class="flex flex-col items-center gap-0.5 text-[var(--color-text-secondary)] hover:text-[var(--color-accent)] {{ request()->routeIs('forum.*') ? 'text-[var(--color-accent)]' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
                <span class="text-[10px]">{{ __('Forum') }}</span>
            </a>
            <a href="{{ route('reviews.index') }}" class="flex flex-col items-center gap-0.5 text-[var(--color-text-secondary)] hover:text-[var(--color-accent)] {{ request()->routeIs('reviews.*') ? 'text-[var(--color-accent)]' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                <span class="text-[10px]">{{ __('Reviews') }}</span>
            </a>
        </div>
    </nav>

    {{-- Bottom padding for mobile nav --}}
    <div class="h-14 md:hidden"></div>

    @livewireScripts
</body>
</html>
