<x-app-layout title="Tous les outils DécisionClaire" meta-description="Accède aux calculateurs gratuits DécisionClaire : reste à vivre, achat raisonnable, objectif épargne, abonnements et scénarios.">
    <div class="dc-section py-12">
        <div class="dc-surface overflow-hidden">
            <div class="bg-slate-950 px-6 py-10 text-white sm:px-8">
                <p class="dc-badge-dark">Outils gratuits</p>
                <h1 class="mt-4 max-w-3xl text-4xl font-extrabold leading-tight sm:text-5xl">
                    Clarifier une décision d’argent sans compte obligatoire
                </h1>
                <p class="mt-5 max-w-2xl text-lg leading-8 text-slate-200">
                    Six parcours courts, des raccourcis guidés et un résultat qui explique le risque sans dramatiser.
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
                ['Simple', 'Des profils et valeurs prudentes pour commencer sans tout connaître.'],
                ['Pédagogique', 'Un verdict, un chiffre clé, une explication et des recommandations.'],
                ['Respectueux', 'Aucun compte bancaire, aucune IA, aucun conseil financier déguisé.'],
            ] as [$title, $text])
                <div class="dc-kpi">
                    <h2 class="font-extrabold text-slate-950">{{ $title }}</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ $text }}</p>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
