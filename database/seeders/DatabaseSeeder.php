<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\Tools\LargePurchaseImpactCalculator;
use App\Services\Tools\LivingBalanceCalculator;
use App\Services\Tools\PurchaseDecisionCalculator;
use App\Services\Tools\SavingsGoalCalculator;
use App\Services\Tools\ScenarioComparator;
use App\Services\Tools\SubscriptionAuditCalculator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Compte démo',
            'email' => 'demo@decisionclaire.test',
            'password' => 'password',
        ]);

        $livingInput = [
            'monthly_income' => 1250,
            'fixed_charges' => 420,
            'groceries' => 260,
            'transport' => 55,
            'subscriptions' => 29,
            'debts' => 0,
            'planned_savings' => 80,
            'energy' => 60,
            'insurance' => 22,
            'phone_internet' => 25,
            'security_margin' => 60,
        ];
        $this->save($user, 'living_balance', 'Reste à vivre étudiant', $livingInput, app(LivingBalanceCalculator::class)->calculate($livingInput));

        $phoneInput = [
            'purchase_name' => 'Téléphone',
            'price' => 499,
            'available_monthly' => 520,
            'available_savings' => 1200,
            'urgency' => 'moyenne',
            'utility' => 'forte',
            'usage_frequency' => 'quotidienne',
            'minimum_savings' => 600,
            'usage_duration_months' => 24,
            'cheaper_alternative' => true,
            'alternative_price' => 380,
            'payment_type' => 'comptant',
            'planned_timing' => 'maintenant',
        ];
        $this->save($user, 'purchase_decision', 'Achat téléphone', $phoneInput, app(PurchaseDecisionCalculator::class)->calculate($phoneInput));

        $pcInput = [
            'purchase_name' => 'PC portable',
            'total_price' => 1150,
            'payment_mode' => 'mensualise',
            'monthly_income' => 2100,
            'monthly_charges' => 1370,
            'current_savings' => 1800,
            'minimum_savings' => 900,
            'down_payment' => 400,
            'monthly_payment' => 125,
            'payment_duration' => 6,
            'planned_expenses' => 80,
            'delay_months' => 1,
        ];
        $this->save($user, 'large_purchase_impact', 'Achat PC', $pcInput, app(LargePurchaseImpactCalculator::class)->calculate($pcInput));

        $goalInput = [
            'goal_name' => 'Vacances',
            'target_amount' => 1200,
            'current_savings' => 250,
            'target_date' => now()->addMonths(8)->format('Y-m-d'),
            'monthly_capacity' => 150,
            'priority' => 'moyenne',
            'current_living_balance' => 520,
            'security_margin' => 180,
        ];
        $this->save($user, 'savings_goal', 'Objectif vacances', $goalInput, app(SavingsGoalCalculator::class)->calculate($goalInput));

        $subscriptionsInput = [
            'subscriptions' => [
                ['name' => 'Streaming A', 'price' => 13.99, 'usage' => 'rarement', 'importance' => 'faible', 'duplicate' => true, 'commitment' => false, 'cancellable' => true],
                ['name' => 'Musique', 'price' => 10.99, 'usage' => 'quotidiennement', 'importance' => 'forte', 'duplicate' => false, 'commitment' => false, 'cancellable' => true],
                ['name' => 'Cloud', 'price' => 4.99, 'usage' => 'parfois', 'importance' => 'moyenne', 'duplicate' => false, 'commitment' => false, 'cancellable' => true],
            ],
        ];
        $this->save($user, 'subscription_audit', 'Audit abonnements', $subscriptionsInput, app(SubscriptionAuditCalculator::class)->calculate($subscriptionsInput));

        $scenarioInput = [
            'template' => 'neuf_vs_occasion',
            'scenarios' => [
                ['name' => 'Neuf', 'initial_cost' => 850, 'monthly_cost' => 0, 'duration' => 24, 'utility' => 'forte', 'flexibility' => 'moyenne', 'risk' => 'moyen', 'savings_impact' => 850, 'comment' => 'Garantie complète'],
                ['name' => 'Occasion', 'initial_cost' => 480, 'monthly_cost' => 0, 'duration' => 24, 'utility' => 'moyenne', 'flexibility' => 'forte', 'risk' => 'faible', 'savings_impact' => 480, 'comment' => 'Budget plus prudent'],
            ],
        ];
        $this->save($user, 'scenario_comparator', 'Neuf vs occasion', $scenarioInput, app(ScenarioComparator::class)->calculate($scenarioInput));
    }

    /**
     * @param  array<string, mixed>  $input
     * @param  array<string, mixed>  $result
     */
    private function save(User $user, string $toolKey, string $title, array $input, array $result): void
    {
        $user->savedSimulations()->create([
            'tool_key' => $toolKey,
            'title' => $title,
            'input_data' => $input,
            'result_data' => $result,
        ]);
    }
}
