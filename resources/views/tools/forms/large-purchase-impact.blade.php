@php($v = fn ($key, $default = '') => old($key, data_get($inputs, $key, $default)))

<form method="POST" action="{{ route($tool['calculate_route']) }}" class="mt-5 space-y-5" x-data="{
    fill(values) {
        Object.entries(values).forEach(([key, value]) => {
            if (this.$root.elements[key]) this.$root.elements[key].value = value;
        });
    },
    budgetProfile(type) {
        const profiles = {
            simple: { monthly_income: 1800, monthly_charges: 1150, current_savings: 900, minimum_savings: 400 },
            stable: { monthly_income: 2500, monthly_charges: 1550, current_savings: 2200, minimum_savings: 900 },
            fragile: { monthly_income: 1600, monthly_charges: 1350, current_savings: 600, minimum_savings: 500 }
        };
        this.fill(profiles[type]);
    },
    paymentProfile(type) {
        const price = Number(this.$root.elements.total_price.value) || 1000;
        if (type === 'cash') this.fill({ payment_mode: 'comptant', down_payment: price, monthly_payment: 0, payment_duration: 1 });
        if (type === 'soft') this.fill({ payment_mode: 'mensualise', down_payment: Math.round(price * 0.25), monthly_payment: Math.round(price * 0.15), payment_duration: 6 });
        if (type === 'long') this.fill({ payment_mode: 'mensualise', down_payment: Math.round(price * 0.10), monthly_payment: Math.round(price * 0.08), payment_duration: 12 });
    }
}">
    @csrf

    <div class="rounded-md border border-emerald-200 bg-emerald-50 p-4">
        <p class="text-sm font-semibold text-emerald-900">Tu peux partir d’un profil approximatif</p>
        <div class="mt-3 grid gap-2 sm:grid-cols-3">
            <button type="button" @click="budgetProfile('fragile')" class="rounded-md border border-emerald-300 bg-white px-3 py-2 text-left text-sm font-semibold text-emerald-900 hover:bg-emerald-100">
                Marge fragile
                <span class="block text-xs font-normal text-emerald-800">Peu d’épargne, charges fortes</span>
            </button>
            <button type="button" @click="budgetProfile('simple')" class="rounded-md border border-emerald-300 bg-white px-3 py-2 text-left text-sm font-semibold text-emerald-900 hover:bg-emerald-100">
                Budget simple
                <span class="block text-xs font-normal text-emerald-800">Données prudentes</span>
            </button>
            <button type="button" @click="budgetProfile('stable')" class="rounded-md border border-emerald-300 bg-white px-3 py-2 text-left text-sm font-semibold text-emerald-900 hover:bg-emerald-100">
                Budget stable
                <span class="block text-xs font-normal text-emerald-800">Plus de marge d’épargne</span>
            </button>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <label class="block sm:col-span-2">
            <span class="text-sm font-medium text-slate-700">Nom de l’achat</span>
            <input name="purchase_name" type="text" maxlength="100" required value="{{ $v('purchase_name') }}" placeholder="PC, voyage, équipement..." class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
        </label>
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Prix total</span>
            <input name="total_price" type="number" min="0.01" step="0.01" required value="{{ $v('total_price') }}" placeholder="Ex. 1200" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            <div class="mt-2 flex flex-wrap gap-2">
                <button type="button" @click="fill({ total_price: 600 })" class="rounded border border-slate-200 px-2 py-1 text-xs text-slate-700 hover:bg-slate-50">600 €</button>
                <button type="button" @click="fill({ total_price: 1200 })" class="rounded border border-slate-200 px-2 py-1 text-xs text-slate-700 hover:bg-slate-50">1 200 €</button>
                <button type="button" @click="fill({ total_price: 2500 })" class="rounded border border-slate-200 px-2 py-1 text-xs text-slate-700 hover:bg-slate-50">2 500 €</button>
            </div>
        </label>
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Paiement</span>
            <select name="payment_mode" required class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                <option value="comptant" @selected($v('payment_mode', 'comptant') === 'comptant')>Comptant</option>
                <option value="mensualise" @selected($v('payment_mode') === 'mensualise')>Mensualisé</option>
            </select>
            <div class="mt-2 flex flex-wrap gap-2">
                <button type="button" @click="paymentProfile('cash')" class="rounded border border-slate-200 px-2 py-1 text-xs text-slate-700 hover:bg-slate-50">Comptant</button>
                <button type="button" @click="paymentProfile('soft')" class="rounded border border-slate-200 px-2 py-1 text-xs text-slate-700 hover:bg-slate-50">En 6 mois</button>
                <button type="button" @click="paymentProfile('long')" class="rounded border border-slate-200 px-2 py-1 text-xs text-slate-700 hover:bg-slate-50">En 12 mois</button>
            </div>
        </label>
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Revenus mensuels</span>
            <input name="monthly_income" type="number" min="0" step="0.01" required value="{{ $v('monthly_income') }}" placeholder="Ex. 2500" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
        </label>
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Dépenses mensuelles habituelles</span>
            <input name="monthly_charges" type="number" min="0" step="0.01" required value="{{ $v('monthly_charges') }}" placeholder="Ex. 1550" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            <span class="mt-1 block text-xs text-slate-500">Si tu ne connais pas le détail, utilise seulement un profil ci-dessus.</span>
        </label>
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Épargne actuelle</span>
            <input name="current_savings" type="number" min="0" step="0.01" required value="{{ $v('current_savings') }}" placeholder="Ex. 2200" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
        </label>
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Épargne minimale à garder</span>
            <input name="minimum_savings" type="number" min="0" step="0.01" required value="{{ $v('minimum_savings') }}" placeholder="Ex. 900" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            <div class="mt-2 flex flex-wrap gap-2">
                <button type="button" @click="fill({ minimum_savings: 300 })" class="rounded border border-slate-200 px-2 py-1 text-xs text-slate-700 hover:bg-slate-50">Minimum bas</button>
                <button type="button" @click="fill({ minimum_savings: 800 })" class="rounded border border-slate-200 px-2 py-1 text-xs text-slate-700 hover:bg-slate-50">Marge prudente</button>
            </div>
        </label>
    </div>

    <details class="rounded-md border border-slate-200 bg-slate-50 p-4">
        <summary class="cursor-pointer text-sm font-semibold text-slate-800">Détails avancés</summary>
        <p class="mt-2 text-sm text-slate-600">Ces champs servent surtout si le paiement est étalé ou si une autre dépense arrive bientôt.</p>
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
