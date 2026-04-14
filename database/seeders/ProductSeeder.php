<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Ajoute les produits de démonstration.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Big Mac',
                'description' => 'Burger classique avec salade, fromage et sauce.',
                'category' => 'burger',
                'price' => 6.00,
                'image' => '/img/produits/burgers/BIGMAC.png',
                'availability' => 'available',
            ],
            [
                'name' => 'Mc Chicken',
                'description' => 'Burger au poulet pané.',
                'category' => 'burger',
                'price' => 7.30,
                'image' => '/img/produits/burgers/MCCHICKEN.png',
                'availability' => 'available',
            ],
            [
                'name' => 'Moyenne Frite',
                'description' => 'Portion moyenne de frites.',
                'category' => 'frites',
                'price' => 2.75,
                'image' => '/img/produits/frites/MOYENNE_FRITE.png',
                'availability' => 'available',
            ],
            [
                'name' => 'Coca Cola',
                'description' => 'Boisson fraîche au cola.',
                'category' => 'boissons',
                'price' => 1.90,
                'image' => '/img/produits/boissons/coca-cola.png',
                'availability' => 'available',
            ],
            [
                'name' => 'Brownie',
                'description' => 'Dessert au chocolat.',
                'category' => 'desserts',
                'price' => 2.60,
                'image' => '/img/produits/desserts/brownies.png',
                'availability' => 'available',
            ],
            [
                'name' => 'Mc Wrap Poulet Bacon',
                'description' => 'Wrap au poulet avec bacon.',
                'category' => 'wraps',
                'price' => 3.30,
                'image' => '/img/produits/wraps/MCWRAP-POULET-BACON.png',
                'availability' => 'available',
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['name' => $product['name']],
                $product
            );
        }
    }
}
