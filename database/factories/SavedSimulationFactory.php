<?php

namespace Database\Factories;

use App\Models\SavedSimulation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SavedSimulation>
 */
class SavedSimulationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'tool_key' => 'purchase_decision',
            'title' => 'Simulation test',
            'input_data' => ['purchase_name' => 'Test', 'price' => 100],
            'result_data' => [
                'tool_name' => 'J’achète ou pas ?',
                'title' => 'Test',
                'verdict' => 'achat raisonnable',
                'primary_label' => 'Impact',
                'primary_value' => '10 %',
                'risk_level' => 'faible',
                'confidence_score' => 80,
                'explanation' => 'Simulation test.',
                'recommendations' => ['Vérifier la marge de sécurité.'],
                'summary' => 'Résumé DécisionClaire : test',
                'notice' => 'Estimation indicative, ne remplace pas un conseil financier professionnel.',
            ],
        ];
    }
}
