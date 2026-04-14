<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\User;

it('can update product availability', function () {
    $product = Product::factory()->create([
        'availability' => 'available',
    ]);

    $product->update([
        'availability' => 'out_of_stock',
    ]);

    expect($product->fresh()->availability)->toBe('out_of_stock');
});

it('does not delete products that are already used in an order', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $product = Product::factory()->create();
    $order = Order::factory()->create();

    $order->products()->attach($product->id, ['quantity' => 1]);

    $this->actingAs($admin)
        ->delete("/products/{$product->id}")
        ->assertRedirect(route('products.index'))
        ->assertSessionHas('error');

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
    ]);
});
