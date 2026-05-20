@php
    $riskClass = match ($result['risk_level'] ?? '') {
        'risqué' => 'bg-red-50 text-red-800 border-red-200',
        'limite' => 'bg-amber-50 text-amber-900 border-amber-200',
        'modéré' => 'bg-sky-50 text-sky-800 border-sky-200',
        default => 'bg-emerald-50 text-emerald-800 border-emerald-200',
    };
@endphp

<section id="resultat" class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <p class="text-sm font-semibold text-emerald-700">Résultat indicatif</p>
            <h2 class="mt-1 text-2xl font-bold text-slate-950">{{ $result['verdict'] }}</h2>
        </div>
        <span class="inline-flex w-fit rounded-full border px-3 py-1 text-sm font-semibold {{ $riskClass }}">
            Risque : {{ $result['risk_level'] }}
        </span>
    </div>

    <div class="mt-5 rounded-md bg-slate-950 p-5 text-white">
        <p class="text-sm text-slate-300">{{ $result['primary_label'] }}</p>
        <p class="mt-1 text-3xl font-bold">{{ $result['primary_value'] }}</p>
    </div>

    <div class="mt-5 grid gap-3 sm:grid-cols-2">
        <div class="rounded-md border border-slate-200 p-4">
            <p class="text-sm text-slate-500">Confiance du résultat</p>
            <p class="mt-1 text-xl font-semibold text-slate-950">{{ $result['confidence_score'] }}/100</p>
        </div>
        <div class="rounded-md border border-slate-200 p-4">
            <p class="text-sm text-slate-500">Mention</p>
            <p class="mt-1 text-sm font-medium text-slate-800">{{ $result['notice'] }}</p>
        </div>
    </div>

    <p class="mt-5 leading-7 text-slate-700">{{ $result['explanation'] }}</p>

    @if (! empty($result['metrics']))
        <div class="mt-5 grid gap-3 sm:grid-cols-2">
            @foreach ($result['metrics'] as $metric)
                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <p class="text-sm text-slate-500">{{ $metric['label'] }}</p>
                    <p class="mt-1 font-semibold text-slate-950">{{ $metric['value'] }}</p>
                    @isset($metric['help'])
                        <p class="mt-1 text-xs text-slate-500">{{ $metric['help'] }}</p>
                    @endisset
                </div>
            @endforeach
        </div>
    @endif

    <div class="mt-5">
        <h3 class="font-semibold text-slate-950">Recommandations concrètes</h3>
        <ul class="mt-3 space-y-2">
            @foreach ($result['recommendations'] as $recommendation)
                <li class="rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700">{{ $recommendation }}</li>
            @endforeach
        </ul>
    </div>

    <div class="mt-5" x-data="{ copied: false }">
        <label for="summary-copy" class="font-semibold text-slate-950">Résumé à copier</label>
        <textarea id="summary-copy" readonly rows="6" class="mt-3 w-full rounded-md border-slate-300 text-sm text-slate-800 focus:border-emerald-500 focus:ring-emerald-500">{{ $result['summary'] }}</textarea>
        <div class="mt-3 flex flex-col gap-2 sm:flex-row">
            <button type="button" @click="navigator.clipboard.writeText(document.getElementById('summary-copy').value); copied = true" class="inline-flex items-center justify-center rounded-md bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                Copier le résultat
            </button>
            <a href="#formulaire" class="inline-flex items-center justify-center rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-100">
                Modifier mes réponses
            </a>
            @auth
                @if ($savedSimulation)
                    <a href="{{ route('simulations.export-pdf', $savedSimulation) }}" class="inline-flex items-center justify-center rounded-md border border-emerald-700 px-4 py-2 text-sm font-semibold text-emerald-800 hover:bg-emerald-50">
                        Exporter PDF
                    </a>
                @elseif ($pendingKey)
                    <form method="POST" action="{{ route('simulations.store') }}">
                        @csrf
                        <input type="hidden" name="pending_key" value="{{ $pendingKey }}">
                        <button type="submit" class="w-full rounded-md border border-emerald-700 px-4 py-2 text-sm font-semibold text-emerald-800 hover:bg-emerald-50 sm:w-auto">
                            Sauvegarder
                        </button>
                    </form>
                @endif
            @else
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-md border border-emerald-700 px-4 py-2 text-sm font-semibold text-emerald-800 hover:bg-emerald-50">
                    Se connecter pour sauvegarder
                </a>
            @endauth
        </div>
        <p x-show="copied" x-cloak class="mt-2 text-sm font-medium text-emerald-700">Résumé copié.</p>
    </div>
</section>
