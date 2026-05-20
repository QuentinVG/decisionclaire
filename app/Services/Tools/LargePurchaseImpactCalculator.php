<?php

namespace App\Services\Tools;

use App\Services\Verdict\VerdictBuilder;

final class LargePurchaseImpactCalculator
{
    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    public function calculate(array $input): array
    {
        $name = trim((string) ($input['purchase_name'] ?? 'Gros achat'));
        $price = $this->amount($input, 'total_price');
        $income = $this->amount($input, 'monthly_income');
        $charges = $this->amount($input, 'monthly_charges') + $this->amount($input, 'planned_expenses');
        $currentSavings = $this->amount($input, 'current_savings');
        $minimumSavings = $this->amount($input, 'minimum_savings');
        $immediatePayment = ($input['payment_mode'] ?? 'comptant') === 'mensualise'
            ? min($price, $this->amount($input, 'down_payment'))
            : $price;
        $monthlyPayment = ($input['payment_mode'] ?? 'comptant') === 'mensualise'
            ? $this->amount($input, 'monthly_payment')
            : 0.0;
        $paymentDuration = max(1, (int) ($input['payment_duration'] ?? 1));
        $savingsAfter = $currentSavings - $immediatePayment;
        $availableDuringPayment = $income - $charges - $monthlyPayment;
        $monthsToRebuild = $availableDuringPayment > 0 && $savingsAfter < $currentSavings
            ? (int) ceil(($currentSavings - $savingsAfter) / $availableDuringPayment)
            : 0;
        $underThreshold = $savingsAfter < $minimumSavings || $availableDuringPayment <= 0;
        $riskScore = $this->riskScore($savingsAfter, $minimumSavings, $availableDuringPayment, $income, $paymentDuration);
        $criticalMonths = $this->criticalMonths($underThreshold, $paymentDuration);
        $verdict = $this->verdict($riskScore, $underThreshold);

        return VerdictBuilder::result(
            'large_purchase_impact',
            'Impact gros achat',
            $name,
            $verdict,
            'Épargne restante après achat',
            VerdictBuilder::money($savingsAfter),
            $this->riskLevel($riskScore),
            $this->confidence($input),
            $this->explanation($verdict, $savingsAfter, $minimumSavings, $availableDuringPayment),
            $this->recommendations($underThreshold, $monthsToRebuild, $monthlyPayment),
            $this->summary($name, $savingsAfter, $availableDuringPayment, $verdict),
            [
                ['label' => 'Prix total', 'value' => VerdictBuilder::money($price)],
                ['label' => 'Reste à vivre pendant paiement', 'value' => VerdictBuilder::money($availableDuringPayment)],
                ['label' => 'Mois pour reconstituer l’épargne', 'value' => $monthsToRebuild > 0 ? (string) $monthsToRebuild : 'Non estimable'],
                ['label' => 'Risque sous seuil', 'value' => $underThreshold ? 'Oui' : 'Non'],
                ['label' => 'Mois critiques', 'value' => $criticalMonths],
            ],
        );
    }

    private function riskScore(float $savingsAfter, float $minimumSavings, float $availableDuringPayment, float $income, int $paymentDuration): int
    {
        $score = 15;
        $score += $savingsAfter < $minimumSavings ? 35 : 0;
        $score += $availableDuringPayment <= 0 ? 35 : 0;
        $score += $income > 0 && ($availableDuringPayment / $income) < 0.15 ? 15 : 0;
        $score += $paymentDuration > 6 ? 10 : 0;

        return VerdictBuilder::clampScore($score);
    }

    private function verdict(int $riskScore, bool $underThreshold): string
    {
        if ($riskScore >= 75) {
            return 'impact risqué';
        }

        if ($underThreshold || $riskScore >= 55) {
            return 'impact limite';
        }

        if ($riskScore >= 35) {
            return 'impact à surveiller';
        }

        return 'impact maîtrisé';
    }

    private function explanation(string $verdict, float $savingsAfter, float $minimumSavings, float $availableDuringPayment): string
    {
        $thresholdText = $savingsAfter < $minimumSavings
            ? 'L’épargne passerait sous la marge de sécurité indiquée.'
            : 'La marge de sécurité indiquée resterait préservée.';

        return 'Selon les données renseignées, cet achat aurait un '.$verdict.'. '
            .$thresholdText.' Le reste à vivre pendant la période de paiement est estimé à '
            .VerdictBuilder::money($availableDuringPayment).' par mois.';
    }

    /**
     * @return array<int, string>
     */
    private function recommendations(bool $underThreshold, int $monthsToRebuild, float $monthlyPayment): array
    {
        $recommendations = [];

        if ($underThreshold) {
            $recommendations[] = 'Décaler l’achat ou réduire son montant pour préserver la marge de sécurité.';
        }

        if ($monthsToRebuild > 6) {
            $recommendations[] = 'Prévoir un délai plus long avant l’achat pour reconstituer l’épargne plus vite après coup.';
        }

        if ($monthlyPayment > 0) {
            $recommendations[] = 'Tester le budget avec la mensualité pendant un mois avant de s’engager.';
        }

        $recommendations[] = 'Garder une réserve pour les dépenses imprévues pendant les mois qui suivent.';

        return array_slice($recommendations, 0, 3);
    }

    private function summary(string $name, float $savingsAfter, float $availableDuringPayment, string $verdict): string
    {
        return "Résumé DécisionClaire :\nAchat : {$name}"
            ."\nVerdict : {$verdict}\nÉpargne après achat : ".VerdictBuilder::money($savingsAfter)
            ."\nReste à vivre pendant paiement : ".VerdictBuilder::money($availableDuringPayment)
            ."\nConseil : préserver la marge de sécurité avant de décider.";
    }

    private function riskLevel(int $riskScore): string
    {
        return match (true) {
            $riskScore >= 70 => 'risqué',
            $riskScore >= 50 => 'limite',
            $riskScore >= 30 => 'modéré',
            default => 'faible',
        };
    }

    private function criticalMonths(bool $underThreshold, int $paymentDuration): string
    {
        if (! $underThreshold) {
            return 'Aucun mois critique détecté';
        }

        return $paymentDuration <= 1 ? 'Mois 1' : 'Mois 1 à '.min($paymentDuration, 6);
    }

    /**
     * @param  array<string, mixed>  $input
     */
    private function confidence(array $input): int
    {
        $keys = ['purchase_name', 'total_price', 'payment_mode', 'monthly_income', 'monthly_charges', 'current_savings', 'minimum_savings'];
        $filled = count(array_filter($keys, fn (string $key): bool => isset($input[$key]) && $input[$key] !== ''));

        return VerdictBuilder::clampScore(50 + ($filled / count($keys)) * 40 + ($this->amount($input, 'planned_expenses') > 0 ? 10 : 0));
    }

    /**
     * @param  array<string, mixed>  $input
     */
    private function amount(array $input, string $key): float
    {
        return max(0.0, (float) ($input[$key] ?? 0));
    }
}
