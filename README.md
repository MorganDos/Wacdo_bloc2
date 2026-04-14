# Wakdo Back Office

Application Laravel realisée pour le Bloc 2.

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

## Tests

```bash
php artisan test
```

## Documents de rendu

- `docs/mcd-wdo.png`
- `docs/mpd-wdo.png`
- `docs/flux-commande.png`
- `docs/flux-roles-wdo.png`
- `docs/dump-wakdo_db-full.sql`

## URL

- URL de l'application déployée : http://wdobackoffice.great-site.net/
- URL du dépot public : https://github.com/MorganDos/Wacdo_bloc2
