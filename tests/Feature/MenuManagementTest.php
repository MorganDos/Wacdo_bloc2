<?php

use App\Models\Menu;
use App\Models\Product;
use App\Models\User;

test('admin can create menus with a product composition', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $burger = Product::factory()->create(['name' => 'Burger', 'category' => 'burger']);
    $drink = Product::factory()->create(['name' => 'Drink', 'category' => 'drink']);

    $response = $this->actingAs($admin)->post('/menus', [
        'name' => 'Lunch Menu',
        'description' => 'Burger and drink',
        'price' => 8.90,
        'is_active' => 1,
        'product_ids' => [$burger->id, $drink->id],
        'quantities' => [
            $burger->id => 1,
            $drink->id => 2,
        ],
    ]);

    $response->assertRedirect(route('menus.index'));

    $menu = Menu::first();

    expect($menu)->not->toBeNull();
    expect($menu->name)->toBe('Lunch Menu');
    expect($menu->is_active)->toBeTrue();

    $this->assertDatabaseHas('menu_product', [
        'menu_id' => $menu->id,
        'product_id' => $burger->id,
        'quantity' => 1,
    ]);

    $this->assertDatabaseHas('menu_product', [
        'menu_id' => $menu->id,
        'product_id' => $drink->id,
        'quantity' => 2,
    ]);
});

test('admin can update and delete menus', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $burger = Product::factory()->create(['name' => 'Burger', 'category' => 'burger']);
    $dessert = Product::factory()->create(['name' => 'Dessert', 'category' => 'dessert']);
    $menu = Menu::factory()->create([
        'name' => 'Classic Menu',
        'is_active' => true,
    ]);
    $menu->products()->attach($burger->id, ['quantity' => 1]);

    $this->actingAs($admin)->put("/menus/{$menu->id}", [
        'name' => 'Dessert Menu',
        'description' => 'Burger with dessert',
        'price' => 10.50,
        'is_active' => 0,
        'product_ids' => [$burger->id, $dessert->id],
        'quantities' => [
            $burger->id => 1,
            $dessert->id => 1,
        ],
    ])->assertRedirect(route('menus.index'));

    expect($menu->fresh()->name)->toBe('Dessert Menu');
    expect($menu->fresh()->is_active)->toBeFalse();
    $this->assertDatabaseHas('menu_product', [
        'menu_id' => $menu->id,
        'product_id' => $dessert->id,
        'quantity' => 1,
    ]);

    $this->actingAs($admin)->delete("/menus/{$menu->id}")
        ->assertRedirect(route('menus.index'));

    $this->assertDatabaseMissing('menus', [
        'id' => $menu->id,
    ]);
});
