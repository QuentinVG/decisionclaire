<x-app-layout title="Dashboard DécisionClaire" meta-description="Historique simple des simulations sauvegardées dans DécisionClaire.">
    <x-slot name="header">
        <div>
            <p class="dc-badge">Espace personnel</p>
            <h1 class="mt-3 text-3xl font-extrabold text-slate-950">Dashboard</h1>
            <p class="mt-2 text-sm text-slate-600">Un historique clair, sans statistiques inutiles.</p>
        </div>
    </x-slot>

    <div class="dc-section py-8">
        @if (session('status'))
            <div class="mb-6 rounded-md border border-emerald-200 bg-emerald-50 p-3 text-sm font-bold text-emerald-800">{{ session('status') }}</div>
        @endif

        <section class="grid gap-4 md:grid-cols-3">
            @foreach ($tools as $tool)
                <a href="{{ route($tool['route']) }}" class="dc-card dc-card-hover p-4">
                    <p class="text-xs font-bold text-emerald-700">{{ $tool['badge'] }}</p>
                    <h2 class="mt-2 font-extrabold text-slate-950">{{ $tool['name'] }}</h2>
                </a>
            @endforeach
        </section>

        <section class="dc-surface mt-8 p-5 sm:p-6">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="dc-badge">Historique</p>
                    <h2 class="mt-3 text-2xl font-extrabold text-slate-950">Simulations sauvegardées</h2>
                </div>
                <p class="text-sm font-semibold text-slate-500">{{ $simulations->count() }} simulation(s)</p>
            </div>
            @if ($simulations->isEmpty())
                <p class="mt-5 rounded-md border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">Aucune simulation sauvegardée pour le moment.</p>
            @else
                <div class="mt-5 space-y-3">
                    @foreach ($simulations as $simulation)
                        @php($tool = \App\Support\ToolCatalog::get($simulation->tool_key))
                        <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                                <div>
                                    <p class="text-xs font-bold text-emerald-700">{{ $tool['name'] ?? $simulation->tool_key }}</p>
                                    <h3 class="mt-1 font-extrabold text-slate-950">{{ $simulation->title }}</h3>
                                    <p class="mt-1 text-sm text-slate-600">{{ $simulation->created_at->format('d/m/Y H:i') }} - {{ data_get($simulation->result_data, 'verdict') }}</p>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('simulations.show', $simulation) }}" class="dc-button-secondary px-3 py-2">Voir</a>
                                    @if ($tool)
                                        <a href="{{ route($tool['route'], ['simulation' => $simulation->id]) }}" class="dc-button-secondary px-3 py-2">Relancer</a>
                                    @endif
                                    <a href="{{ route('simulations.export-pdf', $simulation) }}" class="dc-button-secondary border-emerald-300 px-3 py-2 text-emerald-900">PDF</a>
                                    <form method="POST" action="{{ route('simulations.duplicate', $simulation) }}">
                                        @csrf
                                        <button type="submit" class="dc-button-secondary px-3 py-2">Dupliquer</button>
                                    </form>
                                    <form method="POST" action="{{ route('simulations.destroy', $simulation) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center rounded-md border border-red-200 bg-white px-3 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-50 focus:outline-none focus:ring-4 focus:ring-red-100">Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
</x-app-layout>
