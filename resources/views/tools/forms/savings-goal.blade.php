@php($v = fn ($key, $default = '') => old($key, data_get($inputs, $key, $default)))

<form method="POST" action="{{ route($tool['calculate_route']) }}" class="mt-5 space-y-5" x-data="{
    fill(values) {
        Object.entries(values).forEach(([key, value]) => {
            if (this.$root.elements[key]) this.$root.elements[key].value = value;
        });
    },
    dateAfter(months) {
        const date = new Date();
        date.setMonth(date.getMonth() + months);
        return date.toISOString().slice(0, 10);
    },
    preset(type) {
        const map = {
            reserve: { goal_name: 'Réserve de sécurité', target_amount: 1000, current_savings: 100, target_date: this.dateAfter(10), monthly_capacity: 100, priority: 'forte', security_margin: 200 },
            holidays: { goal_name: 'Vacances', target_amount: 1200, current_savings: 200, target_date: this.dateAfter(8), monthly_capacity: 150, priority: 'moyenne', security_margin: 150 },
            equipment: { goal_name: 'Matériel', target_amount: 600, current_savings: 100, target_date: this.dateAfter(6), monthly_capacity: 90, priority: 'moyenne', security_margin: 100 }
        };
        this.fill(map[type]);
    }
}">
    @csrf

    <div class="rounded-md border border-emerald-200 bg-emerald-50 p-4">
        <p class="text-sm font-semibold text-emerald-900">Choisis un objectif proche du tien</p>
        <div class="mt-3 grid gap-2 sm:grid-cols-3">
            <button type="button" @click="preset('reserve')" class="rounded-md border border-emerald-300 bg-white px-3 py-2 text-left text-sm font-semibold text-emerald-900 hover:bg-emerald-100">
                Réserve
                <span class="block text-xs font-normal text-emerald-800">1 000 € en 10 mois</span>
            </button>
            <button type="button" @click="preset('holidays')" class="rounded-md border border-emerald-300 bg-white px-3 py-2 text-left text-sm font-semibold text-emerald-900 hover:bg-emerald-100">
                Vacances
                <span class="block text-xs font-normal text-emerald-800">1 200 € en 8 mois</span>
            </button>
            <button type="button" @click="preset('equipment')" class="rounded-md border border-emerald-300 bg-white px-3 py-2 text-left text-sm font-semibold text-emerald-900 hover:bg-emerald-100">
                Matériel
                <span class="block text-xs font-normal text-emerald-800">600 € en 6 mois</span>
            </button>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <label class="block sm:col-span-2">
            <span class="text-sm font-medium text-slate-700">Nom de l’objectif</span>
            <input name="goal_name" type="text" maxlength="100" required value="{{ $v('goal_name') }}" placeholder="Vacances, matériel, réserve..." class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
        </label>
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Montant cible</span>
            <input name="target_amount" type="number" min="1" step="0.01" required value="{{ $v('target_amount') }}" placeholder="Ex. 1200" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
        </label>
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Épargne déjà disponible</span>
            <input name="current_savings" type="number" min="0" step="0.01" required value="{{ $v('current_savings') }}" placeholder="0 si rien de côté" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
        </label>
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Date cible</span>
            <input name="target_date" type="date" required value="{{ $v('target_date', now()->addMonths(6)->format('Y-m-d')) }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            <div class="mt-2 flex flex-wrap gap-2">
                <button type="button" @click="fill({ target_date: dateAfter(3) })" class="rounded border border-slate-200 px-2 py-1 text-xs text-slate-700 hover:bg-slate-50">Dans 3 mois</button>
                <button type="button" @click="fill({ target_date: dateAfter(6) })" class="rounded border border-slate-200 px-2 py-1 text-xs text-slate-700 hover:bg-slate-50">Dans 6 mois</button>
                <button type="button" @click="fill({ target_date: dateAfter(12) })" class="rounded border border-slate-200 px-2 py-1 text-xs text-slate-700 hover:bg-slate-50">Dans 1 an</button>
            </div>
        </label>
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Capacité mensuelle estimée</span>
            <input name="monthly_capacity" type="number" min="0" step="0.01" required value="{{ $v('monthly_capacity') }}" placeholder="Ex. 100" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            <div class="mt-2 flex flex-wrap gap-2">
                <button type="button" @click="fill({ monthly_capacity: 50 })" class="rounded border border-slate-200 px-2 py-1 text-xs text-slate-700 hover:bg-slate-50">50 €/mois</button>
                <button type="button" @click="fill({ monthly_capacity: 100 })" class="rounded border border-slate-200 px-2 py-1 text-xs text-slate-700 hover:bg-slate-50">100 €/mois</button>
                <button type="button" @click="fill({ monthly_capacity: 200 })" class="rounded border border-slate-200 px-2 py-1 text-xs text-slate-700 hover:bg-slate-50">200 €/mois</button>
            </div>
        </label>
    </div>

    <details class="rounded-md border border-slate-200 bg-slate-50 p-4">
        <summary class="cursor-pointer text-sm font-semibold text-slate-800">Détails avancés</summary>
        <p class="mt-2 text-sm text-slate-600">Utile si tu veux vérifier que l’objectif ne mange pas toute ta marge.</p>
        <div class="mt-4 grid gap-4 sm:grid-cols-2">
            <label class="block">
                <span class="text-sm font-medium text-slate-700">Priorité</span>
                <select name="priority" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                    @foreach (['faible' => 'Faible', 'moyenne' => 'Moyenne', 'forte' => 'Forte'] as $value => $label)
                        <option value="{{ $value }}" @selected($v('priority', 'moyenne') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>
            <label class="block">
                <span class="text-sm font-medium text-slate-700">Reste à vivre actuel</span>
                <input name="current_living_balance" type="number" min="0" step="0.01" value="{{ $v('current_living_balance') }}" placeholder="Je ne sais pas" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            </label>
            <label class="block sm:col-span-2">
                <span class="text-sm font-medium text-slate-700">Marge de sécurité souhaitée</span>
                <input name="security_margin" type="number" min="0" step="0.01" value="{{ $v('security_margin') }}" placeholder="Je ne sais pas" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            </label>
        </div>
    </details>

    <button type="submit" class="w-full rounded-md bg-emerald-700 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-800 sm:w-auto">Calculer l’objectif</button>
</form>
