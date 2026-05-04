# Wakdo Back Office

Application Laravel réalisée pour le Bloc 2.

Le projet correspond au back-office interne de Wakdo. Il permet de :

- gérer les produits
- gérer les menus
- gérer les comptes internes
- suivre les commandes côté restaurant
- exposer une petite API pour la borne de commande

## Stack

- PHP 8.2
- Laravel 12
- Blade
- Eloquent ORM
- MySQL
- Tailwind CSS
- Vite
- Pest

## Installation locale

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

## Comptes démo

- `admin@wakdo.test` / `password`
- `prep@wakdo.test` / `password`
- `cashier@wakdo.test` / `password`

## API

L'API n'est pas appelée par le back-office Blade, mais elle utilise les mêmes modèles et la même base de données.

Routes disponibles :

- `GET /api/products` : liste les produits disponibles.
- `GET /api/products?category=burger` : filtre les produits par catégorie.
- `GET /api/menus` : liste les menus actifs avec leur composition.
- `POST /api/orders` : crée une commande en attente.

Une page de test est disponible après connexion : /api-demo

## Tests

php artisan test

## Documents de rendu

- `docs/mcd-wdo.png`
- `docs/mpd-wdo.png`
- `docs/flux-commande.png`
- `docs/flux-roles-wdo.png`
- `docs/dump-wakdo_db-full.sql`

## URL

- URL de l'application déployée : http://wdobackoffice.great-site.net/
- URL du dépôt public : https://github.com/MorganDos/Wacdo_bloc2
