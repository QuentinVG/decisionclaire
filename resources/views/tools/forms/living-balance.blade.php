@php($v = fn ($key, $default = '') => old($key, data_get($inputs, $key, $default)))

<form method="POST" action="{{ route($tool['calculate_route']) }}" class="mt-5 space-y-5" x-data="{
    fill(values) {
        Object.entries(values).forEach(([key, value]) => {
            if (this.$root.elements[key]) this.$root.elements[key].value = value;
        });
    },
    fillFromProfile(profile) {
        const income = Number(this.$root.elements.monthly_income.value) || profile.income;
        this.fill({
            monthly_income: income,
            fixed_charges: Math.round(income * profile.fixedRate),
            groceries: profile.groceries,
            transport: profile.transport,
            subscriptions: profile.subscriptions,
            debts: profile.debts,
            planned_savings: Math.round(income * profile.savingsRate),
            security_margin: profile.security_margin
        });
    }
}">
    @csrf

    <div class="rounded-md border border-emerald-200 bg-emerald-50 p-4">
        <p class="text-sm font-semibold text-emerald-900">Je ne sais pas par où commencer</p>
        <p class="mt-1 text-sm text-emerald-800">Entre ton revenu si tu le connais, puis choisis un profil. Tu pourras ajuster les montants ensuite.</p>
        <div class="mt-3 grid gap-2 sm:grid-cols-3">
            <button type="button" @click="fillFromProfile({ income: 1400, fixedRate: 0.38, groceries: 260, transport: 60, subscriptions: 35, debts: 0, savingsRate: 0.04, security_margin: 80 })" class="rounded-md border border-emerald-300 bg-white px-3 py-2 text-left text-sm font-semibold text-emerald-900 hover:bg-emerald-100">
                Budget serré
                <span class="block text-xs font-normal text-emerald-800">Peu de marge, dépenses prudentes</span>
            </button>
            <button type="button" @click="fillFromProfile({ income: 2100, fixedRate: 0.42, groceries: 320, transport: 90, subscriptions: 55, debts: 80, savingsRate: 0.08, security_margin: 150 })" class="rounded-md border border-emerald-300 bg-white px-3 py-2 text-left text-sm font-semibold text-emerald-900 hover:bg-emerald-100">
                Budget courant
                <span class="block text-xs font-normal text-emerald-800">Montants moyens à corriger</span>
            </button>
            <button type="button" @click="fillFromProfile({ income: 3200, fixedRate: 0.36, groceries: 520, transport: 160, subscriptions: 80, debts: 120, savingsRate: 0.12, security_margin: 250 })" class="rounded-md border border-emerald-300 bg-white px-3 py-2 text-left text-sm font-semibold text-emerald-900 hover:bg-emerald-100">
                Budget foyer
                <span class="block text-xs font-normal text-emerald-800">Plus de dépenses récurrentes</span>
            </button>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Revenus mensuels nets</span>
            <input name="monthly_income" type="number" min="0" step="0.01" required value="{{ $v('monthly_income') }}" placeholder="Ex. 2100" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            <span class="mt-1 block text-xs text-slate-500">Salaire net, aides régulières si elles sont stables.</span>
        </label>
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Charges fixes connues</span>
            <input name="fixed_charges" type="number" min="0" step="0.01" required value="{{ $v('fixed_charges') }}" placeholder="Ex. 850" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            <span class="mt-1 block text-xs text-slate-500">Factures, assurances, téléphone, remboursements récurrents. Si tu hésites, utilise un profil.</span>
        </label>
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Courses</span>
            <input name="groceries" type="number" min="0" step="0.01" required value="{{ $v('groceries') }}" placeholder="Ex. 320" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            <div class="mt-2 flex flex-wrap gap-2">
                <button type="button" @click="fill({ groceries: 220 })" class="rounded border border-slate-200 px-2 py-1 text-xs text-slate-700 hover:bg-slate-50">Solo léger</button>
                <button type="button" @click="fill({ groceries: 320 })" class="rounded border border-slate-200 px-2 py-1 text-xs text-slate-700 hover:bg-slate-50">Solo courant</button>
                <button type="button" @click="fill({ groceries: 520 })" class="rounded border border-slate-200 px-2 py-1 text-xs text-slate-700 hover:bg-slate-50">Foyer</button>
            </div>
        </label>
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Transport</span>
            <input name="transport" type="number" min="0" step="0.01" required value="{{ $v('transport') }}" placeholder="Ex. 90" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            <div class="mt-2 flex flex-wrap gap-2">
                <button type="button" @click="fill({ transport: 0 })" class="rounded border border-slate-200 px-2 py-1 text-xs text-slate-700 hover:bg-slate-50">Aucun</button>
                <button type="button" @click="fill({ transport: 55 })" class="rounded border border-slate-200 px-2 py-1 text-xs text-slate-700 hover:bg-slate-50">Transport simple</button>
                <button type="button" @click="fill({ transport: 160 })" class="rounded border border-slate-200 px-2 py-1 text-xs text-slate-700 hover:bg-slate-50">Déplacements élevés</button>
            </div>
        </label>
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Abonnements</span>
            <input name="subscriptions" type="number" min="0" step="0.01" required value="{{ $v('subscriptions') }}" placeholder="Ex. 55" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            <div class="mt-2 flex flex-wrap gap-2">
                <button type="button" @click="fill({ subscriptions: 25 })" class="rounded border border-slate-200 px-2 py-1 text-xs text-slate-700 hover:bg-slate-50">Peu</button>
                <button type="button" @click="fill({ subscriptions: 55 })" class="rounded border border-slate-200 px-2 py-1 text-xs text-slate-700 hover:bg-slate-50">Classique</button>
                <button type="button" @click="fill({ subscriptions: 100 })" class="rounded border border-slate-200 px-2 py-1 text-xs text-slate-700 hover:bg-slate-50">Beaucoup</button>
            </div>
        </label>
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Crédits / dettes personnels</span>
            <input name="debts" type="number" min="0" step="0.01" required value="{{ $v('debts') }}" placeholder="0 si aucun" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
        </label>
        <label class="block sm:col-span-2">
            <span class="text-sm font-medium text-slate-700">Épargne souhaitée</span>
            <input name="planned_savings" type="number" min="0" step="0.01" required value="{{ $v('planned_savings') }}" placeholder="Ex. 150" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            <div class="mt-2 flex flex-wrap gap-2">
                <button type="button" @click="fill({ planned_savings: 0 })" class="rounded border border-slate-200 px-2 py-1 text-xs text-slate-700 hover:bg-slate-50">Je ne sais pas : 0</button>
                <button type="button" @click="fill({ planned_savings: 50 })" class="rounded border border-slate-200 px-2 py-1 text-xs text-slate-700 hover:bg-slate-50">Petite épargne</button>
                <button type="button" @click="fill({ planned_savings: 150 })" class="rounded border border-slate-200 px-2 py-1 text-xs text-slate-700 hover:bg-slate-50">Épargne régulière</button>
            </div>
        </label>
    </div>

    <details class="rounded-md border border-slate-200 bg-slate-50 p-4">
        <summary class="cursor-pointer text-sm font-semibold text-slate-800">Détails avancés, seulement si tu les connais</summary>
        <p class="mt-2 text-sm text-slate-600">Laisse vide si tu ne sais pas. Ces champs servent à rendre le résultat plus précis, pas à remplir un dossier.</p>
        <div class="mt-4 grid gap-4 sm:grid-cols-2">
            @foreach ([
                'aids' => 'Aides',
                'other_income' => 'Autres revenus',
                'energy' => 'Énergie',
                'insurance' => 'Assurances',
                'phone_internet' => 'Téléphone / internet',
                'family_expenses' => 'Dépenses enfants / famille',
                'other_charges' => 'Autres charges',
                'security_margin' => 'Marge de sécurité souhaitée',
            ] as $name => $label)
                <label class="block">
                    <span class="text-sm font-medium text-slate-700">{{ $label }}</span>
                    <input name="{{ $name }}" type="number" min="0" step="0.01" value="{{ $v($name) }}" placeholder="Je ne sais pas" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                </label>
            @endforeach
        </div>
    </details>

    <button type="submit" class="w-full rounded-md bg-emerald-700 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-800 sm:w-auto">Calculer mon reste à vivre</button>
</form>
