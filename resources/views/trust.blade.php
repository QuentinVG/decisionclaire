<x-app-layout title="Confidentialité et limites | DécisionClaire" meta-description="DécisionClaire explique ses limites : aucune connexion bancaire, calculs indicatifs, données saisies manuellement et pas de conseil financier personnalisé.">
    <div class="dc-section py-12">
        <a href="{{ route('home') }}" class="text-sm font-bold text-emerald-800 underline decoration-emerald-200 underline-offset-4">Retour à DécisionClaire</a>

        <section class="mt-8 max-w-3xl">
            <p class="dc-badge">Confiance</p>
            <h1 class="mt-4 text-4xl font-extrabold leading-tight text-slate-950 sm:text-5xl">Confidentialité et limites</h1>
            <p class="mt-5 text-lg leading-8 text-slate-700">DécisionClaire est conçu pour répondre vite à une décision d'argent ponctuelle. Il ne remplace pas une banque, un conseiller financier ou une app de budget connectée.</p>
        </section>

        <section class="mt-10 grid gap-4 md:grid-cols-2">
            <div class="dc-surface p-6">
                <h2 class="text-2xl font-extrabold text-slate-950">Aucune connexion bancaire</h2>
                <p class="mt-3 leading-7 text-slate-600">L'application ne demande pas l'accès à tes comptes, ne lit pas tes opérations et ne catégorise aucune transaction bancaire. Tu saisis uniquement les montants nécessaires au calcul.</p>
            </div>
            <div class="dc-surface p-6">
                <h2 class="text-2xl font-extrabold text-slate-950">Moins précis, mais plus rapide</h2>
                <p class="mt-3 leading-7 text-slate-600">Sans connexion bancaire, le résultat dépend de tes réponses. Le but est de décider vite avec prudence, pas de produire une comptabilité exacte.</p>
            </div>
            <div class="dc-surface p-6">
                <h2 class="text-2xl font-extrabold text-slate-950">Pas de conseil financier personnalisé</h2>
                <p class="mt-3 leading-7 text-slate-600">Les verdicts sont des repères indicatifs : raisonnable, limite ou risqué. Ils ne constituent pas une recommandation professionnelle.</p>
            </div>
            <div class="dc-surface p-6">
                <h2 class="text-2xl font-extrabold text-slate-950">Compte optionnel après résultat</h2>
                <p class="mt-3 leading-7 text-slate-600">Le compte sert à sauvegarder, revenir dans 48 h, dupliquer ou exporter une simulation. Les outils publics restent utilisables sans compte obligatoire.</p>
            </div>
        </section>
    </div>
</x-app-layout>
