<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);
uses(Tests\TestCase::class)->in('Feature');

it('deve permitir login com credenciais corretas', function () {
    $user = User::factory()->create([
        'email' => 'teste@email.com',
        'password' => bcrypt('senha123'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'teste@email.com',
        'password' => 'senha123',
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure(['token', 'user']);
});

it('deve retornar erro para login inválido', function () {
    $user = User::factory()->create([
        'email' => 'teste@email.com',
        'password' => bcrypt('senha123'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'teste@email.com',
        'password' => 'senhaerrada',
    ]);

    $response->assertStatus(401);
    $response->assertJson(['message' => 'Dados Inválidos.']);
});
