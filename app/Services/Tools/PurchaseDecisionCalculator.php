<?php

namespace App\Services\Tools;

use App\Services\Verdict\VerdictBuilder;

final class PurchaseDecisionCalculator
{
    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    public function calculate(array $input): array
    {
        $name = trim((string) ($input['purchase_name'] ?? 'Achat'));
        $price = $this->amount($input, 'price');
        $available = max(1.0, $this->amount($input, 'available_monthly'));
        $savings = $this->amount($input, 'available_savings');
        $minimumSavings = $this->amount($input, 'minimum_savings');
        $duration = max(1, (int) ($input['usage_duration_months'] ?? 12));
        $usagePerMonth = $this->usagePerMonth((string) ($input['usage_frequency'] ?? 'mensuelle'));
        $monthlyCost = ($input['payment_type'] ?? 'comptant') === 'plusieurs_fois'
            ? $this->amount($input, 'monthly_payment')
            : $price / $duration;
        $costPerUse = $price / max(1, $duration * $usagePerMonth);
        $impactOnAvailable = ($price / $available) * 100;
        $savingsAfter = $savings - $price;
        $utilityScore = $this->levelScore((string) ($input['utility'] ?? 'moyenne'));
        $riskScore = $this->riskScore($input, $impactOnAvailable, $savingsAfter, $monthlyCost);
        $impulseScore = $this->impulseScore($input, $riskScore);
        $verdict = $this->verdict($input, $riskScore, $impulseScore, $savingsAfter);
        $confidence = $this->confidence($input);

        return VerdictBuilder::result(
            'purchase_decision',
            'J’achète ou pas ?',
            $name,
            $verdict,
            'Impact sur reste à vivre',
            VerdictBuilder::percent($impactOnAvailable),
            $this->riskLevel($riskScore),
            $confidence,
            $this->explanation($verdict, $impactOnAvailable, $minimumSavings, $savingsAfter),
            $this->recommendations($input, $riskScore, $impulseScore),
            $this->summary($name, $price, $verdict, $riskScore),
            [
                ['label' => 'Prix', 'value' => VerdictBuilder::money($price)],
                ['label' => 'Coût mensuel théorique', 'value' => VerdictBuilder::decimalMoney($monthlyCost)],
                ['label' => 'Coût par usage estimé', 'value' => VerdictBuilder::decimalMoney($costPerUse)],
                ['label' => 'Impact sur épargne', 'value' => VerdictBuilder::money($savingsAfter)],
                ['label' => 'Score utilité', 'value' => $utilityScore.'/100'],
                ['label' => 'Score risque', 'value' => $riskScore.'/100'],
                ['label' => 'Score impulsivité', 'value' => $impulseScore.'/100'],
            ],
        );
    }

    /**
     * @param  array<string, mixed>  $input
     */
    private function riskScore(array $input, float $impactOnAvailable, float $savingsAfter, float $monthlyCost): int
    {
        $available = max(1.0, $this->amount($input, 'available_monthly'));
        $score = 15;
        $score += min(35, $impactOnAvailable * 0.35);
        $score += $savingsAfter < $this->amount($input, 'minimum_savings') ? 25 : 0;
        $score += ($monthlyCost / $available) > 0.2 ? 15 : 0;
        $score += $this->levelScore((string) ($input['utility'] ?? 'moyenne')) < 50 ? 10 : 0;
        $score -= $this->levelScore((string) ($input['urgency'] ?? 'moyenne')) > 70 ? 8 : 0;

        return VerdictBuilder::clampScore($score);
    }

    /**
     * @param  array<string, mixed>  $input
     */
    private function impulseScore(array $input, int $riskScore): int
    {
        $score = $riskScore * 0.35;
        $score += $this->levelScore((string) ($input['urgency'] ?? 'moyenne')) < 50 ? 25 : 0;
        $score += $this->levelScore((string) ($input['utility'] ?? 'moyenne')) < 50 ? 25 : 0;
        $score += ($input['planned_timing'] ?? 'maintenant') === 'maintenant' ? 10 : 0;
        $score += (bool) ($input['cheaper_alternative'] ?? false) ? 12 : 0;

        return VerdictBuilder::clampScore($score);
    }

    /**
     * @param  array<string, mixed>  $input
     */
    private function verdict(array $input, int $riskScore, int $impulseScore, float $savingsAfter): string
    {
        if ($savingsAfter < $this->amount($input, 'minimum_savings') && $riskScore >= 75) {
            return 'à éviter pour l’instant';
        }

        if ($riskScore >= 70) {
            return 'achat risqué';
        }

        if ($impulseScore >= 70) {
            return 'achat impulsif probable';
        }

        if ($riskScore >= 50) {
            return 'achat limite';
        }

        if (($input['urgency'] ?? 'moyenne') === 'faible') {
            return 'achat acceptable mais pas urgent';
        }

        return 'achat raisonnable';
    }

    private function explanation(string $verdict, float $impactOnAvailable, float $minimumSavings, float $savingsAfter): string
    {
        $security = $savingsAfter < $minimumSavings
            ? ' Il ferait passer l’épargne sous la marge minimale indiquée.'
            : ' La marge d’épargne minimale indiquée semble préservée.';

        return 'Selon les données renseignées, cet achat semble '.$verdict.'. Il représente environ '
            .VerdictBuilder::percent($impactOnAvailable).' du reste à vivre mensuel déclaré.'.$security;
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<int, string>
     */
    private function recommendations(array $input, int $riskScore, int $impulseScore): array
    {
        $recommendations = [];

        if ($riskScore >= 50 || $impulseScore >= 55) {
            $recommendations[] = 'Attendre 48h avant de décider, surtout si l’achat n’est pas urgent.';
        }

        if ((bool) ($input['cheaper_alternative'] ?? false)) {
            $alternative = $this->amount($input, 'alternative_price');
            $recommendations[] = $alternative > 0
                ? 'Comparer avec l’alternative autour de '.VerdictBuilder::money($alternative).'.'
                : 'Chercher une alternative moins chère avant de valider.';
        }

        $recommendations[] = 'Vérifier que la dépense ne réduit pas ta marge de sécurité utile pour le mois.';

        return array_slice($recommendations, 0, 3);
    }

    private function summary(string $name, float $price, string $verdict, int $riskScore): string
    {
        return "Résumé DécisionClaire :\nAchat : {$name} - ".VerdictBuilder::money($price)
            ."\nVerdict : {$verdict}\nRisque : {$riskScore}/100"
            ."\nConseil : attendre 48h ou comparer une alternative si l’achat n’est pas urgent.";
    }

    /**
     * @param  array<string, mixed>  $input
     */
    private function confidence(array $input): int
    {
        $keys = ['purchase_name', 'price', 'available_monthly', 'available_savings', 'urgency', 'utility', 'usage_frequency'];
        $filled = count(array_filter($keys, fn (string $key): bool => isset($input[$key]) && $input[$key] !== ''));

        return VerdictBuilder::clampScore(50 + ($filled / count($keys)) * 35 + ($this->amount($input, 'usage_duration_months') > 0 ? 15 : 0));
    }

    private function usagePerMonth(string $frequency): int
    {
        return match ($frequency) {
            'rare' => 1,
            'mensuelle' => 2,
            'hebdo' => 8,
            'quotidienne' => 30,
            default => 2,
        };
    }

    private function levelScore(string $level): int
    {
        return match ($level) {
            'faible' => 25,
            'forte' => 85,
            default => 55,
        };
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

    /**
     * @param  array<string, mixed>  $input
     */
    private function amount(array $input, string $key): float
    {
        return max(0.0, (float) ($input[$key] ?? 0));
    }
}
