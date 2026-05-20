<x-app-layout title="Tous les outils DécisionClaire" meta-description="Accède aux calculateurs gratuits DécisionClaire : reste à vivre, achat raisonnable, objectif épargne, abonnements et scénarios.">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <p class="text-sm font-semibold text-emerald-700">Outils gratuits</p>
            <h1 class="mt-2 text-3xl font-bold text-slate-950">Clarifier une décision d’argent sans compte obligatoire</h1>
            <p class="mt-4 text-slate-700">Chaque outil demande quelques informations simples, affiche un verdict prudent, un chiffre principal, une explication et un résumé copiable.</p>
        </div>

        <div class="mt-8 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($tools as $tool)
                <a href="{{ route($tool['route']) }}" class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm hover:border-emerald-300">
                    <span class="text-xs font-semibold uppercase text-emerald-700">{{ $tool['badge'] }}</span>
                    <h2 class="mt-2 text-xl font-semibold text-slate-950">{{ $tool['name'] }}</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ $tool['short'] }}</p>
                </a>
            @endforeach
        </div>
    </div>
</x-app-layout>
