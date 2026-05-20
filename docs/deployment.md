# Déploiement

DécisionClaire est une application Laravel dynamique. Elle nécessite PHP, Composer, une base de données et un serveur capable d’exécuter Laravel.

## Local

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan serve
```

## Production Laravel

Prévoir :

- PHP 8.3 ou supérieur ;
- extensions PHP usuelles Laravel ;
- SQLite, MySQL ou MariaDB ;
- un `APP_KEY` généré ;
- `APP_DEBUG=false` ;
- `npm run build` ;
- `php artisan migrate --force`.

## GitHub Pages

GitHub Pages ne lance pas PHP. Le workflow Pages publie uniquement `docs/` comme vitrine statique.
