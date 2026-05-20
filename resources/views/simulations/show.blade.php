<x-app-layout :title="'Simulation sauvegardée - '.$simulation->title" meta-description="Consulte une simulation sauvegardée dans DécisionClaire.">
    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 dc-surface p-5">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="dc-badge">{{ $tool['name'] ?? 'Simulation' }}</p>
                    <h1 class="mt-3 text-3xl font-extrabold text-slate-950">{{ $simulation->title }}</h1>
                </div>
                @if ($tool)
                    <a href="{{ route($tool['route'], ['simulation' => $simulation->id]) }}" class="dc-button-secondary">
                        Relancer / modifier
                    </a>
                @endif
            </div>
        </div>

        @include('tools.partials.result', ['result' => $result, 'pendingKey' => null, 'savedSimulation' => $simulation])
    </div>
</x-app-layout>
