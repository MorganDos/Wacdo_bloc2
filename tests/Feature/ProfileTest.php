<?php

use App\Models\User;

test('profile page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/profile');

    $response->assertOk();
});

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $user->refresh();

    $this->assertSame('Test User', $user->name);
    $this->assertSame('test@example.com', $user->email);
});

test('legacy role switch endpoint is no longer exposed', function () {
    $user = User::factory()->create([
        'role' => 'cashier',
    ]);

    $response = $this
        ->actingAs($user)
        ->post('/profile/switch-role', [
            'role' => 'admin',
        ]);

    $response->assertNotFound();
    $this->assertSame('cashier', $user->fresh()->role);
});
