<?php

use App\Models\Product;

test('api can store an order with available products', function () {
    $product = Product::factory()->create([
        'availability' => 'available',
        'category' => 'burger',
    ]);

    $response = $this->postJson('/api/orders', [
        'products' => [
            [
                'product_id' => $product->id,
                'quantity' => 2,
            ],
        ],
    ]);

    $response
        ->assertCreated()
        ->assertJsonPath('success', true)
        ->assertJsonPath('status', 'pending');

    $this->assertDatabaseHas('order_product', [
        'product_id' => $product->id,
        'quantity' => 2,
    ]);
});

test('api rejects unavailable products', function () {
    $product = Product::factory()->create([
        'availability' => 'out_of_stock',
        'category' => 'dessert',
    ]);

    $response = $this->postJson('/api/orders', [
        'products' => [
            [
                'product_id' => $product->id,
                'quantity' => 1,
            ],
        ],
    ]);

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors('products.0.product_id');
});
