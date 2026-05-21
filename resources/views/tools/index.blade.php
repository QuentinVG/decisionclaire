<x-app-layout title="Tous les outils DécisionClaire" meta-description="Accède aux calculateurs gratuits DécisionClaire : reste à vivre, achat raisonnable, objectif épargne, abonnements et scénarios.">
    <div class="dc-section py-12">
        <div class="dc-surface overflow-hidden">
            <div class="bg-slate-950 px-6 py-10 text-white sm:px-8">
                <p class="dc-badge-dark">Feux tricolores de décision</p>
                <h1 class="mt-4 max-w-3xl text-4xl font-extrabold leading-tight sm:text-5xl">
                    Un outil par décision d'argent, pas un tableur complet
                </h1>
                <p class="mt-5 max-w-2xl text-lg leading-8 text-slate-200">
                    Commence par le doute réel : achat à valider, reste à vivre, objectif d'épargne, abonnements ou scénarios à comparer. Le compte vient seulement après le résultat.
                </p>
            </div>

            <div class="grid gap-4 p-5 md:grid-cols-2 lg:grid-cols-3 lg:p-7">
                @foreach ($tools as $tool)
                    <a href="{{ route($tool['route']) }}" class="dc-card dc-card-hover group relative overflow-hidden p-5">
                        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-emerald-500 via-sky-400 to-amber-300"></div>
                        <span class="dc-badge">{{ $tool['badge'] }}</span>
                        <h2 class="mt-4 text-xl font-extrabold text-slate-950">{{ $tool['name'] }}</h2>
                        <p class="mt-3 text-sm leading-6 text-slate-600">{{ $tool['short'] }}</p>
                        <p class="mt-5 text-sm font-bold text-emerald-800 transition group-hover:text-slate-950">Ouvrir l’outil</p>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="mt-8 grid gap-4 md:grid-cols-3">
            @foreach ([
                ['Décision immédiate', 'Un verdict prudent pour agir maintenant : acheter, attendre, réduire ou comparer.'],
                ['Preuve lisible', 'Un chiffre clé, un risque, une confiance et des recommandations concrètes.'],
                ['Respectueux', 'Aucun compte bancaire, aucun conseil financier déguisé, compte optionnel après résultat.'],
            ] as [$title, $text])
                <div class="dc-kpi">
                    <h2 class="font-extrabold text-slate-950">{{ $title }}</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ $text }}</p>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
