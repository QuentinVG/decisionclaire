<x-guest-layout>
    <div class="mb-6">
        <p class="dc-badge">Compte optionnel</p>
        <h1 class="mt-3 text-2xl font-extrabold text-slate-950">Sauvegarder mes décisions</h1>
        <p class="mt-2 text-sm leading-6 text-slate-600">Crée un compte seulement si tu veux retrouver, dupliquer ou exporter tes simulations.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <x-input-label for="name" value="Nom" />
            <x-text-input id="name" class="mt-1 block w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="Mot de passe" />
            <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Confirmer le mot de passe" />
            <x-text-input id="password_confirmation" class="mt-1 block w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-5 flex items-center justify-between gap-3">
            <a class="rounded-md text-sm font-semibold text-slate-600 transition hover:text-slate-950 focus:outline-none focus:ring-4 focus:ring-emerald-100" href="{{ route('login') }}">
                Déjà inscrit ?
            </a>

            <x-primary-button>
                Créer le compte
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
