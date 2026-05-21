<x-app-layout title="DécisionClaire - feu vert ou orange avant une dépense" meta-description="Avant un achat ou une fin de mois tendue, DécisionClaire donne un verdict prudent, un risque et un plan d'action sans connexion bancaire.">
    <section class="dc-hero-band">
        <div class="relative dc-section py-16 sm:py-20 lg:py-24">
            <div class="max-w-3xl text-white">
                <p class="dc-badge-dark">Feu vert, orange ou rouge avant une dépense</p>
                <h1 class="mt-6 max-w-4xl break-words text-3xl font-extrabold leading-tight sm:text-6xl">
                    <span class="block">Stopper un achat impulsif</span>
                    <span class="block">avant qu'il pèse sur ton mois.</span>
                </h1>
                <p class="mt-6 max-w-2xl break-words text-base leading-8 text-emerald-50 sm:text-lg">
                    DécisionClaire ne remplace pas une app bancaire. Il répond à une question immédiate : est-ce que ton budget encaisse cette dépense, maintenant, sans te mettre en tension ?
                </p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('tools.purchase-decision.show') }}" class="inline-flex items-center justify-center rounded-md bg-white px-5 py-3 text-sm font-bold text-slate-950 shadow-xl transition hover:bg-emerald-50 focus:outline-none focus:ring-4 focus:ring-white/40">
                        Commencer par J’achète ou pas
                    </a>
                    <a href="{{ route('trust') }}" class="inline-flex items-center justify-center rounded-md border border-white/30 bg-white/10 px-5 py-3 text-sm font-bold text-white backdrop-blur transition hover:bg-white/20 focus:outline-none focus:ring-4 focus:ring-white/30">
                        Voir les limites
                    </a>
                </div>
            </div>

            <div class="relative mt-12 grid gap-3 sm:grid-cols-3 lg:max-w-4xl">
                @foreach ([
                    ['2 min', 'pour un verdict utile'],
                    ['Sans banque connectée', 'aucun accès à tes comptes'],
                    ['Compte après résultat', 'uniquement pour sauvegarder'],
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
                <p class="dc-badge">Preuve de résultat</p>
                <h2 class="mt-4 text-2xl font-extrabold text-slate-950 sm:text-3xl">Téléphone à 499 €, reste à vivre serré : verdict orange.</h2>
                <p class="mt-3 max-w-2xl break-words text-slate-700">
                    Le résultat ne dit pas seulement oui ou non. Il explique l'impact, le risque, l'attente conseillée et l'alternative à comparer.
                </p>
                <a href="{{ route('tools.purchase-decision.show') }}" class="dc-button-primary mt-5">Tester avec mon achat</a>
            </div>
            <div class="grid gap-3 sm:grid-cols-3 md:grid-cols-1">
                @foreach ([
                    ['Verdict', 'Achat limite'],
                    ['Risque', 'marge mensuelle trop fine'],
                    ['Action', 'attendre 48h ou baisser le budget'],
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
                <p class="dc-badge">Trois portes d'entrée</p>
                <h2 class="mt-4 text-3xl font-extrabold text-slate-950">Pars de ta situation, pas d'un tableur</h2>
            </div>
            <p class="max-w-xl text-sm leading-6 text-slate-600">
                Les autres outils restent disponibles, mais le point d'entrée principal est volontairement concret : une dépense à valider, un reste à vivre à protéger, une charge à réduire.
            </p>
        </div>

        <div class="mt-8 grid gap-4 md:grid-cols-3">
            <a href="{{ route('tools.purchase-decision.show') }}" class="dc-card dc-card-hover p-5">
                <span class="dc-badge">Achat important</span>
                <h3 class="mt-4 text-xl font-extrabold text-slate-950">Je veux acheter, mais j'hésite</h3>
                <p class="mt-3 text-sm leading-6 text-slate-600">Prix, urgence, utilité, épargne et marge mensuelle donnent un feu vert, orange ou rouge.</p>
            </a>
            <a href="{{ route('tools.living-balance.show') }}" class="dc-card dc-card-hover p-5">
                <span class="dc-badge">Fin de mois</span>
                <h3 class="mt-4 text-xl font-extrabold text-slate-950">Je veux savoir ce qu'il me reste</h3>
                <p class="mt-3 text-sm leading-6 text-slate-600">Un calcul simple pour voir le reste mensuel, hebdo et journalier sans importer tes comptes.</p>
            </a>
            <a href="{{ route('tools.subscription-audit.show') }}" class="dc-card dc-card-hover p-5">
                <span class="dc-badge">Charges récurrentes</span>
                <h3 class="mt-4 text-xl font-extrabold text-slate-950">Mes abonnements s'accumulent</h3>
                <p class="mt-3 text-sm leading-6 text-slate-600">Repère les abonnements peu utiles et l'économie annuelle possible.</p>
            </a>
        </div>
    </section>

    <section class="border-y border-white/70 bg-white/70 py-10 backdrop-blur">
        <div class="dc-section">
            <div class="max-w-2xl">
                <p class="dc-badge">Tous les calculateurs</p>
                <h2 class="mt-4 text-3xl font-extrabold text-slate-950">Six outils publics, utilisables sans compte</h2>
            </div>
            <div class="mt-7 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($tools as $tool)
                    <a href="{{ route($tool['route']) }}" class="dc-card dc-card-hover group relative overflow-hidden p-5">
                        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-emerald-500 via-sky-400 to-amber-300"></div>
                        <span class="dc-badge">{{ $tool['badge'] }}</span>
                        <h3 class="mt-4 text-xl font-extrabold text-slate-950">{{ $tool['name'] }}</h3>
                        <p class="mt-3 text-sm leading-6 text-slate-600">{{ $tool['short'] }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="dc-section py-10">
        <div class="grid gap-4 md:grid-cols-5">
            @foreach (['Gratuit', 'Sans compte obligatoire', 'Sans banque connectée', 'Résultat en moins de 2 minutes', 'Langage humain'] as $benefit)
                <div class="dc-kpi text-sm font-bold text-slate-800">{{ $benefit }}</div>
            @endforeach
        </div>
        <p class="mt-6 rounded-md border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-950">
            DécisionClaire est moins précis qu'une app bancaire connectée, mais il est suffisant pour clarifier une décision courte. Estimation indicative, ne remplace pas un conseil financier professionnel.
        </p>
    </section>
</x-app-layout>
