@php($v = fn ($key, $default = '') => old($key, data_get($inputs, $key, $default)))

<form method="POST" action="{{ route($tool['calculate_route']) }}" class="mt-5 space-y-5">
    @csrf
    <div class="grid gap-4 sm:grid-cols-2">
        <label class="block sm:col-span-2">
            <span class="text-sm font-medium text-slate-700">Nom de l’achat</span>
            <input name="purchase_name" type="text" maxlength="100" required value="{{ $v('purchase_name') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
        </label>
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Prix total</span>
            <input name="total_price" type="number" min="0.01" step="0.01" required value="{{ $v('total_price') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
        </label>
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Paiement</span>
            <select name="payment_mode" required class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                <option value="comptant" @selected($v('payment_mode', 'comptant') === 'comptant')>Comptant</option>
                <option value="mensualise" @selected($v('payment_mode') === 'mensualise')>Mensualisé</option>
            </select>
        </label>
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Revenus mensuels</span>
            <input name="monthly_income" type="number" min="0" step="0.01" required value="{{ $v('monthly_income') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
        </label>
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Charges mensuelles</span>
            <input name="monthly_charges" type="number" min="0" step="0.01" required value="{{ $v('monthly_charges') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
        </label>
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Épargne actuelle</span>
            <input name="current_savings" type="number" min="0" step="0.01" required value="{{ $v('current_savings') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
        </label>
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Épargne minimale à garder</span>
            <input name="minimum_savings" type="number" min="0" step="0.01" required value="{{ $v('minimum_savings') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
        </label>
    </div>

    <details class="rounded-md border border-slate-200 bg-slate-50 p-4">
        <summary class="cursor-pointer text-sm font-semibold text-slate-800">Mode avancé</summary>
        <p class="mt-2 text-sm text-slate-600">Si tu ne sais pas, laisse vide. Les champs mensualisés ne sont utilisés que si le paiement est mensualisé.</p>
        <div class="mt-4 grid gap-4 sm:grid-cols-2">
            <label class="block">
                <span class="text-sm font-medium text-slate-700">Apport / paiement immédiat</span>
                <input name="down_payment" type="number" min="0" step="0.01" value="{{ $v('down_payment') }}" placeholder="Je ne sais pas" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            </label>
            <label class="block">
                <span class="text-sm font-medium text-slate-700">Mensualité</span>
                <input name="monthly_payment" type="number" min="0" step="0.01" value="{{ $v('monthly_payment') }}" placeholder="Je ne sais pas" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            </label>
            <label class="block">
                <span class="text-sm font-medium text-slate-700">Durée mensualités</span>
                <input name="payment_duration" type="number" min="1" max="120" value="{{ $v('payment_duration') }}" placeholder="Je ne sais pas" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            </label>
            <label class="block">
                <span class="text-sm font-medium text-slate-700">Autres dépenses prévues</span>
                <input name="planned_expenses" type="number" min="0" step="0.01" value="{{ $v('planned_expenses') }}" placeholder="Je ne sais pas" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            </label>
            <label class="block sm:col-span-2">
                <span class="text-sm font-medium text-slate-700">Délai souhaité avant achat en mois</span>
                <input name="delay_months" type="number" min="0" max="120" value="{{ $v('delay_months') }}" placeholder="0 si achat prévu maintenant" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            </label>
        </div>
    </details>

    <button type="submit" class="w-full rounded-md bg-emerald-700 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-800 sm:w-auto">Simuler l’impact</button>
</form>
