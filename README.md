# Wakdo Back Office

Projet Laravel réalisé pour le Bloc 2.

L'application sert de back-office interne pour Wakdo. Elle permet de gérer le catalogue, les menus, les comptes internes et le suivi des commandes côté restaurant. Une petite API JSON est aussi disponible pour simuler la communication avec une borne de commande.

## Fonctionnalités

- gestion des produits : nom, description, catégorie, prix, image et disponibilité
- gestion des menus et de leur composition
- gestion des utilisateurs internes
- rôles `admin`, `prep` et `cashier`
- création et suivi des commandes
- API pour lire les produits, lire les menus et créer une commande

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

L'API n'est pas appelée par le back-office Blade. Elle est prévue pour un front externe, par exemple une borne de commande. Elle utilise les mêmes modèles et la même base de données que le back-office.

- `GET /api/products` : liste les produits disponibles
- `GET /api/products?category=burger` : filtre les produits par catégorie
- `GET /api/menus` : liste les menus actifs avec leur composition
- `POST /api/orders` : crée une commande en attente

Une page de vérification est disponible après connexion : /api-demo


## Tests

php artisan test

## Documents de rendu

- `docs/mpd-wdo.png`
- `docs/flux-commande.png`
- `docs/flux-roles-wdo.png`
- `docs/dump-wakdo_db-full.sql`

## URL

- URL de l'application déployée : http://wdobackoffice.great-site.net/
- URL du dépôt public : https://github.com/MorganDos/Wacdo_bloc2
