<?php

use App\Models\User;

test('returns a successful response', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can access the modules page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/modules');
    dd($response);
    $response->assertStatus(200);
});
