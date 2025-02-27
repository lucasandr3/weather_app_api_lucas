<?php

use App\Models\User;
use App\Service\OpenWeatherMapService;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Fluent;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

uses(TestCase::class);

it('retorna erro quando a API falha', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    Http::fake([
        'https://api.openweathermap.org/data/2.5/weather*' => Http::response([], 500),
    ]);

    $service = new OpenWeatherMapService();
    $resultado = $service->getWeather('São Paulo');

    expect($resultado->get('erro'))->toBeTrue();
    expect($resultado->get('status'))->toBe(500);
});

it('retorna dados meteorológicos quando a API responde corretamente', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    Http::fake([
        'https://api.openweathermap.org/data/2.5/weather*' => Http::response([
            'main' => ['temp' => 25],
            'weather' => [['description' => 'Céu limpo']]
        ], 200),
    ]);

    $service = new OpenWeatherMapService();
    $resultado = $service->getWeather('São Paulo');

    expect($resultado->get('erro'))->toBeFalse();
    expect($resultado->get('status'))->toBe(200);
    expect($resultado->get('response'))->toHaveKeys(['main', 'weather']);
});
