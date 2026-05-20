@php($v = fn ($key, $default = '') => old($key, data_get($inputs, $key, $default)))

<form method="POST" action="{{ route($tool['calculate_route']) }}" class="mt-5 space-y-5">
    @csrf
    <div class="grid gap-4 sm:grid-cols-2">
        <label class="block sm:col-span-2">
            <span class="text-sm font-medium text-slate-700">Nom de l’achat</span>
            <input name="purchase_name" type="text" maxlength="100" required value="{{ $v('purchase_name') }}" placeholder="Téléphone, PC, voyage..." class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
        </label>
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Prix</span>
            <input name="price" type="number" min="0.01" step="0.01" required value="{{ $v('price') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
        </label>
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Reste à vivre mensuel</span>
            <input name="available_monthly" type="number" min="0" step="0.01" required value="{{ $v('available_monthly') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
        </label>
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Épargne disponible</span>
            <input name="available_savings" type="number" min="0" step="0.01" required value="{{ $v('available_savings') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
        </label>
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Urgence</span>
            <select name="urgency" required class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                @foreach (['faible' => 'Faible', 'moyenne' => 'Moyenne', 'forte' => 'Forte'] as $value => $label)
                    <option value="{{ $value }}" @selected($v('urgency', 'moyenne') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </label>
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Utilité</span>
            <select name="utility" required class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                @foreach (['faible' => 'Faible', 'moyenne' => 'Moyenne', 'forte' => 'Forte'] as $value => $label)
                    <option value="{{ $value }}" @selected($v('utility', 'moyenne') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </label>
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Fréquence d’usage</span>
            <select name="usage_frequency" required class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                @foreach (['rare' => 'Rare', 'mensuelle' => 'Mensuelle', 'hebdo' => 'Hebdo', 'quotidienne' => 'Quotidienne'] as $value => $label)
                    <option value="{{ $value }}" @selected($v('usage_frequency', 'mensuelle') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </label>
    </div>

    <details class="rounded-md border border-slate-200 bg-slate-50 p-4">
        <summary class="cursor-pointer text-sm font-semibold text-slate-800">Mode avancé</summary>
        <p class="mt-2 text-sm text-slate-600">Si tu ne sais pas, garde les valeurs par défaut : 12 mois d’usage et achat maintenant.</p>
        <div class="mt-4 grid gap-4 sm:grid-cols-2">
            <label class="block">
                <span class="text-sm font-medium text-slate-700">Épargne minimale à garder</span>
                <input name="minimum_savings" type="number" min="0" step="0.01" value="{{ $v('minimum_savings', 0) }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            </label>
            <label class="block">
                <span class="text-sm font-medium text-slate-700">Durée d’usage prévue en mois</span>
                <input name="usage_duration_months" type="number" min="1" max="240" value="{{ $v('usage_duration_months', 12) }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            </label>
            <label class="flex items-center gap-2 rounded-md border border-slate-200 bg-white p-3">
                <input type="hidden" name="cheaper_alternative" value="0">
                <input name="cheaper_alternative" type="checkbox" value="1" @checked((bool) $v('cheaper_alternative', false)) class="rounded border-slate-300 text-emerald-700 focus:ring-emerald-500">
                <span class="text-sm font-medium text-slate-700">Alternative moins chère disponible</span>
            </label>
            <label class="block">
                <span class="text-sm font-medium text-slate-700">Prix de l’alternative</span>
                <input name="alternative_price" type="number" min="0" step="0.01" value="{{ $v('alternative_price') }}" placeholder="Je ne sais pas" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            </label>
            <label class="block">
                <span class="text-sm font-medium text-slate-700">Paiement</span>
                <select name="payment_type" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="comptant" @selected($v('payment_type', 'comptant') === 'comptant')>Comptant</option>
                    <option value="plusieurs_fois" @selected($v('payment_type') === 'plusieurs_fois')>Plusieurs fois</option>
                </select>
            </label>
            <label class="block">
                <span class="text-sm font-medium text-slate-700">Mensualité</span>
                <input name="monthly_payment" type="number" min="0" step="0.01" value="{{ $v('monthly_payment') }}" placeholder="Je ne sais pas" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            </label>
            <label class="block">
                <span class="text-sm font-medium text-slate-700">Durée paiement</span>
                <input name="payment_duration" type="number" min="1" max="120" value="{{ $v('payment_duration') }}" placeholder="Je ne sais pas" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            </label>
            <label class="block">
                <span class="text-sm font-medium text-slate-700">Moment prévu</span>
                <select name="planned_timing" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="maintenant" @selected($v('planned_timing', 'maintenant') === 'maintenant')>Maintenant</option>
                    <option value="plus_tard" @selected($v('planned_timing') === 'plus_tard')>Plus tard</option>
                </select>
            </label>
        </div>
    </details>

    <button type="submit" class="w-full rounded-md bg-emerald-700 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-800 sm:w-auto">Évaluer l’achat</button>
</form>
