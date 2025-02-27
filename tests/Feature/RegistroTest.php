<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);
uses(Tests\TestCase::class)->in('Feature');

it('deve registrar um usuário e retornar sucesso', function () {
    $dados = [
        'name' => 'Teste',
        'email' => 'teste@email.com',
        'password' => 'senha123',
    ];

    $response = $this->postJson('/api/registro', $dados);

    $response->assertStatus(201)
        ->assertJson(['message' => 'Registro realizado com sucesso']);

    $this->assertDatabaseHas('users', ['email' => 'teste@email.com']);
});

it('deve falhar ao registrar um usuário com dados inválidos', function () {
    $dados = [
        'name' => '',
        'email' => 'email_invalido',
        'password' => '123',
    ];

    $response = $this->postJson('/api/registro', $dados);

    $response->assertStatus(422)->assertJsonValidationErrors(['name', 'email', 'password']);
});
