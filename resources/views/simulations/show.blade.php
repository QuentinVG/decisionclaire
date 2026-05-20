<x-app-layout :title="'Simulation sauvegardée - '.$simulation->title" meta-description="Consulte une simulation sauvegardée dans DécisionClaire.">
    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold text-emerald-700">{{ $tool['name'] ?? 'Simulation' }}</p>
                <h1 class="text-2xl font-bold text-slate-950">{{ $simulation->title }}</h1>
            </div>
            @if ($tool)
                <a href="{{ route($tool['route'], ['simulation' => $simulation->id]) }}" class="inline-flex items-center justify-center rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-100">
                    Relancer / modifier
                </a>
            @endif
        </div>

        @include('tools.partials.result', ['result' => $result, 'pendingKey' => null, 'savedSimulation' => $simulation])
    </div>
</x-app-layout>
