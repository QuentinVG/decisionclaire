@php
    $subscriptionRows = old('subscriptions', data_get($inputs, 'subscriptions', [
        ['name' => 'Streaming', 'price' => 12.99, 'usage' => 'parfois', 'importance' => 'moyenne', 'duplicate' => false, 'commitment' => false, 'cancellable' => true],
        ['name' => 'Musique', 'price' => 10.99, 'usage' => 'souvent', 'importance' => 'forte', 'duplicate' => false, 'commitment' => false, 'cancellable' => true],
        ['name' => 'Cloud', 'price' => 4.99, 'usage' => 'rarement', 'importance' => 'faible', 'duplicate' => false, 'commitment' => false, 'cancellable' => true],
    ]));
@endphp

<form method="POST" action="{{ route($tool['calculate_route']) }}" class="mt-5 space-y-5" x-data="{
    rows: @js($subscriptionRows),
    preset(type) {
        const presets = {
            starter: [
                {name: 'Streaming', price: 12.99, usage: 'parfois', importance: 'moyenne', duplicate: false, commitment: false, cancellable: true},
                {name: 'Musique', price: 10.99, usage: 'souvent', importance: 'forte', duplicate: false, commitment: false, cancellable: true},
                {name: 'Cloud', price: 4.99, usage: 'rarement', importance: 'faible', duplicate: false, commitment: false, cancellable: true}
            ],
            cleanup: [
                {name: 'Streaming peu utilisé', price: 15.99, usage: 'rarement', importance: 'faible', duplicate: true, commitment: false, cancellable: true},
                {name: 'Application mobile', price: 6.99, usage: 'jamais', importance: 'faible', duplicate: false, commitment: false, cancellable: true},
                {name: 'Abonnement sport', price: 29.99, usage: 'rarement', importance: 'moyenne', duplicate: false, commitment: true, cancellable: false}
            ],
            empty: [
                {name: '', price: '', usage: 'rarement', importance: 'faible', duplicate: false, commitment: false, cancellable: true}
            ]
        };
        this.rows = presets[type];
    }
}">
    @csrf

    <div class="rounded-md border border-emerald-200 bg-emerald-50 p-4">
        <p class="text-sm font-semibold text-emerald-900">Pas besoin de tout lister d’un coup</p>
        <p class="mt-1 text-sm text-emerald-800">Commence par 3 abonnements visibles. Tu pourras ajouter les autres ensuite.</p>
        <div class="mt-3 grid gap-2 sm:grid-cols-3">
            <button type="button" @click="preset('starter')" class="rounded-md border border-emerald-300 bg-white px-3 py-2 text-left text-sm font-semibold text-emerald-900 hover:bg-emerald-100">
                Pack courant
                <span class="block text-xs font-normal text-emerald-800">Streaming, musique, cloud</span>
            </button>
            <button type="button" @click="preset('cleanup')" class="rounded-md border border-emerald-300 bg-white px-3 py-2 text-left text-sm font-semibold text-emerald-900 hover:bg-emerald-100">
                Je veux faire le tri
                <span class="block text-xs font-normal text-emerald-800">Exemples peu utilisés</span>
            </button>
            <button type="button" @click="preset('empty')" class="rounded-md border border-emerald-300 bg-white px-3 py-2 text-left text-sm font-semibold text-emerald-900 hover:bg-emerald-100">
                Partir de zéro
                <span class="block text-xs font-normal text-emerald-800">Une ligne vide seulement</span>
            </button>
        </div>
    </div>

    <div class="space-y-4">
        <template x-for="(row, index) in rows" :key="index">
            <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                <div class="flex items-center justify-between gap-3">
                    <h3 class="font-semibold text-slate-950">Abonnement <span x-text="index + 1"></span></h3>
                    <button type="button" x-show="rows.length > 1" @click="rows.splice(index, 1)" class="text-sm font-semibold text-red-700">Supprimer</button>
                </div>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700">Nom</span>
                        <input type="text" maxlength="80" x-model="row.name" :name="`subscriptions[${index}][name]`" required placeholder="Ex. streaming, sport, cloud" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700">Prix mensuel</span>
                        <input type="number" min="0" step="0.01" x-model="row.price" :name="`subscriptions[${index}][price]`" required placeholder="Ex. 12.99" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700">Utilisation</span>
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
                            <option value="faible">Faible - je peux m’en passer</option>
                            <option value="moyenne">Moyenne</option>
                            <option value="forte">Forte - vraiment utile</option>
                        </select>
                    </label>
                </div>
                <div class="mt-4 grid gap-3 sm:grid-cols-3">
                    <label class="flex items-center gap-2 text-sm text-slate-700">
                        <input type="hidden" :name="`subscriptions[${index}][duplicate]`" value="0">
                        <input type="checkbox" :name="`subscriptions[${index}][duplicate]`" value="1" x-model="row.duplicate" class="rounded border-slate-300 text-emerald-700 focus:ring-emerald-500">
                        Doublon avec un autre service
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
