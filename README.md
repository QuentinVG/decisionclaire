# DécisionClaire

DécisionClaire est une application Laravel qui propose des outils gratuits pour prendre de meilleures décisions d’argent au quotidien.

Promesse : **Calcule vite, comprends clairement, décide calmement.**

Slogan : **Des outils gratuits pour éviter les mauvaises décisions d’argent.**

DécisionClaire n’est pas une banque, pas une application de comptabilité, pas un agrégateur bancaire, pas un conseiller financier, pas un outil immobilier et ne contient aucune fonctionnalité IA.

## Fonctionnalités

- Landing page publique orientée besoins utilisateur.
- 6 outils publics sans compte obligatoire :
  - Combien il me reste ?
  - J’achète ou pas ?
  - Impact gros achat
  - Objectif épargne
  - Abonnements inutiles
  - Comparateur de scénarios
- Raccourcis guidés, profils prudents et choix préremplis pour éviter de devoir connaître toutes ses charges avant de commencer.
- Authentification optionnelle via Laravel Breeze.
- Sauvegarde des simulations pour les utilisateurs connectés.
- Dashboard simple avec historique, duplication, suppression, relance et export PDF.
- Résumé copiable pour chaque résultat.
- Pages publiques SEO-friendly avec FAQ courte.
- Seeders de démonstration.
- Tests unitaires et feature.
- CI GitHub Actions.
- Vitrine statique compatible GitHub Pages dans `docs/index.html`.

Chaque résultat affiche un verdict, un chiffre principal, une explication, un niveau de risque, un score de confiance, des recommandations concrètes et la mention :

> Estimation indicative, ne remplace pas un conseil financier professionnel.

## Stack

- PHP 8.3+
- Laravel 13
- Blade
- Tailwind CSS
- Alpine.js
- SQLite local par défaut
- Laravel Breeze
- PHPUnit
- DomPDF
- Laravel Pint
- Larastan / PHPStan
- Docker / docker-compose
- GitHub Actions

## Installation locale

Composer n’est pas forcément disponible globalement sur cette machine. Dans cet environnement, il existe sous `C:\Users\Quentin\bin\composer.phar`.

```bash
php C:\Users\Quentin\bin\composer.phar install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan serve
```

Ouvrir ensuite `http://127.0.0.1:8000`.

## Compte démo

- Email : `demo@decisionclaire.test`
- Mot de passe : `password`

Le seeder crée des simulations sauvegardées : reste à vivre étudiant, achat téléphone, achat PC, objectif vacances, audit abonnements et comparaison neuf vs occasion.

## Docker

```bash
docker compose up --build
```

L’application sera disponible sur `http://localhost:8000`. Le service Node expose Vite sur `http://localhost:5173`.

## Tests et qualité

```bash
php artisan test
vendor/bin/pint --test
vendor/bin/phpstan analyse --memory-limit=1G
npm run build
```

Pour corriger le style :

```bash
vendor/bin/pint
```

## GitHub Pages

GitHub Pages ne peut pas héberger directement une application Laravel dynamique avec backend PHP.

Le dossier `docs/` contient donc une vitrine statique réaliste :

- présentation du produit ;
- fonctionnalités ;
- stack ;
- instructions de lancement local ;
- avertissement clair que l’application complète nécessite Laravel.

Le workflow `.github/workflows/pages.yml` publie `docs/` sur GitHub Pages.

## Limites assumées

- Aucun paiement réel.
- Aucun compte bancaire connecté.
- Aucune API IA.
- Aucun scraping.
- Aucun module immobilier.
- Pas de conseil financier personnalisé.
- Les calculs sont indicatifs et reposent sur les données saisies par l’utilisateur.

## Roadmap documentée

- Architecture premium sans paiement réel dans la version initiale.
- Exports PDF avancés.
- Mode couple / famille.
- Partage public temporaire.
- Graphiques avancés.
- PWA.
- Rappels.
- Bibliothèque de conseils.
- Traduction anglaise.
- Thèmes visuels.
- Tests A/B SEO.

## Déploiement

Pour une vraie démo dynamique, utiliser un hébergeur PHP compatible Laravel : serveur VPS, PaaS Laravel, Render, Fly.io, Laravel Forge, etc.

GitHub Pages sert uniquement de vitrine statique.

## Sécurité

- CSRF Laravel actif.
- Validation via Form Requests.
- Échappement Blade par défaut.
- Policies sur les simulations sauvegardées.
- Pas de stockage de données bancaires.
- Pas de clé secrète commitée.

Voir `docs/security.md`.
