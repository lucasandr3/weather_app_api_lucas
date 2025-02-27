<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('deve permitir logout e revogar o token', function () {

    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->postJson('/api/logout');

    $response->assertNoContent();
    expect($user->tokens)->toBeEmpty();
});
