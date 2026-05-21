<nav x-data="{ open: false }" class="sticky top-0 z-40 border-b border-white/70 bg-white/80 shadow-sm backdrop-blur-xl">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex min-h-16 items-center justify-between py-3">
            <div class="flex items-center gap-8">
                <a href="{{ route('home') }}" class="group flex items-center gap-3">
                    <x-application-logo class="h-10 w-10 transition group-hover:scale-105" />
                    <span class="leading-tight">
                        <span class="block text-base font-extrabold text-slate-950">DécisionClaire</span>
                        <span class="hidden text-xs font-semibold text-emerald-800 sm:block">Calcule vite. Décide calmement.</span>
                    </span>
                </a>

                <div class="hidden items-center gap-2 sm:flex">
                    <x-nav-link :href="route('tools.index')" :active="request()->routeIs('tools.*')">
                        Outils
                    </x-nav-link>
                    @auth
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            Dashboard
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            <div class="hidden items-center gap-3 sm:flex">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-emerald-300 hover:text-slate-950 focus:outline-none focus:ring-4 focus:ring-emerald-100">
                                <span>{{ Auth::user()->name }}</span>
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                Profil
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    Déconnexion
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('trust') }}" class="text-sm font-semibold text-slate-700 transition hover:text-slate-950">Confidentialité</a>
                    <a href="{{ route('tools.purchase-decision.show') }}" class="dc-button-primary px-4 py-2">
                        Tester un achat
                    </a>
                @endauth
            </div>

            <div class="flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center rounded-md border border-slate-200 bg-white p-2 text-slate-600 shadow-sm transition hover:text-slate-950 focus:outline-none focus:ring-4 focus:ring-emerald-100" aria-label="Ouvrir le menu">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden border-t border-slate-200 bg-white/95 px-4 pb-4 pt-3 shadow-lg backdrop-blur sm:hidden">
        <div class="space-y-2">
            <x-responsive-nav-link :href="route('tools.index')" :active="request()->routeIs('tools.*')">
                Outils
            </x-responsive-nav-link>
            @auth
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    Dashboard
                </x-responsive-nav-link>
            @endauth
        </div>

        <div class="mt-4 border-t border-slate-200 pt-4">
            @auth
                <div class="rounded-md bg-slate-50 px-4 py-3">
                    <div class="font-semibold text-slate-900">{{ Auth::user()->name }}</div>
                    <div class="text-sm text-slate-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-2">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        Profil
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            Déconnexion
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="space-y-2">
                    <x-responsive-nav-link :href="route('trust')">Confidentialité</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('tools.purchase-decision.show')">Tester un achat</x-responsive-nav-link>
                </div>
            @endauth
        </div>
    </div>
</nav>
