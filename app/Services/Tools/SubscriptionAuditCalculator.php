<?php

namespace App\Services\Tools;

use App\Services\Verdict\VerdictBuilder;

final class SubscriptionAuditCalculator
{
    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    public function calculate(array $input): array
    {
        $subscriptions = $this->subscriptions($input);
        $monthlyTotal = array_sum(array_map(fn (array $subscription): float => $subscription['price'], $subscriptions));
        $annualTotal = $monthlyTotal * 12;
        $lowUseful = $this->lowUseful($subscriptions);
        $monthlySavings = array_sum(array_map(fn (array $subscription): float => $subscription['price'], $lowUseful));
        $annualSavings = $monthlySavings * 12;
        $priority = $this->priorityLabel($monthlySavings, $monthlyTotal);

        return VerdictBuilder::result(
            'subscription_audit',
            'Abonnements inutiles',
            'Audit abonnements',
            $priority,
            'Économie mensuelle possible',
            VerdictBuilder::money($monthlySavings),
            $monthlySavings > 0 ? 'modéré' : 'faible',
            $this->confidence($subscriptions),
            $this->explanation($monthlyTotal, $monthlySavings, count($lowUseful)),
            $this->recommendations($lowUseful),
            $this->summary($monthlyTotal, $annualTotal, $monthlySavings, $priority),
            [
                ['label' => 'Coût mensuel total', 'value' => VerdictBuilder::money($monthlyTotal)],
                ['label' => 'Coût annuel total', 'value' => VerdictBuilder::money($annualTotal)],
                ['label' => 'Abonnements peu utiles', 'value' => $this->names($lowUseful)],
                ['label' => 'Économie annuelle possible', 'value' => VerdictBuilder::money($annualSavings)],
                ['label' => 'Priorité de résiliation', 'value' => $priority],
            ],
        );
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<int, array{name:string,price:float,usage:string,importance:string,duplicate:bool,commitment:bool,cancellable:bool}>
     */
    private function subscriptions(array $input): array
    {
        $rows = is_array($input['subscriptions'] ?? null) ? $input['subscriptions'] : [];
        $subscriptions = [];

        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }

            $name = trim((string) ($row['name'] ?? ''));
            $price = max(0.0, (float) ($row['price'] ?? 0));

            if ($name === '' || $price <= 0) {
                continue;
            }

            $subscriptions[] = [
                'name' => $name,
                'price' => $price,
                'usage' => (string) ($row['usage'] ?? 'parfois'),
                'importance' => (string) ($row['importance'] ?? 'moyenne'),
                'duplicate' => (bool) ($row['duplicate'] ?? false),
                'commitment' => (bool) ($row['commitment'] ?? false),
                'cancellable' => (bool) ($row['cancellable'] ?? false),
            ];
        }

        return $subscriptions;
    }

    /**
     * @param  array<int, array{name:string,price:float,usage:string,importance:string,duplicate:bool,commitment:bool,cancellable:bool}>  $subscriptions
     * @return array<int, array{name:string,price:float,usage:string,importance:string,duplicate:bool,commitment:bool,cancellable:bool}>
     */
    private function lowUseful(array $subscriptions): array
    {
        return array_values(array_filter($subscriptions, function (array $subscription): bool {
            $score = 0;
            $score += in_array($subscription['usage'], ['jamais', 'rarement'], true) ? 2 : 0;
            $score += $subscription['usage'] === 'parfois' ? 1 : 0;
            $score += $subscription['importance'] === 'faible' ? 2 : 0;
            $score += $subscription['importance'] === 'moyenne' ? 1 : 0;
            $score += $subscription['duplicate'] ? 1 : 0;
            $score += $subscription['cancellable'] ? 1 : 0;
            $score -= $subscription['commitment'] && ! $subscription['cancellable'] ? 2 : 0;

            return $score >= 4;
        }));
    }

    /**
     * @param  array<int, array{name:string,price:float,usage:string,importance:string,duplicate:bool,commitment:bool,cancellable:bool}>  $lowUseful
     * @return array<int, string>
     */
    private function recommendations(array $lowUseful): array
    {
        if ($lowUseful === []) {
            return [
                'Conserver les abonnements vraiment utilisés, mais vérifier le coût annuel une fois par trimestre.',
                'Surveiller les doublons entre services similaires.',
            ];
        }

        $first = $lowUseful[0]['name'];

        return [
            'Commencer par vérifier manuellement la résiliation de '.$first.'.',
            'Annuler d’abord les services peu utilisés et sans engagement.',
            'Comparer le coût annuel au nombre réel d’utilisations.',
        ];
    }

    private function explanation(float $monthlyTotal, float $monthlySavings, int $lowUsefulCount): string
    {
        return 'Selon les données renseignées, tes abonnements coûtent environ '.VerdictBuilder::money($monthlyTotal)
            .' par mois. '.$lowUsefulCount.' abonnement(s) semblent peu utiles, pour une économie possible de '
            .VerdictBuilder::money($monthlySavings).' par mois.';
    }

    private function priorityLabel(float $monthlySavings, float $monthlyTotal): string
    {
        if ($monthlySavings <= 0) {
            return 'pas de résiliation prioritaire';
        }

        $share = $monthlyTotal > 0 ? $monthlySavings / $monthlyTotal : 0;

        return $share >= 0.35 ? 'résiliation prioritaire' : 'économie possible à vérifier';
    }

    /**
     * @param  array<int, array{name:string,price:float,usage:string,importance:string,duplicate:bool,commitment:bool,cancellable:bool}>  $subscriptions
     */
    private function names(array $subscriptions): string
    {
        if ($subscriptions === []) {
            return 'Aucun détecté';
        }

        return implode(', ', array_map(fn (array $subscription): string => $subscription['name'], $subscriptions));
    }

    private function summary(float $monthlyTotal, float $annualTotal, float $monthlySavings, string $priority): string
    {
        return "Résumé DécisionClaire :\nCoût abonnements : ".VerdictBuilder::money($monthlyTotal).'/mois'
            ."\nCoût annuel : ".VerdictBuilder::money($annualTotal)
            ."\nÉconomie possible : ".VerdictBuilder::money($monthlySavings).'/mois'
            ."\nVerdict : {$priority}\nConseil : résilier seulement après vérification des conditions.";
    }

    /**
     * @param  array<int, array{name:string,price:float,usage:string,importance:string,duplicate:bool,commitment:bool,cancellable:bool}>  $subscriptions
     */
    private function confidence(array $subscriptions): int
    {
        if ($subscriptions === []) {
            return 40;
        }

        return VerdictBuilder::clampScore(60 + min(30, count($subscriptions) * 6));
    }
}
