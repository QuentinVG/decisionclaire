@php
    $subscriptionRows = old('subscriptions', data_get($inputs, 'subscriptions', [
        ['name' => 'Streaming', 'price' => 12.99, 'usage' => 'parfois', 'importance' => 'moyenne', 'duplicate' => false, 'commitment' => false, 'cancellable' => true],
        ['name' => '', 'price' => '', 'usage' => 'rarement', 'importance' => 'faible', 'duplicate' => false, 'commitment' => false, 'cancellable' => true],
    ]));
@endphp

<form method="POST" action="{{ route($tool['calculate_route']) }}" class="mt-5 space-y-5" x-data="{ rows: @js($subscriptionRows) }">
    @csrf
    <div class="space-y-4">
        <template x-for="(row, index) in rows" :key="index">
            <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                <div class="grid gap-4 sm:grid-cols-2">
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700">Nom</span>
                        <input type="text" maxlength="80" x-model="row.name" :name="`subscriptions[${index}][name]`" required class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700">Prix mensuel</span>
                        <input type="number" min="0" step="0.01" x-model="row.price" :name="`subscriptions[${index}][price]`" required class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700">Fréquence d’utilisation</span>
                        <select x-model="row.usage" :name="`subscriptions[${index}][usage]`" required class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="jamais">Jamais</option>
                            <option value="rarement">Rarement</option>
                            <option value="parfois">Parfois</option>
                            <option value="souvent">Souvent</option>
                            <option value="quotidiennement">Quotidiennement</option>
                        </select>
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700">Importance</span>
                        <select x-model="row.importance" :name="`subscriptions[${index}][importance]`" required class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="faible">Faible</option>
                            <option value="moyenne">Moyenne</option>
                            <option value="forte">Forte</option>
                        </select>
                    </label>
                </div>
                <div class="mt-4 grid gap-3 sm:grid-cols-3">
                    <label class="flex items-center gap-2 text-sm text-slate-700">
                        <input type="hidden" :name="`subscriptions[${index}][duplicate]`" value="0">
                        <input type="checkbox" :name="`subscriptions[${index}][duplicate]`" value="1" x-model="row.duplicate" class="rounded border-slate-300 text-emerald-700 focus:ring-emerald-500">
                        Doublon
                    </label>
                    <label class="flex items-center gap-2 text-sm text-slate-700">
                        <input type="hidden" :name="`subscriptions[${index}][commitment]`" value="0">
                        <input type="checkbox" :name="`subscriptions[${index}][commitment]`" value="1" x-model="row.commitment" class="rounded border-slate-300 text-emerald-700 focus:ring-emerald-500">
                        Engagement
                    </label>
                    <label class="flex items-center gap-2 text-sm text-slate-700">
                        <input type="hidden" :name="`subscriptions[${index}][cancellable]`" value="0">
                        <input type="checkbox" :name="`subscriptions[${index}][cancellable]`" value="1" x-model="row.cancellable" class="rounded border-slate-300 text-emerald-700 focus:ring-emerald-500">
                        Résiliable maintenant
                    </label>
                </div>
                <button type="button" x-show="rows.length > 1" @click="rows.splice(index, 1)" class="mt-4 text-sm font-semibold text-red-700">Supprimer cette ligne</button>
            </div>
        </template>
    </div>

    <div class="flex flex-col gap-3 sm:flex-row">
        <button type="button" @click="if (rows.length < 20) rows.push({name: '', price: '', usage: 'rarement', importance: 'faible', duplicate: false, commitment: false, cancellable: true})" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-100">
            Ajouter un abonnement
        </button>
        <button type="submit" class="rounded-md bg-emerald-700 px-5 py-2 text-sm font-semibold text-white hover:bg-emerald-800">
            Analyser mes abonnements
        </button>
    </div>
</form>
