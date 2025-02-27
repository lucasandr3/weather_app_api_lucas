<?php

use App\Http\Controllers\Autenticacao\AutenticacaoController;
use App\Http\Requests\Autenticacao\LoginRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('deve autenticar usuário com credenciais válidas', function () {
    $user = User::factory()->create([
        'email' => 'teste@email.com',
        'password' => Hash::make('senha123'),
    ]);

    $request = Request::create('/login', 'POST', [
        'email' => 'teste@email.com',
        'password' => 'senha123',
    ]);

    $controller = new AutenticacaoController();
    $response = $controller->login($request);

    expect($response->getStatusCode())->toBe(200);
    expect(json_decode($response->getContent()))->toHaveKeys(['token', 'user']);
});

it('deve retornar erro para credenciais inválidas', function () {
    User::factory()->create([
        'email' => 'teste@email.com',
        'password' => bcrypt('senha123'),
    ]);

    $request = LoginRequest::create('/login', 'POST', [
        'email' => 'teste@email.com',
        'password' => 'senhaerrada',
    ]);

    $controller = new AutenticacaoController();
    $response = $controller->login($request);

    expect($response->getStatusCode())->toBe(401);
    expect(json_decode($response->getContent()))->toHaveKey('message');
});
