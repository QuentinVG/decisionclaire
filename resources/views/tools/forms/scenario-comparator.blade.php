@php
    $v = fn ($key, $default = '') => old($key, data_get($inputs, $key, $default));
    $scenarioRows = old('scenarios', data_get($inputs, 'scenarios', [
        ['name' => 'Option économique', 'initial_cost' => 200, 'monthly_cost' => 0, 'duration' => 12, 'utility' => 'moyenne', 'flexibility' => 'forte', 'risk' => 'faible', 'savings_impact' => 200, 'comment' => ''],
        ['name' => 'Option confort', 'initial_cost' => 450, 'monthly_cost' => 0, 'duration' => 12, 'utility' => 'forte', 'flexibility' => 'moyenne', 'risk' => 'moyen', 'savings_impact' => 450, 'comment' => ''],
    ]));
@endphp

<form method="POST" action="{{ route($tool['calculate_route']) }}" class="mt-5 space-y-5" x-data="{ rows: @js($scenarioRows) }">
    @csrf
    <label class="block">
        <span class="text-sm font-medium text-slate-700">Template de comparaison</span>
        <select name="template" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            @foreach ([
                'acheter_maintenant_vs_attendre' => 'Acheter maintenant vs attendre',
                'neuf_vs_occasion' => 'Neuf vs occasion',
                'comptant_vs_plusieurs_fois' => 'Payer comptant vs payer en plusieurs fois',
                'garder_vs_remplacer' => 'Garder l’existant vs remplacer',
                'mensuel_vs_annuel' => 'Abonnement mensuel vs abonnement annuel',
                'economique_vs_confort' => 'Option économique vs option confort',
            ] as $value => $label)
                <option value="{{ $value }}" @selected($v('template', 'economique_vs_confort') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </label>

    <div class="space-y-4">
        <template x-for="(row, index) in rows" :key="index">
            <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                <div class="flex items-center justify-between gap-3">
                    <h3 class="font-semibold text-slate-950">Scénario <span x-text="index + 1"></span></h3>
                    <button type="button" x-show="rows.length > 2" @click="rows.splice(index, 1)" class="text-sm font-semibold text-red-700">Supprimer</button>
                </div>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700">Nom</span>
                        <input type="text" maxlength="80" x-model="row.name" :name="`scenarios[${index}][name]`" required class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700">Coût initial</span>
                        <input type="number" min="0" step="0.01" x-model="row.initial_cost" :name="`scenarios[${index}][initial_cost]`" required class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700">Coût mensuel</span>
                        <input type="number" min="0" step="0.01" x-model="row.monthly_cost" :name="`scenarios[${index}][monthly_cost]`" required class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700">Durée en mois</span>
                        <input type="number" min="1" max="240" x-model="row.duration" :name="`scenarios[${index}][duration]`" required class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700">Utilité</span>
                        <select x-model="row.utility" :name="`scenarios[${index}][utility]`" required class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="faible">Faible</option>
                            <option value="moyenne">Moyenne</option>
                            <option value="forte">Forte</option>
                        </select>
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700">Flexibilité</span>
                        <select x-model="row.flexibility" :name="`scenarios[${index}][flexibility]`" required class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="faible">Faible</option>
                            <option value="moyenne">Moyenne</option>
                            <option value="forte">Forte</option>
                        </select>
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700">Risque</span>
                        <select x-model="row.risk" :name="`scenarios[${index}][risk]`" required class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="faible">Faible</option>
                            <option value="moyen">Moyen</option>
                            <option value="élevé">Élevé</option>
                        </select>
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700">Impact sur épargne</span>
                        <input type="number" min="0" step="0.01" x-model="row.savings_impact" :name="`scenarios[${index}][savings_impact]`" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                    </label>
                    <label class="block sm:col-span-2">
                        <span class="text-sm font-medium text-slate-700">Commentaire</span>
                        <input type="text" maxlength="240" x-model="row.comment" :name="`scenarios[${index}][comment]`" placeholder="Optionnel" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                    </label>
                </div>
            </div>
        </template>
    </div>

    <div class="flex flex-col gap-3 sm:flex-row">
        <button type="button" @click="if (rows.length < 4) rows.push({name: '', initial_cost: 0, monthly_cost: 0, duration: 12, utility: 'moyenne', flexibility: 'moyenne', risk: 'moyen', savings_impact: 0, comment: ''})" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-100">
            Ajouter un scénario
        </button>
        <button type="submit" class="rounded-md bg-emerald-700 px-5 py-2 text-sm font-semibold text-white hover:bg-emerald-800">
            Comparer les scénarios
        </button>
    </div>
</form>
