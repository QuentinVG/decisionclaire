@php($v = fn ($key, $default = '') => old($key, data_get($inputs, $key, $default)))

<form method="POST" action="{{ route($tool['calculate_route']) }}" class="mt-5 space-y-5">
    @csrf
    <div class="grid gap-4 sm:grid-cols-2">
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Revenus mensuels nets</span>
            <input name="monthly_income" type="number" min="0" step="0.01" required value="{{ $v('monthly_income') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
        </label>
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Charges fixes totales</span>
            <input name="fixed_charges" type="number" min="0" step="0.01" required value="{{ $v('fixed_charges') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
        </label>
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Courses</span>
            <input name="groceries" type="number" min="0" step="0.01" required value="{{ $v('groceries') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
        </label>
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Transport</span>
            <input name="transport" type="number" min="0" step="0.01" required value="{{ $v('transport') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
        </label>
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Abonnements</span>
            <input name="subscriptions" type="number" min="0" step="0.01" required value="{{ $v('subscriptions') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
        </label>
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Crédits / dettes personnels</span>
            <input name="debts" type="number" min="0" step="0.01" required value="{{ $v('debts') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
        </label>
        <label class="block sm:col-span-2">
            <span class="text-sm font-medium text-slate-700">Épargne souhaitée</span>
            <input name="planned_savings" type="number" min="0" step="0.01" required value="{{ $v('planned_savings') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
        </label>
    </div>

    <details class="rounded-md border border-slate-200 bg-slate-50 p-4">
        <summary class="cursor-pointer text-sm font-semibold text-slate-800">Mode avancé et “je ne sais pas”</summary>
        <p class="mt-2 text-sm text-slate-600">Si tu ne sais pas, laisse vide : DécisionClaire applique une valeur prudente de 0.</p>
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
