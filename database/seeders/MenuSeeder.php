<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Product;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Ajoute les menus de démonstration.
     */
    public function run(): void
    {
        $classicBurger = Product::where('name', 'Big Mac')->first();
        $chickenBurger = Product::where('name', 'Mc Chicken')->first();
        $fries = Product::where('name', 'Moyenne Frite')->first();
        $cola = Product::where('name', 'Coca Cola')->first();
        $dessert = Product::where('name', 'Brownie')->first();

        if (! $classicBurger || ! $chickenBurger || ! $fries || ! $cola || ! $dessert) {
            return;
        }

        $menus = [
            [
                'name' => 'Classic Menu',
                'description' => 'Burger classique, frites et cola.',
                'price' => 10.90,
                'products' => [
                    $classicBurger->id => ['quantity' => 1],
                    $fries->id => ['quantity' => 1],
                    $cola->id => ['quantity' => 1],
                ],
            ],
            [
                'name' => 'Chicken Menu',
                'description' => 'Burger au poulet, frites et cola.',
                'price' => 11.40,
                'products' => [
                    $chickenBurger->id => ['quantity' => 1],
                    $fries->id => ['quantity' => 1],
                    $cola->id => ['quantity' => 1],
                ],
            ],
            [
                'name' => 'Dessert Menu',
                'description' => 'Burger classique avec dessert et cola.',
                'price' => 12.50,
                'products' => [
                    $classicBurger->id => ['quantity' => 1],
                    $dessert->id => ['quantity' => 1],
                    $cola->id => ['quantity' => 1],
                ],
            ],
        ];

        foreach ($menus as $menuData) {
            $products = $menuData['products'];
            unset($menuData['products']);

            $menu = Menu::updateOrCreate(
                ['name' => $menuData['name']],
                $menuData + ['is_active' => true]
            );

            $menu->products()->sync($products);
        }
    }
}
