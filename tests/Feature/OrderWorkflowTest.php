<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\User;

test('cashier can create pending orders with product quantities', function () {
    $cashier = User::factory()->create(['role' => 'cashier']);
    $product = Product::factory()->create([
        'availability' => 'available',
        'category' => 'burger',
    ]);

    $response = $this->actingAs($cashier)->post('/orders', [
        'delivery_at' => now()->addHour()->toDateTimeString(),
        'product_ids' => [$product->id],
        'quantities' => [
            $product->id => 2,
        ],
    ]);

    $response->assertRedirect(route('orders.index'));

    $order = Order::first();

    expect($order->status)->toBe('pending');
    expect($order->ticket_number)->not->toBeEmpty();
    $this->assertDatabaseHas('order_product', [
        'order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => 2,
    ]);
});

test('pending orders can be updated by a cashier', function () {
    $cashier = User::factory()->create(['role' => 'cashier']);
    $burger = Product::factory()->create([
        'availability' => 'available',
        'category' => 'burger',
    ]);
    $drink = Product::factory()->create([
        'availability' => 'available',
        'category' => 'drink',
    ]);
    $order = Order::factory()->create(['status' => 'pending']);
    $order->products()->attach($burger->id, ['quantity' => 1]);

    $this->actingAs($cashier)->put("/orders/{$order->id}", [
        'delivery_at' => now()->addHours(2)->toDateTimeString(),
        'product_ids' => [$burger->id, $drink->id],
        'quantities' => [
            $burger->id => 3,
            $drink->id => 1,
        ],
    ])->assertRedirect(route('orders.index'));

    $this->assertDatabaseHas('order_product', [
        'order_id' => $order->id,
        'product_id' => $burger->id,
        'quantity' => 3,
    ]);

    $this->assertDatabaseHas('order_product', [
        'order_id' => $order->id,
        'product_id' => $drink->id,
        'quantity' => 1,
    ]);
});

test('unavailable products cannot be added to a new order', function () {
    $cashier = User::factory()->create(['role' => 'cashier']);
    $unavailable = Product::factory()->create([
        'availability' => 'out_of_stock',
        'category' => 'dessert',
    ]);

    $response = $this->actingAs($cashier)->from('/orders/create')->post('/orders', [
        'product_ids' => [$unavailable->id],
        'quantities' => [
            $unavailable->id => 1,
        ],
    ]);

    $response->assertRedirect('/orders/create');
    $response->assertSessionHasErrors('product_ids');
    $this->assertDatabaseCount('orders', 0);
});

test('ready orders can not be edited any more', function () {
    $cashier = User::factory()->create(['role' => 'cashier']);
    $order = Order::factory()->create(['status' => 'ready']);

    $this->actingAs($cashier)->get("/orders/{$order->id}/edit")
        ->assertRedirect(route('orders.index'));
});
