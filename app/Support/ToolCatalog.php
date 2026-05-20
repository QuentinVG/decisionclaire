<?php

namespace App\Support;

final class ToolCatalog
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public static function all(): array
    {
        return [
            'living_balance' => [
                'key' => 'living_balance',
                'slug' => 'reste-a-vivre',
                'route' => 'tools.living-balance.show',
                'calculate_route' => 'tools.living-balance.calculate',
                'name' => 'Combien il me reste ?',
                'short' => 'Calcule ton reste à vivre mensuel, hebdo et journalier.',
                'h1' => 'Calculateur de reste à vivre gratuit',
                'seo_title' => 'Calculateur de reste à vivre gratuit | DécisionClaire',
                'meta' => 'Estime ton reste à vivre mensuel, hebdomadaire et journalier avec un calcul simple, gratuit et sans compte obligatoire.',
                'form' => 'tools.forms.living-balance',
                'badge' => 'Budget quotidien',
                'intro' => 'Renseigne tes revenus, charges et épargne prévue. Le résultat t’aide à voir ce qu’il reste vraiment pour les dépenses courantes.',
                'faq' => [
                    ['q' => 'Le reste à vivre est-il un conseil financier ?', 'a' => 'Non. C’est une estimation indicative basée sur les montants renseignés.'],
                    ['q' => 'Faut-il créer un compte ?', 'a' => 'Non. Le compte sert uniquement à sauvegarder une simulation.'],
                ],
            ],
            'purchase_decision' => [
                'key' => 'purchase_decision',
                'slug' => 'jachete-ou-pas',
                'route' => 'tools.purchase-decision.show',
                'calculate_route' => 'tools.purchase-decision.calculate',
                'name' => 'J’achète ou pas ?',
                'short' => 'Évalue si un achat semble raisonnable, limite ou risqué.',
                'h1' => 'Est-ce que je peux me permettre cet achat ?',
                'seo_title' => 'Est-ce que je peux me permettre cet achat ? | DécisionClaire',
                'meta' => 'Évalue rapidement si un achat semble raisonnable selon ton budget, ton épargne, son utilité et son urgence.',
                'form' => 'tools.forms.purchase-decision',
                'badge' => 'Outil phare',
                'intro' => 'Cet outil ne dit jamais quoi faire de façon absolue. Il met en évidence l’impact probable de l’achat et propose une décision prudente.',
                'faq' => [
                    ['q' => 'Le résultat donne-t-il un oui absolu ?', 'a' => 'Non. Le verdict reste prudent : raisonnable, limite, impulsif probable ou risqué selon les données.'],
                    ['q' => 'Pourquoi attendre 48h ?', 'a' => 'C’est une recommandation simple quand l’urgence est faible ou que l’impact budgétaire est élevé.'],
                ],
            ],
            'large_purchase_impact' => [
                'key' => 'large_purchase_impact',
                'slug' => 'impact-gros-achat',
                'route' => 'tools.large-purchase-impact.show',
                'calculate_route' => 'tools.large-purchase-impact.calculate',
                'name' => 'Impact gros achat',
                'short' => 'Simule l’effet d’un achat important sur les prochains mois.',
                'h1' => 'Calculer l’impact d’un gros achat',
                'seo_title' => 'Calculer l’impact d’un gros achat | DécisionClaire',
                'meta' => 'Simule l’effet d’un gros achat sur ton épargne, ton reste à vivre et ta marge de sécurité.',
                'form' => 'tools.forms.large-purchase-impact',
                'badge' => 'Projection simple',
                'intro' => 'Idéal pour comprendre les conséquences après l’achat : épargne restante, mois sensibles et délai de reconstitution.',
                'faq' => [
                    ['q' => 'Quelle différence avec J’achète ou pas ?', 'a' => 'Ici, l’objectif est de mesurer les conséquences sur plusieurs mois, pas seulement de donner un verdict rapide.'],
                    ['q' => 'Les mensualités sont-elles un crédit ?', 'a' => 'Non. L’outil simule seulement un impact mensuel déclaré, sans proposer ni comparer de financement.'],
                ],
            ],
            'savings_goal' => [
                'key' => 'savings_goal',
                'slug' => 'objectif-epargne',
                'route' => 'tools.savings-goal.show',
                'calculate_route' => 'tools.savings-goal.calculate',
                'name' => 'Objectif épargne',
                'short' => 'Calcule l’effort mensuel pour atteindre un objectif.',
                'h1' => 'Calculateur d’objectif épargne',
                'seo_title' => 'Calculateur d’objectif épargne | DécisionClaire',
                'meta' => 'Estime combien épargner chaque mois ou chaque semaine pour atteindre un objectif à une date donnée.',
                'form' => 'tools.forms.savings-goal',
                'badge' => 'Objectif réaliste',
                'intro' => 'DécisionClaire compare l’effort nécessaire à ta capacité estimée pour classer l’objectif comme facile, réaliste, ambitieux ou irréaliste.',
                'faq' => [
                    ['q' => 'Que faire si l’objectif est irréaliste ?', 'a' => 'Le résultat propose une date plus réaliste ou une baisse du montant cible.'],
                    ['q' => 'Puis-je laisser certains champs vides ?', 'a' => 'Oui, les champs avancés sont optionnels et le calcul reste prudent.'],
                ],
            ],
            'subscription_audit' => [
                'key' => 'subscription_audit',
                'slug' => 'abonnements',
                'route' => 'tools.subscription-audit.show',
                'calculate_route' => 'tools.subscription-audit.calculate',
                'name' => 'Abonnements inutiles',
                'short' => 'Repère les abonnements peu utiles et l’économie possible.',
                'h1' => 'Calculateur du coût annuel de vos abonnements',
                'seo_title' => 'Calculateur du coût annuel de vos abonnements | DécisionClaire',
                'meta' => 'Calcule le coût mensuel et annuel de tes abonnements et repère ceux qui semblent peu utiles.',
                'form' => 'tools.forms.subscription-audit',
                'badge' => 'Économie possible',
                'intro' => 'Liste tes abonnements, leur usage et leur importance. L’outil classe les résiliations à envisager en priorité.',
                'faq' => [
                    ['q' => 'L’outil résilie-t-il les abonnements ?', 'a' => 'Non. Il indique seulement une priorité indicative à vérifier manuellement.'],
                    ['q' => 'Dois-je renseigner tous mes abonnements ?', 'a' => 'Non. Commence par les plus chers ou ceux que tu utilises peu.'],
                ],
            ],
            'scenario_comparator' => [
                'key' => 'scenario_comparator',
                'slug' => 'comparateur-scenarios',
                'route' => 'tools.scenario-comparator.show',
                'calculate_route' => 'tools.scenario-comparator.calculate',
                'name' => 'Comparateur de scénarios',
                'short' => 'Compare deux à quatre options simples sans tableur.',
                'h1' => 'Comparateur de scénarios financiers simples',
                'seo_title' => 'Comparateur de scénarios financiers simples | DécisionClaire',
                'meta' => 'Compare deux à quatre options financières simples : coût total, flexibilité, risque et recommandation prudente.',
                'form' => 'tools.forms.scenario-comparator',
                'badge' => 'Choix comparé',
                'intro' => 'Choisis un template concret puis compare les coûts, la flexibilité, le risque et l’impact sur ton épargne.',
                'faq' => [
                    ['q' => 'Puis-je comparer plus de quatre options ?', 'a' => 'Non, volontairement. Au-delà, la décision devient moins lisible.'],
                    ['q' => 'Le scénario recommandé est-il obligatoire ?', 'a' => 'Non. Il s’agit d’un repère prudent à confronter à ta situation réelle.'],
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function get(string $key): array
    {
        return self::all()[$key] ?? [];
    }

    /**
     * @return array<string, mixed>
     */
    public static function bySlug(string $slug): array
    {
        foreach (self::all() as $tool) {
            if ($tool['slug'] === $slug) {
                return $tool;
            }
        }

        return [];
    }
}
