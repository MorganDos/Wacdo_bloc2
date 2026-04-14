<?php

use App\Models\User;

it('stores the selected user role', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $prep = User::factory()->create(['role' => 'prep']);
    $cashier = User::factory()->create(['role' => 'cashier']);

    expect($admin->role)->toBe('admin')
        ->and($prep->role)->toBe('prep')
        ->and($cashier->role)->toBe('cashier');
});
