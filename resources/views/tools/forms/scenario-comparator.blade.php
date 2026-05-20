@php
    $v = fn ($key, $default = '') => old($key, data_get($inputs, $key, $default));
    $scenarioRows = old('scenarios', data_get($inputs, 'scenarios', [
        ['name' => 'Option économique', 'initial_cost' => 200, 'monthly_cost' => 0, 'duration' => 12, 'utility' => 'moyenne', 'flexibility' => 'forte', 'risk' => 'faible', 'savings_impact' => 200, 'comment' => 'Répond au besoin avec moins de coût'],
        ['name' => 'Option confort', 'initial_cost' => 450, 'monthly_cost' => 0, 'duration' => 12, 'utility' => 'forte', 'flexibility' => 'moyenne', 'risk' => 'moyen', 'savings_impact' => 450, 'comment' => 'Plus agréable, mais marge réduite'],
    ]));
@endphp

<form method="POST" action="{{ route($tool['calculate_route']) }}" class="mt-5 space-y-5" x-data="{
    template: @js($v('template', 'economique_vs_confort')),
    rows: @js($scenarioRows),
    templates: {
        acheter_maintenant_vs_attendre: [
            {name: 'Acheter maintenant', initial_cost: 650, monthly_cost: 0, duration: 12, utility: 'forte', flexibility: 'faible', risk: 'moyen', savings_impact: 650, comment: 'Décision rapide, moins de marge après achat'},
            {name: 'Attendre 2 mois', initial_cost: 650, monthly_cost: 0, duration: 12, utility: 'forte', flexibility: 'forte', risk: 'faible', savings_impact: 450, comment: 'Plus de temps pour comparer et épargner'},
        ],
        neuf_vs_occasion: [
            {name: 'Acheter neuf', initial_cost: 800, monthly_cost: 0, duration: 24, utility: 'forte', flexibility: 'moyenne', risk: 'moyen', savings_impact: 800, comment: 'Garantie plus simple, coût plus élevé'},
            {name: 'Acheter occasion', initial_cost: 480, monthly_cost: 0, duration: 18, utility: 'moyenne', flexibility: 'moyenne', risk: 'moyen', savings_impact: 480, comment: 'Moins cher, vérifier l’état avant achat'},
        ],
        comptant_vs_plusieurs_fois: [
            {name: 'Payer comptant', initial_cost: 900, monthly_cost: 0, duration: 12, utility: 'forte', flexibility: 'faible', risk: 'moyen', savings_impact: 900, comment: 'Pas de mensualité, épargne plus touchée'},
            {name: 'Payer en plusieurs fois', initial_cost: 0, monthly_cost: 150, duration: 6, utility: 'forte', flexibility: 'moyenne', risk: 'moyen', savings_impact: 150, comment: 'Impact étalé, surveiller les mensualités'},
        ],
        garder_vs_remplacer: [
            {name: 'Garder l’existant', initial_cost: 0, monthly_cost: 25, duration: 12, utility: 'moyenne', flexibility: 'forte', risk: 'faible', savings_impact: 0, comment: 'Moins d’engagement immédiat'},
            {name: 'Remplacer maintenant', initial_cost: 700, monthly_cost: 0, duration: 12, utility: 'forte', flexibility: 'moyenne', risk: 'moyen', savings_impact: 700, comment: 'Plus confortable, coût immédiat'},
        ],
        mensuel_vs_annuel: [
            {name: 'Abonnement mensuel', initial_cost: 0, monthly_cost: 15, duration: 12, utility: 'moyenne', flexibility: 'forte', risk: 'faible', savings_impact: 15, comment: 'Plus facile à arrêter'},
            {name: 'Abonnement annuel', initial_cost: 120, monthly_cost: 0, duration: 12, utility: 'moyenne', flexibility: 'faible', risk: 'moyen', savings_impact: 120, comment: 'Moins cher si usage régulier'},
        ],
        economique_vs_confort: [
            {name: 'Option économique', initial_cost: 200, monthly_cost: 0, duration: 12, utility: 'moyenne', flexibility: 'forte', risk: 'faible', savings_impact: 200, comment: 'Répond au besoin avec moins de coût'},
            {name: 'Option confort', initial_cost: 450, monthly_cost: 0, duration: 12, utility: 'forte', flexibility: 'moyenne', risk: 'moyen', savings_impact: 450, comment: 'Plus agréable, mais marge réduite'},
        ],
    },
    loadTemplate(type) {
        this.template = type;
        this.rows = this.templates[type].map((row) => ({ ...row }));
    },
}">
    @csrf

    <div class="rounded-md border border-emerald-200 bg-emerald-50 p-4">
        <p class="text-sm font-semibold text-emerald-950">Choisis la comparaison la plus proche</p>
        <p class="mt-1 text-sm text-emerald-900">Les scénarios sont pré-remplis avec des valeurs prudentes. Tu peux ensuite modifier seulement ce que tu connais.</p>
        <div class="mt-3 flex flex-wrap gap-2">
            <button type="button" @click="loadTemplate('acheter_maintenant_vs_attendre')" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-emerald-900 ring-1 ring-emerald-200 hover:bg-emerald-100">Maintenant ou attendre</button>
            <button type="button" @click="loadTemplate('neuf_vs_occasion')" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-emerald-900 ring-1 ring-emerald-200 hover:bg-emerald-100">Neuf ou occasion</button>
            <button type="button" @click="loadTemplate('comptant_vs_plusieurs_fois')" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-emerald-900 ring-1 ring-emerald-200 hover:bg-emerald-100">Comptant ou plusieurs fois</button>
            <button type="button" @click="loadTemplate('mensuel_vs_annuel')" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-emerald-900 ring-1 ring-emerald-200 hover:bg-emerald-100">Mensuel ou annuel</button>
        </div>
    </div>

    <label class="block">
        <span class="text-sm font-medium text-slate-700">Type de comparaison</span>
        <select name="template" x-model="template" @change="loadTemplate(template)" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            @foreach ([
                'acheter_maintenant_vs_attendre' => 'Acheter maintenant vs attendre',
                'neuf_vs_occasion' => 'Neuf vs occasion',
                'comptant_vs_plusieurs_fois' => 'Payer comptant vs payer en plusieurs fois',
                'garder_vs_remplacer' => 'Garder l’existant vs remplacer',
                'mensuel_vs_annuel' => 'Abonnement mensuel vs abonnement annuel',
                'economique_vs_confort' => 'Option économique vs option confort',
            ] as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>
        <span class="mt-1 block text-xs text-slate-500">Ce choix sert à charger des exemples modifiables, pas à imposer une conclusion.</span>
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
                        <span class="text-sm font-medium text-slate-700">Coût au départ</span>
                        <input type="number" min="0" step="0.01" x-model="row.initial_cost" :name="`scenarios[${index}][initial_cost]`" required class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700">Coût par mois</span>
                        <input type="number" min="0" step="0.01" x-model="row.monthly_cost" :name="`scenarios[${index}][monthly_cost]`" required class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700">Durée comparée</span>
                        <input type="number" min="1" max="240" x-model="row.duration" :name="`scenarios[${index}][duration]`" required class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                        <span class="mt-1 block text-xs text-slate-500">En mois. Mets 12 si tu ne sais pas.</span>
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700">Utilité</span>
                        <select x-model="row.utility" :name="`scenarios[${index}][utility]`" required class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="faible">Faible : confort limité</option>
                            <option value="moyenne">Moyenne : utile mais pas indispensable</option>
                            <option value="forte">Forte : vrai besoin</option>
                        </select>
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700">Flexibilité</span>
                        <select x-model="row.flexibility" :name="`scenarios[${index}][flexibility]`" required class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="faible">Faible : difficile à arrêter</option>
                            <option value="moyenne">Moyenne : ajustable</option>
                            <option value="forte">Forte : facile à reporter ou arrêter</option>
                        </select>
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700">Risque</span>
                        <select x-model="row.risk" :name="`scenarios[${index}][risk]`" required class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="faible">Faible : marge préservée</option>
                            <option value="moyen">Moyen : à surveiller</option>
                            <option value="élevé">Élevé : marge trop réduite</option>
                        </select>
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700">Épargne touchée</span>
                        <input type="number" min="0" step="0.01" x-model="row.savings_impact" :name="`scenarios[${index}][savings_impact]`" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                        <span class="mt-1 block text-xs text-slate-500">Laisse 0 si tu n'utilises pas ton épargne.</span>
                    </label>
                    <label class="block sm:col-span-2">
                        <span class="text-sm font-medium text-slate-700">Commentaire simple</span>
                        <input type="text" maxlength="240" x-model="row.comment" :name="`scenarios[${index}][comment]`" placeholder="Ex. plus prudent, moins cher, moins flexible..." class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
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
