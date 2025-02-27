<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Event;
use App\Events\HistoricoConsulta;
use App\Events\HistoricoErroConsulta;
use Laravel\Sanctum\Sanctum;

it('retorna erro se a API estiver indisponível', function () {

    $user = User::factory()->create();
    Sanctum::actingAs($user);

    Http::fake([
        'https://api.openweathermap.org/data/2.5/weather*' => Http::response([], 500),
    ]);

    Event::fake();

    $response = $this->actingAs($user)->getJson('/api/weather?cidade=São Paulo');

    $response->assertStatus(500);
    $response->assertJsonStructure(['erro', 'message', 'status', 'response']);

    Event::assertDispatched(HistoricoErroConsulta::class);
});

it('retorna dados meteorológicos corretamente', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    Http::fake([
        'https://api.openweathermap.org/data/2.5/weather*' => Http::response([
            'weather' => [['description' => 'céu limpo', 'icon' => '01d']],
            'main' => [
                'temp' => 25,
                'feels_like' => 27,
                'temp_min' => 22,
                'temp_max' => 28,
                'humidity' => 60
            ],
            'wind' => ['speed' => 5.5],
            'sys' => ['country' => 'BR'],
            'name' => 'São Paulo'
        ], 200),
    ]);

    Event::fake();

    $response = $this->getJson('/api/weather?cidade=São Paulo');

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'descricao',
        'icone',
        'temperatura',
        'sensacao_termica',
        'temp_min',
        'temp_max',
        'humidade',
        'vento',
        'cidade',
        'pais'
    ]);

    Event::assertDispatched(HistoricoConsulta::class);
});
