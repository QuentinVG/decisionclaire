<x-app-layout :title="$tool['seo_title']" :meta-description="$tool['meta']">
    <div class="dc-section py-8 sm:py-10">
        <div class="grid gap-7 lg:grid-cols-[0.88fr_1.12fr]">
            <section class="order-2 lg:order-none lg:sticky lg:top-24 lg:self-start">
                <div class="dc-surface overflow-hidden">
                    <div class="bg-slate-950 p-6 text-white">
                        <span class="dc-badge-dark">{{ $tool['badge'] }}</span>
                        <h1 class="mt-5 text-3xl font-extrabold leading-tight sm:text-4xl">{{ $tool['h1'] }}</h1>
                        <p class="mt-4 leading-7 text-slate-200">{{ $tool['intro'] }}</p>
                    </div>
                    <div class="space-y-4 p-5">
                        <p class="rounded-md border border-amber-200 bg-amber-50 p-3 text-sm font-semibold text-amber-950">
                            Estimation indicative, ne remplace pas un conseil financier professionnel.
                        </p>
                        <div class="rounded-md border border-emerald-200 bg-emerald-50 p-3">
                            <p class="text-sm font-extrabold text-emerald-950">Repère express</p>
                            <p class="mt-1 text-sm leading-6 text-emerald-900">Saisis les champs que tu connais, lance le calcul, puis lis dans l’ordre : feu, risque, action.</p>
                        </div>
                        <div class="grid gap-2">
                            @foreach ($tools as $otherTool)
                                <a href="{{ route($otherTool['route']) }}" class="dc-tool-pill {{ $otherTool['key'] === $tool['key'] ? 'border-emerald-300 bg-emerald-50 text-emerald-950 shadow-sm' : 'border-slate-200 bg-white text-slate-700 hover:border-emerald-300 hover:bg-emerald-50' }}">
                                    {{ $otherTool['name'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            <div class="order-1 space-y-6 lg:order-none">
                <section id="formulaire" class="dc-surface dc-form-shell p-5 sm:p-6">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <p class="dc-badge">Mode guidé</p>
                            <h2 class="mt-3 text-2xl font-extrabold text-slate-950">Réponds à quelques questions simples</h2>
                            <p class="mt-2 text-sm leading-6 text-slate-600">Utilise les raccourcis si tu ne connais pas les montants exacts, puis corrige seulement ce que tu sais.</p>
                        </div>
                        <div class="rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-bold text-slate-800">
                            Résultat en moins de 2 minutes
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="mt-5 rounded-md border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                            <p class="font-bold">Certaines réponses sont à corriger.</p>
                            <ul class="mt-2 list-disc space-y-1 pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @include($tool['form'])
                </section>

                @if ($result)
                    @include('tools.partials.result', ['result' => $result, 'pendingKey' => $pendingKey, 'savedSimulation' => $savedSimulation])
                @endif
            </div>
        </div>

        <section class="dc-surface mt-10 p-6">
            <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="dc-badge">À savoir</p>
                    <h2 class="mt-3 text-2xl font-extrabold text-slate-950">Questions fréquentes</h2>
                </div>
                <p class="max-w-xl text-sm leading-6 text-slate-600">Les réponses restent volontairement courtes : l’objectif est d’aider à décider, pas de remplir un dossier.</p>
            </div>
            <div class="mt-6 grid gap-4 md:grid-cols-2">
                @foreach ($tool['faq'] as $item)
                    <div class="rounded-md border border-slate-200 bg-white p-4">
                        <h3 class="font-bold text-slate-950">{{ $item['q'] }}</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">{{ $item['a'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
</x-app-layout>
