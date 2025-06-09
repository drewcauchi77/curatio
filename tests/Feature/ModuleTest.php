<?php

use App\Models\User;

describe('company owners', function () {
    test('can access the modules list page', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/modules');

        $response->assertStatus(200);
    });

    test('can access the module creation page', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/modules/create');

        $response->assertStatus(200);
    });
});

describe('users without company', function () {
    test('cannot access the modules list page', function () {
        $user = User::factory()->withoutCompany()->create();
        $this->actingAs($user);

        $response = $this->get('/modules');

        $response->assertStatus(403);
    });

    test('cannot access the module creation page', function () {
        $user = User::factory()->withoutCompany()->create();
        $this->actingAs($user);

        $response = $this->get('/modules/create');

        $response->assertStatus(403);
    });
});
