<?php

use App\Models\User;

test('api demo page requires authentication', function () {
    $this->get('/api-demo')
        ->assertRedirect('/login');
});

test('authenticated users can view the api demo page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/api-demo')
        ->assertOk()
        ->assertSee('Démo API');
});
