<?php

use App\Models\Menu;
use App\Models\Product;

it('returns active menus in the api', function () {
    $burger = Product::factory()->create([
        'name' => 'Burger',
        'category' => 'burger',
        'availability' => 'available',
        'price' => 6.50,
    ]);

    $drink = Product::factory()->create([
        'name' => 'Drink',
        'category' => 'drink',
        'availability' => 'available',
        'price' => 2.50,
    ]);

    $menu = Menu::create([
        'name' => 'Lunch Menu',
        'description' => 'Burger and drink.',
        'price' => 8.90,
        'is_active' => true,
    ]);

    $menu->products()->attach($burger->id, ['quantity' => 1]);
    $menu->products()->attach($drink->id, ['quantity' => 1]);

    $this->getJson('/api/menus')
        ->assertOk()
        ->assertJsonFragment([
            'name' => 'Lunch Menu',
            'price' => 8.90,
        ])
        ->assertJsonFragment([
            'name' => 'Burger',
            'category' => 'burger',
            'quantity' => 1,
        ]);
});
