<?php

use App\Models\User;

test('returns a successful response', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});
