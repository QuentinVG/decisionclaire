<?php

namespace Tests\Unit;

use App\Services\Tools\LargePurchaseImpactCalculator;
use App\Services\Tools\LivingBalanceCalculator;
use App\Services\Tools\PurchaseDecisionCalculator;
use App\Services\Tools\SavingsGoalCalculator;
use App\Services\Tools\ScenarioComparator;
use App\Services\Tools\SubscriptionAuditCalculator;
use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;

class ToolCalculatorsTest extends TestCase
{
    public function test_living_balance_monthly_weekly_and_daily_are_calculated(): void
    {
        $calculator = new LivingBalanceCalculator;
        $input = $this->livingInput();

        $this->assertSame(2500.0, $calculator->calculateMonthlyIncome($input));
        $this->assertSame(900.0, $calculator->calculateMonthlyExpenses($input));
        $this->assertSame(1400.0, $calculator->calculateAvailableMonthly($input));
        $this->assertGreaterThan(320, $calculator->calculateWeeklyAvailable($input));
        $this->assertGreaterThan(45, $calculator->calculateDailyAvailable($input));
    }

    public function test_living_balance_comfort_levels_cover_main_cases(): void
    {
        $calculator = new LivingBalanceCalculator;

        $this->assertSame('confortable', $calculator->calculateComfortLevel($this->livingInput()));
        $this->assertSame('correct', $calculator->calculateComfortLevel($this->livingInput(['fixed_charges' => 1000])));
        $this->assertSame('limite', $calculator->calculateComfortLevel($this->livingInput(['fixed_charges' => 1300])));
        $this->assertSame('risqué', $calculator->calculateComfortLevel($this->livingInput(['fixed_charges' => 2300])));
    }

    public function test_living_balance_confidence_increases_with_filled_fields(): void
    {
        $calculator = new LivingBalanceCalculator;

        $low = $calculator->calculateConfidenceScore(['monthly_income' => 2000]);
        $high = $calculator->calculateConfidenceScore($this->livingInput(['energy' => 80, 'insurance' => 35]));

        $this->assertGreaterThan($low, $high);
    }

    public function test_purchase_can_be_reasonable(): void
    {
        $result = (new PurchaseDecisionCalculator)->calculate($this->purchaseInput([
            'price' => 120,
            'available_monthly' => 900,
            'available_savings' => 3000,
            'utility' => 'forte',
            'urgency' => 'forte',
        ]));

        $this->assertSame('achat raisonnable', $result['verdict']);
    }

    public function test_purchase_can_be_risky(): void
    {
        $result = (new PurchaseDecisionCalculator)->calculate($this->purchaseInput([
            'price' => 1400,
            'available_monthly' => 350,
            'available_savings' => 1000,
            'minimum_savings' => 700,
        ]));

        $this->assertContains($result['verdict'], ['achat risqué', 'à éviter pour l’instant']);
    }

    public function test_purchase_can_be_probably_impulsive(): void
    {
        $result = (new PurchaseDecisionCalculator)->calculate($this->purchaseInput([
            'price' => 250,
            'available_monthly' => 700,
            'available_savings' => 2000,
            'urgency' => 'faible',
            'utility' => 'faible',
            'cheaper_alternative' => true,
        ]));

        $this->assertSame('achat impulsif probable', $result['verdict']);
    }

    public function test_large_purchase_detects_security_threshold_risk(): void
    {
        $result = (new LargePurchaseImpactCalculator)->calculate([
            'purchase_name' => 'PC',
            'total_price' => 1600,
            'payment_mode' => 'comptant',
            'monthly_income' => 2100,
            'monthly_charges' => 2200,
            'current_savings' => 1700,
            'minimum_savings' => 800,
        ]);

        $this->assertSame('risqué', $result['risk_level']);
    }

    public function test_savings_goal_can_be_realistic(): void
    {
        CarbonImmutable::setTestNow('2026-05-20');

        $result = (new SavingsGoalCalculator)->calculate([
            'goal_name' => 'Vacances',
            'target_amount' => 1000,
            'current_savings' => 200,
            'target_date' => '2027-01-20',
            'monthly_capacity' => 120,
        ]);

        $this->assertSame('Objectif réaliste', $result['verdict']);
        CarbonImmutable::setTestNow();
    }

    public function test_savings_goal_can_be_unrealistic(): void
    {
        CarbonImmutable::setTestNow('2026-05-20');

        $result = (new SavingsGoalCalculator)->calculate([
            'goal_name' => 'Gros objectif',
            'target_amount' => 5000,
            'current_savings' => 0,
            'target_date' => '2026-08-20',
            'monthly_capacity' => 100,
        ]);

        $this->assertSame('Objectif irréaliste', $result['verdict']);
        CarbonImmutable::setTestNow();
    }

    public function test_subscription_audit_calculates_annual_cost_and_possible_savings(): void
    {
        $result = (new SubscriptionAuditCalculator)->calculate([
            'subscriptions' => [
                ['name' => 'Streaming', 'price' => 15, 'usage' => 'rarement', 'importance' => 'faible', 'duplicate' => true, 'commitment' => false, 'cancellable' => true],
                ['name' => 'Musique', 'price' => 10, 'usage' => 'quotidiennement', 'importance' => 'forte', 'duplicate' => false, 'commitment' => false, 'cancellable' => true],
            ],
        ]);

        $this->assertSame('15 €', $result['primary_value']);
        $this->assertStringContainsString('300 €', $result['summary']);
    }

    public function test_scenario_comparator_recommends_a_prudent_option(): void
    {
        $result = (new ScenarioComparator)->calculate([
            'template' => 'neuf_vs_occasion',
            'scenarios' => [
                ['name' => 'Neuf', 'initial_cost' => 900, 'monthly_cost' => 0, 'duration' => 12, 'utility' => 'forte', 'flexibility' => 'moyenne', 'risk' => 'moyen', 'savings_impact' => 900],
                ['name' => 'Occasion', 'initial_cost' => 450, 'monthly_cost' => 0, 'duration' => 12, 'utility' => 'moyenne', 'flexibility' => 'forte', 'risk' => 'faible', 'savings_impact' => 450],
            ],
        ]);

        $this->assertStringContainsString('Occasion', $result['verdict']);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function livingInput(array $overrides = []): array
    {
        return array_merge([
            'monthly_income' => 2500,
            'fixed_charges' => 450,
            'groceries' => 300,
            'transport' => 80,
            'subscriptions' => 40,
            'debts' => 30,
            'planned_savings' => 200,
        ], $overrides);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function purchaseInput(array $overrides = []): array
    {
        return array_merge([
            'purchase_name' => 'Achat test',
            'price' => 300,
            'available_monthly' => 800,
            'available_savings' => 2000,
            'minimum_savings' => 500,
            'urgency' => 'moyenne',
            'utility' => 'moyenne',
            'usage_frequency' => 'hebdo',
            'usage_duration_months' => 24,
            'payment_type' => 'comptant',
            'planned_timing' => 'maintenant',
        ], $overrides);
    }
}
