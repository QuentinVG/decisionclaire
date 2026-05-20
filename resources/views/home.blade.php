<x-app-layout title="DécisionClaire - outils gratuits pour décisions d’argent" meta-description="Des outils gratuits, simples et sans compte obligatoire pour clarifier une décision d’argent du quotidien.">
    <section class="dc-hero-band">
        <div class="relative dc-section py-16 sm:py-20 lg:py-24">
            <div class="max-w-3xl text-white">
                <p class="dc-badge-dark">Calcule vite, comprends clairement, décide calmement.</p>
                <h1 class="mt-6 max-w-4xl break-words text-2xl font-extrabold leading-tight sm:text-6xl">
                    <span class="block">Quelle décision d’argent</span>
                    <span class="block">veux-tu clarifier ?</span>
                </h1>
                <p class="mt-6 max-w-2xl break-words text-base leading-8 text-emerald-50 sm:text-lg">
                    DécisionClaire transforme un doute flou en verdict lisible : un chiffre principal, un niveau de risque, une explication et des actions prudentes.
                </p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('tools.purchase-decision.show') }}" class="inline-flex items-center justify-center rounded-md bg-white px-5 py-3 text-sm font-bold text-slate-950 shadow-xl transition hover:bg-emerald-50 focus:outline-none focus:ring-4 focus:ring-white/40">
                        Tester J’achète ou pas ?
                    </a>
                    <a href="{{ route('tools.index') }}" class="inline-flex items-center justify-center rounded-md border border-white/30 bg-white/10 px-5 py-3 text-sm font-bold text-white backdrop-blur transition hover:bg-white/20 focus:outline-none focus:ring-4 focus:ring-white/30">
                        Voir tous les outils
                    </a>
                </div>
            </div>

            <div class="relative mt-12 grid gap-3 sm:grid-cols-3 lg:max-w-4xl">
                @foreach ([
                    ['2 min', 'un résultat utile'],
                    ['0 banque', 'aucune connexion sensible'],
                    ['6 outils', 'pour les choix du quotidien'],
                ] as [$value, $label])
                    <div class="rounded-lg border border-white/20 bg-white/10 p-4 text-white shadow-xl backdrop-blur">
                        <p class="text-2xl font-extrabold">{{ $value }}</p>
                        <p class="mt-1 text-sm font-medium text-emerald-50">{{ $label }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="dc-section -mt-6 pb-10">
        <div class="dc-surface grid gap-5 p-5 md:grid-cols-[1.1fr_0.9fr] lg:p-7">
            <div class="min-w-0">
                <p class="dc-badge">Outil phare</p>
                <h2 class="mt-4 text-2xl font-extrabold text-slate-950 sm:text-3xl">J’achète ou pas ?</h2>
                <p class="mt-3 max-w-2xl break-words text-slate-700">
                    Le raccourci pour sortir du “je ne sais pas”. Prix, utilité, urgence, épargne et reste à vivre sont croisés pour produire un verdict prudent, jamais absolu.
                </p>
            </div>
            <div class="grid gap-3 sm:grid-cols-3 md:grid-cols-1">
                @foreach ([
                    ['Verdict', 'raisonnable, limite ou risqué'],
                    ['Impact', 'sur reste à vivre et épargne'],
                    ['Conseil', '48h, alternative ou marge à garder'],
                ] as [$label, $text])
                    <div class="rounded-md border border-slate-200 bg-slate-50 p-3">
                        <p class="text-xs font-bold text-emerald-800">{{ $label }}</p>
                        <p class="mt-1 text-sm font-semibold text-slate-800">{{ $text }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="dc-section py-10">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="dc-badge">Choisis ton point de départ</p>
                <h2 class="mt-4 text-3xl font-extrabold text-slate-950">Un besoin, un outil clair</h2>
            </div>
            <p class="max-w-xl text-sm leading-6 text-slate-600">
                Chaque page commence par des raccourcis guidés : tu peux partir d’une estimation prudente, puis corriger uniquement ce que tu connais.
            </p>
        </div>

        <div class="mt-8 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($tools as $tool)
                <a href="{{ route($tool['route']) }}" class="dc-card dc-card-hover group relative overflow-hidden p-5">
                    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-emerald-500 via-sky-400 to-amber-300"></div>
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <span class="dc-badge">{{ $tool['badge'] }}</span>
                            <h3 class="mt-4 text-xl font-extrabold text-slate-950">{{ $tool['name'] }}</h3>
                        </div>
                        <span class="rounded-md bg-slate-950 px-2.5 py-1 text-xs font-bold text-white transition group-hover:bg-emerald-700">ouvrir</span>
                    </div>
                    <p class="mt-3 text-sm leading-6 text-slate-600">{{ $tool['short'] }}</p>
                </a>
            @endforeach
        </div>
    </section>

    <section class="border-y border-white/70 bg-white/70 py-10 backdrop-blur">
        <div class="dc-section">
            <div class="max-w-2xl">
                <p class="dc-badge">Situations concrètes</p>
                <h2 class="mt-4 text-3xl font-extrabold text-slate-950">Pars de la vraie question, pas d’un tableur</h2>
            </div>
            <div class="mt-7 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                @php
                    $situations = [
                        ['Acheter un téléphone', 'tools.purchase-decision.show'],
                        ['Acheter un PC', 'tools.purchase-decision.show'],
                        ['Prendre un abonnement', 'tools.subscription-audit.show'],
                        ['Faire un voyage', 'tools.savings-goal.show'],
                        ['Acheter du matériel', 'tools.large-purchase-impact.show'],
                        ['Réduire ses abonnements', 'tools.subscription-audit.show'],
                        ['Préparer une épargne', 'tools.savings-goal.show'],
                        ['Comparer deux options', 'tools.scenario-comparator.show'],
                    ];
                @endphp
                @foreach ($situations as [$label, $route])
                    <a href="{{ route($route) }}" class="dc-card dc-card-hover px-4 py-4 text-sm font-bold text-slate-800">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="dc-section py-10">
        <div class="grid gap-4 md:grid-cols-5">
            @foreach (['Gratuit', 'Sans compte obligatoire', 'Sans connexion bancaire', 'Résultat en moins de 2 minutes', 'Langage humain'] as $benefit)
                <div class="dc-kpi text-sm font-bold text-slate-800">{{ $benefit }}</div>
            @endforeach
        </div>
        <p class="mt-6 rounded-md border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-950">
            DécisionClaire ne remplace pas un conseiller financier. Estimation indicative, ne remplace pas un conseil financier professionnel.
        </p>
    </section>
</x-app-layout>
