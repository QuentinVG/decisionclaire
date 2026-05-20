<x-app-layout title="DécisionClaire - outils gratuits pour décisions d’argent" meta-description="Des outils gratuits, simples et sans compte obligatoire pour clarifier une décision d’argent du quotidien.">
    <section class="bg-white">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-6 lg:grid-cols-[1.05fr_0.95fr] lg:px-8 lg:py-16">
            <div>
                <p class="text-sm font-semibold text-emerald-700">Calcule vite, comprends clairement, décide calmement.</p>
                <h1 class="mt-4 text-3xl font-bold tracking-normal text-slate-950 sm:text-5xl">Quelle décision d’argent veux-tu clarifier ?</h1>
                <p class="mt-5 max-w-2xl text-lg leading-8 text-slate-700">
                    DécisionClaire propose des outils gratuits pour éviter les mauvaises décisions d’argent, sans compte obligatoire et sans connexion bancaire.
                </p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('tools.purchase-decision.show') }}" class="inline-flex items-center justify-center rounded-md bg-emerald-700 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-800">
                        Tester J’achète ou pas ?
                    </a>
                    <a href="{{ route('tools.index') }}" class="inline-flex items-center justify-center rounded-md border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-800 hover:bg-slate-100">
                        Voir tous les outils
                    </a>
                </div>
            </div>

            <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-6">
                <p class="text-sm font-semibold text-emerald-800">Outil phare</p>
                <h2 class="mt-2 text-2xl font-bold text-slate-950">J’achète ou pas ?</h2>
                <p class="mt-3 text-slate-700">Évalue un achat en combinant prix, reste à vivre, épargne, urgence, utilité et fréquence d’usage.</p>
                <ul class="mt-5 space-y-2 text-sm text-slate-700">
                    <li>Verdict prudent : raisonnable, limite, impulsif probable ou risqué.</li>
                    <li>Coût par usage et impact sur ton reste à vivre.</li>
                    <li>Recommandation 48h quand l’achat paraît fragile.</li>
                </ul>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($tools as $tool)
                <a href="{{ route($tool['route']) }}" class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm transition hover:border-emerald-300 hover:shadow-md">
                    <span class="text-xs font-semibold uppercase text-emerald-700">{{ $tool['badge'] }}</span>
                    <h2 class="mt-2 text-xl font-semibold text-slate-950">{{ $tool['name'] }}</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ $tool['short'] }}</p>
                </a>
            @endforeach
        </div>
    </section>

    <section class="border-y border-slate-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-slate-950">Situations concrètes</h2>
            <div class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
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
                    <a href="{{ route($route) }}" class="rounded-md border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-800 hover:border-emerald-300 hover:bg-emerald-50">{{ $label }}</a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-4 md:grid-cols-5">
            @foreach (['Gratuit', 'Sans compte obligatoire', 'Sans connexion bancaire', 'Résultat en moins de 2 minutes', 'Langage humain'] as $benefit)
                <div class="rounded-md border border-slate-200 bg-white p-4 text-sm font-semibold text-slate-800">{{ $benefit }}</div>
            @endforeach
        </div>
        <p class="mt-6 text-sm text-slate-600">DécisionClaire ne remplace pas un conseiller financier. Estimation indicative, ne remplace pas un conseil financier professionnel.</p>
    </section>
</x-app-layout>
