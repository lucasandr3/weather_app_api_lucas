<?php
use App\Models\User;
use App\Models\WeatherQueries;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('index retorna dados corretos via API', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    WeatherQueries::create(['user_id' => 1, 'cidade' => 'São Paulo', 'response' => []]);
    WeatherQueries::create(['user_id' => 1, 'cidade' => 'Rio de Janeiro', 'response' => []]);
    WeatherQueries::create(['user_id' => 1, 'cidade' => 'São Paulo', 'response' => []]); // Duplicada

    $response = $this->getJson('api/dashboard');
    $response->assertStatus(200)
        ->assertJsonStructure([
            'usuarios',
            'consultas',
            'cidades'
        ]);
});
