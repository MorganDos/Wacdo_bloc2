<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\User;

test('cashier can create and deliver orders but cannot access admin-only areas', function () {
    $cashier = User::factory()->create(['role' => 'cashier']);
    $pendingOrder = Order::factory()->create(['status' => 'pending']);
    $readyOrder = Order::factory()->create(['status' => 'ready']);

    $this->actingAs($cashier)->get('/orders')->assertOk();
    $this->actingAs($cashier)->get('/orders/create')->assertOk();
    $this->actingAs($cashier)->get('/products')->assertForbidden();
    $this->actingAs($cashier)->get('/menus')->assertForbidden();
    $this->actingAs($cashier)->get('/users')->assertForbidden();
    $this->actingAs($cashier)->put("/orders/{$pendingOrder->id}/ready")->assertForbidden();
    $this->actingAs($cashier)->put("/orders/{$readyOrder->id}/delivered")->assertRedirect(route('orders.index'));

    expect($readyOrder->fresh()->status)->toBe('delivered');
});

test('prep can view orders and mark them ready but cannot create or deliver them', function () {
    $prep = User::factory()->create(['role' => 'prep']);
    $pendingOrder = Order::factory()->create(['status' => 'pending']);
    $readyOrder = Order::factory()->create(['status' => 'ready']);

    $this->actingAs($prep)->get('/orders')->assertOk();
    $this->actingAs($prep)->get('/orders/create')->assertForbidden();
    $this->actingAs($prep)->get("/orders/{$pendingOrder->id}/edit")->assertForbidden();
    $this->actingAs($prep)->put("/orders/{$pendingOrder->id}/ready")->assertRedirect(route('orders.index'));
    $this->actingAs($prep)->put("/orders/{$readyOrder->id}/delivered")->assertForbidden();

    expect($pendingOrder->fresh()->status)->toBe('ready');
});

test('admin can access every back-office area', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $product = Product::factory()->create(['availability' => 'available']);
    $order = Order::factory()->create(['status' => 'pending']);

    $this->actingAs($admin)->get('/products')->assertOk();
    $this->actingAs($admin)->get('/menus')->assertOk();
    $this->actingAs($admin)->get('/users')->assertOk();
    $this->actingAs($admin)->get('/orders')->assertOk();
    $this->actingAs($admin)->get("/orders/{$order->id}/edit")->assertOk();
    $this->actingAs($admin)->post('/orders', [
        'product_ids' => [$product->id],
        'quantities' => [$product->id => 1],
    ])->assertRedirect(route('orders.index'));
});
