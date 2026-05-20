@php
    $riskClass = match ($result['risk_level'] ?? '') {
        'risqué' => 'bg-red-50 text-red-800 border-red-200',
        'limite' => 'bg-amber-50 text-amber-900 border-amber-200',
        'modéré' => 'bg-sky-50 text-sky-800 border-sky-200',
        default => 'bg-emerald-50 text-emerald-800 border-emerald-200',
    };
    $confidence = min(100, max(0, (int) ($result['confidence_score'] ?? 0)));
@endphp

<section id="resultat" class="dc-surface dc-lift overflow-hidden">
    <div class="bg-slate-950 p-6 text-white sm:p-7">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="dc-badge-dark">Résultat indicatif</p>
                <h2 class="mt-4 text-3xl font-extrabold leading-tight">{{ $result['verdict'] }}</h2>
            </div>
            <span class="inline-flex w-fit rounded-full border px-3 py-1 text-sm font-bold {{ $riskClass }}">
                Risque : {{ $result['risk_level'] }}
            </span>
        </div>

        <div class="mt-7 rounded-lg border border-white/20 bg-white/10 p-5 backdrop-blur">
            <p class="text-sm font-semibold text-emerald-100">{{ $result['primary_label'] }}</p>
            <p class="mt-2 text-4xl font-extrabold sm:text-5xl">{{ $result['primary_value'] }}</p>
        </div>
    </div>

    <div class="space-y-6 p-5 sm:p-6">
        <div class="grid gap-4 sm:grid-cols-[0.8fr_1.2fr]">
            <div class="dc-kpi">
                <p class="text-sm font-semibold text-slate-500">Confiance du résultat</p>
                <p class="mt-1 text-2xl font-extrabold text-slate-950">{{ $confidence }}/100</p>
                <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-100">
                    <div class="dc-result-meter h-full rounded-full" style="width: {{ $confidence }}%"></div>
                </div>
            </div>
            <div class="dc-kpi">
                <p class="text-sm font-semibold text-slate-500">Mention</p>
                <p class="mt-1 text-sm font-semibold leading-6 text-slate-800">{{ $result['notice'] }}</p>
            </div>
        </div>

        <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-5">
            <h3 class="font-extrabold text-emerald-950">Pourquoi ce verdict ?</h3>
            <p class="mt-2 leading-7 text-emerald-950">{{ $result['explanation'] }}</p>
        </div>

        @if (! empty($result['metrics']))
            <div class="grid gap-3 sm:grid-cols-2">
                @foreach ($result['metrics'] as $metric)
                    <div class="dc-kpi">
                        <p class="text-sm font-semibold text-slate-500">{{ $metric['label'] }}</p>
                        <p class="mt-1 font-extrabold text-slate-950">{{ $metric['value'] }}</p>
                        @isset($metric['help'])
                            <p class="mt-1 text-xs leading-5 text-slate-500">{{ $metric['help'] }}</p>
                        @endisset
                    </div>
                @endforeach
            </div>
        @endif

        <div>
            <h3 class="text-lg font-extrabold text-slate-950">Recommandations concrètes</h3>
            <ul class="mt-3 grid gap-3">
                @foreach ($result['recommendations'] as $recommendation)
                    <li class="flex gap-3 rounded-md border border-slate-200 bg-white p-3 text-sm leading-6 text-slate-700 shadow-sm">
                        <span class="mt-1 h-2 w-2 shrink-0 rounded-full bg-emerald-500"></span>
                        <span>{{ $recommendation }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <div x-data="{ copied: false }">
            <label for="summary-copy" class="text-lg font-extrabold text-slate-950">Résumé à copier</label>
            <textarea id="summary-copy" readonly rows="6" class="mt-3 w-full rounded-md border-slate-300 bg-slate-50 text-sm text-slate-800 shadow-inner focus:border-emerald-500 focus:ring-emerald-500">{{ $result['summary'] }}</textarea>
            <div class="mt-3 flex flex-col gap-2 sm:flex-row">
                <button type="button" @click="navigator.clipboard.writeText(document.getElementById('summary-copy').value); copied = true" class="dc-button-primary">
                    Copier le résultat
                </button>
                <a href="#formulaire" class="dc-button-secondary">
                    Modifier mes réponses
                </a>
                @auth
                    @if ($savedSimulation)
                        <a href="{{ route('simulations.export-pdf', $savedSimulation) }}" class="dc-button-secondary border-emerald-300 text-emerald-900">
                            Exporter PDF
                        </a>
                    @elseif ($pendingKey)
                        <form method="POST" action="{{ route('simulations.store') }}">
                            @csrf
                            <input type="hidden" name="pending_key" value="{{ $pendingKey }}">
                            <button type="submit" class="dc-button-secondary w-full border-emerald-300 text-emerald-900 sm:w-auto">
                                Sauvegarder
                            </button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="dc-button-secondary border-emerald-300 text-emerald-900">
                        Se connecter pour sauvegarder
                    </a>
                @endauth
            </div>
            <p x-show="copied" x-cloak class="mt-2 text-sm font-bold text-emerald-700">Résumé copié.</p>
        </div>
    </div>
</section>
