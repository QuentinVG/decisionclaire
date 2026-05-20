<?php

namespace App\Services\Tools;

use App\Services\Verdict\VerdictBuilder;
use Carbon\CarbonImmutable;

final class SavingsGoalCalculator
{
    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    public function calculate(array $input): array
    {
        $name = trim((string) ($input['goal_name'] ?? 'Objectif'));
        $targetAmount = $this->amount($input, 'target_amount');
        $currentSavings = $this->amount($input, 'current_savings');
        $monthlyCapacity = $this->amount($input, 'monthly_capacity');
        $remaining = max(0.0, $targetAmount - $currentSavings);
        $months = $this->monthsUntil((string) ($input['target_date'] ?? ''));
        $monthlyNeeded = $remaining / max(1, $months);
        $weeklyNeeded = $monthlyNeeded / 4.345;
        $feasibility = $this->feasibility($monthlyNeeded, $monthlyCapacity, $input);
        $alternativeDate = $this->alternativeDate($remaining, $monthlyCapacity);

        return VerdictBuilder::result(
            'savings_goal',
            'Objectif épargne',
            $name,
            'Objectif '.$feasibility,
            'Montant mensuel nécessaire',
            VerdictBuilder::money($monthlyNeeded),
            $feasibility === 'irréaliste' ? 'risqué' : ($feasibility === 'ambitieux' ? 'limite' : 'faible'),
            $this->confidence($input),
            $this->explanation($feasibility, $remaining, $months, $monthlyCapacity),
            $this->recommendations($feasibility, $alternativeDate),
            $this->summary($name, $remaining, $monthlyNeeded, $feasibility),
            [
                ['label' => 'Montant restant à épargner', 'value' => VerdictBuilder::money($remaining)],
                ['label' => 'Montant hebdomadaire nécessaire', 'value' => VerdictBuilder::decimalMoney($weeklyNeeded)],
                ['label' => 'Délai jusqu’à la date cible', 'value' => $months.' mois'],
                ['label' => 'Date réaliste alternative', 'value' => $alternativeDate],
            ],
        );
    }

    /**
     * @param  array<string, mixed>  $input
     */
    private function feasibility(float $monthlyNeeded, float $monthlyCapacity, array $input): string
    {
        $living = $this->amount($input, 'current_living_balance');
        $security = $this->amount($input, 'security_margin');
        $adjustedCapacity = $living > 0 ? min($monthlyCapacity, max(0.0, $living - $security)) : $monthlyCapacity;

        if ($monthlyNeeded <= $adjustedCapacity * 0.5) {
            return 'facile';
        }

        if ($monthlyNeeded <= $adjustedCapacity) {
            return 'réaliste';
        }

        if ($monthlyNeeded <= $adjustedCapacity * 1.4) {
            return 'ambitieux';
        }

        return 'irréaliste';
    }

    private function monthsUntil(string $targetDate): int
    {
        $target = $targetDate !== '' ? CarbonImmutable::parse($targetDate) : CarbonImmutable::now()->addMonths(6);
        $days = max(1, CarbonImmutable::now()->startOfDay()->diffInDays($target->startOfDay(), false));

        return max(1, (int) ceil($days / 30.42));
    }

    private function alternativeDate(float $remaining, float $monthlyCapacity): string
    {
        if ($remaining <= 0) {
            return 'Objectif déjà atteint';
        }

        if ($monthlyCapacity <= 0) {
            return 'Non estimable sans capacité mensuelle';
        }

        return CarbonImmutable::now()->addMonths((int) ceil($remaining / $monthlyCapacity))->translatedFormat('d/m/Y');
    }

    private function explanation(string $feasibility, float $remaining, int $months, float $monthlyCapacity): string
    {
        return 'Selon les données renseignées, cet objectif semble '.$feasibility.'. Il reste '
            .VerdictBuilder::money($remaining).' à épargner sur environ '.$months
            .' mois, avec une capacité mensuelle estimée à '.VerdictBuilder::money($monthlyCapacity).'.';
    }

    /**
     * @return array<int, string>
     */
    private function recommendations(string $feasibility, string $alternativeDate): array
    {
        if ($feasibility === 'irréaliste') {
            return [
                'Allonger le délai cible ou réduire le montant visé.',
                'Utiliser la date alternative comme repère plus prudent : '.$alternativeDate.'.',
                'Éviter de sacrifier toute la marge de sécurité pour atteindre cet objectif.',
            ];
        }

        if ($feasibility === 'ambitieux') {
            return [
                'Prévoir un montant mensuel légèrement plus bas si le budget varie.',
                'Suivre l’objectif une fois par mois pour éviter une pression excessive.',
                'Garder une marge pour les dépenses imprévues.',
            ];
        }

        return [
            'Automatiser le montant si le budget reste stable.',
            'Garder l’objectif séparé de la marge de sécurité.',
            'Réévaluer la date cible en cas de dépense imprévue.',
        ];
    }

    private function summary(string $name, float $remaining, float $monthlyNeeded, string $feasibility): string
    {
        return "Résumé DécisionClaire :\nObjectif : {$name}\nReste à épargner : ".VerdictBuilder::money($remaining)
            ."\nPar mois : ".VerdictBuilder::money($monthlyNeeded)
            ."\nVerdict : objectif {$feasibility}\nConseil : garder une marge de sécurité réaliste.";
    }

    /**
     * @param  array<string, mixed>  $input
     */
    private function confidence(array $input): int
    {
        $keys = ['goal_name', 'target_amount', 'current_savings', 'target_date', 'monthly_capacity'];
        $filled = count(array_filter($keys, fn (string $key): bool => isset($input[$key]) && $input[$key] !== ''));

        return VerdictBuilder::clampScore(55 + ($filled / count($keys)) * 35 + ($this->amount($input, 'current_living_balance') > 0 ? 10 : 0));
    }

    /**
     * @param  array<string, mixed>  $input
     */
    private function amount(array $input, string $key): float
    {
        return max(0.0, (float) ($input[$key] ?? 0));
    }
}
