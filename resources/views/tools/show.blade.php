<x-app-layout :title="$tool['seo_title']" :meta-description="$tool['meta']">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-[0.95fr_1.05fr]">
            <section>
                <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold uppercase text-emerald-800">{{ $tool['badge'] }}</span>
                <h1 class="mt-4 text-3xl font-bold text-slate-950">{{ $tool['h1'] }}</h1>
                <p class="mt-4 leading-7 text-slate-700">{{ $tool['intro'] }}</p>
                <p class="mt-4 rounded-md border border-amber-200 bg-amber-50 p-3 text-sm font-medium text-amber-900">Estimation indicative, ne remplace pas un conseil financier professionnel.</p>

                <div class="mt-6 grid gap-2">
                    @foreach ($tools as $otherTool)
                        <a href="{{ route($otherTool['route']) }}" class="rounded-md border px-3 py-2 text-sm font-medium {{ $otherTool['key'] === $tool['key'] ? 'border-emerald-300 bg-emerald-50 text-emerald-900' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50' }}">
                            {{ $otherTool['name'] }}
                        </a>
                    @endforeach
                </div>
            </section>

            <div class="space-y-6">
                <section id="formulaire" class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <h2 class="text-xl font-semibold text-slate-950">Réponds à quelques questions simples</h2>
                    <p class="mt-2 text-sm text-slate-600">Utilise les raccourcis si tu ne connais pas les montants exacts, puis corrige seulement ce que tu sais.</p>

                    @if ($errors->any())
                        <div class="mt-4 rounded-md border border-red-200 bg-red-50 p-3 text-sm text-red-800">
                            <p class="font-semibold">Certaines réponses sont à corriger.</p>
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

        <section class="mt-10 rounded-lg border border-slate-200 bg-white p-6">
            <h2 class="text-xl font-semibold text-slate-950">Questions fréquentes</h2>
            <div class="mt-4 grid gap-4 md:grid-cols-2">
                @foreach ($tool['faq'] as $item)
                    <div>
                        <h3 class="font-semibold text-slate-900">{{ $item['q'] }}</h3>
                        <p class="mt-1 text-sm leading-6 text-slate-600">{{ $item['a'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
</x-app-layout>
