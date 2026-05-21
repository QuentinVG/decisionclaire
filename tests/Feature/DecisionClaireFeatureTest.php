<?php

namespace Tests\Feature;

use App\Models\SavedSimulation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DecisionClaireFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_trust_page_and_seo_files_support_conversion(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Stopper un achat impulsif')
            ->assertSee('Sans banque connectée')
            ->assertSee('Commencer par J’achète ou pas');

        $this->get('/confiance')
            ->assertOk()
            ->assertSee('Confidentialité et limites')
            ->assertSee('Aucune connexion bancaire');

        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('Sitemap: '.rtrim(config('app.url'), '/').'/sitemap.xml');

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee('/outils/jachete-ou-pas')
            ->assertSee('/confiance')
            ->assertSee('<urlset', false);
    }

    public function test_public_tool_pages_are_accessible_and_seo_friendly(): void
    {
        foreach ([
            '/outils/reste-a-vivre' => 'Calculateur de reste à vivre gratuit',
            '/outils/jachete-ou-pas' => 'Est-ce que je peux me permettre cet achat ?',
            '/outils/impact-gros-achat' => 'Calculer l’impact d’un gros achat',
            '/outils/objectif-epargne' => 'Calculateur d’objectif épargne',
            '/outils/abonnements' => 'Calculateur du coût annuel de vos abonnements',
            '/outils/comparateur-scenarios' => 'Comparateur de scénarios financiers simples',
        ] as $uri => $heading) {
            $this->get($uri)
                ->assertOk()
                ->assertSee($heading)
                ->assertSee('Estimation indicative');
        }
    }

    public function test_purchase_result_contains_copyable_summary_and_confidence(): void
    {
        $this->post('/outils/jachete-ou-pas', $this->purchasePayload())
            ->assertOk()
            ->assertSee('Résumé à copier')
            ->assertSee('Confiance du résultat')
            ->assertSee('Feu')
            ->assertSee('Selon les données renseignées');
    }

    public function test_guest_cannot_save_a_simulation(): void
    {
        $this->post('/simulations', ['pending_key' => 'missing'])
            ->assertRedirect('/login');
    }

    public function test_authenticated_user_can_save_a_pending_simulation(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/outils/jachete-ou-pas', $this->purchasePayload())
            ->assertOk()
            ->assertSessionHas('pending_simulations');

        $pendingKey = array_key_first(session('pending_simulations'));

        $this->actingAs($user)
            ->post('/simulations', ['pending_key' => $pendingKey])
            ->assertRedirect();

        $this->assertDatabaseHas('saved_simulations', [
            'user_id' => $user->id,
            'tool_key' => 'purchase_decision',
        ]);
    }

    public function test_user_cannot_view_another_users_simulation(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $simulation = SavedSimulation::factory()->for($owner, 'user')->create();

        $this->actingAs($other)
            ->get(route('simulations.show', $simulation))
            ->assertForbidden();
    }

    public function test_authorized_pdf_export_returns_pdf(): void
    {
        $user = User::factory()->create();
        $simulation = SavedSimulation::factory()->for($user, 'user')->create();

        $this->actingAs($user)
            ->get(route('simulations.export-pdf', $simulation))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_purchase_form_validation_rejects_missing_fields(): void
    {
        $this->post('/outils/jachete-ou-pas', [])
            ->assertSessionHasErrors(['purchase_name', 'price', 'available_monthly']);
    }

    public function test_dashboard_displays_user_simulations(): void
    {
        $user = User::factory()->create();
        SavedSimulation::factory()->for($user, 'user')->create(['title' => 'Achat téléphone']);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Achat téléphone')
            ->assertSee('Simulations sauvegardées');
    }

    /**
     * @return array<string, mixed>
     */
    private function purchasePayload(): array
    {
        return [
            'purchase_name' => 'Casque audio',
            'price' => 180,
            'available_monthly' => 750,
            'available_savings' => 1800,
            'urgency' => 'moyenne',
            'utility' => 'forte',
            'usage_frequency' => 'hebdo',
            'minimum_savings' => 600,
            'usage_duration_months' => 24,
            'cheaper_alternative' => 0,
            'payment_type' => 'comptant',
            'planned_timing' => 'maintenant',
        ];
    }
}
