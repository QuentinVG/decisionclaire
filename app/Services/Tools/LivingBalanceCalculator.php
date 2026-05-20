<?php

namespace App\Services\Tools;

use App\Services\Verdict\VerdictBuilder;

final class LivingBalanceCalculator
{
    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    public function calculate(array $input): array
    {
        $income = $this->calculateMonthlyIncome($input);
        $expenses = $this->calculateMonthlyExpenses($input);
        $plannedSavings = $this->amount($input, 'planned_savings');
        $available = $this->calculateAvailableMonthly($input);
        $weekly = $this->calculateWeeklyAvailable($input);
        $daily = $this->calculateDailyAvailable($input);
        $comfort = $this->calculateComfortLevel($input);
        $confidence = $this->calculateConfidenceScore($input);
        $expenseRate = $income > 0 ? ($expenses / $income) * 100 : 100;
        $savingsRate = $income > 0 ? ($plannedSavings / $income) * 100 : 0;

        return VerdictBuilder::result(
            'living_balance',
            'Combien il me reste ?',
            'Reste à vivre mensuel',
            $this->generateVerdict($input),
            'Reste à vivre mensuel',
            VerdictBuilder::money($available),
            $comfort,
            $confidence,
            $this->explanation($comfort, $available, $daily),
            $this->generateRecommendations($input),
            $this->summary($available, $weekly, $daily, $comfort),
            [
                ['label' => 'Revenus totaux', 'value' => VerdictBuilder::money($income)],
                ['label' => 'Dépenses fixes estimées', 'value' => VerdictBuilder::money($expenses)],
                ['label' => 'Épargne prévue', 'value' => VerdictBuilder::money($plannedSavings)],
                ['label' => 'Reste hebdomadaire', 'value' => VerdictBuilder::money($weekly)],
                ['label' => 'Reste journalier', 'value' => VerdictBuilder::decimalMoney($daily)],
                ['label' => 'Taux de dépenses fixes', 'value' => VerdictBuilder::percent($expenseRate)],
                ['label' => 'Taux d’épargne', 'value' => VerdictBuilder::percent($savingsRate)],
            ],
        );
    }

    /**
     * @param  array<string, mixed>  $input
     */
    public function calculateMonthlyIncome(array $input): float
    {
        return $this->amount($input, 'monthly_income')
            + $this->amount($input, 'aids')
            + $this->amount($input, 'other_income');
    }

    /**
     * @param  array<string, mixed>  $input
     */
    public function calculateMonthlyExpenses(array $input): float
    {
        $keys = [
            'fixed_charges',
            'groceries',
            'transport',
            'subscriptions',
            'debts',
            'energy',
            'insurance',
            'phone_internet',
            'family_expenses',
            'other_charges',
            'security_margin',
        ];

        return array_reduce($keys, fn (float $total, string $key): float => $total + $this->amount($input, $key), 0.0);
    }

    /**
     * @param  array<string, mixed>  $input
     */
    public function calculateAvailableMonthly(array $input): float
    {
        return $this->calculateMonthlyIncome($input)
            - $this->calculateMonthlyExpenses($input)
            - $this->amount($input, 'planned_savings');
    }

    /**
     * @param  array<string, mixed>  $input
     */
    public function calculateWeeklyAvailable(array $input): float
    {
        return $this->calculateAvailableMonthly($input) / 4.345;
    }

    /**
     * @param  array<string, mixed>  $input
     */
    public function calculateDailyAvailable(array $input): float
    {
        return $this->calculateAvailableMonthly($input) / 30.42;
    }

    /**
     * @param  array<string, mixed>  $input
     */
    public function calculateComfortLevel(array $input): string
    {
        $income = $this->calculateMonthlyIncome($input);
        $available = $this->calculateAvailableMonthly($input);
        $daily = $this->calculateDailyAvailable($input);
        $expenseRate = $income > 0 ? ($this->calculateMonthlyExpenses($input) / $income) : 1.0;
        $availableRate = $income > 0 ? ($available / $income) : -1.0;

        if ($income <= 0 || $available <= 0 || $expenseRate >= 0.9 || $daily < 10) {
            return 'risqué';
        }

        if ($availableRate < 0.2 || $daily < 25) {
            return 'limite';
        }

        if ($availableRate < 0.35 || $daily < 45) {
            return 'correct';
        }

        return 'confortable';
    }

    /**
     * @param  array<string, mixed>  $input
     */
    public function calculateConfidenceScore(array $input): int
    {
        $importantKeys = ['monthly_income', 'fixed_charges', 'groceries', 'transport', 'subscriptions', 'debts', 'planned_savings'];
        $advancedKeys = ['aids', 'other_income', 'energy', 'insurance', 'phone_internet', 'family_expenses', 'other_charges', 'security_margin'];

        $filledImportant = $this->filledCount($input, $importantKeys);
        $filledAdvanced = $this->filledCount($input, $advancedKeys);

        return VerdictBuilder::clampScore(45 + ($filledImportant / count($importantKeys)) * 40 + min(15, $filledAdvanced * 2));
    }

    /**
     * @param  array<string, mixed>  $input
     */
    public function generateVerdict(array $input): string
    {
        return match ($this->calculateComfortLevel($input)) {
            'confortable' => 'Budget confortable',
            'correct' => 'Budget correct',
            'limite' => 'Budget limite',
            default => 'Budget risqué',
        };
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<int, string>
     */
    public function generateRecommendations(array $input): array
    {
        $comfort = $this->calculateComfortLevel($input);
        $available = $this->calculateAvailableMonthly($input);
        $recommendations = [];

        if ($comfort === 'risqué') {
            $recommendations[] = 'Revoir en priorité les charges fixes et les abonnements avant d’ajouter une nouvelle dépense.';
            $recommendations[] = 'Garder une marge de sécurité minimale avant de valider un achat non urgent.';
        } elseif ($comfort === 'limite') {
            $recommendations[] = 'Prévoir une petite marge pour les dépenses oubliées ou irrégulières.';
            $recommendations[] = 'Décaler les achats non urgents si le reste journalier te semble trop serré.';
        } else {
            $recommendations[] = 'Conserver cette marge plutôt que l’utiliser entièrement chaque mois.';
            $recommendations[] = 'Automatiser une épargne réaliste si ton reste à vivre reste stable.';
        }

        if ($available < $this->amount($input, 'planned_savings')) {
            $recommendations[] = 'Ajuster l’épargne prévue si elle rend le quotidien trop tendu.';
        }

        return array_slice($recommendations, 0, 3);
    }

    private function explanation(string $comfort, float $available, float $daily): string
    {
        return "Selon les données renseignées, ton budget semble {$comfort}. Le reste disponible est estimé à "
            .VerdictBuilder::money($available).' par mois, soit environ '.VerdictBuilder::decimalMoney($daily).' par jour.';
    }

    private function summary(float $available, float $weekly, float $daily, string $comfort): string
    {
        return "Résumé DécisionClaire :\nReste à vivre : ".VerdictBuilder::money($available)
            ."\nPar semaine : ".VerdictBuilder::money($weekly)
            ."\nPar jour : ".VerdictBuilder::decimalMoney($daily)
            ."\nVerdict : budget {$comfort}\nConseil : garder une marge avant toute dépense non urgente.";
    }

    /**
     * @param  array<string, mixed>  $input
     */
    private function amount(array $input, string $key): float
    {
        return max(0.0, (float) ($input[$key] ?? 0));
    }

    /**
     * @param  array<string, mixed>  $input
     * @param  array<int, string>  $keys
     */
    private function filledCount(array $input, array $keys): int
    {
        return count(array_filter($keys, fn (string $key): bool => isset($input[$key]) && $input[$key] !== '' && (float) $input[$key] > 0));
    }
}
