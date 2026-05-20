<x-app-layout title="Dashboard DécisionClaire" meta-description="Historique simple des simulations sauvegardées dans DécisionClaire.">
    <x-slot name="header">
        <div>
            <h1 class="text-2xl font-bold text-slate-950">Dashboard</h1>
            <p class="mt-1 text-sm text-slate-600">Un historique clair, sans statistiques inutiles.</p>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-6 rounded-md border border-emerald-200 bg-emerald-50 p-3 text-sm font-medium text-emerald-800">{{ session('status') }}</div>
        @endif

        <section class="grid gap-4 md:grid-cols-3">
            @foreach ($tools as $tool)
                <a href="{{ route($tool['route']) }}" class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm hover:border-emerald-300">
                    <p class="text-xs font-semibold uppercase text-emerald-700">{{ $tool['badge'] }}</p>
                    <h2 class="mt-1 font-semibold text-slate-950">{{ $tool['name'] }}</h2>
                </a>
            @endforeach
        </section>

        <section class="mt-8 rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="text-xl font-semibold text-slate-950">Simulations sauvegardées</h2>
            @if ($simulations->isEmpty())
                <p class="mt-4 text-sm text-slate-600">Aucune simulation sauvegardée pour le moment.</p>
            @else
                <div class="mt-4 divide-y divide-slate-200">
                    @foreach ($simulations as $simulation)
                        @php($tool = \App\Support\ToolCatalog::get($simulation->tool_key))
                        <div class="py-4">
                            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                                <div>
                                    <p class="text-xs font-semibold uppercase text-emerald-700">{{ $tool['name'] ?? $simulation->tool_key }}</p>
                                    <h3 class="font-semibold text-slate-950">{{ $simulation->title }}</h3>
                                    <p class="mt-1 text-sm text-slate-600">{{ $simulation->created_at->format('d/m/Y H:i') }} - {{ data_get($simulation->result_data, 'verdict') }}</p>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('simulations.show', $simulation) }}" class="rounded-md border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-100">Voir</a>
                                    @if ($tool)
                                        <a href="{{ route($tool['route'], ['simulation' => $simulation->id]) }}" class="rounded-md border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-100">Relancer</a>
                                    @endif
                                    <a href="{{ route('simulations.export-pdf', $simulation) }}" class="rounded-md border border-emerald-700 px-3 py-2 text-sm font-semibold text-emerald-800 hover:bg-emerald-50">PDF</a>
                                    <form method="POST" action="{{ route('simulations.duplicate', $simulation) }}">
                                        @csrf
                                        <button type="submit" class="rounded-md border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-100">Dupliquer</button>
                                    </form>
                                    <form method="POST" action="{{ route('simulations.destroy', $simulation) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-md border border-red-300 px-3 py-2 text-sm font-semibold text-red-700 hover:bg-red-50">Supprimer</button>
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
