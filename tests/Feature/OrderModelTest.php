<?php

use App\Models\Order;
use App\Models\Product;

it('stores several products on an order', function () {
    $order = Order::factory()->create();
    $productA = Product::factory()->create();
    $productB = Product::factory()->create();

    $order->products()->attach([
        $productA->id => ['quantity' => 1],
        $productB->id => ['quantity' => 3],
    ]);

    expect($order->fresh()->products()->count())->toBe(2);
    $this->assertDatabaseHas('order_product', [
        'order_id' => $order->id,
        'product_id' => $productB->id,
        'quantity' => 3,
    ]);
});
