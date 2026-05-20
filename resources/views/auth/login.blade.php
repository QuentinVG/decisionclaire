<x-guest-layout>
    <div class="mb-6">
        <p class="dc-badge">Compte optionnel</p>
        <h1 class="mt-3 text-2xl font-extrabold text-slate-950">Retrouver mes simulations</h1>
        <p class="mt-2 text-sm leading-6 text-slate-600">Les outils restent gratuits sans compte. La connexion sert uniquement à sauvegarder ton historique.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="Mot de passe" />
            <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4 block">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-emerald-700 shadow-sm focus:ring-emerald-500" name="remember">
                <span class="ms-2 text-sm font-medium text-slate-600">Se souvenir de moi</span>
            </label>
        </div>

        <div class="mt-5 flex items-center justify-between gap-3">
            @if (Route::has('password.request'))
                <a class="rounded-md text-sm font-semibold text-slate-600 transition hover:text-slate-950 focus:outline-none focus:ring-4 focus:ring-emerald-100" href="{{ route('password.request') }}">
                    Mot de passe oublié ?
                </a>
            @endif

            <x-primary-button>
                Connexion
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
