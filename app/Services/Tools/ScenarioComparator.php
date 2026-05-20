<?php

namespace App\Services\Tools;

use App\Services\Verdict\VerdictBuilder;

final class ScenarioComparator
{
    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    public function calculate(array $input): array
    {
        $scenarios = $this->scenarios($input);
        $evaluated = $this->evaluate($scenarios);
        $cheapest = $this->bestBy($evaluated, 'total_cost', false);
        $flexible = $this->bestBy($evaluated, 'flexibility_score', true);
        $leastRisky = $this->bestBy($evaluated, 'risk_score', false);
        $recommended = $this->bestBy($evaluated, 'decision_score', true);
        $template = (string) ($input['template'] ?? 'Comparaison simple');

        return VerdictBuilder::result(
            'scenario_comparator',
            'Comparateur de scénarios',
            $template,
            'Scénario recommandé : '.$recommended['name'],
            'Option la moins chère',
            $cheapest['name'].' - '.VerdictBuilder::money($cheapest['total_cost']),
            $recommended['risk_score'] >= 70 ? 'risqué' : ($recommended['risk_score'] >= 45 ? 'limite' : 'faible'),
            $this->confidence($evaluated),
            $this->explanation($recommended, $cheapest, $leastRisky),
            $this->recommendations($recommended, $cheapest, $leastRisky),
            $this->summary($recommended, $cheapest, $leastRisky),
            [
                ['label' => 'Scénario le moins cher', 'value' => $cheapest['name'].' ('.VerdictBuilder::money($cheapest['total_cost']).')'],
                ['label' => 'Scénario le plus flexible', 'value' => $flexible['name']],
                ['label' => 'Scénario le moins risqué', 'value' => $leastRisky['name']],
                ['label' => 'Tableau comparatif', 'value' => $this->tableSummary($evaluated)],
            ],
        );
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<int, array{name:string,initial_cost:float,monthly_cost:float,duration:int,utility:string,flexibility:string,risk:string,savings_impact:float,comment:string}>
     */
    private function scenarios(array $input): array
    {
        $rows = is_array($input['scenarios'] ?? null) ? $input['scenarios'] : [];
        $scenarios = [];

        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }

            $name = trim((string) ($row['name'] ?? ''));

            if ($name === '') {
                continue;
            }

            $scenarios[] = [
                'name' => $name,
                'initial_cost' => max(0.0, (float) ($row['initial_cost'] ?? 0)),
                'monthly_cost' => max(0.0, (float) ($row['monthly_cost'] ?? 0)),
                'duration' => max(1, (int) ($row['duration'] ?? 1)),
                'utility' => (string) ($row['utility'] ?? 'moyenne'),
                'flexibility' => (string) ($row['flexibility'] ?? 'moyenne'),
                'risk' => (string) ($row['risk'] ?? 'moyen'),
                'savings_impact' => max(0.0, (float) ($row['savings_impact'] ?? 0)),
                'comment' => trim((string) ($row['comment'] ?? '')),
            ];
        }

        return $scenarios;
    }

    /**
     * @param  array<int, array{name:string,initial_cost:float,monthly_cost:float,duration:int,utility:string,flexibility:string,risk:string,savings_impact:float,comment:string}>  $scenarios
     * @return array<int, array<string, mixed>>
     */
    private function evaluate(array $scenarios): array
    {
        $maxCost = max(1.0, max(array_map(fn (array $scenario): float => $scenario['initial_cost'] + ($scenario['monthly_cost'] * $scenario['duration']), $scenarios)));

        return array_map(function (array $scenario) use ($maxCost): array {
            $totalCost = $scenario['initial_cost'] + ($scenario['monthly_cost'] * $scenario['duration']);
            $costPenalty = ($totalCost / $maxCost) * 35;
            $utilityScore = $this->levelScore($scenario['utility']);
            $flexibilityScore = $this->levelScore($scenario['flexibility']);
            $riskScore = $this->riskScore($scenario['risk']);
            $impactPenalty = min(15, $scenario['savings_impact'] / 10);
            $decisionScore = 65 - $costPenalty + ($utilityScore * 0.2) + ($flexibilityScore * 0.15) - ($riskScore * 0.2) - $impactPenalty;

            return $scenario + [
                'total_cost' => $totalCost,
                'utility_score' => $utilityScore,
                'flexibility_score' => $flexibilityScore,
                'risk_score' => $riskScore,
                'decision_score' => VerdictBuilder::clampScore($decisionScore),
            ];
        }, $scenarios);
    }

    /**
     * @param  array<int, array<string, mixed>>  $evaluated
     * @return array<string, mixed>
     */
    private function bestBy(array $evaluated, string $key, bool $higherIsBetter): array
    {
        usort($evaluated, function (array $a, array $b) use ($key, $higherIsBetter): int {
            $left = (float) $a[$key];
            $right = (float) $b[$key];

            return $higherIsBetter ? $right <=> $left : $left <=> $right;
        });

        return $evaluated[0];
    }

    /**
     * @param  array<string, mixed>  $recommended
     * @param  array<string, mixed>  $cheapest
     * @param  array<string, mixed>  $leastRisky
     */
    private function explanation(array $recommended, array $cheapest, array $leastRisky): string
    {
        return 'Selon les données renseignées, '.$recommended['name'].' ressort comme scénario recommandé. '
            .'Le moins cher est '.$cheapest['name'].', tandis que le moins risqué est '.$leastRisky['name']
            .'. Le choix final doit rester prudent si le coût pèse sur ton épargne.';
    }

    /**
     * @param  array<string, mixed>  $recommended
     * @param  array<string, mixed>  $cheapest
     * @param  array<string, mixed>  $leastRisky
     * @return array<int, string>
     */
    private function recommendations(array $recommended, array $cheapest, array $leastRisky): array
    {
        $recommendations = [
            'Comparer le scénario recommandé avec le moins cher avant de décider.',
            'Privilégier le scénario le moins risqué si ta marge de sécurité est faible.',
        ];

        if ($recommended['name'] !== $cheapest['name']) {
            $recommendations[] = 'Vérifier que le confort supplémentaire de '.$recommended['name'].' justifie l’écart de coût.';
        }

        if ($recommended['name'] !== $leastRisky['name']) {
            $recommendations[] = 'Garder '.$leastRisky['name'].' comme option prudente.';
        }

        return array_slice($recommendations, 0, 3);
    }

    /**
     * @param  array<int, array<string, mixed>>  $evaluated
     */
    private function tableSummary(array $evaluated): string
    {
        return implode(' | ', array_map(
            fn (array $scenario): string => $scenario['name'].' : '.VerdictBuilder::money((float) $scenario['total_cost']).', score '.$scenario['decision_score'].'/100',
            $evaluated
        ));
    }

    /**
     * @param  array<string, mixed>  $recommended
     * @param  array<string, mixed>  $cheapest
     * @param  array<string, mixed>  $leastRisky
     */
    private function summary(array $recommended, array $cheapest, array $leastRisky): string
    {
        return "Résumé DécisionClaire :\nScénario recommandé : {$recommended['name']}"
            ."\nMoins cher : {$cheapest['name']}"
            ."\nMoins risqué : {$leastRisky['name']}"
            ."\nConseil : choisir l’option qui garde une marge de sécurité suffisante.";
    }

    /**
     * @param  array<int, array<string, mixed>>  $evaluated
     */
    private function confidence(array $evaluated): int
    {
        return VerdictBuilder::clampScore(55 + min(35, count($evaluated) * 10));
    }

    private function levelScore(string $level): int
    {
        return match ($level) {
            'faible' => 30,
            'forte' => 85,
            default => 55,
        };
    }

    private function riskScore(string $risk): int
    {
        return match ($risk) {
            'faible' => 25,
            'élevé' => 85,
            default => 55,
        };
    }
}
