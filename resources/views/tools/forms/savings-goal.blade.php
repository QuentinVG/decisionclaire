@php($v = fn ($key, $default = '') => old($key, data_get($inputs, $key, $default)))

<form method="POST" action="{{ route($tool['calculate_route']) }}" class="mt-5 space-y-5">
    @csrf
    <div class="grid gap-4 sm:grid-cols-2">
        <label class="block sm:col-span-2">
            <span class="text-sm font-medium text-slate-700">Nom de l’objectif</span>
            <input name="goal_name" type="text" maxlength="100" required value="{{ $v('goal_name') }}" placeholder="Vacances, matériel, réserve..." class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
        </label>
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Montant cible</span>
            <input name="target_amount" type="number" min="1" step="0.01" required value="{{ $v('target_amount') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
        </label>
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Épargne déjà disponible</span>
            <input name="current_savings" type="number" min="0" step="0.01" required value="{{ $v('current_savings') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
        </label>
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Date cible</span>
            <input name="target_date" type="date" required value="{{ $v('target_date', now()->addMonths(6)->format('Y-m-d')) }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
        </label>
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Capacité mensuelle estimée</span>
            <input name="monthly_capacity" type="number" min="0" step="0.01" required value="{{ $v('monthly_capacity') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
        </label>
    </div>

    <details class="rounded-md border border-slate-200 bg-slate-50 p-4">
        <summary class="cursor-pointer text-sm font-semibold text-slate-800">Mode avancé</summary>
        <p class="mt-2 text-sm text-slate-600">Si tu ne sais pas, laisse vide : le calcul utilisera surtout la capacité mensuelle déclarée.</p>
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
